# CLAUDE.md

Guidance for Claude Code working in this repository.

## What this is

**Pato 1.0.0**, a Colorlib **WordPress block theme** (full site editing) for
restaurants. 64 patterns, 14 templates, 8 colour palettes × 5 type pairings,
6 starter sites, dark mode, WooCommerce support, text domain `pato`. It is **not** a static HTML template — the Colorlib R2
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
| Six starter sites + the import screen | `inc/starter-sites.php` |
| Visitor dark mode | `inc/scheme.php` + `assets/css/scheme.css` + `assets/js/scheme-toggle.js` |
| WooCommerce | `inc/woocommerce.php` + `assets/css/woocommerce.css` |
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

## More traps, from the 1.1.0 work

- **`base` is the page background, not white.** Text on a dark ground or a
  photograph must use `overlay`. Using `base` made every cover headline and the
  entire footer black-on-black in the two dark palettes.
- **`wp_update_post()` unslashes.** Writing JSON to a post without `wp_slash()`
  eats the backslashes and the content stops parsing — silently.
- **An empty `core/button` renders nothing.** Give icon-only buttons a
  screen-reader label rather than empty text.
- **Cover `dimRatio` must round to the nearest ten in the CLASS** (core's
  `dimRatioToClass`), while the attribute keeps the exact value.
- **Patterns stored in post content must be expanded recursively**, or the
  editor shows one opaque block per section.
- Woo on a block theme renders **blocks**, not `ul.products`. Style both.
- **Form controls need `box-sizing: border-box` spelled out.** Neither
  WordPress nor this theme resets it for inputs, so `width: 100%` plus padding
  overflows by exactly the padding and border. It put every reservation field
  34px outside its grid cell.
- **Document-level overflow checks miss most overflow.** `scrollWidth >
  innerWidth` only catches a horizontally scrollable page; something spilling
  out of a panel in the middle of the page leaves the document the same width
  as the viewport. Use `.dev/overflow-check.mjs`, which compares each element
  against its parent's content box.
- Run `.dev/contrast-rendered.mjs` in light *and* dark (`PATO_DARK=1`) after
  any colour change. It parses `color(srgb ...)` as well as `rgb()`, because
  `color-mix()` returns the former and reading it as 0-255 reports everything
  at about 1.16:1.

## Theme Check: 3 expected REQUIRED findings

`Update URI` (outside the directory), `add_shortcode()` (the reservation form
must survive pattern expansion), Unsplash photographs. Each is explained in
readme.txt with what it would take to remove it.
