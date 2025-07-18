<?php

declare(strict_types=1);

namespace AC\Storage;

use LogicException;

abstract class Table
{

    public function exists(): bool
    {
        global $wpdb;

        $query = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($this->get_name()));

        return $wpdb->get_var($query) === $this->get_name();
    }

    public function create(): bool
    {
        global $wpdb;

        if ($this->exists()) {
            throw new LogicException(sprintf('Table %s does already exist', $this->get_name()));
        }

        return $wpdb->query($this->get_schema()) === true;
    }

    /**
     * Return full name including WordPress database prefix
     */
    abstract public function get_name(): string;

    abstract public function get_schema(): string;

}