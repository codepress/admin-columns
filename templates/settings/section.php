<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<table class="ac-column-setting<?php echo $this->name ? esc_attr( ' ac-column-setting--' . $this->name ) : ''; ?>" data-setting="<?php echo esc_attr( $this->name ); ?>">
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

				<?php if( $this->instructions  ): ?>

					<a class="ac-pointer instructions" rel="pointer-export-5e39458051777" data-pos="right" data-pos_edge="" data-width="300">
						<img src="https://acp.test/wp-content/plugins/admin-columns-pro/admin-columns/assets/images/question.svg" alt="?" class="ac-setting-input__info">
					</a>
					<div id="pointer-smart-filtering-5e39458051777" style="display:none;">
						<?= $this->instructions; ?>
					</div>
				<?php endif; ?>

				<?php if ( $this->read_more ) : ?>
					<a href="<?php echo esc_url( $this->read_more ); ?>" target="_blank" class="more-link">
						<span class="dashicons dashicons-admin-links"></span>
					</a>
				<?php endif; ?>
			</label>
		</td>
		<td class="col-input">
			<?php if ( $this->setting ) : ?>
				<div class="ac-setting-input">
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