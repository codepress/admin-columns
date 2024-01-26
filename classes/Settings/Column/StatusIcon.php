<?php

namespace AC\Settings\Column;

<<<<<<< HEAD
use AC\Column;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Setting\SettingTrait;
=======
use AC\Expression\Specification;
use AC\Setting;
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
use AC\Settings;

// TODO formatter
class StatusIcon extends Settings\Column
{

    public function __construct(Specification $conditionals = null)
    {
<<<<<<< HEAD
        $this->name = 'use_icon';
        $this->label = __('Use an icon?', 'codepress-admin-columns');
        $this->description = __('Use an icon instead of text for displaying the status.', 'codepress-admin-columns');
        $this->input = OptionFactory::create_toggle(
            OptionCollection::from_array([
                '1',
                '0',
            ], false)
=======
        parent::__construct(
            'use_icon',
            __('Use an icon?', 'codepress-admin-columns'),
            __('Use an icon instead of text for displaying the status.', 'codepress-admin-columns'),
            Setting\Input\Option\Single::create_toggle(
                Setting\OptionCollection::from_array(['1', '0',], false)
            ),
            $conditionals
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
        );
    }


    // TODO
    //	/**
    //	 * @var bool
    //	 */
    //	private $use_icon;
    //
    //	protected function define_options() {
    //		return [ 'use_icon' => '' ];
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
    //		$view = new View( [
    //			'label'   => __( 'Use an icon?', 'codepress-admin-columns' ),
    //			'tooltip' => __( 'Use an icon instead of text for displaying the status.', 'codepress-admin-columns' ),
    //			'setting' => $setting,
    //		] );
    //
    //		return $view;
    //	}
    //
    //	/**
    //	 * @return int
    //	 */
    //	public function get_use_icon() {
    //		return $this->use_icon;
    //	}
    //
    //	/**
    //	 * @param $use_icon
    //	 *
    //	 * @return bool
    //	 */
    //	public function set_use_icon( $use_icon ) {
    //		$this->use_icon = $use_icon;
    //
    //		return true;
    //	}
    //
    //	/**
    //	 * @param string $status
    //	 * @param int    $post_id
    //	 *
    //	 * @return string
    //	 */
    //	public function format( $status, $post_id ) {
    //		global $wp_post_statuses;
    //
    //		$value = $status;
    //
    //		$post = get_post( $post_id );
    //
    //		if ( $this->get_use_icon() ) {
    //			$value = ac_helper()->post->get_status_icon( $post );
    //
    //			if ( $post->post_password ) {
    //				$value .= ac_helper()->html->tooltip( ac_helper()->icon->dashicon( [ 'icon' => 'lock', 'class' => 'gray' ] ), __( 'Password protected' ) );
    //			}
    //		} else if ( isset( $wp_post_statuses[ $status ] ) ) {
    //			$value = $wp_post_statuses[ $status ]->label;
    //
    //			if ( 'future' === $status ) {
    //				$value .= " <p class='description'>" . ac_helper()->date->date( $post->post_date, 'wp_date_time' ) . "</p>";
    //			}
    //		}
    //
    //		return $value;
    //	}

}