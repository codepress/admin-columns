<?php

namespace AC\Settings\Column;

use AC;
use AC\Setting\OptionCollection;
use AC\Setting\Type\Option;
use AC\Settings;

class AttachmentDisplay extends Settings\Column implements AC\Setting\Option
{

    //implements Settings\FormatValue {

    use AC\Setting\OptionTrait;
    use AC\Setting\SettingTrait;

    public function __construct(AC\Column $column)
    {
        $this->name = 'attachment_display';
        $this->label = __('Display', 'codepress-admin-columns');
        $this->input = AC\Setting\Input\Multiple::create_select();
        $this->options = $this->get_display_options();

        parent::__construct($column);
    }

    private function get_display_options(): AC\Setting\OptionCollection
    {
        return new OptionCollection([
            new Option(__('Thumbnails', 'codepress-admin-columns'), 'thumbnail'),
            new Option(__('Count', 'codepress-admin-columns'), 'count'),
        ]);
    }

    //	private $attachment_display;
    //
    //	protected function define_options() {
    //		return [
    //			'attachment_display' => 'thumbnail',
    //		];
    //	}
    //
    //	public function get_dependent_settings() {
    //		$settings = [];
    //
    //		switch ( $this->get_attachment_display() ) {
    //			case 'thumbnail' :
    //				$settings[] = new Settings\Column\Images( $this->column );
    //
    //				break;
    //		}
    //
    //		return $settings;
    //	}
    //
    //	public function create_view() {
    //
    //		$setting = $this->create_element( 'select' )
    //		                ->set_attribute( 'data-refresh', 'column' )
    //		                ->set_options( [
    //			                'thumbnail' => __( 'Thumbnails', 'codepress-admin-columns' ),
    //			                'count'     => __( 'Count', 'codepress-admin-columns' ),
    //		                ] );
    //
    //		return new View( [
    //			'label'   => __( 'Display', 'codepress-admin-columns' ),
    //			'setting' => $setting,
    //		] );
    //	}
    //
    //	/**
    //	 * @return int
    //	 */
    //	public function get_attachment_display() {
    //		return $this->attachment_display;
    //	}
    //
    //	/**
    //	 * @param int $attachment_display
    //	 *
    //	 * @return bool
    //	 */
    //	public function set_attachment_display( $attachment_display ) {
    //		$this->attachment_display = $attachment_display;
    //
    //		return true;
    //	}
    //
    //	public function format( $value, $original_value ) {
    //		switch ( $this->get_attachment_display() ) {
    //			case 'count':
    //				$value = count( $value );
    //				break;
    //		}
    //
    //		return $value;
    //	}
}