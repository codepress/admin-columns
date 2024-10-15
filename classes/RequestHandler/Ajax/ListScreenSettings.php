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

    private $preference;

    private $type_repository;

    public function __construct(
        Storage $storage,
        AC\TableScreenFactory\Aggregate $table_factory,
        AC\ColumnTypeRepository $type_repository,
        Preference\ListScreen $preference
    ) {
        $this->storage = $storage;
        $this->table_factory = $table_factory;
        $this->preference = $preference;
        $this->type_repository = $type_repository;
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
                $this->type_repository
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
        $response->set_parameter('column_types', $this->encode_columns($table_screen));
        $response->set_parameter('column_settings', $this->encode_column_settings($list_screen->get_columns()));

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

    private function get_original_types(AC\TableScreen $table_screen): array
    {
        $types = [];
        foreach ($this->type_repository->find_all_by_original($table_screen) as $column) {
            $types[] = $column->get_type();
        }

        return $types;
    }

    private function encode_columns(AC\TableScreen $table_screen): array
    {
        $column_types = [];

        $groups = AC\ColumnGroups::get_groups();
        $original_types = $this->get_original_types($table_screen);

        foreach ($this->type_repository->find_all($table_screen) as $column) {
            $column_types[] = [
                'label'     => $this->get_clean_label($column),
                'value'     => $column->get_type(),
                'group'     => $groups->get($column->get_group())['label'],
                'group_key' => $column->get_group(),
                'original'  => in_array($column->get_type(), $original_types, true),
            ];
        }

        return $column_types;
    }

    private function encode_column_settings(AC\ColumnIterator $columns): array
    {
        $settings = [];

        foreach ($columns as $column) {
            $settings[(string)$column->get_id()] = (new Encoder($column->get_settings()))->encode();
        }

        return $settings;
    }

}