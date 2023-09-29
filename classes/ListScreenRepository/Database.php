<?php

declare(strict_types=1);

namespace AC\ListScreenRepository;

use AC\Exception\MissingListScreenIdException;
use AC\ListScreen;
use AC\ListScreenCollection;
use AC\ListScreenFactory;
use AC\ListScreenRepositoryWritable;
use AC\Type\ListScreenId;
use LogicException;

class Database implements ListScreenRepositoryWritable
{

    use ListScreenRepositoryTrait;

    private const TABLE = 'admin_columns';

    private $list_screen_factory;

    public function __construct(ListScreenFactory $list_screen_factory)
    {
        $this->list_screen_factory = $list_screen_factory;
    }

    protected function find_from_source(ListScreenId $id): ?ListScreen
    {
        global $wpdb;

        $sql = $wpdb->prepare(
            '
			SELECT * 
			FROM ' . $wpdb->prefix . self::TABLE . '
			WHERE list_id = %s
		',
            (string)$id
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
		';

        return $this->create_list_screens(
            $wpdb->get_results($sql)
        );
    }

    protected function find_all_by_key_from_source(string $key): ListScreenCollection
    {
        global $wpdb;

        $sql = $wpdb->prepare(
            '
			SELECT * 
			FROM ' . $wpdb->prefix . self::TABLE . '
			WHERE list_key = %s
		',
            $key
        );

        return $this->create_list_screens(
            $wpdb->get_results($sql)
        );
    }

    public function save(ListScreen $list_screen): void
    {
        global $wpdb;

        if ( ! $list_screen->has_id()) {
            throw MissingListScreenIdException::from_saving_list_screen();
        }

        $list_screen->set_preferences($this->save_preferences($list_screen));

        $args = [
            'list_id'       => $list_screen->get_layout_id(),
            'list_key'      => $list_screen->get_key(),
            'title'         => $list_screen->get_title(),
            'columns'       => $list_screen->get_settings() ? serialize($list_screen->get_settings()) : null,
            'settings'      => $list_screen->get_preferences() ? serialize($list_screen->get_preferences()) : null,
            'date_modified' => $list_screen->get_updated()->format('Y-m-d H:i:s'),
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
                array_fill(0, 6, '%s')
            );
        } else {
            $args['date_created'] = $args['date_modified'];

            $wpdb->insert(
                $table,
                $args,
                array_fill(0, 7, '%s')
            );
        }
    }

    public function delete(ListScreen $list_screen): void
    {
        global $wpdb;

        if ( ! $list_screen->has_id()) {
            throw new LogicException('Cannot delete a ListScreen without an identity.');
        }

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
    protected function save_preferences(ListScreen $list_screen): array
    {
        return $list_screen->get_preferences();
    }

    /**
     * Template method to add and remove preferences before retrieval
     */
    protected function get_preferences(ListScreen $list_screen): array
    {
        return $list_screen->get_preferences();
    }

    private function create_list_screen(object $data): ?ListScreen
    {
        if ( ! $this->list_screen_factory->can_create($data->list_key)) {
            return null;
        }

        $list_screen = $this->list_screen_factory->create(
            $data->list_key,
            [
                'title'       => $data->title,
                'list_id'     => $data->list_id,
                'date'        => $data->date_modified,
                'preferences' => $data->settings ? unserialize($data->settings, ['allowed_classes' => false]) : [],
                'columns'     => $data->columns ? unserialize($data->columns, ['allowed_classes' => false]) : [],
                'group'       => null,
            ]
        );

        $list_screen->set_preferences($this->get_preferences($list_screen));

        return $list_screen;
    }

    private function create_list_screens(array $rows): ListScreenCollection
    {
        $list_screens = array_filter(
            array_map([$this, 'create_list_screen'], $rows)
        );

        return new ListScreenCollection($list_screens);
    }

}