#!/usr/bin/env python3
"""Generate Pato's patterns.

Run from the theme root:

    python3 .dev/build_patterns.py

Every pattern file it writes is committed as-is. Edit this file, never
patterns/*.php.
"""

import os
import sys

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))

from patternlib import (  # noqa: E402
    attrs, button, buttons, column, columns, cover, group, heading, image,
    paragraph, separator, shortcode, spacer, sp, spc,
)

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
PATTERNS = os.path.join(ROOT, "patterns")

WRITTEN = []


def write(slug, title, content, categories=None, keywords=None,
          block_types=None, inserter=True, viewport=None, description=None):
    """Write one pattern file with its header comment."""
    header = ["Title: " + title, "Slug: pato/" + slug]
    if categories:
        header.append("Categories: " + ", ".join(categories))
    if keywords:
        header.append("Keywords: " + ", ".join(keywords))
    if block_types:
        header.append("Block Types: " + ", ".join(block_types))
    if description:
        header.append("Description: " + description)
    if viewport:
        header.append("Viewport Width: %d" % viewport)
    if not inserter:
        header.append("Inserter: no")

    body = (
        "<?php\n"
        "/**\n"
        " * " + "\n * ".join(header) + "\n"
        " *\n"
        " * @package Pato\n"
        " */\n"
        "\n"
        "defined( 'ABSPATH' ) || exit;\n"
        "?>\n"
        + content.strip() + "\n"
    )

    path = os.path.join(PATTERNS, slug + ".php")
    with open(path, "w") as fh:
        fh.write(body)
    WRITTEN.append(slug)


# ===========================================================================
# Structure: the patterns the templates reference
# ===========================================================================

def build_header():
    """Site header: brand on the left, navigation and a booking button right.

    core/navigation falls back to a page list when no menu exists, so a fresh
    site gets working navigation before anyone has built one.
    """
    brand = group(
        "\n".join([
            '<!-- wp:site-logo {"width":150} /-->',
            '<!-- wp:site-title {"level":0} /-->',
        ]),
        layout="flex",
        gap="30",
        extra_class="pato-header__brand",
    )

    # Seven items at a 24px gap need about 700px and the column gives 560, so
    # the menu wrapped to two rows at every width including 1600. A 16px gap
    # and a wider column fit it on one line, and `nowrap` makes a future
    # eighth item collapse to the overlay rather than silently wrapping again.
    nav = (
        '<!-- wp:navigation ' + attrs({
            "overlayMenu": "mobile",
            "className": "pato-nav",
            "layout": {"type": "flex", "justifyContent": "right", "flexWrap": "nowrap"},
            "style": {"spacing": {"blockGap": "var:preset|spacing|30"}},
        }).strip() + ' /-->'
    )

    # The dark-mode switch sits beside the booking button. The class goes on
    # the BUTTON, not the wrapper: the script binds everything carrying it, so
    # a class on both would toggle twice per click.
    actions = buttons([
        # Not an empty button: WordPress renders nothing at all for a
        # core/button with no text, so the toggle silently vanished from the
        # header. The label is real and simply not shown — the icon is a
        # ::before, and a control with no accessible name is useless anyway.
        button('<span class="screen-reader-text">Toggle dark mode</span>',
               "#", style="pato-ghost", extra_class="pato-scheme-toggle"),
        button("Book a table", "#pato-reservation"),
    ], align="right", gap="20")

    inner = columns([
        column(brand, width="22%", vertical="center"),
        column(nav, width="56%", vertical="center"),
        column(actions, width="22%", vertical="center"),
    ], align="wide", vertical="center", stack_on_mobile=True)

    write(
        "header", "Header",
        group(inner, align="full", padding_y="40", background="base"),
        categories=["header"], block_types=["core/template-part/header"],
        inserter=False,
    )


def build_footer():
    """Four columns on the dark ground, then a credit line."""
    def col_title(text):
        return paragraph("<strong>" + text + "</strong>", extra_class="pato-footer__title")

    contact = "\n".join([
        col_title("Find us"),
        paragraph("80 Broad Street<br>New York, NY 10004", color="overlay", size="small"),
        paragraph('<a href="tel:+18001234567">+1 800 123 4567</a><br><a href="mailto:hello@example.com">hello@example.com</a>',
                  color="overlay", size="small"),
    ])

    hours = "\n".join([
        col_title("Opening times"),
        ('<!-- wp:html -->\n'
         '<dl class="pato-hours">\n'
         '\t<dt>Monday &ndash; Friday</dt><dd>11:00 &ndash; 23:00</dd>\n'
         '\t<dt>Saturday</dt><dd>10:00 &ndash; 23:00</dd>\n'
         '\t<dt>Sunday</dt><dd>10:00 &ndash; 22:00</dd>\n'
         '</dl>\n'
         '<!-- /wp:html -->'),
    ])

    # A written list, not core/page-list.
    #
    # page-list renders every published page with no way to cap it, so on any
    # site with real content the footer becomes a wall: on the test site it
    # printed several hundred links, nested four deep. These are the pages
    # inc/front-page-setup.php creates, and an owner can edit the list like any
    # other block.
    explore = "\n".join([
        col_title("Explore"),
        ('<!-- wp:list {"className":"pato-footer__links"} -->\n'
         '<ul class="wp-block-list pato-footer__links">\n'
         '<!-- wp:list-item -->\n<li><a href="#menu">Menu</a></li>\n<!-- /wp:list-item -->\n'
         '<!-- wp:list-item -->\n<li><a href="#pato-reservation">Reservations</a></li>\n<!-- /wp:list-item -->\n'
         '<!-- wp:list-item -->\n<li><a href="#gallery">Gallery</a></li>\n<!-- /wp:list-item -->\n'
         '<!-- wp:list-item -->\n<li><a href="#about">About</a></li>\n<!-- /wp:list-item -->\n'
         '<!-- wp:list-item -->\n<li><a href="#contact">Contact</a></li>\n<!-- /wp:list-item -->\n'
         '</ul>\n'
         '<!-- /wp:list -->'),
    ])

    social = "\n".join([
        col_title("Elsewhere"),
        paragraph("Follow the kitchen, the specials and whatever the chef is pickling this week.",
                  color="overlay", size="small"),
        ('<!-- wp:social-links ' + attrs({
            "iconColor": "overlay", "iconColorValue": "var(--wp--preset--color--overlay)",
            "className": "is-style-logos-only",
            "layout": {"type": "flex"},
        }).strip() + ' -->\n'
         '<ul class="wp-block-social-links has-icon-color is-style-logos-only">'
         '<!-- wp:social-link {"url":"#","service":"instagram"} /-->'
         '<!-- wp:social-link {"url":"#","service":"facebook"} /-->'
         '<!-- wp:social-link {"url":"#","service":"x"} /-->'
         '</ul>\n'
         '<!-- /wp:social-links -->'),
    ])

    grid = columns([
        column(contact, width="26%"),
        column(hours, width="28%"),
        column(explore, width="22%"),
        column(social, width="24%"),
    ], align="wide", gap="50")

    credit = group(
        "\n".join([
            paragraph('&copy; <?php echo esc_html( gmdate( \'Y\' ) ); ?> Pato. All rights reserved.',
                      color="overlay", size="small"),
            paragraph('Made by <a href="https://colorlib.com/" rel="nofollow">Colorlib</a>',
                      color="overlay", size="small"),
        ]),
        layout="flex", justify="space-between", wrap="wrap", align="wide", gap="30",
    )

    inner = "\n".join([
        grid,
        spacer("50"),
        ('<!-- wp:separator {"className":"is-style-wide"} -->\n'
         '<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>\n'
         '<!-- /wp:separator -->'),
        spacer("30"),
        credit,
    ])

    write(
        "footer", "Footer",
        group(inner, align="full", padding_y="70", background="dark", text="overlay",
              extra_class="pato-footer"),
        categories=["footer"], block_types=["core/template-part/footer"],
        inserter=False,
    )


