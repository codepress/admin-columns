<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Setting\Children;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Control\Input;

final class SelectOptions extends BaseComponentFactory
{

    private const NAME = 'select_options';

    private IsMultiple $is_multiple;

    public function __construct(IsMultiple $is_multiple)
    {
        $this->is_multiple = $is_multiple;
    }

    protected function get_label(Config $config): ?string
    {
        return __('Select Options', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): Input
    {
        return new Input\Custom('select_options', self::NAME, [], $config->get(self::NAME, ''));
    }

    protected function get_children(Config $config): ?Children
    {
        return new Children(new ComponentCollection([
            $this->is_multiple->create($config),
        ]));
    }

}