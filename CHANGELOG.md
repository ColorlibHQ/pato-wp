# Changelog

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
