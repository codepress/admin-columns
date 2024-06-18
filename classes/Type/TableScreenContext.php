<?php

declare(strict_types=1);

namespace AC\Type;

use AC;
use AC\MetaType;
use AC\TableScreen;
use LogicException;

final class TableScreenContext
{

    private $meta_type;

    private $post_type;

    private $taxonomy;

    public function __construct(
        MetaType $meta_type,
        PostTypeSlug $post_type = null,
        TaxonomySlug $taxonomy = null
    ) {
        $this->meta_type = $meta_type;
        $this->post_type = $post_type;
        $this->taxonomy = $taxonomy;
    }

    public static function from_table_screen(TableScreen $screen): self
    {
        if ( ! $screen instanceof AC\TableScreen\MetaType) {
            throw new LogicException('Could not get meta type from table screen: ' . get_class($screen));
        }

        return new self(
            $screen->get_meta_type(),
            $screen instanceof AC\PostType ? new PostTypeSlug($screen->get_post_type()) : null,
            $screen instanceof AC\Taxonomy ? new TaxonomySlug($screen->get_taxonomy()) : null
        );
    }

    public function get_meta_type(): MetaType
    {
        return $this->meta_type;
    }

    public function has_post_type(): bool
    {
        return null !== $this->post_type;
    }

    public function get_post_type(): PostTypeSlug
    {
        return $this->post_type;
    }

    public function has_taxonomy(): bool
    {
        return null !== $this->taxonomy;
    }

    public function get_taxonomy(): TaxonomySlug
    {
        return $this->taxonomy;
    }

}