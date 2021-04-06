<?php

use AC\Type\Url\Documentation;

?>
<h3><?= __( 'Smart Filtering', 'codepress-admin-columns' ); ?></h3>
<p>
	<?= _x( 'Smart filtering allows you to segment your data by different criteria.', 'smart filtering help', 'codepress-admin-columns' ); ?>
</p>
<p>
	1. <?= _x(
		sprintf(
			'Click on the %s button and select a column and the criteria you want to filter on. You can add as many filters as you like.',
			sprintf( '<strong>%s</strong>', __( 'Add Filter', 'codepress-admin-columns' ) )
		),
		'smart filtering help',
		'codepress-admin-columns'
	); ?>
</p>
<img src="<?= esc_url( AC()->get_url() . 'assets/images/tooltip/smart-filters.png' ); ?>" alt="Smart Filtering" style="border:1px solid #ddd;">
<p>
	2. <?= _x( 'You can save your filters for later use.', 'smart filtering help', 'codepress-admin-columns' ); ?>
	<?= _x( 'When you return to your list table you can easily select them again from the menu.', 'smart filtering help', 'codepress-admin-columns' ); ?>
</p>
<img width="180" src="<?= esc_url( AC()->get_url() . 'assets/images/tooltip/save-filters.png' ); ?>" alt="Saved Filters" style="border:1px solid #ddd;">
<p>
	<a href="<?= esc_url( Documentation::create_with_path( Documentation::ARTICLE_SMART_FILTERING )->get_url() ); ?>" target="_blank">
		<?= __( 'Learn more &raquo;', 'codepress-admin-columns' ); ?>
	</a>
</p>