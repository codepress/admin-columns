<?php

if ( ! defined('ABSPATH')) {
    exit;
}

$class = $this->type . ' ' . $this->id;
?>
<div class="ac-notice notice ac-message ac-message--styled ac-message--icon <?= $class ?> notice-<?= $class ?>">
	<p><?= $this->message ?></p>
</div>