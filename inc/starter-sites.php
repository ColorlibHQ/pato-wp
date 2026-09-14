<?php
/**
 * One-click starter sites.
 *
 * Six venues — bistro, fine dining, café, pizzeria, bar and bakery — each a
 * front page, a palette and a type pairing. They exist because "a restaurant
 * theme" is six different briefs wearing one coat: a bar's first screen asks
 * for a table at eleven at night, a bakery's says what came out of the oven.
 * The sections are the same; the order and the copy are not.
 *
 * Importing **expands the pattern into real post content** rather than
 * referencing it, so every word is editable in the editor afterwards. That is
 * also why the reservation form is a shortcode: PHP inside stored post content
 * never runs, so an inline form would freeze whatever it produced at import.
 *
 * Nothing here is destructive. Importing creates a new page and points the
 * front page at it; it never edits or deletes an existing one, so a site can
 * try all six and keep whichever it likes.
 *
 * @package Pato
 */

defined( 'ABSPATH' ) || exit;

/**
 * The starters on offer.
 *
 * Keys match the `demo-{slug}` patterns written by .dev/build_patterns.py.
 *
 * @return array<string, array<string, string>>
 */
function pato_starter_sites() {
	return array(
		'bistro'      => array(
			'name'        => __( 'Bistro', 'pato' ),
			'description' => __( 'A neighbourhood restaurant: menu, reviews and a booking form.', 'pato' ),
			'palette'     => 'colors-1-ember',
			'typography'  => 'type-1-montserrat',
		),
		'fine-dining' => array(
			'name'        => __( 'Fine dining', 'pato' ),
			'description' => __( 'One tasting menu, press quotes and private hire. Dark palette.', 'pato' ),
			'palette'     => 'colors-7-cellar',
			'typography'  => 'type-3-classic',
		),
		'cafe'        => array(
			'name'        => __( 'Café', 'pato' ),
			'description' => __( 'All-day coffee and cake, with gift cards and opening times.', 'pato' ),
			'palette'     => 'colors-5-harvest',
			'typography'  => 'type-2-poppins',
		),
		'pizzeria'    => array(
			'name'        => __( 'Pizzeria', 'pato' ),
			'description' => __( 'Menu first, then delivery and collection.', 'pato' ),
			'palette'     => 'colors-2-olive',
			'typography'  => 'type-2-poppins',
		),
		'bar'         => array(
			'name'        => __( 'Bar', 'pato' ),
			'description' => __( 'Drinks, events and late opening. Dark palette.', 'pato' ),
			'palette'     => 'colors-6-midnight',
			'typography'  => 'type-1-montserrat',
		),
		'bakery'      => array(
			'name'        => __( 'Bakery', 'pato' ),
			'description' => __( 'Counter menu, specials and gift cards.', 'pato' ),
			'palette'     => 'colors-5-harvest',
			'typography'  => 'type-2-poppins',
		),
	);
}

/**
 * Import one starter.
 *
 * @param string $slug Starter key.
 * @return int|WP_Error New page ID.
 */
function pato_import_starter( $slug ) {
	$starters = pato_starter_sites();

	if ( ! isset( $starters[ $slug ] ) ) {
		return new WP_Error( 'pato_unknown_starter', __( 'That starter does not exist.', 'pato' ) );
	}

	$content = pato_pattern_content( 'pato/demo-' . $slug );

	if ( '' === $content ) {
		return new WP_Error( 'pato_missing_pattern', __( 'That starter’s pattern is not registered.', 'pato' ) );
	}

	$starter = $starters[ $slug ];

	$id = wp_insert_post(
		array(
			'post_title'   => $starter['name'],
			'post_content' => $content,
			'post_status'  => 'publish',
			'post_type'    => 'page',
		),
		true
	);

	if ( is_wp_error( $id ) ) {
		return $id;
	}

	update_post_meta( $id, '_wp_page_template', 'page-no-title' );

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $id );

	pato_apply_style_variation( $starter['palette'] );
	pato_apply_style_variation( $starter['typography'] );

	return $id;
}

/**
 * Merge a style variation into the site's own global styles.
 *
 * Merged rather than replaced, so importing a starter does not throw away a
 * colour or a font someone has already chosen in the Site Editor — and so
 * applying the palette and then the typography does not undo the palette.
 *
 * @param string $slug File name under styles/ (without .json).
 * @return bool
 */
