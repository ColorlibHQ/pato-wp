# Pato — WordPress block theme

**Version 1.0.0** · WordPress 6.6+ · PHP 7.4+ · no build step

A full-site-editing theme for restaurants, bistros, cafés and bars. Built from
the [Pato HTML template](https://preview.colorlib.com/theme/pato/).

## What is here

```
theme.json            generated — edit .dev/build_theme.py
styles/               8 colour palettes, 3 type pairings, 4 section styles
templates/  parts/    14 templates, 3 parts
patterns/             35 patterns — generated, edit .dev/build_patterns.py
inc/                  reservations, form styling, starter pages, updates
assets/               fonts, images, forms.css
style.css             block style variations and what theme.json cannot say
.dev/                 the generators and the checks
```

## Commands

```bash
# Regenerate the design system (theme.json + every styles/*.json)
python3 .dev/build_theme.py

# Regenerate the patterns, then canonicalise their block markup.
# ALWAYS run both, in this order: the generator writes markup that parses,
# the normaliser rewrites it as the block editor would serialise it.
python3 .dev/build_patterns.py
PATO_PATTERNS="$PWD/patterns" WP_URL=http://local-wp.local \
  WP_USER=admin WP_PASS=secret node .dev/normalize-blocks.mjs

# Assert every block in every template, part and pattern is valid
WP_URL=http://local-wp.local WP_USER=admin WP_PASS=secret \
  node .dev/validate-blocks.mjs

# Rebuild the webfonts
node .dev/build-fonts.mjs

# Lint
find . -name '*.php' -not -path './.git/*' -exec php -l {} \; | grep -v "No syntax"
for f in theme.json styles/*.json styles/*/*.json; do python3 -c "import json;json.load(open('$f'))" || echo "BAD $f"; done

# Screenshot (must be exactly 1200x900)
node .dev/screenshot.mjs http://local-wp.local/ screenshot.png

# Translation template
wp i18n make-pot "$PWD" "$PWD/languages/pato.pot" --exclude=.dev --domain=pato
```

## The two rules that matter most

**Never hand-write block markup.** Block comment attributes have to match what
a block's `save()` writes, exactly. Nothing warns you at build time and the
front end looks fine — the editor just offers to "recover" the block when
someone opens it. `.dev/build_patterns.py` writes markup that parses and
`.dev/normalize-blocks.mjs` hands it to the real parser and takes back the real
serialiser's output. Run both.

**Dynamic sections must be shortcodes.** `inc/front-page-setup.php` expands
patterns into real post content so the copy stays editable, and PHP inside
stored post content never runs. A pattern that rendered the reservation form
inline would freeze whatever it produced at activation into the page forever.
`[pato_reservation_form]` is expanded at render time, every time.

## Reservations

The form is in the theme. It validates server-side, carries a nonce and a
honeypot, and emails the admin address. To take the booking somewhere else:

```php
add_filter( 'pato_reservation_handlers', function ( $handled, $booking ) {
	// name, email, phone, date, time, people, message
	my_crm_create_booking( $booking );
	return true;   // Pato then sends no email of its own
}, 10, 2 );
```

Also `pato_reservation_fields`, `pato_reservation_email_to`,
`pato_reservation_email_subject`, `pato_reservation_email_body`.

## Licence

GPL v2 or later. Fonts are SIL OFL. Photographs are Unsplash-licensed — see
readme.txt.
