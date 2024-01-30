<?php

namespace AC\Column\Post;

use AC\Column;
use AC\Column\Renderable;
use AC\Setting\SettingCollection;

class Excerpt extends Column implements Column\Value
{

    public function __construct(SettingCollection $settings)
    {
        parent::__construct(
            'column-excerpt',
            __('Excerpt', 'codepress-admin-columns'),
            $settings
        );
    }

    public function renderable(): Renderable
    {
        return new Column\Post\Renderable\Excerpt(
            new Renderable\ValueFormatter($this->settings)
        );
    }

    //    public function get_value($post_id)
    //    {
    //        // TODO move to formatter
    //        $value = parent::get_value($post_id);
    //
    //        if ($value && ! has_excerpt($post_id) && $value !== $this->get_empty_char()) {
    //            $value = ac_helper()->html->tooltip(
    //                    ac_helper()->icon->dashicon(['icon' => 'media-text', 'class' => 'gray']),
    //                    __('Excerpt is missing.') . ' ' . __('Current excerpt is generated from the content.')
    //                ) . ' ' . $value;
    //        }
    //
    //        return $value;
    //    }
}