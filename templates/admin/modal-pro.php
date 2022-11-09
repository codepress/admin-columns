<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="ac-modal -pro" id="ac-modal-pro" style="display: none;">
	<div class="ac-modal__dialog -mascot">
		<div class="ac-modal__dialog__header">
			<?php _e( 'Do you like Admin Columns?', 'codepress-admin-columns' ); ?>
			<button class="ac-modal__dialog__close" data-dismiss="modal">
				<span class="dashicons dashicons-no-alt"></span>
			</button>
		</div>
		<div class="ac-modal__dialog__content">
			<p class="ac-modal__dialog__content__lead">
				<?php _e( 'Upgrade to PRO, and take Admin Columns to the next level:', 'codepress-admin-columns' ); ?>
			</p>
			<ul class="ac-modal__dialog__list">
				<li><?php _e( 'Sort & Filter on all your content.', 'codepress-admin-columns' ); ?></li>
				<li><?php _e( 'Search the contents of your columns.', 'codepress-admin-columns' ); ?></li>
				<li><?php _e( 'Bulk edit any content, including custom fields.', 'codepress-admin-columns' ); ?></li>
				<li><?php _e( 'Quick edit any content with Inline Editing, including custom fields.' ); ?></li>
				<li><?php _e( 'Export all column data to CSV.', 'codepress-admin-columns' ); ?></li>
				<li><?php _e( 'Create multiple list table views with different columns.', 'codepress-admin-columns' ); ?></li>
				<li><?php _e( 'Get add-ons for ACF, WooCommerce and many more', 'codepress-admin-columns' ); ?></li>
			</ul>
		</div>
		<div class="ac-modal__dialog__footer">
			<a class="button button-primary" target="_blank" href="<?= esc_url( $this->upgrade_url ); ?>"><?php _e( 'Upgrade', 'codepress-admin-columns' ); ?></a>
			<?php if ( $this->price ) : ?>
				<span class="ac-modal__dialog__footer__content"><?php echo sprintf( __( 'Only %s for 1 site', 'codepress-admin-columns' ), '$' . $this->price ); ?></span>
			<?php endif; ?>
			<svg class="ac-modal__dialog__mascot">
				<use xlink:href="<?php echo esc_url( AC()->get_url() ); ?>/assets/images/symbols.svg#zebra-thumbs-up"></use>
			</svg>
		</div>
	</div>
</div>