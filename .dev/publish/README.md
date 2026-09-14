# Publishing Pato to colorlib.com

Run these with wp-cli **as the site user**, from the colorlib.com docroot:

```bash
scp .dev/publish/colorlib-product-page.php hetzner:/tmp/pp.php
ssh hetzner 'cd /var/www/colorlib.com/public \
  && sudo chown web_colorlib_com /tmp/pp.php \
  && sudo -u web_colorlib_com wp --url=https://colorlib.com/wp/ eval "require \"/tmp/pp.php\";"'
```

| Script | Does |
| --- | --- |
| `colorlib-product-page.php` | Creates or rebuilds the Pato product page (381531, child of 5091). Idempotent; leaves it a **draft**. |
| `colorlib-themes-listing.php` | Adds Pato to the `/wp/themes/` listing (5091) and to the "what are you building" picker. If already listed, refreshes only the card image. |

## Use `wp eval "require …"`, not `wp eval-file`

`wp eval-file` runs these and **prints nothing, changes nothing and exits 0** —
no error, no warning. `wp eval "require '…';"` runs the identical file
correctly.

## The release itself

```bash
# 1. Build, and run Theme Check against the BUILT zip, not the working tree.
bash .dev/build-zip.sh /tmp/pato-release

# 2. Upload. rclone only reaches R2 from hetzner — the laptop's ISP cannot
#    route Cloudflare's 172.64.x — and the remote there is `r2:`, not `r2pro:`.
scp /tmp/pato-release/pato-1.0.0.zip hetzner:/tmp/
ssh hetzner 'sudo rclone copyto /tmp/pato-1.0.0.zip \
  r2:colorlib-downloads/wp/pato/pato-1.0.0.zip'
#    A `501 Not Implemented` on attempt 1 followed by "Attempt 2/3 succeeded"
#    is normal for R2 and is not a failure.

# 3. Publish the release row. CLOUDFLARE_API_TOKEN in the environment is the
#    R2-only token and overrides wrangler's OAuth, so unset it.
cd ~/Projects/colorlib-updates
env -u CLOUDFLARE_API_TOKEN node release.mjs \
  --product theme/pato --version 1.0.0 \
  --package https://downloads.colorlib.com/wp/pato/pato-1.0.0.zip \
  --url https://colorlib.com/wp/themes/pato/ \
  --tested 7.0 --requires 6.6 --requires-php 7.4
```

## Two maps on colorlib.com that also need the theme

Both in `wp-content/mu-plugins/`, one line each:

- `colorlib-wp-theme-downloads.php` — `381531 => 'pato'` puts the page's
  download buttons behind the Sendy email gate. Without it the zip is handed
  out with no opt-in.
- `schema-wp-themes.php` — `'pato' => array( array( 'pato' ), 'WordPress Theme' )`
  emits SoftwareApplication schema from `updates.colorlib.com/theme/pato.json`.
  Themes not in the wordpress.org directory get no schema without this.

## Things that bite

- **WPBakery keeps every `css="…"` rule in `_wpb_shortcodes_custom_css` post
  meta** and only regenerates it on a save through the builder UI. Both scripts
  call `visual_composer()->buildShortcodesCss()`; any script that writes
  WPBakery content must.
- **URLs in a `vc_btn` `link=` attribute must be percent-encoded.** It is
  WPBakery's own `url:…|title:…|target:…` encoding, split on `|` then on the
  first `:`, so a raw `https://…` is cut off and the button renders
  `href="http://https"`.
- **`vcex_toggle` takes `heading`, not `title`** — with `title` every toggle
  renders the shortcode's placeholder, "Lorem ipsum dolor sit amet?".
- **`vcex_icon_box` renders `h2` unless given `heading_type="h3"`.**
- **Draft pages 301 for anonymous visitors** and the pretty permalink then
  resolves elsewhere. Preview with `?page_id=381531&preview=true` and a cookie
  from `wp_generate_auth_cookie()`.
- **`wp media import` does not overwrite.** A filename that already exists
  becomes `-1.jpg`, so derive image URLs from the attachment ID, never from a
  literal filename.
- **colorlib.com's HTML is Cloudflare-cached** (unlike colorlibhub, which is
  DYNAMIC). `cf-purge-url` the page after any change.
