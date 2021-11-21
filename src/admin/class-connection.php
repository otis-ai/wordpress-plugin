<?php

namespace OtisAI\admin;

use OtisAI\options\AccountOptions;

/**
 * Handles portal connection to the plugin.
 */
class Connection {

	const CONNECT_KEYS = array(
		'access_token',
		'refresh_token',
		'expires_in',
		'portal_id',
		'portal_domain',
		'portal_name',
	);

	const CONNECT_NONCE_ARG    = 'otisai_connect';
	const DISCONNECT_NONCE_ARG = 'otisai_disconnect';

	/**
	 * Returns true if a portal has been connected to the plugin
	 */
	public static function is_connected() {
		return ! empty( AccountOptions::get_portal_id() );
	}
}
