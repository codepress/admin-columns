<h1 class="nav-tab-wrapper cpac-nav-tab-wrapper">
	<?php foreach ( $this->menu_items as $menu_item ) : ?>
		<a href="<?= esc_url( $menu_item->get_url() ); ?>" class="nav-tab <?= esc_attr( $menu_item->get_class() ); ?>"><?= $menu_item->get_label(); ?></a>
	<?php endforeach; ?>
</h1>