<table class="ac-column-setting <?php echo $this->name ? esc_attr( 'ac-column-setting--' . $this->name ) : ''; ?>" data-setting="<?php echo esc_attr( $this->name ); ?>">
	<tr>
		<td class="col-label">
			<label for="<?php echo esc_attr( $this->for ); ?>">
				<span class="label <?php echo esc_attr( $this->tooltip ? 'tooltip' : '' ); ?>">
					<?php echo $this->label; ?>
				</span>
			</label>
		</td>
		<td class="col-input">
			<div class="ac-setting-input ac-setting-input-date">
				<div class="radio-labels vertical">
					<?php
					$input_name = uniqid();
					$options = array( 'j F Y', 'Y-m-d', 'm/d/Y', 'd/m/Y' );
					$date_format = $this->date_format;
					?>

					<label class="ac-setting-input-diff">
						<input type="radio" name="<?php echo $input_name; ?>" value="diff" <?php checked( $date_format, 'diff' ); ?>>
						<span class="ac-setting-input-date__value"><?php _e( 'Readable time difference', 'codepress-admin-columns' ); ?><?php _e( '(example: 1 hour ago)', 'codepress-admin-columns' ); ?></span>
					</label>

					<?php foreach ( $options as $value ) : ?>
						<label>
							<input type="radio" name="<?php echo $input_name; ?>" value="<?php echo esc_attr( $value ); ?>" <?php checked( $date_format, $value ); ?>>
							<span class="ac-setting-input-date__value"><?php echo date_i18n( $value, time() ); ?></span>
							<code><?php echo $value; ?></code>
						</label>
					<?php endforeach; ?>

					<?php
					$example = ( 'diff' == $date_format ) ? '' : date_i18n( $date_format, time() );
					$display_format = ( 'diff' == $date_format ) ? '' : $date_format;
					?>
					<label class="ac-setting-input-more">
						<input type="radio" name="<?php echo $input_name; ?>" value="custom" <?php checked( ! in_array( $date_format, $options ) && 'diff' != $date_format ); ?>>
						<span class="ac-setting-input-date__value"><?php _e( 'Custom:' ); ?></span>

						<input type="text" name="<?php echo $input_name; ?>" value="<?php echo $display_format; ?>" class="display">
						<?php echo $this->setting; ?>

						<span class="ac-setting-input-date__example" data-date-example=""><?php echo $example; ?></span>
						<p class="help-msg"><?php echo $this->description; ?></p>
					</label>

				</div>
			</div>
		</td>
	</tr>
</table>