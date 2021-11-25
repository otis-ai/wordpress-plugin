<?php

namespace OtisAI\admin;

use OtisAI\AssetsManager;
use OtisAI\admin\Connection;
use OtisAI\admin\AdminFilters;
use OtisAI\admin\MenuConstants;
use OtisAI\admin\NoticeManager;
use OtisAI\utils\Versions;
use OtisAI\admin\Links;

/**
 * Class responsible for initializing the admin side of the plugin.
 */
class OtisAIAdmin {
	const REDIRECT_TRANSIENT = 'otisai_redirect_after_activation';

	/**
	 * Class constructor, adds all the hooks and instantiate the APIs.
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'redirect_after_activation' ) );
		add_action( 'admin_head', array( $this, 'collapse_menu' ) );
		add_filter( 'allowed_redirect_hosts', array( $this, 'otisai_allowed_redirect_hosts' ) );
		add_action( 'admin_menu', array( $this, 'build_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		register_activation_hook( OTISAI_BASE_PATH, array( $this, 'do_activate_action' ) );

		/**
		 * The following hooks are public APIs.
		 */
		add_action( 'otisai_redirect', array( $this, 'set_redirect_transient' ) );
		add_action( 'otisai_activate', array( $this, 'do_redirect_action' ), 100 );

		new NoticeManager();
		new AdminFilters();
	}

	/**
	 * Adding otis domain to the allowed hosts.
	 */
	public function otisai_allowed_redirect_hosts( $hosts ) {
		$my_hosts = array(
			'meetotis.com',
		);
		return array_merge( $hosts, $my_hosts );
	}

	/**
	 * Handler called on plugin activation.
	 */
	public function do_activate_action() {
		\do_action( 'otisai_activate' );
	}

	/**
	 * Handler for the otisai_activate action.
	 */
	public function do_redirect_action() {
		\do_action( 'otisai_redirect' );
	}

	/**
	 * Set transient after activating the plugin.
	 */
	public function set_redirect_transient() {
		set_transient( self::REDIRECT_TRANSIENT, true, 60 );
	}

	/**
	 * Redirect to the dashboard after activation.
	 */
	public function redirect_after_activation() {
		if ( get_transient( self::REDIRECT_TRANSIENT ) ) {
			delete_transient( self::REDIRECT_TRANSIENT );
			wp_safe_redirect( admin_url( 'admin.php?page=otisai' ) );
			exit;
		}
	}

	/**
	 * Collapse side bar menu after redirect
	 */
	public function collapse_menu() {
		$current_screen = get_current_screen();

		if ( strpos( $current_screen->id, 'otisai' ) !== false ) {
			if ( 'f' !== get_user_setting( 'mfold' ) ) {
				set_user_setting( 'mfold', 'f' );
			}
		} else {
			set_user_setting( 'mfold', 'o' );
		}
	}

	/**
	 * Adds scripts for the admin section.
	 */
	public function enqueue_scripts() {
		AssetsManager::register_assets();
		AssetsManager::enqueue_admin_assets();
	}

	/**
	 * Adds OtisAI menu to admin sidebar
	 */
	public function build_menu() {
		add_menu_page( __( 'Otis', 'otisai' ), __( 'Otis', 'otisai' ), AdminFilters::apply_connect_plugin_capability(), MenuConstants::ROOT, array( $this, 'build_app' ), 'dashicons-marker', '25.2' );
	}

	/**
	 * Renders the otisai admin page.
	 */
	public function build_app() {
		AssetsManager::enqueue_bridge_assets();

		$error_message = '';

		if ( Versions::is_php_version_supported() ) {
			$error_message = sprintf(
				__( 'Otis AI - Digital marketing for small business %1$s requires PHP %2$s or higher. Please upgrade WordPress first.', 'otisai' ),
				OTISAI_PLUGIN_VERSION,
				OTISAI_REQUIRED_PHP_VERSION
			);
		} elseif ( Versions::is_wp_version_supported() ) {
			$error_message = sprintf(
				__( 'Otis AI - Digital marketing for small business %1$s requires PHP %2$s or higher. Please upgrade WordPress first.', 'otisai' ),
				OTISAI_PLUGIN_VERSION,
				OTISAI_REQUIRED_WP_VERSION
			);
		}

		if ( $error_message ) {
			?>
				<div class='notice notice-warning'>
					<p>
						<?php echo esc_html( $error_message ); ?>
					</p>
				</div>
			<?php
		} else {
			$external_link = Links::get_iframe_src();
			wp_safe_redirect( $external_link, 302 );
			exit;
		}
	}
}
