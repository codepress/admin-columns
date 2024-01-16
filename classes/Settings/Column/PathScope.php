<?php

namespace AC\Settings\Column;

use AC;
use AC\Setting\ArrayImmutable;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;
use AC\Settings;
use AC\Expression\Specification;

class PathScope extends Settings\Column implements Formatter
{

    /**
     * @var string
     */
    private $path_scope;

    protected function define_options()
    {
        return [
            'path_scope' => 'full',
        ];
    }

    public function __construct(AC\Column $column, Specification $conditions = null)
    {
        $this->name = 'path_scope';
        $this->label = __('Path scope', 'codepress-admin-columns');
        $this->description = __('Part of the file path to display', 'codepress-admin-columns');
        $this->input = AC\Setting\Input\Option\Single::create_select(
            AC\Setting\OptionCollection::from_array([
                'full'             => __('Full Path', 'codepress-admin-columns'),
                'relative-domain'  => __('Relative to domain', 'codepress-admin-columns'),
                'relative-uploads' => __('Relative to main uploads folder', 'codepress-admin-columns'),
                'local'            => __('Local Path', 'codepress-admin-columns'),
            ]),
            'full'
        );

        parent::__construct(
            $column,
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