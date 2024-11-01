<?php
/**
 * Register taxonomies for use with the sheet_music post type.
 */

add_action( 'init', 'sml_register_taxonomies', 11 );
function sml_register_taxonomies() {
	register_taxonomy( 'composer', 'sheet_music', array(
		'hierarchical'      => false, 
		'labels'            => array(
			'name'                  => __( 'Composer', 'sheet-music-library' ),
			'singular_name'         => __( 'Composer', 'sheet-music-library' ),
			'all_items'             => __( 'All Composers', 'sheet-music-library' ),
			'edit_item'             => __( 'Edit Composer', 'sheet-music-library' ),
			'update_item'           => __( 'Update Composer', 'sheet-music-library' ),
			'add_new_item'          => __( 'Add New Composer', 'sheet-music-library' ),
			'new_item_name'         => __( 'New Composer Name', 'sheet-music-library' ),
			'search_items'          => __( 'Search Composers', 'sheet-music-library' ),
			'popular_items'         => __( 'Popular Composers', 'sheet-music-library' ),
			'separate_items_with_commas' => __( 'Separate multiple composers with commas', 'sheet-music-library' ),
			'choose_from_most_used' => __( 'Choose from most-used composers', 'sheet-music-library'),
		),
		'rewrite'           => array(
			'slug'         => 'composer',
			'hierarchical' => true,
			'with_front'   => false,
		),
		'show_admin_column' => true,
		'show_in_rest' => true,
	) );

	register_taxonomy( 'orchestration', 'sheet_music', array(
		'hierarchical'      => false, 
		'labels'            => array(
			'name'                  => __( 'Orchestration', 'sheet-music-library' ),
			'singular_name'         => __( 'Orchestration', 'sheet-music-library' ),
			'all_items'             => __( 'All Orchestrations', 'sheet-music-library' ),
			'edit_item'             => __( 'Edit Orchestration', 'sheet-music-library' ),
			'update_item'           => __( 'Update Orchestration', 'sheet-music-library' ),
			'add_new_item'          => __( 'Add New Orchestration', 'sheet-music-library' ),
			'new_item_name'         => __( 'New Orchestration Name', 'sheet-music-library' ),
			'parent_item'           => __( 'Parent Orchestration', 'sheet-music-library' ),
			'parent_item_colon'     => __( 'Parent Orchestration:', 'sheet-music-library' ),
			'search_items'          => __( 'Search Orchestrations', 'sheet-music-library' ),
			'popular_items'         => __( 'Popular Orchestrations', 'sheet-music-library' ),
			'separate_items_with_commas' => __( 'Separate multiple instruments with commas', 'sheet-music-library' ),
			'choose_from_most_used' => __( 'Choose from most-used instruments', 'sheet-music-library' ),
		),
		'rewrite'           => array(
			'slug'         => 'orchestration',
			'hierarchical' => true,
			'with_front'   => false,
		),
		'show_admin_column' => true,
		'show_in_rest' => true,
	) );

	register_taxonomy( 'difficulty', 'sheet_music', array(
		'hierarchical'      => true,
		'labels'            => array(
			'name'                  => __( 'Difficulties', 'sheet-music-library' ),
			'singular_name'         => __( 'Difficulty', 'sheet-music-library' ),
			'all_items'             => __( 'All Difficulties', 'sheet-music-library' ),
			'edit_item'             => __( 'Edit Difficulty', 'sheet-music-library' ),
			'update_item'           => __( 'Update Difficulty', 'sheet-music-library' ),
			'add_new_item'          => __( 'Add New Difficulty', 'sheet-music-library' ),
			'new_item_name'         => __( 'New Difficulty Name', 'sheet-music-library' ),
			'parent_item'           => __( 'Parent Difficulty', 'sheet-music-library' ),
			'parent_item_colon'     => __( 'Parent Difficulty:', 'sheet-music-library' ),
			'search_items'          => __( 'Search Difficulties', 'sheet-music-library' ),
			'popular_items'         => __( 'Popular Difficulties', 'sheet-music-library' ),
			'choose_from_most_used' => __( 'Choose from most-used difficulties', 'sheet-music-library' ),
		),
		'rewrite'           => array(
			'slug'         => 'difficulty',
			'hierarchical' => true,
			'with_front'   => false,
		),
		'show_admin_column' => true,
		'show_in_rest' => true,
	) );

	register_taxonomy( 'genre', 'sheet_music', array(
		'hierarchical'      => true,
		'labels'            => array(
			'name'                  => __( 'Genres', 'sheet-music-library' ),
			'singular_name'         => __( 'Genre', 'sheet-music-library' ),
			'all_items'             => __( 'All Genres', 'sheet-music-library' ),
			'edit_item'             => __( 'Edit Genre', 'sheet-music-library' ),
			'update_item'           => __( 'Update Genre', 'sheet-music-library' ),
			'add_new_item'          => __( 'Add New Genre', 'sheet-music-library' ),
			'new_item_name'         => __( 'New Genre Name', 'sheet-music-library' ),
			'parent_item'           => __( 'Parent Genre', 'sheet-music-library' ),
			'parent_item_colon'     => __( 'Parent Genre:', 'sheet-music-library' ),
			'search_items'          => __( 'Search Genres', 'sheet-music-library' ),
			'popular_items'         => __( 'Popular Genres', 'sheet-music-library' ),
			'choose_from_most_used' => __( 'Choose from most-used genres', 'sheet-music-library' ),
		),
		'rewrite'           => array(
			'slug'         => 'genre',
			'hierarchical' => true,
			'with_front'   => false,
		),
		'show_admin_column' => true,
		'show_in_rest' => true,
	) );

	register_taxonomy( 'sml_collection', 'sheet_music', array(
		'hierarchical'      => false, 
		'labels'            => array(
			'name'                  => __( 'Collections', 'sheet-music-library' ),
			'singular_name'         => __( 'Collection', 'sheet-music-library' ),
			'all_items'             => __( 'All Collections', 'sheet-music-library' ),
			'edit_item'             => __( 'Edit Collection', 'sheet-music-library' ),
			'update_item'           => __( 'Update Collection', 'sheet-music-library' ),
			'add_new_item'          => __( 'Add New Collection', 'sheet-music-library' ),
			'new_item_name'         => __( 'New Collection Name', 'sheet-music-library' ),
			'parent_item'           => __( 'Parent Collection', 'sheet-music-library' ),
			'parent_item_colon'     => __( 'Parent Collection:', 'sheet-music-library' ),
			'search_items'          => __( 'Search Collections', 'sheet-music-library' ),
			'popular_items'         => __( 'Popular Collections', 'sheet-music-library' ),
			'separate_items_with_commas' => __( 'Separate multiple collections with commas', 'sheet-music-library' ),
			'choose_from_most_used' => __( 'Choose from most-used collections', 'sheet-music-library' ),
		),
		'rewrite'           => array(
			'slug'         => 'collection',
			'hierarchical' => true,
			'with_front'   => false,
		),
		'show_admin_column' => true,
		'show_in_rest' => true,
	) );

}