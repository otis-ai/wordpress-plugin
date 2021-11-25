<?php
namespace OtisAI\admin;

use OtisAI\OtisAIFilters;
use OtisAI\admin\Links;
use OtisAI\admin\Routing;
use OtisAI\utils\Versions;
use OtisAI\wp\User;
use OtisAI\wp\Website;
use OtisAI\options\AccountOptions;
use OtisAI\admin\Connection;

/**
 * Class containing all the constants used for admin script localization.
 */
class AdminConstants {

	/**
	 * Return an array with the utm parameters for signup
	 */
	private static function get_utm_query_params_array() {
		$utm_params = array(
			'utm_source' => 'wordpress-plugin',
			'utm_medium' => 'marketplaces',
		);

		return $utm_params;
	}

	/**
	 * Return a nonce used on the connection class
	 */
	private static function get_connection_nonce() {
		return wp_create_nonce( 'otis-nonce' );
	}

	/**
	 * Return an array with the user's pre-fill info for signup
	 */
	private static function get_signup_prefill_params_array() {
		$wp_user   = wp_get_current_user();
		$user_info = array(
			'firstName' => $wp_user->user_firstname,
			'lastName'  => $wp_user->user_lastname,
			'userEmail' => $wp_user->user_email,
			'company'   => get_bloginfo( 'name' ),
			'show_nav'  => 'true',
		);

		return $user_info;
	}

	/**
	 * Return an array of properties to be included in the signup search string
	 */
	public static function get_signup_query_params_array() {
		$signup_params                        = array();
		$signup_params['otisaiPluginVersion'] = constant( 'OTISAI_PLUGIN_VERSION' );
		$user_prefill_params                  = self::get_signup_prefill_params_array();
		$signup_params                        = array_merge( $signup_params, $user_prefill_params );
		$utm_params                           = self::get_utm_query_params_array();
		return array_merge( $signup_params, $utm_params );
	}

	/**
	 * Return query params array for the iframe.
	 */
	public static function get_otis_query_params_array() {
		$wp_user     = wp_get_current_user();
		$otis_config = array(
			'l'            => get_locale(),
			'php'          => Versions::get_php_version(),
			'v'            => OTISAI_PLUGIN_VERSION,
			'wp'           => Versions::get_wp_version(),
			'theme'        => Website::get_theme(),
			'adminUrl'     => admin_url(),
			'websiteName'  => get_bloginfo( 'name' ),
			'store'        => parse_url( get_site_url(), PHP_URL_HOST ),
			'wp_user'      => $wp_user->first_name ? $wp_user->first_name : $wp_user->user_nicename,
			'ajaxUrl'      => Website::get_ajax_url(),
			'nonce'        => self::get_connection_nonce(),
			'accountName'  => AccountOptions::get_account_name(),
			'portalDomain' => AccountOptions::get_portal_domain(),
		);

		if ( User::is_admin() ) {
			$otis_config['admin'] = '1';
		}

		if ( function_exists( 'get_avatar_url' ) ) {
			$otis_config['wp_gravatar'] = get_avatar_url( $wp_user->ID );
		}

		if ( ! Connection::is_connected() ) {
			$otis_config['oauth'] = true;

			$signup_params = self::get_signup_query_params_array();
			$otis_config   = array_merge( $otis_config, $signup_params );
		}

		return $otis_config;
	}

	/**
	 * Returns a minimal version of otisaiConfig, containing the data needed by the background iframe.
	 */
	public static function get_background_otisai_config() {
		$wp_user_id = get_current_user_id();

		$background_config = array(
			'adminUrl'              => admin_url(),
			'ajaxUrl'               => Website::get_ajax_url(),
			'didDisconnect'         => true,
			'otisBaseUrl'           => OtisAIFilters::get_otisai_base_url(),
			'otisaiPluginVersion'   => constant( 'OTISAI_PLUGIN_VERSION' ),
			'locale'                => get_locale(),
			'ajaxNonce'             => wp_create_nonce( 'otis-ajax' ),
			'restNonce'             => wp_create_nonce( 'wp_rest' ),
			'otisNonce'             => self::get_connection_nonce(),
			'redirectNonce'         => wp_create_nonce( Routing::REDIRECT_NONCE ),
			'phpVersion'            => Versions::get_wp_version(),
			'pluginPath'            => constant( 'OTISAI_PATH' ),
			'plugins'               => get_plugins(),
			'portalId'              => AccountOptions::get_portal_id(),
			'accountName'           => AccountOptions::get_account_name(),
			'portalDomain'          => AccountOptions::get_portal_domain(),
			'portalEmail'           => get_user_meta( $wp_user_id, 'otisai_email', true ),
			'loginUrl'              => Links::get_login_url(),
			'routes'                => Links::get_routes_mapping(),
			'theme'                 => get_option( 'stylesheet' ),
			'wpVersion'             => Versions::get_wp_version(),
			'otisaiQueryParamsKeys' => array_keys( self::get_otis_query_params_array() ),
		);

		return $background_config;
	}

	/**
	 * Returns otisaiConfig, containing all the data needed by the otisai javascript.
	 */
	public static function get_otisai_config() {
		$wp_user_id = get_current_user_id();

		$otisai_config = \array_merge(
			self::get_background_otisai_config(),
			array(
				'iframeUrl' => Links::get_iframe_src(),
			)
		);

		return $otisai_config;
	}
}
