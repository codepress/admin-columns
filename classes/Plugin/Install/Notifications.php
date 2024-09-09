<?php

declare(strict_types=1);

namespace AC\Plugin\Install;

use AC\NoticeRepository;
use AC\Plugin\Install;
use AC\Type\Notice;

class Notifications implements Install
{

    private $repository;

    public function __construct(NoticeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function install(): void
    {
        foreach ($this->get_administrator_ids() as $user_id) {
            // TODO
            $this->repository->delete_all((int)$user_id);
            $this->repository->save(
                $this->create_notice((int)$user_id)
            );
        }
    }

    private function create_notice(int $user_id): Notice
    {
        return new Notice(
            'check-review',
            'Review Message',
            [
                'list_screen' => 'any',
                'settings'    => 'any',
            ],
            Notice::INFO,
            $user_id
        );
    }

    private function get_administrator_ids(): array
    {
        return get_users([
            'role'   => 'administrator',
            'fields' => 'ids',
        ]);
    }

}