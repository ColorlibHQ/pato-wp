#!/usr/bin/env python3
"""Generate theme.json and every styles/*.json variation for Pato.

One source of truth for the design system. Run from the theme root:

    python3 .dev/build_theme.py

Everything it writes is committed as-is; the theme has no build step at runtime.
Edit this file, never the JSON.
"""

import collections
import json
import os

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SCHEMA = "https://schemas.wp.org/trunk/theme.json"

# ---------------------------------------------------------------------------
# Fonts
# ---------------------------------------------------------------------------
# Written by .dev/build-fonts.mjs, which reads the unicode ranges off Fontsource
# rather than hardcoding them -- the ranges grow with Unicode, and a stale one
# silently stops a subset matching.
with open(os.path.join(ROOT, ".dev/font-faces.json")) as fh:
    FACES = json.load(fh)

SANS = "ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, sans-serif"

STACKS = {
    "Montserrat": "Montserrat, " + SANS,
    "Poppins": "Poppins, " + SANS,
    "Courgette": "Courgette, ui-rounded, cursive",
}


def family(slug, name):
    faces = [
        collections.OrderedDict([
            ("fontFamily", name),
            ("fontStyle", "normal"),
            ("fontWeight", f["weight"]),
            ("fontDisplay", "swap"),
            ("unicodeRange", f["range"]),
            ("src", ["file:./assets/fonts/" + f["file"]]),
        ])
        for f in FACES[name]
    ]
    return collections.OrderedDict([
        ("slug", slug), ("name", name),
        ("fontFamily", STACKS[name]), ("fontFace", faces),
    ])


SYSTEM_FAMILY = {
    "slug": "system",
    "name": "System",
    "fontFamily": 'ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
}

# ---------------------------------------------------------------------------
# Colour
# ---------------------------------------------------------------------------
# Pato's identity is the red the HTML template shipped, #ec1d25. Measured, that
# is 4.40:1 on white -- it fails WCAG AA as body text and as a button ground in
# either direction. The palette keeps the hue and darkens it by the smallest
# amount that passes: `primary` #d41b22 is 5.28:1 both ways, and it is not an
# invention -- it is the template's own hover shade. #ec1d25 survives as
# `flame`, for large decorative fills and headings only, where AA-large applies.
#
# Ten slugs, the same ten in every variation, so a pattern written against them
# follows whichever the visitor's site has active.
#
# NOTE: never name a slug `border`, `text`, `background` or `link` -- WordPress
# emits bare utility classes with those names and a matching palette slug makes
# them collide.
PALETTE = [
    ("Base",        "base",         "#ffffff"),
    ("Surface",     "surface",      "#faf7f4"),   # warm off-white, not grey
    ("Contrast",    "contrast",     "#1a1513"),
    ("Muted",       "muted",        "#5f5853"),
    ("Primary",     "primary",      "#d41b22"),
    ("Primary deep", "primary-deep", "#a8141a"),
    ("Flame",       "flame",        "#ec1d25"),   # decorative only, 4.40:1
    ("Accent",      "accent",       "#b8863b"),   # brass, for the script type
    ("Dark",        "dark",         "#15100e"),
    ("Divider",     "divider",      "#e2d9d1"),
]

