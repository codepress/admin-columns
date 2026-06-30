<?php

declare(strict_types=1);

/**
 * @var \AC\View $this
 * @var string   $table_id
 * @var string   $column_id
 * @var string   $user_width
 */

$table_id = esc_attr($this->table_id);
$column_id = esc_attr($this->column_id);
$style_id = esc_attr(sprintf('ac-column-size-%s-user', $this->column_id));

$var_user = sprintf('--ac-col-%s-width-user', $column_id);
?>
<style id="<?= $style_id ?>">
	.ac-<?= $table_id ?> { <?= $var_user ?>: <?= esc_attr($this->user_width) ?>; }
</style>
