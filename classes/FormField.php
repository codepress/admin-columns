<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_FormField {

	/**
	 * @since NEWVERSION
	 */
	public function select( $args ) {
		$defaults = array(
			'name'            => '',
			'options'         => array(),
			'grouped_options' => array(),
			'no_result'       => '',
			'default'         => '',
			'current'         => false,
		);

		$args = (object) wp_parse_args( (array) $args, $defaults );

		$current = $args->current ? $args->current : $args->default;

		if ( $args->options || $args->grouped_options ) : ?>
			<select name="<?php $this->attr_name( $args->name ); ?>" id="<?php $this->attr_id( $args->name ); ?>">
				<?php if ( $args->options ) : ?>
					<?php foreach ( $args->options as $key => $label ) : ?>
						<option value="<?php echo $key; ?>"<?php selected( $key, $current ); ?>><?php echo $label; ?></option>
					<?php endforeach; ?>
				<?php elseif ( $args->grouped_options ) : ?>
					<?php foreach ( $args->grouped_options as $group ) : ?>
						<optgroup label="<?php echo esc_attr( $group['title'] ); ?>">
							<?php foreach ( $group['options'] as $key => $label ) : ?>
								<option value="<?php echo $key ?>"<?php selected( $key, $current ) ?>><?php echo esc_html( $label ); ?></option>
							<?php endforeach; ?>
						</optgroup>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>

			<?php //ajax message ?>
			<div class="msg"></div>
		<?php elseif ( $args->no_result ) :
			echo $args->no_result;
		endif;
	}

	/**
	 * @since NEWVERSION
	 */
	public function radio( $args ) {
		$defaults = array(
			'name'     => '',
			'options'  => array(),
			'default'  => '',
			'vertical' => false // display radio buttons vertical
		);

		$args = (object) wp_parse_args( (array) $args, $defaults );

		$current = $this->get_option( $args->name );
		if ( ! $current ) {
			$current = $args->default;
		}

		foreach ( $args->options as $key => $label ) : ?>
			<label>
				<input type="radio" name="<?php $this->attr_name( $args->name ); ?>" id="<?php $this->attr_id( $args->name . '-' . $key ); ?>" value="<?php echo $key; ?>"<?php checked( $key, $current ); ?>>
				<?php echo $label; ?>
			</label>
			<?php echo $args->vertical ? '<br/>' : ''; ?>
		<?php endforeach;
	}

	/**
	 * @since NEWVERSION
	 */
	public function text( $args ) {
		$args = wp_parse_args( $args, array(
			'type' => 'text',
		) );

		$this->input( $args );
	}

	/**
	 * @since NEWVERSION
	 */
	public function number( $args ) {
		$args = wp_parse_args( $args, array(
			'type' => 'number',
		) );

		$this->input( $args );
	}

	/**
	 * @since NEWVERSION
	 */
	private function input( $args ) {
		$args = (object) wp_parse_args( $args, array(
			'name'        => '',
			'placeholder' => '',
			'type'        => 'text',
		) ); ?>
		<input type="<?php echo esc_attr( $args->type ); ?>" name="<?php $this->attr_name( $args->name ); ?>" id="<?php $this->attr_id( $args->name ); ?>" value="<?php echo esc_attr( stripslashes( $this->get_option( $args->name ) ) ); ?>"<?php echo $args->placeholder ? ' placeholder="' . esc_attr( $args->placeholder ) . '"' : ''; ?>/>
		<?php
	}

	/**
	 * @since NEWVERSION
	 */
	public function width() {
		?>
		<div class="description" title="<?php _e( 'default', 'codepress-admin-columns' ); ?>">
			<input class="width" type="text" placeholder="<?php _e( 'auto', 'codepress-admin-columns' ); ?>" name="<?php $this->attr_name( 'width' ); ?>" id="<?php $this->attr_id( 'width' ); ?>" value="<?php echo $this->get_option( 'width' ); ?>"/>
			<span class="unit"><?php echo $this->get_option( 'width_unit' ); ?></span>
		</div>
		<div class="width-slider"></div>

		<div class="unit-select">
			<label for="<?php $this->attr_id( 'width_unit_px' ); ?>">
				<input type="radio" class="unit" name="<?php $this->attr_name( 'width_unit' ); ?>" id="<?php $this->attr_id( 'width_unit_px' ); ?>" value="px"<?php checked( $this->get_option( 'width_unit' ), 'px' ); ?>/>px
			</label>
			<label for="<?php $this->attr_id( 'width_unit_perc' ); ?>">
				<input type="radio" class="unit" name="<?php $this->attr_name( 'width_unit' ); ?>" id="<?php $this->attr_id( 'width_unit_perc' ); ?>" value="%"<?php checked( $this->get_option( 'width_unit' ), '%' ); ?>/>%
			</label>
		</div>
		<?php
	}

}