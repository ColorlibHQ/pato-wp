<?php
/**
 * Title: Welcome: Café
 * Slug: pato/welcome-cafe
 * Categories: pato-sections
 * Keywords: about, cafe
 * Description: The café starter's introduction.
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
<figure class="wp-block-image size-large has-custom-border"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/dish-baked-apple.avif' ) ); ?>" alt="A baked apple with strawberry and blueberries in a pool of custard" style="border-radius:6px;aspect-ratio:4/5;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"54%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:54%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":3,"className":"is-style-pato-script pato-section__script","style":{"typography":{"textAlign":"left"}}} -->
<h3 class="wp-block-heading has-text-align-left is-style-pato-script pato-section__script">Since 2011</h3>
<!-- /wp:heading -->

<!-- wp:heading {"className":"pato-section__title"} -->
<h2 class="wp-block-heading pato-section__title">The corner everyone claims</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Coffee roasted eight miles away, pastries out of the oven at eight, and a lunch menu that changes when the market does.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>There is no time limit on a table and the wifi password is on the board. Stay as long as you like.</p>
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
