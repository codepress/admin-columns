<?php

declare(strict_types=1);

namespace AC\Request\Middleware;

use AC\ListScreen;
use AC\ListScreenRepository\Sort;
use AC\ListScreenRepository\Storage;
use AC\Middleware;
use AC\Request;
use AC\Table;
use AC\Type\ListScreenId;
use AC\Type\ListScreenStatus;
use AC\Type\TableId;
use Exception;
use WP_User;

class ListScreenTable implements Middleware
{

    private Storage $storage;

    private Table\LayoutPreference $preference;

    private TableId $list_key;

    private WP_User $user;

    public function __construct(
        Storage $storage,
        TableId $list_key,
        Table\LayoutPreference $preference,
        WP_User $user
    ) {
        $this->storage = $storage;
        $this->list_key = $list_key;
        $this->preference = $preference;
        $this->user = $user;
    }

    private function get_first_list_screen(): ?ListScreen
    {
        $list_screens = $this->storage->find_all_by_assigned_user(
            $this->list_key,
            $this->user,
            new Sort\UserOrder($this->user, $this->list_key),
            ListScreenStatus::create_active()
        );

        if ( ! $list_screens->valid()) {
            return null;
        }

        return $list_screens->current();
    }

    private function get_preference_list_screen(): ?ListScreen
    {
        $list_id = $this->preference->find_list_id($this->list_key);

        return $list_id
            ? $this->get_list_screen_by_id($list_id)
            : null;
    }

    private function get_requested_list_screen(Request $request): ?ListScreen
    {
        try {
            $id = new ListScreenId((string)$request->get('layout'));
        } catch (Exception $e) {
            return null;
        }

        return $this->get_list_screen_by_id($id);
    }

    private function get_list_screen_by_id(ListScreenId $id): ?ListScreen
    {
        $list_screen = $this->storage->find($id);

        if ( ! $list_screen ||
             ! $list_screen->is_user_allowed($this->user) ||
             ! $this->list_key->equals($list_screen->get_table_id()) ||
             ! $list_screen->get_status()->equals(ListScreenStatus::create_active())
        ) {
            return null;
        }

        return $list_screen;
    }

    private function get_list_screen(Request $request): ?ListScreen
    {
        $list_screen = $this->get_requested_list_screen($request);

        if ( ! $list_screen) {
            $list_screen = $this->get_preference_list_screen();
        }

        return $list_screen ?: $this->get_first_list_screen();
    }

    public function handle(Request $request): void
    {
        $request->get_parameters()->merge([
            'list_screen' => $this->get_list_screen($request),
        ]);
    }

}