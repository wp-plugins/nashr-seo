<?php
/*
Plugin Name:Nashr SEO 
Plugin URI: http://www.mnbaa.com/en/%D9%85%D9%86%D8%AA%D8%AC%D8%A7%D8%AA/%D9%86%D8%B4%D8%B1-seo/
Description: wordpress plugin you can use to optimize your website for search engines and social media websites .
Author: Mnbaa CO
Author URI: http://www.mnbaa.com
Version: 1.0
Text Domian:mnbaa-seo
Domain Path: /languages
*/
wp_enqueue_script('custom-js', plugins_url( '', __FILE__ ).'/js/custom-js.js');
wp_enqueue_script('limit', plugins_url( '', __FILE__ ).'/js/limit.js');
wp_enqueue_script('selectall', plugins_url( '', __FILE__ ).'/js/selectall.js');
wp_enqueue_script('mytabs', plugins_url( '', __FILE__ ).'/js/mytabs.js',array( 'jquery-ui-tabs' ));
load_plugin_textdomain('mnbaa-seo', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
include( plugin_dir_path( __FILE__ ) . 'includes/mnbaa_functions.php');
include( plugin_dir_path( __FILE__ ) . 'includes/wp_functions.php');
include( plugin_dir_path( __FILE__ ) . 'includes/arrays.php');
register_activation_hook( __FILE__, 'Mnbaa_seo_activate' );
run_mnbaa_seo_plugin();

?>