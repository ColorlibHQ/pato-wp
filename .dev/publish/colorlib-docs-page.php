<?php
/**
 * Build the Pato documentation page, a child of the product page.
 *
 * Written for someone who has just installed the theme and wants to open on
 * Monday. Every step that happens on a screen has a screenshot or a short
 * animation, captured from a fresh WordPress install with Pato activated
 * (.dev/publish/README.md, "Documentation images").
 *
 * Images are found in the media library by file name; the importer sets their
 * alt text, so alt text has one source. The run stops before saving if an image
 * is missing or a %%placeholder%% is left, so the page never goes out with a
 * hole in it. PATO_DOCS_ALLOW_MISSING=1 saves anyway.
 *
 * Idempotent. Keeps the page's current status; a new page starts as a draft.
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
		// Explicit list, not 'any': in WP_Query 'any' leaves drafts out, so the
		// lookup missed the draft it had just created and made a second one.
		'post_status' => array( 'publish', 'draft', 'pending', 'private', 'future' ),
		'numberposts' => 1,
	)
);
$existing = $found ? $found[0] : null;
$page_id  = $existing ? $existing->ID : 0;

// Token => [file in the media library, caption (HTML), max width in px or 0].
// $GLOBALS because `wp eval` runs this file inside a function.
$GLOBALS['pato_docs_missing'] = array();
$GLOBALS['pato_docs_figures'] = array(
	'upload'       => array( 'pato-docs-upload-theme.png', 'Appearance → Themes → Add New Theme → <strong>Upload Theme</strong>.', 0 ),
	'pages'        => array( 'pato-docs-pages-created.png', 'Straight after activation: seven new pages, with Home as the front page and Blog as the posts page.', 0 ),
	'reading'      => array( 'pato-docs-reading-settings.png', 'Settings → Reading. Choose <em>A static page</em>, then the page your site should open on.', 760 ),
	'menu-admin'   => array( 'pato-docs-appearance-menu.png', '', 170 ),
	'six'          => array( 'pato-docs-six-starters.jpg', 'The six starter sites. Each has its own photographs, headline, dishes, offers, gallery, colours and fonts.', 0 ),
	'starters'     => array( 'pato-docs-starter-sites.png', 'Appearance → Starter sites.', 0 ),
	'import-gif'   => array( 'pato-docs-import-starter.gif', 'Importing the Pizzeria starter: <strong>Import this</strong>, then <strong>View it</strong>.', 0 ),
	'price-gif'    => array( 'pato-docs-edit-price.gif', 'Changing a price: click it, select it, type, then Save.', 0 ),
	'price'        => array( 'pato-docs-edit-price.jpg', 'A price selected. The breadcrumb along the bottom shows where it sits: the price is a Paragraph inside the dish row’s Group.', 0 ),
	'inserter'     => array( 'pato-docs-pattern-inserter.jpg', 'The block inserter’s Patterns tab with <em>Pato: menus</em> open.', 0 ),
	'listview'     => array( 'pato-docs-list-view.jpg', 'List View (left) with the home page’s opening banner opened up. Clicking a row selects that block on the page.', 0 ),
	'form'         => array( 'pato-docs-reservation-form.png', 'The booking form as your guests see it. Fields marked * are required.', 640 ),
	'parts'        => array( 'pato-docs-template-parts.jpg', 'Appearance → Editor → Patterns → <em>All template parts</em>: the header, the footer and the blog sidebar.', 0 ),
	'header'       => array( 'pato-docs-edit-header.jpg', 'The Header template part. Click the grey placeholder to add your logo.', 0 ),
	'nav'          => array( 'pato-docs-navigation.jpg', 'Appearance → Editor → Navigation, with the Primary menu open.', 0 ),
	'styles'       => array( 'pato-docs-styles.jpg', 'Appearance → Editor → <strong>Styles</strong>.', 0 ),
	'palettes-gif' => array( 'pato-docs-palettes.gif', 'Browse styles: each click previews a palette. Your live site does not change until you click Save.', 0 ),
	'browse'       => array( 'pato-docs-browse-styles.jpg', 'Browse styles: the eight palettes plus the theme default, then the five font pairings plus the default.', 0 ),
	'dark-gif'     => array( 'pato-docs-dark-mode.gif', 'The moon switches a visitor to dark mode, and back again.', 0 ),
	'dark'         => array( 'pato-docs-dark-mode.jpg', '', 0 ),
	'template'     => array( 'pato-docs-template-menu.jpg', 'Page tab → Template → <strong>Change template</strong>.', 0 ),
);

/**
 * Attachment ID for a file name, newest first.
 *
 * By ID rather than by URL: `wp media import` renames a clashing file to
 * `-1`, so a literal URL could silently point at a different picture. The
 * stored path is `YYYY/MM/name` or, on colorlib.com (no month folders), just
 * `name` — match both, never a bare `LIKE '%/name'`, which misses the second.
 *
 * @param string $file File name.
 * @return int
 */
function pato_docs_attachment_id( $file ) {
	global $wpdb;
	return (int) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file' AND ( meta_value = %s OR meta_value LIKE %s ) ORDER BY post_id ASC LIMIT 1",
			$file,
			'%/' . $wpdb->esc_like( $file )
		)
	);
}

/**
 * Replace a {{fig:token}} with a figure. Kept on one line so wpautop has
 * nothing to break inside it.
 *
 * @param array $m Regex match.
 * @return string
 */
