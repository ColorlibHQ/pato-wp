<?php
/**
 * Title: Menu: drinks
 * Slug: pato/menu-drinks
 * Categories: pato-menu
 * Keywords: drinks, bar, wine, cocktails
 * Description: A drinks list beside a photograph.
 * Viewport Width: 1400
 *
 * @package Pato
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"backgroundColor":"surface","layout":{"type":"constrained"},"anchor":"drinks"} -->
<div class="wp-block-group alignfull has-surface-background-color has-background" id="drinks" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"820px"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":3,"className":"is-style-pato-script pato-section__script","style":{"typography":{"textAlign":"center"}}} -->
<h3 class="wp-block-heading has-text-align-center is-style-pato-script pato-section__script">From the bar</h3>
<!-- /wp:heading -->

<!-- wp:heading {"className":"pato-section__title","style":{"typography":{"textAlign":"center"}}} -->
<h2 class="wp-block-heading has-text-align-center pato-section__title">Drinks</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:spacer {"height":"var(\u002d\u002dwp\u002d\u002dpreset\u002d\u002dspacing\u002d\u002d60)"} -->
<div style="height:var(--wp--preset--spacing--60)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:columns {"verticalAlignment":"center","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns alignwide are-vertically-aligned-center"><!-- wp:column {"width":"58%"} -->
<div class="wp-block-column" style="flex-basis:58%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":3,"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|30"}}},"fontSize":"x-large"} -->
<h3 class="wp-block-heading has-x-large-font-size" style="margin-bottom:var(--wp--preset--spacing--30)">Wine &amp; cocktails</h3>
<!-- /wp:heading -->

<!-- wp:group {"className":"pato-dish","layout":{"type":"default"}} -->
<div class="wp-block-group pato-dish"><!-- wp:paragraph {"className":"pato-dish__name"} -->
<p class="pato-dish__name">House red — Nero d'Avola</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"pato-dish__price"} -->
<p class="pato-dish__price">$9</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"pato-dish__note"} -->
<p class="pato-dish__note">Glass. Bottle $34</p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"pato-dish","layout":{"type":"default"}} -->
<div class="wp-block-group pato-dish"><!-- wp:paragraph {"className":"pato-dish__name"} -->
<p class="pato-dish__name">House white — Picpoul</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"pato-dish__price"} -->
<p class="pato-dish__price">$9</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"pato-dish__note"} -->
<p class="pato-dish__note">Glass. Bottle $34</p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"pato-dish","layout":{"type":"default"}} -->
<div class="wp-block-group pato-dish"><!-- wp:paragraph {"className":"pato-dish__name"} -->
<p class="pato-dish__name">Negroni, stirred over a rock</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"pato-dish__price"} -->
<p class="pato-dish__price">$13</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"pato-dish","layout":{"type":"default"}} -->
<div class="wp-block-group pato-dish"><!-- wp:paragraph {"className":"pato-dish__name"} -->
<p class="pato-dish__name">Espresso martini</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"pato-dish__price"} -->
<p class="pato-dish__price">$14</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"pato-dish","layout":{"type":"default"}} -->
<div class="wp-block-group pato-dish"><!-- wp:paragraph {"className":"pato-dish__name"} -->
<p class="pato-dish__name">Alcohol-free spritz</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"pato-dish__price"} -->
<p class="pato-dish__price">$8</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"pato-dish__note"} -->
<p class="pato-dish__note">Seedlip, grapefruit, soda</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"42%"} -->
<div class="wp-block-column" style="flex-basis:42%"><!-- wp:image {"aspectRatio":"3/4","scale":"cover","sizeSlug":"large","linkDestination":"none","style":{"border":{"radius":"6px"}}} -->
<figure class="wp-block-image size-large has-custom-border"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/drink-cocktail.avif' ) ); ?>" alt="A bartender straining a cocktail into a glass" style="border-radius:6px;aspect-ratio:3/4;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
