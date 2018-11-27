<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @global array $items
 * @global string $current
 * @global string $screen_link
 */

$items = $this->items;

?>

<div class="menu">
	<form>

		<?php // todo: do we need nonce + page? ?>
		<?php wp_nonce_field( 'select-list-screen', '_ac_nonce', false ); ?>

		<input type="hidden" name="page" value="<?php echo esc_attr( \AC\Admin::MENU_SLUG ); ?>">

		<select name="list_screen" title="<?php esc_attr_e( 'Select type', 'codepress-admin-columns' ); ?>" id="ac_list_screen">
			<?php foreach ( $items as $group ) : ?>
				<optgroup label="<?php echo esc_attr( $group['title'] ); ?>">
					<?php foreach ( $group['options'] as $key => $label ) : ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $this->current ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</optgroup>
			<?php endforeach; ?>
		</select>
		<span class="spinner"></span>

		<?php if ( $this->screen_link ) : ?>
			<a href="<?php echo esc_url( $this->screen_link ); ?>" class="page-title-action view-link"><?php esc_html_e( 'View', 'codepress-admin-columns' ); ?></a>
		<?php endif; ?>
	</form>
</div>