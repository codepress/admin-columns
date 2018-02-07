<?php
/**
 * @var AC_Notice_Dismissible $this
 */
?>

<div class="ac-notice notice notice-success is-dismissible" data-key="<?php echo esc_attr( $this->get_name() ); ?>">
	<p>
		<?php echo $this->get( 'message' ); ?>
	</p>
	<div class="clear"></div>
</div>
