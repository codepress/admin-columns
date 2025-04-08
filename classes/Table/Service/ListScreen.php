<?php

namespace AC\Table\Service;

use AC;
use AC\Registerable;
use AC\Services;
use AC\Table\PrimaryColumnFactory;
use AC\Table\TableFormView;

final class ListScreen implements Registerable
{

    private AC\ListScreen $list_screen;

    private PrimaryColumnFactory $primary_column_factory;

    private AC\Table\InlineStyle\ColumnSize $column_size;

    public function __construct(
        AC\ListScreen $list_screen,
        PrimaryColumnFactory $primary_column_factory,
        AC\Table\InlineStyle\ColumnSize $column_size
    ) {
        $this->list_screen = $list_screen;
        $this->primary_column_factory = $primary_column_factory;
        $this->column_size = $column_size;
    }

    public function register(): void
    {
        $this->create_services()->register();

        add_filter('list_table_primary_column', [$this, 'set_primary_column'], 20);
        add_action('admin_head', [$this, 'admin_head_scripts']);
        add_action('admin_footer', [$this, 'admin_footer_scripts']);
    }

    private function create_services(): Services
    {
        return new Services([
            new TableFormView(
                $this->list_screen->get_meta_type(),
                sprintf('<input type="hidden" name="layout" value="%s">', $this->list_screen->get_id())
            ),
        ]);
    }

    public function set_primary_column($default): string
    {
        return $this->primary_column_factory->create($this->list_screen)
                                            ->set_primary_column($default);
    }

    public function admin_head_scripts(): void
    {
        echo $this->column_size->render($this->list_screen);

        do_action('ac/admin_head', $this->list_screen, $this);
    }

    public function admin_footer_scripts(): void
    {
        do_action('ac/table/admin_footer', $this->list_screen, $this);
    }

}