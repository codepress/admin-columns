<?php

use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;
use AC\View;

$url = (new UtmTags(Site::create_admin_columns_pro(), 'upgrade'))->get_url();

?>
<section class="ac-settings-box ac-settings-box-pro-cta">
	<h2 class="ac-lined-header"><?= $this->title; ?></h2>

	<p class="description"><?= $this->description; ?></p>

	<ul class="ac-pro-list">
        <?=
        (new View([
            'feature' => __('Sortable Columns', 'codepress-admin-columns'),
            'tooltip' => sprintf(
                '%s %s',
                __('Any column can be turned into an ordered list.', 'codepress-admin-columns'),
                __('Sort on numbers, names, prices or anything else.', 'codepress-admin-columns')
            ),
        ]))->set_template('admin/page/component/pro-feature-list-item')->render();
        ?>

        <?=
        (new View([
            'feature' => __('Smart Filters', 'codepress-admin-columns'),
            'tooltip' => sprintf(
                '%s %s',
                __('Only show the content that matches the rules you set.', 'codepress-admin-columns'),
                __(
                    'Filters help you to get valuable insights about orders, users, posts, anything really.',
                    'codepress-admin-columns'
                )
            ),
        ]))->set_template('admin/page/component/pro-feature-list-item')->render();
        ?>

        <?=
        (new View([
            'feature' => __('Sticky Headers', 'codepress-admin-columns'),
            'tooltip' => __(
                'Keep your column names on top of the screen when scrolling down the page',
                'codepress-admin-columns'
            ),
        ]))->set_template('admin/page/component/pro-feature-list-item')->render();
        ?>

        <?=
        (new View([
            'feature' => __('Multiple Table Views', 'codepress-admin-columns'),
            'tooltip' => sprintf(
                '%s %s %s',
                __('Need certain columns for certain tasks?', 'codepress-admin-columns'),
                __('Create multiple columns presets.', 'codepress-admin-columns'),
                __('Switching between sets is easy and can be done from any list table.', 'codepress-admin-columns')
            ),
        ]))->set_template('admin/page/component/pro-feature-list-item')->render();
        ?>

        <?=
        (new View([
            'feature' => __('Unlimited Columns', 'codepress-admin-columns'),
            'tooltip' => sprintf(
                '%s %s',
                __('Choose from a well designed set of columns or create your own.', 'codepress-admin-columns'),
                __('Craft overviews in minutes that display precisely what you want to see.', 'codepress-admin-columns')
            ),
        ]))->set_template('admin/page/component/pro-feature-list-item')->render();
        ?>

        <?=
        (new View([
            'feature' => __('Inline Editing', 'codepress-admin-columns'),
            'tooltip' => sprintf(
                '%s %s',
                __('No need to open your post, page, order, user or anything else.', 'codepress-admin-columns'),
                __('Edit your content directly from the overview.', 'codepress-admin-columns')
            ),
        ]))->set_template('admin/page/component/pro-feature-list-item')->render();
        ?>

        <?=
        (new View([
            'feature' => __('Bulk Editing', 'codepress-admin-columns'),
            'tooltip' => sprintf(
                '%s %s',
                __('Need to update multiple values at once?', 'codepress-admin-columns'),
                __('With Bulk Edit you can update thousands of values at once.', 'codepress-admin-columns')
            ),
        ]))->set_template('admin/page/component/pro-feature-list-item')->render();
        ?>

        <?=
        (new View([
            'feature' => __('Horizontal Scrolling', 'codepress-admin-columns'),
            'tooltip' => sprintf(
                '%s %s',
                __('Need more columns than the size of your screen?', 'codepress-admin-columns'),
                __(
                    'Simply scroll through your columns horizontally so your overview can be complete and functional.',
                    'codepress-admin-columns'
                )
            ),
        ]))->set_template('admin/page/component/pro-feature-list-item')->render();
        ?>

        <?=
        (new View([
            'feature' => __('Export Table to CSV', 'codepress-admin-columns'),
            'tooltip' => sprintf(
                '%s %s',
                __('Want to import content in Excel, Mailchimp or anything else?', 'codepress-admin-columns'),
                __('Admin Columns can generate a fully customized and downloadable CSV.', 'codepress-admin-columns')
            ),
        ]))->set_template('admin/page/component/pro-feature-list-item')->render();
        ?>

        <?=
        (new View([
            'feature' => __('Multisite Support', 'codepress-admin-columns'),
            'tooltip' => sprintf(
                '%s %s',
                __('Get insights about your entire network of sites.', 'codepress-admin-columns'),
                __('And of course, all network sites can use Admin Columns.', 'codepress-admin-columns')
            ),
        ]))->set_template('admin/page/component/pro-feature-list-item')->render();
        ?>

        <?=
        (new View([
            'feature' => __('Customize List Table', 'codepress-admin-columns'),
            'tooltip' => __(
                'Create a better user experience when visiting the list table by setting preferences and hiding the functionality you do not need.',
                'codepress-admin-columns'
            ),
        ]))->set_template('admin/page/component/pro-feature-list-item')->render();
        ?>

        <?=
        (new View([
            'feature' => __('Taxonomy Support', 'codepress-admin-columns'),
            'tooltip' => __('Manage columns for your Taxonomy overview pages', 'codepress-admin-columns'),
        ]))->set_template('admin/page/component/pro-feature-list-item')->render();
        ?>

        <?=
        (new View([
            'feature' => __('All integration add-ons', 'codepress-admin-columns'),
            'tooltip' => __(
                'Get access to all our integrations for popular WordPress plugins',
                'codepress-admin-columns'
            ),
        ]))->set_template('admin/page/component/pro-feature-list-item')->render();
        ?>

        <?=
        (new View([
            'feature' => __('Add Row', 'codepress-admin-columns'),
            'tooltip' => __(
                'Insert a post, user or anything really directly from the overview.',
                'codepress-admin-columns'
            ),
        ]))->set_template('admin/page/component/pro-feature-list-item')->render();
        ?>

		<li class="ac-pro-list__item">
			<a target="_blank" href="<?= esc_url($url); ?>" class="ac-pro-list__item__link">
                <?= __('View all Pro features', 'codepress-admin-columns'); ?>
			</a>
		</li>
	</ul>

	<a target="_blank" href="<?= esc_url($url); ?>" class="button-primary -pink">
        <?php
        _e('Upgrade to Admin Columns Pro', 'codepress-admin-columns'); ?>
	</a>

</section>