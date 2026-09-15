<?php
/**
 * Build the Pato product page on colorlib.com/wp, as a child of the themes
 * listing (5091).
 *
 * Same shape as the Academia, Unapp and Philosophy pages: what the theme is,
 * what you get, what it looks like, and the questions people ask. What Pato
 * leads with is the two things a restaurant site actually needs and normally
 * has to buy a plugin for — a menu with prices and a booking form.
 *
 * Idempotent: creates the page the first time, rewrites it after that, and
 * leaves it a DRAFT. colorlib.com's convention is draft first, publish
 * separately.
 */

defined( 'ABSPATH' ) || exit;

$slug   = 'pato';
$parent = 5091;

// Captured from the colorlibhub.com/pato demo, 2026-09-14.
$img_home        = 381527;
$img_menu        = 381528;
$img_reservation = 381530;
$img_starters    = 381547;   // six starter home pages, hero + first section
$img_palettes    = 381529;
$img_dark        = 381526;
$img_blog        = 381525;
$img_card        = 381524;

$download = 'https://updates.colorlib.com/download/theme/pato.zip';
$demo     = 'https://colorlibhub.com/pato/';

// vc_btn's `link` attribute is WPBakery's own "url:…|title:…|target:…"
// encoding. It splits on "|" then on the first ":", so a raw URL is cut off at
// "https:" and the button renders href="http://https". Percent-encode anything
// that goes into a `link`.
$docs         = 'https://colorlib.com/wp/themes/pato/documentation/';

$download_enc = rawurlencode( $download );
$demo_enc     = rawurlencode( $demo );
$docs_enc     = rawurlencode( $docs );

$btn_css = 'display:inline-block !important;vertical-align:middle !important;margin-right:12px !important;margin-bottom:10px !important;';

// Look up by slug AND parent. get_page_by_path( 'pato' ) looks for a
// TOP-LEVEL page called pato; this one is a child of 5091, so that lookup
// always returned nothing and the script created a second page every run
// instead of updating the first.
$found = get_posts(
	array(
		'post_type'   => 'page',
		'name'        => $slug,
		'post_parent' => $parent,
		// Explicit list, not 'any': in WP_Query 'any' means any status not
		// flagged exclude_from_search, which leaves drafts out — so the
		// lookup missed the draft it had just created and made a second one.
		'post_status' => array( 'publish', 'draft', 'pending', 'private', 'future' ),
		'numberposts' => 1,
	)
);

$existing = $found ? $found[0] : null;
$page_id  = $existing ? $existing->ID : 0;

// ---------------------------------------------------------------------------
// Content
// ---------------------------------------------------------------------------

$features = array(
	array( 'cutlery', 'Menus with prices', 'A dish, a dotted leader and a price, built from ordinary blocks. No custom post type and no menu builder to learn: anyone who can edit a page can change a price.' ),
	array( 'calendar', 'A booking form', 'Validated on the server, protected by a nonce and a honeypot, and it works with JavaScript off. One filter hands the booking to a plugin, a CRM or a webhook instead.' ),
	array( 'th-large', 'Six starter sites', 'Bistro, fine dining, café, pizzeria, bar and bakery. One click builds a front page you can then edit like any other page.' ),
	array( 'paint-brush', 'Eight palettes, five type pairings', 'Every one checked against WCAG AA for body text, secondary text, links and button labels, on every ground the design puts them on.' ),
	array( 'moon-o', 'Dark mode', 'A switch for the visitor, separate from the palette the owner chose. It lifts the palette rather than replacing it, so an olive site stays olive.' ),
	array( 'shopping-cart', 'WooCommerce ready', 'Gift cards, merchandise or collection orders. Styled for the block-based shop a block theme actually gets, and for the classic markup a shortcode produces.' ),
);

$feature_boxes = '';
foreach ( $features as $f ) {
	list( $icon, $heading, $body ) = $f;
	$feature_boxes .= '[vc_column width="1/3"][vcex_icon_box style="two" heading="' . esc_attr( $heading ) . '" heading_type="h3"'
		. ' icon="fa fa-' . $icon . '" icon_color="#d41b22" icon_size="28px" heading_size="20px"'
		. ' content_font_size="15px" css=".vc_custom_pato_f_' . sanitize_key( $icon ) . '{margin-bottom:26px !important;}"]'
		. $body . '[/vcex_icon_box][/vc_column]';
}

