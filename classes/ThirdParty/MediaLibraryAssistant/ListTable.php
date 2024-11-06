<?php

declare(strict_types=1);

namespace AC\ThirdParty\MediaLibraryAssistant;

use AC;
use MLAData;

class ListTable implements AC\ListTable
{

    private WpListTableFactory $factory;

    public function __construct(WpListTableFactory $factory)
    {
        $this->factory = $factory;
    }

    public function render_cell(string $column_id, $row_id): string
    {
        $item = $this->get_attachment($row_id);

        if ( ! $item) {
            return '';
        }

        $method = 'column_' . $column_id;

        $table = $this->factory->create();

        if (method_exists($table, $method)) {
            return (string)call_user_func([$table, $method], $item);
        }

        return (string)$table->column_default($item, $column_id);
    }

    public function get_total_items(): int
    {
        return $this->factory->create()->get_pagination_arg('total_items');
    }

    public function render_row($id): string
    {
        ob_start();

        $this->factory->create()->single_row($this->get_attachment($id));

        return ob_get_clean();
    }

    private function get_attachment(int $id): object
    {
        // Author column depends on this global to be set.
        global $authordata;

        $authordata = get_userdata(get_post_field('post_author', $id));

        if ( ! class_exists('MLAData')) {
            require_once(MLA_PLUGIN_PATH . 'includes/class-mla-data.php');
            MLAData::initialize();
        }

        return (object)MLAData::mla_get_attachment_by_id($id);
    }

}