<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Setting\ArrayImmutable;
use AC\Setting\Formatter;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Type\Value;
use AC\Settings;

class PathScope extends Settings\Column implements Formatter
{

    public function __construct(Specification $conditions = null)
    {
<<<<<<< HEAD
        return [
            'path_scope' => 'full',
        ];
    }

    public function __construct(AC\Column $column, Specification $conditions = null)
    {
        $this->name = 'path_scope';
        $this->label = __('Path scope', 'codepress-admin-columns');
        $this->description = __('Part of the file path to display', 'codepress-admin-columns');
        $this->input = OptionFactory::create_select(
            AC\Setting\Component\OptionCollection::from_array([
=======
        $input = AC\Setting\Input\Option\Single::create_select(
            AC\Setting\OptionCollection::from_array([
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
                'full'             => __('Full Path', 'codepress-admin-columns'),
                'relative-domain'  => __('Relative to domain', 'codepress-admin-columns'),
                'relative-uploads' => __('Relative to main uploads folder', 'codepress-admin-columns'),
                'local'            => __('Local Path', 'codepress-admin-columns'),
            ]),
            'full'
        );

        parent::__construct(
            'path_scope',
            __('Path scope', 'codepress-admin-columns'),
            __('Part of the file path to display', 'codepress-admin-columns'),
            $input,
            $conditions
        );
    }

    public function format(Value $value, ArrayImmutable $options): Value
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