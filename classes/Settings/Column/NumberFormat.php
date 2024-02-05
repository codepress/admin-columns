<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Expression\StringComparisonSpecification;
use AC\Setting\Component;
use AC\Setting\Component\OptionCollection;
use AC\Setting\Formatter;
use AC\Setting\SettingCollection;
use AC\Setting\Type\Value;

class NumberFormat extends AC\Settings\Setting implements Formatter
{

    private $format;

    private $number_decimals;

    private $number_decimal_separator;

    private $number_thousands_separator;

    public function __construct(
        string $format,
        int $number_decimals = 0,
        string $number_decimal_separator = '',
        string $number_thousands_separator = '',
        Specification $specification = null
    ) {
        parent::__construct(
            __('Number Format', 'codepress-admin-columns'),
            '',
            Component\Input\OptionFactory::create_select(
                'number_format',
                OptionCollection::from_array([
                    ''          => __('Default', 'codepress-admin-column'),
                    'formatted' => __('Formatted', 'codepress-admin-column'),
                ]),
                $format
            ),
            $specification
        );

        $this->format = $format;
        $this->number_decimals = $number_decimals;
        $this->number_decimal_separator = $number_decimal_separator;
        $this->number_thousands_separator = $number_thousands_separator;
    }

    public function format(Value $value): Value
    {
        if ('formatted' !== $this->format) {
            return $value;
        }

        if ( ! is_numeric( $value->get_value() ) ) {
            return $value;
        }

        return $value->with_value(
            number_format(
                (float)$value->get_value(),
                $this->number_decimals,
                $this->number_decimal_separator,
                $this->number_thousands_separator
            )
        );
    }

    public function is_parent(): bool
    {
        return true;
    }

    public function get_children(): SettingCollection
    {
        return new SettingCollection([
            new AC\Settings\Setting(
                __('Decimals', 'codepress-admin-columns'),
                '',
                Component\Input\Number::create_single_step(
                    'number_decimals',
                    0,
                    20,
                    $this->number_decimals
                ),
                StringComparisonSpecification::equal('formatted')
            ),
            new AC\Settings\Setting(
                __('Decimal point', 'codepress-admin-columns'),
                '',
                Component\Input\OpenFactory::create_text(
                    'number_decimal_point',
                    $this->number_decimal_separator,
                    '.'
                ),
                StringComparisonSpecification::equal('formatted')
            ),
            new AC\Settings\Setting(
                __('Thousands separator', 'codepress-admin-columns'),
                '',
                Component\Input\OpenFactory::create_text(
                    'number_thousands_separator',
                    $this->number_thousands_separator,
                    ','
                ),
                StringComparisonSpecification::equal('formatted')
            ),
            new AC\Settings\Setting(
                __('Preview', 'codepress-admin-columns'),
                '',
                new Component\Input\Custom(
                    'number_preview',
                    [
                        'keys' => ['number_decimals', 'number_decimal_point', 'number_thousands_separator'],
                    ]
                ),
                StringComparisonSpecification::equal('formatted')
            ),
        ]);
    }

