<?php
// Import Pato documentation images into the colorlib.com media library.
// Idempotent: a file already in the library only gets its alt text refreshed.
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

global $wpdb;
$dir      = '/tmp/pato-docs';
$manifest = json_decode( file_get_contents( $dir . '/manifest.json' ), true );

foreach ( $manifest as $file => $alt ) {
	$src = $dir . '/' . $file;
	if ( ! file_exists( $src ) ) {
		echo "not uploaded yet  $file\n";
		continue;
	}
	$existing = (int) $wpdb->get_var(
		$wpdb->prepare(
			// Exact name OR a month folder in front of it. colorlib.com stores uploads
			// with no month folder, so a LIKE '%/name' alone never matched there and a
			// rerun imported all 20 images a second time as name-1.png.
			"SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file' AND ( meta_value = %s OR meta_value LIKE %s ) ORDER BY post_id ASC LIMIT 1",
			$file,
			'%/' . $wpdb->esc_like( $file )
		)
	);
	if ( $existing ) {
		update_post_meta( $existing, '_wp_attachment_image_alt', $alt );
		echo "exists   $existing  $file\n";
		continue;
	}
	$tmp = wp_tempnam( $file );
	copy( $src, $tmp );
	$title = 'Pato documentation: ' . str_replace( '-', ' ', preg_replace( '/^pato-docs-|\.[a-z]+$/', '', $file ) );
	$id    = media_handle_sideload( array( 'name' => $file, 'tmp_name' => $tmp ), 0, null, array( 'post_title' => $title ) );
	if ( is_wp_error( $id ) ) {
		@unlink( $tmp );
		echo "ERROR    $file: " . $id->get_error_message() . "\n";
		continue;
	}
	update_post_meta( $id, '_wp_attachment_image_alt', $alt );
	echo "imported $id  $file  " . wp_get_attachment_url( $id ) . "\n";
}
