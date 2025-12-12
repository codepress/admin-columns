<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Setting\Formatter;
use AC\Type\Value;

class PregReplace implements Formatter
{

    private array $patterns = [];

    private array $replacements = [];

    public function add_pattern(string $pattern, string $replacement): self
    {
        $this->patterns[] = $pattern;
        $this->replacements[] = $replacement;

        return $this;
    }

    public function replace_br(string $replacement = ', '): self
    {
        return $this->add_pattern('#<br\s*/?>#i', $replacement);
    }

    public function replace_new_line(string $replacement = ' '): self
    {
        return $this->add_pattern('/(\r\n|\r|\n)/', $replacement);
    }

    public function replace_tabs(string $replacement = ' '): self
    {
        return $this->add_pattern('/\t+/', $replacement);
    }

    public function replace_multiple_spaces(string $replacement = ' '): self
    {
        return $this->add_pattern('/\s{2,}/', $replacement);
    }

    public function format(Value $value): Value
    {
        if (empty($this->patterns)) {
            return $value;
        }

        $replaced_value = preg_replace($this->patterns, $this->replacements, (string)$value);

        return $value->with_value((string)$replaced_value);
    }

}