<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Setting\Input\Custom;

class Single implements Setting
{

    use SettingTrait;

    private function __construct(
        string $name,
        Input $input = null,
        string $label = '',
        string $description = ''
    ) {
        if (null === $input) {
            $input = new Custom($this);
        }

        $this->name = $name;
        $this->label = $label;
        $this->description = $description;
        $this->input = $input;
    }

    public static function create(string $name): self
    {
        return new self($name);
    }

    public function with_label(string $label): self
    {
        return new self($this->name, $this->input, $label, $this->description);
    }

    public function with_description(string $description): self
    {
        return new self($this->name, $this->input, $this->label, $description);
    }

}