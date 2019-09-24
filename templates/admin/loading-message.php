<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="ac-loading-msg-wrapper">
	<div class="ac-loading-msg">
		<img class="ac-loading-msg__logo" src="<?php echo AC()->get_url() . '/assets/images/logo-ac.svg'; ?>" alt="Admin Columns">
		<div class="ac-loading-msg__content"><?php _e( 'Loading Columns', 'codepress-admin-columns' ); ?></div>
	</div>
</div>