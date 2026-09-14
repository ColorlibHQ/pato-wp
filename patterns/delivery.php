<?php
/**
 * Title: Delivery and collection
 * Slug: pato/delivery
 * Categories: pato-sections, call-to-action
 * Keywords: delivery, takeaway, collection, order
 * Description: A takeaway and delivery call to action over a photograph.
 * Viewport Width: 1400
 *
 * @package Pato
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","layout":{"type":"default"},"anchor":"order"} -->
<div class="wp-block-group alignfull" id="order"><!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'assets/images/post-pizza.avif' ) ); ?>","dimRatio":70,"overlayColor":"dark","isUserOverlayColor":true,"minHeight":420,"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull" style="min-height:420px"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/post-pizza.avif' ) ); ?>" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-dark-background-color has-background-dim-70 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"720px"}} -->
<div class="wp-block-group"><!-- wp:heading {"className":"pato-section__title","style":{"typography":{"textAlign":"center"}},"textColor":"overlay"} -->
<h2 class="wp-block-heading has-text-align-center pato-section__title has-overlay-color has-text-color">Takeaway</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"}},"textColor":"overlay","fontSize":"large"} -->
<p class="has-text-align-center has-overlay-color has-text-color has-large-font-size">Not coming in tonight? The whole menu travels, except the things that should not. Order direct and we keep the fee instead of the app.</p>
<!-- /wp:paragraph -->

<!-- wp:spacer {"height":"var(\u002d\u002dwp\u002d\u002dpreset\u002d\u002dspacing\u002d\u002d40)"} -->
<div style="height:var(--wp--preset--spacing--40)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:buttons {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#">Order for collection</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-pato-outline"} -->
<div class="wp-block-button is-style-pato-outline"><a class="wp-block-button__link wp-element-button" href="#">Order delivery</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:group -->
