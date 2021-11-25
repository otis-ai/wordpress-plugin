<?php

namespace OtisAI\admin;

use OtisAI\OtisAIFilters;
use OtisAI\options\AccountOptions;
use OtisAI\admin\Connection;
use OtisAI\admin\AdminConstants;

/**
 * Class containing all the functions to generate links to Otis.
 */
class Links {
	/**
	 * Deprecated for OAuth2 routes
	 *
	 * Get a map of <admin_page, url>
	 * Where
	 * - admin_page is a string
	 * - url is either a string or another map <route, string_url>, both strings
	 */
	public static function get_routes_mapping() {
		$portal_id = AccountOptions::get_portal_id();
	}

	/**
	 * Get page name from the current page id.
	 */
	private static function get_page_id() {
		$screen_id = get_current_screen()->id;
		return preg_replace( '/^(otis_page_|toplevel_page_)/', '', $screen_id );
	}

	/**
	 * Get the parsed `otisai_route` from the query string.
	 */
	private static function get_iframe_route() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$iframe_route = isset( $_GET['otisai_route'] ) ? esc_url_raw( wp_unslash( $_GET['otisai_route'] ) ) : array();
		return is_array( $iframe_route ) ? $iframe_route : array();
	}

	/**
	 * Get the parsed `otisai_search` from the query string.
	 */
	private static function get_iframe_search_string() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return isset( $_GET['otisai_search'] ) ? esc_url_raw( wp_unslash( '&' . $_GET['otisai_search'] ) ) : '';
	}

	/**
	 * Return query string from object
	 *
	 * @param array $arr query parameters to stringify.
	 */
	private static function http_build_query( $arr ) {
		if ( ! is_array( $arr ) ) {
			return '';
		}
		return http_build_query( $arr, null, ini_get( 'arg_separator.output' ), PHP_QUERY_RFC3986 );
	}

	/**
	 * Validate static version.
	 *
	 * @param String $version version of the static bundle.
	 */
	private static function is_static_version_valid( $version ) {
		preg_match( '/static-\d+\.\d+/', $version, $match );
		return ! empty( $match );
	}

	/**
	 * Return a string query parameters to add to the iframe src.
	 */
	public static function get_query_params() {
		$config_array = AdminConstants::get_otis_query_params_array();

		return self::http_build_query( $config_array );
	}

	/**
	 * Get background iframe src.
	 */
	public static function get_background_iframe_src() {
		$portal_id     = AccountOptions::get_portal_id();
		$portal_id_url = '';

		if ( Connection::is_connected() ) {
			$portal_id_url = "/$portal_id";
		}

		$query = '';

		return OtisAIFilters::get_otisai_base_url() . "/wordpress$portal_id_url/background?$query" . self::get_query_params();
	}

	/**
	 * Return login link to redirect to when the user isn't authenticated in Otis
	 */
	public static function get_login_url() {
		$portal_id = AccountOptions::get_portal_id();
		return OtisAIFilters::get_otisai_base_url() . "/wordpress/$portal_id/login?" . self::get_query_params();
	}

	/**
	 * Returns the url for the connection page
	 */
	private static function get_connection_src() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$portal_id = isset( $_GET['otisai_connect'] ) ? filter_var( esc_url_raw( wp_unslash( $_GET['otisai_connect'] ) ), FILTER_VALIDATE_INT ) : '';
		return OtisAIFilters::get_otisai_base_url() . "/wordpress/register/connect?portalId=$portal_id&" . self::get_query_params();
	}

	/**
	 * Returns the right iframe src.
	 */
	public static function get_iframe_src() {
		$otisai_onboarding     = 'otisai_onboarding';
		$browser_search_string = '';

		if ( get_transient( $otisai_onboarding ) ) {
			delete_transient( $otisai_onboarding );
			$browser_search_string = '&justConnected=true';
		}

		$sub_routes_array      = self::get_iframe_route();
		$inframe_search_string = self::get_iframe_search_string();
		$browser_search_string = $browser_search_string . $inframe_search_string;

		$wp_user    = wp_get_current_user();
		$wp_user_id = $wp_user->ID;
		set_transient( $otisai_onboarding, 'true' );
		$route = '/wordpress/register';

		$sub_routes = join( '/', $sub_routes_array );
		$sub_routes = empty( $sub_routes ) ? $sub_routes : "/$sub_routes";
		// Query string separator "?" may have been added to the URL already.
		$add_separator = strpos( $sub_routes, '?' ) ? '&' : '?';
		return OtisAIFilters::get_otisai_base_url() . "$route$sub_routes" . $add_separator . self::get_query_params() . $browser_search_string;
	}
}
