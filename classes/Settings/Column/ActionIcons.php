<?php

namespace AC\Settings\Column;

use AC;
use AC\Setting\OptionCollection;
use AC\Setting\Type\Option;
use AC\Settings\Column;

class ActionIcons extends Column implements AC\Setting\Option
{

    use AC\Setting\OptionTrait;
    use AC\Setting\SettingTrait;

    public function __construct(AC\Column $column)
    {
        $this->name = 'use_icons';
        $this->label = __('Use icons?', 'codepress-admin-columns');
        $this->input = AC\Setting\Input\Multiple::create_toggle();
        $this->options = new OptionCollection([
            Option::from_value('on'),
            Option::from_value('off'),
        ]);

        parent::__construct($column);
    }

    //	private $use_icons;
    //
    //	protected function define_options() {
    //		return [
    //			'use_icons' => '',
    //		];
    //	}
    //
    //	public function create_view() {
    //
    //		$setting = $this->create_element( 'radio' )
    //		                ->set_options( [
    //			                '1' => __( 'Yes' ),
    //			                ''  => __( 'No' ),
    //		                ] );
    //
    //		return new View( [
    //			'label'   => __( 'Use icons?', 'codepress-admin-columns' ),
    //			'tooltip' => __( 'Use icons instead of text for displaying the actions.', 'codepress-admin-columns' ),
    //			'setting' => $setting,
    //		] );
    //	}
    //
    //	/**
    //	 * @return int
    //	 */
    //	public function get_use_icons() {
    //		return $this->use_icons;
    //	}
    //
    //	/**
    //	 * @param int $use_icons
    //	 *
    //	 * @return bool
    //	 */
    //	public function set_use_icons( $use_icons ) {
    //		$this->use_icons = $use_icons;
    //
    //		return true;
    //	}

}