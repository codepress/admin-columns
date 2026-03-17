<?php

declare(strict_types=1);

namespace AC\Formatter\Post;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;
use AC\Type\ValueCollection;

class Attachments implements Formatter
{

    public function format(Value $value): ValueCollection
    {
        $parent_id = (int)$value->get_value();

        if ( ! $parent_id) {
            throw new ValueNotFoundException('Parent ID is required');
        }

        $attachment_ids = get_posts([
            'post_type'      => 'attachment',
            'posts_per_page' => -1,
            'post_status'    => null,
            'post_parent'    => $parent_id,
            'fields'         => 'ids',
        ]);

        return ValueCollection::from_ids($parent_id, $this->group_by_extension($attachment_ids));
    }

    /**
     * Group attachment IDs by file extension, preserving the order of first appearance.
     *
     * @param int[] $ids
     * @return int[]
     */
    private function group_by_extension(array $ids): array
    {
        $groups = [];

        foreach ($ids as $id) {
            $file = get_attached_file($id);
            $ext  = $file ? strtolower((string)pathinfo($file, PATHINFO_EXTENSION)) : '';

            $groups[$ext][] = $id;
        }

        return array_merge(...array_values($groups));
    }

}