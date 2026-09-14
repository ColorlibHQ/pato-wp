<?php
/**
 * The reservation panel.
 *
 * A restaurant theme lives or dies on this form, so Pato ships one rather than
 * requiring a plugin for the single thing every visitor came to do.
 *
 * It is a **shortcode**, not inline PHP in the pattern. That is not a style
 * preference: inc/front-page-setup.php expands patterns into real post content
 * so the copy stays editable, and PHP inside stored post content never runs.
 * A pattern that rendered the form inline would freeze whatever it produced at
 * activation into the page forever.
 *
 * A theme must not create database tables or register a post type, so Pato
 * stores nothing. It validates, then hands the booking to whoever wants it:
 *
 *   - `pato_reservation_handlers` — return true from any handler to say the
 *     booking has been dealt with, and the built-in email is skipped. This is
 *     where a booking plugin, a CRM or a webhook hooks in.
 *   - `pato_reservation_email_to` / `_subject` / `_body` — adjust the email
 *     the theme sends when nothing else claims the booking.
 *   - `pato_reservation_fields` — add, remove or relabel fields.
 *
 * The form works with JavaScript off: it is a plain POST to the same URL,
 * answered with a redirect carrying the result. Nothing here depends on the
 * Interactivity API or on a bundler.
 *
 * @package Pato
 */

defined( 'ABSPATH' ) || exit;

const PATO_RESERVATION_ACTION = 'pato_reservation';

/**
 * The fields the form asks for.
 *
 * @return array<string, array<string, mixed>>
 */
function pato_reservation_fields() {
	$fields = array(
		'name'    => array(
			'label'        => __( 'Your name', 'pato' ),
			'type'         => 'text',
			'autocomplete' => 'name',
			'required'     => true,
		),
		'email'   => array(
			'label'        => __( 'Email address', 'pato' ),
			'type'         => 'email',
			'autocomplete' => 'email',
			'required'     => true,
		),
		'phone'   => array(
			'label'        => __( 'Phone number', 'pato' ),
			'type'         => 'tel',
			'autocomplete' => 'tel',
			'required'     => false,
		),
		'date'    => array(
			'label'    => __( 'Date', 'pato' ),
			'type'     => 'date',
			'required' => true,
		),
		'time'    => array(
			'label'    => __( 'Time', 'pato' ),
			'type'     => 'time',
			'required' => true,
		),
		'people'  => array(
			'label'    => __( 'Number of people', 'pato' ),
			'type'     => 'number',
			'min'      => 1,
			'max'      => 30,
			'required' => true,
		),
		'message' => array(
			'label'    => __( 'Anything we should know?', 'pato' ),
			'type'     => 'textarea',
			'required' => false,
		),
	);

	/**
	 * Filters the reservation form fields.
	 *
	 * @param array $fields Field definitions keyed by name.
	 */
	return apply_filters( 'pato_reservation_fields', $fields );
}

/**
 * Render one field, label included.
 *
 * Labels are real <label for> elements, always. A placeholder is not a label:
 * it is unreadable to some screen readers and it disappears the moment the
 * field has content.
 *
 * @param string $name  Field name.
 * @param array  $field Field definition.
 * @param array  $sent  Previously submitted values, to repopulate on error.
 * @return string
 */
function pato_reservation_field( $name, $field, $sent = array() ) {
	$id       = 'pato-reservation-' . $name;
	$value    = isset( $sent[ $name ] ) ? $sent[ $name ] : '';
	$required = ! empty( $field['required'] );

	$attributes = array(
		'id'    => $id,
		'name'  => $name,
		'class' => 'pato-field__control',
	);

	if ( $required ) {
		$attributes['required'] = 'required';
	}
	if ( ! empty( $field['autocomplete'] ) ) {
		$attributes['autocomplete'] = $field['autocomplete'];
	}
	if ( isset( $field['min'] ) ) {
		$attributes['min'] = $field['min'];
	}
	if ( isset( $field['max'] ) ) {
		$attributes['max'] = $field['max'];
	}

	$out = '<p class="pato-field pato-field--' . esc_attr( $name ) . '">';
	$out .= '<label class="pato-field__label" for="' . esc_attr( $id ) . '">' . esc_html( $field['label'] );
	if ( $required ) {
		$out .= ' <span class="pato-field__required" aria-hidden="true">*</span>';
	}
	$out .= '</label>';

	if ( 'textarea' === $field['type'] ) {
		$attributes['rows'] = 4;
		$out               .= '<textarea' . pato_attributes( $attributes ) . '>' . esc_textarea( $value ) . '</textarea>';
	} else {
		$attributes['type']  = $field['type'];
		$attributes['value'] = $value;
		$out                .= '<input' . pato_attributes( $attributes ) . '>';
	}

	return $out . '</p>';
}

