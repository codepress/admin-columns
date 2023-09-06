<?php

namespace AC\Helper\Select;

final class OptionGroup
{

    private $label;

    /**
     * @var Option[]
     */
    private $options;

    /**
     * @param string   $label
     * @param Option[] $options
     */
    public function __construct(string $label, array $options = [])
    {
        $this->label = $label;

        foreach ($options as $option) {
            $this->add_option($option);
        }
    }

    public function get_label(): string
    {
        return $this->label;
    }

    /**
     * @return Option[]
     */
    public function get_options(): array
    {
        return $this->options;
    }

    protected function add_option(Option $option): self
    {
        $this->options[] = $option;

        return $this;
    }

}