<?php
namespace OtisAI\admin;

/**
 * Class containing all the filters used for the admin side of the plugin.
 */
class AdminFilters {
	/**
	 * Apply otisai_view_plugin_menu_capability filter.
	 */
	public static function apply_view_plugin_menu_capability() {
		return apply_filters( 'otisai_view_plugin_menu_capability', 'edit_posts' );
	}

	/**
	 * Apply otisai_connect_plugin_capability filter.
	 */
	public static function apply_connect_plugin_capability() {
		return apply_filters( 'otisai_connect_plugin_capability', 'manage_options' );
	}
}
