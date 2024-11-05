<?php
/**
 * @var string $background_color
 * @var string $color
 * @var string $label
 */

?>

<div class="cpac-color">
	<span style="background-color:<?= esc_attr($this->background_color) ?>;color:<?= esc_attr($this->color) ?>;">
		<?= $this->label ?>
	</span>
</div>