<?php

declare(strict_types=1);

namespace AC\Setting;

class Single implements Setting
{

    use SettingTrait;

    public function __construct(
        string $name,
        string $label = '',
        string $description = ''
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->description = $description;
    }

    public static function create(string $name): self
    {
        return new self($name);
    }

    public function with_label(string $label): self
    {
        return new self($this->name, $label, $this->description);
    }

    public function with_description(string $description): self
    {
        return new self($this->name, $this->label, $description);
    }

}