$faqs = array(
	array( 'Do I need a plugin?', 'No. Menus, reservations, opening hours, galleries and dark mode are all in the theme. WooCommerce is styled if you add it and is not required.' ),
	array( 'How do the starter sites work?', 'Appearance → Starter sites. Each one builds a front page and applies its palette and type pairing. It never edits or deletes a page you already have, so you can import more than one and keep whichever you prefer. Everything it creates is ordinary editable content.' ),
	array( 'Where do the bookings go?', 'To the site’s admin email address by default. If you already use a booking plugin, a CRM or a webhook, the <code>pato_reservation_handlers</code> filter hands the booking to it instead and Pato sends nothing of its own.' ),
	array( 'Which form plugins does it style?', 'Contact Form 7, WPForms, Gravity Forms, Fluent Forms, Forminator, Ninja Forms, Formidable and HappyForms are all mapped onto the theme’s own colours and spacing, so a form block does not look like a different website.' ),
	array( 'Can I change the colours?', 'Eight palettes and five type pairings ship with the theme, and each is a one-click choice in the Site Editor under Styles. Beyond that, every colour in the theme is a palette entry you can edit there.' ),
	array( 'Is it translation ready?', 'Yes. Every string is translatable and <code>languages/pato.pot</code> is included.' ),
	array( 'Does it check for updates?', 'Yes, twice a day, because it is distributed outside the WordPress.org theme directory. It sends the theme, WordPress and PHP versions, the locale and a one-way hash of the site URL — no personal data and no site name. The <code>pato_check_for_updates</code> filter switches it off.' ),
);

$toggles = '';
foreach ( $faqs as $i => $faq ) {
	// vcex_toggle takes `heading`. With `title` every toggle renders the
	// shortcode's own placeholder, "Lorem ipsum dolor sit amet?".
	$toggles .= '[vcex_toggle heading="' . esc_attr( $faq[0] ) . '" heading_type="h3" css=".vc_custom_pato_q' . $i . '{margin-bottom:10px !important;}"]'
		. $faq[1] . '[/vcex_toggle]';
}

$specs = array(
	'Requires'      => 'WordPress 6.6 or newer',
	'PHP'           => '7.4 or newer',
	'Tested up to'  => 'WordPress 7.0',
	'Licence'       => 'GNU General Public License v2 or later',
	'Patterns'      => '64',
	'Templates'     => '14, plus 3 template parts',
	'Starter sites' => '6',
	'Styles'        => '8 colour palettes × 5 type pairings',
	'Fonts'         => 'Montserrat, Poppins and Courgette, self-hosted',
	'Build step'    => 'None — no npm, no SCSS',
);

$spec_rows = '';
foreach ( $specs as $label => $value ) {
	$spec_rows .= '<tr><th style="text-align:left;padding:10px 18px 10px 0;border-bottom:1px solid #eceae7;font-weight:600;white-space:nowrap;">'
		. esc_html( $label ) . '</th><td style="padding:10px 0;border-bottom:1px solid #eceae7;">' . $value . '</td></tr>';
}

$content = <<<HTML
[vc_row css=".vc_custom_pato001{padding-top:64px !important;padding-bottom:40px !important;background-color:#faf7f4 !important;}"][vc_column width="1/1"][vcex_heading text="A restaurant theme that already knows what a restaurant needs" tag="h2" font_size="46px" text_align="center" bottom_margin="20px" font_weight="700"][vc_column_text css=".vc_custom_pato002{text-align:center !important;font-size:18px !important;max-width:820px !important;margin-left:auto !important;margin-right:auto !important;}"]Pato is a free block theme for restaurants, bistros, cafés and bars. It ships the two things every restaurant site needs and normally has to buy a plugin for — a menu with prices and a booking form — plus six one-click starter sites, eight colour palettes and full site editing throughout.[/vc_column_text][vc_column_text css=".vc_custom_pato003{text-align:center !important;margin-top:26px !important;}"][vc_btn title="Download Pato" style="flat" color="green" link="url:{$download_enc}|title:Download%20Pato|target:_blank" css=".vc_custom_pato004{{$btn_css}}" i_icon_fontawesome="fa fa-download" add_icon="true"][vc_btn title="Live demo" style="flat" color="grey" link="url:{$demo_enc}|title:Live%20demo|target:_blank" css=".vc_custom_pato005{{$btn_css}}" i_icon_fontawesome="fa fa-eye" add_icon="true"][vc_btn title="Documentation" style="flat" color="grey" link="url:{$docs_enc}|title:Documentation" css=".vc_custom_pato005b{{$btn_css}}" i_icon_fontawesome="fa fa-book" add_icon="true"][/vc_column_text][/vc_column][/vc_row][vc_row css=".vc_custom_pato006{padding-top:0px !important;padding-bottom:64px !important;background-color:#faf7f4 !important;}"][vc_column width="1/1"][vcex_image image_id="{$img_home}" align="center" border_radius="14px" bottom_margin="0px"][/vc_column][/vc_row]

