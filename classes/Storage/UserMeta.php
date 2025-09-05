<?php

declare(strict_types=1);

namespace AC\Storage;

class UserMeta implements UserData
{

    protected int $user_id;

    protected string $key;

    private bool $single;

    public function __construct(string $key, ?int $user_id = null, bool $single = true)
    {
        if (null === $user_id) {
            $user_id = get_current_user_id();
        }

        $this->user_id = $user_id;
        $this->key = $key;
        $this->single = $single;
    }

    public function get()
    {
        return get_user_meta($this->user_id, $this->key, $this->single);
    }

    public function save($value): void
    {
        update_user_meta($this->user_id, $this->key, $value);
    }

    public function delete(): void
    {
        delete_user_meta($this->user_id, $this->key);
    }

}