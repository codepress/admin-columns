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
	<?php foreach ( $table->getColumns() as $name => $label ) : ?>
		<th class="<?= $name; ?>"><?= $label; ?></th>
	<?php endforeach; ?>
	</thead>
	<tbody>

	<?php foreach ( $table->getItems() as $item ) : ?>
		<tr class="<?= $table->getRowClass( $item ); ?>">
			<?php foreach ( $table->getColumnNames() as $name ) : ?>
				<td class="<?= $name; ?>">
					<?= $table->renderColumn( $name, $item ); ?>
				</td>
			<?php endforeach; ?>
		</tr>
	<?php endforeach; ?>
	</tbody>

	<?php if ( $this->message ) : ?>
		<tfoot>
		<tr class="message">
			<td colspan="<?= count( $table->getColumns() ); ?>">
				<?= $this->message; ?>
			</td>
		</tr>
		</tfoot>
	<?php endif; ?>

</table>