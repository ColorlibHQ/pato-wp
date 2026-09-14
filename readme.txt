=== Pato ===

Contributors: colorlib
Requires at least: 6.6
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.1.0
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: food-and-drink, blog, full-site-editing, block-patterns, block-styles, template-editing, wide-blocks, accessibility-ready, translation-ready, custom-colors, custom-menu, custom-logo, featured-images, threaded-comments, one-column, two-columns, right-sidebar, rtl-language-support, sticky-post, theme-options

A block theme for restaurants, bistros, cafés and bars.

== Description ==

Pato is a full-site-editing theme for places that serve food. It ships the
layouts a restaurant site actually needs — lunch and dinner menus with prices,
a reservation form, opening hours, chef profiles, a gallery and guest reviews —
as block patterns you edit like any other content.

Eight colour palettes and three type pairings, every one of them checked
against WCAG AA for body text, secondary text, links and button labels on
every ground the design puts them on.

= Menus without a plugin =

A menu row is a heading, a dotted leader and a price, built from core blocks.
There is no custom post type and no menu builder to learn: the words are
paragraphs, and anyone who can edit a page can change a price.

= Reservations without a plugin =

The booking form is part of the theme. It validates on the server, protects
itself with a nonce and a honeypot, and emails the site's admin address. It
works with JavaScript turned off.

Sites that already use a booking plugin, a CRM or a webhook can take the
booking over with one filter:

`add_filter( 'pato_reservation_handlers', function ( $handled, $booking ) {
	// $booking has name, email, phone, date, time, people, message.
	return true; // Pato then sends no email of its own.
}, 10, 2 );`

`pato_reservation_fields` adds or relabels fields, and
`pato_reservation_email_to`, `_subject` and `_body` adjust the email.

== Frequently Asked Questions ==

= Do I need a plugin? =

No. Menus, reservations, galleries, opening hours and dark mode are all in the
theme. WooCommerce is styled if you add it, and not required.

= How do the starter sites work? =

Appearance → Starter sites. Each one builds a front page and applies its
palette and type pairing. It never edits or deletes a page you already have, so
you can import more than one and keep whichever you prefer. Everything it
creates is ordinary editable content.

= Which form plugins does it style? =

Contact Form 7, WPForms, Gravity Forms, Fluent Forms, Forminator, Ninja Forms,
Formidable and HappyForms are mapped onto the theme's own colours and spacing,
so a form block does not look like a different website.

= Can I put this on WordPress.org? =

Not as it stands. Three of Theme Check's REQUIRED findings are the deliberate
consequence of how this theme is built and distributed:

* `Update URI` in style.css — it updates from updates.colorlib.com, which is
  the point of that header, and the directory does not allow it.
* `add_shortcode()` in inc/reservations.php — the reservation form has to be a
  shortcode, because patterns are expanded into real post content and PHP
  inside stored content never runs. The directory considers shortcodes plugin
  territory, and it is right about the trade-off: deactivate Pato and
  `[pato_reservation_form]` will show as text until you delete it.
* The bundled photographs are Unsplash-licensed rather than GPL.

Each has a fix if you want to submit it: drop the header, move the form into a
companion plugin, and swap the photographs.

= Does it check for updates? =

Yes, twice a day, because it is distributed outside the WordPress.org theme
directory. It sends the theme, WordPress and PHP versions, the locale and a
one-way hash of the site URL — no personal data and no site name. The
`pato_check_for_updates` filter switches it off.

== Copyright ==

Pato WordPress Theme, (C) 2026 Colorlib.
Pato is distributed under the terms of the GNU GPL v2 or later.

Montserrat, Poppins and Courgette
  Licence: SIL Open Font License 1.1
  Source: https://fonts.google.com/
  Each family's licence is at assets/fonts/<family>/OFL.txt

Photographs in assets/images/
  Licence: Unsplash License (https://unsplash.com/license)
  Source: https://unsplash.com/
  These are the photographs the Pato HTML template ships, credited by Colorlib
  to Unsplash. The Unsplash License permits commercial use and redistribution
  but is not the GPL, which is why Theme Check reports them -- see the note in
  readme.txt. Replacing them with your own is a matter of dropping new files
  into assets/images/ under the same names.

== Changelog ==

= 1.1.0 =
* Six one-click starter sites: bistro, fine dining, café, pizzeria, bar, bakery.
* 29 more patterns, including FAQ, set menus, private dining, press, gift cards
  and a keyless map.
* Visitor dark mode, applied before first paint.
* WooCommerce support, covering both the block and classic shop markup.
* Two more type pairings, five in all.
* New `overlay` colour, so text on dark grounds reads in every palette.

= 1.0.0 =
* Initial release.
