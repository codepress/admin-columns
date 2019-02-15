<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<span class="ac-column-heading-setting ac-column-heading-setting--<?php echo esc_attr( $this->setting ); ?> <?php echo $this->class ? esc_attr( $this->class ) : ''; ?>" title="<?php echo esc_attr( $this->title ); ?>" data-setting="<?php echo esc_attr( $this->setting ); ?>">
	<?php echo $this->content; ?>
</span>