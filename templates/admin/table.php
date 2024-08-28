<?php

use AC\Admin\Table;

if ( ! defined('ABSPATH')) {
    exit;
}

/**
 * @var Table $table
 */
$table = $this->table;

?>

<table class="widefat fixed ac-table">
	<thead>
    <?php
    foreach ($table->get_headings() as $column => $label) : ?>
		<th class="<?= $column ?>"><?= $label ?></th>
    <?php
    endforeach; ?>
	</thead>
	<tbody>
    <?php
    foreach ($table->get_rows() as $row_data) : ?>
		<tr>
            <?php
            foreach (array_keys($table->get_headings()) as $column) : ?>
				<td class="<?= $column ?>">
                    <?= $table->render_cell($column, $row_data) ?>
				</td>
            <?php
            endforeach; ?>
		</tr>
    <?php
    endforeach; ?>
	</tbody>
    <?php
    if ($table->has_message()) : ?>
		<tfoot>
		<tr class="message">
			<td colspan="<?= count($table->get_headings()) ?>">
                <?= $table->get_message() ?>
			</td>
		</tr>
		</tfoot>
    <?php
    endif; ?>
</table>