<?php

declare(strict_types=1);

namespace AC\Check\Integration;

use AC\Screen;

interface IntegrationNotice
{

    public function is_active(Screen $screen): bool;

    public function get_slug(): string;

    public function get_integration_slug(): string;

    public function get_eyebrow(): string;

    public function get_title(): string;

    public function get_description(): string;

    public function get_cta_label(): string;

    public function get_cta_url(): string;

    public function get_secondary_label(): string;

    public function get_secondary_url(): string;

    public function get_extra_classes(): string;

    public function get_delay_days(): int;

}
