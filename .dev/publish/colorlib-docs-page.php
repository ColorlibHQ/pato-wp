<?php
/**
 * Build the Pato documentation page, a child of the product page.
 *
 * Written for someone who has just installed the theme and wants to open on
 * Monday, not for someone reading about WordPress. It answers in order: how do
 * I get a site that looks like the demo, how do I change the menu, how do
 * bookings reach me, how do I change the colours, and what do I do when
 * something is not where I expect it.
 *
 * Idempotent. Leaves the page a DRAFT.
 */

defined( 'ABSPATH' ) || exit;

$slug     = 'documentation';
$parent   = 381531;                    // the Pato product page
$demo     = 'https://colorlibhub.com/pato/';
$download = 'https://updates.colorlib.com/download/theme/pato.zip';
$support  = 'https://colorlibsupport.com/';

$demo_enc     = rawurlencode( $demo );
$download_enc = rawurlencode( $download );
$btn_css      = 'display:inline-block !important;vertical-align:middle !important;margin-right:12px !important;margin-bottom:10px !important;';

// Slug AND parent. A path lookup does not work for a grandchild page, and
// falling back to "create" would add a new draft on every run.
$found    = get_posts(
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

/**
 * One documentation section: a heading and its body.
 *
 * @param string $n    Section number, used for the css= class so WPBakery
 *                     keeps each rule separate.
 * @param string $head Heading.
 * @param string $body HTML body.
 * @return string
 */
function pato_docs_section( $n, $head, $body ) {
	return '[vc_row css=".vc_custom_patodoc' . $n . '{padding-top:34px !important;padding-bottom:6px !important;}"][vc_column width="1/1"]'
		. '[vcex_heading text="' . esc_attr( $head ) . '" tag="h2" font_size="28px" bottom_margin="14px" font_weight="700"]'
		. '[vc_column_text css=".vc_custom_patodoct' . $n . '{max-width:820px !important;}"]' . $body . '[/vc_column_text]'
		. '[/vc_column][/vc_row]';
}

$sections = '';

$sections .= pato_docs_section( '01', 'Install the theme', <<<'HTML'
<p>Download the zip, then in WordPress go to <strong>Appearance → Themes → Add New → Upload Theme</strong>, choose the file and click <strong>Install Now</strong>, then <strong>Activate</strong>.</p>
<p>Pato needs WordPress 6.6 or newer and PHP 7.4 or newer. It is a block theme, so everything — the header, the footer, every template — is edited in <strong>Appearance → Editor</strong> rather than in the Customizer. There is no separate page builder to install and no required plugin.</p>
<p>The moment you activate it, Pato builds seven pages — Home, Menu, Reservation, About, Gallery, Contact and Blog — sets Home as your front page and creates a matching navigation menu. It only does this on a site that has not been built yet: if you already have pages, nothing is touched.</p>
HTML
);

$sections .= pato_docs_section( '02', 'Start from one of the six restaurants', <<<'HTML'
<p>Go to <strong>Appearance → Starter sites</strong> and pick the one closest to what you run:</p>
<ul>
<li><strong>Bistro</strong> — a neighbourhood restaurant: menu, reviews, booking form.</li>
<li><strong>Fine dining</strong> — one tasting menu, press quotes, private hire, on a dark palette.</li>
<li><strong>Café</strong> — all-day counter menu, gift cards, opening times.</li>
<li><strong>Pizzeria</strong> — menu first, then delivery and collection.</li>
<li><strong>Bar</strong> — drinks, events and late opening, on a dark palette.</li>
<li><strong>Bakery</strong> — counter menu, specials and gift cards.</li>
</ul>
<p>Each one builds a front page with its own photographs, its own dishes and prices, its own offers and its own gallery, and applies a matching colour palette and type pairing. The pizzeria lists a Margherita and a Diavola; the bar lists a Negroni.</p>
<p><strong>Importing never edits or deletes a page you already have.</strong> It creates a new page and points your front page at it, so you can import all six, look at them and keep whichever you prefer. Everything it creates is ordinary editable content — not a locked template.</p>
HTML
);

$sections .= pato_docs_section( '03', 'Change the menu and the prices', <<<'HTML'
<p>A menu row is three ordinary blocks: the dish, a dotted leader drawn in CSS, and the price. Open the page, click the text and type. There is no custom post type, no menu builder and nothing to learn.</p>
<p><strong>To add a dish</strong>, select an existing row, copy it (⌘/Ctrl + C), paste it below and edit the two pieces of text. The leader between them stretches to whatever width is left on its own.</p>
<p><strong>To add a course</strong>, use the <em>Menu: one course</em> pattern from the block inserter under <em>Pato: menus</em>, or copy a whole column and change its heading.</p>
<p><strong>The description under a dish</strong> is optional — delete the paragraph if you do not want it.</p>
<p>If you would rather not edit the front page directly, every section is also available on its own from the inserter: open any page, click <strong>+</strong>, choose <strong>Patterns</strong>, and look under <em>Pato: menus</em>, <em>Pato: sections</em> and <em>Pato: pages</em>.</p>
HTML
);

$sections .= pato_docs_section( '04', 'Reservations, and where they go', <<<'HTML'
<p>The booking form is part of the theme. It validates on the server, carries a nonce and a hidden honeypot field against bots, and works with JavaScript turned off.</p>
<p><strong>By default, bookings are emailed to the address in Settings → General.</strong> Change that address and the bookings follow it. If your host does not send mail reliably, install any SMTP plugin — Pato uses WordPress's own <code>wp_mail()</code>, so anything that fixes mail for WordPress fixes it for Pato.</p>
<p><strong>To send bookings somewhere else</strong> — a booking plugin, a CRM, a webhook, a spreadsheet — add this to your child theme's <code>functions.php</code> or a small plugin:</p>
<pre style="background:#faf7f4;padding:16px 18px;border-radius:6px;overflow-x:auto;">add_filter( 'pato_reservation_handlers', function ( $handled, $booking ) {
	// $booking has: name, email, phone, date, time, people, message
	my_crm_create_booking( $booking );
	return true;   // Pato then sends no email of its own
}, 10, 2 );</pre>
<p>Other filters: <code>pato_reservation_fields</code> adds, removes or relabels fields; <code>pato_reservation_email_to</code>, <code>pato_reservation_email_subject</code> and <code>pato_reservation_email_body</code> adjust the email.</p>
<p><strong>The form is a shortcode</strong>, <code>[pato_reservation_form]</code>, so you can drop it on any page with a Shortcode block. That is also why it keeps working when the page content is edited: it is expanded fresh every time the page renders.</p>
HTML
);

$sections .= pato_docs_section( '05', 'Opening hours, address and the map', <<<'HTML'
<p>Opening hours are a small table in the <em>Opening hours and address</em> section and in the footer. Edit them like any other text.</p>
<p>The map is an OpenStreetMap embed, which needs no API key and no billing account — it draws as soon as you install the theme. To move the pin, edit the section, switch the block to <strong>Edit as HTML</strong>, and change the two coordinates in the <code>marker=</code> part of the address. The <code>bbox=</code> numbers set how far out it zooms.</p>
<p>If you would rather use Google Maps, delete the block and paste Google's own embed code into a Custom HTML block.</p>
HTML
);

$sections .= pato_docs_section( '06', 'Colours and type', <<<'HTML'
<p>Go to <strong>Appearance → Editor → Styles</strong> (the half-filled circle), then <strong>Browse styles</strong>.</p>
<p>Eight colour palettes — Ember, Olive, Vineyard, Charcoal, Harvest, Midnight, Cellar and Seaside — and five type pairings. Midnight and Cellar are dark; the rest are light. Click one and the whole site follows, including the starter content you imported.</p>
<p>Every palette was checked for legibility before it shipped: body text, secondary text, links and button labels all meet WCAG AA against every background the design puts them on, in light mode and in dark mode.</p>
<p><strong>To change an individual colour</strong>, open Styles → Colors → Palette and edit it there. The theme refers to its colours by role — <em>primary</em>, <em>contrast</em>, <em>surface</em> — so changing one entry updates every section that uses it.</p>
HTML
);

$sections .= pato_docs_section( '07', 'Dark mode', <<<'HTML'
<p>The moon in the header is the visitor's switch, not yours. It is separate from the palette you chose: it lifts your palette rather than replacing it, so an Olive site stays olive and only the backgrounds and text invert.</p>
<p>It follows the visitor's own system setting until they choose for themselves, and then remembers their choice. It is applied before the page paints, so there is no flash of white on a dark-mode phone.</p>
<p><strong>To turn the whole feature off</strong>, add <code>add_filter( 'pato_enable_dark_mode', '__return_false' );</code>.</p>
<p><strong>To move the switch</strong>, edit the header in Appearance → Editor and drag the button. It is an ordinary Button block with the class <code>pato-scheme-toggle</code> — put that class on any button and it becomes the switch.</p>
HTML
);

$sections .= pato_docs_section( '08', 'Pages, posts and the sidebar', <<<'HTML'
<p>Pato ships 14 templates. The ones you will choose between are in <strong>Page attributes → Template</strong> when editing a page:</p>
<ul>
<li><strong>Page</strong> — a photograph banner with the page title on it.</li>
<li><strong>Page without title</strong> — no banner; use this when the page starts with its own hero. The starter pages use it.</li>
<li><strong>Page with sidebar</strong> — two columns, with search, categories and recent posts on the right.</li>
</ul>
<p>Posts have <strong>Post with sidebar</strong> as an alternative to the default. Archives, categories, tags, author pages, search results and the 404 page are all designed and can be edited in Appearance → Editor → Templates.</p>
<p>The header and footer are template parts: edit them once in <strong>Appearance → Editor → Patterns → Template parts</strong> and every page follows.</p>
HTML
);

$sections .= pato_docs_section( '09', 'Selling things', <<<'HTML'
<p>Install WooCommerce and Pato styles it — the shop, the product pages, the cart and the checkout all pick up the theme's colours, type and button shape, so a shop page does not look like a different website. Nothing to configure.</p>
<p>This covers both the modern block-based shop a block theme gets and the older shortcode markup, so it works whichever way your shop is built.</p>
<p>Gift cards, a cookbook, merchandise or collection orders are the usual reasons a restaurant wants this. If you do not install WooCommerce, none of its styling is loaded.</p>
HTML
);

$sections .= pato_docs_section( '10', 'Contact forms', <<<'HTML'
<p>Pato's own booking form needs no plugin. If you want a separate contact form, install any of these and Pato will style it to match: Contact Form 7, WPForms, Gravity Forms, Fluent Forms, Forminator, Ninja Forms, Formidable or HappyForms.</p>
<p>Drop the plugin's block onto the page and it inherits the theme's input styling, button and spacing.</p>
HTML
);

$sections .= pato_docs_section( '11', 'Updates', <<<'HTML'
<p>Pato is not in the WordPress.org theme directory, so it checks <code>updates.colorlib.com</code> for new versions twice a day. Updates then appear in <strong>Dashboard → Updates</strong> exactly like any other theme.</p>
<p>That check sends the theme version, your WordPress and PHP versions, your locale and a one-way hash of your site address. It sends no personal data and no site name, and the hash cannot be turned back into your URL. Add <code>add_filter( 'pato_check_for_updates', '__return_false' );</code> to switch it off — you will then not be told when a new version is released.</p>
<p><strong>Before updating</strong>, note that your pages are ordinary content: an update changes the theme, never your pages. Anything you have edited stays edited.</p>
HTML
);

$sections .= pato_docs_section( '12', 'If something is not where you expect', <<<'HTML'
<p><strong>The starter sites menu is missing.</strong> It is under Appearance → Starter sites, and needs the theme to be active.</p>
<p><strong>My front page is the blog, not the home page.</strong> Settings → Reading → "Your homepage displays" → A static page, and choose Home.</p>
<p><strong>The booking form says it is not configured.</strong> Set a valid address in Settings → General, or add a handler with the filter above.</p>
<p><strong>Bookings are not arriving.</strong> Test whether WordPress can send mail at all — most hosts need an SMTP plugin. Pato uses <code>wp_mail()</code>, so if WordPress cannot send a password reset it cannot send a booking either.</p>
<p><strong>The header is transparent over a photograph on some pages and solid on others.</strong> That is deliberate: it sits on the photograph where a page opens with one, and becomes a solid bar where there is nothing to sit on, such as a single blog post.</p>
<p><strong>I edited a pattern in the theme and nothing changed.</strong> Starter content is copied into your pages when you import it, so it is yours from that moment. Edit the page, not the pattern.</p>
HTML
);

$content = '[vc_row css=".vc_custom_patodoc00{padding-top:44px !important;padding-bottom:20px !important;background-color:#faf7f4 !important;}"][vc_column width="1/1"]'
	. '[vcex_heading text="Pato documentation" tag="h2" font_size="40px" text_align="center" bottom_margin="16px" font_weight="700"]'
	. '[vc_column_text css=".vc_custom_patodoc00t{text-align:center !important;font-size:18px !important;max-width:760px !important;margin-left:auto !important;margin-right:auto !important;}"]'
	. 'Everything needed to get a restaurant site open, in the order you will need it. Pato is a free block theme for restaurants, cafés and bars.'
	. '[/vc_column_text]'
	. '[vc_column_text css=".vc_custom_patodoc00b{text-align:center !important;margin-top:22px !important;}"]'
	. '[vc_btn title="Download Pato" style="flat" color="green" link="url:' . $download_enc . '|title:Download%20Pato|target:_blank" css=".vc_custom_patodoc00c{' . $btn_css . '}" i_icon_fontawesome="fa fa-download" add_icon="true"]'
	. '[vc_btn title="Live demo" style="flat" color="grey" link="url:' . $demo_enc . '|title:Live%20demo|target:_blank" css=".vc_custom_patodoc00d{' . $btn_css . '}" i_icon_fontawesome="fa fa-eye" add_icon="true"]'
	. '[/vc_column_text][/vc_column][/vc_row]'
	. $sections
	. '[vc_row css=".vc_custom_patodoc99{padding-top:34px !important;padding-bottom:56px !important;}"][vc_column width="1/1"]'
	. '[vcex_heading text="Still stuck?" tag="h2" font_size="28px" bottom_margin="14px" font_weight="700"]'
	. '[vc_column_text css=".vc_custom_patodoc99t{max-width:820px !important;}"]'
	. '<p>Ask on the <a href="' . esc_url( $support ) . '" target="_blank" rel="noopener">Colorlib support forum</a> and include your WordPress version, your PHP version and what you expected to happen.</p>'
	. '[/vc_column_text][/vc_column][/vc_row]';

$kses = has_filter( 'content_save_pre', 'wp_filter_post_kses' );
if ( $kses ) {
	kses_remove_filters();
}

$args = array(
	'post_title'   => 'Pato documentation',
	'post_name'    => $slug,
	'post_content' => $content,
	'post_status'  => $existing ? $existing->post_status : 'draft',
	'post_type'    => 'page',
	'post_parent'  => $parent,
);

if ( $page_id ) {
	$args['ID'] = $page_id;
	$result     = wp_update_post( $args, true );
} else {
	$result  = wp_insert_post( $args, true );
	$page_id = is_wp_error( $result ) ? 0 : $result;
}

if ( $kses ) {
	kses_init_filters();
}

if ( is_wp_error( $result ) ) {
	echo 'ERROR: ' . $result->get_error_message() . "\n";
	return;
}

if ( function_exists( 'visual_composer' ) ) {
	$vc = visual_composer();
	if ( method_exists( $vc, 'buildShortcodesCss' ) ) {
		$vc->buildShortcodesCss( $page_id, 'custom' );
		$vc->buildShortcodesCss( $page_id, 'default' );
		echo "custom css rebuilt\n";
	}
}

$saved    = get_post_field( 'post_content', $page_id );
$rendered = do_shortcode( $saved );

echo 'page: ' . $page_id . ' (' . get_post_status( $page_id ) . '), parent ' . wp_get_post_parent_id( $page_id ) . "\n";
echo 'length: ' . strlen( $saved ) . "\n";
echo 'sections: ' . substr_count( $saved, '[vcex_heading' ) . "\n";
echo 'unbalanced rows: ' . ( substr_count( $saved, '[vc_row' ) - substr_count( $saved, '[/vc_row]' ) ) . "\n";

preg_match_all( '#<a[^>]+href="([^"]*)"#', $rendered, $hrefs );
$dead = array_filter(
	array_unique( $hrefs[1] ),
	function ( $h ) {
		return '' === $h || '#' === $h || false !== strpos( $h, 'http://https' ) || 0 === strpos( $h, 'url:' );
	}
);
echo 'rendered links: ' . count( array_unique( $hrefs[1] ) ) . ', dead: ' . count( $dead ) . "\n";
