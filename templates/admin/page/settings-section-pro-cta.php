<?php

use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;

// TODO
$features = [
	__( 'Sortable Columns', 'codepress-admin-columns' ),
	__( 'Smart Filters', 'codepress-admin-columns' ),
	__( 'Sticky Headers', 'codepress-admin-columns' ),
	__( 'Multiple Table Views', 'codepress-admin-columns' ),
	__( 'Unlimited Columns', 'codepress-admin-columns' ),
	__( 'Inline Editing', 'codepress-admin-columns' ),
	__( 'Bulk Editing', 'codepress-admin-columns' ),
	__( 'Horizontal Scrolling', 'codepress-admin-columns' ),
	__( 'Export Table to CSV', 'codepress-admin-columns' ),
	__( 'Multisite Support', 'codepress-admin-columns' ),
	__( 'Customize List Table', 'codepress-admin-columns' ),
	__( 'Taxonomy support', 'codepress-admin-columns' ),
	__( 'All integration add-ons', 'codepress-admin-columns' ),
	__( 'Quick Add', 'codepress-admin-columns' ),
];

$url = ( new UtmTags( new Site( Site::PAGE_ABOUT_PRO ), 'upgrade' ) )->get_url();

?>
<section class="ac-settings-box ac-settings-box-pro-cta">
	<h2 class="ac-lined-header"><?= $this->title; ?></h2>

	<p class="description"><?= $this->description; ?></p>

	<ul>
		<?php foreach ( $features as $feature ) : // TODO tooltip ?>
			<li class="-feature"><a><?= $feature; ?></a></li>
		<?php endforeach; ?>
		<li>
			<a target="_blank" href="<?= esc_url( $url ); ?>">
				<?= __( 'View all Pro features', 'codepress-admin-columns' ); ?>
			</a>
		</li>
	</ul>

	<a target="_blank" href="<?= esc_url( $url ); ?>" class="button-primary button-pink">
		<?php _e( 'Upgrade to Admin Columns Pro', 'codepress-admin-columns' ); ?>
	</a>

</section>