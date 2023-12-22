<?php

namespace AC\ApplyFilter;

class QueryTotalNumber
{

    public function apply_filter(int $limit = 250): int
    {
        return (int)apply_filters('ac/select/query/limit', $limit);
    }

}