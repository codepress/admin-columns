<?php

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting;
use AC\Settings;

class PostStatus extends Settings\Control
{

    public function __construct(array $post_status = null, Specification $conditions = null)
    {
        $input = Setting\Control\Input\OptionFactory::create_select(
            'post_status',
            $this->create_options(),
            $post_status ?: ['publish', 'private'],
            null,
            true
        );

        parent::__construct(
            $input,
            __('Post Status', 'codepress-admin-columns'),
            null,
            $conditions
        );
    }

    private function create_options(): Setting\Control\OptionCollection
    {
        $options = [];

        // TODO test
        foreach (get_post_stati(['exclude_from_search' => false]) as $name) {
            $options[$name] = $this->get_post_status_label((string)$name);
        }

        return Setting\Control\OptionCollection::from_array($options);
    }

    private function get_post_status_label(string $key): string
    {
        $status = get_post_status_object($key);

        return $status->label ?? $key;
    }

}