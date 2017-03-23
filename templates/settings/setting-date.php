<table class="ac-column-setting<?php echo $this->name ? esc_attr( ' ac-column-setting--' . $this->name ) : ''; ?>" data-setting="<?php echo esc_attr( $this->name ); ?>">
    <tr>
        <td class="col-label">
            <label for="<?php echo esc_attr( $this->for ); ?>">
				<span class="label<?php echo esc_attr( $this->tooltip ? ' tooltip' : '' ); ?>">
					<?php echo $this->label; ?>
				</span>
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

					<?php

                    /*
					$custom_current = false;

					switch ( $this->date_format ) {
						case 'diff' :
							$example = '';
							$display_format = '';

							break;
						case 'wp_default' :
							$example = date_i18n( get_option( 'date_format' ) );
							$display_format = get_option( 'date_format' );

							break;
						default :
							$example = date_i18n( $this->date_format, time() );
							$display_format = $this->date_format;

							if ( ! in_array( $this->date_format, array_keys( $this->date_options ) ) ) {
								$custom_current = true;
							}
					}


					?>
                    <label class="ac-setting-input-more">
                        <input type="radio" name="<?php echo $input_name; ?>" value="custom"<?php checked( $custom_current ); ?>>
                        <span class="ac-setting-input-date__value"><?php _e( 'Custom:' ); ?></span>
                        <input type="text" name="<?php echo $input_name; ?>" value="<?php echo $display_format; ?>" class="display">

						<?php echo $this->setting; ?>

                        <span class="ac-setting-input-date__example" data-date-example=""><?php echo $example; ?></span>
                        <p class="help-msg"><?php echo $this->description; ?></p>
                    </label>

                    <?php */ ?>

                </div>
            </div>
        </td>
    </tr>
</table>