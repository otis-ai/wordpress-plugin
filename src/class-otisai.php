<?php

namespace OtisAI;

use \OtisAI\AssetsManager;
use \OtisAI\admin\OtisAIAdmin;

/**
 * Main class of the plugin.
 */
class OtisAI {
	/**
	 * Plugin's constructor. Everything starts here.
	 */
	public function __construct() {
		if ( ! $this->is_woocommerce_active() ) {
			$this->deactivate_plugin();
			add_action( 'admin_notices', array( $this, 'otisai_plugin_error_notice' ) );
		} else {
			if ( is_admin() ) {
				new OtisAIAdmin();
			}
		}
	}

	/**
	 * Determines if WooCommerce is active.
	 */
	public static function is_woocommerce_active() {

		$active_plugins = (array) get_option( 'active_plugins', array() );

		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}

		return in_array( 'woocommerce/woocommerce.php', $active_plugins, true ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins );
	}

	/**
	 * Deactivates the plugin.
	 */
	protected function deactivate_plugin() {

		deactivate_plugins( plugin_basename( OTISAI_BASE_PATH ) );

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
	}

	/**
	 * Error message if woocommerce is not active.
	 */
	public function otisai_plugin_error_notice() {
		?>
		<div class="error notice is-dismissible">
			<p>
				<?php esc_html_e( 'WooCommerce is not activated. Please activate WooCommerce first to use Otis using WooCommerce', 'otisai' ); ?>
			</p>
		</div>
		<?php
	}
}
