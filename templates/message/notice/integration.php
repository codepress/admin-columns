<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="ac-notice ac-notice--integration notice is-dismissible" data-dismissible-callback="<?php echo esc_attr( wp_json_encode( $this->dismissible_callback ) ); ?>">
	<div class="ac-notice-integration">
		<div class="ac-notice-integration__copy">
			<p class="ac-notice-integration__eyebrow"><?php echo esc_html( $this->eyebrow ); ?></p>
			<p class="ac-notice-integration__desc"><strong><?php echo esc_html( $this->title ); ?></strong> <?php echo esc_html( $this->description ); ?></p>
		</div>
		<div class="ac-notice-integration__actions">
			<a href="<?php echo esc_url( $this->cta_url ); ?>" target="_blank" class="ac-notice-integration__btn ac-notice-integration__btn--primary">
				<?php echo esc_html( $this->cta_label ); ?>
			</a>
			<a href="<?php echo esc_url( $this->secondary_url ); ?>" target="_blank" class="ac-notice-integration__link">
				<?php echo esc_html( $this->secondary_label ); ?> →
			</a>
		</div>
	</div>
</div>
