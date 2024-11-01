<?php
/**
 * Template part components for sheet music library display.
 *
 * These functions are referenced both by blocks and by classic filters and theme template functions.
 *
 * Contents:
 *	sml_sheet_music_download_box( $score_url, $parts_url, $no_download_message );
 *  sml_sheet_music_last_updated_text();
 *	sml_sheet_music_download_box_current();
 *	sml_score_preview_current();
 *	sml_excerpt_button_current();
 *	sml_sheet_music_search_form();
 *	sml_sheet_music_audio_video_current();
 *
 * All functions in this file return the markup result.
 */


// Returns markup for the download buttons and terms.
function sml_sheet_music_download_box( $score_url, $parts_url, $no_download_message, $block_attributes = null ) {
	
	// Block wrapper element attributes if called as a block render callback, or empty string when called in other contexts.
	if ( null === $block_attributes ) {
		$wrapper_attributes = 'class="download-box"';
	} else {
		$wrapper_attributes = get_block_wrapper_attributes(['class' => 'download-box']);
	}

	ob_start();
	?><div <?php echo $wrapper_attributes; ?>>
		<?php 
	if ( '' === $score_url && '' === $parts_url && '' === $no_download_message ) {
		echo '<p class="piece-no-files">' . __( 'No sheet music files available for this piece.', 'sheet-music-library' ) . '</p>';
	} else {
		if ( $no_download_message ) {
			echo '<p class="piece-download-terms">' . $no_download_message . '</p>';
		} elseif ( ! $score_url && ! $parts_url ) {
			echo '<!-- No sheet music file attachments. -->';
		} else {
			if ( $score_url ) : ?>
				<a class="button" href="<?php echo $score_url; ?>" target="_blank"><?php _e( 'Download Score', 'sheet-music-library' ); ?></a>
			<?php endif;
			if ( $parts_url ) : ?>
				<a class="button" href="<?php echo $parts_url; ?>" target="_blank"><?php _e( 'Download Parts', 'sheet-music-library' ); ?></a>
			<?php endif; ?>
			<p class="piece-modified-text"><?php echo sml_sheet_music_last_updated_text( $score_url, $parts_url );?></p>
			<p class="piece-download-terms"><?php echo get_option( 'sml_terms', __( 'By downloading this music, you agree to the <a href="/terms/">Terms & Conditions</a>.', 'sheet-music-library' ) ); ?></p>
		<?php }
		} ?>
	</div>
	<?php

	$output = trim( ob_get_contents() );
	ob_end_clean();

	return $output;
}

// Returns markup for last updated text for sheet music PDFs. Displays the newer date of the score and parts attachment files.
// Intentionally doesn't show the post modified date, so that editorial revisions are not reflected in this date.
function sml_sheet_music_last_updated_text( $score_url = false, $parts_url = false ) {
	if ( ! $score_url ) {
		$score_attachment_id = absint( get_post_meta( get_the_ID(), 'score-attachment-id', true ) );
	} else {
		$score_attachment_id = absint( attachment_url_to_postid( $score_url ) );
	}
	// Handle empty attachment.
	if ( $score_attachment_id ) {
		$score_date = get_the_modified_date( 'U', $score_attachment_id );
	} else {
		$score_date = 0;
	}

	if ( ! $parts_url ) {
		$parts_attachment_id = absint( get_post_meta( get_the_ID(), 'parts-attachment-id', true ) );
	} else {
		$parts_attachment_id = absint( attachment_url_to_postid( $parts_url ) );
	}
	// Handle empty attachment.
	if ( $parts_attachment_id ) {
		$parts_date = get_the_modified_date( 'U', $parts_attachment_id );	
	} else {
		$parts_date = 0;
	}

	// Ensure that the modified date is not before the piece publish date.
	$post_publish = absint( get_the_date( 'U', get_the_ID() ) );

	// Report the more recent attachment modified date. Note this is not necessarily the file-modified date, but any other attachment modify triggers are unlikely.
	// Falls back to the post published date if both attachments are missing.
	if ( $post_publish > $score_date && $post_publish > $parts_date ) {
		$date = get_the_date( 'F j, Y', get_the_id() );
	} elseif ( $score_date > $parts_date ) {
		$date = get_the_modified_date( 'F j, Y', $score_attachment_id );
	} else {
		$date = get_the_modified_date( 'F j, Y', $parts_attachment_id );
	}

	return sprintf( __( 'Last modified: %1$s', 'sheet-music-library'), $date );
}

// Returns markup for the download buttons and terms for the current post.
// Block attributes is provided when this is run as a block render_callback.
function sml_sheet_music_download_box_current( $block_attributes = null ) {

	// Get post meta fields' data.
	$score_attachment_id = absint( get_post_meta( get_the_ID(), 'score-attachment-id', true ) );
	$score_url = ( $score_attachment_id ) ? wp_get_attachment_url( $score_attachment_id ) : '';
	$parts_attachment_id = absint( get_post_meta( get_the_ID(), 'parts-attachment-id', true ) );
	$parts_url = ( $parts_attachment_id ) ? wp_get_attachment_url( $parts_attachment_id ) : '';
	$no_download_message = get_post_meta( get_the_ID(), 'no-download-message', true );
	$no_download_message = wp_kses( $no_download_message, array(
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
	) );

	return sml_sheet_music_download_box( $score_url, $parts_url, $no_download_message, $block_attributes );
}


