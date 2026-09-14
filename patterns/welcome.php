<?php
/**
 * Title: Welcome: image and text
 * Slug: pato/welcome
 * Categories: pato-sections
 * Keywords: about, welcome, story
 * Description: A photograph beside an introduction.
 * Viewport Width: 1400
 *
 * @package Pato
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)"><!-- wp:columns {"verticalAlignment":"center","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns alignwide are-vertically-aligned-center"><!-- wp:column {"width":"46%"} -->
<div class="wp-block-column" style="flex-basis:46%"><!-- wp:image {"aspectRatio":"4/5","scale":"cover","sizeSlug":"large","linkDestination":"none","style":{"border":{"radius":"6px"}}} -->
<figure class="wp-block-image size-large has-custom-border"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/story-salmon.avif' ) ); ?>" alt="A grilled salmon fillet on a salad of tomato and rocket" style="border-radius:6px;aspect-ratio:4/5;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"54%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:54%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":3,"className":"is-style-pato-script","style":{"typography":{"textAlign":"left"}},"fontSize":"x-large"} -->
<h3 class="wp-block-heading has-text-align-left is-style-pato-script has-x-large-font-size">Welcome</h3>
<!-- /wp:heading -->

<!-- wp:heading {"fontSize":"heading"} -->
<h2 class="wp-block-heading has-heading-font-size">Cooked over fire, eaten slowly</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We opened with six tables and one grill. Most of that is still true: the room is bigger, but everything still comes off the same fire, and the menu is still written the morning it is served.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Produce comes from growers we have used for years. What they have decided is ready is what you will find on the menu that week.</p>
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
