<?php

declare(strict_types=1);

namespace AC\ListScreenRepository;

use AC\ColumnFactories\Aggregate;
use AC\ColumnIterator;
use AC\ColumnIterator\ProxyColumnIterator;
use AC\ColumnRepository\ColumnRepository;
use AC\ColumnRepository\EncodedData;
use AC\ListScreen;
use AC\ListScreenCollection;
use AC\ListScreenRepositoryWritable;
use AC\Setting\Config;
use AC\Setting\ConfigCollection;
use AC\Storage\EncoderFactory;
use AC\TableScreen;
use AC\TableScreenFactory;
use AC\Type\ListKey;
use AC\Type\ListScreenId;
use AC\Type\ListScreenStorageType;
use DateTime;

class Database implements ListScreenRepositoryWritable
{

    use ListScreenRepositoryTrait;

    private const TABLE = 'admin_columns';

    private TableScreenFactory $table_screen_factory;

    private EncoderFactory $encoder_factory;

    private Aggregate $column_factory;

    private ListScreenStorageType $storage_type;

    public function __construct(
        TableScreenFactory $table_screen_factory,
        EncoderFactory $encoder_factory,
        Aggregate $column_factory,
        ListScreenStorageType $storage_type = null
    ) {
        $this->table_screen_factory = $table_screen_factory;
        $this->encoder_factory = $encoder_factory;
        $this->column_factory = $column_factory;
        $this->storage_type = $storage_type ?? new ListScreenStorageType();
    }

    protected function find_from_source(ListScreenId $id): ?ListScreen
    {
        global $wpdb;

        $sql = $wpdb->prepare(
            '
			SELECT * 
			FROM ' . $wpdb->prefix . self::TABLE . '
			WHERE list_id = %s
			AND type = %s
		',
            (string)$id,
            (string)$this->storage_type
        );

        $row = $wpdb->get_row($sql);

        if ( ! $row) {
            return null;
        }

        return $this->create_list_screen($row);
    }

    protected function find_all_from_source(): ListScreenCollection
    {
        global $wpdb;

        $sql = '
			SELECT * 
			FROM ' . $wpdb->prefix . self::TABLE . '
			WHERE type = %s
		';

        $sql = $wpdb->prepare(
            $sql,
            (string)$this->storage_type
        );

        return $this->create_list_screens(
            $wpdb->get_results($sql)
        );
    }

    protected function find_all_by_key_from_source(ListKey $key): ListScreenCollection
    {
        global $wpdb;

        $sql = $wpdb->prepare(
            '
			SELECT * 
			FROM ' . $wpdb->prefix . self::TABLE . '
			WHERE list_key = %s
			AND type = %s
		',
            (string)$key,
            (string)$this->storage_type
        );

        return $this->create_list_screens(
            $wpdb->get_results($sql)
        );
    }

    public function save(ListScreen $list_screen): void
    {
        global $wpdb;

        $list_screen_dto = $this->encoder_factory->create()
                                                 ->set_list_screen($list_screen)
                                                 ->encode()['list_screen'];

        $settings = $this->save_preferences($list_screen_dto);
        $date = DateTime::createFromFormat('U', (string)$list_screen_dto['updated']);

        $args = [
            'list_id'       => $list_screen_dto['id'],
            'list_key'      => $list_screen_dto['type'],
            'title'         => $list_screen_dto['title'],
            'columns'       => $list_screen_dto['columns'] ? serialize($list_screen_dto['columns']) : null,
            'settings'      => $settings ? serialize($settings) : null,
            'date_modified' => $date->format('Y-m-d H:i:s'),
            'type'          => (string)$this->storage_type,
        ];

        $table = $wpdb->prefix . self::TABLE;
        $exists = $this->exists($list_screen->get_id());

        if ($exists) {
            $wpdb->update(
                $table,
                $args,
                [
                    'list_id' => (string)$list_screen->get_id(),
                ],
                array_fill(0, 7, '%s')
            );
        } else {
            $args['date_created'] = $args['date_modified'];

            $wpdb->insert(
                $table,
                $args,
                array_fill(0, 8, '%s')
            );
        }
    }

    public function delete(ListScreen $list_screen): void
    {
        global $wpdb;

        $wpdb->delete(
            $wpdb->prefix . self::TABLE,
            [
                'list_id' => (string)$list_screen->get_id(),
            ],
            [
                '%s',
            ]
        );
    }

    /**
     * Template method to add and remove preferences before save
     */
    protected function save_preferences(array $list_screen_dto): array
    {
        return $list_screen_dto['settings'];
    }

    /**
     * Template method to add and remove preferences before retrieval
     */
    protected function get_preferences(object $data): array
    {
        return $data->settings
            ? unserialize($data->settings, ['allowed_classes' => false])
            : [];
    }

    private function create_list_screen(object $data): ?ListScreen
    {
        $list_key = new ListKey($data->list_key);

        if ( ! $this->table_screen_factory->can_create($list_key)) {
            return null;
        }

        $table_screen = $this->table_screen_factory->create($list_key);

        return new ListScreen(
            new ListScreenId($data->list_id),
            $data->title,
            $table_screen,
            $this->create_column_iterator($table_screen, $data),
            $this->get_preferences($data),
            new DateTime($data->date_modified)
        );
    }

    private function create_column_iterator(TableScreen $table_screen, object $data): ColumnIterator
    {
        return new ProxyColumnIterator(
            new EncodedData(
                $this->column_factory->create($table_screen),
                $this->create_configs($data)
            )
        );
    }

    private function create_configs(object $data): ConfigCollection
    {
        $configs = [];

        $columns = $data->columns
            ? unserialize($data->columns, ['allowed_classes' => false])
            : [];

        foreach ($columns as $name => $config) {
            if ( ! isset($config['name'])) {
                $config['name'] = $name;
            }

            $configs[] = new Config($config);
        }

        return new ConfigCollection($configs);
    }

    private function create_list_screens(array $rows): ListScreenCollection
    {
        $list_screens = array_filter(
            array_map([$this, 'create_list_screen'], $rows)
        );

        return new ListScreenCollection($list_screens);
    }

}