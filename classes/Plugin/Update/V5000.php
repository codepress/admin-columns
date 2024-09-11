<?php

namespace AC\Plugin\Update;

use AC\Plugin\Install\Database;
use AC\Plugin\Update;
use AC\Plugin\Version;

class V5000 extends Update
{

    public const DATABASE_TABLE = 'admin_columns';

    public const LAYOUT_PREFIX = 'cpac_layouts';
    public const COLUMNS_PREFIX = 'cpac_options_';

    public const PROGRESS_KEY = 'ac_update_progress';
    public const REPLACEMENT_IDS_KEY = 'ac_update_replacement_ids';

    /** @var int */
    private $next_step;

    private $database;

    public function __construct(Database $database)
    {
        // because `get_option` could be cached we only fetch the next step from the DB on initialisation.
        $this->next_step = $this->get_next_step();

        parent::__construct(new Version('5.0.0'));
        $this->database = $database;
    }

    public function apply_update(): void
    {
        // just in case we need a bit of extra time to execute our upgrade script
        if (ini_get('max_execution_time') < 120) {
            @set_time_limit(120);
        }

        global $wpdb;

        //TODO just for convenience remove
        $this->update_database();

        // Apply update in chunks to minimize the impact of a timeout.
        switch ($this->next_step) {
            case 1 :
                $this->update_database();

                $this->update_next_step(2)
                     ->apply_update();
                break;
        }
    }

    /**
     * @return int
     */
    private function get_next_step()
    {
        return (int)get_option(self::PROGRESS_KEY, 1);
    }

    private function update_next_step($step)
    {
        $this->next_step = (int)$step;

        update_option(self::PROGRESS_KEY, $this->next_step, false);

        return $this;
    }

    private function update_database()
    {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        global $wpdb;

        // TODO use install since it uses dbDelta for creating and updating tables?
        $this->database->install();

        //
        //        $collate = $wpdb->get_charset_collate();
        //
        //        $table_name = $wpdb->prefix . 'admin_columns';
        //
        //        $sql = "
        //		CREATE TABLE {$table_name} (
        //			id bigint(20) unsigned NOT NULL auto_increment,
        //			list_id varchar(20) NOT NULL default '',
        //			list_key varchar(100) NOT NULL default '',
        //			title varchar(255) NOT NULL default '',
        //			columns mediumtext,
        //			settings mediumtext,
        //			date_created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        //			date_modified datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        //			type varchar(20) DEFAULT '' NOT NULL,
        //			PRIMARY KEY (id),
        //			UNIQUE KEY `list_id` (`list_id`)
        //		) $collate;
        //		";
        //
        //        dbDelta($sql);
    }

}