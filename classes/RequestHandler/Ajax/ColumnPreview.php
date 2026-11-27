<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC;
use AC\Capabilities;
use AC\Column\ColumnFactory;
use AC\ColumnPreview\SampleItemsRegistry;
use AC\Formatter\TableRender;
use AC\ListScreen;
use AC\Nonce;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\TableScreen;
use AC\TableScreenFactory\Aggregate;
use AC\Type\ListScreenIdGenerator;
use AC\Type\TableId;

class ColumnPreview implements RequestAjaxHandler
{
    private const SAMPLE_SIZE = 20;

    private const MAX_RESULTS = 2;

    private Aggregate $table_screen_factory;

    private AC\ColumnFactories\Aggregate $column_factory;

    public function __construct(
        Aggregate $table_screen_factory,
        AC\ColumnFactories\Aggregate $column_factory
    ) {
        $this->table_screen_factory = $table_screen_factory;
        $this->column_factory = $column_factory;
    }

    public function handle(): void
    {
        if (! current_user_can(Capabilities::MANAGE)) {
            return;
        }

        $request = new Request();

        if (! (new Nonce\Ajax())->verify($request)) {
            wp_send_json_error();
        }

        $table_screen = $this->table_screen_factory->create(
            new TableId((string)$request->get('list_key'))
        );

        $column_data = json_decode((string)$request->get('data'), true);
        $preview = $this->create_preview($table_screen, is_array($column_data) ? $column_data : []);

        if (null === $preview) {
            wp_send_json_error(__('Unknown column type.', 'codepress-admin-columns'));
        }

        wp_send_json_success($preview);
    }

    public function create_preview(TableScreen $table_screen, array $column_data): ?array
    {
        $factory = $this->get_column_factory($table_screen, (string)($column_data['type'] ?? ''));

        if (! $factory) {
            return null;
        }

        $column = $factory->create(new AC\Setting\Config($column_data));

        $sample_items = SampleItemsRegistry::find($table_screen);

        if (! $sample_items || 0 === $column->get_formatters()->count()) {
            return ['supported' => false, 'items' => [], 'sampled' => 0, 'empty' => 0];
        }

        $render = new TableRender(
            $column->get_formatters(),
            $column->get_context(),
            $table_screen,
            new ListScreen((new ListScreenIdGenerator())->generate(), '', $table_screen)
        );

        try {
            $values = $sample_items->get_values($table_screen, self::SAMPLE_SIZE);
        } catch (\Throwable $e) {
            return ['supported' => false, 'items' => [], 'sampled' => 0, 'empty' => 0];
        }

        $items = [];
        $empty = 0;

        foreach ($values as $sample) {
            $id = $sample->get_id();

            try {
                $value = trim((string)$render->format($sample));
            } catch (\Throwable $e) {
                $value = '';
            }

            if ('' === $value || AC\Formatter\EmptyValue::DEFAULT === $value) {
                $empty++;
                continue;
            }

            $items[] = [
                'id'    => $id,
                'label' => $sample_items->get_label($table_screen, $id) ?: sprintf('#%s', $id),
                'value' => $value,
            ];

            if (count($items) >= self::MAX_RESULTS) {
                break;
            }
        }

        return [
            'supported' => true,
            'items'     => $items,
            'sampled'   => count($items) + $empty,
            'empty'     => $empty,
        ];
    }

    private function get_column_factory(TableScreen $table_screen, string $column_type): ?ColumnFactory
    {
        foreach ($this->column_factory->create($table_screen) as $factory) {
            if ($factory->get_column_type() === $column_type) {
                return $factory;
            }
        }

        return null;
    }

}
