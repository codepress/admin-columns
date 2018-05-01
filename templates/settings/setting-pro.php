<table class="ac-column-setting ac-column-setting--pro <?php echo $this->name ? esc_attr( ' ac-column-setting--' . $this->name ) : ''; ?>" data-setting="<?php echo esc_attr( $this->name ); ?>">
	<tr>
		<td class="col-label">
			<label for="<?php echo esc_attr( $this->for ); ?>">
				<span class="label <?php echo esc_attr( $this->tooltip ? 'tooltip' : '' ); ?>">
					<span class="dashicons dashicons-info" data-ac-open-modal="#ac-modal-pro"></span>
					<?php echo $this->label; ?>
				</span>

				<?php if ( $this->tooltip ) : ?>
					<div class="tooltip">
						<?php echo $this->tooltip; ?>
					</div>
				<?php endif; ?>

			</label>
		</td>
		<td class="col-input">
			<?php if ( $this->setting ) : ?>
				<div class="ac-setting-input">
					<?php echo $this->setting; ?>
					<button class="acp-button" data-ac-open-modal="#ac-modal-pro">PRO</button>
				</div>

			<?php endif; ?>
		</td>
	</tr>
</table>