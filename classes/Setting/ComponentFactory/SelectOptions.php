<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Setting\Children;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Control\Input;

final class SelectOptions extends BaseComponentFactory implements InputNameAware
{

    private IsMultiple $is_multiple;

    public function __construct(IsMultiple $is_multiple)
    {
        $this->is_multiple = $is_multiple;
    }

    public function get_name(): string
    {
        return 'select_options';
    }

    protected function get_label(Config $config): ?string
    {
        return __('Select Options', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): Input
    {
        return new Input\Custom($this->get_name(), $this->get_name(), [], $config->get($this->get_name(), ''));
    }

    protected function get_children(Config $config): ?Children
    {
        return new Children(new ComponentCollection([
            $this->is_multiple->create($config),
        ]));
    }

}