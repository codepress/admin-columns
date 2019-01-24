<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div id="pointer-<?php echo esc_attr( $this->id ); ?>" style="display:none;">
	<h3><?php echo esc_html( $this->title ); ?></h3>
	<?php echo $this->content; ?>
</div>