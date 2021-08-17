<?php

use AC\Admin\Menu;
use AC\View;

/**
 * @var Menu\Item[] $items
 */
$items = $this->menu_items;
?>
<?= ( new View( [ 'license_status' => 1 ] ) )->set_template( 'admin/header' ) ?>

<nav class="cpac-admin-nav">
	<ul class="cpac-nav">
		<?php foreach ( $items as $item ) : ?>
			<li class="cpac-nav__item <?= esc_attr( $item->get_class() ); ?>">
				<a href="<?= esc_url( $item->get_url() ); ?>"<?php echo $item->get_target() ? sprintf( ' target="%s"', $item->get_target() ) : ''; ?>>
					<?= $item->get_label(); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>