<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Expression\NotSpecification;
use AC\Expression\OrSpecification;
use AC\Expression\Specification;
use AC\Expression\StringComparisonSpecification as Compare;
use AC\Setting\ArrayImmutable;
<<<<<<< HEAD
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
=======
use AC\Setting\OptionCollection;
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
use AC\Setting\SettingCollection;
use AC\Setting\Type\Value;
use AC\Settings;

class ExifData extends Settings\Column implements AC\Setting\Recursive, AC\Setting\Formatter
{

    use AC\Setting\RecursiveFormatterTrait {
        AC\Setting\RecursiveFormatterTrait::format as format_recursive;
    }

    public const NAME = 'exif_data';

    public function __construct(string $label, Specification $conditions = null)
    {
<<<<<<< HEAD
        $this->name = self::NAME;
        $this->label = $column->get_label();
        $this->input = OptionFactory::create_select(
            OptionCollection::from_array($this->get_exif_types()),
            'aperture'
        );

=======
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
        parent::__construct(
            'exif_data',
            $label,
            '',
            AC\Setting\Input\Option\Single::create_select(
                OptionCollection::from_array($this->get_exif_types()),
                'aperture'
            ),
            $conditions
        );
    }

    public function is_parent(): bool
    {
        return false;
    }

    public function get_children(): SettingCollection
    {
        $settings = new SettingCollection();

        $before_after = [
            'aperture'      => ['/f', ''],
            'focal_length'  => ['', 'mm'],
            'iso'           => ['ISO', ''],
            'shutter_speed' => ['', 's'],
        ];

        $not = [];

        foreach ($before_after as $key => $defaults) {
            $conditions = Compare::equal($key);

            $settings->add(
                new Settings\Column\BeforeAfter(
                    $conditions,
                    ...$defaults
                )
            );

            $not[] = $conditions;
        }

        $settings->add(
            new Settings\Column\BeforeAfter(
                new NotSpecification(
                    new OrSpecification($not)
                )
            )
        );

        return $settings;
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

    public function format(Value $value, ArrayImmutable $options): Value
    {
        $exif_datatype = $options->get(self::NAME) ?? '';
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

        return $this->format_recursive($value->with_value($exif_value), $options);
    }

}