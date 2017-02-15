<?php

class AC_Notice_Plugin {

	private function plugin_has_update( $file ) {
		$current = get_site_transient( 'update_plugins' );

		return isset( $current->response[ $file ] );
	}

	public function display( $args = array() ) {
		$defaults = array(
			'message' => '',

			// yellow: notice-warning
			// yellow with white background: notice-warning.notice-alt
			// red: notice-error
			// blue: notice-info
			// green: notice-success
			'class'   => 'notice-warning notice-alt',
			'icon'    => 'info',

			// filename
			'plugin'  => '',
		);

		$args = wp_parse_args( $args, $defaults );

		$data = (object) $args;

		$classes = array( 'notice', 'inline' );

		if ( $data->class ) {
			$classes[] = $data->class;
		}

		if ( $data->icon ) {
			$classes[] = 'icon';
			$classes[] = 'icon-' . $data->icon;
		}

		$row_classes = array();

		if ( $data->plugin && is_plugin_active( $data->plugin ) ) {
			$row_classes[] = 'active';

			if ( $this->plugin_has_update( $data->plugin ) ) {
				$row_classes[] = 'update';
			}
		}

		?>
		<tr class="plugin-update-tr plugin-update-tr-admin-columns <?php echo esc_attr( implode( ' ', $row_classes ) ); ?>" data-slug="<?php echo esc_attr( basename( $data->plugin ) ); ?>" data-plugin="<?php echo esc_attr( $data->plugin ); ?>">
			<td colspan="3" class="plugin-update colspanchange">
				<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
					<p><?php echo $data->message; ?></p>
				</div>
			</td>
		</tr>

		<style>
			.plugins tr[data-plugin='<?php echo $data->plugin; ?>'] th,
			.plugins tr[data-plugin='<?php echo $data->plugin; ?>'] td {
				padding-bottom: 0;
				box-shadow: none;
			}
		</style>
		<?php

		wp_enqueue_style( 'ac-plugin-row', AC()->get_plugin_url() . 'assets/css/plugin-screen' . AC()->minified() . '.css' );
	}

}