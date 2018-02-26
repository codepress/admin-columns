<?php

final class AC_Plugin_Notice {

	/**
	 * @var string
	 */
	private $plugin_basename;

	/**
	 * @var string
	 */
	private $message;

	/**
	 * @var string
	 */
	private $class;

	/**
	 * @var string
	 */
	private $icon;

	/**
	 * @param string $plugin_basename
	 */
	public function __construct( $plugin_basename ) {
		$this->plugin_basename = $plugin_basename;
		$this->set_type( 'warning' );
	}

	/**
	 * Check if the plugin has an update available
	 *
	 * @return bool
	 */
	private function update_available() {
		$current = get_site_transient( 'update_plugins' );

		return isset( $current->response[ $this->plugin_basename ] );
	}

	public function register() {
		add_action( 'after_plugin_row_' . $this->plugin_basename, array( $this, 'display_notice' ), 11 );
	}

	public function display_notice() {
		$class = '';

		if ( is_plugin_active( $this->plugin_basename ) ) {
			$class .= ' active';

			if ( $this->update_available() ) {
				$class .= ' update';
			}
		}

		?>

		<style>
			.plugins tr[data-plugin='<?php echo $this->plugin_basename; ?>'] th,
			.plugins tr[data-plugin='<?php echo $this->plugin_basename; ?>'] td {
				box-shadow: none;
			}

			<?php if ( $this->icon ) : ?>

			.plugins tr[data-plugin='<?php echo $this->plugin_basename; ?>'] .update-message p:before {
				content: "<?php echo $this->icon ?>";
			}

			<?php endif; ?>
		</style>

		<tr class="plugin-update-tr <?php echo esc_attr( $class ); ?>" data-slug="<?php echo esc_attr( basename( $this->plugin_basename ) ); ?>" data-plugin="<?php echo esc_attr( $this->plugin_basename ); ?>">
			<td colspan="3" class="plugin-update colspanchange">
				<div class="update-message notice inline <?php echo esc_attr( $this->class ); ?>">
					<p><?php echo $this->message; ?></p>
				</div>
			</td>
		</tr>

		<?php
	}

	/**
	 * Set the message of this notice. Only links allowed, other HTML is escaped
	 *
	 * @param string $message
	 *
	 * @return $this
	 */
	public function set_message( $message ) {
		$this->message = wp_kses( $message, array(
			'strong' => array(),
			'br'     => array(),
			'a'      => array(
				'class' => true,
				'data'  => true,
				'href'  => true,
				'id'    => true,
				'title' => true,
			),
		) );

		return $this;
	}

	private function get_predefined_type( $type ) {
		$mapping = array(
			'warning' => 'notice-warning|\f348',
			'error'   => 'notice-error|\f534',
			'success' => 'updated-message notice-success|\f147',
			'update'  => 'notice-warning|\f463',
		);

		if ( array_key_exists( $type, $mapping ) ) {
			$parts = explode( '|', $mapping[ $type ] );

			return (object) array(
				'class' => $parts[0] . ' notice-alt',
				'icon'  => $parts[1],
			);
		}

		return false;
	}

	public function set_type( $type ) {
		$type = $this->get_predefined_type( $type );

		if ( $type ) {
			$this->set_class( $type->class );
			$this->set_icon( $type->icon );
		}

		return $this;
	}

	/**
	 * Set the color-scheme of the notice
	 *
	 * @param string $class
	 *
	 * @return $this
	 */
	public function set_class( $class ) {
		$type = $this->get_predefined_type( $class );

		if ( $type ) {
			$class = $type->class;
		}

		$this->class = $class;

		return $this;
	}

	/**
	 * Set the icon of the notice. Defaults to 'update'
	 *
	 * @param string $icon
	 *
	 * @return $this
	 */
	public function set_icon( $icon ) {
		$type = $this->get_predefined_type( $icon );

		if ( $type ) {
			$icon = $type->icon;
		}

		$this->icon = $icon;

		return $this;
	}

}