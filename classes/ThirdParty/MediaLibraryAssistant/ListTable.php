<?php

declare(strict_types=1);

namespace AC\ThirdParty\MediaLibraryAssistant;

use AC;
use MLA_List_Table;
use MLAData;

class ListTable implements AC\ListTable
{

    private $table;

    public function __construct(MLA_List_Table $table)
    {
        $this->table = $table;
    }

    public function get_column_value(string $column, $id): string
    {
        $item = (object)MLAData::mla_get_attachment_by_id($id);

        if ( ! $item) {
            return '';
        }

        $method = 'column_' . $column;

        if (method_exists($this->table, $method)) {
            return (string)call_user_func([$this->table, $method], $item);
        }

        return (string)$this->table->column_default($item, $column);
    }

    public function get_total_items(): int
    {
        return $this->table->get_pagination_arg('total_items');
    }

    public function render_row($id): string
    {
        ob_start();

        $this->table->single_row($this->get_attachment($id));

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