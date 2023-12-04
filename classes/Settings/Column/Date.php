<?php

namespace AC\Settings\Column;

use AC\Setting\Type\Value;
use AC\Settings;

class Date extends Settings\Column\DateTimeFormat
{

    protected function get_custom_format_options()
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
            $options[$format] = $this->get_html_label_from_date_format($format);
        }

        return $options;
    }

    public function format(Value $value, array $options): Value
    {
        if ('diff' === $options[$this->name]) {
            $timestamp = $this->get_timestamp($value->get_value());

            return $value->with_value($timestamp ? $this->format_human_time_diff($timestamp) : false);
        }

        return parent::format($value, $options);
    }

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
    protected function get_wp_default_format()
    {
        return get_option('date_format');
    }
    //
    //	/**
    //	 * @param string $date
    //	 * @param        $original_value
    //	 *
    //	 * @return string
    //	 */
    //	public function format( $date, $original_value ) {
    //		$timestamp = $this->get_timestamp( $date );
    //
    //		if ( ! $timestamp ) {
    //			return false;
    //		}
    //
    //		if ( 'diff' === $this->get_date_format() ) {
    //			return $this->format_human_time_diff( $timestamp );
    //		}
    //
    //		return parent::format( $date, $original_value );
    //	}
    //
    //	/**
    //	 * @param int $timestamp Unix time stamp
    //	 *
    //	 * @return string
    //	 */
    public function format_human_time_diff($timestamp): ?string
    {
        if ( ! $timestamp) {
            return null;
        }

        $current_time = current_time('timestamp');

        $tpl = __('%s ago');

        if ($timestamp > $current_time) {
            $tpl = __('in %s', 'codepress-admin-columns');
        }

        return sprintf($tpl, human_time_diff($timestamp, $current_time));
    }

}