<h3>
	<?= __( 'Export Unavailable', 'codepress-admin-columns' ); ?>
</h3>
<p>
	<?= __( 'Unfortunately not every column can be exported.', 'codepress-admin-columns' ); ?>
</p>
<p>
	<?= __( 'Third-party columns and some custom columns cannot be exported unless there is build-in support for that specific column.', 'codepress-admin-columns' ); ?>
</p>
<p>
	<a href="<?= esc_url( ac_get_site_documentation_url( \AC\Type\Url\Documentation::ARTICLE_EXPORT ) ); ?>" target="_blank">
		<?= __( 'Learn more &raquo;', 'codepress-admin-columns' ); ?>
	</a>
</p>