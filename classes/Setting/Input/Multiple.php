<?php

declare(strict_types=1);

namespace AC\Setting\Input;

use AC\Setting\Input;
use AC\Setting\OptionCollection;
use AC\Setting\OptionCollectionFactory\ToggleOptionCollection;

final class Multiple implements Input
{

    private $type;

    private $multiple;

    private $options;

    private function __construct(string $type, bool $multiple, OptionCollection $options)
    {
        $this->type = $type;
        $this->multiple = $multiple;
        $this->options = $options;
    }

    public static function create_select(OptionCollection $options, bool $multiple = false): self
    {
        return new self('select', $multiple, $options);
    }

    public static function create_radio(OptionCollection $options): self
    {
        return new self('radio', false, $options);
    }

    public static function create_toggle(): self
    {
        return new self('toggle', false, (new ToggleOptionCollection())->create());
    }

    public static function create_checkbox(OptionCollection $options): self
    {
        return new self('checkbox', true, $options);
    }

    public function get_type(): string
    {
        return $this->type;
    }

    public function is_multiple(): bool
    {
        return $this->multiple;
    }

    public function get_options(): OptionCollection
    {
        return $this->options;
    }

}