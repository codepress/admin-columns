<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="ac-notice notice is-dismissible <?php echo esc_attr( $this->type . ' ' . $this->id ); ?>" data-dismissible-callback="<?php echo esc_attr( wp_json_encode( $this->dismissible_callback ) ); ?>">

	<div class="ac-notice__body">
		<?php echo $this->message; ?>
	</div>

</div>