<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression;
use AC\Setting\ArrayImmutable;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Setting\SettingCollection;
use AC\Setting\Type\Value;
use AC\Settings;

class Comment extends Recursive
{

    public const NAME = 'comment';

    public const PROPERTY_COMMENT = 'comment';
    public const PROPERTY_DATE = 'date';
    public const PROPERTY_ID = 'id';
    public const PROPERTY_AUTHOR = 'author';
    public const PROPERTY_AUTHOR_EMAIL = 'author_email';

    public function __construct(Expression\Specification $specification = null)
    {
        parent::__construct(
            'comment',
            __('Display', 'codepress-admin-columns'),
            '',
            OptionFactory::create_select(
                OptionCollection::from_array([
                    self::PROPERTY_COMMENT      => __('Comment'),
                    self::PROPERTY_ID           => __('ID'),
                    self::PROPERTY_AUTHOR       => __('Author'),
                    self::PROPERTY_AUTHOR_EMAIL => __('Author Email', 'codepress-admin-column'),
                    self::PROPERTY_DATE         => __('Date'),
                ]),
                self::PROPERTY_COMMENT
            ),
            $specification
        );
    }

    public function is_parent(): bool
    {
        return false;
    }

    public function get_children(): SettingCollection
    {
        return new SettingCollection([
            new Settings\Column\Date(Expression\StringComparisonSpecification::equal(self::PROPERTY_DATE)),
            new Settings\Column\StringLimit(Expression\StringComparisonSpecification::equal(self::PROPERTY_COMMENT)),
            new Settings\Column\CommentLink(),
        ]);
    }

    public function format(Value $value, ArrayImmutable $options): Value
    {
        switch ($options->get(self::NAME)) {
            case self::PROPERTY_DATE :
                return parent::format(
                    $value->with_value($this->get_comment_property('comment_date', $value->get_id()) ?: false),
                    $options
                );

            case self::PROPERTY_AUTHOR :
                return parent::format(
                    $value->with_value($this->get_comment_property('comment_author', $value->get_id()) ?: false),
                    $options
                );

            case self::PROPERTY_AUTHOR_EMAIL :
                return parent::format(
                    $value->with_value(
                        $this->get_comment_property('comment_author_email', $value->get_id()) ?: false
                    ),
                    $options
                );

            case self::PROPERTY_COMMENT :
                return parent::format(
                    $value->with_value($this->get_comment_property('comment_content', $value->get_id()) ?: false),
                    $options
                );

            case self::PROPERTY_ID :
                return parent::format($value->with_value($value->get_id()), $options);
            default :
                return parent::format($value, $options);
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