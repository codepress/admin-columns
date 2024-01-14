<?php

namespace AC\Settings\Column;

use AC\Settings;
use AC\View;

class ExifData extends Settings\Column
    implements Settings\FormatValue
{

    public const NAME = 'exif_data';

    /**
     * @var string
     */
    private $exif_datatype;

    protected function set_name()
    {
        $this->name = self::NAME;
    }

    protected function define_options()
    {
        return ['exif_datatype' => 'aperture'];
    }

    public function create_view()
    {
        $setting = $this->create_element('select')
                        ->set_attribute('data-label', 'update')
                        ->set_attribute('data-refresh', 'column')
                        ->set_options($this->get_exif_types());

        return new View([
            'label'   => $this->column->get_label(),
            'setting' => $setting,
        ]);
    }

    public function get_dependent_settings()
    {
        switch ($this->get_exif_datatype()) {
            case 'aperture' :
                $settings = [new Settings\Column\BeforeAfter\Aperture($this->column)];

                break;
            case 'focal_length' :
                $settings = [new Settings\Column\BeforeAfter\FocalLength($this->column)];

                break;
            case 'iso' :
                $settings = [new Settings\Column\BeforeAfter\ISO($this->column)];

                break;
            case 'shutter_speed' :
                $settings = [new Settings\Column\BeforeAfter\ShutterSpeed($this->column)];

                break;
            default :
                $settings = [new Settings\Column\BeforeAfter($this->column)];
        }

        return $settings;
    }

    /**
     * Get EXIF data
     * Get extended image metadata
     * @return array EXIF data types
     * @since 2.0
     */
    private function get_exif_types()
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

    /**
     * @return string
     */
    public function get_exif_datatype()
    {
        return $this->exif_datatype;
    }

    /**
     * @param string $exif_datatype
     *
     * @return bool
     */
    public function set_exif_datatype($exif_datatype)
    {
        $this->exif_datatype = $exif_datatype;

        return true;
    }

    public function format($value, $original_value)
    {
        $exif_datatype = $this->get_exif_datatype();
        $value = $value[$exif_datatype] ?? '';

        if ($value) {
            switch ($exif_datatype) {
                case 'created_timestamp' :
                    $value = ac_format_date(get_option('date_format') . ' ' . get_option('time_format'), $value);
                    break;
                case 'keywords' :
                    $value = ac_helper()->array->implode_recursive(', ', $value);
                    break;
            }
        }

        return $value;
    }

}