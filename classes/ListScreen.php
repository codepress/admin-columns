<?php

declare(strict_types=1);

namespace AC;

use AC\Type\ListScreenId;
use AC\Type\Uri;
use AC\Type\Url;
use DateTime;
use LogicException;
use WP_User;

abstract class ListScreen implements PostType
{

    /**
     * @var string
     */
    protected $key;

    /**
     * The unique ID of the screen.
     * @see   \WP_Screen::id
     * @var string
     */
    protected $screen_id;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $singular_label;

    /**
     * Meta type of list screen; post, user, comment. Mostly used for fetching metadata.
     * @var string
     */
    protected $meta_type;

    /**
     * Group slug. Used for menu.
     * @var string
     */
    protected $group = '';

    /**
     * @var Column[]
     */
    private $columns;

    /**
     * @var Column[]
     */
    private $column_types;

    /**
     * @var ListScreenId
     */
    protected $id;

    /**
     * @var array Column settings data
     */
    private $settings = [];

    /**
     * @var array ListScreen settings data
     */
    private $preferences = [];

    /**
     * @var bool True when column settings can not be overwritten
     */
    private $read_only = false;

    /**
     * @var string
     */
    private $title;

    /**
     * @var DateTime
     */
    private $updated;

    /**
     * @var string
     */
    protected $query_type;

    protected $post_type = '';

    protected $table_screen;

    // TODO construct should only contain settings (from DB), nothing else.
    public function __construct(TableScreen $table_screen)
    {
        $this->table_screen = $table_screen;
    }

    public function get_post_type(): string
    {
        return $this->table_screen instanceof PostType
            ? $this->table_screen->get_post_type()
            : '';
    }

    public function has_id(): bool
    {
        return null !== $this->id;
    }

    public function get_id(): ListScreenId
    {
        if ( ! $this->has_id()) {
            throw new LogicException('ListScreen has no identity.');
        }

        return $this->id;
    }

    // TODO remove from inheritors
    //    abstract protected function register_column_types(): void;

    /**
     * Register column types from a list with (fully qualified) class names
     *
     * @param string[] $list
     */
    protected function register_column_types_from_list(array $list): void
    {
        foreach ($list as $column) {
            $this->register_column_type(new $column());
        }
    }

    // TODO should we even pass the TableScreen object to the ListScreen. It seems to be here for convenience only.
    public function get_table_screen(): TableScreen
    {
        return $this->table_screen;
    }

    public function get_heading_hookname(): string
    {
        return $this->table_screen->get_heading_hookname();
    }

    // TODO return ListKey object, not a string
    public function get_key(): string
    {
        return (string)$this->table_screen->get_key();
    }

    public function get_label(): ?string
    {
        return $this->table_screen->get_labels()->get_plural();
    }

    public function get_singular_label(): ?string
    {
        return $this->table_screen->get_labels()->get_singular() ?: $this->get_label();
    }

    public function get_meta_type(): string
    {
        return (string)$this->table_screen->get_meta_type();
    }

    public function get_query_type(): string
    {
        return $this->table_screen->get_query_type() ?: $this->get_meta_type();
    }

    public function get_screen_id(): string
    {
        return $this->table_screen->get_screen_id();
    }

    public function get_group(): string
    {
        return $this->table_screen->get_group();
    }

    // TODO remove
    public function set_group(string $group): void
    {
        $this->group = $group;
    }

    public function get_title(): string
    {
        return $this->title;
    }

    public function set_title(string $title): void
    {
        $this->title = $title;
    }

    public function get_storage_key(): string
    {
        return $this->key . $this->id;
    }

    public function set_id(ListScreenId $id): void
    {
        $this->id = $id;
    }

    public function get_table_attr_id(): string
    {
        return $this->table_screen->get_attr_id();
    }

    public function is_read_only(): bool
    {
        return $this->read_only;
    }

    public function set_read_only(bool $read_only): void
    {
        $this->read_only = $read_only;
    }

    public function set_updated(DateTime $updated): void
    {
        $this->updated = $updated;
    }

    public function get_updated(): DateTime
    {
        return $this->updated ?: new DateTime();
    }

    public function get_table_url(): Uri
    {
        return $this->table_screen->get_url()->with_arg('layout', (string)$this->id);
    }

    public function get_editor_url(): Uri
    {
        return new Url\EditorColumns($this->get_key(), $this->id);
    }

    /**
     * @return Column[]
     */
    public function get_columns(): array
    {
        return $this->columns;
    }

    public function set_columns(array $columns): void
    {
        $this->columns = $columns;
    }

    public function get_column_by_name($name): ?Column
    {
        foreach ($this->get_columns() as $column) {
            // Do not do a strict comparison. All column names are stored as strings, even integers.
            if ($column->get_name() == $name) {
                return $column;
            }
        }

        return null;
    }

    public function deregister_column_type(string $type): void
    {
        unset($this->column_types[$type]);
    }

    public function register_column_type(Column $column): void
    {
        if ( ! $column->get_type()) {
            return;
        }

        $column->set_list_screen($this);

        if ( ! $column->is_valid()) {
            return;
        }

        // Skip the custom registered columns which are marked 'original' but are not available for this list screen
        if ($column->is_original() && ! array_key_exists($column->get_type(), $this->get_original_columns())) {
            return;
        }

        $this->column_types[$column->get_type()] = $column;
    }

    public function get_original_columns(): array
    {
        return (new DefaultColumnsRepository())->get($this->get_key());
    }

    public function set_settings(array $settings): void
    {
        $this->settings = $settings;
    }

    public function get_settings(): array
    {
        return $this->settings;
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
        $this->preferences = apply_filters('ac/list_screen/preferences', $preferences, $this);
    }

    public function set_preference(string $key, $value): void
    {
        $this->preferences[$key] = $value;
    }

    public function get_preferences(): array
    {
        return $this->preferences;
    }

    public function get_preference(string $key)
    {
        return $this->preferences[$key] ?? null;
    }

    /**
     * @deprecated NEWVERSION
     */
    public function get_layout_id(): ?string
    {
        return (string)$this->id;
    }

    public function get_screen_link(): string
    {
        _deprecated_function(__METHOD__, '4.6.5', 'AC\ListScreen::get_table_url()');

        return (string)$this->get_table_url();
    }

    public function get_edit_link(): string
    {
        _deprecated_function(__METHOD__, '4.6.5', 'AC\ListScreen::get_editor_url()');

        return (string)$this->get_editor_url();
    }

    protected function set_meta_type(string $meta_type): void
    {
        _deprecated_function(__METHOD__, 'NEWVERSION');

        $this->meta_type = $meta_type;
    }

    public function deregister_column(string $column_name): void
    {
        _deprecated_function(__METHOD__, 'NEWVERSION');

        unset($this->columns[$column_name]);
    }

    public function set_layout_id(string $layout_id): void
    {
        _deprecated_function(__METHOD__, 'NEWVERSION', 'AC\ListScreen::set_id()');

        if (ListScreenId::is_valid_id($layout_id)) {
            $this->id = new ListScreenId($layout_id);
        }
    }

    protected function set_label(string $label): void
    {
        _deprecated_function(__METHOD__, 'NEWVERSION');

        $this->label = $label;
    }

}