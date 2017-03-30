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
			'icon'    => null,

			// filename
			'plugin'  => '',
		);

		$data = (object) wp_parse_args( $args, $defaults );

		$classes = array( 'notice', 'inline' );

		if ( $data->class ) {
			$classes[] = $data->class;
		}

		switch ( $data->icon ) {
			case 'info': {
				$data->icon = '\f348';

				break;
			}
		}

		$row_classes = array();

		if ( $data->plugin && is_plugin_active( $data->plugin ) ) {
			$row_classes[] = 'active';

			if ( $this->plugin_has_update( $data->plugin ) ) {
				$row_classes[] = 'update';
			}
		}

		// set styling
		$tr_plugin = ".plugins tr[data-plugin='$data->plugin']";

		$tpl = '
			%1$s th,
			%1$s td {
				padding-bottom: 0;
				box-shadow: none;
			}';

		$css = sprintf( $tpl, $tr_plugin );

		if ( $data->icon ) {
			$tpl = '
				%s .update-message p:before { 
					content: "%s"; 
				}';

			$css .= sprintf( $tpl, $tr_plugin, $data->icon );
		}

		?>

		<tr class="plugin-update-tr <?php echo esc_attr( implode( ' ', $row_classes ) ); ?>" data-slug="<?php echo esc_attr( basename( $data->plugin ) ); ?>" data-plugin="<?php echo esc_attr( $data->plugin ); ?>">
			<td colspan="3" class="plugin-update colspanchange">
				<div class="update-message <?php echo esc_attr( implode( ' ', $classes ) ); ?>">
					<p><?php echo $data->message; ?></p>
				</div>
			</td>
		</tr>

		<style>
			<?php echo $css; ?>
		</style>

		<?php
	}

}