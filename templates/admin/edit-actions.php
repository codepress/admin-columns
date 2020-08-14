<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * @global string $label_main
 * @global string $label_second
 * @global string $list_screen_key
 * @global string $list_screen_id
 * @global string $delete_confirmation_message
 */

?>

<div class="sidebox form-actions">
	<h3>
		<span class="left"><?php echo esc_html( $this->label_main ); ?></span>
		<?php echo $this->label_second; ?>
	</h3>

	<div class="form-update">
		<a class="button-primary submit update"><?php _e( 'Update' ); ?></a>
		<a class="button-primary submit save"><?php _e( 'Save' ); ?></a>
	</div>

	<form class="form-reset" method="post">
		<input type="hidden" name="list_screen" value="<?php echo esc_attr( $this->list_screen_key ); ?>"/>
		<input type="hidden" name="layout" value="<?php echo esc_attr( $this->list_screen_id ); ?>"/>
		<input type="hidden" name="action" value="restore_by_type"/>
		<input type="hidden" name="_ac_nonce" value="<?= wp_create_nonce( 'restore-type' ); ?>"/>

		<?php if ( $this->delete_confirmation_message ) : ?>
			<input class="reset-column-type" type="submit" value="<?php _e( 'Restore columns', 'codepress-admin-columns' ); ?>"
					onclick="return confirm( '<?php echo esc_js( $this->delete_confirmation_message ); ?>'); ">
		<?php else : ?>
			<input class="reset-column-type" type="submit" value="<?php _e( 'Restore columns', 'codepress-admin-columns' ); ?>">
		<?php endif; ?>

		<small class="list-screen-type"><?= sprintf( '%s: %s', __( 'Key', 'codepress-admin-columns' ), $this->list_screen_key ); ?></small>
		<small class="list-screen-id"><?= sprintf( '%s: %s', __( 'ID', 'codepress-admin-columns' ), $this->list_screen_id ); ?></small>

		<span class="spinner"></span>
	</form>

</div>