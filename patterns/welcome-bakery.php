<?php
/**
 * Title: Welcome: Bakery
 * Slug: pato/welcome-bakery
 * Categories: pato-sections
 * Keywords: about, bakery
 * Description: The bakery starter's introduction.
 * Viewport Width: 1400
 *
 * @package Pato
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained"},"anchor":"about"} -->
<div class="wp-block-group alignfull" id="about" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)"><!-- wp:columns {"verticalAlignment":"center","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns alignwide are-vertically-aligned-center"><!-- wp:column {"width":"46%"} -->
<div class="wp-block-column" style="flex-basis:46%"><!-- wp:image {"aspectRatio":"4/5","scale":"cover","sizeSlug":"large","linkDestination":"none","style":{"border":{"radius":"6px"}}} -->
<figure class="wp-block-image size-large has-custom-border"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/gallery-sandwiches.avif' ) ); ?>" alt="Club sandwiches cut into triangles on a wooden board" style="border-radius:6px;aspect-ratio:4/5;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"54%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:54%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":3,"className":"is-style-pato-script","style":{"typography":{"textAlign":"left"}},"fontSize":"x-large"} -->
<h3 class="wp-block-heading has-text-align-left is-style-pato-script has-x-large-font-size">From four in the morning</h3>
<!-- /wp:heading -->

<!-- wp:heading {"fontSize":"heading"} -->
<h2 class="wp-block-heading has-heading-font-size">Bread that takes its time</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Sourdough on a starter we have kept alive since 2014, proved overnight and baked before the sun is properly up.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Whatever the bakers felt like making goes on the counter beside it. When it is gone, it is gone.</p>
<!-- /wp:paragraph -->

<!-- wp:spacer {"height":"var(\u002d\u002dwp\u002d\u002dpreset\u002d\u002dspacing\u002d\u002d30)"} -->
<div style="height:var(--wp--preset--spacing--30)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-pato-ghost"} -->
<div class="wp-block-button is-style-pato-ghost"><a class="wp-block-button__link wp-element-button" href="#about">Our story</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
