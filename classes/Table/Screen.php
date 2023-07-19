<?php

namespace AC\Table;

use AC;
use AC\Asset;
use AC\Capabilities;
use AC\ColumnSize;
use AC\Form;
use AC\ListScreen;
use AC\Registerable;
use AC\Renderable;
use AC\ScreenController;
use AC\Settings;

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

    public function __construct(
        Asset\Location\Absolute $location,
        ListScreen $list_screen,
        ColumnSize\ListStorage $column_size_list_storage,
        ColumnSize\UserStorage $column_size_user_storage,
        PrimaryColumnFactory $primary_column_factory
    ) {
        $this->location = $location;
        $this->list_screen = $list_screen;
        $this->column_size_list_storage = $column_size_list_storage;
        $this->column_size_user_storage = $column_size_user_storage;
        $this->primary_column_factory = $primary_column_factory;
    }

    /**
     * Register hooks
     */
    public function register(): void
    {
        $controller = new ScreenController($this->list_screen);
        $controller->register();

        $render = new TableFormView(
            $this->list_screen->get_meta_type(),
            sprintf('<input type="hidden" name="layout" value="%s">', $this->list_screen->get_layout_id())
        );
        $render->register();

        add_filter(
            'list_table_primary_column',
            [
                $this->primary_column_factory->create($this->list_screen),
                'set_primary_column',
            ],
            20
        );
        add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);
        add_action('admin_footer', [$this, 'admin_footer_scripts']);
        add_action('admin_head', [$this, 'admin_head_scripts']);
        add_action('admin_head', [$this, 'register_settings_button']);
        add_filter('admin_body_class', [$this, 'admin_class']);
        add_action('admin_footer', [$this, 'render_actions']);
        add_filter('screen_settings', [$this, 'screen_options']);
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
        $classes .= ' ac-' . $this->list_screen->get_key();

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

        $button = new Button('edit-columns');
        $button->set_label(__('Edit columns', 'codepress-admin-columns'))
               ->set_url((string)$this->list_screen->get_editor_url())
               ->set_dashicon('admin-generic');

        $this->register_button($button, 1);
    }

    /**
     * @since 2.2.4
     */
    public function admin_scripts()
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
        $script
            ->add_inline_variable('AC', [
                'assets'           => $this->location->with_suffix('assets/')->get_url(),
                'list_screen'      => $this->list_screen->get_key(),
                'layout'           => $this->list_screen->get_layout_id(),
                'column_types'     => $this->get_column_types_mapping(),
                'ajax_nonce'       => wp_create_nonce('ac-ajax'),
                'table_id'         => $this->list_screen->get_table_attr_id(),
                'screen'           => $this->get_current_screen_id(),
                'meta_type'        => $this->list_screen->get_meta_type(),
                'list_screen_link' => $this->get_list_screen_clear_link(),
                'current_user_id'  => get_current_user_id(),
                'number_format'    => [
                    'decimal_point' => $this->get_local_number_format('decimal_point'),
                    'thousands_sep' => $this->get_local_number_format('thousands_sep'),
                ],
            ])
            ->localize('AC_I18N', $table_translation)
            ->enqueue();

        /**
         * @param ListScreen $list_screen
         */
        do_action('ac/table_scripts', $this->list_screen, $this);

        // Column specific scripts
        foreach ($this->list_screen->get_columns() as $column) {
            $column->scripts();
        }
    }

    private function get_local_number_format(string $var)
    {
        global $wp_locale;

        return $wp_locale->number_format[$var] ?? null;
    }

    /**
     * @return string
     */
    private function get_list_screen_clear_link(): string
    {
        $url = $this->list_screen->get_table_url();

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

    /**
     * @return false|string
     */
    private function get_current_screen_id()
    {
        $screen = get_current_screen();

        if ( ! $screen) {
            return false;
        }

        return $screen->id;
    }

    /**
     * @return array
     */
    private function get_column_types_mapping()
    {
        $types = [];
        foreach ($this->list_screen->get_columns() as $column) {
            $types[$column->get_name()] = $column->get_type();
        }

        return $types;
    }

    /**
     * @return ListScreen
     */
    public function get_list_screen()
    {
        return $this->list_screen;
    }

    /**
     * Admin header scripts
     * @since 3.1.4
     */
    public function admin_head_scripts()
    {
        $inline_style = new AC\Table\InlineStyle\ColumnSize(
            $this->list_screen,
            $this->column_size_list_storage,
            $this->column_size_user_storage
        );

        echo $inline_style->render();

        /**
         * Add header scripts that only apply to column screens.
         *
         * @param ListScreen
         * @param self
         *
         * @since 3.1.4
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