<?php

namespace AC\Plugin\Update;

use AC\Plugin\Update;
use AC\Plugin\Version;
use AC\Storage\ListScreenOrder;
use AC\Type\ListKey;
use DateTime;

class V4000 extends Update
{

    public const DATABASE_TABLE = 'admin_columns';

    public const LAYOUT_PREFIX = 'cpac_layouts';
    public const COLUMNS_PREFIX = 'cpac_options_';

    public const PROGRESS_KEY = 'ac_update_progress';
    public const REPLACEMENT_IDS_KEY = 'ac_update_replacement_ids';

    /** @var int */
    private $next_step;

    public function __construct()
    {
        // because `get_option` could be cached we only fetch the next step from the DB on initialisation.
        $this->next_step = $this->get_next_step();

        parent::__construct(new Version('4.0.0'));
    }

    public function apply_update(): void
    {
        // just in case we need a bit of extra time to execute our upgrade script
        if (ini_get('max_execution_time') < 120) {
            @set_time_limit(120);
        }

        global $wpdb;

        // Apply update in chunks to minimize the impact of a timeout.
        switch ($this->next_step) {
            case 1 :
                $this->create_database();

                $this->update_next_step(2)
                     ->apply_update();
                break;
            case 2 :
                // 1. migrate segments to site specific user preference. Previously this was stored globally.
                $this->migrate_segments_preferences();

                // go to next step
                $this->update_next_step(3)
                     ->apply_update();

                break;
            case 3 :

                // 2. migrate column settings to new DB table
                $replaced_list_ids = $this->migrate_list_screen_settings();

                // $replaced_list_ids contains a list of empty id's that are replaced with unique id's
                $this->update_replacement_ids($replaced_list_ids);

                // go to next step
                $this->update_next_step(4)
                     ->apply_update();

                break;
            case 4 :

                // 3. User Preference "Segments": ac_preferences_search_segments
                $this->update_user_preferences_segments($this->get_replacement_ids());

                // go to next step
                $this->update_next_step(5)
                     ->apply_update();

                break;
            case 5 :
                $replaced_list_ids = $this->get_replacement_ids();

                // 4. User Preference "Horizontal Scrolling": ac_preferences_show_overflow_table
                $this->update_user_preference_by_key(
                    $wpdb->get_blog_prefix() . 'ac_preferences_show_overflow_table',
                    $replaced_list_ids
                );

                // 5. User Preference "Sort": ac_preferences_sorted_by
                $this->update_user_preference_by_key(
                    $wpdb->get_blog_prefix() . 'ac_preferences_sorted_by',
                    $replaced_list_ids
                );

                // go to next step
                $this->update_next_step(6)
                     ->apply_update();

                break;
            case 6 :
                $this->migrate_invalid_network_settings();

                // go to next step
                $this->update_next_step(7)
                     ->apply_update();

                break;
            case 7 :
                $replaced_list_ids = $this->get_replacement_ids();

                // 6. User Preference "Table selection": wp_ac_preferences_layout_table
                $this->migrate_user_preferences_table_selection($replaced_list_ids);

                // 7. Migrate layout order from `usermeta` to the `option table`
                $this->migrate_list_screen_order($replaced_list_ids);

                // clear steps and replacement id's
                $this->flush_temp_data();

                break;
        }
    }

    private function update_replacement_ids(array $ids)
    {
        update_option(self::REPLACEMENT_IDS_KEY, $ids, false);
    }

