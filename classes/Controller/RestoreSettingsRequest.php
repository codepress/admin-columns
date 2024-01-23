<?php

namespace AC\Controller;

use AC\Capabilities;
use AC\ListScreenRepository;
use AC\Message\Notice;
use AC\Registerable;

class RestoreSettingsRequest implements Registerable
{

    private $repository;

    public function __construct(ListScreenRepository\Storage\ListScreenRepository $repository)
    {
        $this->repository = $repository;
    }

    public function register(): void
    {
        add_action('admin_init', [$this, 'handle_request']);
    }

    public function handle_request(): void
    {
        if ( ! current_user_can(Capabilities::MANAGE)) {
            return;
        }

        if ('restore' !== filter_input(INPUT_POST, 'ac_action')) {
            return;
        }

        if ( ! wp_verify_nonce(filter_input(INPUT_POST, '_ac_nonce'), 'restore')) {
            return;
        }

        $repository = $this->repository->get_list_screen_repository();

        foreach ($repository->find_all() as $list_screen) {
            // TODO
            $repository->delete($list_screen);
        }

        $this->delete_options();
        $this->delete_user_preferences();

        do_action('ac/settings/restore');

        $notice = new Notice(__('Default settings successfully restored.', 'codepress-admin-columns'));
        $notice->register();
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