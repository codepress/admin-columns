<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;
use AC\Settings;

class PathScope extends Settings\Setting implements Formatter
{

    private $path_scope;

    public function __construct(string $path_scope, Specification $conditions = null)
    {
        parent::__construct(
            __('Path scope', 'codepress-admin-columns'),
            __('Part of the file path to display', 'codepress-admin-columns'),
            OptionFactory::create_select(
                'path_scope',
                OptionCollection::from_array([
                    'full'             => __('Full Path', 'codepress-admin-columns'),
                    'relative-domain'  => __('Relative to domain', 'codepress-admin-columns'),
                    'relative-uploads' => __('Relative to main uploads folder', 'codepress-admin-columns'),
                    'local'            => __('Local Path', 'codepress-admin-columns'),
                ]),
                $path_scope
            ),
            $conditions
        );

        $this->path_scope = $path_scope;
    }

    public function format(Value $value, Config $options): Value
    {
        // TODO: Implement format() method.
        return $value;
    }

    public function formatsss($value, $original_value)
    {
        $file = $value;
        $value = '';

        if ($file) {
            switch ($this->get_path_scope()) {
                case 'relative-domain' :
                    $file = str_replace('https://', 'http://', $file);
                    $url = str_replace('https://', 'http://', home_url('/'));

                    if (strpos($file, $url) === 0) {
                        $file = '/' . substr($file, strlen($url));
                    }

                    break;
                case 'relative-uploads' :
                    $file = str_replace('https://', 'http://', $file);
                    $upload_dir = wp_upload_dir();
                    $url = str_replace('https://', 'http://', $upload_dir['baseurl']);

                    if (strpos($file, $url) === 0) {
                        $file = substr($file, strlen($url));
                    }

                    break;
                case 'local' :
                    $file = get_attached_file($original_value);

                    break;
            }

            $value = $file;
        }

        return $value;
    }

}