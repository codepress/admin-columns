<?php

/* @var AC_Notice $this */

$classes = 'ac-notice notice ' . $this->type;
$action = $this->is_dismissible() ? $this->get_name() : '';

?>
<div class="<?php echo esc_attr( $classes ); ?>" data-action="<?php echo esc_attr( $action ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'ac-ajax' ) ); ?>">
	<?php echo $this->message; ?>

	<?php if ( $this->is_dismissible() ) : ?>
		<a href="#" class="notice-dismiss" data-dismiss=""></a>
	<?php endif; ?>
</div>