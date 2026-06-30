<?php

declare(strict_types=1);

if ( ! defined('ABSPATH')) {
    exit;
}

?>
<div class="ac-notice notice <?= esc_attr($this->type . ' ' . $this->id); ?>">
	<div class="ac-notice__body">
        <?= $this->message ?>
	</div>
</div>