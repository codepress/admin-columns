<?php

/* @var AC_Notice $this */

$dismiss_action = $this->get_dismiss_action();
$classes = 'ac-notice notice notice-' . $this->type;

?>
<div class="<?php esc_attr( $classes ); ?>">
	<p>
		<?php echo $this->message; ?>
	</p>

	<?php if ( $dismiss_action ) : ?>
		<a href="#" class="notice-dismiss" data-action="<?php echo esc_attr( $dismiss_action ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'ac-ajax' ) ); ?>"></a>
	<?php endif; ?>
</div>