<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="ac-setting-input ac-setting-input-width">
	<div class="description">
		<?php echo $this->width; ?>
		<span class="unit"><?php echo esc_html( $this->unit->get_value() ); ?></span>
	</div>
	<div class="width-slider"></div>

	<div class="unit-select">
		<?php echo $this->unit; ?>
	</div>
</div>