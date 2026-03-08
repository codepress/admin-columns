<?php

declare(strict_types=1);

namespace AC\Notice\DismissHandler;

use AC\Notice\DismissHandler;
use AC\Preferences\Preference;

final class PreferenceDismiss implements DismissHandler
{

    private Preference $preference;

    private string $key;

    public function __construct(Preference $preference, string $key)
    {
        $this->preference = $preference;
        $this->key = $key;
    }

    public function dismiss(): void
    {
        $this->preference->save($this->key, true);
    }

}
