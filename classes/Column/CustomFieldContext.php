<?php

declare(strict_types=1);

namespace AC\Column;

use AC\Setting\Config;
use AC\Type\TableScreenContext;

class CustomFieldContext extends Context
{

    private string $field_type;

    private string $meta_key;

    private TableScreenContext $table;

    public function __construct(
        Config $config,
        string $label,
        string $field_type,
        string $meta_key,
        TableScreenContext $table
    ) {
        parent::__construct($config, $label);

        $this->field_type = $field_type;
        $this->meta_key = $meta_key;
        $this->table = $table;
    }

    public function get_field_type(): string
    {
        return $this->field_type;
    }

    public function get_meta_key(): string
    {
        return $this->meta_key;
    }

    public function get_meta_type(): string
    {
        return (string)$this->table->get_meta_type();
    }

    public function get_post_type(): ?string
    {
        return $this->table->has_post_type()
            ? (string)$this->table->get_post_type()
            : null;
    }

    public function get_taxonomy(): ?string
    {
        return $this->table->has_taxonomy()
            ? (string)$this->table->get_taxonomy()
            : null;
    }

}