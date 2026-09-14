<?php
/**
 * Title: Reservation form
 * Slug: pato/reservation
 * Categories: pato-sections
 * Keywords: reservation, booking, form, table
 * Description: The booking form beside opening times.
 * Viewport Width: 1400
 *
 * @package Pato
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"backgroundColor":"surface","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-surface-background-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)"><!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns alignwide"><!-- wp:column {"verticalAlignment":"center","width":"42%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:42%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":3,"className":"is-style-pato-script","style":{"typography":{"textAlign":"left"}},"fontSize":"x-large"} -->
<h3 class="wp-block-heading has-text-align-left is-style-pato-script has-x-large-font-size">Reservations</h3>
<!-- /wp:heading -->

<!-- wp:heading {"fontSize":"heading"} -->
<h2 class="wp-block-heading has-heading-font-size">Book a table</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Tell us when and how many, and we will confirm by email. For parties over eight, or to take the whole room, call us on <a href="tel:+18001234567">+1 800 123 4567</a>.</p>
<!-- /wp:paragraph -->

<!-- wp:spacer {"height":"var(\u002d\u002dwp\u002d\u002dpreset\u002d\u002dspacing\u002d\u002d30)"} -->
<div style="height:var(--wp--preset--spacing--30)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:html -->
<dl class="pato-hours">
	<dt>Lunch</dt><dd>11:00 &ndash; 16:00</dd>
	<dt>Dinner</dt><dd>17:00 &ndash; 22:30</dd>
	<dt>Sunday</dt><dd>10:00 &ndash; 22:00</dd>
</dl>
<!-- /wp:html --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"58%"} -->
<div class="wp-block-column" style="flex-basis:58%"><!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|50","right":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50"}},"border":{"radius":"6px"}},"backgroundColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-base-background-color has-background" style="border-radius:6px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:shortcode -->
[pato_reservation_form]
<!-- /wp:shortcode --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
