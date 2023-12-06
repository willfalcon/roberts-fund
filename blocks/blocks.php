<?php

/**
 * Register all custom blocks for theme
 * Hooked into 'init'
 */
	function cdhq_register_blocks() {
		$blocks = get_blocks();

		$ver = wp_get_theme()->get('Version');
		$env = wp_get_environment_type();
		$base = get_template_directory() . '/blocks/';
		$is_dev = $env == 'development' || $env == 'local';
		
		foreach ($blocks as $block) {
			$block_base = $base . $block;
			$dist_base = get_template_directory_uri() . '/dist/' . $block . '/' . $block;
			if (file_exists($block_base . '/block.json')) {
				register_block_type($block_base);
				if (file_exists($block_base . '/' . $block . '.scss')) {
					wp_register_style($block . '-style', $is_dev ? $dist_base . '.css' : $dist_base . '.min.css', array(), $ver);
				}
				if (file_exists($block_base . '/' . $block . '-editor.scss')) {
					wp_register_style($block . '-editor-style', $is_dev ? $dist_base . '-editor.css' : $dist_base . '-editor.min.css', array(), $ver);
				}
				if (file_exists($block_base . '/' . $block . '.js')) {
					wp_register_script($block . '-script', $is_dev ? $dist_base . '.js' : $dist_base . '.min.js', array(), $ver, true);
				}
				if (file_exists($block_base . '/' . $block . '-editor.js')) {
					wp_register_script($block . '-editor-script', $is_dev ? $dist_base . '-editor.js' : $dist_base . '-editor.min.js', array(), $ver, true);
				}
				
			}
		}
	}

	add_action('init', 'cdhq_register_blocks', 5);

	
	
	function get_blocks() {
		$theme = wp_get_theme();
		$blocks = get_option('cdhq_blocks');
		$version = get_option('cdhq_blocks_version');
		if (empty($blocks) || version_compare($theme->get('Version'), $version) || (function_exists( 'wp_get_environment_type' ) && 'production' !== wp_get_environment_type())) {
			$blocks = scandir(get_template_directory() . '/blocks/' );
			$blocks = array_values(array_diff($blocks, array('..', '.', '.DS_Store', '_base-block', 'blocks.php', '_block-import.scss')));
			
			update_option('cdhq_blocks', $blocks);
			update_option('cdhq_blocks_version', $theme->get('Version'));
		}
		return $blocks;
	}

	/**
	 * Enqueue block editor assets
	 */
	function cdhq_block_editor_assets() {
		$ver = wp_get_theme()->get('Version');
		$env = wp_get_environment_type();
		$is_dev = $env == 'development' || $env == 'local';
		$base = get_template_directory_uri();
		wp_enqueue_style( 'editor_styles', $is_dev ? $base . '/dist/editor-styles.css' : $base . '/dist/editor-styles.min.css', array(), $ver );
		wp_enqueue_script('cdhq-editor-customizations', $is_dev ? $base . '/dist/editor.js' : $base . '/dist/editor.min.js', array( 'wp-blocks', 'wp-dom-ready', 'wp-plugins', 'wp-edit-post', 'wp-hooks' ), $ver, true);
		// wp_enqueue_style( 'fonts_editor', 'https://use.typekit.net/zlj5jck.css', array(), null );
	}
	add_action( 'enqueue_block_editor_assets', 'cdhq_block_editor_assets' );

// function cdhq_register_blocks() {
// 	$ver = wp_get_theme()->get('Version');
//   $env = wp_get_environment_type();
  

// 	$is_dev = $env == 'development' || $env == 'local';
// 	$base = get_template_directory_uri() . '/dist/';
	
// 	// Image Link
// 	register_block_type( __DIR__ . '/image-link' );
// 	wp_register_style('image-link-style', $is_dev ? $base . 'image-link/image-link.css' : $base . 'image-link/image-link.min.css', array(), $ver);
// 	wp_register_style('image-link-editor-style', $is_dev ? $base . 'image-link/image-link-editor.css' : $base . 'image-link/image-link-editor.min.css', array(), $ver);
// }

// add_action('init', 'cdhq_register_blocks', 5);

add_filter( 'should_load_separate_core_block_assets', '__return_true' );

/**
 * Add Theme Block Category
 */
function cdhq_add_block_category( $categories, $post ) {
	return array_merge(
		array(
			array(
				'slug' => 'cdhq',
				'title' => __('MDHS Blocks', 'cdhq'),
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