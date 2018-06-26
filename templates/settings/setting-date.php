<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<table class="ac-column-setting<?php echo $this->name ? esc_attr( ' ac-column-setting--' . $this->name ) : ''; ?>" data-setting="<?php echo esc_attr( $this->name ); ?>">
	<tr>
		<td class="col-label">
			<label for="<?php echo esc_attr( $this->for ); ?>">
				<span class="label<?php echo esc_attr( $this->tooltip ? ' tooltip' : '' ); ?>">
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
			<div class="ac-setting-input ac-setting-input-date">
				<div class="radio-labels vertical">

					<?php foreach ( $this->date_options as $value => $label ) : ?>
						<label>
							<input class="<?php echo esc_attr( $value ); ?>" type="radio" name="<?php echo $this->setting->get_name(); ?>" value="<?php echo esc_attr( $value ); ?>"<?php checked( $this->date_format, $value ); ?>>
							<?php echo $label; ?>
						</label>
					<?php endforeach; ?>

				</div>
				<p class="help-msg"></p>
				<input type="hidden" class="ac-setting-input-date__value" name="<?php echo $this->setting->get_name(); ?>" value="<?php echo $this->date_format; ?>">
			</div>
		</td>
	</tr>
</table>