def build_sidebar():
    inner = "\n".join([
        '<!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Search","buttonText":"Search"} /-->',
        spacer("40"),
        heading("Categories", level=3, size="large"),
        '<!-- wp:categories {"showHierarchy":true} /-->',
        spacer("40"),
        heading("Latest posts", level=3, size="large"),
        '<!-- wp:latest-posts {"postsToShow":4,"displayFeaturedImage":true,"featuredImageAlign":"left","featuredImageSizeSlug":"thumbnail","addLinkToFeaturedImage":true} /-->',
    ])
    write("sidebar", "Sidebar", group(inner, gap="30"),
          categories=["pato-sections"], inserter=False)


def build_hidden():
    """The partials templates reference. None appear in the inserter."""

    # Blog index heading. Visually hidden so the archive keeps its h1 without
    # printing a title above a grid that already explains itself.
    write("hidden-blog-heading", "Blog heading",
          ('<!-- wp:heading {"level":1,"className":"screen-reader-text"} -->\n'
           '<h1 class="wp-block-heading screen-reader-text">Latest posts</h1>\n'
           '<!-- /wp:heading -->'),
          inserter=False)

    # Post grid, shared by index, home, archive, search.
    card = "\n".join([
        '<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3"} /-->',
        '<!-- wp:post-terms {"term":"category","className":"is-style-pato-script","fontSize":"large","style":{"spacing":{"margin":{"top":"var:preset|spacing|30"}}}} /-->',
        '<!-- wp:post-title {"isLink":true,"level":3,"fontSize":"x-large"} /-->',
        '<!-- wp:post-excerpt {"moreText":"Read more","excerptLength":22} /-->',
        '<!-- wp:pattern {"slug":"pato/hidden-post-meta"} /-->',
    ])

    grid = (
        '<!-- wp:query ' + attrs({
            "queryId": 1, "query": {
                "perPage": 6, "pages": 0, "offset": 0, "postType": "post",
                "order": "desc", "orderBy": "date", "author": "", "search": "",
                "exclude": [], "sticky": "", "inherit": True,
            },
            "align": "wide",
        }).strip() + ' -->\n'
        '<div class="wp-block-query alignwide">\n'
        '<!-- wp:post-template ' + attrs({
            "layout": {"type": "grid", "columnCount": 3},
            "style": {"spacing": {"blockGap": "var:preset|spacing|50"}},
        }).strip() + ' -->\n'
        + card + '\n'
        '<!-- /wp:post-template -->\n'
        '<!-- wp:query-no-results -->\n'
        + paragraph("Nothing here yet. Check back soon.", align="center") + '\n'
        '<!-- /wp:query-no-results -->\n'
        '<!-- wp:query-pagination ' + attrs({
            "layout": {"type": "flex", "justifyContent": "center"},
            "style": {"spacing": {"margin": {"top": "var:preset|spacing|60"}}},
        }).strip() + ' -->\n'
        '<!-- wp:query-pagination-previous /-->\n'
        '<!-- wp:query-pagination-numbers /-->\n'
        '<!-- wp:query-pagination-next /-->\n'
        '<!-- /wp:query-pagination -->\n'
        '</div>\n'
        '<!-- /wp:query -->'
    )
    write("hidden-posts-grid", "Posts grid", grid, inserter=False)

    # Post meta: date and author on one line. The bullet between them is a CSS
    # ::after on every child but the last, so a post with no author does not
    # render a stray dot.
    meta = group(
        "\n".join([
            '<!-- wp:post-date {"fontSize":"small"} /-->',
            '<!-- wp:post-author-name {"isLink":true,"fontSize":"small"} /-->',
        ]),
        layout="flex", wrap="wrap", gap="20", extra_class="pato-meta",
    )
    write("hidden-post-meta", "Post meta", meta, inserter=False)

    # Comments, wrapped so the whole block disappears when they are closed.
    comments = (
        '<!-- wp:comments {"className":"pato-comments"} -->\n'
        '<div class="wp-block-comments pato-comments">\n'
        + heading("Comments", level=3, size="x-large") + '\n'
        '<!-- wp:comment-template -->\n'
        '<!-- wp:columns -->\n'
        '<div class="wp-block-columns">\n'
        '<!-- wp:column {"width":"64px"} -->\n'
        '<div class="wp-block-column" style="flex-basis:64px">\n'
        '<!-- wp:avatar {"size":64,"style":{"border":{"radius":"50%"}}} /-->\n'
        '</div>\n'
        '<!-- /wp:column -->\n'
        '<!-- wp:column -->\n'
        '<div class="wp-block-column">\n'
        '<!-- wp:comment-author-name {"fontSize":"small"} /-->\n'
        '<!-- wp:comment-date {"fontSize":"small"} /-->\n'
        '<!-- wp:comment-content /-->\n'
        '<!-- wp:comment-reply-link {"fontSize":"small"} /-->\n'
        '</div>\n'
        '<!-- /wp:column -->\n'
        '</div>\n'
        '<!-- /wp:columns -->\n'
        '<!-- /wp:comment-template -->\n'
        '<!-- wp:comments-pagination {"layout":{"type":"flex","justifyContent":"center"}} -->\n'
        '<!-- wp:comments-pagination-previous /-->\n'
        '<!-- wp:comments-pagination-numbers /-->\n'
        '<!-- wp:comments-pagination-next /-->\n'
        '<!-- /wp:comments-pagination -->\n'
        '<!-- wp:post-comments-form /-->\n'
        '</div>\n'
        '<!-- /wp:comments -->'
    )
    write("hidden-comments", "Comments", comments, inserter=False)

    # Page banner. A cover over the page's own featured image when it has one,
    # falling back to the theme's photograph when it does not.
    banner = cover(
        group(
            "\n".join([
                '<!-- wp:post-title {"textAlign":"center","level":1,"textColor":"overlay","fontSize":"colossal"} /-->',
            ]),
            layout="constrained", content_size="860px",
        ),
        "banner-about", dim=60, min_height=380, extra_class="pato-banner",
    )
    write("hidden-page-banner", "Page banner", banner, inserter=False)

    archive_banner = cover(
        group(
            "\n".join([
                '<!-- wp:query-title {"type":"archive","textAlign":"center","textColor":"overlay","fontSize":"colossal"} /-->',
                '<!-- wp:term-description {"textAlign":"center","textColor":"overlay"} /-->',
            ]),
            layout="constrained", content_size="860px",
        ),
        "banner-menu", dim=60, min_height=340, extra_class="pato-banner",
    )
    write("hidden-archive-banner", "Archive banner", archive_banner, inserter=False)

    search_banner = cover(
        group(
            '<!-- wp:query-title {"type":"search","textAlign":"center","textColor":"overlay","fontSize":"colossal"} /-->',
            layout="constrained", content_size="860px",
        ),
        "banner-contact", dim=60, min_height=340, extra_class="pato-banner",
    )
    write("hidden-search-banner", "Search banner", search_banner, inserter=False)

    # 404.
    not_found = group(
        "\n".join([
            heading("Nothing on the menu here", level=1, align="center", size="display"),
            paragraph("That page has been taken off. Try the menu, or search for what you were after.",
                      align="center", color="muted", size="large"),
            spacer("40"),
            '<!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Search the site","buttonText":"Search","align":"center"} /-->',
            spacer("40"),
            buttons([button("Back to the home page", "<?php echo esc_url( home_url( '/' ) ); ?>")], align="center"),
        ]),
        layout="constrained", content_size="620px",
    )
    write("hidden-404", "404 content", not_found, inserter=False)


