<?php

declare(strict_types=1);

?>
<template id="doc-smart-filtering-fields">
	<h3><?= __('Filter By', 'codepress-admin-columns') ?></h3>
	<p>
        <?= _x(
            'This column holds more than one value, such as a first and a last name.',
            'smart filtering fields help',
            'codepress-admin-columns'
        ); ?>
	</p>
	<p>
        <?= _x(
            'Choose which of these values you want to filter on. Each selected value appears as its own smart filter on the list table.',
            'smart filtering fields help',
            'codepress-admin-columns'
        ); ?>
	</p>
</template>
