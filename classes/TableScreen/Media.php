<?php

declare(strict_types=1);

namespace AC\TableScreen;

use AC;
use AC\Column;
use AC\ColumnRepository;
use AC\ListScreen\ListTable;
use AC\MetaType;
use AC\PostType;
use AC\Table;
use AC\TableScreen;
use AC\Type\Labels;
use AC\Type\ListKey;
use AC\Type\Uri;
use AC\Type\Url;
use AC\WpListTableFactory;

class Media extends TableScreen implements ListTable, PostType
{

    public function __construct(ListKey $key)
    {
        parent::__construct($key, 'upload', false);
    }

    public function manage_value(ColumnRepository $column_repository): AC\Table\ManageValue
    {
        return new Table\ManageValue\Media($column_repository);
    }

    public function list_table(): AC\ListTable
    {
        return new AC\ListTable\Media((new WpListTableFactory())->create_media_table($this->screen_id));
    }

    public function get_group(): string
    {
        return 'media';
    }

    public function get_query_type(): string
    {
        return MetaType::POST;
    }

    public function get_meta_type(): MetaType
    {
        return new MetaType(MetaType::POST);
    }

    public function get_post_type(): string
    {
        return 'attachment';
    }

    public function get_attr_id(): string
    {
        return '#the-list';
    }

    public function get_url(): Uri
    {
        return new Url\ListTable\Media();
    }

    public function get_labels(): Labels
    {
        return new Labels(
            __('Media'),
            __('Media')
        );
    }

    protected function get_columns_fqn(): array
    {
        $columns = [
            Column\CustomField::class,
            Column\Actions::class,
            Column\Post\TitleRaw::class,
            Column\Post\Slug::class,
            Column\Post\TitleRaw::class,
            Column\Media\Album::class,
            Column\Media\AlternateText::class,
            Column\Media\Artist::class,
            Column\Media\Author::class,
            Column\Media\AuthorName::class,
            Column\Media\AvailableSizes::class,
            Column\Media\Caption::class,
            Column\Media\Date::class,
            Column\Media\Description::class,
            Column\Media\Dimensions::class,
            Column\Media\FileMetaAudio::class,
            Column\Media\FileMetaVideo::class,
            Column\Media\FileName::class,
            Column\Media\FileSize::class,
            Column\Media\FullPath::class,
            Column\Media\Height::class,
            Column\Media\ID::class,
            Column\Media\Image::class,
            Column\Media\MediaParent::class,
            Column\Media\Menu::class,
            Column\Media\MimeType::class,
            Column\Media\Preview::class,
            Column\Media\Taxonomy::class,
            Column\Media\Title::class,
            Column\Media\VideoPlayer::class,
            Column\Media\Width::class,
        ];

        if (post_type_supports($this->get_post_type(), 'comments')) {
            $columns[] = Column\Media\Comments::class;
        }
        if (function_exists('exif_read_data')) {
            $columns[] = Column\Media\ExifData::class;
        }

        return $columns;
    }

}