# ===========================================================================
# Restaurant sections
# ===========================================================================

def eyebrow(text):
    """The script line above a section title, as in the HTML template."""
    return heading(text, level=3, align="center", style="pato-script", size="x-large")


def section_head(script, title, blurb=None, align="center"):
    parts = [eyebrow(script), heading(title, level=2, align=align, size="heading")]
    if blurb:
        parts.append(paragraph(blurb, align=align, color="muted", size="large"))
    return group("\n".join(parts), layout="constrained", content_size="720px", gap="30")


def dish(name, price, note=None):
    """One menu row: name, dotted leader, price, optional description.

    Built from core blocks rather than a custom block so the copy stays
    editable by anyone, and so the theme needs no post type and no JavaScript.
    The leader is drawn in CSS on the flex gap, which is what lets it stretch
    to whatever width is left instead of wrapping like a row of typed dots.
    """
    row = (
        '<!-- wp:group {"className":"pato-dish","layout":{"type":"default"}} -->\n'
        '<div class="wp-block-group pato-dish">\n'
        + paragraph(name, extra_class="pato-dish__name") + '\n'
        + paragraph(price, extra_class="pato-dish__price") + '\n'
        '</div>\n'
        '<!-- /wp:group -->'
    )
    if note:
        row += '\n' + paragraph(note, extra_class="pato-dish__note")
    return row


LUNCH = [
    ("Smoked salmon &amp; leaf salad", "$14", "Cured in-house, soft herbs, lemon"),
    ("Griddled pork chop", "$19", "Charred greens, mustard cream"),
    ("Beef fillet, wild mushrooms", "$26", "Red wine reduction, pomme purée"),
    ("Whole prawns, garlic aïoli", "$21", "Grilled over charcoal, lemon"),
    ("Pumpkin &amp; sage ravioli", "$17", "Brown butter, toasted hazelnut"),
]

DINNER = [
    ("Rosemary lamb over fire", "$29", "Cooked on the open grill, salsa verde"),
    ("Salmon, saffron &amp; fennel", "$24", "Dill oil, cucumber, new potato"),
    ("Aged emmental &amp; walnut", "$13", "Quince, sourdough, pickled grape"),
    ("Baked apple, vanilla custard", "$11", "Blueberry, toasted oat crumb"),
    ("Chocolate &amp; sea salt tart", "$12", "Crème fraîche, cocoa nib"),
]

DRINKS = [
    ("House red &mdash; Nero d'Avola", "$9", "Glass. Bottle $34"),
    ("House white &mdash; Picpoul", "$9", "Glass. Bottle $34"),
    ("Negroni, stirred over a rock", "$13", None),
    ("Espresso martini", "$14", None),
    ("Alcohol-free spritz", "$8", "Seedlip, grapefruit, soda"),
]


def menu_column(title, items, note=None):
    inner = [heading(title, level=3, size="x-large", margin_bottom="30")]
    if note:
        inner.append(paragraph(note, color="muted", size="small", margin_bottom="30"))
    for name, price, desc in items:
        inner.append(dish(name, price, desc))
    return group("\n".join(inner), gap="40")


def wide_row(inner, gap="50"):
    """An alignwide columns row with an explicit gap.

    columns() in patternlib takes a single gap slug; these rows need the
    top/left pair that the editor writes for a columns block, so they are
    built here rather than bending the helper out of shape.
    """
    return (
        '<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":'
        '{"top":"%s","left":"%s"}}}} -->\n'
        '<div class="wp-block-columns alignwide">\n%s\n</div>\n'
        '<!-- /wp:columns -->' % (sp(gap), sp(gap), inner)
    )


def photo_row(inner):
    return wide_row(inner, gap="30")


def build_menu_sections():
    """Lunch and dinner side by side -- the section a restaurant theme exists for."""
    inner = "\n".join([
        section_head("Our menu", "Lunch &amp; dinner",
                     "Everything is cooked to order in an open kitchen. The menu moves with the season, so some of this changes every few weeks."),
        spacer("60"),
        columns([
            column(menu_column("Lunch", LUNCH, "Served 11:00 &ndash; 16:00"), width="50%"),
            column(menu_column("Dinner", DINNER, "Served 17:00 &ndash; 22:30"), width="50%"),
        ], align="wide", gap="60"),
    ])
    write("menu-lunch-dinner", "Menu: lunch and dinner",
          group(inner, align="full", padding_y="70", anchor="menu"),
          categories=["pato-menu"], keywords=["menu", "food", "prices", "restaurant"],
          viewport=1400,
          description="Two menu columns with dish names, dotted leaders and prices.")

    drinks = "\n".join([
        section_head("From the bar", "Drinks"),
        spacer("60"),
        columns([
            column(menu_column("Wine &amp; cocktails", DRINKS), width="58%"),
            column(image("drink-cocktail", "A bartender straining a cocktail into a glass",
                         ratio="3/4", rounded="6px"), width="42%"),
        ], align="wide", gap="60", vertical="center"),
    ])
    write("menu-drinks", "Menu: drinks",
          group(drinks, align="full", padding_y="70", background="surface", anchor="drinks"),
          categories=["pato-menu"], keywords=["drinks", "bar", "wine", "cocktails"],
          viewport=1400,
          description="A drinks list beside a photograph.")

    # A single course, for a longer menu page.
    single = "\n".join([
        heading("Starters", level=2, align="center", size="heading", style="pato-ruled"),
        spacer("50"),
        group("\n".join(dish(n, p, d) for n, p, d in LUNCH[:4]),
              layout="constrained", content_size="760px", gap="40"),
    ])
    write("menu-course", "Menu: one course",
          group(single, align="full", padding_y="60", anchor="menu"),
          categories=["pato-menu"], keywords=["menu", "course", "starters"],
          description="One named course of a longer menu.")


def build_hero():
    inner = group(
        "\n".join([
            heading("A table by the fire", level=1, align="center", color="overlay", size="colossal"),
            paragraph("Seasonal plates, an open kitchen and a short, careful wine list &mdash; in the middle of the city since 1998.",
                      align="center", color="overlay", size="large"),
            spacer("40"),
            buttons([
                button("Book a table", "#pato-reservation"),
                button("See the menu", "#menu", style="pato-outline"),
            ], align="center", gap="30"),
        ]),
        layout="constrained", content_size="760px", gap="30",
    )
    write("hero", "Hero",
          cover(inner, "hero-dining", dim=60, min_height=620, extra_class="pato-banner"),
          categories=["pato-sections", "banner"], keywords=["hero", "banner", "restaurant"],
          viewport=1400,
          description="Full-width photograph with a headline and two buttons.")


