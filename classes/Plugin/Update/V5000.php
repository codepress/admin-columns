<?php

namespace AC\Plugin\Update;

use AC\Plugin\Install\Database;
use AC\Plugin\Update;
use AC\Plugin\Version;

// TODO add update logic and register class
class V5000 extends Update
{

    public const PROGRESS_KEY = 'ac_update_progress_v5000';

    private int $next_step;

    private Database $database;

    public function __construct(Database $database)
    {
        parent::__construct(new Version('5.0.0'));

        $this->database = $database;
        // because `get_option` could be cached we only fetch the next step from the DB on initialisation.
        $this->next_step = $this->get_next_step();
    }

    public function apply_update(): void
    {
        // just in case we need a bit of extra time to execute our upgrade script
        if (ini_get('max_execution_time') < 120) {
            @set_time_limit(120);
        }

        // Apply update in chunks to minimize the impact of a timeout.
        switch ($this->next_step) {
            case 1 :
                $this->update_database();

                $this->update_next_step(2)
                     ->apply_update();
                break;
            case 2:
                // TODO next step
                $this->update_next_step(3)
                     ->apply_update();
        }
    }

    private function get_next_step(): int
    {
        return (int)get_option(self::PROGRESS_KEY, 1);
    }

    private function update_next_step(int $step): self
    {
        $this->next_step = $step;

        update_option(self::PROGRESS_KEY, $this->next_step, false);

        return $this;
    }

    private function update_database(): void
    {
        $this->database->install();
    }

}