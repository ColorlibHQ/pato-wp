<?php
/**
 * Title: Gallery: Bar
 * Slug: pato/gallery-bar
 * Categories: pato-sections, gallery
 * Keywords: gallery, bar
 * Description: The bar starter's gallery.
 * Viewport Width: 1400
 *
 * @package Pato
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"backgroundColor":"surface","layout":{"type":"constrained"},"anchor":"gallery"} -->
<div class="wp-block-group alignfull has-surface-background-color has-background" id="gallery" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"820px"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":3,"className":"is-style-pato-script pato-section__script","style":{"typography":{"textAlign":"center"}}} -->
<h3 class="wp-block-heading has-text-align-center is-style-pato-script pato-section__script">The room</h3>
<!-- /wp:heading -->

<!-- wp:heading {"className":"pato-section__title","style":{"typography":{"textAlign":"center"}}} -->
<h2 class="wp-block-heading has-text-align-center pato-section__title">A look around</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:spacer {"height":"var(\u002d\u002dwp\u002d\u002dpreset\u002d\u002dspacing\u002d\u002d60)"} -->
<div style="height:var(--wp--preset--spacing--60)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"var:preset|spacing|30"}}}} -->
<div class="wp-block-columns alignwide"><!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:image {"aspectRatio":"1","scale":"cover","sizeSlug":"large","linkDestination":"none","className":"is-style-pato-lift","style":{"border":{"radius":"6px"}}} -->
<figure class="wp-block-image size-large has-custom-border is-style-pato-lift"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/drink-cocktail.avif' ) ); ?>" alt="A bartender straining a cocktail into a glass" style="border-radius:6px;aspect-ratio:1;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:image {"aspectRatio":"1","scale":"cover","sizeSlug":"large","linkDestination":"none","className":"is-style-pato-lift","style":{"border":{"radius":"6px"}}} -->
<figure class="wp-block-image size-large has-custom-border is-style-pato-lift"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/whisky-toast.avif' ) ); ?>" alt="Three people touching whisky glasses together" style="border-radius:6px;aspect-ratio:1;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:image {"aspectRatio":"1","scale":"cover","sizeSlug":"large","linkDestination":"none","className":"is-style-pato-lift","style":{"border":{"radius":"6px"}}} -->
<figure class="wp-block-image size-large has-custom-border is-style-pato-lift"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/wine-in-hand.avif' ) ); ?>" alt="A hand holding a glass of red wine in a busy bar" style="border-radius:6px;aspect-ratio:1;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"var:preset|spacing|30"}}}} -->
<div class="wp-block-columns alignwide"><!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:image {"aspectRatio":"1","scale":"cover","sizeSlug":"large","linkDestination":"none","className":"is-style-pato-lift","style":{"border":{"radius":"6px"}}} -->
<figure class="wp-block-image size-large has-custom-border is-style-pato-lift"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/bar-counter-long.avif' ) ); ?>" alt="A long bar counter set with wine glasses" style="border-radius:6px;aspect-ratio:1;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:image {"aspectRatio":"1","scale":"cover","sizeSlug":"large","linkDestination":"none","className":"is-style-pato-lift","style":{"border":{"radius":"6px"}}} -->
<figure class="wp-block-image size-large has-custom-border is-style-pato-lift"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/bar-table.avif' ) ); ?>" alt="A bar table with wine glasses and red napkins" style="border-radius:6px;aspect-ratio:1;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:image {"aspectRatio":"1","scale":"cover","sizeSlug":"large","linkDestination":"none","className":"is-style-pato-lift","style":{"border":{"radius":"6px"}}} -->
<figure class="wp-block-image size-large has-custom-border is-style-pato-lift"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/canapes-strawberry.avif' ) ); ?>" alt="Strawberry and cheese canapes on sticks" style="border-radius:6px;aspect-ratio:1;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
