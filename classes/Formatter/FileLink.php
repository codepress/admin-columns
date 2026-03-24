<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Helper;
use AC\Type\Value;

class FileLink implements Formatter
{

    private string $link_to;

    public function __construct(string $link_to = '')
    {
        $this->link_to = $link_to;
    }

    public function format(Value $value): Value
    {
        $attachment_id = $value->get_value();

        if ( ! is_numeric($attachment_id)) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        $attachment = get_attached_file($attachment_id);

        if ( ! $attachment) {
            return $value->with_value('<em>' . __('Invalid attachment', 'codepress-admin-columns') . '</em>');
        }

        $attachment_id = (int)$attachment_id;
        $label = esc_html(basename($attachment));

        if ($this->link_to === 'download') {
            $url = wp_get_attachment_url($attachment_id) ?: '';

            return $value->with_value(
                Helper\Html::create()->link($url, $label, ['download' => ''])
            );
        }

        if ($this->link_to === 'edit') {
            $edit_url = get_edit_post_link($attachment_id) ?: wp_get_attachment_url($attachment_id) ?: '';

            return $value->with_value(
                Helper\Html::create()->link($edit_url, $label)
            );
        }

        return $value->with_value(
            Helper\Html::create()->link(
                wp_get_attachment_url($attachment_id) ?: '',
                $label,
                ['target' => '_blank']
            )
        );
    }

}
