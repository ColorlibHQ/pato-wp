<?php
/**
 * Title: Reservation banner
 * Slug: pato/hidden-reservation-banner
 * Inserter: no
 *
 * @package Pato
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'assets/images/reservation-table.avif' ) ); ?>","dimRatio":60,"overlayColor":"dark","isUserOverlayColor":true,"minHeight":380,"align":"full","className":"pato-banner","layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull pato-banner" style="min-height:380px"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/reservation-table.avif' ) ); ?>" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-dark-background-color has-background-dim-60 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"820px"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":1,"style":{"typography":{"textAlign":"center"}},"textColor":"overlay","fontSize":"colossal"} -->
<h1 class="wp-block-heading has-text-align-center has-overlay-color has-text-color has-colossal-font-size">Reservation</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"}},"textColor":"overlay","fontSize":"large"} -->
<p class="has-text-align-center has-overlay-color has-text-color has-large-font-size">Tell us when, and we will keep a table</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->