# Colour-only variations. Every one keeps the same ten slugs.
# primary and primary-deep are AA on base; base is AA on both.
COLOR_SETS = {
    "colors-1-ember": ("Ember", {
        "base": "#ffffff", "surface": "#faf7f4", "contrast": "#1a1513", "muted": "#5f5853",
        "primary": "#d41b22", "primary-deep": "#a8141a", "flame": "#ec1d25",
        "accent": "#b8863b", "dark": "#15100e", "divider": "#e2d9d1",
    }),
    "colors-2-olive": ("Olive", {
        "base": "#ffffff", "surface": "#f7f8f3", "contrast": "#171c12", "muted": "#555c4c",
        "primary": "#4d6b1f", "primary-deep": "#3a5116", "flame": "#6f9a2d",
        "accent": "#a8762c", "dark": "#141810", "divider": "#dbe0d0",
    }),
    "colors-3-vineyard": ("Vineyard", {
        "base": "#ffffff", "surface": "#faf5f7", "contrast": "#1d1016", "muted": "#615059",
        "primary": "#8e1d45", "primary-deep": "#6d1434", "flame": "#b32657",
        "accent": "#b8863b", "dark": "#180d12", "divider": "#e6d5dd",
    }),
    "colors-4-charcoal": ("Charcoal", {
        "base": "#ffffff", "surface": "#f6f6f5", "contrast": "#16171a", "muted": "#585b60",
        "primary": "#2f3337", "primary-deep": "#1c1f22", "flame": "#4a5057",
        "accent": "#b8863b", "dark": "#121314", "divider": "#dcdcda",
    }),
    "colors-5-harvest": ("Harvest", {
        "base": "#ffffff", "surface": "#fdf8f0", "contrast": "#1f1708", "muted": "#645a45",
        "primary": "#9a5b12", "primary-deep": "#77450b", "flame": "#c9791d",
        "accent": "#4d6b1f", "dark": "#1a1409", "divider": "#eadfcb",
    }),
    # Midnight's primary is deliberately brighter than the light palettes'.
    # On a dark ground a button reads as dark-on-bright, so the red has to be
    # light enough for the dark label -- #ef4a51 as primary left the label at
    # 3.40:1 against primary-deep whichever way round it was tried.
    "colors-6-midnight": ("Midnight", {
        "base": "#14161c", "surface": "#1c1f27", "contrast": "#f4f3f1", "muted": "#a8a49e",
        "primary": "#ff7a7f", "primary-deep": "#ef4a51", "flame": "#ff9a9e",
        "accent": "#d6a85c", "dark": "#0c0e12", "divider": "#333743",
    }),
    "colors-7-cellar": ("Cellar", {
        "base": "#16120f", "surface": "#1f1a16", "contrast": "#f6f1ea", "muted": "#aaa096",
        "primary": "#d9913f", "primary-deep": "#b4732c", "flame": "#eaa955",
        "accent": "#c96a5c", "dark": "#0e0b09", "divider": "#3a322b",
    }),
    "colors-8-seaside": ("Seaside", {
        "base": "#ffffff", "surface": "#f2f8f9", "contrast": "#0e1b20", "muted": "#4c5f65",
        "primary": "#0d6b7d", "primary-deep": "#084e5c", "flame": "#149bb4",
        "accent": "#b8863b", "dark": "#0a161a", "divider": "#cfe0e4",
    }),
}

# Which colour a button's label takes is decided by measurement, not by a rule
# about light and dark palettes. The first version of this file assumed "dark
# palette -> light label", and that is wrong exactly where it matters: Midnight
# and Cellar are dark palettes whose primaries are a bright red and a bright
# amber, so a light label came out at 3.28:1 and 2.32:1. button_text() below
# tries the palette's own colours and keeps the first that clears AA on both
# button grounds; build() refuses to write a palette where none does.
BUTTON_TEXT_CANDIDATES = ("base", "contrast", "dark")


def _luminance(value):
    value = value.lstrip("#")
    channels = [int(value[i:i + 2], 16) / 255 for i in (0, 2, 4)]
    channels = [c / 12.92 if c <= 0.03928 else ((c + 0.055) / 1.055) ** 2.4 for c in channels]
    return 0.2126 * channels[0] + 0.7152 * channels[1] + 0.0722 * channels[2]


def contrast_ratio(a, b):
    la, lb = _luminance(a), _luminance(b)
    return (max(la, lb) + 0.05) / (min(la, lb) + 0.05)


def button_text(colors):
    """The palette colour that reads on both button grounds, or None."""
    for slug in BUTTON_TEXT_CANDIDATES:
        if all(contrast_ratio(colors[slug], colors[ground]) >= 4.5
               for ground in ("primary", "primary-deep")):
            return slug
    return None


