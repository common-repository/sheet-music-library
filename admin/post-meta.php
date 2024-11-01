<?php
/**
 * Custom post meta handling for the Sheet Music post type.
 */

// Add the Piece Files Meta box, for the sheet music post type.
function sml_add_meta_box() {
	add_meta_box( 'sml_piece_files', __( 'Piece Files', 'sheet-music-library' ), 'sml_piece_files_meta_box', 'sheet_music', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'sml_add_meta_box' );

// Enqueue scripts & styles.
function sml_admin_scripts() {
    global $post_type, $post;
    if ( 'sheet_music' === $post_type ) {
    	// Enqueue scripts & styles.
		wp_enqueue_script( 'sheet-music-admin', plugins_url( '/sheet-music-admin.js', __FILE__), '', '', true );
    	wp_enqueue_style( 'sheet-music-admin', plugins_url( '/sheet-music-admin.css', __FILE__) );

		// Load data into JS, including translated strings.
		$sml_stored_meta = get_post_meta( $post->ID );
		if ( isset ( $sml_stored_meta['audio-attachment-id'] ) && 0 != absint( $sml_stored_meta['audio-attachment-id'] ) ) {
			$audio_attachment = wp_prepare_attachment_for_js( absint( $sml_stored_meta['audio-attachment-id'] ) );
		} else {
			$audio_attachment = false;
		}

		wp_localize_script( 'sheet-music-admin', 'sheetMusicOptions', array(
			'audioAttachment' => $audio_attachment,
			'l10n' => array(
				'select' => __( 'Select File', 'sheet-music-library' ),
				'scorePDF' => __( 'Score PDF', 'sheet-music-library' ),
				'partsPDF' => __( 'Parts PDF', 'sheet-music-library' ),
				'recordingAudio' => __( 'Recording Audio', 'sheet-music-library' ),
			),
		) );
	}
}
add_action( 'admin_print_scripts-post-new.php', 'sml_admin_scripts' );
add_action( 'admin_print_scripts-post.php', 'sml_admin_scripts' );

// Callback that renders the contents of the Piece Files meta box.
function sml_piece_files_meta_box( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'sml_nonce' );
	$sml_stored_meta = get_post_meta( $post->ID );

	if ( isset ( $sml_stored_meta['score-attachment-id'] ) && 0 != absint( $sml_stored_meta['score-attachment-id'] ) ) {
		$score_attachment_id = absint( $sml_stored_meta['score-attachment-id'][0] );
		$score = get_post( $score_attachment_id );
		if ( $score === null ) {
			$score_attachment_title = '';
		} else {
			$score_attachment_title = $score->post_title;
		}
		$score_attachment_link = wp_get_attachment_url( $score_attachment_id );
		$score_attachment_image = esc_url( get_post_meta( $score_attachment_id, 'pdf_thumbnail_url', true ) );
	} else {
		$score_attachment_id = '';
		$score_attachment_title = '';
		$score_attachment_link = '';
		$score_attachment_image = ''; // @todo default image?
	}

	if ( isset ( $sml_stored_meta['parts-attachment-id'] ) && 0 != absint( $sml_stored_meta['parts-attachment-id'] ) ) {
		$parts_attachment_id = absint( $sml_stored_meta['parts-attachment-id'][0] );
		$parts = get_post( $parts_attachment_id );
		if ( $parts === null ) {
			$parts_attachment_title = '';
		} else {
			$parts_attachment_title = $parts->post_title;
		}
		$parts_attachment_link = wp_get_attachment_url( $parts_attachment_id );
	} else {
		$parts_attachment_id = '';
		$parts_attachment_title = '';
		$parts_attachment_link = '';
	}

	if ( isset ( $sml_stored_meta['audio-attachment-id'] ) && 0 != absint( $sml_stored_meta['audio-attachment-id'] ) ) {
		$audio_attachment_id = absint( $sml_stored_meta['audio-attachment-id'][0] );
		$audio = get_post( $audio_attachment_id );
		if ( $audio === null ) {
			$audio_attachment_title = '';
		} else {
			$audio_attachment_title = $audio->post_title;
		}
		$audio_attachment = wp_prepare_attachment_for_js( $audio_attachment_id );
	} else {
		$audio_attachment_id = '';
		$audio_attachment_title = '';
		$audio_attachment = false;
	}

	if ( isset ( $sml_stored_meta['piece-video-url'] ) && esc_url( $sml_stored_meta['piece-video-url'][0] ) ) {
		$video_url = esc_url( $sml_stored_meta['piece-video-url'][0] );
	} else {
		$video_url = '';
	}

	if ( array_key_exists( 'no-download-message', $sml_stored_meta ) ) {
		$no_download_message = esc_html( $sml_stored_meta['no-download-message'][0] );
	} else {
		$no_download_message = '';
	}

	?>

	<img src="<?php echo $score_attachment_image; ?>" id="score-image" />

	<p id="score-attachment" class="piece-attachment<?php if ( ! $score_attachment_id ) { echo ' empty'; } ?>">
		<label for="score-upload" class="sml-row-title"><?php _e( 'Score PDF', 'sheet-music-library' )?></label>
		<span id="score-attachment-title"><?php echo $score_attachment_title; ?></span>
		<a id="score-attachment-link" class="button preview-link" href="<?php echo $score_attachment_link; ?>" target="_blank"><?php _e( 'Preview', 'sheet-music-library' ); ?></a>
		<button type="button" class="button button-secondary" id="score-upload"><span class="upload"><?php _e( 'Upload', 'sheet-music-library' ); ?></span><span class="change"><?php _e( 'Change', 'sheet-music-library' ); ?></span></button>
		<button type="button" class="button-link deletion" id="score-remove"><span class="change"><?php _e( 'Remove', 'sheet-music-library' ); ?></span></button>
		<input type="hidden" name="score-attachment-id" id="score-attachment-id" value="<?php echo $score_attachment_id; ?>" />
	</p>

	<p id="parts-attachment" class="piece-attachment<?php if ( ! $parts_attachment_id ) { echo ' empty'; } ?>">
		<label for="parts-upload" class="sml-row-title"><?php _e( 'Parts PDF', 'sheet-music-library' ); ?></label>
		<span id="parts-attachment-title"><?php echo $parts_attachment_title; ?></span>
		<a id="parts-attachment-link" class="button preview-link" href="<?php echo $parts_attachment_link; ?>" target="_blank"><?php _e( 'Preview', 'sheet-music-library' ); ?></a>
		<button type="button" class="button button-secondary" id="parts-upload"><span class="upload"><?php _e( 'Upload', 'sheet-music-library' ); ?></span><span class="change"><?php _e( 'Change', 'sheet-music-library' ); ?></span></button>
		<button type="button" class="button-link deletion" id="parts-remove"><span class="change"><?php _e( 'Remove', 'sheet-music-library' ); ?></span></button>
		<input type="hidden" name="parts-attachment-id" id="parts-attachment-id" value="<?php echo $parts_attachment_id; ?>" />
	</p>

	<p id="audio-attachment" class="piece-attachment<?php if ( ! $audio_attachment_id ) { echo ' empty'; } ?>">
		<label for="audio-upload" class="sml-row-title"><?php _e( 'Recording', 'sheet-music-library' ); ?></label>
		<span id="audio-attachment-title"><?php echo $audio_attachment_title; ?></span>
		<button type="button" class="button button-secondary" id="audio-upload"><span class="upload"><?php _e( 'Upload', 'sheet-music-library' ); ?></span><span class="change"><?php _e( 'Change', 'sheet-music-library' ); ?></span></button>
		<button type="button" class="button-link deletion" id="audio-remove"><span class="change"><?php _e( 'Remove', 'sheet-music-library' ); ?></span></button>
		<script type="text/javascript">var initialAudioAttachment = <?php echo wp_json_encode( $audio_attachment ); ?></script>
		<iframe id="audio-preview-container" seamless="seamless"></iframe>
		<input type="hidden" name="audio-attachment-id" id="audio-attachment-id" value="<?php echo $audio_attachment_id; ?>" />
	</p>

	<p class="piece-attachment">
		<label for="piece-video-url"><?php _e( 'YouTube or Vimeo Video URL', 'sheet-music-library' ); ?></label>
		<input type="url" name="piece-video-url" id="piece-video-url" value="<?php echo $video_url; ?>" />
	</p>
	<div id="video-embed-preview"></div>
	<br style="clear:both">
	<label for="no-download-message"><?php _e( 'Direct Download Unavailable Message', 'sheet-music-library' ); ?></label>
	<p style="font-style: italic;"><?php _e( 'Enter text in the area below to hide the PDF download buttons from public view and display an alternative message. This can be used if your library contains items that are not available freely and publicly, such as for copyright reasons.', 'sheet-music-library' ); ?></p>
	<textarea name="no-download-message" class="widefat"><?php echo $no_download_message; ?></textarea>
	<?php
}

