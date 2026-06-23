<?php

declare(strict_types=1);

namespace AC\Helper;

class LocalFile extends Creatable
{
    public function url_to_path(string $url): string
    {
        return (string)str_replace(WP_CONTENT_URL, WP_CONTENT_DIR, $url);
    }

    public function path_to_url(string $path): string
    {
        return (string)str_replace(WP_CONTENT_DIR, WP_CONTENT_URL, $path);
    }

    public function get_path(string $url): ?string
    {
        $url = set_url_scheme($url, wp_parse_url(WP_CONTENT_URL, PHP_URL_SCHEME) ?: null);
        $path = $this->url_to_path($url);

        if (! file_exists($path)) {
            return null;
        }

        return $path;
    }

    public function get_size(string $url): ?int
    {
        $path = $this->get_path($url);

        if ($path === null) {
            return null;
        }

        $size = filesize($path);

        return $size === false
            ? null
            : $size;
    }

    public function get_file_name(int $attachment_id): ?string
    {
        $file = get_post_meta($attachment_id, '_wp_attached_file', true);

        if (! $file) {
            return null;
        }

        return basename((string)$file);
    }

    public function get_file_extension(int $attachment_id): string
    {
        return (string)pathinfo($this->get_file_name($attachment_id) ?? '', PATHINFO_EXTENSION);
    }

}
