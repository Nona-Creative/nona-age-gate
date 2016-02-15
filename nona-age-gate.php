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

$_version = '1.0.0';
$_token = 'nona_site_gate';
$dir = plugin_dir_path( __FILE__ );
$assets_dir = $dir . 'assets';
$assets_url = plugin_dir_url( __FILE__) . 'assets/';
$script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

// Load plugin class files
//
require_once( 'includes/nona-age-gate-setup.php' );
require_once( 'includes/options-page.php' );



