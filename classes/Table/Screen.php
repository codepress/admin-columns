<?php

namespace AC\Table;

use AC;
use AC\Asset;
use AC\Asset\Location\Absolute;
use AC\Capabilities;
use AC\ListScreen;
use AC\Registerable;
use AC\Renderable;
use AC\TableScreen;
use AC\Type\EditorUrlFactory;

final class Screen implements Registerable
{

    private Absolute $location;

    private AC\TableScreen $table_screen;

    private ?AC\ListScreen $list_screen;

    private array $buttons = [];

    private array $screen_options = [];

    public function __construct(
        AC\AdminColumns $plugin,
        TableScreen $table_screen,
        ListScreen $list_screen = null
    ) {
        $this->location = $plugin->get_location();
        $this->table_screen = $table_screen;
        $this->list_screen = $list_screen;
    }

    public function register(): void
    {
        (new AC\Table\AdminHeadStyle())->register();

        add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);
        add_filter('admin_body_class', [$this, 'admin_class']);
        add_action('admin_footer', [$this, 'render_actions']);
        add_filter('screen_settings', [$this, 'screen_options']);
        add_action('admin_head', [$this, 'register_settings_button']);
    }

    private function get_buttons(): array
    {
        return array_merge([], ...$this->buttons);
    }

    public function register_button(Button $button, int $priority = 10): void
    {
        $button->set_attribute('data-priority', $priority);

        $this->buttons[$priority][] = $button;

        ksort($this->buttons, SORT_NUMERIC);
    }

    public function get_list_screen(): ?ListScreen
    {
        return $this->list_screen;
    }

    public function register_settings_button()
    {
        if ( ! current_user_can(Capabilities::MANAGE)) {
            return;
        }

        $edit_button = new AC\Storage\Repository\EditButton();

        if ( ! $edit_button->is_active()) {
            return;
        }

        $url = EditorUrlFactory::create(
            $this->table_screen->get_key(),
            $this->table_screen->is_network(),
            $this->list_screen ? $this->list_screen->get_id() : null
        );

        $button = new Button('edit-columns');
        $button->set_label(__('Edit columns', 'codepress-admin-columns'))
               ->set_url((string)$url)
               ->set_dashicon('admin-generic');

        $this->register_button($button, 1);
    }

    public function render_actions(): void
    {
        ?>
		<div id="ac-table-actions" class="ac-table-actions">

            <?php
            $this->render_buttons(); ?>

            <?php
            do_action('ac/table/actions', $this); ?>
		</div>
        <?php
    }

    private function render_buttons(): void
    {
        ?>
		<div class="ac-table-actions-buttons">
            <?php
            foreach ($this->get_buttons() as $button) {
                $button->render();
            }
            ?>
		</div>
        <?php
    }

    public function register_screen_option(Renderable $option): void
    {
        $this->screen_options[] = $option;
    }

    public function screen_options($html): string
    {
        if (empty($this->screen_options)) {
            return (string)$html;
        }

        ob_start();
        ?>

		<fieldset class='acp-screen-option-prefs'>
			<legend><?= __('Admin Columns', 'codepress-admin-columns'); ?></legend>
			<div class="acp-so-container">
                <?php

                foreach ($this->screen_options as $option) {
                    echo $option->render();
                }

                ?>
			</div>
		</fieldset>

        <?php

        $html .= ob_get_clean();

        return $html;
    }

    public function admin_scripts(): void
    {
        $style = new Asset\Style(
            'ac-table',
            $this->location->with_suffix('assets/css/table.css'),
            ['ac-ui']
        );
        $style->enqueue();

        $table_translation = Asset\Script\Localize\Translation::create([
            'value_loading' => __('Loading...', 'codepress-admin-columns'),
            'edit'          => __('Edit', 'codepress-admin-columns'),
            'download'      => __('Download', 'codepress-admin-columns'),
        ]);

        $script = new Asset\Script(
            'ac-table',
            $this->location->with_suffix('assets/js/table.js'),
            ['jquery', Asset\Script\GlobalTranslationFactory::HANDLE]
        );

        $args = [
            'layout'           => '',
            'column_types'     => '',
            'read_only'        => false,
            'assets'           => $this->location->with_suffix('assets/')->get_url(),
            'list_screen'      => (string)$this->table_screen->get_key(),
            'ajax_nonce'       => wp_create_nonce('ac-ajax'),
            'table_id'         => $this->table_screen->get_attr_id(),
            'screen'           => $this->table_screen->get_screen_id(),
            'list_screen_link' => $this->get_list_screen_clear_link(),
            'current_user_id'  => get_current_user_id(),
            'number_format'    => [
                'decimal_point' => $this->get_local_number_format('decimal_point'),
                'thousands_sep' => $this->get_local_number_format('thousands_sep'),
            ],
            'meta_type'        => $this->table_screen instanceof AC\TableScreen\MetaType
                ? (string)$this->table_screen->get_meta_type()
                : '',
        ];

        if ($this->list_screen) {
            $args['column_types'] = $this->get_column_types_mapping();
            $args['layout'] = (string)$this->list_screen->get_id();
            $args['read_only'] = $this->list_screen->is_read_only();

            do_action('ac/table_scripts', $this->list_screen, $this);
        }

        $script
            ->add_inline_variable('AC', $args)
            ->localize('AC_I18N', $table_translation)
            ->enqueue();
    }

    public function admin_class($classes): string
    {
        $classes .= ' ac-' . $this->table_screen->get_key();

        return (string)apply_filters('ac/table/body_class', $classes, $this);
    }

    private function get_column_types_mapping(): array
    {
        $types = [];
        foreach ($this->list_screen->get_columns() as $column) {
            $types[(string)$column->get_id()] = $column->get_type();
        }

        return $types;
    }

    private function get_local_number_format(string $var)
    {
        global $wp_locale;

        return $wp_locale->number_format[$var] ?? null;
    }

    private function get_list_screen_clear_link(): string
    {
        $url = $this->list_screen
            ? $this->list_screen->get_table_url()
            : $this->table_screen->get_url();

        $query_args_whitelist = [
            'layout',
            'orderby',
            'order',
        ];

        switch (true) {
            case $this->table_screen instanceof AC\PostType :
                $query_args_whitelist[] = 'post_status';
                break;
            case $this->table_screen instanceof AC\TableScreen\User :
                $query_args_whitelist[] = 'role';
                break;
            case $this->table_screen instanceof AC\TableScreen\Comment :
                $query_args_whitelist[] = 'comment_status';
                break;
        }

        foreach ($query_args_whitelist as $query_arg) {
            if (isset($_GET[$query_arg]) && is_string($_GET[$query_arg])) {
                $url = $url->with_arg($query_arg, $_GET[$query_arg]);
            }
        }

        return (string)$url;
    }

}