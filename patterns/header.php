<?php
/**
 * Title: Header
 * Slug: pato/header
 * Categories: header
 * Block Types: core/template-part/header
 * Inserter: no
 *
 * @package Pato
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40)"><!-- wp:columns {"verticalAlignment":"center","align":"wide"} -->
<div class="wp-block-columns alignwide are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","width":"22%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:22%"><!-- wp:group {"className":"pato-header__brand is-layout-flex","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex"}} -->
<div class="wp-block-group pato-header__brand is-layout-flex"><!-- wp:site-logo {"width":150} /-->

<!-- wp:site-title {"level":0} /--></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"56%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:56%"><!-- wp:navigation {"className":"pato-nav","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","justifyContent":"right","flexWrap":"nowrap"}} /--></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"22%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:22%"><!-- wp:buttons {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","justifyContent":"right","flexWrap":"nowrap"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-pato-ghost pato-scheme-toggle"} -->
<div class="wp-block-button is-style-pato-ghost pato-scheme-toggle"><a class="wp-block-button__link wp-element-button" href="#"><span class="screen-reader-text">Toggle dark mode</span></a></div>
<!-- /wp:button -->

<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#pato-reservation">Book a table</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
