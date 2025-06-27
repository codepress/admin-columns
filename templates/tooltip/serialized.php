<template id="doc-serialized">
	<h3>
        <?= __('Serialized Array Keys', 'codepress-admin-columns'); ?>
	</h3>
	<p>
        <?= __('Fill in the key(s) of the nested element that you want to display.', 'codepress-admin-columns'); ?>
	</p>
	<h4><?= __('Example') ?></h4>
	<p>
		<img src="<?= esc_url(
            \AC\Container::get_location()->with_suffix('assets/images/tooltip/serialized-array.png')->get_url()
        ) ?>" alt="Serialized Array" style="box-sizing: border-box; border:1px solid #ddd;padding: 8px 10px;max-width: 100%;margin: 5px 0;">
	</p>
	<p>
        <?= sprintf(
            __('Fill in %s', 'codepress-admin-columns'),
            sprintf('<span class="tooltip-serialized-array-input">%s</span>', 'sizes.medium')
        ); ?>
	</p>
	<p>
        <?= sprintf(
            __('Displayed value is %s', 'codepress-admin-columns'),
            sprintf('<span class="tooltip-serialized-array-result">%s</span>', 'image-200.jpg')
        ); ?>
	</p>
	<style>
		.tooltip-serialized-array-input {
			border: 1px solid #ccc;
			padding: 4px 10px;
			border-radius: 4px;
			margin-left: 4px;
			font-family: monospace;
			color: #4b4b4b;
		}

		.tooltip-serialized-array-result {
			font-weight: bold;
			font-family: monospace;
			color: #4b4b4b;
		}
	</style>
</template>