    private function get_replacement_ids()
    {
        return (array)get_option(self::REPLACEMENT_IDS_KEY, []);
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

    private function flush_temp_data()
    {
        delete_option(self::PROGRESS_KEY);
        delete_option(self::REPLACEMENT_IDS_KEY);
    }

    // Segments were stored globally, ignoring individual sites on a multisite network. Segments are now stored per site.
    private function migrate_segments_preferences()
    {
        global $wpdb;

        $prefix = 'ac_preferences_search_segments_';

        $sql = "
			SELECT *
			FROM $wpdb->usermeta
			WHERE meta_key LIKE '{$prefix}%'
			ORDER BY `umeta_id` DESC
		";

        $results = $wpdb->get_results($sql);

        foreach ($results as $row) {
            $data = maybe_unserialize($row->meta_value);

            if ($data) {
                update_user_option($row->user_id, $row->meta_key, $data);
            }
        }
    }

    private function update_user_preferences_segments(array $list_ids)
    {
        global $wpdb;

        $prefix = $wpdb->get_blog_prefix() . 'ac_preferences_search_segments_';

        foreach ($list_ids as $list_key => $ids) {
            foreach ($ids as $deprecated_id => $list_id) {
                $old_meta_key = $prefix . ($deprecated_id ?: $list_key);

                // Segments were stored globally, ignoring individual sites on a multisite network. Segments are now stored per site.
                $new_meta_key = $prefix . $list_id;

                $sql = $wpdb->prepare(
                    "SELECT user_id, meta_value FROM $wpdb->usermeta WHERE meta_key = %s",
                    $old_meta_key
                );

                $results = $wpdb->get_results($sql);

                foreach ($results as $row) {
                    $wpdb->insert(
                        $wpdb->usermeta,
                        [
                            'user_id'    => $row->user_id,
                            'meta_key'   => $new_meta_key,
                            'meta_value' => $row->meta_value,
                        ],
                        [
                            '%d',
                            '%s',
                            '%s',
                        ]
                    );
                }
            }
        }
    }

    /**
     * @param array $list_ids
     *
     * @return array
     */
    private function map_to_storage_keys(array $list_ids)
    {
        $map = [];

        foreach ($list_ids as $list_key => $ids) {
            foreach ($ids as $deprecated_id => $list_id) {
                $old_meta_key = $list_key . $deprecated_id;
                $new_meta_key = $list_key . $list_id;

                $map[$old_meta_key] = $new_meta_key;
            }
        }

        return $map;
    }

    private function update_user_preference_by_key($meta_key, array $list_ids)
    {
        global $wpdb;

        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->usermeta} WHERE meta_key = %s", $meta_key));

        $storage_keys = $this->map_to_storage_keys($list_ids);

        foreach ($results as $row) {
            $data = maybe_unserialize($row->meta_value);

            if (empty($data)) {
                continue;
            }

            $orginal_data = $data;

            foreach ($storage_keys as $old_key => $new_key) {
                // Replace old key with new key
                if (isset($data[$old_key])) {
                    $data[$new_key] = $data[$old_key];

                    unset($data[$new_key]);
                }
            }

            // no update needed
            if ($data === $orginal_data) {
                continue;
            }

            $wpdb->query(
                $wpdb->prepare(
                    "UPDATE {$wpdb->usermeta} SET meta_value = %s WHERE umeta_id = %d",
                    serialize($data),
                    $row->umeta_id
                )
            );
        }
    }

    private function migrate_user_preferences_table_selection(array $replaced_list_ids)
    {
        global $wpdb;

        $meta_key = $wpdb->get_blog_prefix() . 'ac_preferences_layout_table';
        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->usermeta} WHERE meta_key = %s", $meta_key));

