<?php

namespace AC\Table;

use AC;
use AC\Asset;
use AC\Capabilities;
use AC\ColumnSize;
use AC\DefaultColumnsRepository;
use AC\Form;
use AC\ListScreen;
use AC\Registerable;
use AC\Renderable;
use AC\ScreenController;
use AC\Settings;
use AC\Type\EditorUrlFactory;

final class Screen implements Registerable
{

    /**
     * @var Asset\Location\Absolute
     */
    private $location;

    /**
     * @var ListScreen
     */
    private $list_screen;

    /**
     * @var Form\Element[]
     */
    private $screen_options;

    /**
     * @var Button[]
     */
    private $buttons = [];

    /**
     * @var ColumnSize\ListStorage
     */
    private $column_size_list_storage;

    /**
     * @var ColumnSize\UserStorage
     */
    private $column_size_user_storage;

    private $primary_column_factory;

    private $table_screen;

    public function __construct(
        Asset\Location\Absolute $location,
        AC\TableScreen $table_screen,
        ColumnSize\ListStorage $column_size_list_storage,
        ColumnSize\UserStorage $column_size_user_storage,
        PrimaryColumnFactory $primary_column_factory,
        ListScreen $list_screen = null
    ) {
        $this->location = $location;
        $this->list_screen = $list_screen;
        $this->column_size_list_storage = $column_size_list_storage;
        $this->column_size_user_storage = $column_size_user_storage;
        $this->primary_column_factory = $primary_column_factory;
        $this->table_screen = $table_screen;
    }

    /**
     * Register hooks
     */
    public function register(): void
    {
        if ($this->list_screen) {
            $controller = new ScreenController(
                new DefaultColumnsRepository($this->table_screen->get_key()),
                $this->table_screen,
                $this->list_screen
            );
            $controller->register();

            $render = new TableFormView(
                $this->list_screen->get_meta_type(),
                sprintf('<input type="hidden" name="layout" value="%s">', $this->list_screen->get_id())
            );
            $render->register();

            add_filter('list_table_primary_column', [$this, 'set_primary_column'], 20);
            add_action('admin_head', [$this, 'admin_head_scripts']);
            add_action('admin_footer', [$this, 'admin_footer_scripts']);
        }

        (new AdminHeadStyle())->register();

        add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);
        add_action('admin_head', [$this, 'register_settings_button']);
        add_filter('admin_body_class', [$this, 'admin_class']);
        add_action('admin_footer', [$this, 'render_actions']);
        add_filter('screen_settings', [$this, 'screen_options']);
    }

    public function set_primary_column($default): string
    {
        return $this->primary_column_factory->create($this->list_screen)
                                            ->set_primary_column($default);
    }

    public function get_buttons(): array
    {
        return array_merge([], ...$this->buttons);
    }

    public function register_button(Button $button, int $priority = 10): bool
    {
        $button->set_attribute('data-priority', $priority);
        $this->buttons[$priority][] = $button;

        ksort($this->buttons, SORT_NUMERIC);

        return true;
    }

    /**
     * Adds a body class which is used to set individual column widths
     *
     * @param string $classes body classes
     *
     * @return string
     * @since 1.4.0
     */
    public function admin_class($classes)
    {
        $classes .= ' ac-' . $this->table_screen->get_key();

        return apply_filters('ac/table/body_class', $classes, $this);
    }

    public function register_settings_button()
    {
        if ( ! current_user_can(Capabilities::MANAGE)) {
            return;
        }

        $edit_button = new Settings\Option\EditButton();

        if ( ! $edit_button->is_enabled()) {
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

    public function admin_scripts(): void
    {
        $style = new Asset\Style('ac-table', $this->location->with_suffix('assets/css/table.css'), ['ac-ui']);
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
            'meta_type'        => (string)$this->table_screen->get_meta_type(),
            'list_screen_link' => $this->get_list_screen_clear_link(),
            'current_user_id'  => get_current_user_id(),
            'number_format'    => [
                'decimal_point' => $this->get_local_number_format('decimal_point'),
                'thousands_sep' => $this->get_local_number_format('thousands_sep'),
            ],
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

        // Column specific scripts
        if ($this->list_screen) {
            foreach ($this->list_screen->get_columns() as $column) {
                $column->scripts();
            }
        }
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
            case $this->list_screen instanceof ListScreen\Post :
                $query_args_whitelist[] = 'post_status';
                break;
            case $this->list_screen instanceof ListScreen\User :
                $query_args_whitelist[] = 'role';
                break;
            case $this->list_screen instanceof ListScreen\Comment :
                $query_args_whitelist[] = 'comment_status';
                break;
        }

        foreach ($query_args_whitelist as $query_arg) {
            if (isset($_GET[$query_arg])) {
                $url = $url->with_arg($query_arg, $_GET[$query_arg]);
            }
        }

        return (string)$url;
    }

    private function get_column_types_mapping(): array
    {
        $types = [];
        foreach ($this->list_screen->get_columns() as $column) {
            $types[$column->get_name()] = $column->get_type();
        }

        return $types;
    }

    // TODO check consumers, because this can return `null` now
    public function get_list_screen(): ?ListScreen
    {
        return $this->list_screen;
    }

    public function admin_head_scripts(): void
    {
        $inline_style = new AC\Table\InlineStyle\ColumnSize(
            $this->list_screen,
            $this->column_size_list_storage,
            $this->column_size_user_storage
        );

        echo $inline_style->render();

        /**
         * Add header scripts that only apply to column screens
         */
        do_action('ac/admin_head', $this->list_screen, $this);
    }

    public function admin_footer_scripts(): void
    {
        do_action('ac/table/admin_footer', $this->list_screen, $this);
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

    /**
     * @param string $html
     *
     * @return string
     */
    public function screen_options($html)
    {
        if (empty($this->screen_options)) {
            return $html;
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

}