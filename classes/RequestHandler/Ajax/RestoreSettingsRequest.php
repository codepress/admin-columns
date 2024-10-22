<?php

namespace AC\RequestHandler\Ajax;

use AC\Capabilities;
use AC\ListScreenRepository;
use AC\ListScreenRepositoryWritable;
use AC\Message\Notice;
use AC\Nonce;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\Response;

// TODO create JS handler
class RestoreSettingsRequest implements RequestAjaxHandler
{

    private ListScreenRepository\Storage\ListScreenRepository $repository;

    public function __construct(ListScreenRepository\Storage\ListScreenRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(): void
    {
        if ( ! current_user_can(Capabilities::MANAGE)) {
            return;
        }

        $request = new Request();
        $response = new Response\Json();

        if ( ! (new Nonce\Ajax())->verify($request)) {
            $response->error();
        }

        $repository = $this->repository->get_list_screen_repository();

        if ( ! $repository instanceof ListScreenRepositoryWritable) {
            $response->error();
        }

        foreach ($repository->find_all() as $list_screen) {
            $repository->delete($list_screen);
        }

        $this->delete_options();
        $this->delete_user_preferences();

        do_action('ac/settings/restore');

        $notice = new Notice(__('Default settings successfully restored.', 'codepress-admin-columns'));
        $notice->register();
        $response->success();
    }

    private function delete_user_preferences(): void
    {
        global $wpdb;

        $wpdb->query("DELETE FROM $wpdb->usermeta WHERE meta_key LIKE '{$wpdb->get_blog_prefix()}ac_preferences_%'");
    }

    private function delete_options(): void
    {
        global $wpdb;

        $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'ac_api_request%'");
        $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'ac_cache_data%'");
        $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'ac_sorting_%'");
        $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'cpac_options%__default'");
        $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'cpac_general_options'");
    }

}