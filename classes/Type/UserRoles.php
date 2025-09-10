<?php

declare(strict_types=1);

namespace AC\Type;

use AC\Collection;

class UserRoles extends Collection
{

    public function __construct(array $roles = [])
    {
        $this->data = $roles;
    }

    public function add(UserRole $role): void
    {
        $this->data[] = $role;
    }

    public function current(): UserRole
    {
        return current($this->data);
    }

}