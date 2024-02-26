<?php

namespace AC\Helper;

class Strings
{

    public function shorten_url(string $url): string
    {
        return ac_helper()->html->link($url, url_shorten($url), ['title' => $url]);
    }

    public function contains(string $haystack, string $needle): bool
    {
        return '' === $needle || false !== strpos($haystack, $needle);
    }

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

    public function strip_trim(string $string): string
    {
        return trim(strip_tags($string));
    }

    /**
     * @param string $content
     *
     * @return array
     */
    public function get_shortcodes($content)
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

        $string = $this->strip_trim($string);

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

    public function trim_words(string $string, int $limit = 30, string $more = null): string
    {
        if ('' === $string || ! $limit) {
            return '';
        }

        return wp_trim_words($string, $limit, $more);
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

    /**
     * Formats a valid hex color to a 6 digit string, optionally prefixed with a #
     * Example: #FF0 will be fff000 based on the $prefix parameter
     *
     * @param string $hex    Valid hex color
     * @param bool   $prefix Prefix with a # or not
     *
     * @return string
     */
    protected function hex_format($hex, $prefix = false)
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) == 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        if ($prefix) {
            $hex = '#' . $hex;
        }

        return strtolower($hex);
    }

    /**
     * Get RGB values from a hex color string
     *
     * @param string $hex Valid hex color
     *
     * @return array
     * @since 3.0
     */
    public function hex_to_rgb($hex)
    {
        $hex = $this->hex_format($hex);

        return sscanf($hex, '%2x%2x%2x');
    }

    /**
     * Get contrasting hex color based on given hex color
     *
     * @param string $hex Valid hex color
     *
     * @return string
     * @since 3.0
     */
    public function hex_get_contrast($hex)
    {
        $rgb = $this->hex_to_rgb($hex);
        $contrast = ($rgb[0] * 0.299 + $rgb[1] * 0.587 + $rgb[2] * 0.114) < 186 ? 'fff' : '333';

        return $this->hex_format($contrast, true);
    }

    /**
     * @param string $url
     *
     * @return bool
     * @since 1.2.0
     */
    public function is_image($url)
    {
        if ( ! $url || ! is_string($url)) {
            return false;
        }

        $ext = strtolower(pathinfo(strtok($url, '?'), PATHINFO_EXTENSION));

        return in_array($ext, ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'webp']);
    }

    /**
     * @param string $string
     *
     * @return array
     * @since 3.0
     */
    public function comma_separated_to_array($string)
    {
        $array = [];
        if (is_scalar($string)) {
            if (strpos($string, ',') !== false) {
                $array = array_filter(explode(',', ac_helper()->string->strip_trim(str_replace(' ', '', $string))));
            } else {
                $array = [$string];
            }
        } elseif (is_array($string)) {
            $array = $string;
        }

        return $array;
    }

    /**
     * @param string $string
     *
     * @return array
     * @since 3.0
     */
    public function string_to_array_integers($string)
    {
        $integers = [];

        foreach ($this->comma_separated_to_array($string) as $k => $value) {
            if (is_numeric(trim($value))) {
                $integers[] = $value;
            }
        }

        return $integers;
    }

    /**
     * @param string $hex Color Hex Code
     *
     * @return string
     * @since 3.0
     */
    // TODO move to HTML or convert to View
    public function get_color_block($hex)
    {
        if ( ! $hex) {
            return false;
        }

        return '<div class="cpac-color"><span style="background-color:' . esc_attr($hex) . ';color:' . esc_attr(
                $this->hex_get_contrast($hex)
            ) . '">' . esc_html($hex) . '</span></div>';
    }

    public function is_valid_url(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) || preg_match('/[^\w.-]/', $url);
    }

    /**
     * @return string Display empty value
     */
    public function get_empty_char()
    {
        _deprecated_function(__METHOD__, '3.0', 'AC\Column::get_empty_char');

        return '&ndash;';
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