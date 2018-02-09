<?php
/**
 * @var AC_Notice_Dismissible $this
 */

// TODO
?>

<div class="ac-notice notice notice-<?php echo $this->get( 'type' ); ?> is-dismissible" data-name="<?php echo esc_attr( $this->get_name() ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'ac-ajax' ) ); ?>">
	<p>
		<?php echo $this->get( 'message' ); ?>
	</p>
	<div class="clear"></div>
</div>
