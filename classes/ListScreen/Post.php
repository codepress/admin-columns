<?php

namespace AC\ListScreen;

use AC;
use AC\Column;

class Post extends AC\ListScreenPost
{

    protected function register_column_types(): void
    {
        $this->register_column_types_from_list([
            Column\CustomField::class,
            Column\Actions::class,
            Column\Post\Attachment::class,
            Column\Post\Author::class,
            Column\Post\AuthorName::class,
            Column\Post\BeforeMoreTag::class,
            Column\Post\Categories::class,
            Column\Post\CommentCount::class,
            Column\Post\Comments::class,
            Column\Post\CommentStatus::class,
            Column\Post\Content::class,
            Column\Post\Date::class,
            Column\Post\DatePublished::class,
            Column\Post\Depth::class,
            Column\Post\EstimatedReadingTime::class,
            Column\Post\Excerpt::class,
            Column\Post\FeaturedImage::class,
            Column\Post\Formats::class,
            Column\Post\ID::class,
            Column\Post\LastModifiedAuthor::class,
            Column\Post\Menu::class,
            Column\Post\Modified::class,
            Column\Post\Order::class,
            Column\Post\PageTemplate::class,
            Column\Post\PasswordProtected::class,
            Column\Post\Path::class,
            Column\Post\Permalink::class,
            Column\Post\PingStatus::class,
            Column\Post\PostParent::class,
            Column\Post\Shortcodes::class,
            Column\Post\Shortlink::class,
            Column\Post\Slug::class,
            Column\Post\Status::class,
            Column\Post\Sticky::class,
            Column\Post\Tags::class,
            Column\Post\Taxonomy::class,
            Column\Post\Title::class,
            Column\Post\TitleRaw::class,
            Column\Post\WordCount::class,
        ]);
    }

}