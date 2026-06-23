<?php

declare(strict_types=1);

namespace AC\Helper;

final class Dashicon
{
    private string $icon;

    private ?string $class;

    private ?string $title;

    private ?string $tooltip;

    public function __construct(
        string $icon,
        ?string $class = null,
        ?string $title = null,
        ?string $tooltip = null
    ) {
        $this->icon = $icon;
        $this->class = $class;
        $this->title = $title;
        $this->tooltip = $tooltip;
    }

    public static function create(string $icon, ?string $class = null): self
    {
        return new self($icon, $class);
    }

    public static function yes(?string $tooltip = null, ?string $title = null, ?string $class = null): self
    {
        return new self('yes', $class ?? 'green', $title ?? __('Yes'), $tooltip);
    }

    public static function no(?string $tooltip = null, ?string $title = null, ?string $class = null): self
    {
        return new self('no-alt', $class ?? 'red', $title ?? __('No'), $tooltip);
    }

    public static function yes_or_no(bool $is_yes, ?string $tooltip = null): self
    {
        return $is_yes
            ? self::yes($tooltip)
            : self::no($tooltip);
    }

    public function with_class(string $class): self
    {
        return new self($this->icon, $class, $this->title, $this->tooltip);
    }

    public function with_title(string $title): self
    {
        return new self($this->icon, $this->class, $title, $this->tooltip);
    }

    public function with_tooltip(string $tooltip): self
    {
        return new self($this->icon, $this->class, $this->title, $tooltip);
    }

    public function render(): string
    {
        $class = 'dashicons dashicons-' . $this->icon;

        if ($this->class) {
            $class .= ' ' . trim($this->class);
        }

        $attributes = [];

        if (null !== $this->title && '' !== $this->title) {
            $attributes[] = sprintf('title="%s"', esc_attr($this->title));
        }

        if (null !== $this->tooltip && '' !== $this->tooltip) {
            $attributes[] = Html::create()->get_tooltip_attr($this->tooltip);
        }

        return sprintf(
            '<span class="%s" %s></span>',
            esc_attr($class),
            implode(' ', $attributes)
        );
    }

    public function __toString(): string
    {
        return $this->render();
    }

}
