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