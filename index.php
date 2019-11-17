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

	$action = $_GET['heatra_page'];

	switch($action) {

		case 'foods':
			$template_path = 'template-foods.php';
			break;
		
		default:
			$template_path = 'template.php';
			break;
	}

	include plugin_dir_path( __FILE__ ) . $template_path;

	return ob_get_clean();

}

add_action( 'wp_enqueue_scripts', 'heatra_wp_enqueue_scripts' );
function heatra_wp_enqueue_scripts() {

	wp_register_script( 'heatra-script-name', plugin_dir_url( __FILE__ ) . 'scripts.js', array( 'jquery' ), '1.0.0', true );

	wp_localize_script(
		'heatra-script-name',
		'ajax_data',
		array( 'url' => admin_url( 'admin-ajax.php' ) )
	);

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'heatra-script-name' );

}

add_action( 'wp_ajax_heatra_insert_to_db_function', 'heatra_insert_to_db_function' );
add_action( 'wp_ajax_priv_heatra_insert_to_db_function', 'heatra_insert_to_db_function' );
function heatra_insert_to_db_function() {

	global $wpdb;

	$table_name = $wpdb->prefix . 'heatra_records';

	$food = $_POST['food'];
	$amount = $_POST['amount'];

	$result = $wpdb->query( $wpdb->prepare( 
		"
		INSERT INTO $table_name
		( date_posted, ref_food_id, amount )
		VALUES ( NOW(), %d, %s )
		",
		$food,
		$amount
	) );

	if( ! $result )
		$result = $wpdb->last_error;

    // return
    echo json_encode( $result );
    wp_die();

}