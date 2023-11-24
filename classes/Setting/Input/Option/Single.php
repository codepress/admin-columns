<?php

declare(strict_types=1);

namespace AC\Setting\Input\Option;

use AC\Setting\Input;
use AC\Setting\OptionCollection;
use AC\Setting\OptionCollectionFactory\ToggleOptionCollection;

final class Single extends Input\Single implements Input\Option
{

    private $type;

    private $options;

    public function __construct(string $type, OptionCollection $options, string $default = null)
    {
        $this->type = $type;
        $this->options = $options;

        parent::__construct($default);
    }

    public static function create_select(OptionCollection $options, string $default = null): self
    {
        return new self('select', $options, $default);
    }

    public static function create_radio(OptionCollection $options, string $default = null): self
    {
        return new self('radio', $options, $default);
    }

    public static function create_toggle(string $default = null): self
    {
        return new self('toggle', (new ToggleOptionCollection())->create(), $default);
    }

    public function get_type(): string
    {
        return $this->type;
    }

    public function get_options(): OptionCollection
    {
        return $this->options;
    }

}