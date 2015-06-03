<?php
/*
Plugin Name: Nashr SEO 
Plugin URI: http://nashr-seo.mnbaa.com/
Description: wordpress plugin you can use to optimize your website for search engines and social media websites .
Author: Mnbaa CO
Author URI: http://www.mnbaa.com
Version: 1.5
Text Domian:mnbaa-seo
Domain Path: /languages
*/
wp_enqueue_script('custom-js', plugins_url( '', __FILE__ ).'/js/custom-js.js');
wp_enqueue_script('limit', plugins_url( '', __FILE__ ).'/js/limit.js');
wp_enqueue_script('selectall', plugins_url( '', __FILE__ ).'/js/selectall.js');
wp_enqueue_script('mytabs', plugins_url( '', __FILE__ ).'/js/mytabs.js',array( 'jquery-ui-tabs' ));
wp_enqueue_script('jquery-ui', plugins_url('', __FILE__) . '/js/jquery-ui.js');
wp_enqueue_script('nashr-autocomplete', plugins_url('', __FILE__) . '/js/nashr-autocomplete.js', array( 'jquery-ui-autocomplete' ));

//load ajax files
wp_register_script("ajax-js", plugins_url( '', __FILE__ ) . '/js/ajax-js.js', array('jquery'));
wp_localize_script('ajax-js', 'ajax', array('ajaxurl' => admin_url('admin-ajax.php')));
wp_enqueue_script('ajax-js');

// load ajax
wp_register_script("ajax-nashr",plugins_url('', __FILE__) .'/js/ajax-nashr.js', array('jquery'));
wp_localize_script('ajax-nashr', 'myAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
wp_enqueue_script('ajax-nashr');

load_plugin_textdomain('mnbaa-seo', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
include( plugin_dir_path( __FILE__ ) . 'includes/mnbaa_functions.php');
include( plugin_dir_path( __FILE__ ) . 'controllers/ajax_functions.php');
include( plugin_dir_path( __FILE__ ) . 'includes/wp_functions.php');
include( plugin_dir_path( __FILE__ ) . 'includes/arrays.php');
register_activation_hook( __FILE__, 'mnbaa_seo_activate' );
mnbaa_seo_run_plugin();


?>