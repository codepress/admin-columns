<?php

$attr_dismissible = '';

if ( $this->dismissible ) {
	$attr_dismissible = 'data-dismissible="' . esc_attr( json_encode( $this->dismissible ) ) . '"';
}

?>
<div class="ac-notice notice <?php echo esc_attr( $this->type ); ?>" <?php echo $attr_dismissible; ?>>
	<?php echo $this->message; ?>

	<?php if ( $this->dismissible ) : ?>
		<a href="#" class="notice-dismiss"></a>
	<?php endif; ?>
</div>