<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! $this->message ) {
	return false;
}

?>

<span class="message"><?php echo $this->message; ?></span>