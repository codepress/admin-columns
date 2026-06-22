<?php

declare(strict_types=1);

namespace AC\Type;

use AC\Renderable;

final class Link implements Renderable
{
    private string $url;

    private ?string $label;

    private array $attributes;

    /**
     * @var array<string>|null Allowed protocols passed to esc_url(). Null uses WordPress' defaults.
     */
    private ?array $protocols;

    /**
     * @param array<string, string> $attributes
     * @param array<string>|null    $protocols
     */
    public function __construct(
        string $url,
        ?string $label = null,
        array $attributes = [],
        ?array $protocols = null
    ) {
        $this->url = $url;
        $this->label = $label;
        $this->attributes = $attributes;
        $this->protocols = $protocols;
    }

    public function get_url(): string
    {
        return $this->url;
    }

    public function get_label(): ?string
    {
        return $this->label;
    }

    public function with_label(?string $label): self
    {
        return new self($this->url, $label, $this->attributes, $this->protocols);
    }

    public function with_attribute(string $name, string $value): self
    {
        $attributes = $this->attributes;
        $attributes[$name] = $value;

        return new self($this->url, $this->label, $attributes, $this->protocols);
    }

    public function with_tooltip(string $tooltip): self
    {
        return $this->with_attribute('data-ac-tip', $tooltip);
    }

    /**
     * @param array<string, string> $attributes
     */
    public function with_attributes(array $attributes): self
    {
        return new self(
            $this->url,
            $this->label,
            array_merge($this->attributes, $attributes),
            $this->protocols
        );
    }

    /**
     * @param array<string> $protocols
     */
    public function with_protocols(array $protocols): self
    {
        return new self($this->url, $this->label, $this->attributes, $protocols);
    }

    private function render_attributes(): string
    {
        $rendered = [];

        foreach ($this->attributes as $name => $value) {
            $value = trim((string)$value);

            // An empty value renders a boolean attribute (e.g. "download").
            $rendered[] = '' !== $value
                ? sprintf('%s="%s"', $name, esc_attr($value))
                : (string)$name;
        }

        return $rendered ? ' ' . implode(' ', $rendered) : '';
    }

    public function render(): string
    {
        return sprintf(
            '<a href="%s"%s>%s</a>',
            esc_url($this->url, $this->protocols),
            $this->render_attributes(),
            (string)$this->label
        );
    }

    public function __toString(): string
    {
        return $this->render();
    }

}