/**
 * Save the custom fields on post save.
 */
function sml_post_meta_save( $post_id ) {
	// Bail if this isn't a valid time to save post meta.
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST[ 'sml_nonce' ] ) && wp_verify_nonce( $_POST[ 'sml_nonce' ], basename( __FILE__ ) ) ) ? true : false;
	if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
		return;
	}

	// Sanitize and save post meta.
	if ( isset( $_POST[ 'score-attachment-id' ] ) ) {
		update_post_meta( $post_id, 'score-attachment-id', absint( $_POST[ 'score-attachment-id' ] ) );
	}
	if ( isset( $_POST[ 'parts-attachment-id' ] ) ) {
		update_post_meta( $post_id, 'parts-attachment-id', absint( $_POST[ 'parts-attachment-id' ] ) );
	}
	if ( isset( $_POST[ 'audio-attachment-id' ] ) ) {
		update_post_meta( $post_id, 'audio-attachment-id', absint( $_POST[ 'audio-attachment-id' ] ) );
	}
	if ( isset( $_POST[ 'piece-video-url' ] ) ) {
		update_post_meta( $post_id, 'piece-video-url', esc_url( $_POST[ 'piece-video-url' ] ) );
	}
	if ( isset( $_POST[ 'no-download-message' ] ) ) {
		update_post_meta( $post_id, 'no-download-message', wp_kses( $_POST[ 'no-download-message' ], array(
		    'a' => array(
		        'href' => array(),
		        'title' => array(),
				'class' => array(),
				'target' => array(),
		    ),
		    'br' => array(),
		    'em' => array(),
		    'strong' => array(),
			'div' => array(
				'class' => array(),
				'id' => array(),
			)
		) ) );
	}
}
add_action( 'save_post', 'sml_post_meta_save' );

