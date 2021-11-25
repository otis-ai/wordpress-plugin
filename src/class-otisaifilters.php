<?php
namespace OtisAI;

/**
 * Class containing all the custom filters defined to be used instead of constants.
 */
class OtisAIFilters {
	/**
	 * Return the Meetotis domain.
	 */
	public static function get_otisai_domain() {
		return apply_filters( 'otisai_domain', 'meetotis.com' );
	}

	/**
	 * Apply otisai_base_url filter.
	 */
	public static function get_otisai_base_url() {
		$domain = self::get_otisai_domain();
		return apply_filters( 'otisai_base_url', "https://$domain" );
	}
}
