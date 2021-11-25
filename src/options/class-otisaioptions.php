<?php

namespace OtisAI\options;

use OtisAI\wp\Options;

/**
 * Class that wraps calls to store and retrieve options prefixed by "otisai_".
 */
class OtisAIOptions extends Options {
	/**
	 * Class static declarations.
	 *
	 * @var String $prefix prefix added to option names.
	 */
	protected static $prefix = 'otisai';
}
