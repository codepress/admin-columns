<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\ArrayImmutable;
use AC\Setting\Input;
use AC\Setting\OptionCollection;
use AC\Setting\SettingCollection;
use AC\Setting\Type\Value;
use AC\Settings\Column;

abstract class DateTimeFormat extends Recursive
{

    public const NAME = 'date';

    public function __construct(Specification $conditions = null)
    {
        // TODO add default date format, see AC\Column\Post\Modified

        parent::__construct(
            'date',
            __('Date Format', 'codepress-admin-columns'),
            '',
            new Input\Custom('date_format'),
            $conditions
        );
    }

    public function get_children(): SettingCollection
    {
        return new SettingCollection([
            new Column(
                'date_format',
                '',
                '',
                Input\Option\Single::create_radio(
                    OptionCollection::from_array($this->get_date_options()),
                    'wp_default'
                )
            ),
        ]);
    }

    public function format(Value $value, ArrayImmutable $options): Value
    {
        $timestamp = $this->get_timestamp($value->get_value());

        if ( ! $timestamp) {
            return $value->with_value(false);
        }

        $date_format = (string)$options->get($this->name);

        if ($date_format === 'wp_default') {
            $date_format = $this->get_wp_default_format();
        }

        return $value->with_value(
            ac_helper()->date->format_date($date_format, $timestamp)
        );
    }

    protected function get_date_options()
    {
        return $this->get_custom_format_options();
    }
    //
    //	protected function set_name() {
    //		$this->name = self::NAME;
    //	}
    //
    //	protected function define_options() {
    //		return [
    //			'date_format' => 'wp_default',
    //		];
    //	}
    //
    abstract protected function get_custom_format_options();

    //
    abstract protected function get_wp_default_format();
    //
    //	/**
    //	 * @param string $label
    //	 * @param string $date_format
    //	 * @param string $description
    //	 *
    //	 * @return string
    //	 */
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
    //	public function create_view() {
    //		$setting = $this
    //			->create_element( 'text' )
    //			->set_attribute( 'placeholder', $this->get_default() );
    //
    //		$view = new View( [
    //			'custom_date_formats' => $this->get_custom_formats(),
    //			'setting'             => $setting,
    //			'date_format'         => $this->get_date_format(),
    //			'date_options'        => $this->get_date_options(),
    //			'label'               => __( 'Date Format', 'codepress-admin-columns' ),
    //			'tooltip'             => __( 'This will determine how the date will be displayed.', 'codepress-admin-columns' ),
    //		] );
    //
    //		$view->set_template( 'settings/setting-date' );
    //
    //		return $view;
    //	}
    //
    //	protected function get_custom_formats() {
    //		return [ 'wp_default', 'diff' ];
    //	}
    //
    protected function get_html_label_from_date_format($date_format)
    {
        return ac_helper()->date->format_date($date_format, null, ac_helper()->date->timezone());
    }
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

    protected function get_timestamp($date): ?int
    {
        if (empty($date)) {
            return null;
        }

        if ( ! is_scalar($date)) {
            return null;
        }

        if (is_numeric($date)) {
            return (int)$date;
        }

        return strtotime($date) ?: null;
    }

}