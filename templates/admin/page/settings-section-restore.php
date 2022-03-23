<form method="post" id="frm-ac-restore">
	<?php wp_nonce_field( 'restore', '_ac_nonce' ); ?>
	<input type="hidden" name="ac_action" value="restore">
	<input type="submit" class="button" name="ac-restore-defaults" value="<?= esc_attr( __( 'Restore settings', 'codepress-admin-columns' ) ); ?>">
</form>