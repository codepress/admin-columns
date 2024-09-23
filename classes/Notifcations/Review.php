<?php

declare(strict_types=1);

namespace AC\Notifcations;

use AC\Capabilities;
use AC\Notice\Repository;
use AC\Registerable;
use AC\Screen;

// TODO David remove
class Review implements Registerable
{

    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function register(): void
    {
        // TODO
        add_action('ac/screen', [$this, 'add_notification']);
    }

    public function add_notification(Screen $screen): void
    {
        if ( ! $screen->has_screen()) {
            return;
        }

        if ( ! current_user_can(Capabilities::MANAGE)) {
            return;
        }

        if ( ! $screen->is_admin_screen() && ! $screen->is_table_screen()) {
            return;
        }

        // TODO David set screen id
        // TODO David set user id (1?)

//        $conditions = new NoticeConditions();
//        $conditions->set_screens([$screen->get_id()])
//                   ->set_user(1);
//
//        $this->repository->save(
//            new Notice(
//                'check-review',
//                'Review Message',
//                Notice::INFO,
//                $conditions
//            )
//        );
    }

}