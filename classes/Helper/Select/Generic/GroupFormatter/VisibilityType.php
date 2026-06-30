<?php

declare(strict_types=1);

namespace AC\Helper\Select\Generic\GroupFormatter;

use AC\Helper\Select\Generic\GroupFormatter;

class VisibilityType implements GroupFormatter
{
    private string $label;

    public function __construct(?string $label = null)
    {
        $this->label = $label ?? __('Default', 'codepress-admin-columns');
    }

    public function format(string $value): string
    {
        return 0 === strpos($value, '_')
            ? (string)__('Hidden', 'codepress-admin-columns')
            : $this->label;
    }

}
