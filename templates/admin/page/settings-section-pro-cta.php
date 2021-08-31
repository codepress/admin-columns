<?php

use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;
use AC\View;

$url = ( new UtmTags( new Site( Site::PAGE_ABOUT_PRO ), 'upgrade' ) )->get_url();

?>
<section class="ac-settings-box ac-settings-box-pro-cta">
	<h2 class="ac-lined-header"><?= $this->title; ?></h2>

	<p class="description"><?= $this->description; ?></p>

	<ul class="ac-pro-list">
		<?=
		( new View( [
			'feature' => __( 'Sortable Columns', 'codepress-admin-columns' ),
			'tooltip' => __( 'Any column can be turned into an ordered list.', 'codepress-admin-columns' ) . ' ' . __( 'Sort on numbers, names, prices or anything else.', 'codepress-admin-columns' ),
		] ) )->set_template( 'admin/page/component/pro-feature-list-item' )->render();
		?>

		<?=
		( new View( [
			'feature' => __( 'Smart Filters', 'codepress-admin-columns' ),
			'tooltip' => sprintf(
				'%s %s',
				__( 'Only show the content that matches the rules you set.', 'codepress-admin-columns' ),
				__( 'Filters help you to get valuable insights about orders, users, posts, anything really.', 'codepress-admin-columns' )
			),
		] ) )->set_template( 'admin/page/component/pro-feature-list-item' )->render();
		?>

		<?=
		( new View( [
			'feature' => __( 'Sticky Headers', 'codepress-admin-columns' ),
			'tooltip' => __( '', 'codepress-admin-columns' ),
		] ) )->set_template( 'admin/page/component/pro-feature-list-item' )->render();
		?>

		<?=
		( new View( [
			'feature' => __( 'Multiple Table Views', 'codepress-admin-columns' ),
			'tooltip' => __( '', 'codepress-admin-columns' ),
		] ) )->set_template( 'admin/page/component/pro-feature-list-item' )->render();
		?>

		<?=
		( new View( [
			'feature' => __( 'Unlimited Columns', 'codepress-admin-columns' ),
			'tooltip' => __( '', 'codepress-admin-columns' ),
		] ) )->set_template( 'admin/page/component/pro-feature-list-item' )->render();
		?>

		<?=
		( new View( [
			'feature' => __( 'Inline Editing', 'codepress-admin-columns' ),
			'tooltip' => __( '', 'codepress-admin-columns' ),
		] ) )->set_template( 'admin/page/component/pro-feature-list-item' )->render();
		?>

		<?=
		( new View( [
			'feature' => __( 'Bulk Editing', 'codepress-admin-columns' ),
			'tooltip' => __( '', 'codepress-admin-columns' ),
		] ) )->set_template( 'admin/page/component/pro-feature-list-item' )->render();
		?>

		<?=
		( new View( [
			'feature' => __( 'Horizontal Scrolling', 'codepress-admin-columns' ),
			'tooltip' => __( '', 'codepress-admin-columns' ),
		] ) )->set_template( 'admin/page/component/pro-feature-list-item' )->render();
		?>

		<?=
		( new View( [
			'feature' => __( 'Export Table to CSV', 'codepress-admin-columns' ),
			'tooltip' => __( '', 'codepress-admin-columns' ),
		] ) )->set_template( 'admin/page/component/pro-feature-list-item' )->render();
		?>

		<?=
		( new View( [
			'feature' => __( 'Multisite Support', 'codepress-admin-columns' ),
			'tooltip' => __( '', 'codepress-admin-columns' ),
		] ) )->set_template( 'admin/page/component/pro-feature-list-item' )->render();
		?>

		<?=
		( new View( [
			'feature' => __( 'Customize List Table', 'codepress-admin-columns' ),
			'tooltip' => __( '', 'codepress-admin-columns' ),
		] ) )->set_template( 'admin/page/component/pro-feature-list-item' )->render();
		?>

		<?=
		( new View( [
			'feature' => __( 'Taxonomy Support', 'codepress-admin-columns' ),
			'tooltip' => __( '', 'codepress-admin-columns' ),
		] ) )->set_template( 'admin/page/component/pro-feature-list-item' )->render();
		?>

		<?=
		( new View( [
			'feature' => __( 'All integration add-ons', 'codepress-admin-columns' ),
			'tooltip' => __( '', 'codepress-admin-columns' ),
		] ) )->set_template( 'admin/page/component/pro-feature-list-item' )->render();
		?>

		<?=
		( new View( [
			'feature' => __( 'Quick Add', 'codepress-admin-columns' ),
			'tooltip' => __( '', 'codepress-admin-columns' ),
		] ) )->set_template( 'admin/page/component/pro-feature-list-item' )->render();
		?>

		<li class="ac-pro-list__item">
			<a target="_blank" href="<?= esc_url( $url ); ?>" class="ac-pro-list__item__link">
				<?= __( 'View all Pro features', 'codepress-admin-columns' ); ?>
			</a>
		</li>
	</ul>

	<a target="_blank" href="<?= esc_url( $url ); ?>" class="button-primary -pink">
		<?php _e( 'Upgrade to Admin Columns Pro', 'codepress-admin-columns' ); ?>
	</a>

</section>