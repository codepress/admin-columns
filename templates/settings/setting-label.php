<table class="ac-column-setting<?php echo $this->name ? esc_attr( ' ac-column-setting--' . $this->name ) : ''; ?>" data-setting="<?php echo esc_attr( $this->name ); ?>">
	<tr>
		<td class="col-label">
			<label for="<?php echo esc_attr( $this->for ); ?>">
				<span class="label<?php echo esc_attr( $this->tooltip ? ' tooltip' : '' ); ?>">
					<?php echo $this->label; ?>
				</span>

				<?php if ( $this->tooltip ) : ?>
					<div class="tooltip">
						<?php echo $this->tooltip; ?>
					</div>
				<?php endif; ?>
			</label>
		</td>
		<td class="col-input">
			<div class="ac-setting-input ac-setting-input-label">

				<?php echo $this->setting; ?>
				<button class="ac-setting-label-icon">Choose Icon</button>
				<div class="acp-ipicker">
					<?php
					$icons = array( 'format-aside', 'format-image', 'format-video', 'format-quote', 'format-audio', 'format-chat', 'wordpress', 'menu', 'dashboard', 'admin-links', 'admin-page', 'admin-comments' );
					?>
					<div class="acp-ipicker__icons">
						<?php foreach ( $icons as $icon ): ?>
							<div class="acp-ipicker__icon" data-dashicon="<?php echo $icon; ?>">
								<span class="dashicons dashicons-<?php echo $icon; ?>"></span>
							</div>
						<?php endforeach; ?>
					</div>
					<div class="acp-ipicker__handles">
						<button class="button">Select</button>
						<br>
						<a href="">Cancel</a>
					</div>

				</div>
			</div>
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