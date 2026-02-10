<?php

namespace AC\Form\Element;

use AC\Form\Element;

class Select extends Element
{

    protected string $no_result = '';

    protected function render_options(array $options): string
    {
        $output = [];

        foreach ($options as $key => $option) {
            if (isset($option['options']) && is_array($option['options'])) {
                $output[] = $this->render_optgroup($option);

                continue;
            }

            $output[] = $this->render_option((string)$key, (string)$option);
        }

        return implode("\n", $output);
    }

    protected function render_option(string $key, string $label): string
    {
        $template = '<option %s>%s</option>';
        $attributes = $this->get_option_attributes($key);

        return sprintf($template, $this->get_attributes_as_string($attributes), esc_html($label));
    }

    protected function get_option_attributes(string $key): array
    {
        $attributes = [];
        $attributes['value'] = $key;

        if ($this->selected($key)) {
            $attributes['selected'] = 'selected';
        }

        return $attributes;
    }

    protected function selected(string $value): bool
    {
        return (bool)selected($this->get_value(), $value, false);
    }

    protected function render_optgroup(array $group): string
    {
        $template = '<optgroup %s>%s</optgroup>';
        $attributes = [];

        if (isset($group['title'])) {
            $attributes['label'] = esc_attr($group['title']);
        }

        return sprintf(
            $template,
            $this->get_attributes_as_string($attributes),
            $this->render_options($group['options'])
        );
    }

    public function render(): string
    {
        if ( ! $this->get_options() && $this->get_no_result()) {
            return $this->get_no_result();
        }

        $template = '
			<select %s>
				%s
			</select>
			%s';

        $attributes = $this->get_attributes();
        $attributes['name'] = $this->get_name();
        $attributes['id'] = $this->get_id();

        return sprintf(
            $template,
            $this->get_attributes_as_string($attributes),
            $this->render_options($this->get_options()),
            $this->render_description()
        );
    }

    public function get_no_result(): string
    {
        return $this->no_result;
    }

}