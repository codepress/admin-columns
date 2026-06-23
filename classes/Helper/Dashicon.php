<?php

declare(strict_types=1);

namespace AC\Helper;

// TODO Tobias yes and no work, but it might hit wrong with reserved words. so create_yes() might be safer
final class Dashicon
{
    private string $icon;

    private string $class;

    private ?string $title;

    private ?string $tooltip;

    public function __construct(
        string $icon,
        // TODO Tobias make nullable as well and then a default?
        string $class = '',
        ?string $title = null,
        ?string $tooltip = null
    ) {
        $this->icon = $icon;
        $this->class = $class;
        $this->title = $title;
        $this->tooltip = $tooltip;
    }

    // TODO Tobias ?string $class is inconsistent, I would either null it or force a string
    public static function create(string $icon, string $class = ''): self
    {
        return new self($icon, $class);
    }

    // TODO Tobias Perhaps null over green because now green is repeated default
    public static function yes(?string $tooltip = null, ?string $title = null, ?string $class = 'green'): self
    {
        if (null === $title) {
            $title = __('Yes');
        }

        return new self('yes', $class ?: 'green', $title, $tooltip);
    }

    // TODO Tobias Perhaps null over green because now red is a non-forced default
    public static function no(?string $tooltip = null, ?string $title = null, ?string $class = 'red'): self
    {
        if (null === $title) {
            $title = __('No');
        }

        return new self('no-alt', (string)$class, $title, $tooltip);
    }

    // TODO Tobias $is_true is semantically not very strong. $yes = true or $is_yes?
    public static function yes_or_no(bool $is_true, ?string $tooltip = null): self
    {
        return $is_true
            ? self::yes($tooltip)
            : self::no($tooltip);
    }

    public function with_class(string $class): self
    {
        return new self($this->icon, $class, $this->title, $this->tooltip);
    }

    // TODO Tobias with and nullable is weird, why would you call this with null? Or is there a reason you want to
    // kill a title?
    public function with_title(?string $title): self
    {
        return new self($this->icon, $this->class, $title, $this->tooltip);
    }

    // TODO TObias same as above
    public function with_tooltip(?string $tooltip): self
    {
        return new self($this->icon, $this->class, $this->title, $tooltip);
    }

    public function render(): string
    {
        $class = 'dashicons dashicons-' . $this->icon;

        // TODO Tobias Yoda style no more is
        if ('' !== $this->class) {
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
