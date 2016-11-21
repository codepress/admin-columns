x3<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$description = $this->description ? 'description' : '';

?>

<table class="<?php echo esc_attr( $this->class ); ?>" data-events="<?php echo $this->events; ?>">
	<tr>
		<td class="label">
			<label for="<?php echo esc_attr( $this->for ); ?>">
				<span class="label <?php echo esc_attr( $description ); ?>">
					<?php echo $this->label; ?>
				</span>

				<?php if ( $this->description ) : ?>
					<span class="description">
						<?php echo $this->description; ?>
					</span>
				<?php endif; ?>

				<?php if ( $this->read_more ) : ?>
					<a title="<?php esc_attr_e( 'View more', 'codepress-admin-columns' ); ?>" href="<?php echo esc_url( $this->read_more ); ?>" target="_blank" class="more-link">
						<span class="dashicons dashicons-external"></span>
					</a>
				<?php endif; ?>
			</label>
		</td>
		<td>
			<?php if ( $this->setting ) : ?>
				<div class="cpac_form_element">
					<?php echo $this->setting; ?>
				</div>
			<?php endif; ?>

			<?php

			if ( $this->sections ) {
				foreach ( $this->sections as $section ) {
					echo $section;
				}
			}

			?>
		</td>
	</tr>
</table>