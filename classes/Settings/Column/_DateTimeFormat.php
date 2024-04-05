<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting;
use AC\Setting\ComponentCollection;
use AC\Setting\Control\Input\Custom;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;
use AC\Settings;

abstract class DateTimeFormat extends Settings\Control implements Setting\Children, Formatter
{

    protected $date_format;

    public function __construct(string $date_format = null, Specification $conditions = null)
    {
        parent::__construct(
            new Custom('date_format', 'date_format'),
            __('Date Format', 'codepress-admin-columns'),
            null,
            $conditions
        );

        $this->date_format = $date_format ?? 'wp_default';
    }

    abstract protected function get_date_options(): OptionCollection;

    abstract protected function get_wp_default_format(): string;

    public function get_iterator(): ComponentCollection
    {
        // TODO do we need this when we use a Custom setting?
        return new ComponentCollection([
            new Settings\Control(
                OptionFactory::create_radio(
                    'date_format',
                    $this->get_date_options(),
                    $this->date_format
                ),
                ''
            ),
        ]);
    }

    public function format(Value $value): Value
    {
        $timestamp = $this->create_timestamp(
            $value->get_value()
        );

        if ( ! $timestamp) {
            return $value->with_value(false);
        }

        $date_format = $this->date_format;

        if ('wp_default' === $this->date_format) {
            $date_format = $this->get_wp_default_format();
        }

        // TODO validate date format

        return $value->with_value(
            wp_date($date_format, $timestamp)
        );
    }

    protected function create_timestamp($value): ?int
    {
        if (empty($value) || ! is_scalar($value)) {
            return null;
        }

        if (is_numeric($value)) {
            return (int)$value;
        }

        return strtotime($value) ?: null;
    }
    //
    //	/**
    //	 * @param string $label
    //	 * @param string $date_format
    //	 * @param string $description
    //	 *
    //	 * @return string
    //	 */
    // TODO add descriptions to frontend
    //	protected function get_default_html_label( $label, $date_format = '', $description = '' ) {
    //		if ( ! $date_format ) {
    //			$date_format = $this->get_wp_default_format();
    //		}
    //
    //		if ( ! $description && current_user_can( 'manage_options' ) ) {
    //			$description = sprintf(
    //				__( 'The %s can be changed in %s.', 'codepress-admin-columns' ),
    //				$label,
    //				ac_helper()->html->link( admin_url( 'options-general.php' ) . '#date_format_custom_radio', strtolower( __( 'General Settings' ) ) )
    //			);
    //		}
    //
    //		return $this->get_html_label( $label, $date_format, $description );
    //	}
    //
    //
    //	protected function get_custom_formats() {
    //		return [ 'wp_default', 'diff' ];
    //	}
    //
    //    protected function create_date(string $date_format): string
    //    {
    //        return ac_helper()->date->format_date($date_format, null, ac_helper()->date->timezone());
    //    }
    //
    //	/**
    //	 * @param array $formats
    //	 *
    //	 * @return array
    //	 */
    //	protected function get_formatted_date_options( $formats ) {
    //		$options = [];
    //
    //		foreach ( $formats as $format ) {
    //			$options[ $format ] = $this->get_html_label( ac_helper()->date->format_date( $format, null, ac_helper()->date->timezone() ), $format );
    //		}
    //
    //		return $options;
    //	}
    //
    //	protected function get_html_label( $label, $date_format = '', $description = '' ) {
    //		$output = '<span class="ac-setting-input-date__value">' . $label . '</span>';
    //
    //		if ( $date_format ) {
    //			$output .= '<code>' . $date_format . '</code>';
    //		}
    //
    //		if ( $description ) {
    //			$output .= '<span data-help class="ac-setting-input-date__more hidden">' . $description . '</span>';
    //		}
    //
    //		return $output;
    //	}
    //
    //	protected function get_date_options() {
    //		$options = $this->get_custom_format_options();
    //
    //		$custom_label = $this->get_html_label(
    //			__( 'Custom:', 'codepress-admin-columns' ),
    //			'',
    //			sprintf( __( 'Learn more about %s.', 'codepress-admin-columns' ), ac_helper()->html->link( 'https://wordpress.org/support/article/formatting-date-and-time/', __( 'date and time formatting', 'codepress-admin-columns' ), [ 'target' => '_blank' ] ) )
    //		);
    //
    //		$custom_label .= '<input type="text" class="ac-setting-input-date__custom" data-custom-date value="' . esc_attr( $this->get_date_format() ) . '" disabled>';
    //		$custom_label .= '<span class="ac-setting-input-date__example"></span>';
    //
    //		$options['custom'] = $custom_label;
    //
    //		return $options;
    //	}
    //
    //	/**
    //	 * @return mixed
    //	 */

}