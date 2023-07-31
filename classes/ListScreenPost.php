<?php

namespace AC;

abstract class ListScreenPost extends ListScreen
{

    protected $post_type;

    public function __construct(string $post_type, string $key, string $screen_id)
    {
        parent::__construct( $key, $screen_id );

        $this->post_type = $post_type;
        $this->meta_type = MetaType::POST;
    }

    public function get_post_type(): string
    {
        return $this->post_type;
    }

    protected function register_column_types(): void
    {
        $this->register_column_types_from_list([
            Column\CustomField::class,
            Column\Actions::class,
        ]);
    }

}