def build_welcome():
    left = image("story-salmon", "A grilled salmon fillet on a salad of tomato and rocket",
                 ratio="4/5", rounded="6px")
    right = group("\n".join([
        heading("Welcome", level=3, style="pato-script", size="x-large", align="left"),
        heading("Cooked over fire, eaten slowly", level=2, size="heading"),
        paragraph("We opened with six tables and one grill. Most of that is still true: the room is bigger, but everything still comes off the same fire, and the menu is still written the morning it is served."),
        paragraph("Produce comes from growers we have used for years. What they have decided is ready is what you will find on the menu that week."),
        spacer("30"),
        buttons([button("Our story", "#about", style="pato-ghost")]),
    ]), gap="30")

    inner = columns([
        column(left, width="46%"),
        column(right, width="54%", vertical="center"),
    ], align="wide", gap="60", vertical="center")

    write("welcome", "Welcome: image and text",
          group(inner, align="full", padding_y="70", anchor="about"),
          categories=["pato-sections"], keywords=["about", "welcome", "story"],
          viewport=1400,
          description="A photograph beside an introduction.")


def build_reservation():
    """The booking panel.

    The form is [pato_reservation_form], not inline PHP. inc/front-page-setup.php
    expands patterns into real post content so the copy stays editable, and PHP
    inside stored content never runs -- an inline form would be frozen at
    whatever it rendered on activation. A shortcode is expanded every time.
    """
    left = group("\n".join([
        heading("Reservations", level=3, style="pato-script", size="x-large", align="left"),
        heading("Book a table", level=2, size="heading"),
        paragraph("Tell us when and how many, and we will confirm by email. For parties over eight, or to take the whole room, call us on <a href=\"tel:+18001234567\">+1 800 123 4567</a>."),
        spacer("30"),
        ('<!-- wp:html -->\n'
         '<dl class="pato-hours">\n'
         '\t<dt>Lunch</dt><dd>11:00 &ndash; 16:00</dd>\n'
         '\t<dt>Dinner</dt><dd>17:00 &ndash; 22:30</dd>\n'
         '\t<dt>Sunday</dt><dd>10:00 &ndash; 22:00</dd>\n'
         '</dl>\n'
         '<!-- /wp:html -->'),
    ]), gap="30")

    right = group(shortcode("[pato_reservation_form]"),
                  background="base", padding={"top": "50", "right": "50", "bottom": "50", "left": "50"},
                  radius="6px")

    inner = columns([
        column(left, width="42%", vertical="center"),
        column(right, width="58%"),
    ], align="wide", gap="60")

    write("reservation", "Reservation form",
          group(inner, align="full", padding_y="70", background="surface"),
          categories=["pato-sections"], keywords=["reservation", "booking", "form", "table"],
          viewport=1400,
          description="The booking form beside opening times.")


def build_opening_hours():
    inner = "\n".join([
        section_head("Find us", "Where and when"),
        spacer("60"),
        columns([
            column(group("\n".join([
                heading("Address", level=3, size="large", margin_bottom="30"),
                paragraph("80 Broad Street<br>New York, NY 10004", color="muted"),
                paragraph('<a href="https://www.openstreetmap.org/directions?to=40.704644%2C-74.011987" rel="noopener">Get directions</a>'),
            ]), gap="20"), width="33.33%"),
            column(group("\n".join([
                heading("Hours", level=3, size="large", margin_bottom="30"),
                ('<!-- wp:html -->\n'
                 '<dl class="pato-hours">\n'
                 '\t<dt>Mon &ndash; Fri</dt><dd>11:00 &ndash; 23:00</dd>\n'
                 '\t<dt>Saturday</dt><dd>10:00 &ndash; 23:00</dd>\n'
                 '\t<dt>Sunday</dt><dd>10:00 &ndash; 22:00</dd>\n'
                 '</dl>\n'
                 '<!-- /wp:html -->'),
            ]), gap="20"), width="33.33%"),
            column(group("\n".join([
                heading("Contact", level=3, size="large", margin_bottom="30"),
                paragraph('<a href="tel:+18001234567">+1 800 123 4567</a>', color="muted"),
                paragraph('<a href="mailto:hello@example.com">hello@example.com</a>', color="muted"),
            ]), gap="20"), width="33.33%"),
        ], align="wide", gap="50"),
    ])
    write("opening-hours", "Opening hours and address",
          group(inner, align="full", padding_y="70", anchor="hours"),
          categories=["pato-sections"], keywords=["hours", "address", "contact", "opening"],
          description="Address, opening times and contact details in three columns.")


def build_gallery():
    shots = [
        ("gallery-kitchen", "A chef plating a dish at the pass of an open kitchen"),
        ("gallery-banquet", "A tall arrangement of roses on a laid banquet table"),
        ("gallery-market", "Visitors at an outdoor Christmas market"),
        ("gallery-champagne", "A waiter carrying a tray of filled champagne flutes"),
        ("gallery-sandwiches", "Club sandwiches cut into triangles on a wooden board"),
        ("gallery-toast", "Three people touching whisky glasses together over a laid table"),
    ]
    tiles = "\n".join(
        column(image(slug, alt, ratio="1", style="pato-lift", rounded="6px"), width="33.33%")
        for slug, alt in shots[:3]
    )
    tiles2 = "\n".join(
        column(image(slug, alt, ratio="1", style="pato-lift", rounded="6px"), width="33.33%")
        for slug, alt in shots[3:]
    )
    inner = "\n".join([
        section_head("The room", "A look around"),
        spacer("60"),
        photo_row(tiles),
        photo_row(tiles2),
    ])
    write("gallery", "Gallery grid",
          group(inner, align="full", padding_y="70", background="surface", anchor="gallery"),
          categories=["pato-sections", "gallery"], keywords=["gallery", "photos", "images"],
          viewport=1400,
          description="Six photographs in two rows of three.")


def build_chefs():
    people = [
        ("chef-flambe", "A chef cooking over a flaring pan", "Marco Vitale", "Head chef"),
        ("chef-pastry", "A pastry chef arranging chocolates on a counter", "Ines Duarte", "Pastry"),
        ("gallery-kitchen", "A chef plating a dish at the pass", "Sam Okonjo", "Sous chef"),
    ]
    cards = "\n".join(
        column(group("\n".join([
            image(slug, alt, ratio="3/4", style="pato-lift", rounded="6px"),
            heading(name, level=3, size="x-large", align="center", margin_bottom="20"),
            paragraph(role, align="center", color="primary", size="small"),
        ]), gap="30"), width="33.33%")
        for slug, alt, name, role in people
    )
    inner = "\n".join([
        section_head("The kitchen", "Who cooks your dinner"),
        spacer("60"),
        wide_row(cards, gap="50"),
    ])
    write("chefs", "Chef profiles",
          group(inner, align="full", padding_y="70"),
          categories=["pato-sections", "team"], keywords=["chefs", "team", "staff", "people"],
          viewport=1400,
          description="Three chef portraits with names and roles.")