// Returns markup for a linked preview image for the current sheet music piece.
function sml_score_preview_current( $block_attributes = null ) {

	// Block wrapper element attributes if called as a block render callback, or empty string when called in other contexts.
	if ( null === $block_attributes ) {
		$wrapper_attributes = 'class="piece-preview"';
	} else {
		$wrapper_attributes = get_block_wrapper_attributes(['class' => 'piece-preview']);
	}

	// Get post meta fields' data.
	$score_attachment_id = absint( get_post_meta( get_the_ID(), 'score-attachment-id', true ) );
	$score_url = ( $score_attachment_id ) ? wp_get_attachment_url( $score_attachment_id ) : '';
	$image_url = '';
	if ( $score_attachment_id ) {
		$image_url = esc_url( get_post_meta( $score_attachment_id, 'pdf_thumbnail_url', true ) );
	}

	$no_download_message = get_post_meta( get_the_ID(), 'no-download-message', true );

	ob_start();

	if ( $image_url && ! $no_download_message ) { ?>
		<div <?php echo $wrapper_attributes; ?>>
			<a href="<?php echo $score_url; ?>" target="_blank"><img class="score-preview" src="<?php echo $image_url; ?>" alt="score preview"/></a>
		</div>
	<?php }
		// Display placeholder in the block editor if no image is available.
		// Dynamic blocks in the editor are rendered through REST API, frontend blocks are called directly.
		elseif ( defined( 'REST_REQUEST' ) && REST_REQUEST ) { ?>
		<div <?php echo $wrapper_attributes; ?>>
			<div class="score-preview placeholder">
				<?php 
				if ( $no_download_message ) {
					_e( 'No image will be displayed because direct download is disabled for this piece' );
				} else {
					_e( 'Upload a score PDF to display a score preview image.', 'sheet-music-library' );
				} ?>
			</div>
		</div>
	<?php }

	$output = trim( ob_get_contents() );
	ob_end_clean();

	return $output;
}


// Returns markup for a button-link to a single piece from an archive excerpt view.
function sml_excerpt_button_current( $block_attributes = null ) {

	// Block wrapper element attributes if called as a block render callback, or empty string when called in other contexts.
	if ( null === $block_attributes ) {
		$wrapper_attributes = 'class="download-box"';
	} else {
		$wrapper_attributes = get_block_wrapper_attributes(['class' => 'download-box']);
	}

	// Get post meta fields' data.
	$no_download_message = get_post_meta( get_the_ID(), 'no-download-message', true );
	$button_text = ( $no_download_message ) ? __( 'View Piece &rarr;', 'sheet-music-library' ) : __( 'View & Download &rarr;', 'sheet-music-library' );

	ob_start();
	?>

	<div <?php echo $wrapper_attributes; ?>>
		<a class="button" href="<?php the_permalink(); ?>"><?php echo $button_text; ?></a>
	</div>
	<?php

	$output = trim( ob_get_contents() );
	ob_end_clean();

	return $output;
}


// Returns markup for a search form based on the html5 version of `get_search_form()`, scoped to the sheet music post type.
function sml_sheet_music_search_form( $block_attributes = null ) {

	// Block wrapper element attributes if called as a block render callback, or empty string when called in other contexts.
	if ( null === $block_attributes ) {
		$wrapper_attributes = '';
	} else {
		$wrapper_attributes = get_block_wrapper_attributes();
	}

	ob_start();
	?>
	<div <?php echo $wrapper_attributes; ?>>
		<form role="search" method="get" class="search-form sheet-music-search-form" action="<?php echo home_url( '/' ); ?>">
			<label>
				<span class="screen-reader-text"><?php echo _x( 'Search sheet music for:', 'label', 'sheet-music-library' ) ?></span>
				<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search sheet music…', 'form placeholder', 'sheet-music-library' ) ?>" value="<?php echo get_search_query() ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', 'sheet-music-library' ) ?>" />
			</label>
			<input type="hidden" name="post_type" value="sheet_music" />
			<input type="submit" class="search-submit button" value="<?php echo esc_attr_x( 'Search', 'submit button', 'sheet-music-library' ) ?>" />
		</form>
	</div>
	<?php
	$output = trim( ob_get_contents() );
	ob_end_clean();

	return $output;
}

// Returns markup for audio and/or video players for the current sheet music post.
function sml_sheet_music_audio_video_current( $block_attributes = null ) {

	// Block wrapper element attributes if called as a block render callback, or empty string when called in other contexts.
	if ( null === $block_attributes ) {
		$wrapper_attributes = 'class="piece-recording"';
	} else {
		$wrapper_attributes = get_block_wrapper_attributes(['class' => 'piece-recording']);
	}

	$audio_attachment_id = absint( get_post_meta( get_the_ID(), 'audio-attachment-id', true ) );
	$audio_url = ( $audio_attachment_id ) ? wp_get_attachment_url( $audio_attachment_id ) : '';
	$video_url = esc_url( get_post_meta( get_the_ID(), 'piece-video-url', true ) );

	// Hide audio/video as applicable based on block settings.
	if ( is_array( $block_attributes ) ) {
		if ( false === $block_attributes['audio'] ) {
			$audio_url = '';
		}
		if ( false === $block_attributes['video'] ) {
			$video_url = '';
		}
	}
	
	ob_start();
	?>
	<div <?php echo $wrapper_attributes; ?>><?php
		if ( $audio_url ) {
			// Embedded audio player.
			echo wp_audio_shortcode( array( 'src' => $audio_url ) );
		}
		if ( $video_url ) {
			// Skinned embedded YouTube player.
			echo wp_video_shortcode( array( 'src' => $video_url ) );
		}

		// Display placeholder in the block editor if no media is available.
		// Dynamic blocks in the editor are rendered through REST API, frontend blocks are called directly.
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST && ! $audio_url && ! $video_url ) { ?>
			<p class="audio-video placeholder">
				<?php _e( 'Piece audio / video players.', 'sheet-music-library' ); ?>
			</p>
	<?php } ?></div>

	<?php
	$output = trim( ob_get_contents() );
	ob_end_clean();

	return $output;
}
