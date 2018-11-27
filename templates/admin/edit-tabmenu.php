<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<h1 class="nav-tab-wrapper cpac-nav-tab-wrapper">
	<?php foreach ( $this->items as $slug => $label ) {
		echo sprintf( '<a href="%s" class="nav-tab %s">%s</a>', ac_get_admin_url( $slug ), $this->current === $slug ? 'nav-tab-active' : '', $label );
	}
	?>
</h1>