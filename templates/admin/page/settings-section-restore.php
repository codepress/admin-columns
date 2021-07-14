<form method="post">
	<?php wp_nonce_field( 'restore', '_ac_nonce' ); ?>
	<input type="hidden" name="ac_action" value="restore">
	<input type="submit" class="button" name="ac-restore-defaults" value="<?= esc_attr( __( 'Restore settings', 'codepress-admin-columns' ) ); ?>" onclick="return confirm('<?php echo esc_js( __( "Warning! ALL saved admin columns data will be deleted. This cannot be undone. 'OK' to delete, 'Cancel' to stop", 'codepress-admin-columns' ) ); ?>');">
</form>