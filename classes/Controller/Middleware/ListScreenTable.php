<?php

declare(strict_types=1);

namespace AC\Controller\Middleware;

use AC\ListScreen;
use AC\ListScreenFactory;
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

    private $list_screen_factory;

    private $preference;

    private $list_key;

    public function __construct(
        Storage $storage,
        ListKey $list_key,
        ListScreenFactory $list_screen_factory,
        Table\LayoutPreference $preference
    ) {
        $this->storage = $storage;
        $this->list_key = $list_key;
        $this->list_screen_factory = $list_screen_factory;
        $this->preference = $preference;
    }

    private function get_first_list_screen(WP_User $user): ?ListScreen
    {
        $list_screens = $this->storage->find_all_by_assigned_user(
            (string)$this->list_key,
            $user,
            new Sort\UserOrder($user, (string)$this->list_key)
        );

        if ($list_screens->valid()) {
            return $list_screens->current();
        }

        return $this->list_screen_factory->can_create($this->list_key)
            ? $this->list_screen_factory->create($this->list_key)
            : null;
    }

    private function get_preference_list_screen(WP_User $user): ?ListScreen
    {
        $list_key = (string)$this->list_key;

        try {
            $list_id = new ListScreenId((string)$this->preference->get($list_key));
        } catch (Exception $e) {
            return null;
        }

        $list_screen = $this->storage->find($list_id);

        if ( ! $list_screen ||
             ! $list_screen->is_user_assigned($user) ||
             ! $this->list_key->equals(new ListKey($list_screen->get_key()))
        ) {
            return null;
        }

        return $list_screen;
    }

    private function get_requested_list_screen(Request $request, WP_User $user): ?ListScreen
    {
        try {
            $list_id = new ListScreenId((string)$request->get('layout'));
        } catch (Exception $e) {
            return null;
        }

        $list_screen = $this->storage->find($list_id);

        if ( ! $list_screen ||
             ! $list_screen->is_user_allowed($user) ||
             ! $this->list_key->equals(new ListKey($list_screen->get_key()))
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