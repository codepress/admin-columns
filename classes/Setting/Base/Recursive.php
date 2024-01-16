<?php

declare(strict_types=1);

namespace AC\Setting\Base;

use AC;
use AC\Setting\SettingCollection;
use AC\Expression\Specification;

class Recursive extends Setting implements AC\Setting\Recursive
{

    private $settings;

    private $parent;

    public function __construct(
        string $name,
        SettingCollection $settings,
        string $label = '',
        string $description = '',
        AC\Setting\Input $input = null,
        Specification $conditions = null,
        bool $parent = false
    ) {
        parent::__construct(
            $name,
            $label,
            $description,
            $input,
            $conditions
        );

        $this->settings = $settings;
        $this->parent = $parent;
    }

    public function get_children(): SettingCollection
    {
        return $this->settings;
    }

    public function is_parent(): bool
    {
        return $this->parent;
    }

}