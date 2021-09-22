<?php
/**
 * @var string $feature
 * @var string $tooltip
 */
?>
<li class="ac-pro-list__item -feature">
	<span class="ac-pro-list__item__label"><?= $this->feature; ?></span>
	<?php if ( $this->tooltip ): ?>
		<span class="ac-pro-list__item__tooltip"><?= $this->tooltip; ?></span>
	<?php endif; ?>
</li>