<?php

declare(strict_types=1);

namespace AC\Storage;

/**
 * Similar to storing metadata, but this prefixes the key with the site ID when running a multisite installation.
 */
class UserOption implements UserData
{

    private string $key;

    private int $user_id;

    public function __construct(string $key, ?int $user_id = null)
    {
        if (null === $user_id) {
            $user_id = get_current_user_id();
        }

        $this->key = $key;
        $this->user_id = $user_id;
    }

    public function get()
    {
        return get_user_option($this->key, $this->user_id);
    }

    public function save($value): void
    {
        update_user_option($this->user_id, $this->key, $value);
    }

    public function delete(): void
    {
        delete_user_option($this->user_id, $this->key);
    }

}