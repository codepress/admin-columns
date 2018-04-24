<?php

/**
 * Show a notice when plugin dependencies are not met
 *
 * @version 1.4
 */
final class AC_Dependencies {

	/**
	 * Missing dependency messages
	 *
	 * @var string[]
	 */
	private $messages;

	/**
	 * Basename of this plugin
	 *
	 * @var string
	 */
	private $basename;

	/**
	 * @var string Minimal required version of Admin Columns Pro
	 */
	private $acp_version;

	/**
	 * @param string $basename Plugin basename
	 */
	public function __construct( $basename ) {
		$this->messages = array();
		$this->basename = $basename;

		$this->load_data();
	}

	/**
	 * Register hooks
	 */
	private function register() {
		add_action( 'after_plugin_row_' . $this->basename, array( $this, 'display_notice' ), 5 );
		add_action( 'admin_head', array( $this, 'display_notice_css' ) );
	}

	/**
	 * Increments the version if it's higher than the
	 *
	 * @param string $version
	 *
	 * @return $this
	 */
	public function increment_acp_version( $version ) {
		if ( version_compare( $version, $this->acp_version, '>=' ) ) {
			$this->acp_version = $version;
		}

		return $this;
	}

	/**
	 * Load dependency parameters set outside this plugin
	 */
	private function load_data() {
		$data = get_option( 'ac_dependency_data', array() );

		foreach ( $data as $k => $v ) {
			if ( 0 !== strpos( $k, dirname( $this->basename ) ) ) {
				continue;
			}

			if ( isset( $v['acp_version'] ) ) {
				$this->increment_acp_version( $v['acp_version'] );
			}

			break;
		}
	}

	/**
	 * Add missing dependency
	 *
	 * @param string $message
	 */
	public function add_missing( $message ) {
		// Register on first missing dependency
		if ( ! $this->messages ) {
			$this->register();
		}

		$this->messages[] = $message;
	}

	/**
	 * @return bool
	 */
	public function has_missing() {
		return ! empty( $this->messages );
	}

	/**
	 * Add missing dependency
	 *
	 * @param string $plugin
	 * @param string $url
	 * @param string $version
	 */
	public function add_missing_plugin( $plugin, $url = null, $version = null ) {
		$plugin = esc_html( $plugin );

		if ( $url ) {
			$plugin = sprintf( '<a href="%s">%s</a>', esc_url( $url ), $plugin );
		}

		if ( $version ) {
			$plugin .= ' (' . sprintf( __( 'version %s or later', 'codepress-admin-columns' ), esc_html( $version ) ) . ')';
		}

		$message = sprintf( __( '%s needs to be installed and activated.', 'codepress-admin-columns' ), $plugin );

		$this->add_missing( $message );
	}

	/**
	 * Check if Admin Columns Pro is installed and meets the minimum required version
	 *
	 * @param string $version
	 *
	 * @return bool
	 */
	public function check_acp( $version ) {
		$this->increment_acp_version( $version );

		if ( function_exists( 'ACP' ) && ACP()->is_version_gte( $version ) ) {
			return true;
		}

		$this->add_missing_plugin( 'Admin Column Pro', 'https://www.admincolumns.com', $this->acp_version );

		return false;
	}

	/**
	 * Check current PHP version
	 *
	 * @param string $version
	 *
	 * @return bool
	 */
	public function check_php_version( $version ) {
		if ( version_compare( PHP_VERSION, $version, '>=' ) ) {
			return true;
		}

		$documentation_url = 'https://www.admincolumns.com/documentation/getting-started/requirements/';

		$parts[] = sprintf( __( 'This plugin requires at least PHP %s to function properly.', 'codepress-admin-columns' ), $version );
		$parts[] = sprintf( __( 'Your server currently runs PHP %s.', 'codepress-admin-columns' ), PHP_VERSION );
		$parts[] = sprintf( __( 'Read more about <a href="%s" target="_blank">requirements</a>a> in our documentation.', 'codepress-admin-columns' ), esc_url( $documentation_url ) );

		$this->add_missing( implode( ' ', $parts ) );

		return false;
	}

	/**
	 * URL that performs a search in the WordPress repository
	 *
	 * @param string $keywords
	 *
	 * @return string
	 */
	public function get_search_url( $keywords ) {
		$url = add_query_arg( array(
			'tab' => 'search',
			's'   => $keywords,
		), admin_url( 'plugin-install.php' ) );

		return $url;
	}

	/**
	 * Show a warning when dependencies are not met
	 */
	public function display_notice() {
		?>

		<tr class="plugin-update-tr active">
			<td colspan="3" class="plugin-update colspanchange">
				<div class="update-message notice inline notice-error notice-alt">
					<p>
						<?php _e( 'This plugin failed to load:', 'codepress-admin-columns' ); ?>
					</p>

					<ul>
						<?php foreach ( $this->messages as $message ) : ?>
							<li><?php echo $message; ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</td>
		</tr>

		<?php
	}

	/**
	 * Load additional CSS for the warning
	 */
	public function display_notice_css() {
		?>

		<style>
			.plugins tr[data-plugin='<?php echo $this->basename; ?>'] th,
			.plugins tr[data-plugin='<?php echo $this->basename; ?>'] td {
				box-shadow: none;
			}
		</style>

		<?php
	}

}