<?php

declare(strict_types=1);

namespace AC\Setting;

class Multiple implements Setting
{

    use SettingTrait;

    private $options;

    private $other_option;

    public function __construct(
        string $name,
        OptionCollection $options,
        bool $other_option = false,
        string $label = '',
        string $description = ''
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->description = $description;
        $this->options = $options;
        $this->other_option = $other_option;
    }

    public static function create($name, OptionCollection $options, bool $other_option = false): self
    {
        return new self($name, $options, $other_option);
    }

    public function with_label(string $label): self
    {
        return new self($this->name, $this->options, $this->other_option, $label, $this->description);
    }

    public function with_description(string $description): self
    {
        return new self($this->name, $this->options, $this->other_option, $this->label, $description);
    }

    public function get_options(): OptionCollection
    {
        return $this->options;
    }

    public function has_other_option(): bool
    {
        return $this->other_option;
    }

}