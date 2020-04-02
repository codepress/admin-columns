<h3>
	<?= __( 'Filtering', 'codepress-admin-columns' ); ?>
</h3>
<p>
	<?= __( 'This will allow the column to be filtered.', 'codepress-admin-columns' ); ?>
	<?= __( 'You can filter the contents by selecting the column value from the filter dropdown menu.', 'codepress-admin-columns' ); ?>
</p>
<img src="<?= esc_url( AC()->get_url() . 'assets/images/tooltip/filter.png' ); ?>" alt="Export" style="border:1px solid #ddd;">
<h4>
	<?= __( 'Filters vs Smart Filters', 'codepress-admin-columns' ); ?>
</h4>
<p>
	<?= sprintf( __( '%s is an improved version of %s.', 'codepress-admin-columns' ), sprintf( '<em>%s</em>', __( 'Smart Filtering', 'codepress-admin-columns' ) ), sprintf( '<em>%s</em>', __( 'Filtering', 'codepress-admin-columns' ) ) ); ?>
	<?= sprintf( __( 'Finding the right content will be much easier with the use of conditionals, such as %s.', 'codepress-admin-columns' ), wp_sprintf( '%l', [ "contains", "between", "starts with" ] ) ); ?>
	<br>
	<?= sprintf( __( '%s also has better support for all the different types of fields, such as text, numbers and dates.', 'codepress-admin-columns' ), sprintf( '<em>%s</em>', __( 'Smart Filtering', 'codepress-admin-columns' ) ) ); ?>
</p>
<p class="notice notice-warning">
	<?= sprintf( __( 'We recommend using %s', 'codepress-admin-columns' ), sprintf( '<strong>%s</strong>', __( 'Smart Filters', 'codepress-admin-columns' ) ) ); ?>
</p>