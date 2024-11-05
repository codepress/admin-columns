<?php

namespace AC\Helper;

class Strings
{

    public function starts_with(string $haystack, string $needle): bool
    {
        return '' === $needle || 0 === strpos($haystack, $needle);
    }

    public function remove_prefix(string $string, string $prefix): string
    {
        return $this->starts_with($string, $prefix)
            ? substr($string, strlen($prefix))
            : $string;
    }

    public function ends_with(string $haystack, string $needle): bool
    {
        if ('' === $haystack && '' !== $needle) {
            return false;
        }

        $len = strlen($needle);

        return 0 === substr_compare($haystack, $needle, -$len, $len);
    }

    public function get_shortcodes(string $content): array
    {
        global $shortcode_tags;

        if ( ! $shortcode_tags || ! $content) {
            return [];
        }

        $shortcodes = [];

        $_shortcodes = array_keys($shortcode_tags);
        asort($_shortcodes);

        foreach ($_shortcodes as $shortcode) {
            $count = substr_count($content, '[' . $shortcode . ']');
            $count += substr_count($content, '[' . $shortcode . ' ');

            if ($count) {
                $shortcodes[$shortcode] = $count;
            }
        }

        return $shortcodes;
    }

    /**
     * Count the number of words in a string (multibyte-compatible)
     */
    public function word_count(string $string): int
    {
        if (empty($string)) {
            return 0;
        }

        $string = trim(strip_tags($string));

        if (empty($string)) {
            return 0;
        }

        $patterns = [
            'strip' => '/<[a-zA-Z\/][^<>]*>/',
            'clean' => '/[0-9.(),;:!?%#$¿\'"_+=\\/-]+/',
            'w'     => '/\S\s+/',
            'c'     => '/\S/',
        ];

        $string = preg_replace($patterns['strip'], ' ', $string);
        $string = preg_replace('/&nbsp;|&#160;/i', ' ', $string);
        $string = preg_replace($patterns['clean'], '', $string);

        if ( ! strlen(preg_replace('/\s/', '', $string))) {
            return 0;
        }

        return preg_match_all($patterns['w'], $string, $matches) + 1;
    }

    /**
     * Trims a string and strips tags if there is any HTML
     */
    public function trim_characters(string $string, int $limit = 10, string $trail = null): string
    {
        if (1 > $limit) {
            return $string;
        }

        $string = wp_strip_all_tags($string);

        if (mb_strlen($string) <= $limit) {
            return $string;
        }

        if (null === $trail) {
            $trail = __('&hellip;');
        }

        return trim(mb_substr($string, 0, $limit)) . $trail;
    }

    public function is_image(string $url): bool
    {
        if ( ! $url) {
            return false;
        }

        $ext = strtolower(pathinfo(strtok($url, '?'), PATHINFO_EXTENSION));

        return in_array($ext, ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'webp']);
    }

    public function is_valid_url(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) || preg_match('/[^\w.-]/', $url);
    }

    public function is_empty($value): bool
    {
        return ! $this->is_not_empty($value);
    }

    public function is_not_empty($value): bool
    {
        return $value || 0 === $value || '0' === $value;
    }

    public function enumeration_list(array $items, string $compound = 'or'): string
    {
        if ('and' === $compound) {
            return wp_sprintf('%l', $items);
        }

        if ('or' === $compound) {
            $compound = __(' or ', 'codepress-admin-columns');
        }

        $compound = sprintf(' %s ', trim($compound));

        $last = end($items);
        $delimiter = ', ';

        return str_replace($delimiter . $last, $compound . $last, implode($delimiter, $items));
    }

}