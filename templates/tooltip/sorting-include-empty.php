<?php

use AC\Type\Url\Documentation;

$url = ( new Documentation( Documentation::ARTICLE_SHOW_ALL_SORTING_RESULTS ) )->get_url();

?>
<h3>
	<?= __( 'Sorting', 'codepress-admin-columns' ); ?>
</h3>
<p>
	<?= __( 'As a default, when sorting a list table by a column it will exclude items where its value is empty.', 'codepress-admin-columns' ); ?>
</p>
<p>
	<?= __( "By enabling this setting the sorting results will include empty values.", 'codepress-admin-columns' ); ?>
</p>
<p>
	<a href="<?= esc_url( $url ); ?>" target="_blank">
		<?= __( 'Read more &raquo;', 'codepress-admin-columns' ); ?>
	</a>
</p>