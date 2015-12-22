<?php
/*
 * Plugin Name: Nona Age Gate
 * Version: 1.0
 * Plugin URI: http://leogopal.com/
 * Description: Simple Age Gate Plugin to restrict a users access to the site.
 * Author: Leo Gopal, Nona Creative
 * Author URI: http://leogopal.com/
 * Requires at least: 4.0
 * Tested up to: 4.0
 *
 * Text Domain: nona-age-gate
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Hugh Lashbrooke
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Load plugin class files
require_once( 'includes/class-nona-age-gate.php' );
require_once( 'includes/class-nona-age-gate-settings.php' );

// Load plugin libraries
require_once( 'includes/lib/class-nona-age-gate-admin-api.php' );


/**
 * Returns the main instance of Nona_Age_Gate to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Nona_Age_Gate
 */
function Nona_Age_Gate () {
	$instance = Nona_Age_Gate::instance( __FILE__, '1.0.0' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = Nona_Age_Gate_Settings::instance( $instance );
	}

	return $instance;
}

Nona_Age_Gate();
