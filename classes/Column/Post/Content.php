<?php

namespace AC\Column\Post;

use AC\Column;
use AC\Column\Value;
use AC\Setting\ComponentCollection;
use AC\Setting\SettingCollection;
use AC\Settings;

// TODO Tobias this is a `Renderable` POC
class Content extends Column implements Value
{

    public function __construct()
    {
        parent::__construct(
            'column-content',
            __('Content', 'codepress-admin-columns'),
            new ComponentCollection([
                new Settings\Column\StringLimit(),
                new Settings\Column\BeforeAfter(),
            ])
        );
    }

    //    public function renderable(Config $options): Renderable
    //    {
    //        return new Column\Post\Renderable\Content(
    //            new Renderable\ValueFormatter($this->get_settings())
    //        );
    //    }

    // TODO remove
    //    public function register_settings()
    //    {
    //        $this->add_setting(new Settings\Column\StringLimit());
    //        $this->add_setting(new Settings\Column\BeforeAfter());
    //    }

}