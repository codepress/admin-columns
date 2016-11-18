<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
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