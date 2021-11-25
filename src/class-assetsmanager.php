<?php

namespace OtisAI;

use OtisAI\admin\AdminConstants;

/**
 * Class responsible of managing all the plugin assets.
 */
class AssetsManager {
	const ADMIN_CSS  = 'otisai-css';
	const BRIDGE_CSS = 'otisai-bridge-css';

	/**
	 * Register and localize all assets.
	 */
	public static function register_assets() {
		wp_register_style( self::ADMIN_CSS, OTISAI_PATH . '/assets/css/otisai.css', array(), OTISAI_PLUGIN_VERSION );
		wp_register_style( self::BRIDGE_CSS, OTISAI_PATH . '/assets/css/otisai-bridge.css?', array(), OTISAI_PLUGIN_VERSION );
	}

	/**
	 * Enqueue the assets needed in the admin section.
	 */
	public static function enqueue_admin_assets() {
		wp_enqueue_style( self::ADMIN_CSS );
	}

	/**
	 * Enqueue the assets needed to correctly render the plugin's iframe.
	 */
	public static function enqueue_bridge_assets() {
		wp_enqueue_style( self::BRIDGE_CSS );
	}
}
