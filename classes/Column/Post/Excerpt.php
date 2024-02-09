<?php

namespace AC\Column\Post;

use AC\Column;
use AC\Column\Renderable;

// TODO obsolete
class Excerpt extends Column implements Column\Value
{

    private $renderable;

    public function __construct(
        string $type,
        string $label,
        Renderable $renderable,
        SettingCollection $settings = null,
        string $group = null
    ) {
        parent::__construct($type, $label, $settings, $group);

        $this->renderable = $renderable;
    }

    public function renderable(): Renderable
    {
        return $this->renderable;
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