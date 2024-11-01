<?php
/**
 * Customizer options for the Sheet Music Library plugin.
 */

add_action( 'customize_register', 'sml_customize_register' );
function sml_customize_register( $wp_customize ) {
	// Add sheet music library section.
	// Additional controls can be added here in the future or via add-on plugins/custom themes.
	$wp_customize->add_section( 'sheet_music_library', array(
		'title' => __( 'Sheet Music Library', 'sheet-music-library' ),
	) );

	$wp_customize->add_setting( 'sml_terms', array(
		'type' => 'option',
		'capability' => 'manage_options',
		'default' => __( 'By downloading this music, you agree to the <a href="/terms/">Terms & Conditions</a>.', 'sheet-music-library' ),
	) );

	$wp_customize->add_control( 'sml_terms', array(
		'label' => __( 'Terms & Conditions Message', 'sheet-music-library' ),
		'description' => __( 'Displayed alongside sheet music download buttons.', 'sheet-music-library' ),
		'type' => 'textarea',
		'section' => 'sheet_music_library',
	) );

	// Options to turn off content filtering via UI, encouraged for use with block themes only.
	if ( ! current_theme_supports( 'sheet_music_library' ) ) {

		$wp_customize->add_setting( 'sml_theme_support_content', array(
			'type' => 'option',
			'capability' => 'manage_options',
			'default' => false,
		) );
	
		$wp_customize->add_control( 'sml_theme_support_content', array(
			'label' => __( 'Disable Content Filtering', 'sheet-music-library' ),
			'description' => __( 'This option turns off content filtering. Only enable it if you have set up sheet music post and archive templates to display sheet music content blocks.', 'sheet-music-library' ),
			'type' => 'checkbox',
			'section' => 'sheet_music_library',
		) );
	
		$wp_customize->add_setting( 'sml_theme_support_styles', array(
			'type' => 'option',
			'capability' => 'manage_options',
			'default' => false,
		) );
	
		$wp_customize->add_control( 'sml_theme_support_styles', array(
			'label' => __( 'Disable Sheet Music CSS Styles', 'sheet-music-library' ),
			'description' => __( 'This option turns off default sheet music library CSS styles. Only enable it if you have set up custom CSS styling to replace the defaults.', 'sheet-music-library' ),
			'type' => 'checkbox',
			'section' => 'sheet_music_library',
		) );
	}
}