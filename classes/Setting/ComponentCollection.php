<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Collection;

final class ComponentCollection extends Collection
{

    private int $added = 0;

    private const ADDED_SEPARATOR = '.';

    /**
     * @var string[]
     */
    private array $priorities = [];

    public function __construct(array $data = [], int $priority = 10)
    {
        foreach ($data as $component) {
            $this->add($component, $priority);
        }
    }

    private function get_priority_key(int $priority): string
    {
        return $priority . self::ADDED_SEPARATOR . $this->added++;
    }

    private function get_priority_from_key(string $priority_key): int
    {
        return (int)strstr($priority_key, self::ADDED_SEPARATOR, true);
    }

    /**
     * Priority is taken into account when iterating. E.g. 1 comes before 5.
     */
    public function add(Component $component, int $priority = 10): ComponentCollection
    {
        $this->data[$this->get_priority_key($priority)] = $component;

        ksort($this->data, SORT_NATURAL);

        $this->priorities = array_keys($this->data);

        return $this;
    }

    public function current(): Component
    {
        return current($this->data);
    }

    public function current_priority(): int
    {
        return $this->get_priority_from_key($this->priorities[$this->index]);
    }

    public function merge(ComponentCollection $collection): ComponentCollection
    {
        $merged = new ComponentCollection();

        foreach( $this as $component ) {
            $merged->add($component, $this->current_priority() );
        }

        foreach ($collection as $component) {
            $merged->add($component, $collection->current_priority());
        }

        return $merged;
    }

    public function find(string $name, ?ComponentCollection $settings = null): ?Component
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