# Text/ground pairs the design actually produces. Checked for every palette.
CONTRAST_CHECKS = (
    ("contrast", "base"), ("muted", "base"),
    ("contrast", "surface"), ("muted", "surface"),
    ("primary", "base"), ("primary", "surface"),
)

# ---------------------------------------------------------------------------
# Typography
# ---------------------------------------------------------------------------
# `heading`, `body` and `script` are the three slugs every pattern uses. A
# typography variation redefines those, so nothing in patterns/ has to change.
TYPE_SETS = {
    "type-1-montserrat": ("Montserrat & Poppins", "montserrat", "poppins", "courgette"),
    "type-2-poppins": ("Poppins throughout", "poppins", "poppins", "courgette"),
    "type-3-classic": ("Montserrat throughout", "montserrat", "montserrat", "courgette"),
}


def fluid(minimum, maximum):
    """theme.json fluid type: WP writes the clamp() itself."""
    return collections.OrderedDict([("min", minimum), ("max", maximum)])


FONT_SIZES = [
    ("Small",   "small",   "0.875rem", None),
    ("Medium",  "medium",  "1rem",     None),
    ("Large",   "large",   "1.125rem", fluid("1.0625rem", "1.1875rem")),
    ("X Large", "x-large", "1.5rem",   fluid("1.25rem", "1.5rem")),
    ("Heading", "heading", "2rem",     fluid("1.625rem", "2.125rem")),
    ("Display", "display", "2.75rem",  fluid("2.125rem", "3rem")),
    ("Colossal", "colossal", "4rem",   fluid("2.75rem", "4.5rem")),
]

# Spacing: tens only, fluid from 50 up, so one value covers phone to desktop.
# sp() below refuses anything off this scale -- an undefined preset var makes
# WordPress drop the whole declaration, and the element silently falls back to
# its inherited gap.
SPACING = [
    ("20", "0.5rem"),
    ("30", "1rem"),
    ("40", "1.5rem"),
    ("50", "clamp(2rem, 4vw, 2.5rem)"),
    ("60", "clamp(2.5rem, 6vw, 4rem)"),
    ("70", "clamp(3.5rem, 8vw, 6rem)"),
    ("80", "clamp(5rem, 11vw, 8.5rem)"),
]


# ---------------------------------------------------------------------------
# Helpers
# ---------------------------------------------------------------------------
def col(slug):
    return "var(--wp--preset--color--%s)" % slug


def fs(slug):
    return "var(--wp--preset--font-size--%s)" % slug


def ff(slug):
    return "var(--wp--preset--font-family--%s)" % slug


VALID_SPACING = {s for s, _ in SPACING}


def sp(slug):
    """Refuse spacing off the registered scale.

    `var(--wp--preset--spacing--16)` is not defined, so WordPress drops the
    declaration and the element falls back to its inherited gap -- silently,
    and only visible by measuring the rendered page.
    """
    slug = str(slug)
    if slug not in VALID_SPACING:
        raise ValueError("spacing %r is not on the scale %s" % (slug, sorted(VALID_SPACING)))
    return "var(--wp--preset--spacing--%s)" % slug


def od(*pairs):
    return collections.OrderedDict(pairs)


def write(path, data):
    full = os.path.join(ROOT, path)
    os.makedirs(os.path.dirname(full), exist_ok=True)
    with open(full, "w") as fh:
        json.dump(data, fh, indent="\t", ensure_ascii=False)
        fh.write("\n")
    return path


def palette(colors):
    return [od(("name", name), ("slug", slug), ("color", colors.get(slug, default)))
            for name, slug, default in PALETTE]


DEFAULT_COLORS = {slug: value for _, slug, value in PALETTE}