def build_reviews():
    quotes = [
        ("The lamb came off the fire with a crust I have not stopped thinking about. We booked again before we left.", "Dana R."),
        ("Somewhere you can hear the person across the table, with food that deserves the quiet.", "Peter M."),
        ("Our whole party was fed beautifully, including two vegetarians and a coeliac, without any fuss.", "Amara N."),
    ]
    cards = "\n".join(
        column(group("\n".join([
            paragraph("&ldquo;" + text + "&rdquo;", size="large"),
            paragraph("<strong>" + who + "</strong>", color="muted", size="small"),
        ]), gap="30", style="card"), width="33.33%")
        for text, who in quotes
    )
    inner = "\n".join([
        section_head("Reviews", "What people say"),
        spacer("60"),
        wide_row(cards, gap="40"),
    ])
    write("reviews", "Reviews",
          group(inner, align="full", padding_y="70", background="surface"),
          categories=["pato-sections", "testimonials"], keywords=["reviews", "testimonials", "quotes"],
          viewport=1400,
          description="Three guest reviews as cards.")


def build_events():
    inner = group("\n".join([
        heading("Wine nights, every last Thursday", level=2, align="center", color="overlay", size="heading"),
        paragraph("Six glasses, six growers, one long table. $55 a head, and we cook to match whatever is being poured.",
                  align="center", color="overlay", size="large"),
        spacer("40"),
        buttons([button("Reserve a place", "#pato-reservation", style="pato-outline")], align="center"),
    ]), layout="constrained", content_size="720px", gap="30")

    write("events", "Events banner",
          cover(inner, "intro-ribs", dim=70, min_height=460),
          categories=["pato-sections", "call-to-action"], keywords=["events", "cta", "banner"],
          viewport=1400,
          description="A full-width photograph with an event announcement.")


def build_blog_latest():
    card = "\n".join([
        '<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3"} /-->',
        '<!-- wp:post-title {"isLink":true,"level":3,"fontSize":"x-large","style":{"spacing":{"margin":{"top":"var:preset|spacing|30"}}}} /-->',
        '<!-- wp:post-excerpt {"moreText":"Read more","excerptLength":18} /-->',
        '<!-- wp:pattern {"slug":"pato/hidden-post-meta"} /-->',
    ])
    query = (
        '<!-- wp:query ' + attrs({
            "queryId": 2, "query": {
                "perPage": 3, "pages": 1, "offset": 0, "postType": "post",
                "order": "desc", "orderBy": "date", "author": "", "search": "",
                "exclude": [], "sticky": "", "inherit": False,
            },
            "align": "wide",
        }).strip() + ' -->\n'
        '<div class="wp-block-query alignwide">\n'
        '<!-- wp:post-template ' + attrs({
            "layout": {"type": "grid", "columnCount": 3},
            "style": {"spacing": {"blockGap": "var:preset|spacing|50"}},
        }).strip() + ' -->\n'
        + card + '\n'
        '<!-- /wp:post-template -->\n'
        '<!-- wp:query-no-results -->\n'
        + paragraph("No posts yet.", align="center", color="muted") + '\n'
        '<!-- /wp:query-no-results -->\n'
        '</div>\n'
        '<!-- /wp:query -->'
    )
    inner = "\n".join([
        section_head("From the kitchen", "Recent writing"),
        spacer("60"),
        query,
    ])
    write("blog-latest", "Latest posts",
          group(inner, align="full", padding_y="70"),
          categories=["pato-sections", "posts"], keywords=["blog", "posts", "news"],
          viewport=1400,
          description="The three most recent posts in a grid.")


def build_newsletter():
    inner = group("\n".join([
        heading("What we are cooking this week", level=2, align="center", size="x-large"),
        paragraph("One email a week: the menu as it changes, and first refusal on the wine nights.",
                  align="center", color="muted"),
        spacer("30"),
        paragraph("<em>Add your newsletter plugin's form block here.</em>", align="center", color="muted", size="small"),
    ]), layout="constrained", content_size="640px", gap="30")

    write("newsletter", "Newsletter prompt",
          group(inner, align="full", padding_y="60", background="surface"),
          categories=["pato-sections", "call-to-action"], keywords=["newsletter", "signup", "email"],
          description="A short signup prompt for a newsletter plugin's form.")


# ===========================================================================
# More sections
# ===========================================================================
# The sections a restaurant site needs beyond the obvious ones: the questions
# every venue is asked, the things it sells that are not dinner, and the proof
# that it is any good.

def build_faq():
    """Accordion FAQ from core/details, which needs no JavaScript."""
    questions = [
        ("Do you take walk-ins?",
         "Always, and we keep the bar and a few tables back for them. If you are more than four, a booking is safer."),
        ("Can you cook around allergies?",
         "Yes. Tell us when you book and the kitchen will plan for it rather than improvise on the night. Every dish is cooked to order, so most things can be adjusted."),
        ("Is there a set menu for groups?",
         "For eight or more we serve a three-course set menu at $48 a head, chosen from whatever is on that week. Vegetarian and vegan versions are the same price."),
        ("Can we take the whole room?",
         "The dining room seats 54 and the back room 18. Both can be booked exclusively; call us and ask for Marco."),
        ("Where can we park?",
         "There is metered parking along Broad Street, free after 19:00, and a garage two minutes away on Pearl."),
        ("Do you have high chairs?",
         "Two, and a shorter menu for anyone who does not want a whole plate. Children are welcome at every service."),
    ]

    items = []
    for question, answer in questions:
        items.append(
            '<!-- wp:details {"className":"pato-faq__item"} -->\n'
            '<details class="wp-block-details pato-faq__item">'
            '<summary>%s</summary>\n'
            '%s\n'
            '</details>\n'
            '<!-- /wp:details -->' % (question, paragraph(answer, color="muted"))
        )

    inner = "\n".join([
        section_head("Before you come", "Questions we are asked"),
        spacer("60"),
        group("\n".join(items), layout="constrained", content_size="820px", gap="30",
              extra_class="pato-faq"),
    ])
    write("faq", "FAQ",
          group(inner, align="full", padding_y="70"),
          categories=["pato-sections"], keywords=["faq", "questions", "accordion"],
          description="Six questions in collapsible panels. No JavaScript.")


def build_set_menu():
    """A priced set menu -- the thing groups actually ask for."""
    courses = [
        ("To start", ["Whole prawns, garlic a\u00efoli", "Smoked salmon &amp; leaf salad", "Pumpkin &amp; sage ravioli"]),
        ("Main", ["Rosemary lamb over fire", "Salmon, saffron &amp; fennel", "Beef fillet, wild mushrooms"]),
        ("To finish", ["Baked apple, vanilla custard", "Chocolate &amp; sea salt tart", "Aged emmental &amp; walnut"]),
    ]
    cols = []
    for title, dishes in courses:
        lines = [heading(title, level=3, size="x-large", align="center", margin_bottom="30")]
        lines.append(
            '<!-- wp:list {"className":"pato-set-menu__list"} -->\n'
            '<ul class="wp-block-list pato-set-menu__list">\n'
            + "\n".join('<!-- wp:list-item -->\n<li>%s</li>\n<!-- /wp:list-item -->' % d for d in dishes)
            + '\n</ul>\n'
            '<!-- /wp:list -->'
        )
        cols.append(column(group("\n".join(lines), gap="30"), width="33.33%"))

    inner = "\n".join([
        section_head("For a table of eight or more", "The set menu",
                     "Three courses, $48 a head, chosen from whatever is on that week. Vegetarian and vegan versions are the same price."),
        spacer("60"),
        wide_row("\n".join(cols), gap="50"),
        spacer("50"),
        buttons([button("Enquire about a group", "#pato-reservation")], align="center"),
    ])
    write("set-menu", "Set menu for groups",
          group(inner, align="full", padding_y="70", background="surface", anchor="menu"),
          categories=["pato-menu"], keywords=["set menu", "group", "party", "prix fixe"],
          viewport=1400,
          description="A three-course set menu with a price and a call to action.")


