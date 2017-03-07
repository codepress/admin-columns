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

					?>
					<?php foreach ( $options as $value ) : ?>
                        <label>
                            <input type="radio" name="<?php echo $input_name; ?>" value="<?php echo esc_attr( $value ); ?>" <?php checked( $this->date_format, $value ); ?>>
                            <span class="ac-setting-input-date__value"><?php echo date_i18n( $value, time() ); ?></span>
                            <code><?php echo $value; ?></code>
                        </label>
					<?php endforeach; ?>

                    <label class="ac-setting-input-more">
                        <input type="radio" name="<?php echo $input_name; ?>" value="custom" <?php checked( ! in_array( $this->date_format, $options ) ); ?>>
                        <span class="ac-setting-input-date__value"><?php _e( 'Custom:' ); ?></span>
						<?php echo $this->setting; ?>
                        <span class="ac-setting-input-date__example" data-date-example=""><?php echo date_i18n( $this->date_format, time() ); ?></span>
                        <p class="help-msg"><?php echo $this->description; ?></p>
                    </label>
                </div>
            </div>
        </td>
    </tr>
</table>