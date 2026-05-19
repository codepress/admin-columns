<?php

declare(strict_types=1);

/**
 * @var \AC\View $this
 * @var string   $table_id
 * @var string   $column_id
 * @var string   $width
 * @var string   $type
 */

$table_id = esc_attr($this->table_id);
$column_id = esc_attr($this->column_id);
$width = esc_attr($this->width);
$style_id = esc_attr(sprintf('ac-column-size-%s-%s', $this->type, $this->column_id));
?>
<style id="<?= $style_id ?>">
	@media screen and (min-width: 783px) {
		.ac-<?= $table_id ?> .wrap table th.column-<?= $column_id ?>,
		.ac-<?= $table_id ?> .wrap table td.column-<?= $column_id ?> {
			width: <?= $width ?> !important;
		}

		body.acp-overflow-table.ac-<?= $table_id ?> .wrap th.column-<?= $column_id ?>,
		body.acp-overflow-table.ac-<?= $table_id ?> .wrap td.column-<?= $column_id ?> {
			min-width: <?= $width ?> !important;
			max-width: <?= $width ?> !important;
		}
	}
</style>
