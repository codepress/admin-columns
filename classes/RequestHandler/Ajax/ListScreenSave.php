<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC\Capabilities;
use AC\Column\LabelEncoder;
use AC\ColumnCollection;
use AC\ColumnFactory;
use AC\ListScreenRepository\Storage;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\Response\Json;
use AC\TableScreen;
use AC\Type\ListScreenId;

class ListScreenSave implements RequestAjaxHandler
{

    private $storage;

    private $column_factory;

    public function __construct(Storage $storage, ColumnFactory $column_factory)
    {
        $this->storage = $storage;
        $this->column_factory = $column_factory;
    }

    public function handle(): void
    {
        if ( ! current_user_can(Capabilities::MANAGE)) {
            return;
        }

        $request = new Request();

        $data = $request->get('data', '');
        $data = json_decode($data, true);

        $response = new Json();

        $id = $data['id'] ?? null;

        if ( ! ListScreenId::is_valid_id($id)) {
            $response->error();

            exit;
        }

        $list_screen = $this->storage->find(new ListScreenId($id));

        if ( ! $list_screen) {
            $response->error();
            exit;
        }

        $table_screen = $list_screen->get_table_screen();

        $list_screen->set_title((string)$data['title']);
        $list_screen->set_columns($this->get_columns($table_screen, (array)$data['columns']));
        $list_screen->set_preferences((array)$data['settings']);

        $this->storage->save($list_screen);

        $response->success();
    }

    private function get_columns(TableScreen $table_screen, array $columndata): ColumnCollection
    {
        $columns = [];

        foreach ($columndata as $data) {
            if (isset($data['label'])) {
                $data['label'] = (new LabelEncoder())->encode($data['label']);
            }

            $columns[] = $this->column_factory->create($table_screen, $data);
        }

        return new ColumnCollection(array_filter($columns));
    }

}