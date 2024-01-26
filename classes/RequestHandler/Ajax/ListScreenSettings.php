<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC;
use AC\Admin\Preference;
use AC\Capabilities;
use AC\ListScreen;
use AC\ListScreenRepository\Storage;
use AC\Nonce;
use AC\Plugin\Version;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\Setting\Encoder;
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

        if ( ! (new Nonce\Ajax())->verify($request)) {
            $response->error();
        }

        $list_key = new ListKey((string)$request->get('list_key'));

        if ( ! $this->table_factory->can_create($list_key)) {
            throw new InvalidArgumentException('Invalid table screen.');
        }

        $table_screen = $this->table_factory->create($list_key);

        $request->add_middleware(
            new Request\Middleware\ListScreenAdmin(
                $this->storage,
                $table_screen,
                $this->preference,
                $this->column_types_factory,
                $this->column_factory
            )
        );

        $list_screen = $request->get('list_screen');

        if ( ! $list_screen instanceof ListScreen) {
            throw new InvalidArgumentException('Invalid list screen.');
        }

        $encoder = new AC\Storage\Encoder\BaseEncoder(new Version('6.3'));
        $encoder->set_list_screen($list_screen);

        $response->set_parameter('read_only', $list_screen->is_read_only());
        $response->set_parameter('table_url', (string)$list_screen->get_table_url());
        $response->set_parameter('settings', $encoder->encode());
        $response->set_parameter('column_types', $this->get_column_types($table_screen));
        $response->set_parameter('column_settings', $this->get_column_settings($list_screen->get_columns()));

        $response->success();
    }

    private function get_clean_label(AC\Column $column): string
    {
        $label = $column->get_label();

        if (strip_tags($label) === '') {
            $label = ucfirst(str_replace('_', ' ', $column->get_type()));
        }

        return strip_tags($label);
    }

    private function get_column_types(AC\TableScreen $table_screen): array
    {
        $column_types = [];

        $groups = AC\ColumnGroups::get_groups();

        foreach ($this->column_types_factory->create($table_screen) as $column) {
            $column_types[] = [
                'label'     => $this->get_clean_label($column),
                'value'     => $column->get_type(),
                'group'     => $groups->get($column->get_group())['label'],
                'group_key' => $column->get_group(),
                'original'  => $column->is_original(),
            ];
        }

        return $column_types;
    }

    private function get_column_settings(AC\ColumnIterator $columns): array
    {
        $settings = [];

        foreach ($columns as $column) {
            $settings[$column->get_name()] = (new Encoder($column->get_settings()))->encode();
        }

        return $settings;
    }

}