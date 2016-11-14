<?php

class AC_Settings_View_Label extends AC_Settings_ViewAbstract {

	public function template() {

		if ( ! $this->label ) {
			return null;
		}

		$description = $this->description ? 'description' : '';

		?>

		<label for="<?php echo esc_attr( $this->for ); ?>">
			<span class="label <?php echo esc_attr( $description ); ?>"><?php echo $this->label; ?></span>

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

		<?php
	}

}