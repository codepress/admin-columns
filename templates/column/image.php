<?php

declare(strict_types=1);
/**
 * @var string   $src
 * @var string   $class
 * @var string   $styles
 * @var string   $tooltip_attr
 * @var int|null $media_id
 */

?>

<span class="ac-image<?= $this->class ?>" data-media-id="<?= esc_attr((string)$this->media_id) ?>" <?= $this->tooltip_attr ?>>
	<img style="<?= $this->styles ?>" src="<?= esc_attr($this->src) ?>" alt="">
</span>