def build_specials():
    """Today's specials -- a board, not a menu."""
    specials = [
        ("Monday", "Half-price bottles", "Every bottle under $60, from 17:00."),
        ("Wednesday", "Two for one on the grill", "Any two mains from the fire, one price."),
        ("Thursday", "Wine night", "Six glasses, six growers, $55 a head."),
        ("Sunday", "Long lunch", "Three courses and a glass, $38, noon until four."),
    ]
    cards = "\n".join(
        column(group("\n".join([
            heading(day, level=3, style="pato-script", size="x-large", align="center"),
            heading(title, level=4, size="large", align="center", margin_bottom="20"),
            paragraph(note, align="center", color="muted", size="small"),
        ]), gap="20", style="card"), width="25%")
        for day, title, note in specials
    )
    inner = "\n".join([
        section_head("Every week", "What is on when"),
        spacer("60"),
        wide_row(cards, gap="40"),
    ])
    write("specials", "Weekly specials",
          group(inner, align="full", padding_y="70"),
          categories=["pato-menu", "pato-sections"],
          keywords=["specials", "offers", "weekly", "deals"],
          viewport=1400,
          description="Four weekly offers as cards.")


def build_private_dining():
    """Private hire -- the highest-value enquiry a restaurant gets."""
    left = image("gallery-banquet", "A tall arrangement of roses on a laid banquet table",
                 ratio="4/3", rounded="6px")
    right = group("\n".join([
        heading("Private dining", level=3, style="pato-script", size="x-large", align="left"),
        heading("Take the room", level=2, size="heading"),
        paragraph("The back room seats eighteen around one table, with its own bar and a door that closes. The whole restaurant seats fifty-four."),
        ('<!-- wp:list {"className":"is-style-pato-ticks"} -->\n'
         '<ul class="wp-block-list is-style-pato-ticks">\n'
         '<!-- wp:list-item -->\n<li>A menu written with you, not handed to you</li>\n<!-- /wp:list-item -->\n'
         '<!-- wp:list-item -->\n<li>Your own bar and someone behind it</li>\n<!-- /wp:list-item -->\n'
         '<!-- wp:list-item -->\n<li>No hire fee, only a minimum spend</li>\n<!-- /wp:list-item -->\n'
         '</ul>\n'
         '<!-- /wp:list -->'),
        spacer("30"),
        buttons([button("Ask about the room", "#pato-reservation")]),
    ]), gap="30")

    inner = columns([
        column(right, width="50%", vertical="center"),
        column(left, width="50%"),
    ], align="wide", gap="60", vertical="center")

    write("private-dining", "Private dining",
          group(inner, align="full", padding_y="70"),
          categories=["pato-sections"], keywords=["private", "events", "hire", "party"],
          viewport=1400,
          description="Private hire, with the room's capacity and what is included.")


def build_press():
    """Quotes from people whose names carry weight, which is not the same as
    guest reviews -- different shape, different section."""
    quotes = [
        ("&ldquo;The best fire cooking in the city, and it is not close.&rdquo;", "The Evening Standard"),
        ("&ldquo;Twenty-eight years in and still the hardest table to get.&rdquo;", "Time Out"),
        ("&ldquo;One star. A kitchen that knows exactly what it is.&rdquo;", "Guide Rouge"),
    ]
    cards = "\n".join(
        column(group("\n".join([
            paragraph(quote, align="center", size="large"),
            paragraph("<strong>" + who + "</strong>", align="center", color="muted", size="small"),
        ]), gap="30"), width="33.33%")
        for quote, who in quotes
    )
    inner = "\n".join([
        section_head("In print", "What the critics said"),
        spacer("60"),
        wide_row(cards, gap="50"),
    ])
    write("press", "Press quotes",
          group(inner, align="full", padding_y="70", background="surface"),
          categories=["pato-sections", "testimonials"],
          keywords=["press", "reviews", "critics", "awards"],
          viewport=1400,
          description="Three press quotes with the publication named.")


def build_map():
    """A map section.

    An <iframe> rather than an embedded API: OpenStreetMap needs no key, no
    billing account and no consent banner, and it draws for whoever installs
    the theme instead of showing them an error box. loading="lazy" keeps it off
    the critical path.
    """
    frame = (
        '<!-- wp:html -->\n'
        '<iframe class="pato-map" title="Map showing where Pato is"\n'
        '\tsrc="https://www.openstreetmap.org/export/embed.html?bbox=-74.0170%2C40.6990%2C-74.0070%2C40.7100&amp;layer=mapnik&amp;marker=40.704644%2C-74.011987"\n'
        '\tloading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>\n'
        '<!-- /wp:html -->'
    )
    inner = "\n".join([
        section_head("How to find us", "On the map"),
        spacer("50"),
        group(frame, align="wide", layout="default"),
    ])
    write("map", "Map",
          group(inner, align="full", padding_y="70", anchor="map"),
          categories=["pato-sections"], keywords=["map", "location", "directions", "find us"],
          description="A keyless OpenStreetMap embed. No API key and no consent banner.")


def build_delivery():
    """Takeaway and delivery, which most restaurant themes forget entirely."""
    inner = group("\n".join([
        heading("Not coming in tonight?", level=2, align="center", color="overlay", size="heading"),
        paragraph("The whole menu travels, except the things that should not. Order direct and we keep the fee instead of the app.",
                  align="center", color="overlay", size="large"),
        spacer("40"),
        buttons([
            button("Order for collection", "#"),
            button("Order delivery", "#", style="pato-outline"),
        ], align="center", gap="30"),
    ]), layout="constrained", content_size="720px", gap="30")

    write("delivery", "Delivery and collection",
          group(cover(inner, "post-pizza", dim=70, min_height=420), align="full", layout="default", anchor="order"),
          categories=["pato-sections", "call-to-action"],
          keywords=["delivery", "takeaway", "collection", "order"],
          viewport=1400,
          description="A takeaway and delivery call to action over a photograph.")


def build_gift_cards():
    left = group("\n".join([
        heading("Gift cards", level=3, style="pato-script", size="x-large", align="left"),
        heading("Dinner, for someone else", level=2, size="heading"),
        paragraph("Any amount, valid two years, and it can be spent on wine as readily as on food. Sent by email the moment you buy it, or printed and posted if you would rather hand it over."),
        spacer("30"),
        buttons([button("Buy a gift card", "#")]),
    ]), gap="30")
    inner = columns([
        column(left, width="55%", vertical="center"),
        column(image("drink-wine", "Glasses of white wine catching the light",
                     ratio="16/9", rounded="6px"), width="45%", vertical="center"),
    ], align="wide", gap="60", vertical="center")
    write("gift-cards", "Gift cards",
          group(inner, align="full", padding_y="70", background="surface"),
          categories=["pato-sections", "call-to-action"],
          keywords=["gift card", "voucher", "present"],
          viewport=1400,
          description="A gift card offer beside a photograph.")


