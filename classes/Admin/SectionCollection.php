<?php

namespace AC\Admin;

class SectionCollection
{

    private $items = [];

    public function add(Section $section, int $priority = 10): self
    {
        $this->items[$priority][$section->get_slug()] = $section;

        return $this;
    }

    public function get(string $slug): ?Section
    {
        $all = $this->all();

        return $all[$slug] ?? null;
    }

    public function all(): array
    {
        ksort($this->items);

        return array_merge(...$this->items);
    }

}