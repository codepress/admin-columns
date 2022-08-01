<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<table class="ac-column-setting<?= $this->name ? esc_attr( ' ac-column-setting--' . $this->name ) : ''; ?>" data-setting="<?= esc_attr( $this->name ); ?>">
	<tr>
		<td class="col-label">
			<label for="<?= esc_attr( $this->for ); ?>">
				<span class="label<?= esc_attr( $this->tooltip ? ' tooltip' : '' ); ?>">
					<?= $this->label; ?>
				</span>

				<?php if ( $this->tooltip ) : ?>
					<div class="tooltip">
						<?= $this->tooltip; ?>
					</div>
				<?php endif; ?>
			</label>
		</td>
		<td class="col-input">
			<div class="ac-setting-input ac-setting-input-date" data-custom-formats="<?= esc_attr( json_encode( $this->custom_date_formats ) ); ?>">
				<div class="radio-labels vertical">

					<?php foreach ( $this->date_options as $key => $label ) : ?>
						<?php
						$value = $key === 'custom'
							? $this->date_format
							: $key;
						?>
						<label>
							<input class="<?= esc_attr( $value ); ?>" type="radio" <?= $key === 'custom' ? 'data-custom' : '' ?> name="<?= $this->setting->get_name(); ?>" value="<?= esc_attr( $value ); ?>">
							<?= $label; ?>
						</label>
					<?php endforeach; ?>

				</div>
				<p class="help-msg"></p>
				<input type="hidden" class="ac-setting-input-date__value" data-value-input name="<?= $this->setting->get_name(); ?>" value="<?= $this->date_format; ?>">
			</div>
		</td>
	</tr>
</table>