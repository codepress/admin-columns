<?php

declare(strict_types=1);

namespace AC\Setting;

use Countable;

final class ComponentCollection extends Collection implements Countable
{

    private int $added = 0;

    public function __construct(array $data = [], int $priority = 10)
    {
        foreach ($data as $component) {
            $this->add($component, $priority);
        }
    }

    /**
     * Priority is taken into account when iterating. E.g. 1 comes before 5.
     */
    public function add(Component $component, int $priority = 10): ComponentCollection
    {
        $this->data[$priority . '.' . $this->added++] = $component;

        ksort($this->data, SORT_NATURAL);

        return $this;
    }

    public function current(): Component
    {
        return current($this->data);
    }

    public function count(): int
    {
        return count($this->data);
    }

    public function merge(ComponentCollection $collection): ComponentCollection
    {
        $merged = new ComponentCollection($this->data);

        foreach ($collection as $component) {
            $merged->add($component);
        }

        return $merged;
    }

    public function find(string $name, ComponentCollection $settings = null): ?Component
    {
        $settings = $settings ?: $this;

        foreach ($settings as $setting) {
            if ($setting->has_children()) {
                $found = $this->find($name, $setting->get_children()->get_iterator());

                if ($found) {
                    return $found;
                }
            }

            if ($setting->has_input() && $name === $setting->get_input()->get_name()) {
                return $setting;
            }
        }

        return null;
    }

}