<?php

declare(strict_types=1);

namespace AC\Notifcations;

use AC\Capabilities;
use AC\NoticeRepository;
use AC\Registerable;
use AC\Screen;
use AC\Type\Notice;
use AC\Type\NoticeConditions;

class Review implements Registerable
{

    private $repository;

    public function __construct(NoticeRepository $repository)
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

        $conditions = new NoticeConditions();
        $conditions->set_screens([$screen->get_id()])
                   ->set_user(1);

        $this->repository->save(
            new Notice(
                'check-review',
                'Review Message',
                Notice::INFO,
                $conditions
            )
        );
    }

}