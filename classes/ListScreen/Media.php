<?php

declare(strict_types=1);

namespace AC\ListScreen;

use AC;
use AC\Column;
use AC\ColumnRepository;
use AC\MetaType;
use AC\Type\Uri;
use AC\Type\Url;
use AC\WpListTableFactory;

class Media extends AC\ListScreenPost implements ManageValue, ListTable
{

    public function __construct()
    {
        parent::__construct('wp-media', 'upload');

        $this->meta_type = MetaType::POST;
        $this->post_type = 'attachment';
        $this->group = 'media';
        $this->label = __('Media');
    }

    public function list_table(): AC\ListTable
    {
        return new AC\ListTable\Media((new WpListTableFactory())->create_media_table($this->get_screen_id()));
    }

    public function manage_value(): AC\Table\ManageValue
    {
        return new AC\Table\ManageValue\Media(new ColumnRepository($this));
    }

    public function get_table_url(): Uri
    {
        return new Url\ListTable\Media($this->has_id() ? $this->get_id() : null);
    }

    protected function register_column_types(): void
    {
        $this->register_column_types_from_list([
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
            Column\Media\Comments::class,
            Column\Media\Date::class,
            Column\Media\Description::class,
            Column\Media\Dimensions::class,
            Column\Media\ExifData::class,
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
        ]);
    }

}