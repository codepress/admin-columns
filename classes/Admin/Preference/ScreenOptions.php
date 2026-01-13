<?php

namespace AC\Admin\Preference;

use AC\Preferences\Preference;
use AC\Preferences\UserFactory;

class ScreenOptions
{

    public function storage(): Preference
    {
        return (new UserFactory())->create(UserFactory::SCREEN_OPTIONS);
    }

    public function is_active(string $option): bool
    {
        return 1 === $this->storage()->find($option);
    }

    public function set_status(string $option, bool $active): void
    {
        $this->storage()->save($option, (int)$active);
    }

}