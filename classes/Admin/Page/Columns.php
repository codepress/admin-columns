<?php

declare(strict_types=1);

namespace AC\Admin\Page;

use AC\Admin;
use AC\Admin\RenderableHead;
use AC\Admin\ScreenOption;
use AC\Asset\Assets;
use AC\Asset\Enqueueables;
use AC\Asset\Location\Absolute;
use AC\Asset\Script;
use AC\Asset\Style;
use AC\ColumnTypeRepository;
use AC\Renderable;
use AC\Storage\Repository\EditorFavorites;
use AC\Table\TableScreenCollection;
use AC\Table\TableScreenRepository;
use AC\TableScreen;
use AC\Type\ListScreenId;

class Columns implements Enqueueables, Admin\ScreenOptions, Renderable, RenderableHead
{

    public const NAME = 'columns';

    private $location;

    private $head;

    private $table_screen;

    private $list_id;

    private $menu_items;

    private $favorite_repository;

    private $table_screen_repository;

    private $column_type_repository;

    private $uninitialized_screens;

    public function __construct(
        Absolute $location,
        TableScreenCollection $uninitialized_screens,
        Renderable $head,
        TableScreen $table_screen,
        ColumnTypeRepository $column_type_repository,
        Admin\MenuListItems $menu_items,
        EditorFavorites $favorite_repository,
        TableScreenRepository $table_screen_repository,
        ListScreenId $list_id = null
    ) {
        $this->location = $location;
        $this->head = $head;
        $this->table_screen = $table_screen;
        $this->column_type_repository = $column_type_repository;
        $this->list_id = $list_id;
        $this->menu_items = $menu_items;
        $this->favorite_repository = $favorite_repository;
        $this->table_screen_repository = $table_screen_repository;
        $this->uninitialized_screens = $uninitialized_screens;
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
                $this->column_type_repository,
                $this->uninitialized_screens,
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

    public function render(): string
    {
        return '<div></div>';
    }

}