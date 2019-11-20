<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="layout-selector">
	<ul class="subsubsub">
		<li class="first"><?php _e( 'Column Sets', 'codepress-admin-columns' ); ?>:</li>
		<?php echo $this->items; ?>
	</ul>
</div>