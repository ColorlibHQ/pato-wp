<?php
/**
 * Title: Map
 * Slug: pato/map
 * Categories: pato-sections
 * Keywords: map, location, directions, find us
 * Description: A keyless OpenStreetMap embed. No API key and no consent banner.
 *
 * @package Pato
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained"},"anchor":"map"} -->
<div class="wp-block-group alignfull" id="map" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"820px"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":3,"className":"is-style-pato-script pato-section__script","style":{"typography":{"textAlign":"center"}}} -->
<h3 class="wp-block-heading has-text-align-center is-style-pato-script pato-section__script">How to find us</h3>
<!-- /wp:heading -->

<!-- wp:heading {"className":"pato-section__title","style":{"typography":{"textAlign":"center"}}} -->
<h2 class="wp-block-heading has-text-align-center pato-section__title">On the map</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:spacer {"height":"var(\u002d\u002dwp\u002d\u002dpreset\u002d\u002dspacing\u002d\u002d50)"} -->
<div style="height:var(--wp--preset--spacing--50)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:group {"align":"wide","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide"><!-- wp:html -->
<iframe class="pato-map" title="Map showing where Pato is"
	src="https://www.openstreetmap.org/export/embed.html?bbox=-74.0170%2C40.6990%2C-74.0070%2C40.7100&amp;layer=mapnik&amp;marker=40.704644%2C-74.011987"
	loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
<!-- /wp:html --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
