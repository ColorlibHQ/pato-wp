<?php
/**
 * Title: Archive banner
 * Slug: pato/hidden-archive-banner
 * Inserter: no
 *
 * @package Pato
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'assets/images/banner-menu.avif' ) ); ?>","dimRatio":60,"overlayColor":"dark","isUserOverlayColor":true,"minHeight":340,"align":"full","className":"pato-banner","layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull pato-banner" style="min-height:340px"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/banner-menu.avif' ) ); ?>" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-dark-background-color has-background-dim-60 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"type":"constrained","contentSize":"860px"}} -->
<div class="wp-block-group"><!-- wp:query-title {"type":"archive","style":{"typography":{"textAlign":"center"}},"textColor":"base","fontSize":"colossal"} /-->

<!-- wp:term-description {"style":{"typography":{"textAlign":"center"}},"textColor":"base"} /--></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->
