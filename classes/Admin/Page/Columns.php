<?php

declare(strict_types=1);

namespace AC\Admin\Page;

use AC\Admin;
use AC\Admin\Banner;
use AC\Admin\RenderableHead;
use AC\Admin\ScreenOption;
use AC\Asset\Assets;
use AC\Asset\Enqueueables;
use AC\Asset\Location;
use AC\Asset\Script;
use AC\Asset\Style;
use AC\Column;
use AC\ColumnTypesFactory;
use AC\Renderable;
use AC\Storage\Repository\EditorFavorites;
use AC\Table\TableScreenCollection;
use AC\Table\TableScreenRepository;
use AC\TableScreen;
use AC\Type\ListScreenId;
use AC\Type\Url;
use AC\Type\Url\Documentation;
use AC\Type\Url\Site;
use AC\Type\Url\Tweet;
use AC\Type\Url\UtmTags;
use AC\View;

class Columns implements Enqueueables, Admin\ScreenOptions, Renderable, RenderableHead
{

    public const NAME = 'columns';

    private $location;

    private $uninitialized_table_screens;

    private $menu;

    private $head;

    private $table_screen;

    private $column_types_factory;

    private $list_id;

    private $menu_items;

    private $favorite_repository;

    private $table_screen_repository;

    public function __construct(
        Location\Absolute $location,
        TableScreenCollection $uninitialized_table_screens,
        Renderable $head,
        TableScreen $table_screen,
        ColumnTypesFactory\Aggregate $column_types_factory,
        Admin\MenuListItems $menu_items,
        EditorFavorites $favorite_repository,
        TableScreenRepository $table_screen_repository,
        ListScreenId $list_id = null
    ) {
        $this->location = $location;
        $this->uninitialized_table_screens = $uninitialized_table_screens;
        $this->head = $head;
        $this->table_screen = $table_screen;
        $this->column_types_factory = $column_types_factory;
        $this->list_id = $list_id;
        $this->menu_items = $menu_items;
        $this->favorite_repository = $favorite_repository;
        $this->table_screen_repository = $table_screen_repository;
    }

    public function get_table_screen(): TableScreen
    {
        return $this->table_screen;
    }

    public function render_head(): Renderable
    {
        return $this->head;
    }

    public function get_assets(): Assets
    {
        return new Assets([
            new Style(
                'jquery-ui-lightness', $this->location->with_suffix('assets/ui-theme/jquery-ui-1.8.18.custom.css')
            ),
            new Script('jquery-ui-slider'),
            new Admin\Asset\Columns(
                'ac-admin-page-columns',
                $this->location->with_suffix('assets/js/admin-page-columns.js'),
                $this->table_screen,
                $this->column_types_factory,
                $this->uninitialized_table_screens,
                $this->menu_items,
                $this->table_screen_repository,
                $this->favorite_repository,
                $this->list_id
            ),
            new Style(
                'ac-admin-page-columns-css',
                $this->location->with_suffix('assets/css/admin-page-columns.css'),
                ['ac-utilities']
            ),
            new Style('ac-select2'),
            new Script('ac-select2'),
        ]);
    }

    private function get_column_id(): ScreenOption\ColumnId
    {
        return new ScreenOption\ColumnId(new Admin\Preference\ScreenOptions());
    }

    private function get_column_type(): ScreenOption\ColumnType
    {
        return new ScreenOption\ColumnType(new Admin\Preference\ScreenOptions());
    }

    private function get_list_screen_id(): ScreenOption\ListScreenId
    {
        return new ScreenOption\ListScreenId(new Admin\Preference\ScreenOptions());
    }

    private function get_list_screen_type(): ScreenOption\ListScreenType
    {
        return new ScreenOption\ListScreenType(new Admin\Preference\ScreenOptions());
    }

    public function get_screen_options(): array
    {
        return [
            $this->get_column_id(),
            $this->get_column_type(),
            $this->get_list_screen_id(),
            $this->get_list_screen_type(),
        ];
    }

    private function get_tweet_url(): Url
    {
        return new Tweet(
            __("I'm using Admin Columns for WordPress!", 'codepress-admin-columns'),
            new Url\WordpressPluginRepo(),
            Tweet::TWITTER_HANDLE,
            'admincolumns'
        );
    }

    private function is_initialized(): bool
    {
        foreach ($this->uninitialized_table_screens as $uninitialized_table_screen) {
            if ($uninitialized_table_screen->get_key()->equals($this->table_screen->get_key())) {
                return false;
            }
        }

        return true;
    }

    public function render(): string
    {
        return '<div></div>';
    }

    // TODO remove
    public function __render(): string
    {
        return '<div></div>';

        // TODO
        if ( ! $this->is_initialized()) {
            $modal = new View([
                'message' => 'Loading columns',
            ]);
            $modal->set_template('admin/loading-message');

            return $this->menu->render(
                    (string)$this->table_screen->get_key(),
                    (string)$this->list_screen->get_table_url(),
                    true
                ) . $modal->render();
        }

        $classes = [];

        if ($this->storage->exists($this->list_screen->get_id())) {
            $classes[] = 'stored';
        }

        if ($this->get_list_screen_id()->is_active()) {
            $classes[] = 'show-list-screen-id';
        }

        if ($this->get_list_screen_type()->is_active()) {
            $classes[] = 'show-list-screen-type';
        }

        return '<div></div>';

        // TODO
        //$list_id = (string)$this->list_screen->get_id();

        ob_start();
        ?>
		<h1 class="screen-reader-text"><?= __('Columns', 'codepress-admin-columns'); ?></h1>
		<div class="ac-admin <?= esc_attr(implode(' ', $classes)) ?>" data-type="<?= esc_attr(
            (string)$this->table_screen->get_key()
        ); ?>">
			<div class="ac-admin__header">

