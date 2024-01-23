<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC\Capabilities;
use AC\Meta\Query;
use AC\Nonce;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\Response\Json;
use AC\Setting\OptionCollection;
use AC\Setting\OptionCollectionEncoder;
use AC\Setting\Type\Option;
use AC\TableScreen;
use AC\TableScreenFactory\Aggregate;
use AC\Type\ListKey;

class CustomFieldKeys implements RequestAjaxHandler
{

    private $table_screen_factory;

    public function __construct(Aggregate $table_screen_factory)
    {
        $this->table_screen_factory = $table_screen_factory;
    }

    public function handle(): void
    {
        if ( ! current_user_can(Capabilities::MANAGE)) {
            return;
        }

        $request = new Request();
        $response = new Json();

        if ( ! (new Nonce\Ajax())->verify($request)) {
            $response->error();
        }

        $table_screen = $this->table_screen_factory->create(new ListKey($request->get('list_key')));

        if ( ! $table_screen instanceof TableScreen\MetaType) {
            $response->error();
        }

        $query = new Query((string)$table_screen->get_meta_type());

        $query->select('meta_key')
              ->distinct()
              ->order_by('meta_key');

        if ($table_screen instanceof TableScreen\Post) {
            $query->where_post_type($table_screen->get_post_type());
        }

        $meta_keys = $query->get();

        $collection = new OptionCollection();

        foreach ($meta_keys as $meta_key) {
            $collection->add(
                new Option(
                    $meta_key,
                    $meta_key,
                    $this->get_group($meta_key)
                )
            );
        }

        $encoder = new OptionCollectionEncoder();

        $response->set_header(
            'Cache-Control',
            'max-age=120'
        );

        $response
            ->set_parameters($encoder->encode($collection))
            ->success();
    }

    private function get_group(string $meta_key): string
    {
        if (str_starts_with($meta_key, '_')) {
            return __('Hidden', 'codepress-admin-columns');
        }

        return __('Default', 'codepress-admin-columns');
    }

}