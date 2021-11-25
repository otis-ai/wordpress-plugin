<?php

namespace OtisAI\admin;

use OtisAI\wp\User;
use OtisAI\admin\Connection;

/**
 * Class responsible for rendering the admin notices.
 */
class NoticeManager {
	/**
	 * Class constructor, adds the necessary hooks.
	 */
	public function __construct() {
		add_action( 'admin_notices', array( $this, 'otisai_action_required_notice' ) );
	}

	/**
	 * Render the promotion banner.
	 */
	private function otisai_render_promotion_banner() {
		?>
		<div class="notice is-dismissible otis-promotion-notice">
			<div class="thumbnail">
				<img src="<?php echo esc_attr( OTISAI_PATH . '/assets/img/otis-icon.png' ); ?>" alt="Integrate Otis AI to get the best out of your store" class="">
			</div>
			<div class="content">
				<h2 class=""><?php esc_html_e( 'Integrate Otis to get the best out of this store', 'otisai' ); ?></h2>
				<p><?php esc_html_e( 'Use digital marketing to stand above the crowd. Our in house features are designed to boost your store audience.', 'otisai' ); ?></p>
				<a href="https://meetotis.com/#howitworks" class="button button-primary promo-btn" target="_blank"><?php esc_html_e( 'Learn More', 'otisai' ); ?> →</a>
			</div>
			<div class="clear"></div>
		</div>
		<?php
	}

	/**
	 * Find what notice (if any) needs to be rendered
	 */
	public function otisai_action_required_notice() {
		$current_screen = get_current_screen();

		if ( 'otisai' !== $current_screen->parent_base ) {
			if ( ! Connection::is_connected() && User::is_admin() ) {
				$this->otisai_render_promotion_banner();
			}
		}
	}
}
