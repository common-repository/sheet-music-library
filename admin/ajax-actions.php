<?php
/**
 * Ajax actions for the Sheet Music Library plugin.
 */

/**
 * Renders the url for a score PDF attachment's thumbnail image.
 *
 * Can be used outside of the admin context in add-on plugins or
 * custom themes, so nonces are not used/checked here.
 */
function sml_get_score_image_url_ajax() {
	$attachment_id = absint( $_POST['attachment_ID'] );
	echo esc_url( get_post_meta( $attachment_id, 'pdf_thumbnail_url', true ) );

	wp_die();
}
add_action( 'wp_ajax_sml-get-score-image-url', 'sml_get_score_image_url_ajax');
