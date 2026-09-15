# Documentation screenshots and animations

Everything on the colorlib.com documentation page was captured from a **fresh
WordPress install**, never from a working site: a shared sandbox shows other
themes' plugins in the admin menu and other sessions' content in the editor.

```bash
# A throwaway WordPress with this theme mounted and activated the real way,
# so activation builds the seven pages exactly as it does for a user.
npx -y @wp-playground/cli@3.1.54 server --port=9471 --php=8.3 --wp=latest \
  --mount-before-install="$PWD:/wordpress/wp-content/themes/pato" \
  --blueprint=.dev/docs-capture/blueprint.json --login
```

Run the scripts from a scratch directory with Playwright installed; they write
to `docs/` and `docs/gif/` there. `WPBASE` overrides the site URL.

| Script | Captures |
| --- | --- |
| `cap-admin.mjs` | Upload screen, pages after activation, Appearance menu, Starter sites, Reading settings |
| `cap-editor.mjs` | Price selected, List View, pattern inserter |
| `cap-template.mjs` | Page tab → Template menu |
| `cap-site-editor.mjs` | Styles, Browse styles, Navigation, Header part (and Templates/Parts) |
| `cap-prod-front.mjs` | Booking form, light/dark menu — from the live demo |
| `gif-price.mjs`, `gif-dark.mjs` | Frames for the price and dark-mode animations (nothing saved) |
| `cap-starters.mjs` | Imports all six starters through the UI; front-page shots + import GIF frames |
| `set-front-menu.mjs` then `gif-palettes.mjs` | Palette frames over the price list (run after the imports) |
| `gif.py out.gif WIDTH frame.png:ms …` | Assembles frames with one shared palette |
| `verify-fresh.mjs`, `theme-check.mjs` | Release checks used for 1.1.1 |

Order matters: the starter imports change the saved palette and front page, so
take every read-only shot first. Things that bit:

- **Preview over photographs hides palette changes.** Point the Styles preview
  at the Menu page and scroll to the price list, or a palette switch only
  recolours one button.
- **Close only real modals.** A bare `button[aria-label="Close"]` also closes the
  editor sidebar; the Site Editor welcome guide needs Escape.
- **WordPress 7.1 has no Styles button while editing a page in the Site Editor** —
  go through Design → Styles.
- **zsh aborts a `&&` chain on an unmatched glob** (`rm -f pal-*.png` with no
  files). Use `find … -delete`.

Upload with `.dev/publish/docs-images/import.php` (see the publish README).