# ---------------------------------------------------------------------------
# theme.json
# ---------------------------------------------------------------------------
def build_settings():
    return od(
        ("appearanceTools", True),
        ("useRootPaddingAwareAlignments", True),
        ("layout", od(("contentSize", "760px"), ("wideSize", "1200px"))),
        ("color", od(
            ("defaultPalette", False),
            ("defaultGradients", False),
            ("defaultDuotone", False),
            ("palette", palette(DEFAULT_COLORS)),
            ("gradients", [
                od(("name", "Ember"), ("slug", "ember"),
                   ("gradient", "linear-gradient(135deg, %s 0%%, %s 100%%)" % (col("primary"), col("primary-deep")))),
                od(("name", "Dusk"), ("slug", "dusk"),
                   ("gradient", "linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.78) 100%)")),
                od(("name", "Scrim"), ("slug", "scrim"),
                   ("gradient", "linear-gradient(0deg, rgba(0,0,0,0.86) 0%, rgba(0,0,0,0.72) 45%, rgba(0,0,0,0.22) 100%)")),
            ]),
        )),
        ("typography", od(
            ("defaultFontSizes", False),
            ("fluid", True),
            ("fontFamilies", [
                family("heading", "Montserrat"),
                family("body", "Poppins"),
                family("script", "Courgette"),
                SYSTEM_FAMILY,
            ]),
            ("fontSizes", [
                od(("name", name), ("slug", slug), ("size", size),
                   *(( ("fluid", fluidv), ) if fluidv else ()))
                for name, slug, size, fluidv in FONT_SIZES
            ]),
        )),
        ("spacing", od(
            ("units", ["px", "em", "rem", "vh", "vw", "%"]),
            ("spacingSizes", [od(("name", slug), ("slug", slug), ("size", size)) for slug, size in SPACING]),
        )),
        ("shadow", od(
            ("defaultPresets", False),
            ("presets", [
                od(("name", "Soft"), ("slug", "soft"),
                   ("shadow", "0 1px 2px color-mix(in srgb, %s 8%%, transparent), 0 8px 24px color-mix(in srgb, %s 8%%, transparent)" % (col("contrast"), col("contrast")))),
                od(("name", "Lifted"), ("slug", "lifted"),
                   ("shadow", "0 2px 4px color-mix(in srgb, %s 8%%, transparent), 0 18px 40px color-mix(in srgb, %s 14%%, transparent)" % (col("contrast"), col("contrast")))),
            ]),
        )),
        ("border", od(("radius", True), ("color", True), ("style", True), ("width", True))),
        ("blocks", od(
            ("core/button", od(("border", od(("radius", True))))),
        )),
    )


