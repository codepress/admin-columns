<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @global \AC\Column $column
 */

$column = $this->column;

?>

	<div class="ac-column ac-<?php echo esc_attr( $column->get_type() ); ?>"
			data-type="<?php echo esc_attr( $column->get_type() ); ?>"
			data-original="<?php echo esc_attr( $column->is_original() ); ?>"
			data-column-name="<?php echo esc_attr( $column->get_name() ); ?>">

		<div class="ac-column-header">
			<table class="widefat">
				<tbody>
				<tr>
					<td class="column_sort">
						<span class="cpacicon-move"></span>
					</td>
					<td class="column_label">
						<div class="inner">
							<div class="meta">
								<?php

								foreach ( $column->get_settings() as $setting ) {
									if ( $setting instanceof \AC\Settings\Header ) {
										echo $setting->render_header() . "\n";
									}
								}

								/**
								 * Fires in the meta-element for column options, which is displayed right after the column label
								 * @since 2.0
								 *
								 * @param \AC\Column $column_instance Column class instance
								 */
								do_action( 'ac/column/header', $column );

								?>
							</div>
							<a class="toggle" data-toggle="column">
								<?php echo $column->get_setting( 'label' )->get_value(); // do not escape ?>
							</a>
							<a class="edit-button" data-toggle="column"><?php _e( 'Edit', 'codepress-admin-columns' ); ?></a>
							<a class="close-button" data-toggle="column"><?php _e( 'Close', 'codepress-admin-columns' ); ?></a>
							<?php if ( ! $column->is_original() ) : ?>
								<a class="clone-button" href="#"><?php _e( 'Clone', 'codepress-admin-columns' ); ?></a>
							<?php endif; ?>
							<a class="remove-button"><?php _e( 'Remove', 'codepress-admin-columns' ); ?></a>
						</div>
					</td>
					<td class="column_type">
						<div class="inner" data-toggle="column">
							<?php echo ac_helper()->html->strip_attributes( $column->get_label(), array( 'style', 'class' ) ); ?>
						</div>
					</td>
					<td class="column_edit" data-toggle="column">
					</td>
				</tr>
				</tbody>
			</table>
		</div>

		<div class="ac-column-body">
			<div class="ac-column-settings">

				<?php

				foreach ( $column->get_settings() as $setting ) {
					echo $setting->render() . "\n";
				}

				?>

				<table class="ac-column-setting ac-column-setting-actions">
					<tr>
						<td class="col-label"></td>
						<td class="col-settings">
							<p>
								<a href="#" class="close-button" data-toggle="column"><?php _e( 'Close', 'codepress-admin-columns' ); ?></a>
								<?php if ( ! $column->is_original() ) : ?>
									<a class="clone-button" href="#"><?php _e( 'Clone', 'codepress-admin-columns' ); ?></a>
								<?php endif; ?>
								<a href="#" class="remove-button"><?php _e( 'Remove' ); ?></a>
							</p>
						</td>
					</tr>

				</table>
			</div>
		</div>
	</div>