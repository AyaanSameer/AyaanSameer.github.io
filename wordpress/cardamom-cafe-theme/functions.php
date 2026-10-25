<?php
/**
 * Cardamom Café theme setup.
 *
 * A block theme needs very little PHP: theme.json carries the design, and
 * templates, parts and patterns are plain files WordPress finds on its own.
 * This file only loads the stylesheet's few extra rules and names the
 * pattern category.
 *
 * @package Cardamom_Cafe
 */

defined( 'ABSPATH' ) || exit;

/**
 * Load style.css on the front end and in the editor, so what the owner edits
 * looks like what visitors see.
 */
function cardamom_cafe_setup() {
	add_editor_style( 'style.css' );
}
add_action( 'after_setup_theme', 'cardamom_cafe_setup' );

function cardamom_cafe_enqueue_styles() {
	wp_enqueue_style(
		'cardamom-cafe',
		get_stylesheet_uri(),
		array(),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'cardamom_cafe_enqueue_styles' );

/**
 * Group the theme's patterns under one heading in the inserter.
 */
function cardamom_cafe_pattern_category() {
	register_block_pattern_category(
		'cardamom-cafe',
		array( 'label' => __( 'Cardamom Café', 'cardamom-cafe' ) )
	);
}
add_action( 'init', 'cardamom_cafe_pattern_category' );
