<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// todo: remove the grouped options, a nested array tells the tale
class AC_Settings_FormElement_Select extends AC_Helper_FormElement {

	protected $options;

	protected $grouped_options;

	protected $no_result;

	public function __construct() {
		$this->options = array();
		$this->grouped_options = array();
	}

	public function set_no_result( $no_result ) {
		$this->no_result = $no_result;

		return $this;
	}

	public function get_no_result() {
		$this->no_result;
	}

	public function set_options( array $options ) {
		$this->options = $options;

		return $this;
	}

	public function get_options() {
		return $this->options;
	}

	public function set_grouped_options( array $grouped_options ) {
		$this->options = $grouped_options;

		return $this;
	}

	public function get_grouped_options() {
		return $this->grouped_options;
	}

	public function display() {
		$options = $this->get_options();
		$grouped_options = $this->get_grouped_options();

		$value = $this->get_value();

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

		?>

		<?php if ( $options || $grouped_options ) : ?>

			<select <?php $this->attribute( 'name' ); ?> <?php $this->attribute( 'id' ); ?>>
				<?php if ( $options ) : ?>
					<?php foreach ( $options as $key => $label ) : ?>
						<option value="<?php echo esc_attr( $key ); ?>"<?php selected( $key, $value ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				<?php elseif ( $grouped_options ) : ?>
					<?php foreach ( $grouped_options as $group ) : ?>
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

		<?php elseif ( $this->get_no_result() ) :
			echo $this->get_no_result();
		endif;

	}

}