<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Helper_FormElement_Radio extends AC_Helper_FormElement {

	protected $options;

	protected $vertical;

	public function __construct() {
		parent::__construct();

		$this->set_class( 'radio-labels' );
		$this->options = array();
	}

	public function set_vertical( $vertical ) {
		$this->vertical = (bool) $vertical;

		return $this;
	}

	public function is_vertical() {
		return $this->vertical;
	}

	public function set_options( array $options ) {
		$this->options = $options;

		return $this;
	}

	public function get_options() {
		return $this->options;
	}

	public function display() {
		$options = $this->get_options();

		if ( empty( $options ) ) {
			return;
		}

		$value = $this->get_value();

		if ( $this->is_vertical() ) {
			$this->add_class( 'vertical' );
		}

		?>

		<div <?php $this->attribute( 'class' ); ?>">

		<?php foreach ( $options as $key => $label ) : ?>
			<?php

			$input = new AC_Helper_FormElement_Input();
			$input->set_id( $this->get_id() . '-' . $key )
			      ->set_name( $this->get_name() )
			      ->set_value( $key )
			      ->set_type( 'radio' );

			if ( checked( $key, $value, false ) ) {
				$input->set_attribute( 'checked', 'checked' );
			}

			?>
			<label for="<?php echo esc_attr( $input->get_id() ); ?>">
				<?php

				$input->display();
				echo esc_html( $label );

				?>
			</label>
		<?php endforeach; ?>

		</div>

		<?php

	}

}