[vc_row css=".vc_custom_pato010{padding-top:56px !important;padding-bottom:20px !important;}"][vc_column width="1/1"][vcex_heading text="A menu you edit like a page" tag="h2" font_size="34px" text_align="center" bottom_margin="14px" font_weight="700"][vc_column_text css=".vc_custom_pato011{text-align:center !important;max-width:780px !important;margin-left:auto !important;margin-right:auto !important;}"]A dish, a dotted leader and a price. It is built from ordinary blocks — two paragraphs in a group — so there is no custom post type, no menu builder and nothing to learn. Anyone who can edit a page can change a price, and the leader stretches to whatever width is left instead of wrapping like a row of typed dots.[/vc_column_text][/vc_column][/vc_row][vc_row css=".vc_custom_pato012{padding-bottom:52px !important;}"][vc_column width="1/1"][vcex_image image_id="{$img_menu}" align="center" border_radius="12px" bottom_margin="0px"][/vc_column][/vc_row]

[vc_row css=".vc_custom_pato020{padding-top:52px !important;padding-bottom:20px !important;background-color:#faf7f4 !important;}"][vc_column width="1/1"][vcex_heading text="Bookings without a plugin" tag="h2" font_size="34px" text_align="center" bottom_margin="14px" font_weight="700"][vc_column_text css=".vc_custom_pato021{text-align:center !important;max-width:780px !important;margin-left:auto !important;margin-right:auto !important;}"]The reservation form is part of the theme. It validates on the server, carries a nonce and a honeypot, and works with JavaScript turned off. Bookings go to the site’s admin address — or, with one filter, to whatever booking plugin, CRM or webhook you already use.[/vc_column_text][/vc_column][/vc_row][vc_row css=".vc_custom_pato022{padding-bottom:56px !important;background-color:#faf7f4 !important;}"][vc_column width="1/1"][vcex_image image_id="{$img_reservation}" align="center" border_radius="12px" bottom_margin="0px"][/vc_column][/vc_row]

[vc_row css=".vc_custom_pato030{padding-top:56px !important;padding-bottom:20px !important;}"][vc_column width="1/1"][vcex_heading text="Six restaurants, one theme" tag="h2" font_size="34px" text_align="center" bottom_margin="14px" font_weight="700"][vc_column_text css=".vc_custom_pato031{text-align:center !important;max-width:800px !important;margin-left:auto !important;margin-right:auto !important;}"]A bar’s first screen asks for a table at eleven at night; a bakery’s says what came out of the oven. Pato ships six starter sites — bistro, fine dining, café, pizzeria, bar and bakery — and they differ all the way down: their own photographs, their own menu with its own dishes and prices, their own offers, their own gallery, their own palette and type pairing. The pizzeria lists a Margherita and a Diavola; the bar lists a Negroni. Importing one builds a front page you can edit like any other page, and it never touches a page you already have.[/vc_column_text][/vc_column][/vc_row][vc_row css=".vc_custom_pato032{padding-bottom:56px !important;}"][vc_column width="1/1"][vcex_image image_id="{$img_starters}" align="center" border_radius="12px" bottom_margin="0px"][/vc_column][/vc_row]