/**
 * Build an attribute string from a map, escaping every value.
 *
 * @param array $attributes Attribute map.
 * @return string
 */
function pato_attributes( $attributes ) {
	$out = '';
	foreach ( $attributes as $key => $value ) {
		if ( '' === $value && 'value' !== $key ) {
			continue;
		}
		$out .= ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
	}
	return $out;
}

/**
 * The reservation form.
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function pato_reservation_form( $atts = array() ) {
	$atts = shortcode_atts(
		array(
			'button' => __( 'Book a table', 'pato' ),
		),
		$atts,
		'pato_reservation_form'
	);

	$notice = pato_reservation_notice();
	$sent   = array();

	$out  = '<form class="pato-reservation" method="post" action="' . esc_url( pato_current_url() ) . '#pato-reservation">';
	$out .= '<div id="pato-reservation" class="pato-reservation__anchor"></div>';
	$out .= $notice;
	$out .= wp_nonce_field( PATO_RESERVATION_ACTION, 'pato_reservation_nonce', true, false );
	$out .= '<input type="hidden" name="action" value="' . esc_attr( PATO_RESERVATION_ACTION ) . '">';

	// The page to come back to, carried explicitly.
	//
	// wp_get_referer() cannot do this job: it returns false whenever the
	// referer matches the current request URI, which is always the case for a
	// form that posts to its own page. Falling back to home_url() then dumps
	// the guest on the front page with a booking confirmation and no form in
	// sight. Validated with wp_validate_redirect() on the way back out, so a
	// crafted value cannot send anyone off-site.
	$out .= '<input type="hidden" name="pato_redirect" value="' . esc_url( pato_current_url() ) . '">';

	// A field no visitor sees and no visitor fills in. Bots fill everything.
	$out .= '<p class="pato-reservation__trap" aria-hidden="true">';
	$out .= '<label for="pato-reservation-website">' . esc_html__( 'Leave this field empty', 'pato' ) . '</label>';
	$out .= '<input id="pato-reservation-website" type="text" name="pato_website" tabindex="-1" autocomplete="off">';
	$out .= '</p>';

	$out .= '<div class="pato-reservation__grid">';
	foreach ( pato_reservation_fields() as $name => $field ) {
		$out .= pato_reservation_field( $name, $field, $sent );
	}
	$out .= '</div>';

	$out .= '<p class="pato-reservation__actions">';
	$out .= '<button type="submit" class="wp-block-button__link wp-element-button">' . esc_html( $atts['button'] ) . '</button>';
	$out .= '</p>';

	$out .= '</form>';

	return $out;
}
add_shortcode( 'pato_reservation_form', 'pato_reservation_form' );

/**
 * The current URL, without any previous result parameter.
 *
 * @return string
 */
function pato_current_url() {
	$permalink = get_permalink();
	if ( ! $permalink ) {
		$permalink = home_url( '/' );
	}
	return remove_query_arg( array( 'pato-booking' ), $permalink );
}

/**
 * The message shown after a submission, if there is one.
 *
 * @return string
 */
function pato_reservation_notice() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display only.
	$result = isset( $_GET['pato-booking'] ) ? sanitize_key( wp_unslash( $_GET['pato-booking'] ) ) : '';

	$messages = array(
		'sent'    => array( 'ok', __( 'Thank you — your request is with us. We will confirm by email shortly.', 'pato' ) ),
		'invalid' => array( 'error', __( 'Please check the form: we still need a name, an email address, a date, a time and how many of you there are.', 'pato' ) ),
		'email'   => array( 'error', __( 'That email address does not look right.', 'pato' ) ),
		'failed'  => array( 'error', __( 'Sorry, the request could not be sent. Please call us instead.', 'pato' ) ),
		'expired' => array( 'error', __( 'That form had been open a while and expired. Please send it again.', 'pato' ) ),
	);

	if ( ! isset( $messages[ $result ] ) ) {
		return '';
	}

	list( $kind, $text ) = $messages[ $result ];

	return '<p class="pato-reservation__notice is-' . esc_attr( $kind ) . '" role="status">' . esc_html( $text ) . '</p>';
}

/**
 * Handle a submitted reservation.
 *
 * Runs on `template_redirect` so it can redirect before anything is sent —
 * the POST/redirect/GET that stops a refresh re-booking the table.
 */
