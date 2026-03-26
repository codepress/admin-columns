<?php

declare(strict_types=1);

namespace AC\Check\Integration;

/**
 * Detects if the user arrived from a post edit screen (post.php?action=edit).
 * Used by bulk edit notices to identify repetitive single-post editing behavior.
 */
trait PostEditReferrerAware
{

    private function is_post_edit_referrer(): bool
    {
        $referrer = $_SERVER['HTTP_REFERER'] ?? '';

        if (empty($referrer)) {
            return false;
        }

        $parsed = parse_url($referrer);
        $path = $parsed['path'] ?? '';
        $query = $parsed['query'] ?? '';

        if ('post.php' !== basename($path)) {
            return false;
        }

        parse_str($query, $params);

        return isset($params['action'], $params['post']) && 'edit' === $params['action'];
    }

}
