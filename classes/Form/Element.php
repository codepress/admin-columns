<?php

namespace AC\Form;

use AC\Renderable;

abstract class Element implements Renderable
{

    protected array $attributes = [];

    /**
     * Options for element like select
     */
    protected array $options = [];

    /**
     * The elements value
     * @var mixed
     */
    protected $value = null;

    protected string $label = '';

    protected string $description = '';

    /**
     * Setup element with base name and id
     */
    public function __construct(string $name, array $options = [])
    {
        $this->set_name($name);
        $this->set_id($name);
        $this->set_options($options);
    }

    protected function render_description(): ?string
    {
        if ( ! $this->get_description()) {
            return null;
        }

        $template = '<p class="help-msg">%s</p>';

        return sprintf($template, $this->get_description());
    }

    abstract public function render(): string;

    public function get_attribute(string $key): ?string
    {
        if ( ! isset($this->attributes[$key])) {
            return null;
        }

        return trim((string)$this->attributes[$key]);
    }

    public function set_attribute(string $key, string $value): self
    {
        if ('value' === $key) {
            $this->set_value($value);

            return $this;
        }

        $this->attributes[$key] = $value;

        return $this;
    }

    public function get_attributes(): array
    {
        return $this->attributes;
    }

    public function set_attributes(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            $this->set_attribute((string)$key, (string)$value);
        }

        return $this;
    }

    /**
     * Get attributes as string
     */
    protected function get_attributes_as_string(array $attributes): string
    {
        $output = [];

        foreach ($attributes as $key => $value) {
            $output[] = $this->get_attribute_as_string((string)$key, $value);
        }

        return implode(' ', $output);
    }

    /**
     * Render an attribute
     */
    protected function get_attribute_as_string(string $key, ?string $value = null): string
    {
        if (null === $value) {
            $value = $this->get_attribute($key);
        }

        return ac_helper()->html->get_attribute_as_string($key, $value);
    }

    public function get_name(): ?string
    {
        return $this->get_attribute('name');
    }

    public function set_name(string $name): self
    {
        return $this->set_attribute('name', $name);
    }

    public function get_id(): ?string
    {
        return $this->get_attribute('id');
    }

    public function set_id(string $id): self
    {
        return $this->set_attribute('id', $id);
    }

    /**
     * @return mixed
     */
    public function get_value()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function set_value($value): self
    {
        $this->value = $value;

        return $this;
    }

    public function set_class(string $class): self
    {
        $this->set_attribute('class', $class);

        return $this;
    }

    public function add_class(string $class): self
    {
        $parts = explode(' ', (string)$this->get_attribute('class'));
        $parts[] = $class;

        $this->set_class(implode(' ', $parts));

        return $this;
    }

    public function get_label(): string
    {
        return $this->label;
    }

    public function set_label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function set_options(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function get_options(): array
    {
        return $this->options;
    }

    public function get_description(): string
    {
        return $this->description;
    }

    public function set_description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function __toString(): string
    {
        return $this->render();
    }

}