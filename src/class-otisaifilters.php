<?php
namespace OtisAI;

/**
 * Class containing all the custom filters defined to be used instead of constants.
 */
class OtisAIFilters {
    /**
	 * Return the Meetotis domain.
	 * TODO : Change it back to the original domain
	 */
	public static function get_otisai_domain() {
		return apply_filters( 'otisai_domain', 'a5b7-182-65-135-4.ngrok.io' );
	}

    /**
	 * Apply otisai_base_url filter.
	 */
	public static function get_otisai_base_url() {
		$domain = self::get_otisai_domain();
		return apply_filters( 'otisai_base_url', "https://$domain" );
	}
}
