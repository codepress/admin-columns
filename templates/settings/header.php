<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<span class="ac-column-heading-setting ac-column-heading-setting--<?= esc_attr( $this->setting ); ?> -<?= esc_attr( $this->setting ); ?> <?= $this->class ? esc_attr( $this->class ) : ''; ?>" title="<?= esc_attr( $this->title ); ?>" data-setting="<?= esc_attr( $this->setting ); ?>">
	<?= $this->content; ?>
</span>