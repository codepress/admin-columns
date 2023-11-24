<?php

namespace AC\Settings\Column;

use AC\Column;
use AC\Setting\Input;
use AC\Setting\OptionCollection;
use AC\Setting\Recursive;
use AC\Setting\RecursiveTrait;
use AC\Setting\SettingCollection;
use AC\Setting\SettingTrait;
use AC\Settings;
use ACP\Expression;

/**
 * @since 3.0.8
 */
class Comment extends Settings\Column implements Recursive
{

    use SettingTrait;
    use RecursiveTrait;

    public const NAME = 'comment';

    public const PROPERTY_COMMENT = 'comment';
    public const PROPERTY_DATE = 'date';
    public const PROPERTY_ID = 'id';
    public const PROPERTY_AUTHOR = 'author';
    public const PROPERTY_AUTHOR_EMAIL = 'author_email';

    public function __construct(Column $column, Expression\Specification $specification = null)
    {
        $this->name = 'comment';

        $this->label = __('Display', 'codepress-admin-columns');
        $this->input = Input\Option\Multiple::create_select(
            OptionCollection::from_array([
                self::PROPERTY_COMMENT      => __('Comment'),
                self::PROPERTY_ID           => __('ID'),
                self::PROPERTY_AUTHOR       => __('Author'),
                self::PROPERTY_AUTHOR_EMAIL => __('Author Email', 'codepress-admin-column'),
                self::PROPERTY_DATE         => __('Date'),
            ])
        );

        parent::__construct($column, $specification);
    }

    public function get_children(): SettingCollection
    {
        return new SettingCollection([
            new Settings\Column\CommentLink(
                $this->column,
                new Expression\AndSpecification([
                    Expression\StringComparisonSpecification::equal(self::PROPERTY_ID),
                    Expression\StringComparisonSpecification::equal(self::PROPERTY_DATE),
                ])

            ),
            new Settings\Column\Date(
                $this->column,
                Expression\StringComparisonSpecification::equal(self::PROPERTY_DATE)
            ),
            new Settings\Column\StringLimit(
                $this->column,
                Expression\StringComparisonSpecification::equal(self::PROPERTY_COMMENT)
            ),
        ]);
    }



    //	/**
    //	 * @var string
    //	 */
    //	private $comment_property;
    //
    //	protected function set_name() {
    //		$this->name = self::NAME;
    //	}
    //
    //	protected function define_options() {
    //		return [
    //			'comment_property_display' => 'comment',
    //		];
    //	}
    //
    //	public function get_dependent_settings() {
    //
    //		switch ( $this->get_comment_property_display() ) {
    //
    //			case self::PROPERTY_DATE :
    //				return [
    //					new Settings\Column\Date( $this->column ),
    //					new Settings\Column\CommentLink( $this->column ),
    //				];
    //
    //			case self::PROPERTY_COMMENT :
    //				return [
    //					new Settings\Column\StringLimit( $this->column ),
    //					new Settings\Column\CommentLink( $this->column ),
    //				];
    //
    //			default :
    //				return [ new Settings\Column\CommentLink( $this->column ) ];
    //		}
    //	}
    //
    //	/**
    //	 * @param int   $id
    //	 * @param mixed $original_value
    //	 *
    //	 * @return string|int
    //	 */
    //	public function format( $id, $original_value ) {
    //
    //		switch ( $this->get_comment_property_display() ) {
    //
    //			case self::PROPERTY_DATE :
    //				$value = $this->get_comment_property( 'comment_date', $id );
    //
    //				break;
    //			case self::PROPERTY_AUTHOR :
    //				$value = $this->get_comment_property( 'comment_author', $id );
    //
    //				break;
    //			case self::PROPERTY_AUTHOR_EMAIL :
    //				$value = $this->get_comment_property( 'comment_author_email', $id );
    //
    //				break;
    //			case self::PROPERTY_COMMENT :
    //				$value = $this->get_comment_property( 'comment_content', $id );
    //
    //				break;
    //			default :
    //				$value = $id;
    //		}
    //
    //		return $value;
    //	}
    //
    //	/**
    //	 * @param string $property
    //	 * @param int    $id
    //	 *
    //	 * @return false|string
    //	 */
    //	private function get_comment_property( $property, $id ) {
    //		$comment = get_comment( $id );
    //
    //		if ( ! isset( $comment->{$property} ) ) {
    //			return false;
    //		}
    //
    //		return $comment->{$property};
    //	}
    //
    //	public function create_view() {
    //		$select = $this->create_element( 'select' )
    //		               ->set_attribute( 'data-refresh', 'column' )
    //		               ->set_options( $this->get_display_options() );
    //
    //		$view = new View( [
    //			'label'   => __( 'Display', 'codepress-admin-columns' ),
    //			'setting' => $select,
    //		] );
    //
    //		return $view;
    //	}
    //
    //	protected function get_display_options() {
    //		$options = [
    //			self::PROPERTY_COMMENT      => __( 'Comment' ),
    //			self::PROPERTY_ID           => __( 'ID' ),
    //			self::PROPERTY_AUTHOR       => __( 'Author' ),
    //			self::PROPERTY_AUTHOR_EMAIL => __( 'Author Email', 'codepress-admin-column' ),
    //			self::PROPERTY_DATE         => __( 'Date' ),
    //		];
    //
    //		natcasesort( $options );
    //
    //		return $options;
    //	}
    //
    //	/**
    //	 * @return string
    //	 */
    //	public function get_comment_property_display() {
    //		return $this->comment_property;
    //	}
    //
    //	/**
    //	 * @param string $comment_property
    //	 *
    //	 * @return bool
    //	 */
    //	public function set_comment_property_display( $comment_property ) {
    //		$this->comment_property = $comment_property;
    //
    //		return true;
    //	}

}