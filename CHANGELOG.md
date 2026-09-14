# Changelog

## [1.1.0] - September 2026

Brings the theme to the standard Academia set: 64 patterns, six starter sites,
dark mode and WooCommerce.

### Added

- **Six starter sites** — bistro, fine dining, café, pizzeria, bar, bakery.
  Each has its own hero, its own introduction, and its own palette and type
  pairing. Appearance → Starter sites imports one as editable content; it never
  touches a page you already have.
- **29 patterns**: FAQ (core/details, no JavaScript), set menu for groups,
  weekly specials, private dining, press quotes, map, delivery and collection,
  gift cards, allergen note, opening-hours strip, booking CTA, plus a hero and
  an introduction per starter.
- **Visitor dark mode.** It lifts the active palette rather than replacing it,
  so an Olive site stays olive. Applied by an inline script before first paint,
  so there is no flash; follows the system setting until someone chooses.
- **WooCommerce support**, styled for both the block-based shop a block theme
  actually gets and the classic markup a shortcode produces. Loaded only on
  shop pages.
- **Two more type pairings**, five in all.
- **`overlay` colour.** Text on a dimmed photograph or the dark footer now uses
  a slug that is near-white in every palette.

### Fixed

- **Dark palettes were unreadable.** Every cover headline and the whole footer
  came out black-on-black in Midnight and Cellar: they asked for `base`, which
  is the page background and therefore nearly black in a dark palette. 24
  references, plus the footer and the outline button on covers.
- **Starter imports stored pattern references, not content** — they rendered,
  but the editor showed one opaque block per section, so the copy could not be
  edited without opening a theme file. Expansion is recursive now, guarded
  against a pattern that references itself.
- **Global styles were written unslashed.** `wp_update_post()` unslashes what
  it is given, so the JSON came back with its backslashes eaten and stopped
  parsing — palettes silently never applied.
- **Dead buttons on three of six starters**: every hero said "Book a table" and
  half the starters have no reservation section. Each hero has its own buttons
  now, sections carry anchors, and a test asserts every in-page link resolves.
- **"Get directions" was an in-page link** to a map section that three starters
  do not have. It opens a map service.
- **Cover dim classes must round to the nearest ten**, as core's
  `dimRatioToClass()` does; `dimRatio: 62` has to emit `has-background-dim-60`.
- **An empty core/button renders nothing at all**, which is why the dark-mode
  toggle vanished from the header until it was given a label.
- The dark-mode primary is `color-mix(flame 64%, white)`, measured: at 82% the
  worst palette was 3.30:1.

### Tooling

- `.dev/contrast-rendered.mjs` measures contrast on a **rendered page**, in
  light and dark, resolving each text node's effective background. The palette
  maths could not have caught the `base`-on-dark bug — the numbers were right
  and the wrong slug was in use.

## [1.0.0] - September 2026

First release. A block theme for restaurants, built from the Pato HTML
template (`preview.colorlib.com/theme/pato/`, EDD 18863).

The design was rebuilt rather than ported. Pato's identity — the red, the
script eyebrow above each section title, the dotted leader between a dish and
its price — is kept. Its measurements, its type scale and its 2017 utility
classes are not.

### The palette is measured, not copied

The template's red is `#ec1d25`. Measured against white that is **4.40:1**,
which fails WCAG AA for body text and for a button label in either direction.
The theme keeps the hue and darkens it by the smallest amount that passes:
`primary` is `#d41b22` at 5.28:1, and it is not an invention — it is the
template's own hover shade. `#ec1d25` survives as `flame`, for large
decorative fills where AA-large applies.

All eight palettes are checked by the generator itself, on every pair the
design actually produces, and it refuses to write a palette that fails. That
caught two: Midnight and Cellar are dark palettes whose primaries are bright,
where a light button label measured 3.28:1 and 2.32:1.

### Included

- 35 patterns: menus, hero, welcome, reservations, opening hours, gallery,
  chefs, reviews, events, latest posts, newsletter, six whole pages and the
  hidden partials the templates use.
- 14 templates and 3 template parts.
- 8 colour palettes × 3 type pairings, all listed as partials in the Site
  Editor.
- 4 section style variations (soft, dark, card, elevated) and 9 block styles.
- A reservation form with server-side validation, a nonce, a honeypot and
  filters for handing the booking to a plugin, CRM or webhook. Works with
  JavaScript off.
- Form styling for eight form plugins, so a form block matches the theme.
- Self-hosted Montserrat, Poppins and Courgette — 12 woff2 faces, 200 KB,
  split per unicode-range.
- Starter pages built on first activation, guarded so it never touches a site
  that already has content.

### Known Theme Check findings

Three REQUIRED, all deliberate: `Update URI` (distributed outside the
directory), `add_shortcode()` (the reservation form must survive being
expanded into post content), and Unsplash-licensed photographs. See readme.txt.
