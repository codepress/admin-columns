<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\ComponentCollection;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class Comment extends Children implements Formatter
{

    public const NAME = 'comment';

    public const PROPERTY_COMMENT = 'comment';
    public const PROPERTY_DATE = 'date';
    public const PROPERTY_ID = 'id';
    public const PROPERTY_AUTHOR = 'author';
    public const PROPERTY_AUTHOR_EMAIL = 'author_email';

    private $settings;

    private $comment_display;

    public function __construct(
        string $comment_display,
        ComponentCollection $settings,
        Expression\Specification $specification = null
    ) {
        parent::__construct(
            OptionFactory::create_select(
                'comment',
                OptionCollection::from_array([
                    self::PROPERTY_COMMENT      => __('Comment'),
                    self::PROPERTY_ID           => __('ID'),
                    self::PROPERTY_AUTHOR       => __('Author'),
                    self::PROPERTY_AUTHOR_EMAIL => __('Author Email', 'codepress-admin-column'),
                    self::PROPERTY_DATE         => __('Date'),
                ]),
                $comment_display
            ),
            __('Display', 'codepress-admin-columns'),
            null,
            $specification
        );
        $this->settings = $settings;
        $this->comment_display = $comment_display;
    }

    public function get_iterator(): ComponentCollection
    {
        return $this->settings;
    }

    public function format(Value $value): Value
    {
        switch ($this->comment_display) {
            case self::PROPERTY_DATE :
                return $value->with_value(
                    $this->get_comment_property('comment_date', $value->get_id())
                );
            case self::PROPERTY_AUTHOR :
                return new Value(
                    (int)$this->get_comment_property('comment_author', $value->get_id())
                );
            case self::PROPERTY_AUTHOR_EMAIL :
                return $value->with_value(
                    $this->get_comment_property('comment_author_email', $value->get_id())
                );
            case self::PROPERTY_COMMENT :
                return $value->with_value(
                    $this->get_comment_property('comment_content', $value->get_id())
                );
            case self::PROPERTY_ID :
            default :
                return $value;
        }
    }


    // TODO
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
    private function get_comment_property(string $property, int $id): ?string
    {
        $comment = get_comment($id);

        return isset($comment->{$property})
            ? (string)$comment->{$property}
            : null;
    }
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

}