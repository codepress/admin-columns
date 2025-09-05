<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Setting\Children;
use AC\Setting\Component;
use AC\Setting\ComponentBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;

final class ExifData implements ComponentFactory
{

    private const NAME = 'exif_data';

    private $before_after;

    public function __construct(BeforeAfter $before_after)
    {
        $this->before_after = $before_after;
    }

    public function create(Config $config, ?Specification $conditions = null): Component
    {
        $value = (string)$config->get(self::NAME);

        $builder = (new ComponentBuilder())
            ->set_label(__('Image Meta (EXIF)', 'codepress-admin-columns'))
            ->set_input(
                OptionFactory::create_select(
                    self::NAME,
                    $this->get_exif_types(),
                    $value ?: 'aperture'
                )
            )
            ->set_children(
                new Children(new ComponentCollection([
                    $this->before_after->create($config),
                ]))
            );

        if ($conditions) {
            $builder->set_conditions($conditions);
        }

        return $builder->build();
    }

    private function get_exif_types(): OptionCollection
    {
        $exif_types = [
            'aperture'          => __('Aperture', 'codepress-admin-columns'),
            'credit'            => __('Credit', 'codepress-admin-columns'),
            'camera'            => __('Camera', 'codepress-admin-columns'),
            'caption'           => __('Caption', 'codepress-admin-columns'),
            'created_timestamp' => __('Timestamp', 'codepress-admin-columns'),
            'copyright'         => __('Copyright', 'codepress-admin-columns'),
            'focal_length'      => __('Focal Length', 'codepress-admin-columns'),
            'iso'               => __('ISO', 'codepress-admin-columns'),
            'shutter_speed'     => __('Shutter Speed', 'codepress-admin-columns'),
            'title'             => __('Title', 'codepress-admin-columns'),
            'orientation'       => __('Orientation', 'codepress-admin-columns'),
            'keywords'          => __('Keywords', 'codepress-admin-columns'),
        ];

        natcasesort($exif_types);

        return OptionCollection::from_array($exif_types);
    }

}