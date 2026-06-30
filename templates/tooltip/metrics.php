<template id="doc-metrics">
	<h3>
        <?= __('Metrics', 'codepress-admin-columns'); ?>
	</h3>
	<p>
        <?= __(
            'With Metrics, you can add a calculation row to a column in the list table.',
            'codepress-admin-columns'
        ); ?>
	</p>
	<p>
        <?= __(
            'This row performs quick calculations on the paginated values, giving you instant insights without exporting your data.',
            'codepress-admin-columns'
        ); ?>

	</p>
	<img src="<?= esc_url(
        $this->url . '/assets/images/tooltip/metric-row.png'
    ) ?>" alt="Metrics" style="border:1px solid #ddd;">
</template>