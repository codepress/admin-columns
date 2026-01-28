<?php

namespace AC\Table;

class Button
{

    private string $slug;

    private string $label = '';

    private string $tooltip = '';

    private string $dashicon = '';

    protected array $attributes = [];

    public function __construct(string $slug)
    {
        $this->slug = $slug;
        $this->add_class('ac-table-button -' . $slug);
    }

    public function get_attributes(): array
    {
        return $this->attributes;
    }

    public function add_class(string $class): self
    {
        $this->set_attribute('class', $this->get_attribute('class') . ' ' . esc_attr($class));

        return $this;
    }

    public function get_attribute($key)
    {
        if ( ! isset($this->attributes[$key])) {
            return false;
        }

        return trim($this->attributes[$key]);
    }

    public function set_attribute(string $key, string $value): self
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    protected function get_attributes_as_string(array $attributes): string
    {
        $output = [];

        foreach ($attributes as $key => $value) {
            $output[] = $this->get_attribute_as_string((string)$key, $value);
        }

        return implode(' ', $output);
    }

    protected function get_attribute_as_string(string $key, ?string $value = null): string
    {
        if (null === $value) {
            $value = $this->get_attribute($key);
        }

        return ac_helper()->html->get_attribute_as_string($key, $value);
    }

    public function get_slug(): string
    {
        return $this->slug;
    }

    public function get_tooltip(): string
    {
        return $this->tooltip;
    }

    public function set_tooltip(string $tooltip): self
    {
        $this->tooltip = $tooltip;

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

    public function get_dashicon(): string
    {
        if ( ! $this->dashicon) {
            return '';
        }

        return ac_helper()->icon->dashicon([
            'icon' => $this->dashicon,
        ]);
    }

    public function set_dashicon(string $dashicon): self
    {
        $this->dashicon = $dashicon;

        return $this;
    }

    public function set_url(string $url): self
    {
        $this->set_attribute('href', esc_url($url));

        return $this;
    }

    public function render(): void
    {
        $attributes = $this->get_attributes();
        $tooltip = $this->get_tooltip();

        if ($tooltip) {
            $attributes['data-ac-tip'] = $tooltip;
        }
        $attributes['data-slug'] = $this->get_slug();

        $template = '<a %s>%s%s</a>';

        echo sprintf(
            $template,
            $this->get_attributes_as_string($attributes),
            $this->get_dashicon(),
            $this->get_label()
        );
    }

}