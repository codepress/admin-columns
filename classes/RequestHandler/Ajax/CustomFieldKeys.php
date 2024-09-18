<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC\Capabilities;
use AC\Meta\Query;
use AC\Nonce;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\Response\Json;

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

        $meta_type = $request->get('meta_type');
        $post_type = $request->get('post_type');

        $query = new Query((string)$meta_type);

        $query->select('meta_key')
              ->distinct()
              ->order_by('meta_key');

        if ($post_type) {
            $query->where_post_type((string)$post_type);
        }

        // TODO David continue
        $meta_keys = $query->get();
        $encoded = [];

        foreach ($meta_keys as $meta_key) {
            $encoded[] = [
                'value' => $meta_key,
                'label' => $meta_key,
                'group' => $this->get_group($meta_key),
            ];
        }

        $response->set_header(
            'Cache-Control',
            'max-age=120'
        );

        $response
            ->set_parameter('options', $encoded)
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