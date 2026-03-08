<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$product = __( 'Admin Columns', 'codepress-admin-columns' );

?>

<div class="info">
	<p>
		<?php
		printf(
			__(
				"We don't mean to bug you, but you've been using %s for some time now, and we were wondering if you're happy with the plugin. If so, could you please leave a review at wordpress.org? If you're not happy with %s, please %s.",
				'codepress-admin-columns'
			),
			'<strong>' . $product . '</strong>',
			$product,
			'<a class="hide-review-notice-soft" href="#">' . __(
				'click here',
				'codepress-admin-columns'
			) . '</a>'
		); ?>
	</p>
	<p class="buttons">
		<a class="button button-primary" href="https://wordpress.org/support/view/plugin-reviews/codepress-admin-columns?rate=5#postform" target="_blank"><?php
			_e( 'Leave a review!', 'codepress-admin-columns' ); ?></a>
		<a class="button button-secondary hide-review-notice" href='#' data-dismiss=""><?php
			_e( "Permanently hide notice", 'codepress-admin-columns' ); ?></a>
	</p>
</div>
<div class="help hidden">
	<a href="#" class="hide-notice hide-review-notice"></a>
	<p>
		<?php

		printf(
			__(
				"We're sorry to hear that; maybe we can help! If you're having problems properly setting up %s or if you would like help with some more advanced features, please visit our %s.",
				'codepress-admin-columns'
			),
			$product,
			'<a href="' . esc_url( $this->documentation_url ) . '" target="_blank">' . __(
				'documentation page',
				'codepress-admin-columns'
			) . '</a>'
		);

		printf(
			__( 'You can also find help on the %s, and %s.', 'codepress-admin-columns' ),
			'<a href="https://wordpress.org/support/plugin/codepress-admin-columns#postform" target="_blank">' . __(
				'Admin Columns forum on WordPress.org',
				'codepress-admin-columns'
			) . '</a>',
			'<a href="https://wordpress.org/plugins/codepress-admin-columns/#faq" target="_blank">' . __(
				'find answers to frequently asked questions',
				'codepress-admin-columns'
			) . '</a>'
		);

		?>
	</p>
</div>
