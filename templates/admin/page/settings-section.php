<?php

use AC\Ajax;

?>
<section class="ac-settings-box">
	<h2 class="ac-lined-header"><?= $this->title; ?></h2>

	<?php wp_nonce_field( Ajax\Handler::NONCE_ACTION ); ?>

	<?php if ( $this->description ) : ?>
		<p><?= $this->description; ?></p>
	<?php endif; ?>

	<?= $this->content; ?>

</section>
