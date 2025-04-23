<div class="acu-pt-1 lg:acu-pr-8">
	<strong class="acu-block"><?= __('File Directory', 'codepress-admin-columns'); ?></strong>

	<p>
        <?= __('Set the storage directory to a folder on your file system.', 'codepress-admin-columns') ?>
        <?= __(
            'Add this code snippet to your theme\'s functions.php file to start using File Storage.',
            'codepress-admin-columns'
        ) ?>
        <?= __(' Read more about how to setup File Storage', 'codepress-admin-columns') ?>
	</p>


	<pre class="acu-block acu-bg-[#F9FAFB] acu-px-4 acu-rounded acu-border acu-border-solid acu-border-ui-border"><code class="acu-bg-[#F9FAFB]">
add_filter( <span class="acu-text-notification-red">'acp/storage/file/directory'</span>, function() {
	return get_stylesheet_directory() . '/acp-settings';
} );
</code>
</pre>
	
	<p><?= __(
            'Once the file directory is set you can migrate your column settings from the database to the
		file storage on this page.',
            'codepress-admin-columns'
        ) ?></p>
</div>