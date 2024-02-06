<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Setting\Component\OptionCollection;
use AC\Setting\Type\Value;
use AC\Settings;

// TODO
class Date extends Settings\Column\DateTimeFormat
{

    protected function get_date_options(): OptionCollection
    {
        $options = [
            'diff'       => __('Time Difference', 'codepress-admin-columns'),
            'wp_default' => __('WordPress Date Format', 'codepress-admin-columns'),
        ];

        $formats = [
            'j F Y',
            'Y-m-d',
            'm/d/Y',
            'd/m/Y',
        ];

        foreach ($formats as $format) {
            $options[$format] = wp_date($format);
        }

        return OptionCollection::from_array($options);
    }

    public function is_parent(): bool
    {
        return false;
    }

    public function format(Value $value): Value
    {
        if ('diff' === $this->date_format) {
            $timestamp = $this->create_timestamp($value->get_value());

            return $value->with_value(
                $timestamp
                    ? $this->format_human_time_diff($timestamp)
                    : false
            );
        }

        return parent::format($value);
    }

    protected function get_wp_default_format(): string
    {
        return get_option('date_format');
    }

    protected function format_human_time_diff(int $timestamp): ?string
    {
        $current_time = (int)current_time('timestamp');

        $tpl = __('%s ago');

        if ($timestamp > $current_time) {
            $tpl = __('in %s', 'codepress-admin-columns');
        }

        return sprintf($tpl, human_time_diff($timestamp, $current_time));
    }


    // TODO
    //	private function get_diff_html_label() {
    //		$description = __( 'The difference is returned in a human readable format.', 'codepress-admin-columns' ) . ' <br/>' .
    //		               sprintf( __( 'For example: %s.', 'codepress-admin-columns' ),
    //			               '"' . $this->format_human_time_diff( strtotime( "-1 hour" ) ) . '" '
    //			               . __( 'or', 'codepress-admin-columns' ) .
    //			               ' "' . $this->format_human_time_diff( strtotime( "-2 days" ) ) . '"'
    //		               );
    //
    //		return $this->get_html_label( __( 'Time Difference', 'codepress-admin-columns' ), '', $description );
    //	}
    //
    //	protected function get_custom_format_options() {
    //		$options = [
    //			'diff'       => $this->get_diff_html_label(),
    //			'wp_default' => $this->get_default_html_label( __( 'WordPress Date Format', 'codepress-admin-columns' ) ),
    //		];
    //
    //		$formats = [
    //			'j F Y',
    //			'Y-m-d',
    //			'm/d/Y',
    //			'd/m/Y',
    //		];
    //
    //		foreach ( $formats as $format ) {
    //			$options[ $format ] = $this->get_html_label_from_date_format( $format );
    //		}
    //
    //		return $options;
    //	}
    //

}