def build_styles():
    return od(
        ("color", od(("background", col("base")), ("text", col("contrast")))),
        ("typography", od(
            ("fontFamily", ff("body")),
            ("fontSize", fs("medium")),
            ("fontWeight", "400"),
            ("lineHeight", "1.75"),
        )),
        ("spacing", od(
            ("blockGap", sp("40")),
            ("padding", od(("top", "0px"), ("right", sp("40")), ("bottom", "0px"), ("left", sp("40")))),
        )),
        ("elements", od(
            ("heading", od(
                ("typography", od(
                    ("fontFamily", ff("heading")),
                    ("fontWeight", "700"),
                    ("lineHeight", "1.18"),
                    ("letterSpacing", "-0.01em"),
                )),
                ("color", od(("text", col("contrast")))),
            )),
            ("h1", od(("typography", od(("fontSize", fs("display")))))),
            ("h2", od(("typography", od(("fontSize", fs("heading")))))),
            ("h3", od(("typography", od(("fontSize", fs("x-large")))))),
            ("h4", od(("typography", od(("fontSize", fs("large")), ("lineHeight", "1.35"))))),
            ("h5", od(("typography", od(("fontSize", fs("medium")), ("letterSpacing", "0.08em"), ("textTransform", "uppercase"))))),
            ("h6", od(("typography", od(("fontSize", fs("small")), ("letterSpacing", "0.12em"), ("textTransform", "uppercase"))))),
            ("link", od(
                ("color", od(("text", col("primary")))),
                ("typography", od(("textDecoration", "none"))),
                (":hover", od(("color", od(("text", col("primary-deep")))),
                              ("typography", od(("textDecoration", "underline"))))),
                (":focus", od(("typography", od(("textDecoration", "underline"))))),
            )),
            ("button", od(
                ("color", od(("background", col("primary")), ("text", col("base")))),
                ("typography", od(
                    ("fontFamily", ff("heading")),
                    ("fontSize", fs("small")),
                    ("fontWeight", "700"),
                    ("letterSpacing", "0.06em"),
                    ("textTransform", "uppercase"),
                    ("lineHeight", "1"),
                )),
                ("spacing", od(("padding", od(("top", "1.05rem"), ("right", "2.1rem"), ("bottom", "1.05rem"), ("left", "2.1rem"))))),
                ("border", od(("radius", "4px"))),
                (":hover", od(("color", od(("background", col("primary-deep")), ("text", col("base")))))),
                (":focus", od(("color", od(("background", col("primary-deep")), ("text", col("base")))))),
                (":active", od(("color", od(("background", col("primary-deep")), ("text", col("base")))))),
            )),
            ("caption", od(
                ("color", od(("text", col("muted")))),
                ("typography", od(("fontSize", fs("small")))),
            )),
        )),
        ("blocks", od(
            ("core/site-title", od(
                ("typography", od(
                    ("fontFamily", ff("heading")),
                    ("fontSize", fs("x-large")),
                    ("fontWeight", "700"),
                    ("letterSpacing", "0.02em"),
                    ("textTransform", "uppercase"),
                )),
            )),
            ("core/post-title", od(("typography", od(("fontSize", fs("heading")), ("lineHeight", "1.2"))))),
            ("core/pullquote", od(
                ("typography", od(("fontFamily", ff("script")), ("fontSize", fs("x-large")), ("fontWeight", "400"), ("lineHeight", "1.5"))),
                ("color", od(("text", col("contrast")))),
            )),
            ("core/quote", od(
                ("typography", od(("fontFamily", ff("script")), ("fontSize", fs("large")), ("lineHeight", "1.6"))),
            )),
            ("core/separator", od(("color", od(("text", col("divider")))))),
            ("core/code", od(
                ("color", od(("background", col("surface")))),
                ("spacing", od(("padding", od(("top", sp("30")), ("right", sp("30")), ("bottom", sp("30")), ("left", sp("30")))))),
                ("border", od(("radius", "4px"))),
            )),
        )),
    )


def build_theme():
    return od(
        ("$schema", SCHEMA),
        ("version", 3),
        ("settings", build_settings()),
        ("styles", build_styles()),
        ("customTemplates", [
            od(("name", "page-no-title"), ("title", "Page without title"), ("postTypes", ["page"])),
            od(("name", "page-with-sidebar"), ("title", "Page with sidebar"), ("postTypes", ["page"])),
            od(("name", "single-with-sidebar"), ("title", "Post with sidebar"), ("postTypes", ["post"])),
        ]),
        ("templateParts", [
            od(("name", "header"), ("title", "Header"), ("area", "header")),
            od(("name", "footer"), ("title", "Footer"), ("area", "footer")),
            od(("name", "sidebar"), ("title", "Sidebar"), ("area", "uncategorized")),
        ]),
    )


# ---------------------------------------------------------------------------
# Style variations
# ---------------------------------------------------------------------------
# A partial is listed under Colors or Typography in the Site Editor only if it
# contains *nothing else*. One stray settings key demotes it to a full
# variation, so these two builders stay strictly separate.
def build_color_variation(slug, name, colors):
    variation = od(
        ("$schema", SCHEMA),
        ("version", 3),
        ("title", name),
        ("slug", slug),
        ("settings", od(("color", od(("palette", palette(colors)))))),
    )
    label = button_text(colors)
    if label is None:
        raise ValueError("%s: no palette colour reads on both button grounds" % slug)
    if label != "base":
        # theme.json's default is `base`; override only where measurement says so.
        variation["styles"] = od(("elements", od(("button", od(
            ("color", od(("text", col(label)))),
            (":hover", od(("color", od(("text", col(label)))))),
            (":focus", od(("color", od(("text", col(label)))))),
            (":active", od(("color", od(("text", col(label)))))),
        )))))
    return variation


