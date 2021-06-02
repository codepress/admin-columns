<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<style>
	.plugins tr[data-plugin='<?php echo $this->plugin_basename; ?>'] th,
	.plugins tr[data-plugin='<?php echo $this->plugin_basename; ?>'] td {
		box-shadow: none;
	}

	<?php if ( $this->icon ) : ?>

	.plugins tr[data-plugin='<?php echo $this->plugin_basename; ?>'] .update-message p:before {
		content: "<?php echo $this->icon ?>";
	}

	<?php endif; ?>
</style>

<tr class="plugin-update-tr <?php echo esc_attr( $this->status ); ?>" data-slug="<?php echo esc_attr( basename( $this->plugin_basename ) ); ?>" data-plugin="<?php echo esc_attr( $this->plugin_basename ); ?>">
	<td colspan="100%" class="plugin-update colspanchange">
		<div class="update-message notice notice-alt inline <?php echo esc_attr( $this->class ); ?>">
			<p><?php echo $this->message; ?></p>
		</div>
	</td>
</tr>