function pato_docs_figure( $m ) {
	$figures = $GLOBALS['pato_docs_figures'];
	if ( ! isset( $figures[ $m[1] ] ) ) {
		$GLOBALS['pato_docs_missing'][] = 'unknown token ' . $m[1];
		return '';
	}
	list( $file, $caption, $max ) = $figures[ $m[1] ];
	$id = pato_docs_attachment_id( $file );
	if ( ! $id ) {
		$GLOBALS['pato_docs_missing'][] = $file;
		return '';
	}
	$url  = wp_get_attachment_url( $id );
	$meta = wp_get_attachment_metadata( $id );
	$size = ( ! empty( $meta['width'] ) && ! empty( $meta['height'] ) ) ? ' width="' . (int) $meta['width'] . '" height="' . (int) $meta['height'] . '"' : '';
	$alt  = get_post_meta( $id, '_wp_attachment_image_alt', true );

	return '<figure class="pato-docs-fig"' . ( $max ? ' style="max-width:' . (int) $max . 'px"' : '' ) . '>'
		. '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener"><img src="' . esc_url( $url ) . '" alt="' . esc_attr( $alt ) . '"' . $size . ' loading="lazy" decoding="async"></a>'
		. ( $caption ? '<figcaption>' . $caption . '</figcaption>' : '' )
		. '</figure>';
}

/**
 * One documentation section: an anchored row with a heading and its body.
 *
 * @param string $n      Section number, used for the css= class so WPBakery
 *                       keeps each rule separate.
 * @param string $anchor Row id, the target of the contents links.
 * @param string $head   Heading.
 * @param string $body   HTML body, with {{fig:token}} placeholders.
 * @return string
 */
function pato_docs_section( $n, $anchor, $head, $body ) {
	$body = preg_replace_callback( '/\{\{fig:([a-z0-9-]+)\}\}/', 'pato_docs_figure', $body );

	return '[vc_row el_id="' . $anchor . '" el_class="pato-docs" css=".vc_custom_patodoc' . $n . '{padding-top:34px !important;padding-bottom:6px !important;}"][vc_column width="1/1"]'
		. '[vcex_heading text="' . esc_attr( $head ) . '" tag="h2" font_size="28px" bottom_margin="14px" font_weight="700"]'
		. '[vc_column_text css=".vc_custom_patodoct' . $n . '{max-width:860px !important;}"]' . $body . '[/vc_column_text]'
		. '[/vc_column][/vc_row]';
}

$css = '<style>'
	. 'html{scroll-behavior:smooth}.pato-docs{scroll-margin-top:110px}'
	. '.pato-docs h3{font-size:20px;margin:30px 0 10px}'
	. '.pato-docs-fig{margin:22px 0 30px}.pato-docs-fig img{display:block;width:100%;height:auto;border:1px solid #e6e1dc;border-radius:8px}'
	. '.pato-docs-fig figcaption{font-size:14px;line-height:1.55;color:#6b6560;margin-top:9px}'
	. '.pato-docs-tip{background:#f7f3ee;border-left:4px solid #d41b22;border-radius:4px;padding:14px 18px 2px;margin:22px 0}'
	. '.pato-docs kbd{display:inline-block;font:600 13px/1.5 ui-monospace,SFMono-Regular,Menlo,Consolas,monospace;background:#fff;border:1px solid #d7d1cb;border-bottom-width:2px;border-radius:4px;padding:0 6px;color:#1d2327;white-space:nowrap}'
	. '.pato-docs-steps li{margin-bottom:8px}'
	. '.pato-docs-table{overflow-x:auto;margin:18px 0 24px}.pato-docs-table table{border-collapse:collapse;width:100%;min-width:520px;font-size:15px}'
	. '.pato-docs-table th,.pato-docs-table td{border-bottom:1px solid #ece7e2;padding:9px 10px;text-align:left;vertical-align:top}.pato-docs-table th{background:#faf7f4}'
	. '.pato-docs pre{background:#faf7f4;padding:16px 18px;border-radius:6px;overflow-x:auto;font-size:14px;line-height:1.6;white-space:pre}'
	. '.pato-docs-toc{columns:2;column-gap:40px;max-width:720px;margin:0 auto;text-align:left;padding-left:22px}.pato-docs-toc li{margin-bottom:6px;break-inside:avoid}'
	. '@media (max-width:640px){.pato-docs-toc{columns:1}}'
	. '</style>';

$sections = '';

$sections .= pato_docs_section( '01', 'quick-start', 'The short version', <<<'HTML'
<p>If you read one section, read this one. Each step links to the full explanation further down.</p>
<ol class="pato-docs-steps">
<li><strong>Install and activate Pato.</strong> It builds your pages and your menu on its own. <a href="#install">Install Pato</a></li>
<li><strong>Import the starter site closest to your place</strong> from Appearance → Starter sites. <a href="#starters">Starter sites</a></li>
<li><strong>Put in your own dishes and prices.</strong> Click the text on the page and type over it. <a href="#menu">Dishes and prices</a></li>
<li><strong>Add your logo and check the menu links.</strong> <a href="#header">Logo, header and navigation</a></li>
<li><strong>Change the opening hours, address and map.</strong> <a href="#hours">Opening hours and map</a></li>
<li><strong>Send yourself a test booking</strong> and make sure the email arrives. <a href="#reservations">Bookings</a></li>
<li><strong>Pick colours and fonts</strong> if the starter’s are not yours. <a href="#styles">Colours and fonts</a></li>
</ol>
<p>None of it needs code, a page builder or a plugin.</p>
HTML
);