[vc_row css=".vc_custom_pato040{padding-top:52px !important;padding-bottom:20px !important;background-color:#faf7f4 !important;}"][vc_column width="1/1"][vcex_heading text="Dark mode the visitor controls" tag="h2" font_size="34px" text_align="center" bottom_margin="14px" font_weight="700"][vc_column_text css=".vc_custom_pato041{text-align:center !important;max-width:780px !important;margin-left:auto !important;margin-right:auto !important;}"]A restaurant site is read at night more than most — from a phone, on the way somewhere. The switch is the visitor’s, separate from the palette you chose, and it lifts your palette rather than replacing it: an olive site stays olive. It follows the system setting until someone decides for themselves, and it is applied before the first paint, so there is no flash of white.[/vc_column_text][/vc_column][/vc_row][vc_row css=".vc_custom_pato042{padding-bottom:56px !important;background-color:#faf7f4 !important;}"][vc_column width="1/1"][vcex_image image_id="{$img_dark}" align="center" border_radius="12px" bottom_margin="0px"][/vc_column][/vc_row]

[vc_row css=".vc_custom_pato050{padding-top:56px !important;padding-bottom:16px !important;}"][vc_column width="1/1"][vcex_heading text="What you get" tag="h2" font_size="34px" text_align="center" bottom_margin="34px" font_weight="700"][/vc_column][/vc_row][vc_row css=".vc_custom_pato051{padding-bottom:40px !important;}"]{$feature_boxes}[/vc_row]

[vc_row css=".vc_custom_pato060{padding-top:48px !important;padding-bottom:20px !important;background-color:#faf7f4 !important;}"][vc_column width="1/1"][vcex_heading text="It has a blog, too" tag="h2" font_size="34px" text_align="center" bottom_margin="14px" font_weight="700"][vc_column_text css=".vc_custom_pato061{text-align:center !important;max-width:780px !important;margin-left:auto !important;margin-right:auto !important;}"]Recipes, wine notes, what is happening in the kitchen. Archives, single posts, categories, tags, author pages, search and a 404 are all designed rather than inherited, with an optional sidebar layout for posts and pages.[/vc_column_text][/vc_column][/vc_row][vc_row css=".vc_custom_pato062{padding-bottom:56px !important;background-color:#faf7f4 !important;}"][vc_column width="1/1"][vcex_image image_id="{$img_blog}" align="center" border_radius="12px" bottom_margin="0px"][/vc_column][/vc_row]

[vc_row css=".vc_custom_pato070{padding-top:56px !important;padding-bottom:18px !important;}"][vc_column width="1/1"][vcex_heading text="Questions" tag="h2" font_size="34px" text_align="center" bottom_margin="28px" font_weight="700"][/vc_column][/vc_row][vc_row css=".vc_custom_pato071{padding-bottom:48px !important;}"][vc_column width="1/1"]{$toggles}[/vc_column][/vc_row]

[vc_row css=".vc_custom_pato080{padding-top:48px !important;padding-bottom:56px !important;background-color:#faf7f4 !important;}"][vc_column width="1/1"][vcex_heading text="The details" tag="h2" font_size="34px" text_align="center" bottom_margin="26px" font_weight="700"][vc_column_text css=".vc_custom_pato081{max-width:680px !important;margin-left:auto !important;margin-right:auto !important;}"]<table style="width:100%;border-collapse:collapse;">{$spec_rows}</table>[/vc_column_text][vc_column_text css=".vc_custom_pato082{text-align:center !important;margin-top:34px !important;}"][vc_btn title="Download Pato" style="flat" color="green" link="url:{$download_enc}|title:Download%20Pato|target:_blank" css=".vc_custom_pato083{{$btn_css}}" i_icon_fontawesome="fa fa-download" add_icon="true"][vc_btn title="Live demo" style="flat" color="grey" link="url:{$demo_enc}|title:Live%20demo|target:_blank" css=".vc_custom_pato084{{$btn_css}}" i_icon_fontawesome="fa fa-eye" add_icon="true"][vc_btn title="Documentation" style="flat" color="grey" link="url:{$docs_enc}|title:Documentation" css=".vc_custom_pato085{{$btn_css}}" i_icon_fontawesome="fa fa-book" add_icon="true"][/vc_column_text][/vc_column][/vc_row]
HTML;

// ---------------------------------------------------------------------------
// Save
// ---------------------------------------------------------------------------

// kses strips the shortcode attributes this page is made of.
$kses = has_filter( 'content_save_pre', 'wp_filter_post_kses' );
if ( $kses ) {
	kses_remove_filters();
}

