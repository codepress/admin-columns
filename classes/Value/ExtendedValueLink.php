<?php

declare(strict_types=1);

namespace AC\Value;

final class ExtendedValueLink
{

    private $view;

    private $attributes;

    private $id;

    private $label;

    private $params;

    public function __construct(string $label, int $id, string $view, $attributes = [], $params = [])
    {
        $this->view = $view;
        $this->attributes = $attributes;
        $this->id = $id;
        $this->label = $label;
        $this->params = $params;
    }

    protected function with_attribute(string $key, string $value): self
    {
        $this->attributes[$key] = $value;

        return new self($this->label, $this->id, $this->view, $this->attributes, $this->params);
    }

    public function with_params($params): self
    {
        return new self($this->label, $this->id, $this->view, $this->attributes, $params);
    }

    public function with_title(string $title): self
    {
        return $this->with_attribute('title', $title);
    }

    public function with_edit_link(string $edit_link): self
    {
        return $this->with_attribute('edit_link', $edit_link);
    }

    public function with_download_link(string $download_link): self
    {
        return $this->with_attribute('download_link', $download_link);
    }

    public function with_class(string $class): self
    {
        return $this->with_attribute('class', $class);
    }

    public function render(): string
    {
        $attributes = $this->attributes;
        $attribute_markup = [];

        if (isset($attributes['title']) && $attributes['title']) {
            $attribute_markup[] = sprintf('data-modal-title="%s"', esc_attr($attributes['title']));
        }
        if (isset($attributes['edit_link']) && $attributes['edit_link']) {
            $attribute_markup[] = sprintf('data-modal-edit-link="%s"', esc_url($attributes['edit_link']));
        }
        if (isset($attributes['download_link']) && $attributes['download_link']) {
            $attribute_markup[] = sprintf('data-modal-download-link="%s"', esc_url($attributes['download_link']));
        }
        if (isset($attributes['class']) && $attributes['class']) {
            $attribute_markup[] = sprintf('data-modal-class="%s"', esc_attr($attributes['class']));
        }

        if ( ! empty($this->params)) {
            $attribute_markup[] = sprintf('data-modal-params="%s"', esc_attr(json_encode($this->params)));
        }

        $attribute_markup[] = sprintf('data-modal-id="%s"', esc_attr($this->id));
        $attribute_markup[] = sprintf('data-view="%s"', esc_attr($this->view));

        return sprintf(
            '<a style="border-bottom: 1px dotted;" data-modal-value %s>%s</a>',
            implode(' ', $attribute_markup),
            $this->label
        );
    }
}