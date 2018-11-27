<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @global string $price
 */

?>
<div class="ac-modal -pro" id="ac-modal-pro">
	<div class="ac-modal__dialog -mascot">
		<div class="ac-modal__dialog__header">
			<?php _e( 'Do you like Admin Columns?', 'codepress-admin-columns' ); ?>
			<button class="ac-modal__dialog__close" data-dismiss="modal">
				<span class="dashicons dashicons-no"></span>
			</button>
		</div>
		<div class="ac-modal__dialog__content">
			<p class="ac-modal__dialog__content__lead">
				<?php _e( 'Upgrade to PRO, and take Admin Columns to the next level:', 'codepress-admin-columns' ); ?>
			</p>
			<ul class="ac-modal__dialog__list">
				<li><?php _e( 'Sort & Filter on all your content', 'codepress-admin-columns' ); ?></li>
				<li><?php _e( 'Directly edit your content from the overview', 'codepress-admin-columns' ); ?></li>
				<li><?php _e( 'Export all column data to CSV', 'codepress-admin-columns' ); ?></li>
				<li><?php _e( 'Create multiple column groups per overview', 'codepress-admin-columns' ); ?></li>
				<li><?php _e( 'Get add-ons for ACF, WooCommerce and many more', 'codepress-admin-columns' ); ?></li>
			</ul>
		</div>
		<div class="ac-modal__dialog__footer">
			<a class="button button-primary" target="_blank" href="<?php echo esc_url( ac_get_site_utm_url( 'admin-columns-pro', 'upgrade' ) ); ?>"><?php _e( 'Upgrade', 'codepress-admin-columns' ); ?></a>
			<span class="ac-modal__dialog__footer__content"><?php echo sprintf( __( 'Only %s for 1 site', 'codepress-admin-columns' ), '$' . $this->price ); ?></span>
			<svg class="ac-modal__dialog__mascot">
				<use xlink:href="<?php echo esc_url( AC()->get_url() ); ?>/assets/images/symbols.svg#zebra-thumbs-up"/>
			</svg>
		</div>
	</div>
</div>