                <?= $this->menu->render(
                    (string)$this->table_screen->get_key(),
                    (string)$this->list_screen->get_table_url()
                ) ?>

                <?php
                do_action('ac/settings/after_title', $this->list_screen); ?>

			</div>
			<div class="ac-admin__wrap">

				<div class="ac-admin__sidebar">
                    <?php
                    if ( ! $this->list_screen->is_read_only()) : ?>

                        <?php

                        $label_main = __('Store settings', 'codepress-admin-columns');
                        $label_second = sprintf(
                            '<span class="clear contenttype">%s</span>',
                            esc_html($this->table_screen->get_labels()->get_plural())
                        );

                        $truncated_label = 18 > strlen($label_main)
                            ? $this->get_truncated_side_label(
                                $this->table_screen->get_labels()->get_plural(),
                                $label_main
                            )
                            : null;

                        if ($truncated_label) {
                            $label_second = sprintf(
                                '<span class="right contenttype">%s</span>',
                                esc_html($truncated_label)
                            );
                        }

                        $delete_confirmation_message = false;

                        if (apply_filters('ac/delete_confirmation', true)) {
                            $delete_confirmation_message = sprintf(
                                __(
                                    "Warning! The %s columns data will be deleted. This cannot be undone. 'OK' to delete, 'Cancel' to stop",
                                    'codepress-admin-columns'
                                ),
                                "'" . $this->list_screen->get_title() . "'"
                            );
                        }

                        $actions = new View([
                            'label_main'                  => $label_main,
                            'label_second'                => $label_second,
                            'list_screen_key'             => (string)$this->table_screen->get_key(),
                            'list_screen_id'              => $list_id,
                            'delete_confirmation_message' => $delete_confirmation_message,
                        ]);

                        echo $actions->set_template('admin/edit-actions');

                    endif; ?>

                    <?php
                    do_action('ac/settings/sidebox', $this->list_screen); ?>

                    <?php
                    if (apply_filters('ac/show_banner', true)) : ?>

                        <?= new Banner() ?>

                        <?php
                        $view = new View([
                            'documentation_url' => (new UtmTags(new Documentation(), 'feedback-docs-button'))->get_url(
                            ),
                            'upgrade_url'       => (new UtmTags(
                                new Site(Site::PAGE_ABOUT_PRO),
                                'feedback-purchase-button'
                            ))->get_url(),
                            'tweet_url'         => $this->get_tweet_url()->get_url(),
                            'review_url'        => (new Url\WordpressPluginReview())->get_url(),
                        ]);

                        echo $view->set_template('admin/side-feedback');
                        ?>

                    <?php
                    endif; ?>

                    <?php
                    $view = new View([
                        'documentation_url' => (new UtmTags(new Documentation(), 'support'))->get_url(),
                    ]);
                    echo $view->set_template('admin/side-support');
                    ?>

				</div>

                <?php

                $classes = [];

                if ($this->list_screen->is_read_only()) {
                    $classes[] = 'disabled';
                }

                if ($this->get_column_id()->is_active()) {
                    $classes[] = 'show-column-id';
                }

                if ($this->get_column_type()->is_active()) {
                    $classes[] = 'show-column-type';
                }

                $columns = new View([
                    'class'          => implode(' ', $classes),
                    'list_key'       => (string)$this->table_screen->get_key(),
                    'list_id'        => $list_id,
                    'is_disabled'    => $this->list_screen->is_read_only(),
                    'title'          => $this->list_screen->get_title(),
                    'columns'        => $this->list_screen->get_columns(),
                    'column_types'   => iterator_to_array($this->column_types_factory->create($this->table_screen)),
                    'list_screen'    => $this->list_screen,
                    'show_actions'   => ! $this->list_screen->is_read_only(),
                    'show_clear_all' => apply_filters('ac/enable_clear_columns_button', false),
                ]);

                echo $columns->set_template('admin/edit-columns');

                ?>

			</div>

			<div id="add-new-column-template">
                <?= $this->render_column_template() ?>
			</div>

		</div>

		<div class="clear"></div>

        <?php

        $modal = new View([
            'upgrade_url' => (new UtmTags(new Site(Site::PAGE_ABOUT_PRO), 'upgrade'))->get_url(),
        ]);

        echo $modal->set_template('admin/modal-pro');

        return ob_get_clean();
    }

    private function get_column_template_by_group(array $column_types, string $group = ''): ?Column
    {
        if ( ! $group) {
            return array_shift($column_types) ?: null;
        }

        $columns = [];

        foreach ($column_types as $column_type) {
            if ($group === $column_type->get_group()) {
                $columns[$column_type->get_label()] = $column_type;
            }
        }

        $column_keys = array_keys($columns);
        array_multisort($column_keys, SORT_NATURAL, $columns);

        $column = array_shift($columns);

        if ( ! $column) {
            return null;
        }

        return $column;
    }

    private function render_column_template(): string
    {
        $column_types = iterator_to_array($this->column_types_factory->create($this->table_screen));

        $column = $this->get_column_template_by_group($column_types, 'custom');

        if ( ! $column) {
            $column = $this->get_column_template_by_group($column_types);
        }

        if ( ! $column) {
            return '';
        }

        $view = new View([
            'column'       => $column,
            'column_types' => $column_types,
        ]);

        return $view->set_template('admin/edit-column')->render();
    }

    private function get_truncated_side_label(string $label, string $main_label = ''): string
    {
        if (34 < (strlen($label) + (strlen($main_label) * 1.1))) {
            $label = substr($label, 0, absint(34 - (strlen($main_label) * 1.1))) . '...';
        }

        return $label;
    }

}