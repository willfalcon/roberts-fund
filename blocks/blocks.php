<?php


/**
 * Register all custom blocks for theme
 * Hooked into 'init'
 */
function cdhq_register_blocks() {
	$ver = wp_get_theme()->get('Version');
  $env = wp_get_environment_type();
  

	$is_dev = $env == 'development' || $env == 'local';
	$base = get_template_directory_uri() . '/dist/';

	// Image Link
	register_block_type( __DIR__ . '/image-link' );
	wp_register_style('image-link-style', $is_dev ? $base . 'image-link/image-link.css' : $base . 'image-link/image-link.min.css', array(), $ver);
}

add_action('init', 'cdhq_register_blocks', 5);
add_filter( 'should_load_separate_core_block_assets', '__return_true' );

/**
 * Add Theme Block Category
 */
function cdhq_add_block_category( $categories, $post ) {
	return array_merge(
		array(
			array(
				'slug' => 'cdhq',
				'title' => __('MDHS Blocks', 'mdhs'),
			)
		),
		$categories
	);
}
add_filter( 'block_categories_all', 'cdhq_add_block_category', 10, 2);

/**
 * set color palette for blocks
 */
if (function_exists('get_field')) {
	function cdhq_set_site_blocks_color_palette() {
		$colors = get_field('color_palette', 'options');
		if ($colors) {
			$color_array = array();
			foreach ($colors as $color) {
				$new_color = array(
					'name' => $color['label'],
					'slug' => str_replace(' ', '-', strtolower($color['label'])),
					'color' => $color['color']
				);
				array_push($color_array, $new_color);
			}
		}

		add_theme_support( 'editor-color-palette', $color_array);
	}

	add_action( 'after_setup_theme', 'cdhq_set_site_blocks_color_palette' );

}