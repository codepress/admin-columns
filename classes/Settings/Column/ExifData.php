<?php

namespace AC\Settings\Column;

use AC;
use AC\Setting\ArrayImmutable;
use AC\Setting\SettingCollection;
use AC\Setting\Type\Value;
use AC\Settings;
use ACP\Expression\NotSpecification;
use ACP\Expression\OrSpecification;
use ACP\Expression\Specification;
use ACP\Expression\StringComparisonSpecification;

class ExifData extends Settings\Column implements AC\Setting\Recursive, AC\Setting\Formatter
{

    use AC\Setting\RecursiveFormatterTrait {
        AC\Setting\RecursiveFormatterTrait::format as format_recursive;
    }

    public const NAME = 'exif_data';

    public function __construct(AC\Column $column, Specification $conditions = null)
    {
        $this->name = self::NAME;
        $this->label = $column->get_label();
        // TODO Stefan: update label on update
        $this->input = AC\Setting\Input\Option\Single::create_select(
            AC\Setting\OptionCollection::from_array($this->get_exif_types()),
            'aperture'
        );

        parent::__construct(
            $column,
            $conditions
        );
    }

    public function is_parent(): bool
    {
        return false;
    }

    public function get_children(): SettingCollection
    {
        // TODO Stefan, default does not work once it is set. Discard subsettings or make it work?
        return new SettingCollection([
            new Settings\Column\BeforeAfter(
                $this->column, StringComparisonSpecification::equal('aperture'), '/f'
            ),
            new Settings\Column\BeforeAfter(
                $this->column, StringComparisonSpecification::equal('focal_length'), '', 'mm'
            ),
            new Settings\Column\BeforeAfter(
                $this->column, StringComparisonSpecification::equal('iso'), 'ISO'
            ),
            new Settings\Column\BeforeAfter(
                $this->column, StringComparisonSpecification::equal('shutter_speed'), '', 's'
            ),
            new Settings\Column\BeforeAfter(
                $this->column,
                new NotSpecification(
                    new OrSpecification([
                        StringComparisonSpecification::equal('aperture'),
                        StringComparisonSpecification::equal('focal_length'),
                        StringComparisonSpecification::equal('iso'),
                        StringComparisonSpecification::equal('shutter_speed'),
                    ])
                )
            ),
        ]);
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
        $raw_data = (array)$value->get_value();

        $exif_value = $raw_data[$exif_datatype] ?? '';

        if (false !== $exif_value) {
            switch ($exif_value) {
                case 'created_timestamp' :
                    $exif_value = ac_format_date(
                        get_option('date_format') . ' ' . get_option('time_format'),
                        $exif_value
                    );
                case 'keywords' :
                    $exif_value = $value->with_value(ac_helper()->array->implode_recursive(', ', $exif_value));
            }
        }

        return $this->format_recursive($value->with_value($exif_value), $options);
    }

}