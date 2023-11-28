<?php

namespace AC\Settings\Column;

use AC\Column;
use AC\Setting\Input;
use AC\Setting\SettingTrait;
use AC\Settings;
use ACP\Expression\Specification;

// TODO formatter
class LinkLabel extends Settings\Column
{

    use SettingTrait;

    public function __construct(Column $column, Specification $specification)
    {
        $this->name = 'link_label';
        $this->label = __('Link Label', 'codepress-admin-columns');
        $this->description = __('Leave blank to display the URL', 'codepress-admin-columns');
        $this->input = Input\Open::create_text();

        parent::__construct($column, $specification);
    }

    //	/**
    //	 * @var string
    //	 */
    //	private $link_label;
    //
    //	protected function define_options() {
    //		return [ 'link_label' ];
    //	}
    //
    //	public function create_view() {
    //		$view = new View( [
    //			'setting' => $this->create_element( 'text' ),
    //			'label'   => __( 'Link Label', 'codepress-admin-columns' ),
    //			'tooltip' => __( 'Leave blank to display the URL', 'codepress-admin-columns' ),
    //		] );
    //
    //		return $view;
    //	}
    //
    //	public function get_link_label() {
    //		return $this->link_label;
    //	}
    //
    //	public function set_link_label( $link_label ) {
    //		$this->link_label = $link_label;
    //
    //		return true;
    //	}
    //
    //	public function format( $value, $original_value ) {
    //		$url = $value;
    //
    //		if ( filter_var( $url, FILTER_VALIDATE_URL ) && preg_match( '/[^\w.-]/', $url ) ) {
    //			$label = $this->get_value();
    //
    //			if ( ! $label ) {
    //				$label = $url;
    //			}
    //
    //			$value = ac_helper()->html->link( $url, $label );
    //		}
    //
    //		return $value;
    //	}

}