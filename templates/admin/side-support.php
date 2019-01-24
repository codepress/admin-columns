<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="sidebox" id="plugin-support">
	<h3><?php _e( 'Support', 'codepress-admin-columns' ); ?></h3>

	<div class="inside">
		<p>
			<?php _e( "Check the <strong>Help</strong> section in the top-right screen.", 'codepress-admin-columns' ); ?>
		</p>
		<p>
			<?php printf( __( "For full documentation, bug reports, feature suggestions and other tips <a href='%s'>visit the Admin Columns website</a>.", 'codepress-admin-columns' ), ac_get_site_utm_url( 'documentation', 'support' ) ); ?>
		</p>
	</div>
</div>