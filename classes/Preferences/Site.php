<?php

namespace AC\Preferences;

use AC\Preferences;

// TODO remove
class Site extends Preferences
{

    protected function load()
    {
        return get_user_option($this->get_key(), $this->get_user_id());
    }

    public function save(): bool
    {
        return (bool)update_user_option($this->get_user_id(), $this->get_key(), $this->data);
    }

}