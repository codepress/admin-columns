<?php

declare(strict_types=1);

if ( ! defined('ABSPATH')) {
    exit;
}

?>
<div class="ac-notice notice is-dismissible <?= esc_attr($this->type . ' ' . $this->id); ?>" data-dismissible-callback="<?= esc_attr(wp_json_encode($this->dismissible_callback)); ?>">
	<div class="ac-notice__body">
        <?= $this->message; ?>
	</div>
</div>