<?php

declare(strict_types=1);

namespace AC\Notice;

use AC\Storage\UserMeta;

class NoticeState
{

    private const META_KEY = '_ac_notices';

    private ?array $data = null;

    public function get(string $slug, string $key)
    {
        $data = $this->get_data();

        return $data[$slug][$key] ?? null;
    }

    public function set(string $slug, string $key, $value): void
    {
        $data = $this->get_data();
        $data[$slug][$key] = $value;

        $this->save_data($data);
    }

    public function is_dismissed(string $slug): bool
    {
        return (bool)$this->get($slug, 'dismissed');
    }

    public function dismiss(string $slug): void
    {
        $data = $this->get_data();
        $data[$slug] = ['dismissed' => true];

        $this->save_data($data);
    }

    public function track_first_seen(string $slug): void
    {
        if ( ! $this->get($slug, 'first-seen')) {
            $this->set($slug, 'first-seen', time());
        }
    }

    public function is_delay_met(string $slug, int $days): bool
    {
        $first_seen = $this->get($slug, 'first-seen');

        return $first_seen && (time() - (int)$first_seen) >= $days * DAY_IN_SECONDS;
    }

    private function get_data(): array
    {
        if (null === $this->data) {
            $this->data = $this->create_storage()->get() ?: [];
        }

        return $this->data;
    }

    private function save_data(array $data): void
    {
        $this->data = $data;
        $this->create_storage()->save($data);
    }

    private function create_storage(): UserMeta
    {
        return new UserMeta(self::META_KEY);
    }

}
