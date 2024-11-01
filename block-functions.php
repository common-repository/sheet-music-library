<?php
/**
 * Register block functionality for the Sheet Music Library plugin.
 */


// Add Sheet Music Library block category.
function sml_filter_block_categories_when_post_provided( $block_categories, $editor_context ) {
	if ( ! empty( $editor_context->post ) ) {
		array_push(
			$block_categories,
				array(
					'slug'  => 'sheet-music-library',
					'title' => __( 'Sheet Music Library', 'sheet-music-library' ),
					'icon'  => 'dashicons-format-audio',
				)
		);
	}
	return $block_categories;
}
add_filter( 'block_categories_all', 'sml_filter_block_categories_when_post_provided', 10, 2 );


// Register sheet music library blocks.
// Note that styles & scripts are registered through block json and adjacent assets files.
add_action( 'init', 'sml_register_blocks' );
function sml_register_blocks() {

	// Register sheet music search form block.
	register_block_type( plugin_dir_path( __FILE__ ) . 'blocks/sml-search/block.json', array(
		'render_callback' => 'sml_sheet_music_search_form',
	) );

	// Register sheet music download box block.
	register_block_type( plugin_dir_path( __FILE__ ) . 'blocks/sml-download-box/block.json', array(
		'render_callback' => 'sml_sheet_music_download_box_current',
	) );

	// Register sheet music score preview block.
	register_block_type( plugin_dir_path( __FILE__ ) . 'blocks/sml-score-preview/block.json', array(
		'render_callback' => 'sml_score_preview_current',
	) );

	// Register sheet music excerpt button block.
	register_block_type( plugin_dir_path( __FILE__ ) . 'blocks/sml-excerpt-button/block.json', array(
		'render_callback' => 'sml_excerpt_button_current',
	) );

	// Register sheet music audio/video block.
	register_block_type( plugin_dir_path( __FILE__ ) . 'blocks/sml-audio-video/block.json', array(
		'render_callback' => 'sml_sheet_music_audio_video_current',
	) );
}

// Force the block editor to enqueue the wp-mediaelement styles. The mediaelement script is enqueued when required within individual blocks.
// See SML Audio/Video block for an example of where this is required.
// Another option could be to refactor that block to use custom styles instead of the mediaelement skin (especially for audio).
add_action( 'enqueue_block_editor_assets', 'sml_enqueue_block_editor_styles' );
function sml_enqueue_block_editor_styles() {
	wp_enqueue_style( 'wp-mediaelement' );
}

