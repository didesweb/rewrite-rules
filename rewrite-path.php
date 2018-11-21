<?php

/* Didesweb Rewrite Path */
function ddw_flush_rewrites() {
  global $wp_rewrite;
  $wp_rewrite->flush_rules();
}
add_action('admin_init', 'ddw_flush_rewrites');

function ddw_change_path($content) {
  $my_theme_name = wp_get_theme();
  $theme_name = $my_theme_name->get( 'TextDomain' );
  global $wp_rewrite;
  $guv_new_non_wp_rules = array(
    'app/styles/(.*)'      => 'wp-content/themes/'. $theme_name . '/app/styles/$1',
    'app/scripts/(.*)'     => 'wp-content/themes/'. $theme_name . '/app/scripts/$1', 
    'app/fonts/(.*)'     => 'wp-content/themes/'. $theme_name . '/app/fonts/$1',
    'app/images/(.*)'      => 'wp-content/uploads/$1'
  );
  $wp_rewrite->non_wp_rules += $guv_new_non_wp_rules;
}
add_action('generate_rewrite_rules', 'ddw_change_path');

function ddw_filter_path($content) {
  $my_theme_name = wp_get_theme();
  $theme_name = $my_theme_name->get( 'TextDomain' );
  $current_path = '/wp-content/themes/' . $theme_name;
  $new_path = '';
  $content = str_replace($current_path, $new_path, $content);
  return $content;
}
if (!is_admin()) { 
  add_filter('bloginfo', 'ddw_filter_path');
  add_filter('stylesheet_directory_uri', 'ddw_filter_path');
  add_filter('template_directory_uri', 'ddw_filter_path');
}

// Define the wp_get_attachment_image_src callback
add_filter( 'wp_get_attachment_image_src', 'filter_wp_get_attachment_image_src', 10, 4 ); 
function filter_wp_get_attachment_image_src( $image, $attachment_id, $size, $icon ) { 
    $image[0] = str_replace('wp-content/uploads/', 'app/images/', $image[0]);
    return $image; 
}; 

add_filter('wp_get_attachment_url', 'filter_wp_get_attachment_url');
function filter_wp_get_attachment_url($url) {
    return str_replace('wp-content/uploads/', 'app/images/', $url);
}

add_filter('wp_calculate_image_srcset', 'filter_wp_calculate_image_srcset', 10, 5 );
function filter_wp_calculate_image_srcset($sources, $size_array, $image_src, $image_meta, $attachment_id) {
    foreach($sources as &$source){
        $source["url"] = str_replace('wp-content/uploads/', 'app/images/', $source["url"]);
    }
    return $sources;
}