$sections .= pato_docs_section( '02', 'install', 'Install Pato', <<<'HTML'
<p>Download the zip with the button at the top of this page. <strong>Do not unzip it</strong> — WordPress installs the zip file itself.</p>
<ol class="pato-docs-steps">
<li>In your dashboard, go to <strong>Appearance → Themes</strong> and click <strong>Add New Theme</strong>.</li>
<li>Click <strong>Upload Theme</strong> at the top of the screen.</li>
<li>Click <strong>Choose File</strong>, pick <code>pato.zip</code>, then click <strong>Install Now</strong>.</li>
<li>When WordPress reports the theme is installed, click <strong>Activate</strong>.</li>
</ol>
{{fig:upload}}
<p>Pato needs <strong>WordPress 6.6 or newer</strong> and <strong>PHP 7.4 or newer</strong>, and is tested up to WordPress 7.1. <strong>Tools → Site Health → Info</strong> shows both versions.</p>
<h3>What happens when you activate it</h3>
<p>On a site that has not been built yet, Pato creates seven pages — <strong>Home, Menu, Reservation, About, Gallery, Contact and Blog</strong> — and fills each with real content. It sets Home as the front page and Blog as the posts page, and builds a navigation menu that links them all.</p>
{{fig:pages}}
<p><em>Privacy Policy</em> and <em>Sample Page</em> come with WordPress, not with Pato. Delete Sample Page whenever you like.</p>
<div class="pato-docs-tip"><p><strong>Already had pages?</strong> Then Pato builds nothing, so it can never overwrite a site you have been working on. Import a starter site instead (next section) — that works on any site — or point your front page at an existing page in <strong>Settings → Reading</strong>:</p></div>
{{fig:reading}}
<h3>Where Pato lives in the dashboard</h3>
<p>Pato is a block theme, so there is no Customizer and no theme options panel. It adds exactly one screen, <strong>Appearance → Starter sites</strong>. Everything else — header, footer, colours, fonts, templates — is in <strong>Appearance → Editor</strong>. <strong>Fonts</strong> is WordPress’s own font library, where you can add a typeface of your own.</p>
{{fig:menu-admin}}
HTML
);

$sections .= pato_docs_section( '03', 'starters', 'Start from one of six restaurants', <<<'HTML'
<p>Pato comes with six complete front pages, each written for a different kind of place. They are not colour swaps: each has its own photographs, headline, dishes and prices, weekly offers and gallery, and its own colours and fonts.</p>
{{fig:six}}
<div class="pato-docs-table"><table><thead><tr><th>Starter</th><th>Written for</th><th>Colours</th><th>Fonts</th></tr></thead><tbody>
<tr><td><strong>Bistro</strong></td><td>A neighbourhood restaurant: menu, reviews and a booking form</td><td>Ember</td><td>Montserrat &amp; Poppins</td></tr>
<tr><td><strong>Fine dining</strong></td><td>One tasting menu, press quotes and private hire</td><td>Cellar (dark)</td><td>Montserrat throughout</td></tr>
<tr><td><strong>Café</strong></td><td>All-day coffee and cake, gift cards and opening times</td><td>Harvest</td><td>Poppins throughout</td></tr>
<tr><td><strong>Pizzeria</strong></td><td>Menu first, then delivery and collection</td><td>Olive</td><td>Poppins throughout</td></tr>
<tr><td><strong>Bar</strong></td><td>Drinks, events and late opening</td><td>Midnight (dark)</td><td>Montserrat &amp; Poppins</td></tr>
<tr><td><strong>Bakery</strong></td><td>Counter menu, specials and gift cards</td><td>Harvest</td><td>Poppins throughout</td></tr>
</tbody></table></div>
<h3>Import one</h3>
<ol class="pato-docs-steps">
<li>Go to <strong>Appearance → Starter sites</strong>.</li>
<li>Click <strong>Import this</strong> on the starter you want.</li>
<li>When the screen reloads with <em>“Imported, and set as the front page”</em>, click <strong>View it</strong>.</li>
</ol>
{{fig:starters}}
{{fig:import-gif}}
<h3>What importing does, and what it does not</h3>
<ul>
<li>It <strong>creates a new page</strong> named after the starter — “Pizzeria”, say — and makes it your front page.</li>
<li>It <strong>applies the starter’s colours and fonts</strong> to the whole site. Anything else you changed in Styles is kept.</li>
<li>It <strong>never edits or deletes a page you already have</strong>, and it does not touch your navigation menu.</li>
<li>It <strong>changes only the front page.</strong> Your Menu, About, Gallery and other pages keep the content they had. After importing the Pizzeria starter, open the Menu page and put pizzas on it too — or delete that page and its menu link and let the front page’s menu do the job.</li>
</ul>
<div class="pato-docs-tip"><p><strong>Try several.</strong> Each import adds one page, so you can import all six, compare them, and keep the one you like. Delete the others from <strong>Pages</strong> (hover over a title, then <strong>Trash</strong>). To go back to one you imported earlier, choose it under <strong>Settings → Reading → Homepage</strong>. The colours stay those of your most recent import; change them in <a href="#styles">Styles</a>.</p></div>
<p>Everything a starter creates is ordinary content made of ordinary blocks. Nothing is locked, and nothing depends on the theme’s pattern files once it is imported.</p>
HTML
);

