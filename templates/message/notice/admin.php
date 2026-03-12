<?php

declare(strict_types=1);

if ( ! defined('ABSPATH')) {
    exit;
}

$class = $this->type . ' ' . $this->id;
?>
<div class="ac-notice notice ac-message ac-message--styled ac-message--icon <?= $class ?>">
	<p><?= $this->message ?></p>
</div>