<?php

declare(strict_types=1);

namespace AC\Helper;

class AttachmentsByParent extends Creatable
{

    /**
     * @return int[] Attachment IDs whose post_parent equals $parent_id, grouped by file extension
     *               (preserving order of first appearance).
     */
    public function find(int $parent_id): array
    {
        if ($parent_id <= 0) {
            return [];
        }

        $ids = get_posts([
            'post_type'      => 'attachment',
            'posts_per_page' => -1,
            'post_status'    => null,
            'post_parent'    => $parent_id,
            'fields'         => 'ids',
        ]);

        return $this->group_by_extension(array_map('intval', $ids));
    }

    /**
     * @param int[] $ids
     *
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

        return $groups ? array_merge(...array_values($groups)) : [];
    }

}
