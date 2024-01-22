<?php

namespace AC\Storage;

class UserMeta implements UserData
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
    }

    public function get(array $args = [])
    {
        $args = array_merge([
            'single' => true,
        ], $args);

        return get_user_meta($this->user_id, $this->key, $args['single']);
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