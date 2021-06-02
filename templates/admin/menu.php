<h1 class="nav-tab-wrapper cpac-nav-tab-wrapper">
	<?php
	/**
	 * @var \AC\Admin\Menu\Item $menu_item
	 */
	foreach ( $this->menu_items as $menu_item ) :

		$class = '';

		if ( $this->current === $menu_item->get_slug() ) {
			$class = ' nav-tab-active';
		}

		?>
		<a href="<?= esc_url( $menu_item->get_url() ); ?>" class="nav-tab <?= esc_attr( $class ); ?>">
			<?= $menu_item->get_label(); ?>
		</a>
	<?php endforeach; ?>
</h1>