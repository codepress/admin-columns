<?php

declare(strict_types=1);

use AC\Admin\Type\MenuItem;

/**
 * @var MenuItem[] $items
 */
$items = $this->menu_items;
$current = (string)$this->current;

$primary_items = array_values(
    array_filter($items, static fn(MenuItem $item): bool => $item->get_group() === MenuItem::GROUP_PRIMARY)
);
$more_items = array_values(
    array_filter($items, static fn(MenuItem $item): bool => $item->get_group() === MenuItem::GROUP_MORE)
);

$more_slugs = array_map(static fn(MenuItem $item): string => $item->get_slug(), $more_items);
$more_active = in_array($current, $more_slugs, true);

$first = $primary_items[0] ?? $items[0];
?>

<header class="acu-relative acu-z-10 acu-flex acu-flex-col md:acu-flex-row acu-bg-gray-dark acu-px-4 md:acu-px-[50px] acu-py-3" id="acMenu">
	<div class="acu-w-[260px] acu-items-center acu-flex ">
		<a href="<?= esc_url($first->get_url()) ?>">
			<img class="acu-w-[180px]" src="<?= esc_url($this->url . '/assets/images/logo-ac-light.svg') ?>" alt="">
		</a>
	</div>
	<div class="acu-flex acu-flex-grow">
		<ul class="acu-flex ac-admin-nav acu-gap-2 acu-flex-wrap acu-flex-grow">
            <?php
            foreach ($primary_items as $item) : ?>
				<li class="ac-admin-nav__item <?= esc_attr($item->get_class()); ?>">
					<a href="<?= esc_url($item->get_url()) ?>" class="acu-text-[#fff] acu-inline-block ac-admin-nav__link"
                        <?= $item->get_target() ? sprintf(' target="%s"', esc_attr($item->get_target())) : '' ?>
                        <?= $current === $item->get_slug() ? ' aria-current="page"' : '' ?>>
                        <?= $item->get_label() ?>
					</a>
				</li>
            <?php
            endforeach; ?>

            <?php
            if ($more_items) : ?>
				<li class="ac-admin-nav__item <?= $more_active ? '-active' : '' ?>">
					<button type="button"
							id="ac-admin-nav-more-trigger"
							class="acu-text-[#fff] ac-admin-nav__link ac-admin-nav__trigger"
							aria-expanded="false"
							aria-haspopup="true"
							aria-controls="ac-admin-nav-more">
                        <?php
                        esc_html_e('More', 'codepress-admin-columns'); ?>
						<span class="ac-admin-nav__chevron" aria-hidden="true"></span>
					</button>
					<ul id="ac-admin-nav-more" class="ac-admin-nav__dropdown" aria-labelledby="ac-admin-nav-more-trigger" hidden>
                        <?php
                        foreach ($more_items as $item) : ?>
							<li class="ac-admin-nav__dropdown-item <?= esc_attr($item->get_class()); ?>">
								<a href="<?= esc_url($item->get_url()) ?>" class="ac-admin-nav__dropdown-link"
                                    <?= $item->get_target() ? sprintf(' target="%s"', esc_attr($item->get_target())) : '' ?>
                                    <?= $current === $item->get_slug() ? ' aria-current="page"' : '' ?>>
                                    <?= $item->get_label() ?>
								</a>
							</li>
                        <?php
                        endforeach; ?>
					</ul>
				</li>
            <?php
            endif; ?>
		</ul>
	</div>
</header>
