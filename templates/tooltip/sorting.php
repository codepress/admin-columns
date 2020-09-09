<?php

use AC\Type\Url\Documentation;

?>
<h3>
	<?= __( 'Sorting', 'codepress-admin-columns' ); ?>
</h3>
<p>
	<?= __( 'Sort by clicking the column header on the list table. Click the column header again to switch between <em>ascending</em> and <em>descending</em>.', 'codepress-admin-columns' ); ?>
</p>
<img width="230" src="<?= esc_url( AC()->get_url() . 'assets/images/tooltip/sort-table.png' ); ?>" alt="Sort" style="border:1px solid #ddd;">
<p>
	<?= __( 'The sorted column is saved as your personal preference.', 'codepress-admin-columns' ); ?>
	<?= __( 'When you come back the content is sorted just the way you left it.', 'codepress-admin-columns' ); ?>
</p>
<p>
	<?= sprintf(
		__( 'Reset the sorting by clicking the %s button.', 'codepress-admin-columns' ),
		sprintf( '<strong>%s</strong>', __( 'Reset Sorting', 'codepress-admin-columns' ) )
	); ?>
</p>
<img width="222" src="<?= esc_url( AC()->get_url() . 'assets/images/tooltip/reset-sorting.png' ); ?>" alt="Reset Sorting">
<p>
	<?= __( 'You can change the default sorted column in the optional settings below.', 'codepress-admin-columns' ); ?>
</p>
<p>
	<a href="<?= esc_url( ( new Documentation( Documentation::ARTICLE_SORTING ) )->get_url() ); ?>" target="_blank">
		<?= __( 'Learn more &raquo;', 'codepress-admin-columns' ); ?>
	</a>
</p>
