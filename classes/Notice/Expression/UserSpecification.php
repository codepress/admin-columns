<?php

declare(strict_types=1);

namespace AC\Notice\Expression;

use AC\Expression\ComparisonOperators;
use AC\Expression\IntegerComparisonSpecification;

final class UserSpecification extends IntegerComparisonSpecification
{

    public function __construct(int $user_id)
    {
        parent::__construct(ComparisonOperators::EQUAL, $user_id);
    }

}