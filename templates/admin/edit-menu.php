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
		<input type="hidden" name="page" value="<?php echo esc_attr( \AC\Admin::PLUGIN_PAGE ); ?>">

		<?php
		$select = new \AC\Form\Element\Select( 'list_screen', $items );

		$select->set_value( $this->current )
			   ->set_attribute( 'title', __( 'Select type', 'codepress-admin-columns' ) )
			   ->set_attribute( 'id', 'ac_list_screen' );

		echo $select->render();

		?>

		<span class="spinner"></span>

		<?php if ( $this->screen_link ) : ?>
			<a href="<?php echo esc_url( $this->screen_link ); ?>" class="page-title-action view-link"><?php esc_html_e( 'View', 'codepress-admin-columns' ); ?></a>
		<?php endif; ?>
	</form>
</div>