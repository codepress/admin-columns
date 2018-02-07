<?php
/**
 * @var AC_Notice $this
 */
?>
<div class="ac-message <?php echo $this->get( 'type' ); ?>">
	<a class="ac-message-close notice-dismiss"><?php _e( 'Dismiss', 'codepress-admin-columns' ); ?></a>
	<p>
		<?php echo $this->get( 'message' ); ?>
	</p>
	<div class="clear"></div>
</div>