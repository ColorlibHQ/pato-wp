<?php
/**
 * Add Pato to the /wp/themes/ listing (page 5091).
 *
 * Two insertions, both idempotent: a card in the free themes grid, and a line
 * in the "pick a theme by what you are building" list at the top. Restaurants
 * had no entry there at all before this.
 *
 * Safe to re-run: if Pato is already listed, only the card image is refreshed.
 */

defined( 'ABSPATH' ) || exit;

$page_id = 5091;

// Derive the card image from the attachment, never from a literal filename:
// `wp media import` does not overwrite, so a name that already exists becomes
// `-1.jpg` and a hardcoded URL quietly points at the wrong file.
$card_att = 381524;
$card_img = wp_get_attachment_url( $card_att );
$card_dim = wp_get_attachment_image_src( $card_att, 'full' );
$page_url = 'https://colorlib.com/wp/themes/pato/';

$content = get_post_field( 'post_content', $page_id );

if ( ! $card_img || ! $card_dim ) {
	echo "ERROR: attachment $card_att has no file\n";
	return;
}

if ( '' === $content ) {
	echo "ERROR: page $page_id has no content\n";
	return;
}

if ( false !== stripos( $content, 'theme-pato' ) ) {
	if ( false !== strpos( $content, $card_img ) ) {
		echo "already listed, card image current — nothing to do\n";
		return;
	}

	$updated = preg_replace(
		'~(<li class="clt-theme" id="theme-pato">.*?<img src=")[^"]+~s',
		'$1' . $card_img,
		$content,
		1
	);

	if ( null === $updated || $updated === $content ) {
		echo "ERROR: listed, but the card image could not be replaced\n";
		return;
	}

	wp_update_post( array( 'ID' => $page_id, 'post_content' => $updated ) );

	if ( function_exists( 'visual_composer' ) ) {
		visual_composer()->buildShortcodesCss( $page_id, 'custom' );
	}

	echo "card image updated to $card_img\n";
	return;
}

// The card goes before Philosophy's, so the newest block themes lead the grid.
$anchor = '<li class="clt-theme" id="theme-philosophy">';

if ( false === strpos( $content, $anchor ) ) {
	echo "ERROR: could not find the Philosophy card to insert before\n";
	return;
}

$card = '<li class="clt-theme" id="theme-pato">'
	. '<span class="clt-theme__shot">'
	. '<img src="' . esc_url( $card_img ) . '"'
	. ' alt="Pato free restaurant WordPress theme home page with its full-screen photograph and booking button"'
	. ' width="' . (int) $card_dim[1] . '" height="' . (int) $card_dim[2] . '"'
	. ' loading="lazy" decoding="async" />'
	. '</span>'
	. '<span class="clt-theme__body">'
	. '<span class="clt-theme__kind">Block theme</span>'
	. '<h3 class="clt-theme__name"><a href="' . esc_url( $page_url ) . '">Pato</a></h3>'
	. '<span class="clt-theme__desc">A restaurant theme with menus, prices and a booking form built in, six one-click starter sites and eight colour palettes.</span>'
	. '<span class="clt-theme__foot"><span class="clt-theme__installs"></span><span class="clt-theme__cta">View theme &rarr;</span></span>'
	. '</span></li>';

$content = str_replace( $anchor, $card . $anchor, $content );

// A line in the "what are you building" picker. There was no restaurant entry.
$pick_anchor = '<li class="clt-pick">';

if ( false !== strpos( $content, $pick_anchor ) ) {
	$pick = '<li class="clt-pick"><span class="clt-ico">'
		. '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">'
		. '<path d="M7 3v8a3 3 0 0 0 6 0V3"/><path d="M10 11v10"/><path d="M18 3c-1.5 2-2 4-2 6s.5 3 2 3v9"/>'
		. '</svg></span>'
		. '<span><span class="clt-pick__lab">A restaurant, café or bar</span>'
		. '<span class="clt-pick__themes"><a href="#theme-pato">Pato</a></span></span></li>';

	$content = preg_replace( '~' . preg_quote( $pick_anchor, '~' ) . '~', $pick . $pick_anchor, $content, 1 );
	echo "picker entry added\n";
} else {
	echo "NOTE: picker anchor not found — card added, picker left alone\n";
}

$kses = has_filter( 'content_save_pre', 'wp_filter_post_kses' );

if ( $kses ) {
	kses_remove_filters();
}

$result = wp_update_post( array( 'ID' => $page_id, 'post_content' => $content ), true );

if ( $kses ) {
	kses_init_filters();
}

if ( is_wp_error( $result ) ) {
	echo 'ERROR: ' . $result->get_error_message() . "\n";
	return;
}

// This page is WPBakery too, so its css= meta has to be regenerated like any
// other programmatic edit.
if ( function_exists( 'visual_composer' ) ) {
	$vc = visual_composer();
	if ( method_exists( $vc, 'buildShortcodesCss' ) ) {
		$vc->buildShortcodesCss( $page_id, 'custom' );
		$vc->buildShortcodesCss( $page_id, 'default' );
	}
}

$saved = get_post_field( 'post_content', $page_id );

echo 'page: ' . $page_id . ' (' . get_post_status( $page_id ) . ")\n";
echo 'length: ' . strlen( $saved ) . "\n";
echo 'pato cards: ' . substr_count( $saved, 'id="theme-pato"' ) . "\n";
echo 'pato picker links: ' . substr_count( $saved, '#theme-pato' ) . "\n";
echo 'total cards: ' . substr_count( $saved, 'class="clt-theme"' ) . "\n";
