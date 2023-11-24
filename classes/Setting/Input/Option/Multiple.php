<?php

declare(strict_types=1);

namespace AC\Setting\Input\Option;

use AC\Setting\Input\Option;
use AC\Setting\OptionCollection;

final class Multiple implements Option
{

    private $type;

    private $options;

    private $defaults;

    public function __construct(string $type, OptionCollection $options, array $defaults = [])
    {
        $this->type = $type;
        $this->options = $options;
        $this->defaults = $defaults;
    }

    public static function create_select(OptionCollection $options, array $defaults = []): self
    {
        return new self('select', $options, $defaults);
    }

    public static function create_checkbox(OptionCollection $options, array $defaults = []): self
    {
        return new self('checkbox', $options, $defaults);
    }

    public function get_type(): string
    {
        return $this->type;
    }

    public function get_options(): OptionCollection
    {
        return $this->options;
    }

    public function has_defaults(): bool
    {
        return ! empty($this->defaults);
    }

    public function get_defaults(): array
    {
        return $this->defaults;
    }

}