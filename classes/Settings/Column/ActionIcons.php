<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollectionFactory\ToggleOptionCollection;
use AC\Settings\Column;
use ACP\Expression\Specification;

class ActionIcons extends Column
{

    use AC\Setting\SettingTrait;

    public function __construct(AC\Column $column, Specification $conditionals = null)
    {
        $this->name = 'use_icons';
        $this->label = __('Use icons?', 'codepress-admin-columns');
        $this->description = __('Use icons instead of text for displaying the actions.', 'codepress-admin-columns');
        $this->input = OptionFactory::create_toggle(
            (new ToggleOptionCollection())->create()
        );

        parent::__construct($column, $conditionals);
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