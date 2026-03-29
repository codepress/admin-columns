<?php

declare(strict_types=1);

namespace AC\Acf\FieldGroup;

use AC\TableScreen;
use AC\TableScreenFactory;
use AC\Type\TableId;

class TableScreenResolver
{

    private TableScreenFactory $table_screen_factory;

    public function __construct(TableScreenFactory $table_screen_factory)
    {
        $this->table_screen_factory = $table_screen_factory;
    }

    /**
     * @return TableScreen[]
     */
    public function resolve(array $group): array
    {
        if (empty($group['location']) || ! is_array($group['location'])) {
            return [];
        }

        $table_ids = [];

        foreach ($group['location'] as $or_group) {
            if (! is_array($or_group)) {
                continue;
            }

            foreach ($or_group as $rule) {
                $table_id = $this->map_rule_to_table_id(
                    $rule['param'] ?? '',
                    $rule['operator'] ?? '',
                    $rule['value'] ?? ''
                );

                if ($table_id !== null) {
                    $table_ids[(string)$table_id] = $table_id;
                }
            }
        }

        $table_screens = [];

        foreach ($table_ids as $table_id) {
            if ($this->table_screen_factory->can_create($table_id)) {
                $table_screens[] = $this->table_screen_factory->create($table_id);
            }
        }

        return $table_screens;
    }

    private function map_rule_to_table_id(string $param, string $operator, string $value): ?TableId
    {
        if ('==' !== $operator || '' === $value) {
            return null;
        }

        switch ($param) {
            case 'post_type':
                if ('all' === $value) {
                    return null;
                }

                return 'attachment' === $value
                    ? new TableId('wp-media')
                    : new TableId($value);

            case 'taxonomy':
                if ('all' === $value) {
                    return null;
                }

                return new TableId('wp-taxonomy_' . $value);

            case 'user_form':
            case 'user_role':
                return new TableId('wp-users');

            case 'comment':
                return new TableId('wp-comments');

            case 'attachment':
                return new TableId('wp-media');

            default:
                return null;
        }
    }

}
