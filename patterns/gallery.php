<?php
/**
 * Title: Gallery grid
 * Slug: pato/gallery
 * Categories: pato-sections, gallery
 * Keywords: gallery, photos, images
 * Description: Six photographs in two rows of three.
 * Viewport Width: 1400
 *
 * @package Pato
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"backgroundColor":"surface","layout":{"type":"constrained"},"anchor":"gallery"} -->
<div class="wp-block-group alignfull has-surface-background-color has-background" id="gallery" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"720px"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":3,"className":"is-style-pato-script","style":{"typography":{"textAlign":"center"}},"fontSize":"x-large"} -->
<h3 class="wp-block-heading has-text-align-center is-style-pato-script has-x-large-font-size">The room</h3>
<!-- /wp:heading -->

<!-- wp:heading {"style":{"typography":{"textAlign":"center"}},"fontSize":"heading"} -->
<h2 class="wp-block-heading has-text-align-center has-heading-font-size">A look around</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:spacer {"height":"var(\u002d\u002dwp\u002d\u002dpreset\u002d\u002dspacing\u002d\u002d60)"} -->
<div style="height:var(--wp--preset--spacing--60)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"var:preset|spacing|30"}}}} -->
<div class="wp-block-columns alignwide"><!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:image {"aspectRatio":"1","scale":"cover","sizeSlug":"large","linkDestination":"none","className":"is-style-pato-lift","style":{"border":{"radius":"6px"}}} -->
<figure class="wp-block-image size-large has-custom-border is-style-pato-lift"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/gallery-kitchen.avif' ) ); ?>" alt="A chef plating a dish at the pass of an open kitchen" style="border-radius:6px;aspect-ratio:1;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:image {"aspectRatio":"1","scale":"cover","sizeSlug":"large","linkDestination":"none","className":"is-style-pato-lift","style":{"border":{"radius":"6px"}}} -->
<figure class="wp-block-image size-large has-custom-border is-style-pato-lift"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/gallery-banquet.avif' ) ); ?>" alt="A tall arrangement of roses on a laid banquet table" style="border-radius:6px;aspect-ratio:1;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:image {"aspectRatio":"1","scale":"cover","sizeSlug":"large","linkDestination":"none","className":"is-style-pato-lift","style":{"border":{"radius":"6px"}}} -->
<figure class="wp-block-image size-large has-custom-border is-style-pato-lift"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/gallery-market.avif' ) ); ?>" alt="Visitors at an outdoor Christmas market" style="border-radius:6px;aspect-ratio:1;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"var:preset|spacing|30"}}}} -->
<div class="wp-block-columns alignwide"><!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:image {"aspectRatio":"1","scale":"cover","sizeSlug":"large","linkDestination":"none","className":"is-style-pato-lift","style":{"border":{"radius":"6px"}}} -->
<figure class="wp-block-image size-large has-custom-border is-style-pato-lift"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/gallery-champagne.avif' ) ); ?>" alt="A waiter carrying a tray of filled champagne flutes" style="border-radius:6px;aspect-ratio:1;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:image {"aspectRatio":"1","scale":"cover","sizeSlug":"large","linkDestination":"none","className":"is-style-pato-lift","style":{"border":{"radius":"6px"}}} -->
<figure class="wp-block-image size-large has-custom-border is-style-pato-lift"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/gallery-sandwiches.avif' ) ); ?>" alt="Club sandwiches cut into triangles on a wooden board" style="border-radius:6px;aspect-ratio:1;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:image {"aspectRatio":"1","scale":"cover","sizeSlug":"large","linkDestination":"none","className":"is-style-pato-lift","style":{"border":{"radius":"6px"}}} -->
<figure class="wp-block-image size-large has-custom-border is-style-pato-lift"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/gallery-toast.avif' ) ); ?>" alt="Three people touching whisky glasses together over a laid table" style="border-radius:6px;aspect-ratio:1;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
