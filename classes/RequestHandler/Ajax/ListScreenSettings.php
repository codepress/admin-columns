<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC;
use AC\Admin\Preference;
use AC\Capabilities;
use AC\Controller\Middleware;
use AC\ListScreen;
use AC\ListScreenRepository\Storage;
use AC\Plugin\Version;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\Type\ListKey;
use InvalidArgumentException;

class ListScreenSettings implements RequestAjaxHandler
{

    private $storage;

    private $table_factory;

    private $column_types_factory;

    private $column_factory;

    private $preference;

    public function __construct(
        Storage $storage,
        AC\TableScreenFactory\Aggregate $table_factory,
        AC\ColumnFactory $column_factory,
        AC\ColumnTypesFactory\Aggregate $column_types_factory,
        Preference\ListScreen $preference
    ) {
        $this->storage = $storage;
        $this->table_factory = $table_factory;
        $this->column_types_factory = $column_types_factory;
        $this->column_factory = $column_factory;
        $this->preference = $preference;
    }

    public function handle(): void
    {
        if ( ! current_user_can(Capabilities::MANAGE)) {
            return;
        }

        $request = new Request();
        $response = new AC\Response\Json();

        $list_key = new ListKey((string)$request->get('list_key'));

        if ( ! $this->table_factory->can_create($list_key)) {
            return;
        }

        $table_screen = $this->table_factory->create($list_key);

        $request->add_middleware(
            new Middleware\ListScreenAdmin(
                $this->storage,
                $table_screen,
                $this->preference,
                $this->column_types_factory,
                $this->column_factory
            )
        );

        $list_screen = $request->get('list_screen');

        if ( ! $list_screen instanceof ListScreen) {
            throw new InvalidArgumentException('Invalid screen.');
        }

        $encoder = new AC\Storage\Encoder\BaseEncoder(new Version('6.3'));
        $encoder->set_list_screen($list_screen);

        $response->set_parameter('settings', $encoder->encode());
        $response->set_parameter('column_types', $this->get_column_types($table_screen));

        $response->success();
        exit;
    }

    private function get_column_types(AC\TableScreen $table_screen): array
    {
        $column_types = [];

        $groups = AC\ColumnGroups::get_groups();

        foreach ($this->column_types_factory->create($table_screen) as $column) {
            $column_types[] = [
                'label'     => $column->get_label(),
                'value'     => $column->get_type(),
                'group'     => $groups->get($column->get_group())['label'],
                'group_key' => $column->get_group(),
                'original'  => $column->is_original(),
            ];
        }

        return $column_types;
    }

    //    private function method_get_settings(Request $request)
    //    {
    //        $response = new Json();
    //        $list_screen_id = $request->get('list_screen_id');
    //
    //        // TODO load empty list screen if no ID is present? Or do we need another request?
    //        if ( ! $list_screen_id) {
    //            $response->set_message('No list screen ID given')->error();
    //        }
    //
    //        $list_screen = $this->storage->find(new ListScreenId($list_screen_id));
    //
    //        if ( ! $list_screen) {
    //            $response->set_message('No list screen found')->error();
    //        }
    //
    //        // TODO Stefan THIS IS A PRO FEATURE!!! Move?
    //        $encoder = new Encoder(new Version('6.3'));
    //
    //        $encoder->set_list_screen($list_screen);
    //
    //        $settings = [];
    //
    //        foreach ($list_screen->get_columns() as $column) {
    //            $settings[$column->get_name()] = $this->get_column_settings($column);
    //        }
    //
    //        $response->set_parameter('list_screen_data', $encoder->encode());
    //        $response->set_parameter('table_url', (string)$list_screen->get_table_url());
    //        $response->set_parameter('read_only', $list_screen->is_read_only());
    //        $response->set_parameter('settings', $settings);
    //        $response->success();
    //    }

}