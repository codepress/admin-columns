<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="ac-message inline <?= esc_attr( $this->type ); ?>">
	<?= $this->message; ?>
</div>