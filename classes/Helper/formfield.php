<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Helper_FormField {

	public function select( $args = array() ) {
		$defaults = array(
			'name'            => '',
			'options'         => array(),
			'grouped_options' => array(),
			'no_result'       => '',
			'default'         => '',
			'current'         => false,
			'attr_name'       => '',
			'attr_id'         => '',
		);

		$args = (object) wp_parse_args( (array) $args, $defaults );

		$current = $args->current ? $args->current : $args->default;

		if ( $args->options || $args->grouped_options ) : ?>
			<select name="<?php echo esc_attr( $args->attr_name ); ?>" id="<?php echo esc_attr( $args->attr_id ); ?>">
				<?php if ( $args->options ) : ?>
					<?php foreach ( $args->options as $key => $label ) : ?>
						<option value="<?php echo esc_attr( $key ); ?>"<?php selected( $key, $current ); ?>><?php echo esc_html( $label ); ?></option>
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
			echo esc_html( $args->no_result );
		endif;
	}

	/**
	 * @since NEWVERSION
	 */
	public function radio( $args ) {
		$defaults = array(
			'name'      => '',
			'options'   => array(),
			'default'   => '',
			'current'   => false,
			'attr_name' => '',
			'attr_id'   => '',
			'class'     => '',
			'vertical'  => false // display radio buttons vertical
		);
		$args = (object) wp_parse_args( (array) $args, $defaults );

		$current = $args->current ? $args->current : $args->default;

		foreach ( $args->options as $key => $label ) : ?>
			<label for="<?php echo esc_attr( $args->attr_id . '-' . $key ); ?>">
				<input type="radio"<?php echo $args->class ? ' class="' . esc_attr( $args->class ) . '"' : ''; ?> name="<?php echo esc_attr( $args->attr_name ); ?>" id="<?php echo esc_attr( $args->attr_id . '-' . $key ); ?>" value="<?php echo esc_attr( $key ); ?>"<?php checked( $key, $current ); ?>>
				<?php echo esc_html( $label ); ?>
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
	public function input( $args ) {
		$args = (object) wp_parse_args( $args, array(
			'option'      => '',
			'placeholder' => '',
			'type'        => 'text',
			'default'     => false,
			'current'     => false,
			'attr_name'   => '',
			'attr_id'     => '',
		) );

		$current = $args->current ? $args->current : $args->default;
		?>
		<input type="<?php echo esc_attr( $args->type ); ?>" name="<?php echo esc_attr( $args->attr_name ); ?>" id="<?php echo esc_attr( $args->attr_id ); ?>" value="<?php echo esc_attr( stripslashes( $current ) ); ?>"<?php echo $args->placeholder ? ' placeholder="' . esc_attr( $args->placeholder ) . '"' : ''; ?>/>
		<?php
	}

	/**
	 * @since NEWVERSION
	 */
	public function message( $args ) {
		$args = wp_parse_args( $args, array(
			'text'  => '',
			'class' => '',
		) );
		?>
		<span class="<?php echo esc_attr( $args->class ); ?>"><?php echo $args['text'] ?></span>
		<?php

	}
}