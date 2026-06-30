<?php

declare(strict_types=1);
/**
 * @var string   $src
 * @var int      $width
 * @var int      $height
 * @var int|null $media_id
 */

?>

<span class="ac-image -cover" data-media-id="<?= esc_attr((string)$this->media_id) ?>">
	<img style="width:<?= esc_attr((string)$this->width) ?>px;height:<?= esc_attr((string)$this->height) ?>px;" src="<?= esc_attr($this->src) ?>" alt="">
</span>
