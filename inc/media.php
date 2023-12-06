<?php 
// Allow SVG
add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {

  global $wp_version;
  if ( $wp_version !== '4.7.1' ) {
     return $data;
  }

  $filetype = wp_check_filetype( $filename, $mimes );

  return [
      'ext'             => $filetype['ext'],
      'type'            => $filetype['type'],
      'proper_filename' => $data['proper_filename']
  ];

}, 10, 4 );

function cc_mime_types( $mimes ){
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );

function fix_svg() {
  echo '<style type="text/css">
        .attachment-266x266, .thumbnail img {
             width: 100% !important;
             height: auto !important;
        }
        </style>';
}
add_action( 'admin_head', 'fix_svg' );

// Add image sizes

function cdhq_add_image_sizes() {
  // add_image_size( 'hero', 2000 );
  // add_image_size( 'placeholder', 100);
  // add_image_size( 'topic-thumbnail', 300, 300, true);
}

// add_action( 'after_setup_theme', 'cdhq_add_image_sizes' );


function cdhq_custom_sizes( $sizes ) {
  return array_merge( $sizes, array(
      // 'hero' => __('Hero', 'cdhq'),
      // 'placeholder' => __('Placeholder', 'cdhq'),
  ) );
}
// add_filter( 'image_size_names_choose', 'cdhq_custom_sizes' );
