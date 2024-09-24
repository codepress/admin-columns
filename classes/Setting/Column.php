<?php

declare(strict_types=1);

namespace AC\Setting;

class Column
{

    protected Config $config;

    public function __construct( Config $config )
    {
        $this->config = $config;
    }

    public function getType() : string
    {
        return $this->get( 'type' );
    }

    public function has( string $key ): bool    {
        return $this->config->has( $key );
    }

    public function get( string $key, $default = null )
    {
        return $this->config->get( $key, $default );
    }

}