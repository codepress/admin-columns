<?php

declare(strict_types=1);

namespace AC;

use AC\Setting\Formatter;
use AC\Setting\Recursive;
use AC\Setting\SettingCollection;
use AC\Settings\Setting;
use AC\Type\ColumnId;

class Column
{

    protected $type;

    protected $label;

    protected $settings;

    protected $group;

    private $renderable;

    public function __construct(
        string $type,
        string $label,
        Formatter $formatter,
        SettingCollection $settings = null,
        string $group = null
    ) {
        $this->type = $type;
        $this->label = $label;
        $this->renderable = $formatter;
        $this->settings = $settings ?? new SettingCollection();
        $this->group = $group ?? 'custom';
    }

    public function get_type(): string
    {
        return $this->type;
    }

    public function get_id(): ColumnId
    {
        return new ColumnId($this->get_name());
    }

    // TODO remove; refactor to get_id().
    public function get_name(): string
    {
        return (string)$this->get_setting('name')
                            ->get_input()
                            ->get_value();
    }

    // TODO remove
    public function get_custom_label(): string
    {
        return (string)$this->get_setting('label')
                            ->get_input()
                            ->get_value();
    }

    public function get_label(): string
    {
        return $this->label;
    }

    public function get_group(): string
    {
        return $this->group;
    }

    public function get_settings(): SettingCollection
    {
        return $this->settings;
        // TODO
        // do_action('ac/column/settings', $settings);
    }

    public function renderable(): Formatter
    {
        return $this->renderable;
    }

    public function get_setting(string $name, SettingCollection $settings = null): ?Settings\Setting
    {
        $settings = $settings ?: $this->settings;

        foreach ($settings as $setting) {
            if ($setting instanceof Recursive) {
                $found = $this->get_setting($name, $setting->get_children());

                if ($found) {
                    return $found;
                }
            }

            if ($setting instanceof Setting && $setting->get_name() === $name) {
                return $setting;
            }
        }

        return null;
    }

    // TODO remove
    public function is_original(): bool
    {
        return false;
    }

    // TODO remove
    //        public function get_name(): string
    //        {
    //            return $this->name;
    //        }
    //
    //    public function set_name(string $name): self
    //    {
    //        $this->name = $name;
    //

    //        return $this;

    //    }
    // TODO remove
    //    public function set_type(string $type): self
    //    {
    //        $this->type = $type;
    //
    //        return $this;

    //    }
    //    public function get_meta_type(): string
    //    {
    //        return $this->meta_type;
    //    }
    //
    //    public function set_meta_type(string $meta_type): self
    //    {
    //        $this->meta_type = $meta_type;
    //
    //        return $this;

    //    }

    //    public function set_label(string $label = null): self
    //    {
    //        $this->label = $label;
    //
    //        return $this;
    //    }

    //    public function set_group(string $group): self
    //    {
    //        $this->group = $group;
    //
    //        return $this;
    //    }

    //    public function get_post_type(): string
    //    {
    //        return $this->post_type;
    //    }
    //
    //    public function set_taxonomy(string $taxonomy): self
    //    {
    //        $this->taxonomy = $taxonomy;
    //
    //        return $this;
    //    }
    //
    //    public function get_taxonomy()
    //    {
    //        return $this->taxonomy;
    //    }
    //
    //    public function set_post_type(string $post_type): self
    //    {
    //        $this->post_type = $post_type;
    //
    //        return $this;
    //    }

    /**
     * Return true when a default column has been replaced by a custom column.
     * An original column will then use the original label and value.
     */
    //    public function is_original(): bool
    //    {
    //        return $this->original;
    //    }

    //    public function set_original(bool $boolean): self
    //    {
    //        $this->original = $boolean;
    //
    //        return $this;
    //    }

    //    public function add_setting(Settings\Column $setting): self
    //    {
    //        $this->settings[$setting->get_name()] = $setting;
    //
    //        return $this;
    // TODO David Check
    //$setting->set_values($this->options);

    // TODO David check
    //        foreach ((array)$setting->get_dependent_settings() as $dependent_setting) {
    //            $this->add_setting($dependent_setting);
    //        }
    //    }

    //    public function remove_setting($id)
    //    {
    //        if (isset($this->settings[$id])) {
    //            unset($this->settings[$id]);
    //        }
    //    }

    //    public function get_setting($name): ?Settings\Column
    //    {
    //        return $this->settings[$name] ?? null;
    //
    //        // TODO David, reimplement
    //        //        return null;
    //        //
    //        //        return $this->get_settings()->get($id);
    //    }

    // TODO remove?
    //    public function get_formatters(): array
    //    {
    //        if (null === $this->formatters) {
    //            $this->formatters = [];
    //
    //            foreach ($this->get_settings() as $setting) {
    //                if ($setting instanceof Settings\FormatValue || $setting instanceof Settings\FormatCollection) {
    //                    $this->formatters[] = $setting;
    //                }
    //            }
    //        }
    //
    //        return $this->formatters;
    //    }

