<?php
/**
 * One-time repair of block attributes stripped by 1.0.0 and 1.1.0.
 *
 * Those versions built pages with wp_insert_post() without wp_slash(). The
 * function unslashes its input, so every `-` escape in a block attribute
 * lost its backslash: a spacer stored `var(u002du002dwp…)` instead of
 * `var(--wp…)`. The front end renders from the saved HTML and looked
 * right; the editor compared the attributes with that HTML and flagged every
 * spacer as "unexpected or invalid content".
 *
 * The damage is exactly one missing character in one sequence, inside block
 * comment delimiters, so it can be put back without guessing: nothing outside
 * a `<!-- wp:… {…} -->` comment is touched, and inside one only a `u002d` with
 * no backslash in front of it changes.
 *
 * @package Pato
 */

defined( 'ABSPATH' ) || exit;

const PATO_REPAIR_FLAG = 'pato_block_escape_repair';

/**
 * Restore stripped `-` escapes in every post's block attributes, once.
 *
 * Runs on admin_init rather than on theme update: updates arrive through the
 * updater, a host's control panel or an uploaded zip, and only admin_init is
 * certain to follow all of them.
 */
function pato_repair_block_escapes() {
	if ( get_option( PATO_REPAIR_FLAG ) || ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	/**
	 * Filters whether Pato repairs stripped block attribute escapes.
	 *
	 * @param bool $repair Whether to run the one-time repair. Default true.
	 */
	if ( ! apply_filters( 'pato_repair_block_escapes', true ) ) {
		return;
	}

	global $wpdb;

	$rows = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$wpdb->prepare(
			"SELECT ID, post_content FROM {$wpdb->posts} WHERE post_content LIKE %s",
			'%' . $wpdb->esc_like( 'u002d' ) . '%'
		)
	);

	$fixed = 0;

	foreach ( $rows as $row ) {
		$content = pato_restore_block_escapes( $row->post_content );

		if ( $content === $row->post_content ) {
			continue;
		}

		// $wpdb->update() escapes for SQL and does not unslash, so the
		// backslashes go in exactly as written.
		$wpdb->update( $wpdb->posts, array( 'post_content' => $content ), array( 'ID' => $row->ID ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		clean_post_cache( (int) $row->ID );
		++$fixed;
	}

	update_option( PATO_REPAIR_FLAG, array( 'version' => PATO_VERSION, 'fixed' => $fixed ), false );
}
add_action( 'admin_init', 'pato_repair_block_escapes' );

/**
 * Put back the backslash in front of each bare `u002d` inside block comments.
 *
 * @param string $content Post content.
 * @return string
 */
function pato_restore_block_escapes( $content ) {
	return preg_replace_callback(
		// The attributes end at the `}` followed by the comment close, so
		// nested objects such as {"style":{"spacing":{…}}} stay in one match.
		'/<!--\s+wp:[a-z0-9\/-]+\s+\{.*?\}\s+\/?-->/s',
		static function ( $matches ) {
			return preg_replace( '/(?<!\\\\)u002d/', '\\\\u002d', $matches[0] );
		},
		$content
	);
}
