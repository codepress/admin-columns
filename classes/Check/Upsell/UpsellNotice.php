<?php

declare(strict_types=1);

namespace AC\Check\Upsell;

use AC\Screen;

interface UpsellNotice
{

    public function is_active(Screen $screen): bool;

    public function get_slug(): string;

    public function get_integration_slug(): string;

    public function get_eyebrow(): string;

    public function get_title(): string;

    public function get_description(): string;

    /**
     * @return string[]
     */
    public function get_features(): array;

    public function get_cta_label(): string;

    public function get_cta_url(): string;

    public function get_secondary_label(): string;

    public function get_secondary_url(): string;

}