$args = array(
	'post_title'   => 'Pato',
	'post_name'    => $slug,
	'post_content' => $content,
	// Draft on creation, but never demote a page that is already published:
	// rebuilding the copy of a live page must not take it off the site.
	'post_status'  => $existing ? $existing->post_status : 'draft',
	'post_type'    => 'page',
	'post_parent'  => $parent,
);

if ( $page_id ) {
	$args['ID'] = $page_id;
	$result     = wp_update_post( $args, true );
} else {
	$result = wp_insert_post( $args, true );
	$page_id = is_wp_error( $result ) ? 0 : $result;
}

if ( $kses ) {
	kses_init_filters();
}

if ( is_wp_error( $result ) ) {
	echo 'ERROR: ' . $result->get_error_message() . "\n";
	return;
}

set_post_thumbnail( $page_id, $img_card );

// WPBakery keeps every css="…" rule in _wpb_shortcodes_custom_css and only
// regenerates it when the page is saved through the builder UI. Updating
// post_content programmatically leaves that meta stale, so every css= edit
// after the first save is silently inert.
if ( function_exists( 'visual_composer' ) ) {
	$vc = visual_composer();
	if ( method_exists( $vc, 'buildShortcodesCss' ) ) {
		$vc->buildShortcodesCss( $page_id, 'custom' );
		$vc->buildShortcodesCss( $page_id, 'default' );
		echo "custom css rebuilt\n";
	} else {
		echo "WARNING: could not rebuild the custom css\n";
	}
} else {
	echo "WARNING: visual_composer() unavailable\n";
}

$saved = get_post_field( 'post_content', $page_id );

echo 'page: ' . $page_id . ' (' . get_post_status( $page_id ) . '), parent ' . wp_get_post_parent_id( $page_id ) . "\n";
echo 'length: ' . strlen( $saved ) . "\n";
echo 'icon boxes: ' . substr_count( $saved, '[vcex_icon_box' ) . "\n";
echo 'toggles: ' . substr_count( $saved, '[vcex_toggle' ) . ' (with heading=: ' . substr_count( $saved, '[vcex_toggle heading=' ) . ")\n";
echo 'images: ' . substr_count( $saved, '[vcex_image' ) . "\n";
echo 'unbalanced rows: ' . ( substr_count( $saved, '[vc_row' ) - substr_count( $saved, '[/vc_row]' ) ) . "\n";
echo 'unbalanced columns: ' . ( substr_count( $saved, '[vc_column ' ) - substr_count( $saved, '[/vc_column]' ) ) . "\n";

$rendered = do_shortcode( $saved );
preg_match_all( '#<a[^>]+href="([^"]*)"#', $rendered, $hrefs );
$unique = array_values( array_unique( $hrefs[1] ) );
$dead   = array_values(
	array_filter(
		$unique,
		function ( $h ) {
			return '' === $h || '#' === $h || false !== strpos( $h, 'http://https' ) || 0 === strpos( $h, 'url:' );
		}
	)
);

echo 'rendered links: ' . count( $unique ) . ', dead: ' . count( $dead ) . "\n";
foreach ( $dead as $d ) {
	echo '  DEAD: ' . $d . "\n";
}

preg_match_all( '#<h([1-6])#', $rendered, $levels );
echo 'headings: ' . implode( ',', array_count_values( $levels[1] ) ? array_map( function ( $k, $v ) { return 'h' . $k . '×' . $v; }, array_keys( array_count_values( $levels[1] ) ), array_values( array_count_values( $levels[1] ) ) ) : array() ) . "\n";

foreach ( array( $download, $demo, $docs ) as $target ) {
	$head = wp_remote_head( $target, array( 'timeout' => 15, 'redirection' => 5 ) );
	$code = is_wp_error( $head ) ? $head->get_error_message() : wp_remote_retrieve_response_code( $head );
	echo '  ' . str_pad( (string) $code, 6 ) . $target . "\n";
}

echo 'images resolve: ';
foreach ( array( $img_home, $img_menu, $img_reservation, $img_starters, $img_dark, $img_blog, $img_card ) as $id ) {
	echo ( wp_get_attachment_url( $id ) ? 'y' : 'N' );
}
echo "\n";
