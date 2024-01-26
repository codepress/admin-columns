<?php

namespace AC\Column\Media;

use AC\Column;
use AC\Settings;

class Dimensions extends Column
{

    public function __construct()
    {
        $this->set_type('column-dimensions')
             ->set_group('media-image')
             ->set_label(__('Dimensions', 'codepress-admin-columns'));
    }

    public function get_value($id)
    {
        $meta = get_post_meta($id, '_wp_attachment_metadata', true);

        if (empty($meta['width']) || empty($meta['height'])) {
            return $this->get_empty_char();
        }

        $value = $meta['width'] . '&nbsp;&times;&nbsp;' . $meta['height'];

        $tooltip = sprintf(__('Width : %s px', 'codepress-admin-columns'), $meta['width']) . "<br/>\n" . sprintf(
                __('Height : %s px', 'codepress-admin-columns'),
                $meta['height']
            );

        return ac_helper()->html->tooltip($this->get_formatted_value($value, $id), $tooltip);
    }

    public function register_settings()
    {
        $this->add_setting(new Settings\Column\BeforeAfter());
    }

}