<?php
/**
 * Plugin Name: Sheet Music Library
 * Plugin URI: http://celloexpressions.com/plugins/sheet-music-library
 * Description: Add a sheet music library, including PDFs, audio, descriptions and more to your site.
 * Version: 2.0.1
 * Author: Nick Halsey
 * Author URI: http://celloexpressions.com/
 * Tags: music, sheet music, library, music library
 * License: GPL
 * Text Domain: sheet-music-library

=====================================================================================
Copyright (C) 2023 Nick Halsey

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with WordPress; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
=====================================================================================
*/

// Register Custom Post Type.
require( plugin_dir_path( __FILE__ ) . '/post-type.php' );

// Set up taxonomies.
require( plugin_dir_path( __FILE__ ) . '/taxonomies.php' );

// Set up block-related functionality.
require( plugin_dir_path( __FILE__ ) . '/block-functions.php' );

// Set up custom post meta.
require( plugin_dir_path( __FILE__ ) . '/admin/post-meta.php' );

// Ajax actions.
require( plugin_dir_path( __FILE__ ) . '/admin/ajax-actions.php' );

// Plugin options UI, via the Customizer.
require( plugin_dir_path( __FILE__ ) . '/admin/customize-options.php' );

// Load template-part component functions.
require( plugin_dir_path( __FILE__ ) . '/template/template-parts.php' );

// Load template-filtering functions.
require( plugin_dir_path( __FILE__ ) . '/template/template-filters.php' );

// Load the automatic playlist widget.
require( plugin_dir_path( __FILE__ ) . '/template/sheet-music-library-playlist-widget.php' );

// Load latest playlist widget.
require( plugin_dir_path( __FILE__ ) . '/template/sheet-music-library-recent-playlist-widget.php' );

// Load Translations
add_action( 'plugins_loaded', 'sml_load_textdomain' );
function sml_load_textdomain() {
	load_plugin_textdomain( 'sheet-music-library' );
}

// Enqueue default front-end styles.
add_action( 'wp_enqueue_scripts', 'sml_enqueue_scripts' );
function sml_enqueue_scripts() {
	if ( ! current_theme_supports( 'sheet_music_library' ) && false == get_option( 'sml_theme_support_styles', false ) ) {
		wp_enqueue_style( 'sheet-music-library', plugins_url( '/template/sheet-music-library.css', __FILE__ ) );
	}
}

// Load template for single content display.
add_filter( 'the_content', 'sml_template_filter' );
add_filter( 'the_excerpt', 'sml_template_filter' );
function sml_template_filter( $the_content ) {
	if ( 'sheet_music' === get_post_type() && ! current_theme_supports( 'sheet_music_library' ) && false == get_option( 'sml_theme_support_content', false ) ) {
		if ( is_singular() ) {
			return sml_single_content_filter( $the_content );
		} else {
			return sml_archive_content_filter( $the_content );
		}
	}

	return $the_content;
}
add_filter( 'the_title', 'sml_post_title_filter', 10, 2 );

// Register the [all_sheet_music] shortcode.
add_shortcode( 'all_sheet_music', 'sml_shortcode_all_sheet_music' );

// Register the [latest_sheet_music number="10"] shortcode.
add_shortcode( 'latest_sheet_music', 'sml_shortcode_latest_sheet_music' );

// Register the [sheet_music_audio_playlist genre=""] shortcode.
add_shortcode( 'sheet_music_audio_playlist', 'sml_shortcode_sheet_music_audio_playlist' );