function pato_apply_style_variation( $slug ) {
	$candidates = array(
		get_theme_file_path( 'styles/colors/' . $slug . '.json' ),
		get_theme_file_path( 'styles/typography/' . $slug . '.json' ),
		get_theme_file_path( 'styles/' . $slug . '.json' ),
	);

	$path = '';
	foreach ( $candidates as $candidate ) {
		if ( file_exists( $candidate ) ) {
			$path = $candidate;
			break;
		}
	}

	if ( '' === $path ) {
		return false;
	}

	$variation = json_decode( (string) file_get_contents( $path ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	if ( ! is_array( $variation ) ) {
		return false;
	}

	$post_id = WP_Theme_JSON_Resolver::get_user_global_styles_post_id();
	if ( ! $post_id ) {
		return false;
	}

	$existing = json_decode( (string) get_post_field( 'post_content', $post_id ), true );
	if ( ! is_array( $existing ) ) {
		$existing = array( 'version' => 3, 'isGlobalStylesUserThemeJSON' => true );
	}

	foreach ( array( 'settings', 'styles' ) as $key ) {
		if ( isset( $variation[ $key ] ) ) {
			$existing[ $key ] = array_replace_recursive(
				isset( $existing[ $key ] ) ? $existing[ $key ] : array(),
				$variation[ $key ]
			);
		}
	}

	$existing['isGlobalStylesUserThemeJSON'] = true;
	$existing['version'] = isset( $variation['version'] ) ? $variation['version'] : 3;

	// wp_slash() is not optional here. wp_update_post() expects slashed data
	// and unslashes what it is given, so JSON handed over raw comes back with
	// its backslashes eaten — every `\/` and `\u00e9` mangled — and the stored
	// global styles stop being parseable JSON at all. The symptom is not an
	// error: the palette simply never applies, and the site keeps the theme
	// defaults while the post looks the right size.
	wp_update_post(
		array(
			'ID'           => $post_id,
			'post_content' => wp_slash( wp_json_encode( $existing ) ),
		)
	);

	return true;
}

/**
 * The Appearance → Starter sites screen.
 */
function pato_starter_menu() {
	add_theme_page(
		__( 'Starter sites', 'pato' ),
		__( 'Starter sites', 'pato' ),
		'edit_theme_options',
		'pato-starters',
		'pato_render_starter_page'
	);
}
add_action( 'admin_menu', 'pato_starter_menu' );

/**
 * Render the screen, and handle an import.
 */
function pato_render_starter_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'You cannot do that.', 'pato' ) );
	}

	$notice = '';

	if ( isset( $_POST['pato_starter'] ) ) {
		check_admin_referer( 'pato_import_starter' );

		$slug   = sanitize_key( wp_unslash( $_POST['pato_starter'] ) );
		$result = pato_import_starter( $slug );

		if ( is_wp_error( $result ) ) {
			$notice = '<div class="notice notice-error"><p>' . esc_html( $result->get_error_message() ) . '</p></div>';
		} else {
			$notice = sprintf(
				'<div class="notice notice-success"><p>%s <a href="%s">%s</a></p></div>',
				esc_html__( 'Imported, and set as the front page.', 'pato' ),
				esc_url( get_permalink( $result ) ),
				esc_html__( 'View it', 'pato' )
			);
		}
	}

	echo '<div class="wrap">';
	echo '<h1>' . esc_html__( 'Starter sites', 'pato' ) . '</h1>';
	echo '<p class="description" style="max-width:70ch">'
		. esc_html__( 'Each of these builds a front page you can then edit like any other page. Importing never changes or deletes a page you already have, so you can try more than one.', 'pato' )
		. '</p>';

	echo wp_kses_post( $notice );

	echo '<div class="pato-starters">';

	foreach ( pato_starter_sites() as $slug => $starter ) {
		echo '<div class="pato-starter card">';
		echo '<h2>' . esc_html( $starter['name'] ) . '</h2>';
		echo '<p>' . esc_html( $starter['description'] ) . '</p>';
		echo '<form method="post">';
		wp_nonce_field( 'pato_import_starter' );
		echo '<input type="hidden" name="pato_starter" value="' . esc_attr( $slug ) . '">';
		echo '<button type="submit" class="button button-primary">'
			. esc_html__( 'Import this', 'pato' ) . '</button>';
		echo '</form>';
		echo '</div>';
	}

	echo '</div>';
	echo '<style>
		.pato-starters { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 18px; margin-top: 22px; max-width: 1100px; }
		.pato-starter.card { margin: 0; padding: 18px 20px; max-width: none; }
		.pato-starter h2 { margin-top: 0; }
	</style>';
	echo '</div>';
}
