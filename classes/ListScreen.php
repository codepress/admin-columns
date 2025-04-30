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

    public function __construct(string $key, string $screen_id)
    {
        $this->key = $key;
        $this->screen_id = $screen_id;
    }

    public function get_post_type(): string
    {
        return $this->post_type;
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

    abstract protected function register_column_types(): void;

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

    public function get_heading_hookname(): string
    {
        return sprintf('manage_%s_columns', $this->get_screen_id());
    }

    public function get_key(): string
    {
        return $this->key;
    }

    public function get_label(): ?string
    {
        return $this->label;
    }

    public function get_singular_label(): ?string
    {
        return $this->singular_label ?: $this->label;
    }

    public function get_meta_type(): string
    {
        return $this->meta_type ?: '';
    }

    public function get_query_type(): string
    {
        return $this->query_type ?: $this->get_meta_type();
    }

    public function get_screen_id(): string
    {
        return $this->screen_id;
    }

    public function get_group(): string
    {
        return $this->group;
    }

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
        return '#the-list';
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

    abstract public function get_table_url(): Uri;

    public function get_editor_url(): Uri
    {
        return new Url\EditorColumns($this->key, $this->id);
    }

    /**
     * @return Column[]
     */
    public function get_columns(): array
    {
        if (null === $this->columns) {
            $this->set_columns();
        }

        return $this->columns;
    }

    private function set_columns(): void
    {
        foreach ($this->get_settings() as $name => $data) {
            $data['name'] = $name;
            $column = $this->create_column($data);

            if ($column) {
                $this->columns[$column->get_name()] = $column;
            }
        }

        // Nothing stored. Use WP default columns.
        if (null === $this->columns) {
            foreach ($this->get_original_columns() as $type => $label) {
                $column = $this->create_column(['type' => $type, 'original' => true]);

                if ( ! $column) {
                    continue;
                }

                $this->columns[$column->get_name()] = $column;
            }
        }

        if (null === $this->columns) {
            $this->columns = [];
        }
    }

    /**
     * @return Column[]
     */
    public function get_column_types(): array
    {
        if (null === $this->column_types) {
            $this->set_column_types();
        }

        return $this->column_types;
    }

    private function set_column_types(): void
    {
        $this->column_types = [];

        foreach ($this->get_original_columns() as $type => $label) {
            // Ignore the mandatory checkbox column
            if ('cb' === $type) {
                continue;
            }

            $column = new Column();
            $column->set_type($type)
                   ->set_original(true);

            $this->register_column_type($column);
        }

        // Load Custom columns
        $this->register_column_types();

        /**
         * Register column types
         *
         * @param ListScreen $this
         */
        do_action('ac/column_types', $this);
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

    public function get_column_by_type(string $type): ?Column
    {
        $column_types = $this->get_column_types();

        return $column_types[$type] ?? null;
    }

    private function get_class_by_type(string $type): ?string
    {
        $column = $this->get_column_by_type($type);

        return $column
            ? get_class($column)
            : null;
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

    public function get_original_label(string $type): ?string
    {
        $columns = $this->get_original_columns();

        $label = $columns[$type] ?? null;

        return is_string($label)
            ? $label
            : null;
    }

    public function get_original_columns(): array
    {
        return (new DefaultColumnsRepository())->get($this->get_key());
    }

    private function is_original_column(string $type): bool
    {
        $column = $this->get_column_by_type($type);

        return $column && $column->is_original();
    }

    public function create_column(array $settings): ?Column
    {
        if ( ! isset($settings['type'])) {
            return null;
        }

        $class = $this->get_class_by_type((string)$settings['type']);

        if ( ! $class) {
            return null;
        }

        /**
         * @var Column $column
         */
        $column = new $class();
        $column->set_list_screen($this)
               ->set_type($settings['type']);

        if (isset($settings['name'])) {
            $column->set_name($settings['name']);
        }

        // Mark as original
        if ($this->is_original_column($settings['type'])) {
            $column->set_original(true);
            $column->set_name($settings['type']);
        }

        $column->set_options($settings);

        do_action('ac/list_screen/column_created', $column, $this);

        return $column;
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
        _deprecated_function(__METHOD__, '6.4');

        $this->meta_type = $meta_type;
    }

    public function deregister_column(string $column_name): void
    {
        _deprecated_function(__METHOD__, '6.4');

        unset($this->columns[$column_name]);
    }

    public function set_layout_id(string $layout_id): void
    {
        _deprecated_function(__METHOD__, '6.4', 'AC\ListScreen::set_id()');

        if (ListScreenId::is_valid_id($layout_id)) {
            $this->id = new ListScreenId($layout_id);
        }
    }

    protected function set_label(string $label): void
    {
        _deprecated_function(__METHOD__, '6.4');

        $this->label = $label;
    }

}