def build_type_variation(slug, name, heading, body, script):
    return od(
        ("$schema", SCHEMA),
        ("version", 3),
        ("title", name),
        ("slug", slug),
        ("settings", od(("typography", od(("fontFamilies", [
            family("heading", {"montserrat": "Montserrat", "poppins": "Poppins"}[heading]),
            family("body", {"montserrat": "Montserrat", "poppins": "Poppins"}[body]),
            family("script", "Courgette"),
            SYSTEM_FAMILY,
        ]))))),
    )


# Section style variations, applied to groups and columns from the editor.
SECTION_STYLES = [
    ("section-soft", "Soft ground", {
        "color": od(("background", col("surface")), ("text", col("contrast"))),
    }),
    ("section-dark", "Dark ground", {
        "color": od(("background", col("dark")), ("text", col("base"))),
        "elements": od(
            ("heading", od(("color", od(("text", col("base")))))),
            ("link", od(("color", od(("text", col("base")))),
                        (":hover", od(("color", od(("text", col("base")))))))),
        ),
    }),
    ("card", "Card", {
        "color": od(("background", col("base"))),
        "border": od(("radius", "6px"), ("width", "1px"), ("style", "solid"), ("color", col("divider"))),
        "spacing": od(("padding", od(("top", sp("40")), ("right", sp("40")), ("bottom", sp("40")), ("left", sp("40"))))),
    }),
    ("elevated", "Elevated", {
        "color": od(("background", col("base"))),
        "border": od(("radius", "6px")),
        "shadow": "var(--wp--preset--shadow--lifted)",
        "spacing": od(("padding", od(("top", sp("40")), ("right", sp("40")), ("bottom", sp("40")), ("left", sp("40"))))),
    }),
]

SECTION_BLOCK_TYPES = ["core/group", "core/columns", "core/column", "core/cover", "core/media-text"]


def build_section_style(slug, title, styles):
    return od(
        ("$schema", SCHEMA),
        ("version", 3),
        ("title", title),
        ("slug", slug),
        ("blockTypes", SECTION_BLOCK_TYPES),
        ("styles", od(*styles.items())),
    )


# ---------------------------------------------------------------------------
# Main
# ---------------------------------------------------------------------------
def audit():
    """Refuse to emit a palette that fails AA on any pair the design produces."""
    problems = []
    for slug, (name, colors) in sorted(COLOR_SETS.items()):
        for fg, bg in CONTRAST_CHECKS:
            ratio = contrast_ratio(colors[fg], colors[bg])
            if ratio < 4.5:
                problems.append("%s: %s on %s is %.2f" % (name, fg, bg, ratio))
        label = button_text(colors)
        if label is None:
            problems.append("%s: no readable button label" % name)
        else:
            worst = min(contrast_ratio(colors[label], colors[g]) for g in ("primary", "primary-deep"))
            print("  %-10s button label %-8s %.2f" % (name, label, worst))
    if problems:
        raise SystemExit("Contrast failures:\n  " + "\n  ".join(problems))


def main():
    audit()
    written = [write("theme.json", build_theme())]

    for slug, (name, colors) in sorted(COLOR_SETS.items()):
        written.append(write("styles/colors/%s.json" % slug, build_color_variation(slug, name, colors)))

    for slug, (name, heading, body, script) in sorted(TYPE_SETS.items()):
        written.append(write("styles/typography/%s.json" % slug, build_type_variation(slug, name, heading, body, script)))

    for slug, title, styles in SECTION_STYLES:
        written.append(write("styles/%s.json" % slug, build_section_style(slug, title, styles)))

    for path in written:
        print("  " + path)
    print("\n%d files" % len(written))


if __name__ == "__main__":
    main()