// Register block patterns.
add_action( 'init', 'sml_register_block_patterns' );
function sml_register_block_patterns() {

	// Register the Sheet Music Library block pattern category.
	register_block_pattern_category( 'sheet-music-library', array(
		'label' => __( 'Sheet Music Library', 'sheet-music-library' ),
	) );

	// Register a playlist pattern for improved UX until core supports a playlist block to replace the shortcode.
	register_block_pattern( 'sheet-music-library/playlist', array(
		'title' => __( 'Audio Playlist (SML)', 'sheet-music-library' ),
		'description' => __(  'Audio playlist shortcode placeholder to simplify the workflow of inserting a classic block and then adding a playlist through the media modal. Playlist content is managed in the media model through the process introduced in WordPress 3.6.', 'sheet-music-library' ),
		'categories' => array( 'sheet-music-library' ),
		'content' => '[playlist]'
	) );

	// Register sheet music terms pattern. Uses core post term blocks in GB 13.3+ / WP 6.1+.
	register_block_pattern( 'sheet-music-library/piece-terms', array(
		'title' => __( 'Piece Terms', 'sheet-music-library' ),
		'description' => __(  'Horizontal list of taxonomy terms assigned to the current sheet music piece (Composer / Parts / Difficulty / Genere).', 'sheet-music-library' ),
		'categories' => array( 'sheet-music-library' ),
		'content' => '
<!-- wp:group {"className":"taxonomy-box","layout":{"type":"flex","flexWrap":"wrap"}} -->
	<div class="wp-block-group taxonomy-box">
		<!-- wp:post-terms {"term":"composer","prefix":"' . _x( 'Composer: ', 'block pattern term list prefix', 'sheet-music-library' ) . '","suffix":" /"} /-->
		<!-- wp:post-terms {"term":"orchestration","prefix":"' . _x( 'Parts: ', 'block pattern term list prefix', 'sheet-music-library' ) . '","suffix":" /"} /-->
		<!-- wp:post-terms {"term":"difficulty","prefix":"' . _x( 'Difficulty: ', 'block pattern term list prefix', 'sheet-music-library' ) . '","suffix":" /"} /-->
		<!-- wp:post-terms {"term":"genre","prefix":"' . _x( 'Genre: ', 'block pattern term list prefix', 'sheet-music-library' ) . '"} /-->
	</div>
<!-- /wp:group -->'

	) );

	// Register sheet music piece meta box pattern. Uses core post term blocks in GB 13.3+ / WP 6.1+.
	register_block_pattern( 'sheet-music-library/piece-meta-box', array(
		'title' => __( 'Piece Meta Box', 'sheet-music-library' ),
		'description' => __(  'Download buttons and vertical list of taxonomy terms assigned to the current sheet music piece (Composer / Parts / Difficulty / Genere).', 'sheet-music-library' ),
		'categories' => array( 'sheet-music-library' ),
		'content' => '
<!-- wp:group {"className":"piece-meta wp-caption alignright"} -->
	<div class="wp-block-group piece-meta wp-caption alignright">
		<!-- wp:sheet-music-library/download-box /-->
		<!-- wp:group {"className":"taxonomy-box","layout":{"type":"flex","orientation":"vertical"}} -->
			<div class="wp-block-group taxonomy-box">
			<!-- wp:post-terms {"term":"composer","prefix":"' . _x( 'Composer: ', 'block pattern term list prefix', 'sheet-music-library' ) . '"} /-->
			<!-- wp:post-terms {"term":"orchestration","prefix":"' . _x( 'Parts: ', 'block pattern term list prefix', 'sheet-music-library' ) . '"} /-->
			<!-- wp:post-terms {"term":"difficulty","prefix":"' . _x( 'Difficulty: ', 'block pattern term list prefix', 'sheet-music-library' ) . '"} /-->
			<!-- wp:post-terms {"term":"genre","prefix":"' . _x( 'Genre: ', 'block pattern term list prefix', 'sheet-music-library' ) . '"} /-->
			</div>
		<!-- /wp:group -->
	</div>
<!-- /wp:group -->'

	) );


	// Register sheet music archive query pattern. Uses core post term blocks in GB 13.3+ / WP 6.1+.
	register_block_pattern( 'sheet-music-library/archive-query-template', array(
		'title' => __( 'Sheet Music Archive View', 'sheet-music-library' ),
		'description' => __(  'Default sheet music piece archive view with piece taxonomies and audio/video players.', 'sheet-music-library' ),
		'categories' => array( 'query', 'sheet-music-library' ),
		'content' => '
		<!-- wp:group {"className":"taxonomy-box","layout":{"type":"flex","flexWrap":"wrap"}} -->
			<div class="wp-block-group taxonomy-box">
				<!-- wp:post-terms {"term":"composer","prefix":"' . _x( 'Composer: ', 'block pattern term list prefix', 'sheet-music-library' ) . '","suffix":" /"} /-->
				<!-- wp:post-terms {"term":"orchestration","prefix":"' . _x( 'Parts: ', 'block pattern term list prefix', 'sheet-music-library' ) . '","suffix":" /"} /-->
				<!-- wp:post-terms {"term":"difficulty","prefix":"' . _x( 'Difficulty: ', 'block pattern term list prefix', 'sheet-music-library' ) . '","suffix":" /"} /-->
				<!-- wp:post-terms {"term":"genre","prefix":"' . _x( 'Genre: ', 'block pattern term list prefix', 'sheet-music-library' ) . '"} /-->
			</div>
		<!-- /wp:group -->
	<!-- wp:sheet-music-library/audio-video /-->
	<!-- wp:sheet-music-library/excerpt-button /-->'

	) );


	// Register sheet music single piece query pattern. Uses core post term blocks in GB 13.3+ / WP 6.1+.
	register_block_pattern( 'sheet-music-library/single-piece-template', array(
		'title' => __( 'Sheet Music Single Piece', 'sheet-music-library' ),
		'description' => __(  'Default sheet music piece archive view with piece taxonomies and audio/video players.', 'sheet-music-library' ),
		'categories' => array( 'query', 'sheet-music-library' ),
		'content' => '
<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group piece">
	<!-- wp:sheet-music-library/score-preview /-->
	<!-- wp:group {"className":"piece-meta wp-caption"} -->
	<div class="wp-block-group piece-meta wp-caption">
		<!-- wp:sheet-music-library/download-box /-->
		<!-- wp:group {"className":"taxonomy-box","layout":{"type":"flex","orientation":"vertical"}} -->
			<div class="wp-block-group taxonomy-box">
			<!-- wp:post-terms {"term":"composer","prefix":"' . _x( 'Composer: ', 'block pattern term list prefix', 'sheet-music-library' ) . '"} /-->
			<!-- wp:post-terms {"term":"orchestration","prefix":"' . _x( 'Parts: ', 'block pattern term list prefix', 'sheet-music-library' ) . '"} /-->
			<!-- wp:post-terms {"term":"difficulty","prefix":"' . _x( 'Difficulty: ', 'block pattern term list prefix', 'sheet-music-library' ) . '"} /-->
			<!-- wp:post-terms {"term":"genre","prefix":"' . _x( 'Genre: ', 'block pattern term list prefix', 'sheet-music-library' ) . '"} /-->
			</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
<!-- wp:post-content /-->
<!-- wp:sheet-music-library/audio-video /-->
<!-- wp:sheet-music-library/download-box /-->'
	) );


	// Register sheet music condensed query loop pattern. Uses core post term blocks in GB 13.3+ / WP 6.1+.
	register_block_pattern( 'sheet-music-library/query-loop-condensed-template', array(
		'title' => __( 'Sheet Music Condensed Query Loop', 'sheet-music-library' ),
		'description' => __(  'Query loop with condensed sheet music listing with condensed audio players.', 'sheet-music-library' ),
		'categories' => array( 'sheet-music-library' ),
		'content' => '
<!-- wp:group {"style":{"layout":{"selfStretch":"fixed","flexSize":"360px"}},"className":"sheet-music-query-group-compact","layout":{"type":"constrained"}} -->
<div class="wp-block-group sheet-music-query-group-compact">
	<!-- wp:heading --><h2 class="wp-block-heading">' . __( 'All Sheet Music', 'sheet-music-library' ) . '</h2><!-- /wp:heading -->

	<!-- wp:query {"query":{"perPage":3,"pages":0,"offset":0,"postType":"sheet_music","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"displayLayout":{"type":"list","columns":3}} -->
	<div class="wp-block-query"><!-- wp:post-template -->
		<!-- wp:post-title {"level":3,"isLink":true} /-->
		<!-- wp:sheet-music-library/audio-video {"video":false,"className":"condensed-audio"} /-->

		<!-- wp:group {"className":"taxonomy-box","layout":{"type":"flex","orientation":"vertical"}} -->
		<div class="wp-block-group taxonomy-box">
				<!-- wp:post-terms {"term":"composer","prefix":"' . _x( 'By ', 'block pattern composer term list prefix', 'sheet-music-library' ) . '","suffix":" "} /-->
				<!-- wp:post-terms {"term":"orchestration","prefix":"' . _x( 'For ', 'block pattern orchestration list prefix', 'sheet-music-library' ) . '","suffix":" "} /--></div>
		<!-- /wp:group -->
	<!-- /wp:post-template --></div>
	<!-- /wp:query -->

	<!-- wp:buttons -->
	<div class="wp-block-buttons"><!-- wp:button -->
	<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">' . __( 'All Sheet Music &rarr;', 'sheet-music-library' ) . '</a></div>
	<!-- /wp:button --></div>
	<!-- /wp:buttons --></div>
<!-- /wp:group -->'
	) );


	// Register sheet music query loop pattern. Uses core post term blocks in GB 13.3+ / WP 6.1+.
	register_block_pattern( 'sheet-music-library/query-loop-standard-template', array(
		'title' => __( 'Sheet Music Standard Query Loop', 'sheet-music-library' ),
		'description' => __(  'Query loop with default sheet music piece archive view with piece taxonomies and audio/video players.', 'sheet-music-library' ),
		'categories' => array( 'query', 'sheet-music-library' ),
		'content' => '
<!-- wp:query {"query":{"perPage":10,"pages":0,"offset":0,"postType":"sheet_music","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"displayLayout":{"type":"list","columns":3}} -->
	<div class="wp-block-query"><!-- wp:post-template -->
		<!-- wp:post-title /-->
		<!-- wp:group {"className":"taxonomy-box","layout":{"type":"flex","flexWrap":"wrap"}} -->
			<div class="wp-block-group taxonomy-box">
				<!-- wp:post-terms {"term":"composer","prefix":"' . _x( 'Composer: ', 'block pattern term list prefix', 'sheet-music-library' ) . '","suffix":" /"} /-->
				<!-- wp:post-terms {"term":"orchestration","prefix":"' . _x( 'Parts: ', 'block pattern term list prefix', 'sheet-music-library' ) . '","suffix":" /"} /-->
				<!-- wp:post-terms {"term":"difficulty","prefix":"' . _x( 'Difficulty: ', 'block pattern term list prefix', 'sheet-music-library' ) . '","suffix":" /"} /-->
				<!-- wp:post-terms {"term":"genre","prefix":"' . _x( 'Genre: ', 'block pattern term list prefix', 'sheet-music-library' ) . '"} /-->
			</div>
		<!-- /wp:group -->
		<!-- wp:sheet-music-library/audio-video /-->
		<!-- wp:sheet-music-library/excerpt-button /-->
	<!-- /wp:post-template --></div>
<!-- /wp:query -->'
	) );
}
