<?php

declare(strict_types=1);

namespace AC\Helper\Select\Generic;

use AC\Helper\Select;
use AC\Helper\Select\OptionGroup;

class Groups extends Select\Options
{

    public function __construct(Select\Options $options, GroupFormatter $formatter)
    {
        parent::__construct($this->create_groups($options, $formatter));
    }

    private function create_groups(Select\Options $options, GroupFormatter $formatter): array
    {
        $groups = [];

        foreach ($options as $option) {
            $groups[$formatter->format((string)$option->get_value())][] = $option;
        }

        $option_groups = [];

        foreach ($this->sort($groups) as $label => $_options) {
            $option_groups[] = new OptionGroup($label, $_options);
        }

        return $option_groups;
    }

    protected function sort(array $groups): array
    {
        // sort natural by key
        uksort($groups, 'strnatcmp');

        return $groups;
    }

}