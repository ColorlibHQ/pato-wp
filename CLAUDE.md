# CLAUDE.md

Guidance for Claude Code working in this repository.

## What this is

**Pato 1.0.0**, a Colorlib **WordPress block theme** (full site editing) for
restaurants. 35 patterns, 14 templates, 8 colour palettes × 3 type pairings,
text domain `pato`. It is **not** a static HTML template — the Colorlib R2
preview/download flow and the HTML-template upgrade phases in the global
instructions do not apply here.

Built from the Pato HTML template at `~/Fresh Projects/pato-html`
(modernised to 3.0.0 first; see that repo's CHANGELOG). The design was
**rebuilt, not ported**.

Distribution is **outside WordPress.org**, like Unapp, Philosophy and Academia.
Three Theme Check REQUIRED findings follow directly from that and from having
no companion plugin; they are listed in readme.txt and are intended.

No build step, no npm, no SCSS. Every file is committed as-is.

## Commands

See README.md. The one that is easy to get wrong:

```bash
# ALWAYS both, in this order.
python3 .dev/build_patterns.py
PATO_PATTERNS="$PWD/patterns" WP_URL=http://local-wp.local \
  WP_USER=silkalns WP_PASS=... node .dev/normalize-blocks.mjs
```

Running the generator alone leaves markup the editor will flag as invalid.

## Local testing

`local-wp` (Local app). wp-cli needs Local's own PHP and its per-site MySQL
socket; `~/.local/bin/lwp` wraps that. Symlink the theme:

```bash
ln -sfn "$PWD" "/Users/silkalns/Local Sites/local-wp/app/public/wp-content/themes/pato"
```

⚠ **`local-wp` is a shared test site.** It carries WooCommerce, Jetpack and a
404-customizer plugin that replaces every 404 page site-wide, so a 404 test
there measures the plugin, not the theme. Something also rotates the active
theme unprompted. Activate, assert, capture, assert again — never trust a
capture you did not verify.

`inc/front-page-setup.php` will **not** build the starter site there, and that
is correct: it refuses any site that already has more than one page.

## Architecture

| Concern | Where |
| --- | --- |
| The whole design system — palette, type, spacing, shadows, element and block styles | `.dev/build_theme.py` → `theme.json` + `styles/**`. **Edit the generator, never the JSON.** |
| Every pattern | `.dev/build_patterns.py` + `.dev/patternlib.py` → `patterns/*.php` |
| Block markup helpers | `.dev/patternlib.py` |
| Reservation form, validation, filters | `inc/reservations.php` |
| Form-plugin styling (8 recognised) | `inc/forms.php` + `assets/css/forms.css` |
| Starter pages on activation | `inc/front-page-setup.php` |
| Self-hosted updates + install counting | `inc/updates.php` |
| Block style variations, and rules theme.json cannot express | `style.css` |

## Conventions that matter

- **Never hand-write block markup.** See README. The normaliser exists because
  every `core/image` with a border radius, every `core/cover`, and every
  `core/buttons` in the first cut of this theme parsed as invalid.
- **Section wrapper**: full-width constrained group with **top/bottom padding
  only**. `useRootPaddingAwareAlignments` is on, so left/right comes from root
  padding; adding it again double-pads.
- **`sp()` refuses spacing off the scale.** An undefined preset variable makes
  WordPress drop the declaration and the element silently falls back to its
  inherited gap.
- **Colours in patterns are palette slugs only**, so all eight colour
  variations restyle everything.
- **Never name a palette slug `border`, `text`, `background` or `link`** —
  WordPress emits bare utility classes with those names and a matching slug
  makes them collide (this bit Academia: its course price rendered in the
  border colour).
- **Style variation partials are all-or-nothing.** The Site Editor lists a
  variation under Colors or Typography only if it contains *nothing else*.
  That is why `build_color_variation()` adds a `styles` key only when
  measurement says the button label has to change.
- **Block style variations live in `style.css`**, not behind a `style_handle`:
  that option only enqueues while WordPress serves separate core block assets,
  and any plugin can switch that off site-wide (WooCommerce does on `local-wp`).

## Traps hit while building this

- **`wp_get_referer()` cannot bring a form back to its own page.** It returns
  false whenever the referer matches the current request URI, which is always
  true for a form posting to itself — so every booking redirected to the site
  root with a confirmation and no form in sight. The page URL is carried in a
  hidden field and validated with `wp_validate_redirect()`.
- **`core/page-list` has no limit.** In the footer it printed several hundred
  links on the test site. Replaced with a written list.
- **theme.json's link colour is unreadable on the dark footer** — `primary` is
  chosen to read on white. The footer sets its links to `inherit`.
- **An image with an aspect ratio does not fill its column** unless told to.
  One gallery photograph was 320px wide and rendered a third of the row narrow.
- **A ::after is the last flex child.** The dotted leader between a dish and
  its price needs explicit `order`, or it trails off to the right of the price.
- **Playwright's `fullPage` screenshot photographs lazy images as blank** — it
  stitches scrolled strips. Force `loading="eager"` and `decode()` first.
- **Deduplicate placeholder tokens when stashing PHP out of block markup.** A
  cover block carries the same `get_theme_file_uri()` call twice, in the
  comment and in the `<img>`; two different tokens make them disagree and every
  cover parses as invalid. That was a bug in the tooling, not the theme.

## Theme Check: 3 expected REQUIRED findings

`Update URI` (outside the directory), `add_shortcode()` (the reservation form
must survive pattern expansion), Unsplash photographs. Each is explained in
readme.txt with what it would take to remove it.
