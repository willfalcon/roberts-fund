<?php

/**
 * Styles and scripts
 */

 function cdhq_theme_assets() {
  $ver = wp_get_theme()->get('Version');
  $env = wp_get_environment_type();

    if ($env == 'development' || $env == 'local') {
      wp_enqueue_style( 'normalize', get_template_directory_uri() . '/dist/normalize.css' );
      wp_enqueue_style( 'build_styles', get_template_directory_uri() . '/dist/styles.css', array(), $ver );
      wp_enqueue_script( 'build_js', get_template_directory_uri() . '/dist/index.js', array(), $ver, true );
    } else {
      wp_enqueue_style( 'normalize', get_template_directory_uri() . '/dist/normalize.min.css' );
      wp_enqueue_style( 'build_styles', get_template_directory_uri() . '/dist/styles.min.css', array(), $ver );
      wp_enqueue_script( 'build_js', get_template_directory_uri() . '/dist/main.min.js', array('promise_polyfill', 'classlist_polyfill'), $ver, true );
    }
 }

 add_action( 'wp_enqueue_scripts', 'cdhq_theme_assets' ); 

 /**
 * Adjust tag attributes for enqueued scripts
 * 
 * Add pseudo elements attr for fontawesome
 */
function add_async_attribute($tag, $handle) {
    if ( 'fontawesome' !== $handle )
        return $tag;
    return str_replace( ' src', 'data-search-pseudo-elements defer src', $tag );
}
add_filter('script_loader_tag', 'add_async_attribute', 10, 2);

/**
 * Load theme textdomain for translations
 */
function cdhq_load_theme_textdomain() {
  $text_domain = 'cdhq';
  load_theme_textdomain( $text_domain, get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'cdhq_load_theme_textdomain' );

/* Add Theme Supports */
add_theme_support( 'menus' );
add_theme_support( 'title-tag' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'align-wide' );
if (!isset($content_width)) {
  $content_width = 1200;
}

/**
 * write_log
 * ea_pp
 */
include_once('inc/debugging.php');


/**
 * slugify
 * format_phone
 */
include_once('inc/formatting_utils.php');


/**
 * add_block_category
 */
include_once('blocks/blocks.php');

/**
 * cc_mime_types
 * fix_svg
 * add_image_size
 */
include_once('inc/media.php');


/**
 * array_find
 * array_find_index
 */
include_once('inc/utils.php');



include_once('inc/require-plugins.php');


/**
 * Create Menu Locations
 */
function register_theme_menus() {
  register_nav_menus(
    array(
      'main_menu' => __('Main Menu', 'cdhq'),
    )
  );
}
add_action( 'init', 'register_theme_menus' );


/**
 * Add options pages
 */
if (function_exists('acf_add_options_page')) {
  acf_add_options_page(array(
    'page_title' => __('Site Options', 'cdhq'),
    'position' => 53.3
  ));
}


/**
 * Set color palette options on acf color fields
 */
function acf_load_color_field_choices( $field ) {
  // reset choices
  $field['choices'] = array();
  // if has rows
  $field['choices'][''] = '';
  if( have_rows('color_palette', 'option') ) {
      // while has rows
      while( have_rows('color_palette', 'option') ) {
          // instantiate row
          the_row();
          // vars
          $color = get_sub_field('color');
          $label = get_sub_field('label');
          // append to choices
          $field['choices'][ $color ] = $label;   
      }
  }
  // return the field
  return $field;
}

add_filter('acf/load_field/name=palette_colors', 'acf_load_color_field_choices');
add_filter('acf/load_field/name=background_overlay', 'acf_load_color_field_choices');

/**
 * Set color variables
 */

 function cdhq_set_color_vars() {
  $colors = get_field('color_palette', 'options');
  if ($colors) {
    echo '
      <style type="text/css">
        :root {
    ';
    
    foreach ($colors as $color) {
      echo '--' . slugify($color['label']) . ': ' . $color['color'] . ';
      ';
      echo '--rgb-' . slugify($color['label']) . ': ' . implode(',', hexToRgb($color['color'])) . ';
      ';
    }

    echo '
        }
      </style>
    ';
  }

 }
 add_action('wp_head', 'cdhq_set_color_vars');


/**
 * Set login screen logo
 */
function cdhq_login_logo() {
	// Change these
	$actual_height = 1017;
	$actual_width = 1800;
  $logo_filename = 'roberts-fund-color.png';

  $logo_height = 320 * $actual_height / $actual_width;
	?>
		<style type="text/css">
			#login h1 a, .login h1 a {
				background-image: url(<?php echo get_stylesheet_directory_uri() . '/dist/' . $logo_filename ?>);
				height: <?php echo $logo_height; ?>px;
				width:320px;
				background-size: 320px <?php echo $logo_height; ?>px;
				background-repeat: no-repeat;
				padding-bottom: 30px;
			}
		</style>
	<?php
}
add_action( 'login_enqueue_scripts', 'cdhq_login_logo' );


/**
 * change logo link to homepage on login page
 */
function cdhq_login_logo_url() {
	return home_url();
}
add_filter( 'login_headerurl', 'cdhq_login_logo_url' );


/**
 * Excerpt length and more tags
 */

	function custom_excerpt_length( $length ) {
		return 30;
	}
	add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

	function cdhq_excerpt_more( $more ) {
		/**
			* Filters the excerpt "read more" string.
			*
			* @param string $more "Read more" excerpt string.
			* @return string (Maybe) modified "read more" excerpt string.
		*/
		return '...';
	}
	add_filter( 'excerpt_more', 'cdhq_excerpt_more' );
