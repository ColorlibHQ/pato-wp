<?php
/**
 * Title: Set menu for groups
 * Slug: pato/set-menu
 * Categories: pato-menu
 * Keywords: set menu, group, party, prix fixe
 * Description: A three-course set menu with a price and a call to action.
 * Viewport Width: 1400
 *
 * @package Pato
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"backgroundColor":"surface","layout":{"type":"constrained"},"anchor":"menu"} -->
<div class="wp-block-group alignfull has-surface-background-color has-background" id="menu" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"720px"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":3,"className":"is-style-pato-script","style":{"typography":{"textAlign":"center"}},"fontSize":"x-large"} -->
<h3 class="wp-block-heading has-text-align-center is-style-pato-script has-x-large-font-size">For a table of eight or more</h3>
<!-- /wp:heading -->

<!-- wp:heading {"style":{"typography":{"textAlign":"center"}},"fontSize":"heading"} -->
<h2 class="wp-block-heading has-text-align-center has-heading-font-size">The set menu</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"}},"textColor":"muted","fontSize":"large"} -->
<p class="has-text-align-center has-muted-color has-text-color has-large-font-size">Three courses, $48 a head, chosen from whatever is on that week. Vegetarian and vegan versions are the same price.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:spacer {"height":"var(\u002d\u002dwp\u002d\u002dpreset\u002d\u002dspacing\u002d\u002d60)"} -->
<div style="height:var(--wp--preset--spacing--60)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns alignwide"><!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":3,"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|30"}},"typography":{"textAlign":"center"}},"fontSize":"x-large"} -->
<h3 class="wp-block-heading has-text-align-center has-x-large-font-size" style="margin-bottom:var(--wp--preset--spacing--30)">To start</h3>
<!-- /wp:heading -->

<!-- wp:list {"className":"pato-set-menu__list"} -->
<ul class="wp-block-list pato-set-menu__list"><!-- wp:list-item -->
<li>Whole prawns, garlic aïoli</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Smoked salmon &amp; leaf salad</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Pumpkin &amp; sage ravioli</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":3,"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|30"}},"typography":{"textAlign":"center"}},"fontSize":"x-large"} -->
<h3 class="wp-block-heading has-text-align-center has-x-large-font-size" style="margin-bottom:var(--wp--preset--spacing--30)">Main</h3>
<!-- /wp:heading -->

<!-- wp:list {"className":"pato-set-menu__list"} -->
<ul class="wp-block-list pato-set-menu__list"><!-- wp:list-item -->
<li>Rosemary lamb over fire</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Salmon, saffron &amp; fennel</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Beef fillet, wild mushrooms</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":3,"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|30"}},"typography":{"textAlign":"center"}},"fontSize":"x-large"} -->
<h3 class="wp-block-heading has-text-align-center has-x-large-font-size" style="margin-bottom:var(--wp--preset--spacing--30)">To finish</h3>
<!-- /wp:heading -->

<!-- wp:list {"className":"pato-set-menu__list"} -->
<ul class="wp-block-list pato-set-menu__list"><!-- wp:list-item -->
<li>Baked apple, vanilla custard</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Chocolate &amp; sea salt tart</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Aged emmental &amp; walnut</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:spacer {"height":"var(\u002d\u002dwp\u002d\u002dpreset\u002d\u002dspacing\u002d\u002d50)"} -->
<div style="height:var(--wp--preset--spacing--50)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#pato-reservation">Enquire about a group</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
