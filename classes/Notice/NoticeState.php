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
        $count = (int)($data[$slug]['dismiss_count'] ?? 0);
        $data[$slug]['dismiss_count'] = $count + 1;
        $data[$slug]['dismissed_at'] = time();

        $this->save_data($data);
    }

    /**
     * Check if a contextual notice is currently suppressed. Supports recurring
     * dismissal: the notice reappears after $cooldown_days, up to $max total
     * dismissals. After reaching $max, permanently dismissed.
     * Backwards compatible with old ['dismissed' => true] format.
     */
    public function is_recurring_dismissed(string $slug, int $max, int $cooldown_days): bool
    {
        $data = $this->get_data();

        if ( ! empty($data[$slug]['dismissed'])) {
            return true;
        }

        $count = (int)($data[$slug]['dismiss_count'] ?? 0);

        if ($count === 0) {
            return false;
        }

        if ($count >= $max) {
            return true;
        }

        $dismissed_at = (int)($data[$slug]['dismissed_at'] ?? 0);

        return $dismissed_at > 0 && (time() - $dismissed_at) < $cooldown_days * DAY_IN_SECONDS;
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