$sections .= pato_docs_section( '04', 'menu', 'Change dishes and prices', <<<'HTML'
<p>A menu row is two text blocks side by side — the dish and the price — with a dotted leader drawn between them, and an optional description underneath. There is no menu plugin and no special block to learn: you change a price the way you change any other text.</p>
<h3>Change a price or a dish name</h3>
<ol class="pato-docs-steps">
<li>Open the page. From the front of your site, click <strong>Edit Page</strong> in the black bar at the top; from the dashboard, go to <strong>Pages</strong> and click the page’s title. Menus are on your front page and on the page called <strong>Menu</strong>.</li>
<li>Click the price. A small toolbar appears above it.</li>
<li>Select the old text — double-click it, or press <kbd>⌘ A</kbd> (<kbd>Ctrl A</kbd> on Windows), which selects only that price — and type the new one.</li>
<li>Click <strong>Save</strong> in the top-right corner, or press <kbd>⌘ S</kbd> / <kbd>Ctrl S</kbd>.</li>
</ol>
{{fig:price-gif}}
<p>The dotted leader stretches or shrinks by itself, so a long dish name or a four-digit price never needs lining up. Type any currency the way you want it to read: <code>€18</code>, <code>£18</code> or <code>18 kr</code>.</p>
{{fig:price}}
<h3>Add a dish</h3>
<p>Copy one that is already there: the copy keeps the row layout and the leader.</p>
<ol class="pato-docs-steps">
<li>Click the <strong>name</strong> of a dish next to where the new one should go.</li>
<li>Press <strong>Select parent</strong> — the first button on the toolbar — so the whole row, name and price, is outlined.</li>
<li>If the dish has a description, hold <kbd>Shift</kbd> and click the description too. It is a separate line under the row, so it has to be selected with it.</li>
<li>Press <kbd>⇧ ⌘ D</kbd> (<kbd>Ctrl Shift D</kbd>), or choose <strong>Duplicate</strong> from the toolbar’s <strong>⋮</strong> menu. The copy appears directly below.</li>
<li>Type over the copy’s name, price and description, and save.</li>
</ol>
<p><strong>To remove a dish</strong>, select it the same way and press <kbd>⌃ ⌥ Z</kbd> (<kbd>Shift Alt Z</kbd>), or choose <strong>Delete</strong> from the <strong>⋮</strong> menu. <strong>To move a dish</strong>, select it and use the up and down arrows on the toolbar. <strong>A description is optional</strong> — delete it if you do not want one.</p>
<h3>Add a whole menu or course</h3>
<p>Pato has ready-made menu sections — lunch and dinner, a drinks list, a breakfast menu, a counter menu for a bakery or café, a pizzeria menu and more.</p>
<ol class="pato-docs-steps">
<li>Click where the new section should go, then click the blue <strong>+</strong> in the top-left corner.</li>
<li>Open the <strong>Patterns</strong> tab and choose <strong>Pato: menus</strong>.</li>
<li>Click a preview to insert it, or drag it into place, then type over the dishes.</li>
</ol>
{{fig:inserter}}
HTML
);

$sections .= pato_docs_section( '05', 'editing', 'Find your way around a page', <<<'HTML'
<p>Every page Pato builds is a stack of sections — an opening photograph, an introduction, the menu, a gallery, a booking panel — and every section is made of ordinary blocks.</p>
<h3>List View: the outline of the page</h3>
<p>Click <strong>Document Overview</strong> (the three stacked lines near the top left) or press <kbd>⌃ ⌥ O</kbd> (<kbd>Shift Alt O</kbd>). A list of every block on the page opens on the left. Click an arrow to open a section, and click any row to select that block — the easy way to reach text inside a column or on top of a photograph.</p>
{{fig:listview}}
<p>In List View you can <strong>drag a section</strong> up or down the page, and use each row’s <strong>⋮</strong> menu to duplicate, delete or hide it. Some sections carry a small label such as <em>about</em>, <em>menu</em> or <em>gallery</em>. That is the section’s anchor, set under <strong>Advanced → HTML anchor</strong> in the block settings: a button linking to <code>#menu</code> scrolls straight to it. Keep it if you move the section.</p>
<h3>Add a section</h3>
<p>Click the blue <strong>+</strong>, open <strong>Patterns</strong>, and browse <strong>Pato: sections</strong> — the chefs, takeaway, wine nights, private dining, questions and answers, the map, opening hours, a booking panel, the latest blog posts and more — or <strong>Pato: pages</strong> for a whole page at once. Insert it, then edit it like anything else.</p>
<h3>Change a photograph</h3>
<p>Click the photograph and choose <strong>Replace</strong> on the toolbar, then <strong>Open Media Library</strong> or <strong>Upload</strong>. On an opening banner (a Cover block), the <strong>Focal point</strong> picker in the sidebar decides which part of the photograph stays in view on a phone. Fill in <strong>Alternative text</strong> for every photograph: a short description for people who cannot see it, and for search engines.</p>
HTML
);

$sections .= pato_docs_section( '06', 'reservations', 'Bookings: the reservation form', <<<'HTML'
<p>The booking form is built into Pato — no plugin — and is already on the Home, Reservation and Contact pages.</p>
{{fig:form}}
<p>It asks for a name, email address, phone number, date, time, number of people and an optional message; name, email, date, time and number of people are required. It is checked on your server, protected from spam bots by a hidden trap field and a security token, and works with JavaScript turned off.</p>
<h3>Where bookings go</h3>
<p>Each booking is emailed to the <strong>Administration Email Address</strong> in <strong>Settings → General</strong>. Change that address and bookings follow it. The email’s <em>reply-to</em> is the guest, so pressing Reply in your inbox answers them.</p>
<h3>Test it before you open</h3>
<ol class="pato-docs-steps">
<li>Open your Reservation page in a private browser window, so you see it as a guest does.</li>
<li>Fill in the form with your own email address and click <strong>Book a table</strong>.</li>
<li>Check the page confirms the booking, then check the email arrives at the address in Settings → General — look in the spam folder too.</li>
</ol>
<div class="pato-docs-tip"><p><strong>No email?</strong> Many hosts do not send WordPress email reliably. Install an SMTP plugin, such as FluentSMTP or WP Mail SMTP, and connect it to your email provider. Pato sends through WordPress’s own <code>wp_mail()</code>, so whatever fixes a missing password-reset email fixes bookings too.</p></div>
<h3>Put the form on another page</h3>
<p>Add a <strong>Shortcode</strong> block and type <code>&#91;pato_reservation_form&#93;</code>. The form is a shortcode on purpose: it is built fresh each time the page is shown, so it keeps working however the page around it is edited.</p>
<h3>For developers: send bookings somewhere else</h3>
<p>Bookings can go to a booking system, a CRM, a spreadsheet or a webhook instead of email. Add this to a child theme’s <code>functions.php</code> or a small plugin:</p>
<pre>add_filter( 'pato_reservation_handlers', function ( $handled, $booking ) {
	// $booking has: name, email, phone, date, time, people, message
	my_crm_create_booking( $booking );
	return true; // handled, so Pato sends no email of its own
}, 10, 2 );</pre>
<div class="pato-docs-table"><table><thead><tr><th>Filter</th><th>Use it to</th></tr></thead><tbody>
<tr><td><code>pato_reservation_fields</code></td><td>Add, remove, relabel or reorder the form’s fields</td></tr>
<tr><td><code>pato_reservation_handlers</code></td><td>Send the booking elsewhere; return <code>true</code> to skip the email</td></tr>
<tr><td><code>pato_reservation_email_to</code></td><td>Send bookings to an address other than the site administrator’s</td></tr>
<tr><td><code>pato_reservation_email_subject</code></td><td>Change the email’s subject line</td></tr>
<tr><td><code>pato_reservation_email_body</code></td><td>Change the email’s text</td></tr>
</tbody></table></div>
HTML
);

