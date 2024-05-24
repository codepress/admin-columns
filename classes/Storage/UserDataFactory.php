<?php

declare(strict_types=1);

namespace AC\Storage;

class UserDataFactory
{

    public function create(string $key, bool $network = false, int $user_id = null): UserData
    {
        return $network
            ? new UserOption($key, $user_id)
            : new UserMeta($key, $user_id);
    }

}