function pato_handle_reservation() {
	if ( 'POST' !== ( isset( $_SERVER['REQUEST_METHOD'] ) ? $_SERVER['REQUEST_METHOD'] : '' ) ) {
		return;
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- checked immediately below.
	if ( ! isset( $_POST['action'] ) || PATO_RESERVATION_ACTION !== $_POST['action'] ) {
		return;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce checked below; this only selects where to redirect.
	$posted   = isset( $_POST['pato_redirect'] ) ? esc_url_raw( wp_unslash( $_POST['pato_redirect'] ) ) : '';
	$redirect = wp_validate_redirect( $posted, home_url( '/' ) );
	$redirect = remove_query_arg( array( 'pato-booking' ), $redirect );

	$nonce = isset( $_POST['pato_reservation_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['pato_reservation_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, PATO_RESERVATION_ACTION ) ) {
		pato_reservation_redirect( $redirect, 'expired' );
	}

	// Silently accept and discard anything that filled the honeypot: telling a
	// bot it failed only teaches it to try again differently.
	if ( ! empty( $_POST['pato_website'] ) ) {
		pato_reservation_redirect( $redirect, 'sent' );
	}

	$booking = array();
	foreach ( pato_reservation_fields() as $name => $field ) {
		$raw = isset( $_POST[ $name ] ) ? wp_unslash( $_POST[ $name ] ) : '';
		$raw = is_string( $raw ) ? $raw : '';

		if ( 'textarea' === $field['type'] ) {
			$value = sanitize_textarea_field( $raw );
		} elseif ( 'email' === $field['type'] ) {
			$value = sanitize_email( $raw );
		} else {
			$value = sanitize_text_field( $raw );
		}

		if ( ! empty( $field['required'] ) && '' === $value ) {
			pato_reservation_redirect( $redirect, 'invalid' );
		}

		$booking[ $name ] = $value;
	}

	if ( ! empty( $booking['email'] ) && ! is_email( $booking['email'] ) ) {
		pato_reservation_redirect( $redirect, 'email' );
	}

	/**
	 * Filters whether the booking has already been handled.
	 *
	 * Return true from any handler and Pato will not send its own email —
	 * which is how a booking plugin, a CRM or a webhook takes this over.
	 *
	 * @param bool  $handled Whether something has dealt with the booking.
	 * @param array $booking The sanitised booking.
	 */
	$handled = apply_filters( 'pato_reservation_handlers', false, $booking );

	if ( ! $handled ) {
		$handled = pato_reservation_email( $booking );
	}

	pato_reservation_redirect( $redirect, $handled ? 'sent' : 'failed' );
}
add_action( 'template_redirect', 'pato_handle_reservation' );

/**
 * Redirect back to the form with a result, and stop.
 *
 * @param string $url    Where to go.
 * @param string $result Result key.
 */
function pato_reservation_redirect( $url, $result ) {
	wp_safe_redirect( add_query_arg( 'pato-booking', $result, $url ) . '#pato-reservation', 303 );
	exit;
}

/**
 * Email the booking to the site's admin address.
 *
 * From: is the site's own address, never the visitor's. Putting the visitor
 * there fails SPF and DMARC — the mail is sent by this server, not by their
 * provider — and it is the classic route to header injection. Reply-To carries
 * them instead, and wp_mail() rejects a header containing a newline.
 *
 * @param array $booking Sanitised booking.
 * @return bool
 */
function pato_reservation_email( $booking ) {
	$to = apply_filters( 'pato_reservation_email_to', get_option( 'admin_email' ) );

	if ( ! $to || ! is_email( $to ) ) {
		return false;
	}

	/* translators: %s: site name. */
	$subject = sprintf( __( '[%s] Table request', 'pato' ), wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) );
	$subject = apply_filters( 'pato_reservation_email_subject', $subject, $booking );

	$lines  = array();
	$fields = pato_reservation_fields();
	foreach ( $booking as $name => $value ) {
		if ( '' === $value ) {
			continue;
		}
		$label   = isset( $fields[ $name ]['label'] ) ? $fields[ $name ]['label'] : $name;
		$lines[] = $label . ': ' . $value;
	}

	$body = implode( "\n", $lines );
	$body = apply_filters( 'pato_reservation_email_body', $body, $booking );

	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	if ( ! empty( $booking['email'] ) && is_email( $booking['email'] ) ) {
		$headers[] = 'Reply-To: ' . $booking['email'];
	}

	return (bool) wp_mail( $to, $subject, $body, $headers );
}
