<?php

declare(strict_types=1);

namespace AC\Setting;

// TODO David remove?
class Multiple implements Setting, Option
{

    use SettingTrait;
    use OptionTrait;

    private function __construct(
        string $name,
        OptionCollection $options,
        Input\Multiple $input = null,
        string $label = '',
        string $description = ''
    ) {
        if ($input === null) {
            $input = Input\Multiple::create_select();
        }

        $this->name = $name;
        $this->label = $label;
        $this->description = $description;
        $this->options = $options;
        $this->input = $input;
    }

    public static function create($name, OptionCollection $options, Input $input = null): self
    {
        return new self($name, $options, $input);
    }

    public function with_label(string $label): self
    {
        return new self($this->name, $this->options, $this->input, $label, $this->description);
    }

    public function with_description(string $description): self
    {
        return new self($this->name, $this->options, $this->input, $this->label, $description);
    }

}