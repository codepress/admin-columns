<?php

use AC\Admin\Type\MenuItem;

/**
 * @var MenuItem[] $items
 */
$items = $this->menu_items;
?>

<header class="acu-flex acu-bg-gray-dark acu-px-[50px] acu-py-3" id="acMenu">
	<div class="acu-w-[260px] acu-items-center acu-flex ">
		<img class="acu-w-[180px]" src="<?= esc_url(ac_get_url('assets/images/logo-ac-light.svg')) ?>" alt="">
	</div>
	<div class="acu-flex">
		<ul class="acu-flex ac-admin-nav acu-gap-2">
            <?php
            foreach ($items as $item) : ?>
				<li class="ac-admin-nav__item <?= esc_attr($item->get_class()); ?>">
					<a href="<?= esc_url(
                        $item->get_url()
                    ) ?>" class="acu-text-[#fff] acu-inline-block ac-admin-nav__link"
                        <?= $item->get_target() ? sprintf(' target="%s"', $item->get_target()) : '' ?>>
                        <?= $item->get_label() ?>
					</a>
				</li>
            <?php
            endforeach; ?>
		</ul>
	</div>
</header>