    // TODO
    //implements Settings\FormatValue {
    //
    //	/**
    //	 * @var string
    //	 */
    //	private $number_format;
    //
    //	/**
    //	 * @var int
    //	 */
    //	private $number_decimals;
    //
    //	/**
    //	 * @var string
    //	 */
    //	private $number_decimal_point;
    //
    //	/**
    //	 * @var string
    //	 */
    //	private $number_thousands_separator;
    //
    //	protected function define_options() {
    //		return [
    //			'number_format'              => '',
    //			'number_decimals'            => 0,
    //			'number_decimal_point'       => '.',
    //			'number_thousands_separator' => '',
    //		];
    //	}
    //
    //	private function get_decimals_setting() {
    //		$decimals = $this->create_element( 'number', 'number_decimals' );
    //		$decimals->set_attribute( 'placeholder', $this->get_default( 'number_decimals' ) )
    //		         ->set_attribute( 'step', 1 )
    //		         ->set_attribute( 'max', 20 )
    //		         ->set_attribute( 'min', 0 );
    //		$decimals_view = new View( [
    //			'label'   => __( 'Decimals', 'codepress-admin-columns' ),
    //			'setting' => $decimals,
    //		] );
    //
    //		return $decimals_view;
    //	}
    //
    //	private function get_decimal_point_setting() {
    //		$decimal_point = $this->create_element( 'text', 'number_decimal_point' );
    //		$decimal_point->set_attribute( 'placeholder', $this->get_default( 'number_decimal_point' ) );
    //		$decimal_point_view = new View( [
    //			'label'   => __( 'Decimal point', 'codepress-admin-columns' ),
    //			'setting' => $decimal_point,
    //		] );
    //
    //		return $decimal_point_view;
    //	}
    //
    //	private function get_thousands_point_setting() {
    //		$setting = $this->create_element( 'text', 'number_thousands_separator' );
    //		$setting->set_attribute( 'placeholder', $this->get_default( 'number_thousands_separator' ) );
    //		$view = new View( [
    //			'label'   => __( 'Thousands separator', 'codepress-admin-columns' ),
    //			'setting' => $setting,
    //		] );
    //
    //		return $view;
    //	}
    //
    //	private function get_preview_setting() {
    //		return new View( [
    //			'label'   => __( 'Preview', 'codepress-admin-columns' ),
    //			'setting' => '<code data-preview="">75000</code>',
    //		] );
    //	}
    //
    //	public function create_view() {
    //		$sections = [];
    //		$select = $this->create_element( 'select' )
    //		               ->set_attribute( 'data-refresh', 'column' )
    //		               ->set_options( [
    //			               ''          => __( 'Default', 'codepress-admin-column' ),
    //			               'formatted' => __( 'Formatted', 'codepress-admin-column' ),
    //		               ] );
    //
    //		if ( $this->get_number_format() ) {
    //			$sections[] = $this->get_decimals_setting();
    //			$sections[] = $this->get_decimal_point_setting();
    //			$sections[] = $this->get_thousands_point_setting();
    //			$sections[] = $this->get_preview_setting();
    //		}
    //
    //		$view = new View( [
    //			'label'    => __( 'Number Format', 'codepress-admin-columns' ),
    //			'setting'  => $select,
    //			'sections' => $sections,
    //		] );
    //
    //		return $view;
    //	}
    //
    //	/**
    //	 * @return string
    //	 */
    //	public function get_number_format() {
    //		return $this->number_format;
    //	}
    //
    //	/**
    //	 * @param string $number_format
    //	 *
    //	 * @return NumberFormat
    //	 */
    //	public function set_number_format( $number_format ) {
    //		$this->number_format = $number_format;
    //
    //		return $this;
    //	}
    //
    //	/**
    //	 * @return string
    //	 */
    //	public function get_number_decimals() {
    //		return $this->number_decimals;
    //	}
    //
    //	/**
    //	 * @param string $number_decimals
    //	 *
    //	 * @return NumberFormat
    //	 */
    //	public function set_number_decimals( $number_decimals ) {
    //		$this->number_decimals = $number_decimals;
    //
    //		return $this;
    //	}
    //
    //	/**
    //	 * @return string
    //	 */
    //	public function get_number_decimal_point() {
    //		return $this->number_decimal_point;
    //	}
    //
    //	/**
    //	 * @param string $number_decimal_point
    //	 *
    //	 * @return NumberFormat
    //	 */
    //	public function set_number_decimal_point( $number_decimal_point ) {
    //		$this->number_decimal_point = $number_decimal_point;
    //
    //		return $this;
    //	}
    //
    //	/**
    //	 * @return string
    //	 */
    //	public function get_number_thousands_separator() {
    //		return $this->number_thousands_separator;
    //	}
    //
    //	/**
    //	 * @param string $number_thousands_separator
    //	 *
    //	 * @return NumberFormat
    //	 */
    //	public function set_number_thousands_separator( $number_thousands_separator ) {
    //		$this->number_thousands_separator = $number_thousands_separator;
    //
    //		return $this;
    //	}
    //
    //	public function format( $value, $original_value ) {
    //		if ( ! is_numeric( $value ) ) {
    //			return $value;
    //		}
    //
    //		switch ( $this->get_number_format() ) {
    //			case 'formatted' :
    //				return number_format( $value, (int) $this->get_number_decimals(), $this->get_number_decimal_point(), $this->get_number_thousands_separator() );
    //
    //			default:
    //				return $value;
    //		}
    //	}

}