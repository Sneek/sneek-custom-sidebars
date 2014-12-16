<?php defined( 'ABSPATH' ) or exit;
/**
 * Plugin Name: Sneek Custom Sidebars
 * Plugin URI: http://sneekdigital.co.uk/plugins/wp/custom-sidebars
 * Description: A simple yet powerful custom sidebar manager
 * Version: 1.0.1
 * Author: Cristian Giordano
 * Author URI: http://sneekdigital.co.uk/
 * License: GPL2
 */

define('SNEEK_CUSTOM_SIDEBARS_URL', plugin_dir_url(__FILE__));

require_once __DIR__ . '/class-sneek-custom-sidebars.php';
require_once __DIR__ . '/api-template.php';

new Sneek_Custom_Sidebars;