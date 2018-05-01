<table class="ac-column-setting ac-column-setting--pro <?php echo $this->name ? esc_attr( ' ac-column-setting--' . $this->name ) : ''; ?>" data-setting="<?php echo esc_attr( $this->name ); ?>">
	<tr>
		<td class="col-label">
			<label for="<?php echo esc_attr( $this->for ); ?>">
				<span class="label <?php echo esc_attr( $this->tooltip ? 'tooltip' : '' ); ?>">
					<?php echo $this->label; ?>
				</span>

				<?php if ( $this->tooltip ) : ?>
					<div class="tooltip">
						<?php echo $this->tooltip; ?>
					</div>
				<?php endif; ?>

				<?php if ( $this->read_more ) : ?>
					<a title="<?php esc_attr_e( 'View more', 'codepress-admin-columns' ); ?>" href="<?php echo esc_url( $this->read_more ); ?>" target="_blank" class="more-link">
						<span class="dashicons dashicons-external"></span>
					</a>
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