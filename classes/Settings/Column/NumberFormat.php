<?php

namespace AC\Settings\Column;

use AC;
use AC\Column;
use AC\Setting\Base;
use AC\Setting\Input;
use AC\Setting\SettingCollection;
use AC\Setting\SettingTrait;
use AC\Settings;
use ACP\Expression\Specification;
use ACP\Expression\StringComparisonSpecification;

class NumberFormat extends Settings\Column implements AC\Setting\Recursive
{

    use SettingTrait;
    use AC\Setting\RecursiveTrait;

    public function __construct(Column $column, Specification $specification = null)
    {
        $this->name = 'number_format';
        $this->label = __('Number Format', 'codepress-admin-columns');
        $this->input = Input\Option\Single::create_select(
            AC\Setting\OptionCollection::from_array([
                ''          => __('Default', 'codepress-admin-column'),
                'formatted' => __('Formatted', 'codepress-admin-column'),
            ])
        );

        parent::__construct($column, $specification);
    }

    public function is_parent(): bool
    {
        return true;
    }

    public function get_children(): SettingCollection
    {
        return new SettingCollection([
            new Base\Setting(
                'number_decimals',
                __('Decimals', 'codepress-admin-columns'),
                '',
                Input\Number::create_single_step(0, 20, 0),
                StringComparisonSpecification::equal('formatted')
            ),
            new Base\Setting(
                'number_decimal_point',
                __('Decimal point', 'codepress-admin-columns'),
                '',
                new Input\Text(),
                StringComparisonSpecification::equal('formatted')
            ),
            new Base\Setting(
                'number_thousands_separator',
                __('Thousands separator', 'codepress-admin-columns'),
                '',
                new Input\Text(),
                StringComparisonSpecification::equal('formatted')
            ),
            new Base\Setting(
                'number_preview',
                __('Preview', 'codepress-admin-columns'),
                '',
                new Input\Custom('number_preview', [
                    'keys' => ['number_decimals', 'number_decimal_point', 'number_thousands_separator'],
                ]),
                StringComparisonSpecification::equal('formatted')
            ),
        ]);
    }

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