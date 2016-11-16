<?php

class AC_Settings_View_Field_Width extends AC_Settings_ViewAbstract {

	// todo: maybe move to global scope with regex '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/'
	public function render() {
		foreach ( $this->elements as $element ) {
			$this->set( $element->get_name(), $element );
		}

		parent::render();
	}

	public function template() {
		if ( ! $this->width || ! $this->width_unit ) {
			return false;
		}

		?>

		<div class="description">
			<?php echo $this->width; ?>
			<span class="unit"><?php echo esc_html( $this->width_unit->get_value() ); ?></span>
		</div>
		<div class="width-slider"></div>

		<div class="unit-select">
			<?php echo $this->width_unit; ?>
		</div>

		<?php
	}

}