<?php

namespace AC\Formatter;

use AC\Formatter;
use AC\Type\Value;

class PathScope implements Formatter
{

    private $path_scope;

    public function __construct(string $path_scope)
    {
        $this->path_scope = $path_scope;
    }

    public function format(Value $value): Value
    {
        $file = $value->get_value();

        if ( ! $file) {
            return $value;
        }

        switch ($this->path_scope) {
            case 'relative-domain' :
                $file = str_replace('https://', 'http://', $file);
                $url = str_replace('https://', 'http://', home_url('/'));

                if (strpos($file, $url) === 0) {
                    $file = '/' . substr($file, strlen($url));
                }

                return $value->with_value($file);
            case 'relative-uploads' :
                $file = str_replace('https://', 'http://', $file);
                $upload_dir = wp_upload_dir();
                $url = str_replace('https://', 'http://', $upload_dir['baseurl']);

                if (strpos($file, $url) === 0) {
                    $file = substr($file, strlen($url));
                }

                return $value->with_value($file);
            case 'local':

                return $value->with_value(get_attached_file($value->get_id()));
            default:
                return $value;
        }
    }

}