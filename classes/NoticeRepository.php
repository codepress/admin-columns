<?php

declare(strict_types=1);

namespace AC;

use AC\Storage\MetaRepository;
use AC\Storage\OptionFactory;
use AC\Type\Notice;
use AC\Type\NoticeCollection;
use DateTime;

class NoticeRepository
{

    private $storage;

    private $user_storage;

    public function __construct(OptionFactory $option_factory)
    {
        $this->storage = $option_factory->create('acp_notices');
        $this->user_storage = new MetaRepository(MetaType::create_user_type(), 'acp_notices');
    }

    public function save(Notice $notice): void
    {
        if ($notice->has_user()) {
            $this->update_user($notice->get_user(), $notice);
        } else {
            $this->update_option($notice);
        }
    }

    private function update_option(Notice $notice): void
    {
        $notices = $this->storage->get() ?: [];

        $notices[] = $this->encode_notice($notice);

        $this->storage->save($notices);
    }

    private function update_user(int $user_id, Notice $notice): void
    {
        $notices = $this->user_storage->get($user_id) ?: [];

        $notices[] = $this->encode_notice($notice);

        $this->user_storage->save($user_id, $notices);
    }

    public function find_all(int $user = null): NoticeCollection
    {
        $data = $user
            ? $this->user_storage->get($user)
            : $this->storage->get();

        return new NoticeCollection(array_map([$this, 'decode_notice'], $data ?: []));
    }

    public function delete_all(int $user = null): void
    {
        $user
            ? $this->user_storage->delete($user)
            : $this->storage->delete();
    }

    private function decode_notice(array $data): Notice
    {
        return new Notice(
            (string)$data['id'],
            (string)$data['content'],
            (array)$data['screens'],
            (string)$data['type'],
            isset($data['user']) ? (int)$data['user'] : null,
            isset($data['date']) ? DateTime::createFromFormat('Y-m-d H:i:s', $data['date']) : null
        );
    }

    private function encode_notice(Notice $notice): array
    {
        $data = [
            'id'      => $notice->get_id(),
            'content' => $notice->get_content(),
            'type'    => $notice->get_type(),
            'screens' => $notice->get_screens(),
        ];

        if ($notice->has_user()) {
            $data['user'] = $notice->get_user();
        }
        if ($notice->has_date()) {
            $data['date'] = $notice->get_date()->format('Y-m-d H:i:s');
        }

        return $data;
    }

}