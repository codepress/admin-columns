<?php

declare(strict_types=1);

namespace AC\Helper;

use AC\Type\Link;
use WP_HTML_Tag_Processor;

class Html extends Creatable
{
    public function get_attribute_as_string(string $key, ?string $value = null): string
    {
        return Strings::create()->is_not_empty($value)
            ? sprintf('%s="%s"', $key, esc_attr(trim((string)$value)))
            : $key;
    }

    public function get_style_attributes_as_string(array $attributes): string
    {
        $style = '';

        foreach ($attributes as $key => $value) {
            $style .= $key . ':' . $value . '; ';
        }

        return $style;
    }

    public function link(string $url, ?string $label = null, array $attributes = []): string
    {
        if (! $url) {
            return $label ?? '';
        }

        if (null === $label) {
            $label = urldecode($url);
        }

        if (! $label) {
            return '';
        }

        if (! $this->contains_html($label)) {
            $label = esc_html($label);
        }

        if (array_key_exists('tooltip', $attributes)) {
            $attributes['data-ac-tip'] = $attributes['tooltip'];

            unset($attributes['tooltip']);
        }

        $protocols = wp_allowed_protocols();
        $protocols[] = 'skype';
        $protocols[] = 'call';

        return (new Link(
            $url,
            $label,
            $this->filter_attributes($attributes),
            $protocols
        ))->render();
    }

    public function divider(): string
    {
        return '<span class="ac-divider"></span>';
    }

    public function get_tooltip_attr(string $content): string
    {
        if (! $content) {
            return '';
        }

        return 'data-ac-tip="' . esc_attr($content) . '"';
    }

    public function tooltip(string $label, string $tooltip, array $attributes = []): string
    {
        if (Strings::create()->is_not_empty($label) && $tooltip) {
            $label = sprintf(
                '<span %s %s>%s</span>',
                $this->get_tooltip_attr($tooltip),
                $this->get_attributes($attributes),
                $label
            );
        }

        return $label;
    }

    public function codearea(string $string, int $max_chars = 1000): string
    {
        if (! $string) {
            return '';
        }

        $contents = substr(
            $string,
            0,
            $max_chars
        );

        return '<textarea style="color: #808080; width: 100%; min-height: 60px;" readonly>' . esc_textarea($contents) . '</textarea>';
    }

    private function filter_attributes(array $attributes): array
    {
        $filtered = [];

        foreach ($attributes as $attribute => $value) {
            if (
                in_array($attribute, ['title', 'id', 'class', 'style', 'target', 'rel', 'download'], true) ||
                'data-' === substr($attribute, 0, 5)) {
                $filtered[$attribute] = $value;
            }
        }

        return $filtered;
    }

    private function get_attributes(array $attributes): string
    {
        $_attributes = [];

        foreach ($this->filter_attributes($attributes) as $attribute => $value) {
            $_attributes[] = $this->get_attribute_as_string($attribute, (string)$value);
        }

        return ' ' . implode(' ', $_attributes);
    }

    public function get_links(string $string): ?array
    {
        // Just do a very simple check to check for possible links
        if (false === strpos($string, '<a')) {
            return null;
        }

        $hrefs = [];

        $processor = new WP_HTML_Tag_Processor($string);

        while ($processor->next_tag(['tag_name' => 'a'])) {
            $href = $processor->get_attribute('href');

            if (! is_string($href) || 0 === strpos($href, '#')) {
                continue;
            }

            $hrefs[] = $href;
        }

        return $hrefs;
    }

    private function contains_html(string $string): bool
    {
        return $string && $string !== strip_tags($string);
    }

    public function implode(array $array, bool $divider = true): string
    {
        // Remove empty values
        $array = $this->remove_empty($array);

        if (true === $divider) {
            $divider = $this->divider();
        }

        return implode($divider ?: '', $array);
    }

    public function remove_empty(array $array): array
    {
        return array_filter($array, [Strings::create(), 'is_not_empty']);
    }

    /**
     * Small HTML block with grey background and rounded corners
     */
    public function small_block(array $items): string
    {
        $blocks = [];

        foreach ($items as $item) {
            if ($item && is_scalar($item)) {
                $blocks[] = sprintf('<span class="ac-small-block">%s</span>', $item);
            }
        }

        return implode($blocks);
    }

    /**
     * Return round HTML span
     */
    public function rounded(string $string): string
    {
        return sprintf('<span class="ac-rounded">%s</span>', $string);
    }

    public function images(string $html, ?int $removed = null): string
    {
        if (! $html) {
            return '';
        }

        if ($removed) {
            $html .= $this->rounded('+' . $removed);
        }

        return '<div class="ac-image-container">' . $html . '</div>';
    }

    public function file_pill(string $extension, string $filename): string
    {
        return sprintf(
            '<span class="ac-file-type">%s</span><span class="ac-file-name">%s</span>',
            esc_html(strtoupper($extension)),
            esc_html($filename)
        );
    }

    /**
     * @depecated 7.0.9
     */
    public function get_internal_external_links(): ?array
    {
        _deprecated_function(__METHOD__, '7.0.9');

        return [];
    }

    public function strip_attributes(string $html): string
    {
        _deprecated_function(__METHOD__, '7.0.9');

        return $html;
    }

    public function more(array $array, int $limit = 10, string $glue = ', '): string
    {
        _deprecated_function(__METHOD__, '7.0.11');

        return implode($glue, $array);
    }

}
