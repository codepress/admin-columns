<?php

declare(strict_types=1);

namespace AC;

use AC\Type\ListKey;
use AC\Type\ListScreenId;
use AC\Type\Uri;
use AC\Type\Url;
use DateTime;
use WP_User;

final class ListScreen
{

    protected $id;

    private $title;

    protected $table_screen;

    private $columns;

    private $preferences;

    private $updated;

    private $read_only = false;

    public function __construct(
        ListScreenId $id,
        string $title,
        TableScreen $table_screen,
        ColumnIterator $columns = null,
        array $preferences = [],
        DateTime $updated = null
    ) {
        if (null === $updated) {
            $updated = new DateTime();
        }
        if (null === $columns) {
            $columns = new ColumnCollection();
        }

        $this->id = $id;
        $this->title = $title;
        $this->table_screen = $table_screen;
        $this->columns = $columns;
        $this->preferences = $preferences;
        $this->updated = $updated;
    }

    public function get_id(): ListScreenId
    {
        return $this->id;
    }

    public function get_title(): string
    {
        return $this->title;
    }

    public function set_title(string $title): void
    {
        $this->title = $title;
    }

    public function get_columns(): ColumnIterator
    {
        // TODO use ColumnRepo and delay the creation of the actual Column objects.
        // TODO Because the file storage loads all ListScreen objects it will also populate these columns.
        return $this->columns;
    }

    public function set_columns(ColumnIterator $columns): void
    {
        $this->columns = $columns;
    }

    public function get_column(string $name): ?Column
    {
        return $this->columns->exists($name)
            ? $this->columns->get($name)
            : null;
    }

    public function get_preferences(): array
    {
        return $this->preferences;
    }

    public function get_updated(): DateTime
    {
        return $this->updated;
    }

    public function is_read_only(): bool
    {
        return $this->read_only;
    }

    public function set_read_only(bool $read_only): void
    {
        $this->read_only = $read_only;
    }

    public function get_heading_hookname(): string
    {
        return $this->table_screen->get_heading_hookname();
    }

    public function get_key(): ListKey
    {
        return $this->table_screen->get_key();
    }

    public function get_label(): ?string
    {
        return $this->table_screen->get_labels()->get_plural();
    }

    public function get_meta_type(): string
    {
        return $this->table_screen instanceof TableScreen\MetaType
            ? (string)$this->table_screen->get_meta_type()
            : '';
    }

    public function get_query_type(): string
    {
        return $this->table_screen->get_query_type();
    }

    public function get_screen_id(): string
    {
        return $this->table_screen->get_screen_id();
    }

    public function get_post_type(): ?string
    {
        return $this->table_screen instanceof PostType
            ? $this->table_screen->get_post_type()
            : null;
    }

    public function get_table_screen(): TableScreen
    {
        return $this->table_screen;
    }

    public function get_table_url(): Uri
    {
        return $this->table_screen->get_url()
                                  ->with_arg('layout', (string)$this->id);
    }

    public function get_editor_url(): Uri
    {
        return new Url\EditorColumns($this->get_key(), $this->id);
    }

    public function is_user_allowed(WP_User $user): bool
    {
        return user_can($user, Capabilities::MANAGE) || $this->is_user_assigned($user);
    }

    public function is_user_assigned(WP_User $user): bool
    {
        $user_ids = $this->get_preference('users');
        $roles = $this->get_preference('roles');

        $user_ids = is_array($user_ids)
            ? array_filter(array_map('intval', $user_ids))
            : [];

        $roles = is_array($roles)
            ? array_filter(array_map('strval', $roles))
            : [];

        if ( ! $user_ids && ! $roles) {
            return true;
        }

        foreach ($roles as $role) {
            if ($user->has_cap($role)) {
                return true;
            }
        }

        return in_array($user->ID, $user_ids, true);
    }

    public function set_preferences(array $preferences): void
    {
        $this->preferences = $preferences;
    }

    public function get_preference(string $key)
    {
        return $this->preferences[$key] ?? null;
    }

    public function set_preference(string $key, $value): void
    {
        $this->preferences[$key] = $value;
    }

    /**
     * @deprecated NEWVERSION
     */
    public function get_layout_id(): ?string
    {
        return (string)$this->id;
    }

    /**
     * @deprecated NEWVERSION
     */
    public function get_column_by_name(string $name): ?Column
    {
        _deprecated_function(__METHOD__, 'NEWVERSION', 'AC\ListScreen::get_column()');

        return $this->get_column($name);
    }

    /**
     * @deprecated NEWVERSION
     */
    public function get_screen_link(): string
    {
        _deprecated_function(__METHOD__, '4.6.5', 'AC\ListScreen::get_table_url()');

        return (string)$this->get_table_url();
    }

    /**
     * @deprecated NEWVERSION
     */
    public function get_edit_link(): string
    {
        _deprecated_function(__METHOD__, '4.6.5', 'AC\ListScreen::get_editor_url()');

        return (string)$this->get_editor_url();
    }

    /**
     * @deprecated NEWVERSION
     */
    protected function set_meta_type(string $meta_type): void
    {
        _deprecated_function(__METHOD__, 'NEWVERSION');

        $this->meta_type = $meta_type;
    }

    /**
     * @deprecated NEWVERSION
     */
    public function deregister_column(string $column_name): void
    {
        _deprecated_function(__METHOD__, 'NEWVERSION');

        unset($this->columns[$column_name]);
    }

    /**
     * @deprecated NEWVERSION
     */
    public function set_layout_id(string $layout_id): void
    {
        _deprecated_function(__METHOD__, 'NEWVERSION', 'AC\ListScreen::set_id()');

        if (ListScreenId::is_valid_id($layout_id)) {
            $this->id = new ListScreenId($layout_id);
        }
    }

    /**
     * @deprecated NEWVERSION
     */
    protected function set_label(): void
    {
        _deprecated_function(__METHOD__, 'NEWVERSION');
    }

    /**
     * @deprecated NEWVERSION
     */
    protected function register_column_types_from_list(array $list): void
    {
        _deprecated_function(__METHOD__, 'NEWVERSION', 'AC\TableScreen::set_column_type()');
    }

    /**
     * @deprecated NEWVERSION
     */
    public function deregister_column_type(): void
    {
        _deprecated_function(__METHOD__, 'NEWVERSION');
    }

    /**
     * @deprecated NEWVERSION
     */
    public function register_column_type(Column $column): void
    {
        _deprecated_function(__METHOD__, 'NEWVERSION', 'AC\TableScreen::set_column_type()');
    }

    /**
     * @deprecated NEWVERSION
     */
    public function get_original_columns(): array
    {
        _deprecated_function(__METHOD__, 'NEWVERSION');

        return [];
    }

    /**
     * @deprecated NEWVERSION
     */
    public function get_group(): string
    {
        _deprecated_function(__METHOD__, 'NEWVERSION');

        return '';
    }

    /**
     * @deprecated NEWVERSION
     */

    public function has_id(): bool
    {
        _deprecated_function(__METHOD__, 'NEWVERSION');

        return true;
    }

    /**
     * @deprecated NEWVERSION
     */
    public function get_storage_key(): string
    {
        _deprecated_function(__METHOD__, 'NEWVERSION');

        return $this->get_key() . $this->id;
    }

    /**
     * @deprecated NEWVERSION
     */
    public function get_settings(): array
    {
        _deprecated_function(__METHOD__, 'NEWVERSION');

        return [];
    }

}