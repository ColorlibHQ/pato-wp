<?php
/**
 * Title: Hero: Café
 * Slug: pato/hero-cafe
 * Categories: pato-sections, banner
 * Keywords: hero, cafe
 * Description: The café starter's opening screen.
 * Viewport Width: 1400
 *
 * @package Pato
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'assets/images/dish-baked-apple.avif' ) ); ?>","dimRatio":62,"overlayColor":"dark","isUserOverlayColor":true,"minHeight":100,"minHeightUnit":"vh","align":"full","className":"pato-banner pato-hero","layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull pato-banner pato-hero" style="min-height:100vh"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/dish-baked-apple.avif' ) ); ?>" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-dark-background-color has-background-dim-60 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"780px"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":3,"className":"is-style-pato-script pato-hero__eyebrow","style":{"typography":{"textAlign":"center"}},"textColor":"overlay"} -->
<h3 class="wp-block-heading has-text-align-center is-style-pato-script pato-hero__eyebrow has-overlay-color has-text-color">All day</h3>
<!-- /wp:heading -->

<!-- wp:heading {"level":1,"className":"pato-hero__title","style":{"typography":{"textAlign":"center"}},"textColor":"overlay"} -->
<h1 class="wp-block-heading has-text-align-center pato-hero__title has-overlay-color has-text-color">Coffee, cake and a quiet corner</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"}},"textColor":"overlay","fontSize":"large"} -->
<p class="has-text-align-center has-overlay-color has-text-color has-large-font-size">Open from seven. Pastries out of the oven at eight, lunch from eleven, and nobody minding how long you sit.</p>
<!-- /wp:paragraph -->

<!-- wp:spacer {"height":"var(\u002d\u002dwp\u002d\u002dpreset\u002d\u002dspacing\u002d\u002d40)"} -->
<div style="height:var(--wp--preset--spacing--40)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:buttons {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#drinks">See what we serve</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-pato-outline"} -->
<div class="wp-block-button is-style-pato-outline"><a class="wp-block-button__link wp-element-button" href="#hours">Opening times</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->
