<?php

use AC\Settings\Column;
use AC\Settings\Column\Type;

if ( ! defined('ABSPATH')) {
    exit;
}

/**
 * @global AC\Column   $column
 * @global AC\Column[] $column_types
 */

$column = $this->column;
$column_types = $this->column_types;

?>

<form class="ac-column ac-<?= esc_attr($column->get_type()) ?>"
		data-type="<?= esc_attr($column->get_type()) ?>"
		data-original="<?= esc_attr($column->is_original()) ?>"
		data-column-name="<?= esc_attr($column->get_name()) ?>">

	<div class="ac-column__header">
		<div class="ac-column__header__move" data-sort-handle>
			<span class="cpacicon-move"></span>
		</div>
		<div class="ac-column__header__label">
			<a class="ac-column__header__label__link" data-toggle="column" data-column-label>
                <?= $column->get_setting('label')->get_value() ?>
			</a>

			<div class="ac-column__header__info">
				<small class="column-id">
                    <?= sprintf(
                        '%s: %s',
                        __('Name', 'codepress-admin-columns'),
                        $column->get_name()
                    ); ?>
				</small>
				<small class="column-type">
                    <?= sprintf(
                        '%s: %s',
                        __('Type', 'codepress-admin-columns'),
                        $column->get_type()
                    ); ?>
				</small>
			</div>

			<div class="ac-column__header__actions">
				<a class="edit-button" data-toggle="column">
                    <?php
                    _e('Edit', 'codepress-admin-columns'); ?>
				</a>
				<a class="close-button" data-toggle="column">
                    <?php
                    _e('Close', 'codepress-admin-columns'); ?>
				</a>
                <?php
                if ( ! $column->is_original()) : ?>
					<a class="clone-button"><?php
                        _e('Clone', 'codepress-admin-columns'); ?>
					</a>
                <?php
                endif; ?>
				<a class="remove-button" data-remove-column>
                    <?php
                    _e('Remove', 'codepress-admin-columns'); ?>
				</a>
			</div>
		</div>
		<div class="ac-column__header__features">
            <?php

            foreach ($column->get_settings() as $setting) {
                if ($setting instanceof Column) {
                    echo $setting->render_header() . "\n";
                }
            }

            do_action('ac/column/header', $column);

            ?>
		</div>
		<div class="ac-column__header__type" data-toggle="column">
			<span><?= ac_helper()->html->strip_attributes($column->get_label(), ['style', 'class']) ?></span>
		</div>
		<div class="ac-column__header__arrow" data-toggle="column">
			<span class="dashicons dashicons-arrow-down"></span>
		</div>
	</div>

	<div class="ac-column-body">
		<div class="ac-column-settings">

            <?php

            $setting = new Type($column, $column_types);
            echo $setting->render();

            foreach ($column->get_settings() as $setting) {
                echo $setting->render() . "\n";
            }

            ?>

			<table class="ac-column-setting ac-column-setting-actions">
				<tr>
					<td class="col-label"></td>
					<td class="col-settings">
						<p>
							<a href="#" class="close-button" data-toggle="column">
                                <?php
                                _e('Close', 'codepress-admin-columns'); ?>
							</a>
                            <?php
                            if ( ! $column->is_original()) : ?>
								<a class="clone-button" href="#">
                                    <?php
                                    _e('Clone', 'codepress-admin-columns'); ?>
								</a>
                            <?php
                            endif; ?>
							<a href="#" class="remove-button" data-remove-column>
                                <?php
                                _e('Remove'); ?>
							</a>
						</p>
					</td>
				</tr>

			</table>
		</div>
	</div>
</form>