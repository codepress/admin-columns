<?php

namespace AC\Deprecated;

use AC\Transient;

class Hooks
{

    private HookCollectionFactory $factory;

    public function __construct(HookCollectionFactory $factory)
    {
        $this->factory = $factory;
    }

    public function get_count(bool $force_update = false): int
    {
        $cache = new Transient('ac-deprecated-message-count');

        if ($force_update || $cache->is_expired()) {
            $cache->save($this->get_deprecated_count(), WEEK_IN_SECONDS);
        }

        return (int)$cache->get();
    }

    public function get_deprecated_filters(): HookCollection
    {
        return $this->check_deprecated_hooks(
            $this->factory->create_filters()
        );
    }

    public function get_deprecated_actions(): HookCollection
    {
        return $this->check_deprecated_hooks(
            $this->factory->create_actions()
        );
    }

    private function check_deprecated_hooks(HookCollection $hooks): HookCollection
    {
        $deprecated = [];

        foreach ($hooks as $hook) {
            if ($hook->has_hook()) {
                $deprecated[] = $hook;
            }
        }

        return new HookCollection($deprecated);
    }

    public function get_deprecated_count(): int
    {
        return $this->get_deprecated_actions()->count() +
               $this->get_deprecated_filters()->count();
    }

}