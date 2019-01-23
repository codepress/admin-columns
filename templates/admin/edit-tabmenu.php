<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @global \AC\Admin\MenuItem[] $items
 * @global string               $current
 */

$items = $this->items;
$current = $this->current;

?>

<h1 class="nav-tab-wrapper cpac-nav-tab-wrapper">
	<?php foreach ( $items as $item ) {
		echo sprintf( '<a href="%s" class="nav-tab %s">%s</a>', $item->get_url(), $current === $item->get_slug() ? 'nav-tab-active' : '', $item->get_label() );
	}
	?>
</h1>