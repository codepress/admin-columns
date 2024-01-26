<?php

declare(strict_types=1);

namespace AC\Settings\Column;

<<<<<<< HEAD
use AC\Column;
use AC\Setting\Base;
use AC\Setting\Component;
use AC\Setting\Component\OptionCollection;
=======
use AC;
use AC\Expression\Specification;
use AC\Expression\StringComparisonSpecification;
use AC\Setting\Input;
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
use AC\Setting\SettingCollection;

class NumberFormat extends Recursive
{

    public function __construct(Specification $specification = null)
    {
<<<<<<< HEAD
        $this->name = 'number_format';
        $this->label = __('Number Format', 'codepress-admin-columns');
        $this->input = Component\OptionFactory::create_select(
            OptionCollection::from_array([
                ''          => __('Default', 'codepress-admin-column'),
                'formatted' => __('Formatted', 'codepress-admin-column'),
            ])
=======
        parent::__construct(
            'number_format',
            __('Number Format', 'codepress-admin-columns'),
            '',
            Input\Option\Single::create_select(
                AC\Setting\OptionCollection::from_array([
                    ''          => __('Default', 'codepress-admin-column'),
                    'formatted' => __('Formatted', 'codepress-admin-column'),
                ])
            ),
            $specification
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
        );
    }

    public function is_parent(): bool
    {
        return true;
    }

    public function get_children(): SettingCollection
    {
        return new SettingCollection([
            new AC\Settings\Column(
                'number_decimals',
                __('Decimals', 'codepress-admin-columns'),
                '',
                Component\Number::create_single_step(0, 20, 0),
                StringComparisonSpecification::equal('formatted')
            ),
            new AC\Settings\Column(
                'number_decimal_point',
                __('Decimal point', 'codepress-admin-columns'),
                '',
                Component\OpenFactory::create_text(null, '.'),
                StringComparisonSpecification::equal('formatted')
            ),
            new AC\Settings\Column(
                'number_thousands_separator',
                __('Thousands separator', 'codepress-admin-columns'),
                '',
<<<<<<< HEAD
                Component\OpenFactory::create_text(),
=======
                Input\Open::create_text(),
                StringComparisonSpecification::equal('formatted')
            ),
            new AC\Settings\Column(
                'number_preview',
                __('Preview', 'codepress-admin-columns'),
                '',
                new Input\Custom('number_preview', [
                    'keys' => ['number_decimals', 'number_decimal_point', 'number_thousands_separator'],
                ]),
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
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