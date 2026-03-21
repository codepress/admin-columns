<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="ac-notice ac-notice--upsell notice is-dismissible" data-dismissible-callback="<?php echo esc_attr( wp_json_encode( $this->dismissible_callback ) ); ?>">
	<div class="ac-notice-upsell">
		<div class="ac-notice-upsell__icon"><?php echo esc_html( $this->icon ); ?></div>
		<div class="ac-notice-upsell__copy">
			<p class="ac-notice-upsell__eyebrow"><?php echo esc_html( $this->eyebrow ); ?></p>
			<h2 class="ac-notice-upsell__title"><?php echo esc_html( $this->title ); ?></h2>
			<p class="ac-notice-upsell__desc"><?php echo esc_html( $this->description ); ?></p>
		</div>
		<div class="ac-notice-upsell__actions">
			<a href="<?php echo esc_url( $this->cta_url ); ?>" target="_blank" class="ac-notice-upsell__btn ac-notice-upsell__btn--primary">
				<?php echo esc_html( $this->cta_label ); ?>
			</a>
			<a href="<?php echo esc_url( $this->secondary_url ); ?>" target="_blank" class="ac-notice-upsell__link">
				<?php echo esc_html( $this->secondary_label ); ?> →
			</a>
		</div>
	</div>
</div>