$sections .= pato_docs_section( '07', 'hours', 'Opening hours, address and map', <<<'HTML'
<p>Opening hours appear in two places: the <strong>Opening hours and address</strong> section on the Contact and Reservation pages (and in the Café, Pizzeria and Bakery starters), and the <strong>Opening times</strong> column of the footer, which shows on every page. Update both.</p>
<h3>On a page</h3>
<ol class="pato-docs-steps">
<li>Open the page and click the list of hours. It is a <strong>Custom HTML</strong> block, so you see its code.</li>
<li>Change the text between the tags: each <code>&lt;dt&gt;</code> holds the days and the <code>&lt;dd&gt;</code> after it holds the hours. Copy a <code>&lt;dt&gt;…&lt;/dt&gt;&lt;dd&gt;…&lt;/dd&gt;</code> pair to add a line; delete one to remove it.</li>
<li>Click <strong>Preview</strong> on the block’s toolbar to check it, then <strong>Save</strong>.</li>
</ol>
<pre>&lt;dt&gt;Monday – Friday&lt;/dt&gt;&lt;dd&gt;11:00 – 23:00&lt;/dd&gt;
&lt;dt&gt;Sunday&lt;/dt&gt;&lt;dd&gt;Closed&lt;/dd&gt;</pre>
<h3>In the footer</h3>
<p>Go to <strong>Appearance → Editor → Patterns</strong>, choose <strong>Footer</strong> under template parts, and edit the hours the same way. The footer’s phone number, email address and street address are in Custom HTML blocks too: change the visible text and the <code>tel:</code> and <code>mailto:</code> links beside it. The footer is shared, so one save updates every page.</p>
<h3>The map</h3>
<p>The map is an OpenStreetMap embed, so it needs no API key and no billing account. It is a <strong>Custom HTML</strong> block. To show your own address:</p>
<ol class="pato-docs-steps">
<li>Find your place on <a href="https://www.openstreetmap.org/" target="_blank" rel="noopener">openstreetmap.org</a>, right-click it and choose <strong>Show address</strong>. The latitude and longitude appear at the top of the panel on the left.</li>
<li>Click the map block to see its code. In the <code>src</code> address, replace the two numbers after <code>marker=</code> with your latitude and longitude, keeping the <code>%2C</code> between them.</li>
<li>The four numbers after <code>bbox=</code> are the edges of the map: west, south, east, north. About 0.005 either side of your point gives a street-level view.</li>
</ol>
<pre>…embed.html?bbox=-74.0170%2C40.6990%2C-74.0070%2C40.7100&amp;layer=mapnik&amp;marker=40.704644%2C-74.011987</pre>
<p>Prefer Google Maps? Delete the block, add a new Custom HTML block, and paste the code from Google Maps’ <strong>Share → Embed a map</strong>.</p>
HTML
);

$sections .= pato_docs_section( '08', 'header', 'Logo, header, footer and navigation', <<<'HTML'
<p>The header and footer are <strong>template parts</strong>: edit one once and every page follows.</p>
{{fig:parts}}
<h3>Add your logo</h3>
<ol class="pato-docs-steps">
<li>Go to <strong>Appearance → Editor → Patterns</strong> and choose <strong>Header</strong>.</li>
<li>Click the grey logo placeholder beside the site name, and upload your logo or pick it from the Media Library.</li>
<li>Drag the handle on the logo’s corner to resize it, then click <strong>Save</strong>.</li>
</ol>
{{fig:header}}
<p>The name beside the logo is your <strong>Site Title</strong> from <strong>Settings → General</strong>. If your logo already spells out the name, select the title in the header and delete it. Until you add a logo, the placeholder shows only in the editor, never to visitors.</p>
<h3>Change the navigation menu</h3>
<p>Go to <strong>Appearance → Editor → Navigation</strong> and open the <strong>Primary</strong> menu.</p>
<ul>
<li><strong>Reorder links</strong> with <strong>Move up</strong> and <strong>Move down</strong> in the <strong>⋮</strong> menu beside each link.</li>
<li><strong>Add a link</strong> with the <strong>+</strong> under the list: search for a page, or paste any web address.</li>
<li><strong>Remove a link</strong> from its <strong>⋮</strong> menu. The page itself is not deleted.</li>
<li><strong>Rename a link</strong> in the header: open <strong>Patterns → Header</strong>, click the link in the menu and type over it. The page’s own title does not change.</li>
</ul>
{{fig:nav}}
<p>On a phone the menu folds into a single button that opens it full-screen. That happens by itself.</p>
<h3>The two buttons on the right</h3>
<p>The moon is the visitor’s <a href="#dark-mode">dark mode</a> switch. <strong>Book a table</strong> is an ordinary Button block: click it to change its words, and use the link button on its toolbar to point it somewhere else — a booking service, say, or a phone number written as <code>tel:+441234567890</code>.</p>
HTML
);

