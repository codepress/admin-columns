<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC\Capabilities;
use AC\Nonce;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\Response\Json;
use WP_Site;

class NetworkPostStati implements RequestAjaxHandler
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

        $response->set_header('Cache-Control', 'max-age=120')
                 ->set_parameter('options', $this->get_options())
                 ->success();
    }

    private function get_distinct_db_values(): array
    {
        if ( ! function_exists('get_sites')) {
            return [];
        }

        global $wpdb;

        $queries = [];
        foreach (get_sites() as $site) {
            /**
             * @var WP_Site $site
             */
            $table = $wpdb->get_blog_prefix($site->id) . 'posts';

            $sql = "SELECT DISTINCT 'post_status' FROM $table";

            $queries[] = $sql;
        }

        return $wpdb->get_col(implode(" UNION ", $queries));
    }

    private function get_cached_options(): array
    {
        $values = wp_cache_get('ac-site-settings', 'ac-site-post-status');

        if ( ! $values) {
            $values = $this->get_distinct_db_values();

            wp_cache_add('ac-site-settings', $values, 'ac-site-post-status', 60);
        }

        return $values;
    }

    private function get_options(): array
    {
        global $wp_post_statuses;

        $options = [];

        foreach ($this->get_post_statuses() as $name) {
            $status = $wp_post_statuses[$name] ?? null;

            $options[] = [
                'value' => $name,
                'label' => $status->label ?? $name,
                'group' => $status
                    ? __('Status', 'codepress-admin-columns')
                    : __('Other', 'codepress-admin-columns'),
            ];
        }

        $defaults = [
            [
                'value' => '',
                'label' => __('Any post status', 'codepress-admin-columns'),
                'group' => null,
            ],
            [
                'value' => 'without_trash',
                'label' => __('Any post status without Trash', 'codepress-admin-columns'),
                'group' => null,
            ],
        ];

        uasort($options, function ($a, $b) {
            return strnatcasecmp((string)$a['label'], (string)$b['label']);
        });
        uasort($options, function ($a, $b) {
            return strnatcasecmp((string)$b['group'], (string)$a['group']);
        });

        return array_merge($defaults, $options);
    }

    private function get_post_statuses(): array
    {
        $post_statuses = $this->get_cached_options();

        if ( ! $post_statuses) {
            return [];
        }

        $post_statuses[] = 'trash';
        $post_statuses = array_unique(array_merge($post_statuses, array_keys(get_post_statuses())));
        $post_statuses = array_combine($post_statuses, $post_statuses);

        // Exclude 'auto-draft', 'inherit'
        $excluded = (array)get_post_stati(['show_in_admin_status_list' => false]);

        foreach ($excluded as $status) {
            if (isset($post_statuses[$status])) {
                unset($post_statuses[$status]);
            }
        }

        return $post_statuses;
    }

}