def build_allergens():
    inner = group("\n".join([
        heading("Allergens and how we handle them", level=2, size="x-large", align="center"),
        paragraph("Every dish is cooked to order in one kitchen, so we can adapt most of the menu &mdash; but we cannot promise a room free of any ingredient. Tell us when you book and the kitchen plans for it rather than improvising on the night.",
                  align="center", color="muted"),
        paragraph("Full allergen information for every dish is behind the bar and any of the floor team will bring it to you.",
                  align="center", color="muted", size="small"),
    ]), layout="constrained", content_size="760px", gap="30", style="card")

    write("allergens", "Allergen note",
          group(inner, align="full", padding_y="60"),
          categories=["pato-menu"], keywords=["allergens", "dietary", "notice"],
          description="A short allergen statement for the foot of a menu page.")


def build_hours_banner():
    """A compact hours strip, for the top or bottom of a page."""
    items = [
        ("Lunch", "11:00 &ndash; 16:00"),
        ("Dinner", "17:00 &ndash; 22:30"),
        ("Sunday", "10:00 &ndash; 22:00"),
        ("Bar", "until late"),
    ]
    cells = "\n".join(
        column(group("\n".join([
            paragraph("<strong>" + label + "</strong>", align="center", color="overlay", size="small"),
            paragraph(value, align="center", color="overlay"),
        ]), gap="20"), width="25%")
        for label, value in items
    )
    write("hours-banner", "Opening hours strip",
          group(wide_row(cells, gap="40"), align="full", padding_y="50",
                background="dark", text="overlay"),
          categories=["pato-sections"], keywords=["hours", "times", "strip", "banner"],
          description="A four-column strip of opening times on the dark ground.")


def build_cta_book():
    inner = group("\n".join([
        heading("A table is the only thing you need to bring", level=2, align="center", size="heading"),
        paragraph("Booking takes a minute and we confirm by email the same day.",
                  align="center", color="muted", size="large"),
        spacer("40"),
        buttons([button("Book a table", "#pato-reservation")], align="center"),
    ]), layout="constrained", content_size="760px", gap="30")
    write("cta-book", "Booking call to action",
          group(inner, align="full", padding_y="70", background="surface"),
          categories=["pato-sections", "call-to-action"],
          keywords=["cta", "book", "reservation"],
          description="A short booking prompt to close a page with.")


# ===========================================================================
# Whole pages
# ===========================================================================
# These are what inc/front-page-setup.php expands into real posts on first
# activation, so the site a new owner lands on is the one in the screenshot.

def ref(slug):
    """A reference to another pattern, expanded when this one renders."""
    return '<!-- wp:pattern {"slug":"pato/%s"} /-->' % slug


# ===========================================================================
# Starter sites
# ===========================================================================
# Six venues, one home layout each. They are the same sections in different
# orders with different copy -- which is the honest shape of this: a bar and a
# bakery do not need different code, they need a different first screen and a
# different thing asked of the visitor.
#
# inc/starter-sites.php expands one of these into the front page and sets the
# palette and type preset to match.

STARTERS = {
    "bistro": {
        "name": "Bistro",
        "palette": "colors-1-ember",
        "type": "type-1-montserrat",
        "welcome": ("Welcome", "Cooked over fire, eaten slowly",
                    "We opened with six tables and one grill. Most of that is still true: the room is bigger, but everything still comes off the same fire, and the menu is still written the morning it is served.",
                    "Produce comes from growers we have used for years. What they have decided is ready is what you will find on the menu that week.",
                    "story-salmon", "A grilled salmon fillet on a salad of tomato and rocket"),
        "photo": "hero-dining",
        "eyebrow": "Neighbourhood bistro",
        "title": "A table by the fire",
        "blurb": "Seasonal plates, an open kitchen and a short, careful wine list &mdash; in the middle of the city since 1998.",
        "sections": ["welcome", "menu-lunch-dinner", "specials", "reviews",
                     "reservation", "gallery", "blog-latest"],
        "cta": [("Book a table", "#pato-reservation"), ("See the menu", "#menu")],
    },
    "fine-dining": {
        "name": "Fine dining",
        "palette": "colors-7-cellar",
        "type": "type-3-classic",
        "welcome": ("The kitchen", "One menu, written each morning",
                    "There is no choosing. What arrives at the door before eight decides what is served after seven, and the eight courses are built around it that day.",
                    "Twenty-four seats, one sitting, and a pass you can watch from every table in the room.",
                    "chef-flambe", "A chef cooking over a flaring pan"),
        "photo": "hero-grill",
        "eyebrow": "Tasting menu",
        "title": "Eight courses, one sitting",
        "blurb": "One menu a night, written that morning, served from seven. Twenty-four seats and a kitchen you can see into.",
        "sections": ["welcome", "set-menu", "press", "chefs",
                     "private-dining", "reservation", "allergens"],
        "cta": [("Reserve a seat", "#pato-reservation"), ("Tonight's menu", "#menu")],
    },
    "cafe": {
        "name": "Caf\u00e9",
        "palette": "colors-5-harvest",
        "type": "type-2-poppins",
        "welcome": ("Since 2011", "The corner everyone claims",
                    "Coffee roasted eight miles away, pastries out of the oven at eight, and a lunch menu that changes when the market does.",
                    "There is no time limit on a table and the wifi password is on the board. Stay as long as you like.",
                    "dish-baked-apple", "A baked apple with strawberry and blueberries in a pool of custard"),
        "photo": "dish-baked-apple",
        "eyebrow": "All day",
        "title": "Coffee, cake and a quiet corner",
        "blurb": "Open from seven. Pastries out of the oven at eight, lunch from eleven, and nobody minding how long you sit.",
        "sections": ["welcome", "menu-drinks", "specials", "gallery",
                     "gift-cards", "opening-hours", "blog-latest"],
        "cta": [("See what we serve", "#drinks"), ("Opening times", "#hours")],
    },
    "pizzeria": {
        "name": "Pizzeria",
        "palette": "colors-2-olive",
        "type": "type-2-poppins",
        "welcome": ("The dough", "Two days in the making",
                    "Proved for forty-eight hours, stretched by hand, and ninety seconds over wood at four hundred degrees. That is the whole method.",
                    "Tomatoes from one farm, mozzarella delivered each morning, and nothing on the menu that needs more than six ingredients.",
                    "post-pizza", "A hand lifting a slice from a pizza on a floured board"),
        "photo": "post-pizza",
        "eyebrow": "Wood fired",
        "title": "Ninety seconds in a very hot oven",
        "blurb": "Dough proved for two days, tomatoes from one farm, and a queue out the door most Fridays. Eat in or take it home.",
        "sections": ["menu-lunch-dinner", "delivery", "welcome", "reviews",
                     "specials", "opening-hours", "map"],
        "cta": [("Order now", "#order"), ("See the menu", "#menu")],
    },
    "bar": {
        "name": "Bar",
        "palette": "colors-6-midnight",
        "type": "type-1-montserrat",
        "welcome": ("Behind the bar", "Forty cocktails, half of them ours",
                    "The classics made properly and a list of our own that changes with whatever we have been infusing, fermenting or ageing in the back.",
                    "Food is small, shared and designed to be eaten standing up if that is how the night is going.",
                    "drink-cocktail", "A bartender straining a cocktail into a glass"),
        "photo": "drink-cocktail",
        "eyebrow": "Cocktails &amp; small plates",
        "title": "Open until late",
        "blurb": "Forty cocktails, half of them ours, and food that is meant to be shared rather than fought over.",
        "sections": ["menu-drinks", "specials", "events", "gallery",
                     "private-dining", "hours-banner", "reservation"],
        "cta": [("Book a table", "#pato-reservation"), ("The drinks list", "#drinks")],
    },
    "bakery": {
        "name": "Bakery",
        "palette": "colors-5-harvest",
        "type": "type-2-poppins",
        "welcome": ("From four in the morning", "Bread that takes its time",
                    "Sourdough on a starter we have kept alive since 2014, proved overnight and baked before the sun is properly up.",
                    "Whatever the bakers felt like making goes on the counter beside it. When it is gone, it is gone.",
                    "gallery-sandwiches", "Club sandwiches cut into triangles on a wooden board"),
        "photo": "gallery-sandwiches",
        "eyebrow": "Baked each morning",
        "title": "Out of the oven at six",
        "blurb": "Sourdough, pastries and whatever the bakers felt like at four in the morning. When it is gone, it is gone.",
        "sections": ["welcome", "menu-course", "specials", "gift-cards",
                     "gallery", "opening-hours", "delivery"],
        "cta": [("What we bake", "#menu"), ("Order a collection", "#order")],
    },
}


