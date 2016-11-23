<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$description = $this->description ? 'description' : '';

?>

<table class="ac-column-setting" data-type="<?php echo esc_atr( $this->type ); ?>">
	<tr>
		<td class="col-label">
			<label for="<?php echo esc_attr( $this->for ); ?>">
				<span class="label <?php echo esc_attr( $description ); ?>">
					<?php echo $this->label; ?>
				</span>

				<?php if ( $this->description ) : ?>
					<div class="description">
						<?php echo $this->description; ?>
					</div>
				<?php endif; ?>

				<?php if ( $this->read_more ) : ?>
					<a title="<?php esc_attr_e( 'View more', 'codepress-admin-columns' ); ?>" href="<?php echo esc_url( $this->read_more ); ?>" target="_blank" class="more-link">
						<span class="dashicons dashicons-external"></span>
					</a>
				<?php endif; ?>
			</label>
		</td>
		<td class="col-settings">
			<?php if ( $this->setting ) : ?>
				<?php

				$setting_type = $this->type ? 'ac-settings-input_' . $this->type : '';

				?>
				<div class="ac-settings-input <?php echo esc_attr( $setting_type ); ?>">
					<?php echo $this->setting; ?>
				</div>
			<?php endif; ?>

			<?php

			if ( is_array( $this->sections ) ) {
				foreach ( $this->sections as $section ) {
					echo $section;
				}
			}

			?>
		</td>
	</tr>
</table>