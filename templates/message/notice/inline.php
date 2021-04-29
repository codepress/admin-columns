<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="ac-message inline <?= esc_attr( $this->class ); ?>">
	<?= $this->message; ?>
</div>