<?php

use AC\Column;
use AC\ListScreen;
use AC\View;

if ( ! defined('ABSPATH')) {
    exit;
}

/**
 * @var Column[]        $columns
 * @var Column[]        $column_types
 * @var ListScreen|null $list_screen
 */
$columns = $this->columns;
$column_types = $this->column_types;
$list_screen = $this->list_screen;

?>

<div class="ac-admin__main">

    <?php
    if ($list_screen) :
        do_action('ac/settings/notice', $this->list_screen);
    endif;
    ?>

	<div id="listscreen_settings" data-form="listscreen" class="<?= $this->is_disabled ? '-disabled' : ''; ?>">

        <?php
        do_action('ac/settings/before_columns', $this->list_screen); ?>

		<div class="ac-boxes <?= esc_attr($this->class); ?>">
			<div class="ac-columns">
                <?php
                wp_nonce_field('update-type', '_ac_nonce', false); ?>
				<input type="hidden" name="list_screen" value="<?= esc_attr($this->list_key); ?>"/>
				<input type="hidden" name="list_screen_id" value="<?= esc_attr($this->list_id); ?>">

                <?php
                foreach ($columns as $column) {
                    $view = new View([
                        'column'       => $column,
                        'column_types' => $column_types,
                    ]);

                    echo $view->set_template('admin/edit-column');
                }
                ?>
			</div>

			<div class="column-footer">

                <?php
                if ($this->show_actions) : ?>

					<div class="button-container">

                        <?php
                        if ($this->show_clear_all) : ?>
							<a class="clear-columns" data-clear-columns><?php
                                _e('Clear all columns ', 'codepress-admin-columns') ?></a>
                        <?php
                        endif; ?>

						<span class="spinner"></span>
						<a class="button-primary submit update"><?php
                            _e('Update'); ?></a>
						<a class="button-primary submit save"><?php
                            _e('Save'); ?></a>
						<a class="add_column button">+ <?php
                            _e('Add Column', 'codepress-admin-columns'); ?></a>
					</div>

                <?php
                endif; ?>

			</div>


		</div>

        <?php
        do_action('ac/settings/after_columns', $this->list_screen); ?>
	</div>

</div>