def build_starters():
    for slug, cfg in STARTERS.items():
        hero_inner = group("\n".join([
            heading(cfg["eyebrow"], level=3, align="center", style="pato-script",
                    size="x-large", color="overlay"),
            heading(cfg["title"], level=1, align="center", color="overlay", size="colossal"),
            paragraph(cfg["blurb"], align="center", color="overlay", size="large"),
            spacer("40"),
            # Per starter, because half of them have no reservation section:
            # a hero that says "Book a table" on a bakery page, linking to an
            # anchor that is not on the page, is a dead button on a first
            # screen.
            buttons([
                button(cfg["cta"][0][0], cfg["cta"][0][1]),
                button(cfg["cta"][1][0], cfg["cta"][1][1], style="pato-outline"),
            ], align="center", gap="30"),
        ]), layout="constrained", content_size="780px", gap="30")

        write("hero-%s" % slug, "Hero: %s" % cfg["name"],
              cover(hero_inner, cfg["photo"], dim=62, min_height=600,
                    extra_class="pato-banner"),
              categories=["pato-sections", "banner"],
              keywords=["hero", slug], viewport=1400,
              description="The %s starter's opening screen." % cfg["name"].lower())

        # Its own welcome section too. Six venues sharing one "cooked over fire"
        # paragraph is how a starter set stops being six briefs and becomes one
        # brief with six photographs.
        eyebrow_txt, title, para1, para2, photo, alt = cfg["welcome"]
        right = group("\n".join([
            heading(eyebrow_txt, level=3, style="pato-script", size="x-large", align="left"),
            heading(title, level=2, size="heading"),
            paragraph(para1),
            paragraph(para2),
            spacer("30"),
            buttons([button("Our story", "#about", style="pato-ghost")]),
        ]), gap="30")
        welcome_inner = columns([
            column(image(photo, alt, ratio="4/5", rounded="6px"), width="46%"),
            column(right, width="54%", vertical="center"),
        ], align="wide", gap="60", vertical="center")

        write("welcome-%s" % slug, "Welcome: %s" % cfg["name"],
              group(welcome_inner, align="full", padding_y="70", anchor="about"),
              categories=["pato-sections"], keywords=["about", slug],
              viewport=1400,
              description="The %s starter's introduction." % cfg["name"].lower())

        sections = ["welcome-%s" % slug if x == "welcome" else x for x in cfg["sections"]]

        write("demo-%s" % slug, "Starter: %s" % cfg["name"],
              "\n\n".join([ref("hero-%s" % slug)] + [ref(x) for x in sections]),
              categories=["pato-pages"], keywords=["starter", "demo", slug],
              viewport=1400,
              description="A complete %s home page." % cfg["name"].lower())


def build_pages():
    write("page-home", "Page: home",
          "\n\n".join([
              ref("hero"), ref("welcome"), ref("menu-lunch-dinner"), ref("events"),
              ref("reviews"), ref("reservation"), ref("gallery"), ref("blog-latest"),
          ]),
          categories=["pato-pages"], keywords=["home", "front page"],
          viewport=1400,
          description="A complete restaurant home page.")

    write("page-menu", "Page: menu",
          "\n\n".join([
              ref("hidden-menu-banner"), ref("menu-lunch-dinner"), ref("menu-drinks"),
              ref("newsletter"),
          ]),
          categories=["pato-pages"], keywords=["menu", "food"],
          viewport=1400,
          description="A full menu page with food and drinks.")

    write("page-reservation", "Page: reservation",
          "\n\n".join([ref("hidden-reservation-banner"), ref("reservation"), ref("opening-hours")]),
          categories=["pato-pages"], keywords=["reservation", "booking"],
          viewport=1400,
          description="A booking page with the form and opening times.")

    write("page-about", "Page: about",
          "\n\n".join([ref("hidden-about-banner"), ref("welcome"), ref("chefs"), ref("reviews"), ref("events")]),
          categories=["pato-pages"], keywords=["about", "story", "team"],
          viewport=1400,
          description="An about page with the story, the chefs and reviews.")

    write("page-gallery", "Page: gallery",
          "\n\n".join([ref("hidden-gallery-banner"), ref("gallery"), ref("events")]),
          categories=["pato-pages"], keywords=["gallery", "photos"],
          viewport=1400,
          description="A gallery page.")

    write("page-contact", "Page: contact",
          "\n\n".join([ref("hidden-contact-banner"), ref("opening-hours"), ref("reservation")]),
          categories=["pato-pages"], keywords=["contact", "find us"],
          viewport=1400,
          description="A contact page with details and the booking form.")


def build_page_banners():
    """A static banner per page, so a page pattern does not depend on a
    featured image having been set."""
    banners = [
        ("menu", "banner-menu", "Menu", "Lunch, dinner and everything from the bar"),
        ("reservation", "reservation-table", "Reservation", "Tell us when, and we will keep a table"),
        ("about", "banner-about", "About us", "Twenty-eight years of the same fire"),
        ("gallery", "gallery-banquet", "Gallery", "The room, the plates and the people"),
        ("contact", "banner-contact", "Contact", "Where to find us, and how to reach us"),
    ]
    for slug, photo, title, blurb in banners:
        inner = group("\n".join([
            heading(title, level=1, align="center", color="overlay", size="colossal"),
            paragraph(blurb, align="center", color="overlay", size="large"),
        ]), layout="constrained", content_size="820px", gap="30")
        write("hidden-%s-banner" % slug, "%s banner" % title,
              cover(inner, photo, dim=60, min_height=380, extra_class="pato-banner"),
              inserter=False)


def main():
    build_header()
    build_footer()
    build_sidebar()
    build_hidden()
    build_page_banners()
    build_hero()
    build_welcome()
    build_menu_sections()
    build_reservation()
    build_opening_hours()
    build_gallery()
    build_chefs()
    build_reviews()
    build_events()
    build_blog_latest()
    build_newsletter()
    build_faq()
    build_set_menu()
    build_specials()
    build_private_dining()
    build_press()
    build_map()
    build_delivery()
    build_gift_cards()
    build_allergens()
    build_hours_banner()
    build_cta_book()
    build_starters()
    build_pages()

    for slug in sorted(WRITTEN):
        print("  patterns/%s.php" % slug)
    print("\n%d patterns" % len(WRITTEN))


if __name__ == "__main__":
    main()
