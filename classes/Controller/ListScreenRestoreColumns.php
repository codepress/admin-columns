<?php

namespace AC\Controller;

use AC\Capabilities;
use AC\ListScreenRepository\Storage;
use AC\Message\Notice;
use AC\Registerable;
use AC\Type\ListScreenId;
use LogicException;

class ListScreenRestoreColumns implements Registerable
{

    /**
     * @var Storage
     */
    private $repository;

    public function __construct(Storage $repository)
    {
        $this->repository = $repository;
    }

    public function register(): void
    {
        add_action('admin_init', [$this, 'handle_request']);
    }

    public function handle_request()
    {
        if ( ! current_user_can(Capabilities::MANAGE)) {
            return;
        }

        switch (filter_input(INPUT_POST, 'action')) {
            case 'restore_by_type' :
                if ($this->verify_nonce('restore-type')) {
                    try {
                        $id = new ListScreenId(filter_input(INPUT_POST, 'layout'));
                    } catch (LogicException $e) {
                        return;
                    }

                    $list_screen = $this->repository->find($id);

                    if ( ! $list_screen) {
                        return;
                    }

                    // TODO add default columns...
                    $list_screen->set_settings([]);
                    $this->repository->save($list_screen);

                    $notice = new Notice(
                        sprintf(
                            __('Settings for %s restored successfully.', 'codepress-admin-columns'),
                            "<strong>" . esc_html($list_screen->get_title()) . "</strong>"
                        )
                    );
                    $notice->register();
                }
                break;
        }
    }

    /**
     * @param string $action
     *
     * @return bool
     */
    private function verify_nonce($action)
    {
        return wp_verify_nonce(filter_input(INPUT_POST, '_ac_nonce'), $action);
    }

}