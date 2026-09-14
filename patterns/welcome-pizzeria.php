<?php
/**
 * Title: Welcome: Pizzeria
 * Slug: pato/welcome-pizzeria
 * Categories: pato-sections
 * Keywords: about, pizzeria
 * Description: The pizzeria starter's introduction.
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
<figure class="wp-block-image size-large has-custom-border"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/post-pizza.avif' ) ); ?>" alt="A hand lifting a slice from a pizza on a floured board" style="border-radius:6px;aspect-ratio:4/5;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"54%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:54%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":3,"className":"is-style-pato-script pato-section__script","style":{"typography":{"textAlign":"left"}}} -->
<h3 class="wp-block-heading has-text-align-left is-style-pato-script pato-section__script">The dough</h3>
<!-- /wp:heading -->

<!-- wp:heading {"className":"pato-section__title"} -->
<h2 class="wp-block-heading pato-section__title">Two days in the making</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Proved for forty-eight hours, stretched by hand, and ninety seconds over wood at four hundred degrees. That is the whole method.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Tomatoes from one farm, mozzarella delivered each morning, and nothing on the menu that needs more than six ingredients.</p>
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
