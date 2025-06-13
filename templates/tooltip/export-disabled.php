<?php

use AC\Type\Url\Documentation;

?>
<template id="doc-export-unavailable">
	<h3>
        <?= __('Export Unavailable', 'codepress-admin-columns'); ?>
	</h3>
	<p>
        <?= __('Unfortunately not every column can be exported.', 'codepress-admin-columns'); ?>
	</p>
	<p>
        <?= __(
            'Third-party columns and some custom columns cannot be exported unless there is build-in support for that specific column.',
            'codepress-admin-columns'
        ); ?>
	</p>
	<p>
		<a href="<?= esc_url(
            Documentation::create_with_path(Documentation::ARTICLE_EXPORT)->get_url()
        ); ?>" target="_blank">
            <?= __('Learn more &raquo;', 'codepress-admin-columns'); ?>
		</a>
	</p>
</template>