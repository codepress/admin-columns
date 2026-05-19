<?php

declare(strict_types=1);

/**
 * @var View        $this
 * @var string      $table_id
 * @var string      $column_id
 * @var string|null $list_width
 */

use AC\View;

$table_id = esc_attr($this->table_id);
$column_id = esc_attr($this->column_id);
$style_id = esc_attr(sprintf('ac-column-size-%s', $this->column_id));

$var_list = sprintf('--ac-col-%s-width', $column_id);
$var_user = $var_list . '-user';
$value = sprintf('var(%s, var(%s))', $var_user, $var_list);
?>
<style id="<?= $style_id ?>">
	<?php if ($this->list_width !== null): ?>
	.ac-<?= $table_id ?> {
	<?= $var_list ?>: <?= esc_attr($this->list_width) ?>;
	}

	<?php endif; ?>

	@media screen and (min-width: 783px) {
		.ac-<?= $table_id ?> .wrap table th.column-<?= $column_id ?>,
		.ac-<?= $table_id ?> .wrap table td.column-<?= $column_id ?> {
			width: <?= $value ?> !important;
		}

		body.acp-overflow-table.ac-<?= $table_id ?> .wrap th.column-<?= $column_id ?>,
		body.acp-overflow-table.ac-<?= $table_id ?> .wrap td.column-<?= $column_id ?> {
			min-width: <?= $value ?> !important;
			max-width: <?= $value ?> !important;
		}
	}
</style>
