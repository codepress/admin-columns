<?php

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting;
use AC\Setting\OptionCollection;
use AC\Settings;

class PostStatus extends Settings\Column
{

    // TODO remove
    public const NAME = 'post_status';

    public function __construct(Specification $conditions = null)
    {
        $input = Setting\Input\Option\Multiple::create_select(
            $this->create_options(),
            ['publish', 'private']
        );

        parent::__construct(
            'post_status',
            __('Post Status', 'codepress-admin-columns'),
            null,
            $input,
            $conditions
        );
    }

    private function create_options(): OptionCollection
    {
        $options = [];

        // TODO test
        foreach (get_post_stati(['exclude_from_search' => false]) as $name) {
            $options[$name] = $this->get_post_status_label((string)$name);
        }

        return OptionCollection::from_array($options);
    }

    private function get_post_status_label(string $key): string
    {
        $status = get_post_status_object($key);

        return $status->label ?? $key;
    }

}