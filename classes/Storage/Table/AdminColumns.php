<?php

declare(strict_types=1);

namespace AC\Storage\Table;

use AC\Storage\Table;

// TODO David make sure this is run on an install and update
// TODO David ac-force-install=1 is (was) used to force an install or at least an update with dbDelta. It seems there is no way to let users correct the table. dbDelta should still be used with ac-force-install=1.   
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