<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<table class="ac-column-setting<?= $this->class ? ' ' . $this->class : ''; ?><?= $this->name ? esc_attr( ' ac-column-setting--' . $this->name ) : ''; ?>" data-setting="<?= esc_attr( $this->name ); ?>">
	<tr>
		<td class="col-label">
			<label for="<?= esc_attr( $this->for ); ?>">
				<span class="label <?= esc_attr( $this->tooltip ? 'tooltip' : '' ); ?>">
					<?= $this->label; ?>
				</span>

				<?php if ( $this->tooltip ) : ?>
					<div class="tooltip">
						<?= $this->tooltip; ?>
					</div>
				<?php endif; ?>

				<?php if ( $this->instructions ): ?>
					<?php $id = $this->name . uniqid(); ?>
					<a class="ac-pointer ac-column-setting__instructions" rel="p-instruction-<?= $id; ?>" data-pos="right" data-width="300">
						<img src="<?= AC()->get_url(); ?>assets/images/question.svg" alt="?">
					</a>
					<template id="p-instruction-<?= $id; ?>">
						<?= $this->instructions; ?>
					</template>
				<?php endif; ?>

				<?php if ( $this->read_more ) : ?>
					<a href="<?= esc_url( $this->read_more ); ?>" target="_blank" class="more-link">
						<span class="dashicons dashicons-admin-links"></span>
					</a>
				<?php endif; ?>
			</label>
		</td>
		<td class="col-input">
			<?php if ( $this->setting ) : ?>
				<div class="ac-setting-input">
					<?= $this->setting; ?>
				</div>
			<?php endif; ?>

			<?php

			if ( is_array( $this->sections ) ) {
				foreach ( $this->sections as $section ) {
					echo $section;
				}
			}

			?>
		</td>
	</tr>
</table>