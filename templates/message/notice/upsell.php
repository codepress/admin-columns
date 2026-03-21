<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="ac-notice ac-notice--upsell notice is-dismissible" data-dismissible-callback="<?php echo esc_attr( wp_json_encode( $this->dismissible_callback ) ); ?>">
	<div class="ac-notice-upsell">
		<div class="ac-notice-upsell__icon">⚡</div>
		<div class="ac-notice-upsell__copy">
			<p class="ac-notice-upsell__eyebrow"><?php echo esc_html( $this->eyebrow ); ?></p>
			<h2 class="ac-notice-upsell__title"><?php echo esc_html( $this->title ); ?></h2>
			<p class="ac-notice-upsell__desc">
				<?php echo esc_html( $this->description ); ?>
				<?php if ( ! empty( $this->features ) ) : ?>
					<span class="ac-notice-upsell__features">
						<?php echo esc_html( implode( ' · ', $this->features ) ); ?>
					</span>
				<?php endif; ?>
				<span class="ac-notice-upsell__trust">★ <?php echo esc_html__( '250,000+ sites', 'codepress-admin-columns' ); ?></span>
			</p>
		</div>
		<div class="ac-notice-upsell__actions">
			<a href="<?php echo esc_url( $this->cta_url ); ?>" target="_blank" class="ac-notice-upsell__btn ac-notice-upsell__btn--primary">
				<?php echo esc_html( $this->cta_label ); ?>
			</a>
			<a href="<?php echo esc_url( $this->secondary_url ); ?>" target="_blank" class="ac-notice-upsell__btn ac-notice-upsell__btn--secondary">
				<?php echo esc_html( $this->secondary_label ); ?>
			</a>
		</div>
	</div>
</div>
