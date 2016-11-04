<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class AC_Settings_FormElement {

	private $attributes = array();

	protected $name;

	protected $id;

	protected $value = false;

	protected $default_value = false;

	public function set_attribute( $key, $value ) {
		$this->attributes[ $key ] = $value;
	}

	public function get_attribute( $key ) {
		$getter = 'get_' . $key;

		if ( method_exists( $this, $getter ) && $getter != __FUNCTION__ ) {
			return $this->$getter;
		}

		if ( ! isset( $this->attributes[ $key ] ) ) {
			return false;
		}

		return $this->attributes[ $key ];
	}

	public function get_attributes() {
		return $this->attributes;
	}

	/**
	 * Render an attribute
	 *
	 * @param $key
	 * @param bool $return Return or echo output
	 *
	 * @return null|string
	 */
	protected function attribute( $key, $return = false ) {
		$attribute = $this->get_attribute( $key );

		if ( ! $attribute ) {
			return;
		}

		$output = sprintf( '%s="%s"', $key, esc_attr( $attribute ) );

		if ( $return ) {
			return $output;
		}

		echo $output;
	}

	public function get_name() {
		return $this->name;
	}

	/**
	 * @param string $name
	 *
	 * @return $this
	 */
	public function set_name( $name ) {
		$this->name = $name;

		return $this;
	}

	public function get_id() {
		return $this->id;
	}

	/**
	 * @param string $id
	 *
	 * @return $this
	 */
	public function set_id( $id ) {
		$this->id = $id;

		return $this;
	}

	public function get_default_value() {
		return $this->default_value;
	}

	public function get_value() {
		$value = $this->value;

		if ( empty( $value ) ) {
			$value = $this->get_default_value();
		}

		// todo: add stripslashes? some fields have it
		return $value;
	}

	/**
	 * @param mixed $value
	 *
	 * @return $this
	 */
	public function set_value( $value ) {
		$this->value = $value;

		return $this;
	}

	public function set_class( $class ) {
		$this->set_attribute( 'class', $class );

		return $this;
	}

	public function add_class( $class ) {
		$parts = explode( ' ', (string) $this->get_attribute( 'class' ) );
		$parts[] = $class;

		$this->set_class( implode( ' ', $class ) );

		return $this;
	}

	protected function get_input() {

		switch ( $this->get_type() ) {
			case 'text':

		}

		$args = (object) wp_parse_args( $args, array(
			'option'        => '',
			'placeholder'   => '',
			'type'          => 'text',
			'default_value' => false,
			'value'         => false,
			'attr_name'     => '',
			'attr_id'       => '',
		) );

		$value = $args->value ? $args->value : $args->default_value;
		?>
		<input type="<?php echo esc_attr( $args->type ); ?>" name="<?php echo esc_attr( $args->attr_name ); ?>" id="<?php echo esc_attr( $args->attr_id ); ?>" value="<?php echo esc_attr( stripslashes( $value ) ); ?>"<?php echo $args->placeholder ? ' placeholder="' . esc_attr( $args->placeholder ) . '"' : ''; ?>/>
		<?php

	}

	/**
	 * Display the input
	 */
	public abstract function display();

	public function select( $args = array() ) {
		$defaults = array(
			'name'            => '',
			'options'         => array(),
			'grouped_options' => array(),
			'no_result'       => '',
			'default_value'   => '',
			'value'           => false,
			'attr_name'       => '',
			'attr_id'         => '',
		);

		$args = (object) wp_parse_args( (array) $args, $defaults );

		$value = $args->value ? $args->value : $args->default_value;

		if ( $args->options || $args->grouped_options ) : ?>
			<select name="<?php echo esc_attr( $args->attr_name ); ?>" id="<?php echo esc_attr( $args->attr_id ); ?>">
				<?php if ( $args->options ) : ?>
					<?php foreach ( $args->options as $key => $label ) : ?>
						<option value="<?php echo esc_attr( $key ); ?>"<?php selected( $key, $value ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				<?php elseif ( $args->grouped_options ) : ?>
					<?php foreach ( $args->grouped_options as $group ) : ?>
						<optgroup label="<?php echo esc_attr( $group['title'] ); ?>">
							<?php foreach ( $group['options'] as $key => $label ) : ?>
								<option value="<?php echo $key ?>"<?php selected( $key, $value ) ?>><?php echo esc_html( $label ); ?></option>
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
			'name'          => '',
			'options'       => array(),
			'default_value' => '',
			'value'         => false,
			'attr_name'     => '',
			'attr_id'       => '',
			'class'         => '',
			'vertical'      => false // display radio buttons vertical
		);
		$args = (object) wp_parse_args( (array) $args, $defaults );

		$value = $args->value ? $args->value : $args->default_value;

		if ( $args->options ) : ?>
			<div class="radio-labels<?php echo $args->vertical ? ' vertical' : ''; ?>">
				<?php foreach ( $args->options as $key => $label ) : ?>
					<label for="<?php echo esc_attr( $args->attr_id . '-' . $key ); ?>">
						<input type="radio"<?php echo $args->class ? ' class="' . esc_attr( $args->class ) . '"' : ''; ?> name="<?php echo esc_attr( $args->attr_name ); ?>" id="<?php echo esc_attr( $args->attr_id . '-' . $key ); ?>" value="<?php echo esc_attr( $key ); ?>"<?php checked( $key, $value ); ?>>
						<?php echo esc_html( $label ); ?>
					</label>
				<?php endforeach; ?>
			</div>
		<?php endif;
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
			'option'        => '',
			'placeholder'   => '',
			'type'          => 'text',
			'default_value' => false,
			'value'         => false,
			'attr_name'     => '',
			'attr_id'       => '',
		) );

		$value = $args->value ? $args->value : $args->default_value;
		?>
		<input type="<?php echo esc_attr( $args->type ); ?>" name="<?php echo esc_attr( $args->attr_name ); ?>" id="<?php echo esc_attr( $args->attr_id ); ?>" value="<?php echo esc_attr( stripslashes( $value ) ); ?>"<?php echo $args->placeholder ? ' placeholder="' . esc_attr( $args->placeholder ) . '"' : ''; ?>/>
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
		<span class="<?php echo esc_attr( $args['class'] ); ?>"><?php echo $args['text'] ?></span>
		<?php

	}
}