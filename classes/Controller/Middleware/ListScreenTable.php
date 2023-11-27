<?php

declare(strict_types=1);

namespace AC\Controller\Middleware;

use AC\ListScreen;
use AC\ListScreenRepository\Sort;
use AC\ListScreenRepository\Storage;
use AC\Middleware;
use AC\Request;
use AC\Table;
use AC\Type\ListKey;
use AC\Type\ListScreenId;
use Exception;
use WP_User;

class ListScreenTable implements Middleware
{

    private $storage;

    private $preference;

    private $list_key;

    public function __construct(
        Storage $storage,
        ListKey $list_key,
        Table\LayoutPreference $preference
    ) {
        $this->storage = $storage;
        $this->list_key = $list_key;
        $this->preference = $preference;
    }

    private function get_first_list_screen(WP_User $user): ?ListScreen
    {
        $list_screens = $this->storage->find_all_by_assigned_user(
            $this->list_key,
            $user,
            new Sort\UserOrder($user, $this->list_key)
        );

        if ( ! $list_screens->valid()) {
            return null;
        }

        return $list_screens->current();
    }

    private function get_preference_list_screen(WP_User $user): ?ListScreen
    {
        try {
            $id = new ListScreenId((string)$this->preference->get((string)$this->list_key));
        } catch (Exception $e) {
            return null;
        }

        return $this->get_list_screen_by_id($id, $user);
    }

    private function get_requested_list_screen(Request $request, WP_User $user): ?ListScreen
    {
        try {
            $id = new ListScreenId((string)$request->get('layout'));
        } catch (Exception $e) {
            return null;
        }

        return $this->get_list_screen_by_id($id, $user);
    }

    private function get_list_screen_by_id(ListScreenId $id, WP_User $user): ?ListScreen
    {
        $list_screen = $this->storage->find($id);

        if ( ! $list_screen ||
             ! $list_screen->is_user_allowed($user) ||
             ! $this->list_key->equals($list_screen->get_key())
        ) {
            return null;
        }

        return $list_screen;
    }

    private function get_list_screen(Request $request): ?ListScreen
    {
        $user = wp_get_current_user();

        if ( ! $user) {
            return null;
        }

        $list_screen = $this->get_requested_list_screen($request, $user);

        if ( ! $list_screen) {
            $list_screen = $this->get_preference_list_screen($user);
        }

        return $list_screen ?: $this->get_first_list_screen($user);
    }

    public function handle(Request $request): void
    {
        $request->get_parameters()->merge([
            'list_screen' => $this->get_list_screen($request),
        ]);
    }

}