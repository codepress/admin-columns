<header class="cpac-header">
	<img src="<?= AC()->get_url(); ?>assets/images/logo-ac-light.svg" alt="">
</header>
<nav class="cpac-admin-nav">
	<ul class="cpac-nav">
		<?php
		/**
		 * @var \AC\Admin\Menu\Item $menu_item
		 */
		foreach ( $this->menu_items as $menu_item ) :

			$class = '';

			if ( $this->current === $menu_item->get_slug() ) {
				$class = ' -active';
			}

			?>
			<li class="cpac-nav__item <?= esc_attr( $class ); ?>">
				<a href="<?= esc_url( $menu_item->get_url() ); ?>">
					<?= $menu_item->get_label(); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>