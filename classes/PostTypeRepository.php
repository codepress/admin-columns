<?php

declare(strict_types=1);

namespace AC;

use AC\ApplyFilter\PostTypes;
use LogicException;

class PostTypeRepository
{

    public function exists(string $post_type): bool
    {
        static $post_types;

        if (null === $post_types) {
            $post_types = $this->find_all();
        }

        return in_array($post_type, $post_types, true);
    }

    public function find_all(): array
    {
        if ( ! did_action('init')) {
            throw new LogicException("Call after the `init` hook.");
        }

        $post_types = get_post_types([
            '_builtin' => false,
            'show_ui'  => true,
        ]);

        // Reusable content blocks 'wp_block' for Gutenberg
        foreach (['post', 'page', 'wp_block'] as $builtin) {
            if (post_type_exists($builtin)) {
                $post_types[$builtin] = $builtin;
            }
        }

        return (new PostTypes())->apply_filters($post_types);
    }

}