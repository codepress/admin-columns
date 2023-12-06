<?php

declare(strict_types=1);

namespace AC\Helper\Select\Generic\GroupFormatter;

use AC\Helper\Select\Generic\GroupFormatter;

class VisibilityType implements GroupFormatter
{

    private $label;

    public function __construct(string $label = null)
    {
        if (null === $label) {
            $label = __('Default', 'codepress-admin-columns');
        }

        $this->label = $label;
    }

    public function format(string $value): string
    {
        return 0 === strpos($value, '_')
            ? __('Hidden', 'codepress-admin-columns')
            : $this->label;
    }

}