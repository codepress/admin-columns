<?php

use AC\Form\Element\Select;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<section class="ac-setbox ac-ls-settings ac-section" data-section="ls-settings">
	<header class="ac-section__header">
		<div class="ac-setbox__header__title"><?= __( 'Settings', 'codepress-admin-columns' ); ?>
			<small>(<?= __( 'optional', 'codepress-admin-columns' ); ?>)</small>
		</div>
	</header>
	<form>

		<div class="ac-setbox__row">
			<div class="ac-setbox__row__th">
				<label><?= __( 'Conditionals', 'codepress-admin-columns' ); ?></label>
				<small><?= __( 'Make this column set available only for specific users or roles.', 'codepress-admin-columns' ); ?></small>
			</div>
			<div class="ac-setbox__row__fields">
				<div class="ac-setbox__row__fields__inner">
					<fieldset>
						<div class="row roles">
							<label for="layout-roles-<?php echo $this->id; ?>" class="screen-reader-text">
								<?php _e( 'Roles', 'codepress-admin-columns' ); ?>
								<span>(<?php _e( 'optional', 'codepress-admin-columns' ); ?>)</span>
							</label>

							<?php echo $this->select_roles; ?>

						</div>
						<div class="row users">
							<label for="layout-users-<?php echo $this->id; ?>" class="screen-reader-text">
								<?php _e( 'Users' ); ?>
								<span>(<?php _e( 'optional', 'codepress-admin-columns' ); ?>)</span>
							</label>

							<?php echo $this->select_users; ?>
						</div>
					</fieldset>
				</div>
			</div>
		</div>

		<div class="ac-setbox__row" id="hide-on-screen">
			<div class="ac-setbox__row__th">
				<label><?= __( 'Hide on screen', 'codepress-admin-columns' ); ?></label>
				<small><?= __( 'Select items to hide from the list table screen.', 'codepress-admin-columns' ); ?></small>
			</div>
			<div class="ac-setbox__row__fields">
				<div class="ac-setbox__row__fields__inner">
					<div class="checkbox-labels checkbox-labels vertical">

						<?= $this->hide_on_screen; ?>

					</div>
				</div>
			</div>
		</div>

			<div class="ac-setbox__row">
				<div class="ac-setbox__row__th">
					<label><?= __( 'Preferences', 'codepress-admin-columns' ); ?></label>
					<small><?= __( 'Set default settings that users will see when they visit the list table.', 'codepress-admin-columns' ); ?></small>
				</div>

				<div class="ac-setbox__row__fields -has-subsettings -subsetting-total-3">
						<?php
						$pref_hs = 'off';
						?>
						<div class="ac-setbox__row -sub -horizontal-scrolling">
							<div class="ac-setbox__row__th">
								<label><?= __( 'Horizontal Scrolling', 'codepress-admin-columns' ); ?></label>
							</div>
							<div class="ac-setbox__row__fields">
								<div class="ac-setbox__row__fields__inner">
									<div class="radio-labels radio-labels">
										<label class="ac-setting-input_filter"><input name="horizontal_scrolling" type="radio" value="on" <?php checked( $pref_hs, 'on' ); ?>><?= __( 'Yes' ); ?></label>
										<label class="ac-setting-input_filter"><input name="horizontal_scrolling" type="radio" value="off" <?php checked( $pref_hs, 'off' ); ?>><?= __( 'No' ); ?></label>
									</div>
								</div>
							</div>
						</div>
						<?php
						$selected_order_by = 0;

						$selected_order = 'asc';
						?>
						<div class="ac-setbox__row -sub -sorting" data-setting="sorting-preference">
							<div class="ac-setbox__row__th">
								<label><?= __( 'Sorting', 'codepress-admin-columns' ); ?></label>
							</div>
							<div class="ac-setbox__row__fields">
								<div class="ac-setbox__row__fields__inner">
									<div class="radio-labels radio-labels">
										<?php
										$select = new Select( 'sorting', [
											0 => __( 'Default' ),
										] );
										echo $select->set_attribute( 'data-sorting', $selected_order_by );

										$select = new Select( 'sorting_order', [
											'asc'  => __( 'Ascending' ),
											'desc' => __( 'Descending' ),
										] );
										echo $select->set_class( 'sorting_order' )->set_value( $selected_order );

										?>
									</div>
								</div>
							</div>
						</div>

						<?php
						$segments = $this->segments;

						$selected_filter_segment = '';
						?>

						<div class="ac-setbox__row -sub -predefinedfilters" data-setting="filter-segment-preference">
							<div class="ac-setbox__row__th">
								<label><?= __( 'Pre-applied Filters', 'codepress-admin-columns' ); ?></label>
							</div>
							<div class="ac-setbox__row__fields">
								<div class="ac-setbox__row__fields__inner">
									<?php if ( ! empty( $segments ) ): ?>
										<?php
										$select = new Select( 'filter_segment', [ '' => __( 'Default', 'codepress-admin-columns' ) ] + $segments );
										echo $select->set_value( $selected_filter_segment );
										?>
									<?php else: ?>
										<p class="ac-setbox__descriptive">
											<?php _e( "No public saved filters available.", 'codepress-admin-columns' ); ?>
										</p>
									<?php endif; ?>
								</div>
							</div>
						</div>

				</div>
			</div>

	</form>
</section>