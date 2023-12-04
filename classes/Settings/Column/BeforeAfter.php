<?php

namespace AC\Settings\Column;

use AC\Column;
use AC\Setting\Base;
use AC\Setting\Input;
use AC\Setting\SettingCollection;
use ACP\Expression\Specification;

// TODO Stefan Implement Formatter
// TODO David good use case to think about BaseFormatter/ Runtime extension
class BeforeAfter extends Recursive
{

    public function __construct(Column $column, Specification $specification = null)
    {
        $this->name = 'before_after';
        $this->label = __('Display Options', 'codepress-admin-columns');
        $this->input = new Input\Custom('empty');

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
                'before',
                __('Before', 'codepress-admin-columns'),
                '',
                Input\Open::create_text()
            ),
            new Base\Setting(
                'after',
                __('After', 'codepress-admin-columns'),
                '',
                Input\Open::create_text()
            ),
        ]);
    }

    //	implements Settings\FormatValue {
    //
    //	const NAME = 'before_after';
    //
    //	/**
    //	 * @var string
    //	 */
    //	private $before;
    //
    //	/**
    //	 * @var string
    //	 */
    //	private $after;
    //
    //	protected function set_name() {
    //		$this->name = self::NAME;
    //	}
    //
    //	protected function define_options() {
    //		return [ 'before', 'after' ];
    //	}
    //
    //	public function format( $value, $original_value ) {
    //		if ( ac_helper()->string->is_empty( $value ) ) {
    //			return $value;
    //		}
    //
    //		if ( $this->get_before() || $this->get_after() ) {
    //			$value = $this->get_before() . $value . $this->get_after();
    //		}
    //
    //		return $value;
    //	}
    //
    //	protected function get_before_element() {
    //		$text = $this->create_element( 'text', 'before' );
    //		$text->set_attribute( 'placeholder', $this->get_default( 'before' ) );
    //
    //		return $text;
    //	}
    //
    //	protected function get_after_element() {
    //		$text = $this->create_element( 'text', 'after' );
    //		$text->set_attribute( 'placeholder', $this->get_default( 'after' ) );
    //
    //		return $text;
    //	}
    //
    //	public function create_view() {
    //		$setting = $this->get_before_element();
    //
    //		$for = $setting->get_id();
    //
    //		$before = new View( [
    //			'label'       => __( 'Before', 'codepress-admin-columns' ),
    //			'description' => __( 'This text will appear before the column value.', 'codepress-admin-columns' ),
    //			'setting'     => $setting,
    //			'for'         => $for,
    //		] );
    //
    //		$setting = $this->get_after_element();
    //
    //		$after = new View( [
    //			'label'       => __( 'After', 'codepress-admin-columns' ),
    //			'description' => __( 'This text will appear after the column value.', 'codepress-admin-columns' ),
    //			'setting'     => $setting,
    //			'for'         => $setting->get_id(),
    //		] );
    //
    //		return new View( [
    //			'label'    => __( 'Display Options', 'codepress-admin-columns' ),
    //			'sections' => [ $before, $after ],
    //			'for'      => $for,
    //		] );
    //	}
    //
    //	/**
    //	 * @return string
    //	 */
    //	public function get_before() {
    //		return $this->before;
    //	}
    //
    //	/**
    //	 * @param $before
    //	 *
    //	 * @return bool
    //	 */
    //	public function set_before( $before ) {
    //		$this->before = $before;
    //
    //		return true;
    //	}
    //
    //	/**
    //	 * @return string
    //	 */
    //	public function get_after() {
    //		return $this->after;
    //	}
    //
    //	/**
    //	 * @param $after
    //	 *
    //	 * @return bool
    //	 */
    //	public function set_after( $after ) {
    //		$this->after = $after;
    //
    //		return true;
    //	}

}