$sections .= pato_docs_section( '09', 'styles', 'Colours and fonts', <<<'HTML'
<p>Colours and fonts are set once for the whole site in <strong>Appearance → Editor → Styles</strong>. Click <strong>Styles</strong>, then <strong>Browse styles</strong>.</p>
{{fig:styles}}
<p>Click a colour variation and the preview on the right changes immediately. Your live site does not change until you click <strong>Save</strong>, so try as many as you like.</p>
{{fig:palettes-gif}}
{{fig:browse}}
<h3>The eight palettes</h3>
<div class="pato-docs-table"><table><thead><tr><th>Palette</th><th>Background</th><th>Buttons and prices</th></tr></thead><tbody>
<tr><td><strong>Ember</strong> (the theme’s own colours)</td><td>Light</td><td>Tomato red</td></tr>
<tr><td><strong>Olive</strong></td><td>Light</td><td>Olive green</td></tr>
<tr><td><strong>Vineyard</strong></td><td>Light</td><td>Deep wine</td></tr>
<tr><td><strong>Charcoal</strong></td><td>Light</td><td>Charcoal grey</td></tr>
<tr><td><strong>Harvest</strong></td><td>Light</td><td>Amber brown</td></tr>
<tr><td><strong>Seaside</strong></td><td>Light</td><td>Teal</td></tr>
<tr><td><strong>Midnight</strong></td><td>Dark</td><td>Coral</td></tr>
<tr><td><strong>Cellar</strong></td><td>Dark</td><td>Warm amber</td></tr>
</tbody></table></div>
<p>Every palette was checked before it shipped: body text, secondary text, links and button labels all meet WCAG AA contrast on every background the design uses, in light mode and in dark mode.</p>
<h3>The five font pairings</h3>
<p>Pato uses <strong>Montserrat</strong> for headings, <strong>Poppins</strong> for text and <strong>Courgette</strong> for the handwritten lines above section titles, such as “Our menu”. All three are served from your own site, not from Google. The typography variations rearrange them:</p>
<ul>
<li><strong>Montserrat &amp; Poppins</strong> — the default: Montserrat headings, Poppins text.</li>
<li><strong>Poppins throughout</strong> — rounder and softer.</li>
<li><strong>Montserrat throughout</strong> — tighter and more formal.</li>
<li><strong>Poppins headings</strong> — Poppins headings over Montserrat text.</li>
<li><strong>No script</strong> — the handwritten lines use Montserrat instead of Courgette, for a plainer look.</li>
</ul>
<h3>Change one colour, or undo</h3>
<p>To adjust a single colour, open <strong>Styles → Colors → Edit palette</strong> and click a swatch. Pato names its colours by job — <em>Base</em> is the page, <em>Contrast</em> the text, <em>Primary</em> the buttons and prices — so changing one swatch changes it everywhere it is used.</p>
<p>To undo, click <strong>Revisions</strong> at the bottom of the Design panel and go back to any earlier save, or open the <strong>⋮</strong> menu at the top of Styles and choose <strong>Reset styles</strong> to return to the theme’s own.</p>
HTML
);

$sections .= pato_docs_section( '10', 'dark-mode', 'Dark mode', <<<'HTML'
<p>The moon in the header lets each visitor choose light or dark. It is separate from your palette: dark mode lifts the palette you chose instead of replacing it, so an Olive site stays olive and only the backgrounds and text change.</p>
{{fig:dark-gif}}
{{fig:dark}}
<ul>
<li>Until a visitor clicks it, it follows their phone or computer’s own light or dark setting.</li>
<li>Once they click, it remembers their choice on that device.</li>
<li>It is applied before the page is drawn, so a phone in dark mode never flashes white.</li>
</ul>
<p><strong>To remove the switch</strong>, add <code>add_filter( 'pato_enable_dark_mode', '__return_false' );</code> to a child theme or a small plugin. <strong>To move it</strong>, edit the header and drag the button: it is an ordinary Button block with the class <code>pato-scheme-toggle</code>, and any button with that class becomes the switch.</p>
HTML
);

$sections .= pato_docs_section( '11', 'templates', 'Page templates', <<<'HTML'
<p>A template decides what surrounds a page’s content: the header, a banner, a sidebar, the footer. Pato ships 14 templates. For a page you choose between three:</p>
<ul>
<li><strong>Page</strong> — a photograph banner with the page’s title on it, above the content.</li>
<li><strong>Page without title</strong> — no title and no banner. Use it when the page opens with its own photograph; every page Pato builds uses it.</li>
<li><strong>Page with sidebar</strong> — the content, with search, categories and recent posts on the right.</li>
</ul>
<p>To switch, open the page, click the <strong>Page</strong> tab in the sidebar, click the template’s name next to <strong>Template</strong>, and choose <strong>Change template</strong>.</p>
{{fig:template}}
<p>Posts can use <strong>Post with sidebar</strong> the same way. The blog, archives, categories, tags, author pages, search results and the 404 page each have their own designed template, which you can change in <strong>Appearance → Editor → Templates</strong>.</p>
HTML
);

$sections .= pato_docs_section( '12', 'shop', 'Selling gift cards and more', <<<'HTML'
<p>Install WooCommerce and Pato styles it — the shop, product pages, cart and checkout take the theme’s colours, fonts and buttons, so the shop does not look like a different website. There is nothing to configure.</p>
<p>That covers both the block-based shop WooCommerce builds on a block theme and the older shortcode pages, so it works whichever way your shop is set up. Gift cards, a cookbook, merchandise or collection orders are the usual reasons a restaurant wants one. Without WooCommerce, none of this styling is loaded.</p>
HTML
);

$sections .= pato_docs_section( '13', 'forms', 'Contact forms', <<<'HTML'
<p>Pato’s own booking form needs no plugin. For a separate contact form, install any of these and Pato styles it to match: Contact Form 7, WPForms, Gravity Forms, Fluent Forms, Forminator, Ninja Forms, Formidable Forms or HappyForms. Add the plugin’s block to the page and the form takes the theme’s fields, button and spacing.</p>
HTML
);