    // TODO remove
    //    public function get_custom_label(): string
    //    {
    //        $label = $this->get_option('label') ?: $this->get_type();
    //        $label = (new LabelEncoder())->decode($label);
    //
    //        return (string)apply_filters('ac/headings/label', $label, $this);
    //    }

    //    public function get_settings()
    //    {
    //        $settings = $this->settings;
    //
    //        $settings->add(new Settings\Column\Type());
    //        $settings->add(new Settings\Column\Label());
    //        $settings->add(new Settings\Column\Width());
    //
    //        // TODO
    //        // do_action('ac/column/settings', $settings);
    //
    //        return $settings;
    //    }

    //    public function get_settings(): SettingCollection
    //    {
    //        if (null === $this->settings) {
    //            // TODO remove
    //            $this->add_setting(new Settings\Column\Type());
    //
    //            $this->add_setting(new Settings\Column\Label());
    //            $this->add_setting(new Settings\Column\Width());
    //
    //            $this->register_settings();
    //
    //            do_action('ac/column/settings', $this);
    //        }
    //
    //        return new SettingCollection($this->settings);
    //    }

    //    protected function register_settings()
    //    {
    //        // Overwrite in child class
    //    }

    //    public function get_option(string $key)
    //    {
    //        return $this->options[$key] ?? null;
    //    }

    //    public function set_options(array $options): void
    //    {
    //        $this->options = $options;
    //    }

    //    public function get_options(): array
    //    {
    //        return $this->options;
    //    }

    //    public function set_option(string $key, $value): void
    //    {
    //        $this->options[$key] = $value;
    //    }

    /**
     * Use this action to Enqueue CSS + JavaScript on the list table
     * This action is called in the admin_head action on the listings screen where your column values are displayed.
     */
    //    public function scripts(): void
    //    {
    //        // Overwrite in child class
    //    }

    // TODO
    //public function get_formatted_value($value, $original_value = null, $current = 0)
    //    public function get_formatted_value($value, int $id = null) : string
    //    {
    //        $formatters = $this->get_formatters();
    //        $available = count($formatters);
    //
    //        if (null === $original_value) {
    //            $original_value = $value;
    //        }
    //
    //        if ($available > $current) {
    //            $is_collection = $value instanceof Collection;
    //            $is_value_formatter = $formatters[$current] instanceof Settings\FormatValue;
    //
    //            if ($is_collection && $is_value_formatter) {
    //                foreach ($value as $k => $v) {
    //                    $value->put($k, $this->get_formatted_value($v, null, $current));
    //                }
    //
    //                while ($available > $current) {
    //                    if ($formatters[$current] instanceof Settings\FormatCollection) {
    //                        return $this->get_formatted_value($value, $original_value, $current);
    //                    }
    //
    //                    ++$current;
    //                }
    //            } elseif (($is_collection && ! $is_value_formatter) || $is_value_formatter) {
    //                $value = $formatters[$current]->format($value, $original_value);
    //
    //                return $this->get_formatted_value($value, $original_value, ++$current);
    //            }
    //        }
    //
    //        return $value;
    //    }

    // TODO David check if $id cannot be null
    // TODO David can tis method be protected/private, even just by comment if need be
    // TODO Tobias can this method can be removed entirely?
    //    public function get_formatted_value($value, $id = null): string
    //    {
    //        $factory = new ValueFormatterFactory();
    //
    //        return $factory->create($this)
    //                       ->format($value, $id);
    //    }

    /**
     * Get the raw, underlying value for the column
     * Not suitable for direct display, use get_value() for that
     */
    // TODO where is this used? Besides `get_value`. Sorting should no longer use this, is there a possibility we can remove this?
    //    public function get_raw_value($id)
    //    {
    //        return null;
    //    }

    /**
     * Display value
     */
    //    public function get_value($id)
    //    {
    //        return $this->get_formatted_value(
    //            $this->get_raw_value($id),
    //            (int)$id
    //        );

    // TODO remove?
    //        if ($value instanceof Collection) {
    //            $value = $value->filter()->implode($this->get_separator());
    //        }
    //
    //        if ( ! $this->is_original() && ac_helper()->string->is_empty($value)) {
    //            $value = $this->get_empty_char();
    //        }

    //        return $value;
    //    }

    // TODO move to Renderable
    public function get_separator(): string
    {
        switch ($this->get_option('separator')) {
            case 'comma' :
                return ', ';
            case 'newline' :
                return "<br/>";
            case 'none' :
                return '';
            case 'white_space' :
                return '&nbsp;';
            case 'horizontal_rule' :
                return '<hr>';
            default :
                return (new ApplyFilter\ColumnSeparator($this))->apply_filters(', ');
        }
    }

    // TODO move to Renderable
    public function get_empty_char(): string
    {
        return '&ndash;';
    }

    //    public function toArray(): array
    //    {
    //        return $this->options;
    //    }

}