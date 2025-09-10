<?php

declare(strict_types=1);

namespace AC\Storage\Table;

use AC\Storage\Table;

final class AdminColumns extends Table
{

    public function get_name(): string
    {
        global $wpdb;

        return $wpdb->prefix . 'admin_columns';
    }

    public function get_schema(): string
    {
        global $wpdb;

        $collate = $wpdb->get_charset_collate();

        return "
            CREATE TABLE " . $this->get_name() . " (
                id bigint(20) unsigned NOT NULL auto_increment,
                list_id varchar(20) NOT NULL default '',
                list_key varchar(100) NOT NULL default '',
                title varchar(255) NOT NULL default '',
                columns mediumtext,
                settings mediumtext,
                date_created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                date_modified datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                status varchar(20) NOT NULL default '',
                PRIMARY KEY (id),
                UNIQUE KEY `list_id` (`list_id`)
            ) $collate;
		";
    }

}