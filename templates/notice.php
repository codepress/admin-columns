<?php

/* @var AC_Notice $this */

$classes = 'ac-notice notice notice-' . $this->type;

?>
<div class="<?php esc_attr( $classes ); ?>">
	<p>
		<?php echo $this->message; ?>
	</p>

	<?php if ( $this->is_dismissible() ) : ?>
		<a href="#" class="notice-dismiss" data-action="<?php echo esc_attr( $this->get_name() ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'ac-ajax' ) ); ?>"></a>
	<?php endif; ?>
</div>