<div class="ac-notice notice <?php echo esc_attr( $this->class ); ?>" data-dismissible="<?php echo esc_attr( $this->dismissible ); ?>">
	<div class="ac-notice__body">
		<?php echo $this->message; ?>
	</div>

	<?php if ( $this->dismissable ) : ?>
		<a href="#" class="ac-notice__dismiss">
			<?php _e( 'dismiss' ); ?>
		</a>
	<?php endif; ?>
</div>