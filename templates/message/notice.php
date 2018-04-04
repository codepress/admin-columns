<?php

$dismissible_callback = $this->dismissible_callback
	? sprintf( 'data-dismissible-callback="%s"', esc_attr( $this->dismissible_callback ) )
	: '';

?>
<div class="ac-notice notice <?php echo esc_attr( $this->class ); ?>" <?php echo $dismissible_callback; ?>>
	<div class="ac-notice__body">
		<?php echo $this->message; ?>
	</div>

	<?php if ( $this->dismissible ) : ?>
		<a href="#" class="ac-notice__dismiss">
			<?php _e( 'dismiss' ); ?>
		</a>
	<?php endif; ?>
</div>