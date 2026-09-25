# Changelog

## 1.1.2

- Update requests no longer name the site. WordPress's default User-Agent carries the site address; the update check and core's package download to updates.colorlib.com now send only the theme and WordPress versions, so the one-way site identifier is the only thing that tells installs apart.

## [1.1.1] - September 2026

Pages built by Pato no longer open in the editor with "This block contains
unexpected or invalid content".

Activating the theme and importing a starter site both copy pattern markup into
real pages with `wp_insert_post()`, which strips backslashes from what it is
given. Block attributes carry JSON escapes — every spacer stores its height as
`var(\u002d\u002dwp…)` — so the stored pages lost them, and the editor flagged
every spacer: eight on a starter home page. The front end renders from the
saved HTML, not the attributes, so it looked right and nobody noticed.

### Fixed
- Activation, the starter importer and the navigation menu now pass their
  content through `wp_slash()`.
- **Existing sites are repaired on update.** The first time an administrator
  opens the dashboard afterwards, Pato puts the missing backslash back in pages
  and menus where it was stripped. Only that one sequence inside block
  attributes is touched; the text of a page is never changed.

### Changed
- `.dev/validate-blocks.mjs` now checks stored pages and navigation menus as
  well as templates, parts and patterns — the check that would have caught this.

## [1.1.0] - September 2026

The six starter sites are now six restaurants rather than one restaurant in six
colours.

1.0.0 gave each starter its own opening screen and its own introduction, and
then reused a single menu, a single set of offers and a single gallery across
all of them. A pizzeria's menu read "Smoked salmon & leaf salad" and a bakery's
gallery was the same six photographs as the bar's. Each starter now has:

- **Its own menu.** The pizzeria lists a Margherita, a Diavola and a Marinara;
  the bar lists a Negroni and an espresso martini; the bakery lists sourdough
  and pain au chocolat, priced accordingly.
- **Its own weekly offers**, in the venue's own language — industry night at
  the bar, bake club at the bakery, aperitivo at the pizzeria.
- **Its own gallery**, six photographs that belong to that kind of place.

Measured across the six demo pages: no photograph and no dish now appears in
all six, and most pairs share nothing at all. The two that overlap are the café
and the bakery, which genuinely do serve the same cakes.

### Added

- 20 photographs, bringing the theme to 47.
- Galleries on the pizzeria and fine-dining starters, which had one and five
  photographs on a whole page.

### Fixed

- **Alt text described the wrong pictures.** The new photographs were named
  from guessed source filenames and about half were wrong — a bakery gallery
  captioned "whole cooked prawns", a bistro "bar" that was a mountain village
  at night. Every image was checked against its file and renamed for what it
  actually shows.
- Removed ten images nothing referenced.

## [1.0.0] - September 2026

First release. A block theme for restaurants, built from the Pato HTML
template (`preview.colorlib.com/theme/pato/`).

The design was rebuilt rather than ported. Pato's identity is kept — the red,
the script line above each section title, the dotted leader between a dish and
its price, the full-screen opening photograph with the header sitting on it.
Its 2017 measurements and utility classes are not.

### Included

- **64 patterns**: menus with prices, hero, welcome, reservations, opening
  hours, gallery, chefs, reviews, events, FAQ, set menus, weekly specials,
  private dining, press quotes, a keyless map, delivery, gift cards, allergen
  note, latest posts, newsletter, six whole pages and the partials the
  templates use.
- **Six starter sites** — bistro, fine dining, café, pizzeria, bar, bakery —
  each with its own hero, its own introduction and its own palette and type
  pairing. Appearance → Starter sites imports one as editable content; it never
  touches a page you already have.
- **14 templates** and 3 template parts.
- **8 colour palettes × 5 type pairings**, all listed as partials in the Site
  Editor, plus 4 section style variations and 9 block styles.
- **A reservation form that needs no plugin.** Server-side validation, a nonce,
  a honeypot, and filters for handing the booking to a plugin, CRM or webhook.
  Works with JavaScript off.
- **Visitor dark mode**, applied before first paint, lifting the active palette
  rather than replacing it.
- **WooCommerce support**, styled for both the block-based shop a block theme
  gets and the classic markup a shortcode produces.
- Form styling for eight form plugins.
- Self-hosted Montserrat, Poppins and Courgette — 12 woff2 faces, 200 KB,
  split per unicode-range.

### The palette is measured, not copied

The template's red is `#ec1d25`, which is 4.40:1 on white and fails WCAG AA for
body text and for a button label in either direction. `primary` is `#d41b22` at
5.28:1 — the template's own hover shade, not an invention — and `#ec1d25`
survives as `flame` for large decorative fills.

The generator checks every palette against every text/ground pair the design
produces and refuses to write one that fails. Contrast is then measured again
on a rendered page, in light and dark, because the palette maths cannot catch
the wrong slug being used on the wrong ground.

### Known Theme Check findings

Three REQUIRED, all deliberate: `Update URI` (distributed outside the
directory), `add_shortcode()` (the reservation form must survive being expanded
into post content), and Unsplash-licensed photographs. See readme.txt.
