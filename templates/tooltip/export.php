<?php

use AC\Type\Url\Documentation;

?>
<template id="doc-export">
	<h3>
        <?= __('Export', 'codepress-admin-columns'); ?>
	</h3>
	<p>
        <?= __(
            'Export for Admin Columns Pro, allows you to export the contents of your columns to CSV.',
            'codepress-admin-columns'
        ); ?>
	</p>
	<p>
		1. <?= __('Click the export button and all items will be exported to CSV.', 'codepress-admin-columns'); ?>
	</p>
	<img src="<?= esc_url(ac_get_url('assets/images/tooltip/export.png')) ?>" alt="Export" width="213">
	<p>
		2. <?= __('The result is a CSV file.', 'codepress-admin-columns'); ?>
	</p>
	<img src="<?= esc_url(
        ac_get_url('assets/images/tooltip/export-csv.png')
    ) ?>" alt="Export" style="border:1px solid #ddd;">
	<p>
        <?= __('You can use filters to segment your list before exporting.', 'codepress-admin-columns'); ?>
	</p>
	<p>
		<a href="<?= esc_url(
            Documentation::create_with_path(Documentation::ARTICLE_EXPORT)->get_url()
        ) ?>" target="_blank">
            <?= __('Learn more &raquo;', 'codepress-admin-columns'); ?>
		</a>
	</p>
</template>