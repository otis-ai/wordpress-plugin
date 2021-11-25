<?php

namespace OtisAI;

/**
 * Plugin Name: Otis AI - Digital marketing for small business
 * Description: Launch data-driven digital ad campaigns on Facebook, Instagram and Google in 5 minutes
 * Version: 1.0.0
 * Author: Otis AI
 * Author URI:  https://www.meetotis.com/
 * License: GPL v3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: otisai
 * Requires at least: 4.0
 * Tested up to: 5.8
 * WC requires at least: 3.5
 * WC tested up to: 5.9
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// =============================================
// Define Constants
// =============================================
defined( 'ABSPATH' ) || exit;

if ( ! defined( 'OTISAI_BASE_PATH' ) ) {
	define( 'OTISAI_BASE_PATH', __FILE__ );
}

if ( ! defined( 'OTISAI_PATH' ) ) {
	define( 'OTISAI_PATH', untrailingslashit( plugins_url( '', OTISAI_BASE_PATH ) ) );
}

if ( ! defined( 'OTISAI_PLUGIN_DIR' ) ) {
	define( 'OTISAI_PLUGIN_DIR', untrailingslashit( dirname( OTISAI_BASE_PATH ) ) );
}

if ( ! defined( 'OTISAI_REQUIRED_WP_VERSION' ) ) {
	define( 'OTISAI_REQUIRED_WP_VERSION', '4.0' );
}

if ( ! defined( 'OTISAI_REQUIRED_PHP_VERSION' ) ) {
	define( 'OTISAI_REQUIRED_PHP_VERSION', '5.6' );
}

if ( ! defined( 'OTISAI_PLUGIN_VERSION' ) ) {
	define( 'OTISAI_PLUGIN_VERSION', '1.0.0' );
}

if ( ! defined( 'OTISAI_JS_BASE_PATH' ) ) {
	define( 'OTISAI_JS_BASE_PATH', OTISAI_PATH . '/js/dist' );
}


// =============================================
// Set autoload
// =============================================
require_once OTISAI_PLUGIN_DIR . '/vendor/autoload.php';
require_once ABSPATH . 'wp-admin/includes/plugin.php';

use \OtisAI\OtisAI;

$otisai = new OtisAI();