$sections .= pato_docs_section( '14', 'updates', 'Updates', <<<'HTML'
<p>Pato is not in the WordPress.org theme directory, so it checks <code>updates.colorlib.com</code> for new versions every 12 hours. A new version then appears in <strong>Dashboard → Updates</strong> and on <strong>Appearance → Themes</strong>, like any other theme, and updates with one click.</p>
<p>The check sends the theme’s version, your WordPress and PHP versions, your site’s language, whether it is a multisite, and a one-way hash of your site address. It sends no personal data and no site name, and the hash cannot be turned back into your address. To switch it off, add <code>add_filter( 'pato_check_for_updates', '__return_false' );</code> — you will then not hear about new versions.</p>
<p><strong>Updates change the theme, not what you wrote.</strong> Your pages are your content. The single deliberate exception is version 1.1.1, which repairs pages built by 1.0.0 and 1.1.0: in those, every spacer showed <em>“This block contains unexpected or invalid content”</em> in the editor. The repair puts back one missing character in each spacer’s settings the first time an administrator opens the dashboard, and changes nothing a visitor can see.</p>
HTML
);

$sections .= pato_docs_section( '15', 'shortcuts', 'Keyboard shortcuts worth knowing', <<<'HTML'
<div class="pato-docs-table"><table><thead><tr><th>To</th><th>Mac</th><th>Windows</th></tr></thead><tbody>
<tr><td>Save</td><td><kbd>⌘ S</kbd></td><td><kbd>Ctrl S</kbd></td></tr>
<tr><td>Undo / redo</td><td><kbd>⌘ Z</kbd> / <kbd>⇧ ⌘ Z</kbd></td><td><kbd>Ctrl Z</kbd> / <kbd>Ctrl Shift Z</kbd></td></tr>
<tr><td>Select the text in a block (press again for the whole page)</td><td><kbd>⌘ A</kbd></td><td><kbd>Ctrl A</kbd></td></tr>
<tr><td>Duplicate the selected block</td><td><kbd>⇧ ⌘ D</kbd></td><td><kbd>Ctrl Shift D</kbd></td></tr>
<tr><td>Delete the selected block</td><td><kbd>⌃ ⌥ Z</kbd></td><td><kbd>Shift Alt Z</kbd></td></tr>
<tr><td>Add a block before / after</td><td><kbd>⌥ ⌘ T</kbd> / <kbd>⌥ ⌘ Y</kbd></td><td><kbd>Ctrl Alt T</kbd> / <kbd>Ctrl Alt Y</kbd></td></tr>
<tr><td>Open List View</td><td><kbd>⌃ ⌥ O</kbd></td><td><kbd>Shift Alt O</kbd></td></tr>
<tr><td>Show every shortcut</td><td><kbd>⌃ ⌥ H</kbd></td><td><kbd>Shift Alt H</kbd></td></tr>
<tr><td>Add a block by name</td><td colspan="2">Type <kbd>/</kbd> on an empty line, then the block’s name</td></tr>
</tbody></table></div>
HTML
);

$sections .= pato_docs_section( '16', 'troubleshooting', 'If something is not where you expect', <<<'HTML'
<p><strong>The editor says “This block contains unexpected or invalid content”.</strong> On a page built by Pato 1.0.0 or 1.1.0 that is a known fault in the spacers. Update to 1.1.1 or later and open the dashboard once; the pages are repaired automatically. Clicking <strong>Attempt recovery</strong> on each block also fixes it.</p>
<p><strong>There is no Appearance → Starter sites.</strong> It appears only while Pato is the active theme, and only for users who can change the site’s design — administrators.</p>
<p><strong>My front page shows the latest blog posts.</strong> Go to <strong>Settings → Reading</strong>, choose <em>A static page</em>, and pick Home (or your starter) as the homepage.</p>
<p><strong>I imported a starter, but the Menu page still has the old dishes.</strong> Importing changes only the front page. Edit the Menu page too, or remove it.</p>
<p><strong>My colours changed after importing a starter.</strong> Each starter brings its own palette and fonts. Pick different ones in <a href="#styles">Styles</a>, or use <strong>Revisions</strong> there to go back.</p>
<p><strong>Bookings are not arriving.</strong> Check the address in Settings → General and your spam folder, then test whether WordPress can send mail at all — most hosts need an SMTP plugin. If WordPress cannot send a password reset, it cannot send a booking.</p>
<p><strong>The header is see-through on some pages and solid on others.</strong> That is deliberate: it sits over the photograph when a page opens with one, and becomes a solid bar when there is nothing to sit on, such as a blog post.</p>
<p><strong>I edited a pattern file in the theme and my page did not change.</strong> Starter content is copied into your pages when it is created, so it is yours from then on. Edit the page, not the pattern.</p>
<p><strong>I saved, but visitors still see the old version.</strong> A caching plugin or your host’s cache is serving a stored copy. Clear it from the plugin’s or host’s settings.</p>
HTML
);

$toc = '<ol class="pato-docs-toc">'
	. '<li><a href="#quick-start">The short version</a></li>'
	. '<li><a href="#install">Install Pato</a></li>'
	. '<li><a href="#starters">Start from one of six restaurants</a></li>'
	. '<li><a href="#menu">Change dishes and prices</a></li>'
	. '<li><a href="#editing">Find your way around a page</a></li>'
	. '<li><a href="#reservations">Bookings: the reservation form</a></li>'
	. '<li><a href="#hours">Opening hours, address and map</a></li>'
	. '<li><a href="#header">Logo, header, footer and navigation</a></li>'
	. '<li><a href="#styles">Colours and fonts</a></li>'
	. '<li><a href="#dark-mode">Dark mode</a></li>'
	. '<li><a href="#templates">Page templates</a></li>'
	. '<li><a href="#shop">Selling gift cards and more</a></li>'
	. '<li><a href="#forms">Contact forms</a></li>'
	. '<li><a href="#updates">Updates</a></li>'
	. '<li><a href="#shortcuts">Keyboard shortcuts</a></li>'
	. '<li><a href="#troubleshooting">If something is not where you expect</a></li>'
	. '</ol>';