/**
 * When a PDF is uploaded, generate a thumbnail image and save it in the attachment meta.
 *
 * Implementation based off of similar handling in core for audio uploads - see `wp_generate_attachment_metadata()`.
 * Biggest difference is that ImageMagick, via WP_Image_Editor, is used to convert the first page of the uploaded
 * PDF to a PNG (potentily with transparency), which is then saved as its own attachment.
 *
 * Most likely cause for failure here is that Imagick/ImageMagick is not available. If pdf-image previews are a
 * must-have feature, a web host that supports Imagick is necessary.
 *
 * Note that WordPress core also creates image previews of PDFs as of version 4.7.0, inspired by this plugin's
 * original functionality here. These images are optimized for use within the wp-admin media library and use 
 * .jpg files. The plugin-generated .png files typically work better for sheet music; however, the core files
 * could eventually be reused instead. This would require back-generating the core image files for older PDFs.
 * See https://core.trac.wordpress.org/ticket/31050.
 *
 */
add_filter( 'wp_generate_attachment_metadata', 'sml_generate_pdf_thumbnail_metadata', 10, 2 );
function sml_generate_pdf_thumbnail_metadata( $metadata, $attachment_id ) {
	$attachment = get_post( $attachment_id );
	if ( 'application/pdf' === get_post_mime_type( $attachment ) ) {
		// Create a png from the pdf.
		$file = get_attached_file( $attachment_id );
		$pdf = wp_get_image_editor( $file );
		if ( ! is_wp_error( $pdf ) ) {
			$filename = $pdf->generate_filename( 'image', null, 'png' );
			$uploaded = $pdf->save( $filename, 'image/png' );
			if ( ! is_wp_error( $uploaded ) ) {
				$upload_dir = wp_upload_dir();
				update_post_meta( $attachment_id, 'pdf_thumbnail_url', $upload_dir['url'] . '/' . $uploaded['file'] );
			}
		}
	}
	return $metadata;
}
