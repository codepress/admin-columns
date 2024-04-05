<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\ComponentCollection;
use AC\Setting\Type\Value;
use AC\Settings;

class ExifData extends Settings\Control implements AC\Setting\Children, AC\Setting\Formatter
{

    use AC\Setting\RecursiveFormatterTrait;

    public const NAME = 'exif_data';

    private $exif_data;

    private $settings;

    public function __construct(
        string $label,
        string $exif_data,
        ComponentCollection $settings,
        Specification $conditions = null
    ) {
        parent::__construct(
            OptionFactory::create_select(
                'exif_data',
                OptionCollection::from_array($this->get_exif_types()),
                $exif_data
            ),
            $label,
            null,
            $conditions
        );

        $this->exif_data = $exif_data;
        $this->settings = $settings;
    }

    public function is_parent(): bool
    {
        return false;
    }

    public function get_iterator(): ComponentCollection
    {
        return $this->settings;
        //
        //        // TODO move to factory
        //        $settings = new ComponentCollection();
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
        //            $conditions = Compare::equal($key);
        //
        //            $settings->add(
        //                new Settings\Column\BeforeAfter(
        //                    ...$defaults,
        //                    $conditions
        //                )
        //            );
        //
        //            $not[] = $conditions;
        //        }
        //
        //        $settings->add(
        //            new Settings\Column\BeforeAfter(
        //                null,
        //                null,
        //                new NotSpecification(
        //                    new OrSpecification($not)
        //                )
        //            )
        //        );
        //
        //        return $settings;
    }

    private function get_exif_types(): array
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

        return $exif_types;
    }

    public function format(Value $value): Value
    {
        $exif_datatype = $this->exif_data;
        $exif_value = ((array)$value->get_value())[$exif_datatype] ?? '';

        if (false !== $exif_value) {
            switch ($exif_value) {
                case 'created_timestamp' :
                    $exif_value = ac_format_date(
                        get_option('date_format') . ' ' . get_option('time_format'),
                        $exif_value
                    );
                    break;

                case 'keywords' :
                    $exif_value = $value->with_value(ac_helper()->array->implode_recursive(', ', $exif_value));
            }
        }

        return $this->get_recursive_formatter($this->exif_data)
                    ->format($value->with_value($exif_value));
    }

}