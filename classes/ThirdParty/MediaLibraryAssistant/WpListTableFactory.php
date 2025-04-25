<?php

declare(strict_types=1);

namespace AC\ThirdParty\MediaLibraryAssistant;

use MLA_List_Table;

/**
 * Load the list table specifically for export. This will
 * initiate the needed hooks (e.g. mla_list_table_query_final_terms) a bit earlier and it
 * will prevent the "headers already set" message.
 */
class WpListTableFactory
{

    public function create(): MLA_List_Table
    {
        global $wp_list_table;

        if ( ! class_exists('MLA_List_Table')) {
            require_once(MLA_PLUGIN_PATH . 'includes/class-mla-list-table.php');
            MLA_List_Table::mla_admin_init_action();
        }

        if ($wp_list_table instanceof MLA_List_Table) {
            return $wp_list_table;
        }

        $list_table = new MLA_List_Table();
        $list_table->prepare_items();

        return $list_table;
    }

}