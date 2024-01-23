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

class CustomFieldKeys implements RequestAjaxHandler
{

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

        $post_type = (string)$request->get('post_type');

        $query = new Query($request->get('meta_type'));

        $query->select('meta_key')
              ->distinct()
              ->order_by('meta_key');

        if ($post_type) {
            $query->where_post_type($post_type);
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