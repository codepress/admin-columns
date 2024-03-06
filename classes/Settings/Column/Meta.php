<?php

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Form\Element\Select;
use AC\MetaType;
use AC\Setting\Control\Input;
use AC\Settings\Control;
use AC\View;

// TODO
abstract class Meta extends Control
{

    /**
     * @var string
     */
    private $field;

    abstract protected function get_meta_keys(): array;

    public function __construct(
        Input $input,
        string $label,
        string $description = null,
        Specification $conditions = null
    )
    {
        parent::__construct(
            , $label, $description, $conditions);
    }

    protected function define_options()
    {
        return ['field'];
    }

    /**
     * @return Select
     */
    protected function get_setting_field()
    {
        $setting = $this
            ->create_element('select', 'field')
            ->set_options($this->group_keys($this->get_cached_keys()))
            ->set_no_result(__('No fields available.', 'codepress-admin-columns'));

        return $setting;
    }

    /**
     * @return array|false
     */
    protected function get_cached_keys()
    {
        $keys = $this->get_cache();

        if ( ! $keys) {
            $keys = $this->get_meta_keys();

            $this->set_cache($keys);
        }

        return $keys;
    }

    protected function get_cache_key(): string
    {
        return $this->column->get_type() . $this->column->get_meta_type() . $this->column->get_post_type();
    }

    protected function get_meta_type(): string
    {
        return $this->column->get_meta_type();
    }

    protected function get_cache_group()
    {
        return 'ac_settings_meta';
    }



    /**
     * @return string
     */
    public function get_field()
    {
        return $this->field;
    }

    /**
     * @param string $field
     *
     * @return bool
     */
    public function set_field($field)
    {
        $this->field = $field;

        return true;
    }

    private function get_cache()
    {
        return wp_cache_get($this->get_cache_key(), $this->get_cache_group());
    }

    private function set_cache(array $data): void
    {
        wp_cache_add($this->get_cache_key(), $data, $this->get_cache_group(), 15);
    }

    protected function get_meta_groups(): array
    {
        global $wpdb;

        $groups = [
            ''  => __('Public', 'codepress-admin-columns'),
            '_' => __('Hidden', 'codepress-admin-columns'),
        ];

        // User only
        if (MetaType::USER === $this->get_meta_type()) {
            if (is_multisite()) {
                foreach (get_sites() as $site) {
                    $label = __('Network Site:', 'codepress-admin-columns') . ' ' . ac_helper(
                        )->network->get_site_option($site->blog_id, 'blogname');

                    if (get_current_blog_id() == $site->blog_id) {
                        $label .= ' (' . __('current', 'codepress-admin-columns') . ')';
                    }

                    $groups[$wpdb->get_blog_prefix($site->blog_id)] = $label;
                }
            } else {
                $groups[$wpdb->get_blog_prefix()] = __('Site Options', 'codepress-admin-columns');
            }
        }

        return $groups;
    }

    /**
     * @param array $keys
     *
     * @return array
     */
    private function group_keys($keys)
    {
        if ( ! $keys) {
            return [];
        }

        $grouped = [];

        $groups = $this->get_meta_groups();

        // groups are ordered desc because the prefixes without a blog id ( e.g. wp_ ) should be matched last.
        krsort($groups);

        foreach ($groups as $prefix => $title) {
            $options = [];

            foreach ($keys as $k => $key) {
                // Match prefix with meta key
                if ($prefix && 0 === strpos($key, $prefix)) {
                    $options[$key] = $key;

                    unset($keys[$k]);
                }
            }

            if ($options) {
                $grouped[$prefix] = [
                    'title'   => $title,
                    'options' => $options,
                ];
            }
        }

        ksort($grouped);

        // Default group
        if ($keys) {
            $default = [
                'title'   => $groups[''],
                'options' => array_combine($keys, $keys),
            ];

            array_unshift($grouped, $default);
        }

        // Place the hidden group at the end
        if (isset($grouped['_'])) {
            $grouped[] = $grouped['_'];

            unset($grouped['_']);
        }

        // Remove groups when there is only one group
        if (1 === count($grouped)) {
            $grouped = array_pop($grouped);

            $grouped = $grouped['options'];
        }

        return $grouped;
    }

}
