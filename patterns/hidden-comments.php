<?php
/**
 * Title: Comments
 * Slug: pato/hidden-comments
 * Inserter: no
 *
 * @package Pato
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:comments {"className":"pato-comments"} -->
<div class="wp-block-comments pato-comments"><!-- wp:heading {"level":3,"fontSize":"x-large"} -->
<h3 class="wp-block-heading has-x-large-font-size">Comments</h3>
<!-- /wp:heading -->

<!-- wp:comment-template -->
<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column {"width":"64px"} -->
<div class="wp-block-column" style="flex-basis:64px"><!-- wp:avatar {"size":64,"style":{"border":{"radius":"50%"}}} /--></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:comment-author-name {"fontSize":"small"} /-->

<!-- wp:comment-date {"fontSize":"small"} /-->

<!-- wp:comment-content /-->

<!-- wp:comment-reply-link {"fontSize":"small"} /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
<!-- /wp:comment-template -->

<!-- wp:comments-pagination {"layout":{"type":"flex","justifyContent":"center"}} -->
<!-- wp:comments-pagination-previous /-->

<!-- wp:comments-pagination-numbers /-->

<!-- wp:comments-pagination-next /-->
<!-- /wp:comments-pagination -->

<!-- wp:post-comments-form /--></div>
<!-- /wp:comments -->
