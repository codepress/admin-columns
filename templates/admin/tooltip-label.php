<?php

declare(strict_types=1);

if ( ! defined('ABSPATH')) {
    exit;
}

?>

<a class="ac-pointer instructions" rel="pointer-<?= esc_attr($this->id); ?>" data-pos="<?= esc_attr($this->position); ?>" data-width="300">
    <?= $this->label; ?>
</a>