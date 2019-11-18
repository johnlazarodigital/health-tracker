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

	wp_register_script( 'heatra-script-name', plugin_dir_url( __FILE__ ) . 'scripts.js', array( 'jquery' ), '1.0.4', true );

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

add_action( 'wp_ajax_heatra_get_record', 'heatra_get_record' );
add_action( 'wp_ajax_priv_heatra_get_record', 'heatra_get_record' );
function heatra_get_record() {

	global $wpdb;

	$table_name = $wpdb->prefix . 'heatra_records';

	$table_name_two = $wpdb->prefix . 'heatra_foods';

	$results = $wpdb->get_results( 
		"
		SELECT
		DATE_FORMAT(a.date_posted, '%b %d, %Y %h:%i %p') as date_posted, 
		a.ref_food_id,
		a.amount,
		b.name
		FROM $table_name a
		LEFT JOIN $table_name_two b
		ON b.id = a.ref_food_id
		ORDER BY a.id DESC
		"
	);

	if( ! $results )
		$results = $wpdb->last_error;

    // return
    echo json_encode( $results );
    wp_die();

}

add_action( 'wp_ajax_heatra_get_foods', 'heatra_get_foods' );
add_action( 'wp_ajax_priv_heatra_get_foods', 'heatra_get_foods' );
function heatra_get_foods() {

	global $wpdb;

	$table_name = $wpdb->prefix . 'heatra_foods';

	$results = $wpdb->get_results( 
		"
		SELECT *
		FROM $table_name
		"
	);

	if( ! $results )
		$results = $wpdb->last_error;

    // return
    echo json_encode( $results );
    wp_die();

}


add_action( 'wp_ajax_heatra_get_nutrition', 'heatra_get_nutrition' );
add_action( 'wp_ajax_priv_heatra_get_nutrition', 'heatra_get_nutrition' );
function heatra_get_nutrition() {

	global $wpdb;

	$table_name = $wpdb->prefix . 'heatra_nutrients';

	$food_id = $_POST['food_id'];
	$amount = $_POST['amount'];

	$results = $wpdb->get_results( 
		"
		SELECT *
		FROM $table_name
		WHERE ref_food_id = '" . $food_id . "'
		"
	);

	if( $results ) {

		$items = array();

		foreach( $results as $item ) {

			$total_amount = $item->amount * $amount;

			$item->total_amount = $total_amount;

			$items[] = $item;

		}

		$results = $items;

	}

	// if( ! $results )
	// 	$results = $wpdb->last_error;

    // return
    echo json_encode( $results );
    wp_die();

}

add_action( 'wp_ajax_heatra_daily_status', 'heatra_daily_status' );
add_action( 'wp_ajax_priv_heatra_daily_status', 'heatra_daily_status' );
function heatra_daily_status() {

	global $wpdb;

	$table_name = $wpdb->prefix . 'heatra_records';
	$table_two = $wpdb->prefix . 'heatra_nutrients';

	$date = $_POST['date'];

	$results = $wpdb->get_results( 
		"
		SELECT
		b.amount as calories,
		c.amount as protein,
		d.amount as carbs,
		e.amount as fat,
		f.amount as fiber,
		g.amount as sugar,

		a.amount as multiplier

		FROM $table_name a

		LEFT JOIN $table_two b
		ON b.ref_food_id = a.ref_food_id

		LEFT JOIN $table_two c
		ON c.ref_food_id = a.ref_food_id

		LEFT JOIN $table_two d
		ON d.ref_food_id = a.ref_food_id

		LEFT JOIN $table_two e
		ON e.ref_food_id = a.ref_food_id

		LEFT JOIN $table_two f
		ON f.ref_food_id = a.ref_food_id

		LEFT JOIN $table_two g
		ON g.ref_food_id = a.ref_food_id

		WHERE
		b.name = 'calories' AND
		c.name = 'protein' AND
		d.name = 'carbs' AND
		e.name = 'fat' AND
		f.name = 'fiber' AND
		g.name = 'sugar' AND

		a.date_posted LIKE '%" . $date . "%'
		"
	);

	$calories = 0;
	$protein = 0;
	$carbs = 0;
	$fat = 0;
	$fiber = 0;
	$sugar = 0;

	if( $results ) {

		$items = array();

		foreach( $results as $item ) {

			$calories += $item->calories * $item->multiplier;
			$protein += $item->protein * $item->multiplier;
			$carbs += $item->carbohydrates * $item->multiplier;
			$fat += $item->fat * $item->multiplier;
			$fiber += $item->fiber * $item->multiplier;
			$sugar += $item->sugar * $item->multiplier;

			$items[] = $item;

		}

		$results = $items;

	}

	$results = array(
		array(
			'calories' => $calories,
			'protein' => $protein,
			'carbs' => $carbs,
			'fat' => $fat,
			'fiber' => $fiber,
			'sugar' => $sugar,
		),
		$items
	);

	$results = array(
		'calories' => array(
			'count' => $calories,
			'percentage' => count_percentage( $calories, 2000 )
		),
		'protein' => array(
			'count' => $protein,
			'percentage' => count_percentage( $protein, 56 )
		),
		'carbs' => array(
			'count' => $carbs,
			'percentage' => count_percentage( $carbs, 200 )
		),
		'fat' => array(
			'count' => $fat,
			'percentage' => count_percentage( $fat, 40 )
		),
		'fiber' => array(
			'count' => $fiber,
			'percentage' => count_percentage( $fiber, 25 )
		),
		'sugar' => array(
			'count' => $sugar,
			'percentage' => count_percentage( $sugar, 80 )
		),
	);

    // return
    echo json_encode( (object) $results );
    wp_die();

}

function count_percentage($a, $b) {
	$percent = $a/$b;
	return number_format( $percent * 100 ) . '%';
}