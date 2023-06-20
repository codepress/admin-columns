<?php

declare(strict_types=1);

namespace AC\Storage;

abstract class Table
{

    public function get_timestamp_format(): string
    {
        return 'Y-m-d H:i:s';
    }

    public function exists(): bool
    {
        global $wpdb;

        $query = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($this->get_name()));

        return $wpdb->get_var($query) === $this->get_name();
    }

    public function delta(): void
    {
        dbDelta($this->get_schema());
    }

    /**
     * Return full name including WordPress database prefix
     */
    abstract public function get_name(): string;

    abstract public function get_schema(): string;

}