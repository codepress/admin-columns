<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<style>
	.plugins tr[data-plugin='<?= $this->plugin_basename ?>'] th,
	.plugins tr[data-plugin='<?= $this->plugin_basename ?>'] td {
		box-shadow: none;
	}

	<?php if ( $this->icon ) : ?>

	.plugins tr[data-plugin='<?= $this->plugin_basename ?>'] .update-message p:before {
		content: "<?= $this->icon ?>";
	}

	<?php endif; ?>
</style>

<tr class="plugin-update-tr <?= esc_attr( $this->status ) ?>" data-slug="<?= esc_attr( basename( $this->plugin_basename ) ) ?>" data-plugin="<?= esc_attr( $this->plugin_basename ) ?>">
	<td colspan="100%" class="plugin-update colspanchange">
		<div class="notice notice-alt inline <?= esc_attr( $this->class ) ?>">
			<p><?= $this->message ?></p>
		</div>
	</td>
</tr>