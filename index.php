<?php

/* Plugin Name: Health Tracker
Plugin URI: https://johnlazaro.com/
Description: Health tracker plugin for WordPress
Version: 1.0
Author: John Lazaro
URI: https://johnlazaro.com/
License: GPLv2 or later */

add_shortcode( 'health-tracker', 'shortcode_health_tracker' );
function shortcode_health_tracker( $atts ) {

	ob_start();

	include plugin_dir_path( __FILE__ ) . 'template.php';

	return ob_get_clean();

}