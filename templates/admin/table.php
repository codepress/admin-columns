<?php
/** @var Table $table */

use AC\Admin\Table;

$table = $this->table;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<table class="<?= implode( ' ', $table->getTableClasses() ); ?>">
	<thead>
	<?php foreach ( $table->get_columns() as $name => $label ) : ?>
		<th class="<?= $name; ?>"><?= $label; ?></th>
	<?php endforeach; ?>
	</thead>
	<tbody>

	<?php foreach ( $table->get_items() as $item ) : ?>
		<tr class="<?= $table->get_row_class( $item ); ?>">
			<?php foreach ( $table->getColumnNames() as $name ) : ?>
				<td class="<?= $name; ?>">
					<?= $table->render_column( $name, $item ); ?>
				</td>
			<?php endforeach; ?>
		</tr>
	<?php endforeach; ?>
	</tbody>

	<?php if ( $this->message ) : ?>
		<tfoot>
		<tr class="message">
			<td colspan="<?= count( $table->get_columns() ); ?>">
				<?= $this->message; ?>
			</td>
		</tr>
		</tfoot>
	<?php endif; ?>

</table>