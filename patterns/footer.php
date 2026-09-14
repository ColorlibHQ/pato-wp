<?php
/**
 * Title: Footer
 * Slug: pato/footer
 * Categories: footer
 * Block Types: core/template-part/footer
 * Inserter: no
 *
 * @package Pato
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"pato-footer","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"backgroundColor":"dark","textColor":"overlay","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull pato-footer has-overlay-color has-dark-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)"><!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns alignwide"><!-- wp:column {"width":"26%"} -->
<div class="wp-block-column" style="flex-basis:26%"><!-- wp:paragraph {"className":"pato-footer__title"} -->
<p class="pato-footer__title"><strong>Find us</strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"textColor":"overlay","fontSize":"small"} -->
<p class="has-overlay-color has-text-color has-small-font-size">80 Broad Street<br>New York, NY 10004</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"textColor":"overlay","fontSize":"small"} -->
<p class="has-overlay-color has-text-color has-small-font-size"><a href="tel:+18001234567">+1 800 123 4567</a><br><a href="mailto:hello@example.com">hello@example.com</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"28%"} -->
<div class="wp-block-column" style="flex-basis:28%"><!-- wp:paragraph {"className":"pato-footer__title"} -->
<p class="pato-footer__title"><strong>Opening times</strong></p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<dl class="pato-hours">
	<dt>Monday &ndash; Friday</dt><dd>11:00 &ndash; 23:00</dd>
	<dt>Saturday</dt><dd>10:00 &ndash; 23:00</dd>
	<dt>Sunday</dt><dd>10:00 &ndash; 22:00</dd>
</dl>
<!-- /wp:html --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"22%"} -->
<div class="wp-block-column" style="flex-basis:22%"><!-- wp:paragraph {"className":"pato-footer__title"} -->
<p class="pato-footer__title"><strong>Explore</strong></p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"pato-footer__links"} -->
<ul class="wp-block-list pato-footer__links"><!-- wp:list-item -->
<li><a href="#menu">Menu</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="#pato-reservation">Reservations</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="#gallery">Gallery</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="#about">About</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="#contact">Contact</a></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"24%"} -->
<div class="wp-block-column" style="flex-basis:24%"><!-- wp:paragraph {"className":"pato-footer__title"} -->
<p class="pato-footer__title"><strong>Elsewhere</strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"textColor":"overlay","fontSize":"small"} -->
<p class="has-overlay-color has-text-color has-small-font-size">Follow the kitchen, the specials and whatever the chef is pickling this week.</p>
<!-- /wp:paragraph -->

<!-- wp:social-links {"iconColor":"overlay","iconColorValue":"var(\u002d\u002dwp\u002d\u002dpreset\u002d\u002dcolor\u002d\u002doverlay)","className":"is-style-logos-only","layout":{"type":"flex"}} -->
<ul class="wp-block-social-links has-icon-color is-style-logos-only"><!-- wp:social-link {"url":"#","service":"instagram"} /-->

<!-- wp:social-link {"url":"#","service":"facebook"} /-->

<!-- wp:social-link {"url":"#","service":"x"} /--></ul>
<!-- /wp:social-links --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:spacer {"height":"var(\u002d\u002dwp\u002d\u002dpreset\u002d\u002dspacing\u002d\u002d50)"} -->
<div style="height:var(--wp--preset--spacing--50)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:spacer {"height":"var(\u002d\u002dwp\u002d\u002dpreset\u002d\u002dspacing\u002d\u002d30)"} -->
<div style="height:var(--wp--preset--spacing--30)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:group {"align":"wide","className":"is-layout-flex is-content-justification-space-between","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
<div class="wp-block-group alignwide is-layout-flex is-content-justification-space-between"><!-- wp:paragraph {"textColor":"overlay","fontSize":"small"} -->
<p class="has-overlay-color has-text-color has-small-font-size">© <?php echo esc_html( gmdate( 'Y' ) ); ?> Pato. All rights reserved.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"textColor":"overlay","fontSize":"small"} -->
<p class="has-overlay-color has-text-color has-small-font-size">Made by <a href="https://colorlib.com/" rel="nofollow">Colorlib</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
