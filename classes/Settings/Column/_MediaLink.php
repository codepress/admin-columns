<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;
use AC\Settings;

class MediaLink extends Settings\Control implements Formatter
{

    private $link_to;

    public function __construct(string $link_to, Specification $specification)
    {
        parent::__construct(
            OptionFactory::create_select(
                'media_link_to',
                OptionCollection::from_array([
                    ''         => __('None'),
                    'view'     => __('View', 'codepress-admin-columns'),
                    'download' => __('Download', 'codepress-admin-columns'),
                ]),
                $link_to
            ),
            __('Link To', 'codepress-admin-columns'),
            null,
            $specification
        );
        $this->link_to = $link_to;
    }

    public function format(Value $value): Value
    {
        switch ($this->link_to) {
            case 'view' :
            case 'download' :
                $link = wp_get_attachment_url($value->get_id());

                if ($link) {
                    $attributes = [];
                    $attributes['download'] = '';

                    return $value->with_value(ac_helper()->html->link($link, $value->get_value(), $attributes));
                }

                return $value;
            default :
                return $value;
        }
    }
    // TODO
    //
    //    public function format($value, $original_value)
    //    {
    //        switch ($this->get_media_link_to()) {
    //            case 'view' :
    //            case 'download' :
    //                $link = wp_get_attachment_url((int)$original_value);
    //
    //                if ($link) {
    //                    $attributes = [];
    //
    //                    if ('download' === $this->get_media_link_to()) {
    //                        $attributes['download'] = '';
    //                    }
    //
    //                    return ac_helper()->html->link($link, $value, $attributes);
    //                }
    //
    //                return $value;
    //            default :
    //                return $value;
    //        }
    //    }
    //
    //    public function create_view()
    //    {
    //        $select = $this->create_element('select')->set_options($this->get_link_options());
    //
    //        $view = new View([
    //            'label'   => __('Link To', 'codepress-admin-columns'),
    //            'setting' => $select,
    //        ]);
    //
    //        return $view;
    //    }
    //
    //    protected function get_link_options()
    //    {
    //        return [
    //            ''         => __('None'),
    //            'view'     => __('View', 'codepress-admin-columns'),
    //            'download' => __('Download', 'codepress-admin-columns'),
    //        ];
    //    }
    //
    //    /**
    //     * @return string
    //     */
    //    public function get_media_link_to()
    //    {
    //        return $this->media_link_to;
    //    }
    //
    //    /**
    //     * @param string $media_link_to
    //     *
    //     * @return bool
    //     */
    //    public function set_media_link_to($media_link_to)
    //    {
    //        $this->media_link_to = $media_link_to;
    //
    //        return true;
    //    }

}