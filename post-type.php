<?php
/* 
 * Sheet music custom post type.
 */

//Set up custom post types for sheet music
add_action( 'init', 'sml_posts_register', 10) ;
function sml_posts_register() {
	$labels = array(
		'name' => _x( 'Sheet Music', 'post type general name', 'sheet-music-library' ),
		'singular_name' => _x( 'Sheet Music', 'post type singular name', 'sheet-music-library' ),
		'add_new' => __( 'Add New Piece', 'sheet-music-library' ),
		'add_new_item' => __( 'Add New Piece', 'sheet-music-library' ),
		'edit_item' => __( 'Edit Piece', 'sheet-music-library' ),
		'new_item' => __( 'New Piece', 'sheet-music-library' ),
		'view_item' => __( 'View Piece Page', 'sheet-music-library' ),
		'search_items' => __( 'Search Sheet Music', 'sheet-music-library' ),
		'not_found' =>  __( 'Nothing found', 'sheet-music-library' ),
		'not_found_in_trash' => __( 'Nothing found in trash', 'sheet-music-library' ),
	);

	$supports = array(
		'title',
		'editor',
		'thumbnail',
		'excerpt',
		'comments',
		'revisions',
	);

	/**
	 * Filter the slug and query_var parameters of the sheet_music post type.
	 *
	 * @since Sheet Music Library 0.0
	 */
	$slug = apply_filters( 'sml_slug', 'piece' );

	$rewrite = array(
		'slug'       => $slug,
		'with_front' => false,
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_rest'       => true, // Enables the block editor.
		'menu_position'      => 5,
		'menu_icon'          => 'dashicons-format-audio',
		'supports'           => $supports,
		'hierarchical'       => false,
		'has_archive'        => true,
		'rewrite'            => $rewrite,
		'query_var'          => $slug,
		'capability_type'    => 'post',
	);

	register_post_type( 'sheet_music' , $args );
}

// Flush permalinks on activation.
register_activation_hook( __FILE__, 'sml_post_type_activation' );
function sml_post_type_activation() {
	sml_posts_register();
	flush_rewrite_rules();
}