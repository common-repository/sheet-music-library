<?php
/**
 * Widget that displays a playlist of audio featured on the current page for non-singular pages.
 *
 * Based on a similar widget in the Featured Audio plugin: https://wordpress.org/plugins/featured-audio/
*/

// Register 'Sheet Music Library Playlist' widget.
function sheet_music_library_widget_init() {
	return register_widget( 'Sheet_Music_Library_Playlist_Widget' );
}
add_action( 'widgets_init', 'sheet_music_library_widget_init' );

class Sheet_Music_Library_Playlist_Widget extends WP_Widget {
	/* Constructor */
	function __construct() {
		parent::__construct( 'Sheet_Music_Library_Playlist_Widget', __( 'Sheet Music Audio Playlist', 'sheet-music-library' ), array(
			'description' => __( 'A playlist of sheet music audio featured on the current page.', 'sheet-music-library' ),
			'customize_selective_refresh' => false,
		) );
	}

	/* This is the Widget */
	function widget( $args, $instance ) {
		global $post;

		if ( is_singular() ) {
			return;
		}

		extract( $args );

		$playlist = get_the_sheet_music_audio_playlist();
		if ( '' === $playlist ) {
			return;
		}

		if ( ! array_key_exists( 'title', $instance ) ) {
			$instance['title'] = '';
		}

		// Widget options
		$title = apply_filters( 'widget_title', $instance['title'] ); // Title

		// Output
		echo $before_widget;

		if ( $title ) {
			echo $before_title . $title . $after_title;
		}

		echo $playlist;

		echo $after_widget;
	}

	/* Widget control update */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}

	/* Widget settings */
	function form( $instance ) {
	    if ( $instance ) {
			$title = $instance['title'];
	    } else {
			$title = '';
	    }

		// The widget form. ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo __( 'Title:', 'sheet-music-library' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" class="widefat" />
		</p>
		<p><?php _e( 'Note: this widget is contextual. It will only be displayed on pages with sheet music posts that have audio, such as the sheet music post type archive, searches, or a category (genre / composer / orchestration / difficulty) view. It will not display on pages that rely on a sheet music shortcode.', 'sheet-music-library' ); ?></p>
	<?php
	}
}


/**
 * Get the sheet music audio playlist, if there are multiple posts with sheet music audio in the current query.
 * Also, try to magically locate audio from any other post types in the current query (such as for the featured audio plugin, in a search query).
 *
 * @return string Markup of the sheet music audio playlist, or empty string.
 */
function get_the_sheet_music_audio_playlist() {
	if ( have_posts() && ! is_singular() ) {
		rewind_posts(); // Reset the main query.
		$ids = array();
		while ( have_posts() ) : the_post();
			$audio_id =  absint( get_post_meta( get_the_ID(), 'audio-attachment-id', true ) ); // Sheet music library plugin
			if ( ! $audio_id ) {
				$audio_id =  absint( get_post_meta( get_the_ID(), 'featured-audio', true ) ); // Featured audio plugin (all post types)
			}
			if ( $audio_id ) {
				$ids[] = $audio_id;
			}
		endwhile;
		rewind_posts(); // Reset the main query, in case it's used after this function.

		if ( empty( $ids ) ) {
			return '';
		} else {
			return wp_playlist_shortcode( array( 'type' => 'audio', 'ids' => $ids, 'style' => 'dark' ) );
		}
	}
}
