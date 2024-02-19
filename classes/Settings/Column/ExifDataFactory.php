<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Settings\Component;
use AC\Settings\SettingFactory;

final class ExifDataFactory implements SettingFactory
{

    private $before_after_factory;

    public function __construct(BeforeAfterFactory $before_after_factory)
    {
        $this->before_after_factory = $before_after_factory;
    }

    private function build_settings(Config $config)
    {
        return new ComponentCollection([
            $this->before_after_factory->create($config),
        ]);

        // TODO format before after settings
        //
        //        $before_after = [
        //            'aperture'      => ['/f', ''],
        //            'focal_length'  => ['', 'mm'],
        //            'iso'           => ['ISO', ''],
        //            'shutter_speed' => ['', 's'],
        //        ];
        //
        //        $not = [];
        //
        //        foreach ($before_after as $key => $defaults) {
        //            $conditions = StringComparisonSpecification::equal($key);
        //
        //            $settings->add(
        //                $this->before_after_factory->create($config, $conditions)
        //            );
        //
        //            $not[] = $conditions;
        //        }
        //
        //        $settings->add(
        //            $this->before_after_factory->create($config, new NotSpecification(new OrSpecification($not)))
        //        );
        //
        //        return $settings;
    }

    // Todo Test column (not test data)
    public function create(Config $config, Specification $specification = null): Component
    {
        return new ExifData(
            __('Image Meta (EXIF)', 'codepress-admin-columns'),
            $config->get('exif_data') ?: 'aperture',
            $this->build_settings($config),
            $specification
        );
    }

}