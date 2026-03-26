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
        $this->set($slug, 'dismissed', true);
    }

    public function track_first_seen(string $slug): void
    {
        if ( ! $this->get($slug, 'first-seen')) {
            $this->set($slug, 'first-seen', time());
        }
    }

    /**
     * Check if enough time has passed since the notice was first seen.
     * Each notice defines its own delay via get_delay_days(). This prevents
     * notices from showing immediately on new installs.
     */
    public function is_delay_met(string $slug, int $days): bool
    {
        $first_seen = $this->get($slug, 'first-seen');

        return $first_seen && (time() - (int)$first_seen) >= $days * DAY_IN_SECONDS;
    }

    /**
     * Record that a notice was just dismissed. Used together with is_cooldown_active()
     * to suppress all integration notices for a period after any dismissal, preventing
     * the user from seeing back-to-back notices.
     */
    public function track_dismissal(): void
    {
        $data = $this->get_data();
        $data['_last_dismissed'] = time();

        $this->save_data($data);
    }

    /**
     * Check if a recent dismissal should suppress all notices.
     * Returns true if any notice was dismissed less than $days ago.
     */
    public function is_cooldown_active(int $days): bool
    {
        $last = $this->get_data()['_last_dismissed'] ?? null;

        return $last && (time() - (int)$last) < $days * DAY_IN_SECONDS;
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