        foreach ($results as $row) {
            $data = maybe_unserialize($row->meta_value);

            if (empty($data) || ! is_array($data)) {
                continue;
            }

            $orginal_data = $data;

            foreach ($replaced_list_ids as $list_key => $ids) {
                if ( ! array_key_exists($list_key, $data)) {
                    continue;
                }

                $old_id = $data[$list_key];

                if (array_key_exists($old_id, $ids)) {
                    // use replaced ID
                    $data[$list_key] = $ids[$old_id];
                }
            }

            // no update needed
            if ($data === $orginal_data) {
                continue;
            }

            $wpdb->query(
                $wpdb->prepare(
                    "UPDATE {$wpdb->usermeta} SET meta_value = %s WHERE umeta_id = %d",
                    serialize($data),
                    $row->umeta_id
                )
            );
        }
    }

    /**
     * Migrate the order of the list screens from the `usermeta` to the `options` table
     */
    private function migrate_list_screen_order(array $replaced_list_ids)
    {
        global $wpdb;

        $meta_key = $wpdb->get_blog_prefix() . 'ac_preferences_layout_order';

        $sql = $wpdb->prepare(
            "SELECT meta_value FROM {$wpdb->prefix}usermeta WHERE meta_key = %s AND meta_value != '' LIMIT 1",
            $meta_key
        );

        $result = $wpdb->get_var($sql);

        if ( ! $result) {
            return;
        }

        $list_screens = maybe_unserialize($result);

        if ( ! $list_screens || ! is_array($list_screens)) {
            return;
        }

        $order = new ListScreenOrder();

        foreach ($list_screens as $list_key => $ids) {
            if ($ids && is_array($ids)) {
                // try and replace deprecated id's
                foreach ($ids as $k => $id) {
                    $ids[$k] = $this->maybe_replace_id($replaced_list_ids, $list_key, $id);
                }

                $order->set(new ListKey($list_key), $ids);
            }
        }
    }

    /**
     * Since Network list screens have their own preference, we have to remove any network list screens from the preferences
     */
    private function migrate_invalid_network_settings()
    {
        global $wpdb;

        $results = $wpdb->get_results(
            $wpdb->prepare(
                "
			SELECT * 
			FROM {$wpdb->usermeta} 
			WHERE meta_key = %s
			",
                $wpdb->get_blog_prefix() . 'ac_preferences_settings'
            )
        );

        foreach ($results as $row) {
            $data = maybe_unserialize($row->meta_value);

            if (empty($data) || ! is_array($data)) {
                continue;
            }

            if (isset($data['list_screen']) && in_array($data['list_screen'], ['wp-ms_sites', 'wp-ms_users'], true)) {
                $wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->usermeta} WHERE umeta_id = %d", $row->umeta_id));
            }
        }
    }

    private function maybe_replace_id(array $replaced_list_ids, string $list_key, $id)
    {
        if ( ! array_key_exists($list_key, $replaced_list_ids)) {
            return $id;
        }

        $_ids = $replaced_list_ids[$list_key];

        if ( ! $_ids || ! is_array($_ids)) {
            return $id;
        }

        foreach ($_ids as $old_id => $new_id) {
            if ($old_id === $id) {
                return $new_id;
            }
        }

        return $id;
    }

    /**
     * @return object[]
     */
    private function get_layouts_data()
    {
        global $wpdb;

        $prefix = self::LAYOUT_PREFIX;

        $sql = "
			SELECT option_name, option_value
			FROM {$wpdb->options}
			WHERE option_name LIKE '{$prefix}%'
			ORDER BY `option_id` DESC
		";

        $results = $wpdb->get_results($sql);

        $layouts = [];

        foreach ($results as $row) {
            $data = maybe_unserialize($row->option_value);

            if (is_object($data)) {
                $list_id = $data->id;

                $list_key = $this->remove_prefix(self::LAYOUT_PREFIX, $row->option_name);
                $list_key = $this->remove_suffix($list_id, $list_key);

                if ( ! $list_key) {
                    continue;
                }

                $layout_data = [
                    'id'    => $list_id,
                    'key'   => $list_key,
                    'name'  => '',
                    'users' => [],
                    'roles' => [],
                ];

                if ( ! empty($data->name)) {
                    $layout_data['name'] = $data->name;
                }
                if ( ! empty($data->users) && is_array($data->users)) {
                    $layout_data['users'] = array_map('intval', $data->users);
                }
                if ( ! empty($data->roles) && is_array($data->roles)) {
                    $layout_data['roles'] = array_map('strval', $data->roles);
                }

                $layouts[] = $layout_data;
            }
        }

        return $layouts;
    }

    private function get_columns_data()
    {
        global $wpdb;

        $prefix = self::COLUMNS_PREFIX;

        $sql = "
			SELECT option_name, option_value
			FROM {$wpdb->options}
			WHERE option_name LIKE '{$prefix}%'
			ORDER BY `option_id` DESC
		";

        $results = $wpdb->get_results($sql);

        $columns = [];

        foreach ($results as $row) {
            if ($this->has_suffix('__default', $row->option_name)) {
                continue;
            }

            $storage_key = $this->remove_prefix(self::COLUMNS_PREFIX, $row->option_name);

            $column_data = maybe_unserialize($row->option_value);

            $columns[$storage_key] = $column_data && is_array($column_data) ? $column_data : [];
        }

        return $columns;
    }

    /**
     * @return array List of replaced id's
     */
    private function migrate_list_screen_settings()
    {
        $migrate = [];

        /**
         * @var array $replaced_list_ids array( $list_key => array( $deprecated_list_id => $new_list_id ) )
         */
        $replaced_list_ids = [];

        // 1. Fetch data
        $layouts_data = $this->get_layouts_data();
        $columns_data = $this->get_columns_data();

        // 2. Process Pro settings
        foreach ($layouts_data as $layout_data) {
            $list_key = $layout_data['key'];

            $storage_key = $list_key . $layout_data['id'];

            $columns = [];

            // Check for column settings
            if (isset($columns_data[$storage_key])) {
                $columns = $columns_data[$storage_key];

                // Remove used column settings from stack
                unset($columns_data[$storage_key]);
            }

            $settings = [];
            if ($layout_data['users']) {
                $settings['users'] = $layout_data['users'];
            }
            if ($layout_data['roles']) {
                $settings['roles'] = $layout_data['roles'];
            }

            $list_id = $layout_data['id'];

            if ( ! $list_id) {
                $list_id = uniqid();

                // add to list of id's that have been replaced
                $replaced_list_ids[$list_key][''] = $list_id;
            }

            $list_data = [
                'id'       => $list_id,
                'key'      => $list_key,
                'title'    => $layout_data['name'],
                'columns'  => $columns,
                'settings' => $settings,
            ];

            $migrate[] = $list_data;
        }

        // 3. Process Free column settings
        foreach ($columns_data as $list_key => $columns) {
            if (empty($columns)) {
                continue;
            }

            // Skip columns that contain a list ID
            if ($this->contains_list_id($list_key)) {
                continue;
            }

            $list_id = uniqid();

            $list_data = [
                'id'       => $list_id,
                'key'      => $list_key,
                'title'    => __('Original', 'codepress-admin-columns'),
                'settings' => [],
                'columns'  => $columns,
            ];

            $migrate[] = $list_data;

            // add to list of id's that have been replaced
            $replaced_list_ids[$list_key][''] = $list_id;
        }

        // 4. Make sure all ID's are unique.
        // A duplicate `id` is possible when a user manually exported their list screen settings and
        // changed the list screen `key` (e.g. from post to page), without changing the `id`, and imported these settings.
        $migrate = $this->unique_ids($migrate);

        // 5. Insert into DB
        foreach ($migrate as $list_data) {
            $this->insert($list_data);
        }

        return $replaced_list_ids;
    }

    /**
     * @param array $migrate
     *
     * @return array
     */
    private function unique_ids(array $migrate)
    {
        $ids = [];

        foreach ($migrate as $k => $list_data) {
            if (in_array($list_data['id'], $ids, true)) {
                // Truly eliminates a duplicate $unique_id, because `uniqid()` is based on the current time in microseconds
                usleep(1000);

                $migrate[$k]['id'] = uniqid();
            }

            $ids[] = $list_data['id'];
        }

        return $migrate;
    }

    private function create_database()
    {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        global $wpdb;

        $collate = $wpdb->get_charset_collate();

        $table_name = $wpdb->prefix . 'admin_columns';

        $sql = "
		CREATE TABLE {$table_name} (
			id bigint(20) unsigned NOT NULL auto_increment,
			list_id varchar(20) NOT NULL default '',
			list_key varchar(100) NOT NULL default '',
			title varchar(255) NOT NULL default '',
			columns mediumtext,
			settings mediumtext,
			date_created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			date_modified datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			PRIMARY KEY (id),
			UNIQUE KEY `list_id` (`list_id`)
		) $collate;
		";

        dbDelta($sql);
    }

    private function insert(array $data)
    {
        global $wpdb;

        $date = new DateTime();

        $wpdb->insert(
            $wpdb->prefix . self::DATABASE_TABLE,
            [
                'title'         => $data['title'],
                'list_id'       => $data['id'],
                'list_key'      => $data['key'],
                'columns'       => serialize($data['columns']),
                'settings'      => serialize($data['settings']),
                'date_modified' => $date->format('Y-m-d H:i:s'),
                'date_created'  => $date->format('Y-m-d H:i:s'),
            ],
            [
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
            ]
        );
    }

    /**
     * @param string $storage_key
     *
     * @return string|false ID
     */
    private function contains_list_id($storage_key)
    {
        $prefixes = [
            'wp-users',
            'wp-media',
            'wp-comments',
            'wp-ms_sites',
            'wp-ms_users',
        ];

        foreach ($prefixes as $prefix) {
            if ($this->has_prefix($prefix, $storage_key)) {
                $layout_id = $this->remove_prefix($prefix, $storage_key);
                $layout_id = substr($layout_id, -13);

                return $this->is_layout_id($layout_id);
            }
        }

        // Is it a taxonomy?
        if ($this->has_prefix('wp-taxonomy_', $storage_key)) {
            $taxonomy = $this->remove_prefix('wp-taxonomy_', $storage_key);

            if (taxonomy_exists($taxonomy)) {
                return false;
            }

            $layout_id = substr($taxonomy, -13);

            return $this->is_layout_id($layout_id);
        }

        // is it a post?
        if (post_type_exists($storage_key)) {
            return false;
        }

        $layout_id = substr($storage_key, -13);

        return $this->is_layout_id($layout_id);
    }

    /**
     * @param string $id
     *
     * @return string|false
     */
    private function is_layout_id($id)
    {
        return $id && is_string($id) && (strlen($id) === 13) && $this->is_hex($id) ? $id : false;
    }

    /**
     * @param string $string
     *
     * @return bool
     */
    private function is_hex($string)
    {
        return '' == trim(substr($string, -13), '0..9A..Fa..f');
    }

    private function remove_prefix($prefix, $string)
    {
        return (string)preg_replace('/^' . preg_quote($prefix, '/') . '/', '', $string);
    }

    /**
     * @param string $string
     * @param string $end
     *
     * @return string
     */
    private function remove_suffix($suffix, $string)
    {
        return (string)preg_replace('/' . preg_quote($suffix, '/') . '$/', '', $string);
    }

    /**
     * @param string $prefix
     * @param string $string
     *
     * @return bool
     */
    private function has_prefix($prefix, $string)
    {
        return substr($string, 0, strlen($prefix)) === $prefix;
    }

    /**
     * @param string $suffix
     * @param string $string
     *
     * @return bool
     */
    private function has_suffix($suffix, $string)
    {
        return substr($string, strlen($suffix) * -1) === $suffix;
    }

}