$content = '[vc_row el_id="top" css=".vc_custom_patodoc00{padding-top:44px !important;padding-bottom:30px !important;background-color:#faf7f4 !important;}"][vc_column width="1/1"]'
	. '[vcex_heading text="Pato documentation" tag="h2" font_size="40px" text_align="center" bottom_margin="16px" font_weight="700"]'
	. '[vc_column_text css=".vc_custom_patodoc00t{text-align:center !important;font-size:18px !important;max-width:760px !important;margin-left:auto !important;margin-right:auto !important;}"]'
	. $css
	. 'Everything you need to take a restaurant site from install to open, in the order you will need it, with a screenshot or a short animation for every step. Pato is a free block theme for restaurants, cafés, bakeries and bars.'
	. '[/vc_column_text]'
	. '[vc_column_text css=".vc_custom_patodoc00b{text-align:center !important;margin-top:22px !important;}"]'
	. '[vc_btn title="Download Pato" style="flat" color="green" link="url:' . $download_enc . '|title:Download%20Pato|target:_blank" css=".vc_custom_patodoc00c{' . $btn_css . '}" i_icon_fontawesome="fa fa-download" add_icon="true"]'
	. '[vc_btn title="Live demo" style="flat" color="grey" link="url:' . $demo_enc . '|title:Live%20demo|target:_blank" css=".vc_custom_patodoc00d{' . $btn_css . '}" i_icon_fontawesome="fa fa-eye" add_icon="true"]'
	. '[/vc_column_text]'
	. '[vc_column_text css=".vc_custom_patodoc00m{text-align:center !important;font-size:14px !important;color:#6b6560 !important;margin-top:6px !important;}"]Written for Pato 1.1.1 on WordPress 7.1. Every screenshot is from a fresh install.[/vc_column_text]'
	. '[/vc_column][/vc_row]'
	. '[vc_row el_id="contents" el_class="pato-docs" css=".vc_custom_patodoc0c{padding-top:34px !important;padding-bottom:4px !important;}"][vc_column width="1/1"]'
	. '[vcex_heading text="On this page" tag="h2" font_size="24px" text_align="center" bottom_margin="16px" font_weight="700"]'
	. '[vc_column_text]' . $toc . '[/vc_column_text]'
	. '[/vc_column][/vc_row]'
	. $sections
	. '[vc_row el_class="pato-docs" css=".vc_custom_patodoc99{padding-top:34px !important;padding-bottom:56px !important;}"][vc_column width="1/1"]'
	. '[vcex_heading text="Still stuck?" tag="h2" font_size="28px" bottom_margin="14px" font_weight="700"]'
	. '[vc_column_text css=".vc_custom_patodoc99t{max-width:860px !important;}"]'
	. '<p>Ask on the <a href="' . esc_url( $support ) . '" target="_blank" rel="noopener">Colorlib support forum</a>. Include your WordPress version, your PHP version, what you did, and what you expected to happen — a screenshot helps.</p>'
	. '[/vc_column_text][/vc_column][/vc_row]';

// Refuse to publish a page with a hole in it.
$problems = $GLOBALS['pato_docs_missing'];
if ( preg_match_all( '/%%[A-Z_]+%%/', $content, $left ) ) {
	$problems = array_merge( $problems, array_unique( $left[0] ) );
}
if ( $problems && ! getenv( 'PATO_DOCS_ALLOW_MISSING' ) ) {
	echo 'NOT SAVED. Missing: ' . implode( ', ', $problems ) . "\n";
	return;
}

$kses = has_filter( 'content_save_pre', 'wp_filter_post_kses' );
if ( $kses ) {
	kses_remove_filters();
}

$args = array(
	'post_title'   => 'Pato documentation',
	'post_name'    => $slug,
	// wp_slash(): wp_insert_post() and wp_update_post() unslash, and the code
	// samples above carry backslash-free PHP today but would not stay that way.
	'post_content' => wp_slash( $content ),
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
echo 'length: ' . strlen( $saved ) . ', saved intact: ' . ( $saved === $content ? 'yes' : 'NO' ) . "\n";
echo 'sections: ' . substr_count( $saved, '[vcex_heading' ) . ', figures: ' . substr_count( $saved, '<figure' ) . "\n";
echo 'unbalanced rows: ' . ( substr_count( $saved, '[vc_row' ) - substr_count( $saved, '[/vc_row]' ) ) . "\n";

preg_match_all( '#<a[^>]+href="([^"]*)"#', $rendered, $hrefs );
$dead = array_filter(
	array_unique( $hrefs[1] ),
	function ( $h ) {
		return '' === $h || '#' === $h || false !== strpos( $h, 'http://https' ) || 0 === strpos( $h, 'url:' );
	}
);
echo 'rendered links: ' . count( array_unique( $hrefs[1] ) ) . ', dead: ' . count( $dead ) . "\n";

// Every in-page link must have a row to land on.
preg_match_all( '/href="#([a-z0-9-]+)"/', $saved, $anchors );
preg_match_all( '/el_id="([a-z0-9-]+)"/', $saved, $ids );
$orphans = array_diff( array_unique( $anchors[1] ), $ids[1] );
echo 'anchor links: ' . count( array_unique( $anchors[1] ) ) . ', without a target: ' . ( $orphans ? implode( ', ', $orphans ) : 'none' ) . "\n";
