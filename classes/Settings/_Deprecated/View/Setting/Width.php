<?php

class AC_Settings_View_Setting_Width extends AC_Settings_ViewAbstract {

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