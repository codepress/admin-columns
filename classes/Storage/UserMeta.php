<?php

namespace AC\Storage;

use LogicException;

class UserMeta implements KeyValuePair
{

    protected $user_id;

    protected $key;

    public function __construct(string $key, int $user_id = null)
    {
        if (null === $user_id) {
            $user_id = get_current_user_id();
        }

        $this->user_id = $user_id;
        $this->key = $key;

        $this->validate();
    }

    private function validate(): void
    {
        if ($this->user_id < 0) {
            throw new LogicException('Invalid user id.');
        }
        if ('' === $this->key) {
            throw new LogicException('Invalid key.');
        }
    }

    public function get()
    {
        return get_user_meta($this->user_id, $this->key, true);
    }

    public function save($value): bool
    {
        return (bool)update_user_meta($this->user_id, $this->key, $value);
    }

    public function delete(): bool
    {
        return delete_user_meta($this->user_id, $this->key);
    }

    public function exists(): bool
    {
        return false !== $this->get();
    }

}