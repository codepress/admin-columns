<?php

namespace AC\Settings\Column;

use AC;
use AC\Setting\Input;
use AC\Setting\OptionCollection;
use AC\Setting\SettingTrait;
use AC\Settings;
use ACP\Expression\Specification;

/**
 * @since 3.4.6
 */
class CommentLink extends Settings\Column
{

    use SettingTrait;

    public function __construct(AC\Column $column, Specification $conditions = null)
    {
        $this->name = 'comment_link_to';
        $this->label = __('Link To', 'codepress-admin-columns');
        $this->input = Input\Option\Multiple::create_select(
            OptionCollection::from_array(
                [
                    ''             => __('None'),
                    'view_comment' => __('View Comment', 'codepress-admin-columns'),
                    'edit_comment' => __('Edit Comment', 'codepress-admin-columns'),
                ]
            )
        );

        parent::__construct($column, $conditions);
    }

    //	/**
    //	 * @var string
    //	 */
    //	protected $comment_link_to;
    //
    //	protected function define_options() {
    //		return [
    //			'comment_link_to' => '',
    //		];
    //	}
    //
    //	public function format( $value, $original_value ) {
    //		$id = $original_value;
    //
    //		switch ( $this->get_comment_link_to() ) {
    //			case 'view_comment' :
    //				$link = get_comment_link( $id );
    //
    //				break;
    //			case 'edit_comment' :
    //				$comment = get_comment( $id );
    //
    //				$link = $comment
    //					? get_edit_comment_link( $comment )
    //					: false;
    //
    //				break;
    //			default :
    //				$link = false;
    //		}
    //
    //		if ( $link ) {
    //			$value = ac_helper()->html->link( $link, $value );
    //		}
    //
    //		return $value;
    //	}
    //
    //	public function create_view() {
    //		$select = $this->create_element( 'select' )->set_options( $this->get_link_options() );
    //
    //		$view = new View( [
    //			'label'   => __( 'Link To', 'codepress-admin-columns' ),
    //			'setting' => $select,
    //		] );
    //
    //		return $view;
    //	}
    //
    //	protected function get_link_options() {
    //		return [
    //			''             => __( 'None' ),
    //			'view_comment' => __( 'View Comment', 'codepress-admin-columns' ),
    //			'edit_comment' => __( 'Edit Comment', 'codepress-admin-columns' ),
    //		];
    //	}
    //
    //	/**
    //	 * @return string
    //	 */
    //	public function get_comment_link_to() {
    //		return $this->comment_link_to;
    //	}
    //
    //	/**
    //	 * @param string $comment_link_to
    //	 *
    //	 * @return bool
    //	 */
    //	public function set_comment_link_to( $comment_link_to ) {
    //		$this->comment_link_to = $comment_link_to;
    //
    //		return true;
    //	}

}