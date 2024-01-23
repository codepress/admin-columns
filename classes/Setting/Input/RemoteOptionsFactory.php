<?php

namespace AC\Setting\Input;

class RemoteOptionsFactory
{

    public static function create_select(
        string $handler,
        array $data = [],
        array $default = null,
        string $placeholder = null,
        string $class = null
    ): Custom {
        $data = array_merge($data, [
            'ajax_handler' => $handler,
        ]);

        return new Custom('remote_options', $data, $default, $placeholder, $class);
    }
}