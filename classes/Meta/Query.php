<?php

namespace AC\Meta;

use WP_Meta_Query;

class Query
{

    private ?WP_Meta_Query $query = null;

    private string $sql;

    private array $select = [];

    private string $count = '';

    private bool $distinct = false;

    private string $join = '';

    private array $join_where = [];

    private array $where = [];

    private string $group_by = '';

    private array $order_by = [];

    private int $limit = 0;

    public function __construct(string $meta_type)
    {
        $this->set_query($meta_type);
    }

    /**
     * Add a single field or multiple comma separated
     *
     * @param string $field e.g. id or id, meta_value
     *
     * @return $this
     */
    public function select(string $field): self
    {
        $fields = explode(',', $field);

        foreach ($fields as $_field) {
            $this->select[] = trim($_field);
        }

        return $this;
    }

    /**
     * Add a COUNT clause AS count
     */
    public function count(string $field): self
    {
        $this->count = $field;

        return $this;
    }

    /**
     * Group by an aggregated column.
     * Supports: count
     *
     * @param string $field
     *
     * @return $this
     */
    public function group_by(string $field): self
    {
        $this->group_by = $field;

        return $this;
    }

    public function join(string $type = 'inner'): self
    {
        $this->join = strtoupper($type);

        return $this;
    }

    /**
     * @see get_where_clause()
     */
    public function join_where(string $field, string $operator = null, $value = null, string $boolean = 'AND'): self
    {
        // set default join
        if ( ! $this->join) {
            $this->join();
        }

        $this->join_where[] = $this->get_where_clause($field, $operator, $value, $boolean);

        return $this;
    }

    public function order_by(string $order_by, string $order = 'asc'): self
    {
        $parts = explode(',', $order_by);

        foreach ($parts as $_order_by) {
            $this->order_by[] = [
                'order_by' => trim($_order_by),
                'order'    => strtoupper($order),
            ];
        }

        return $this;
    }

    public function limit(int $limit): void
    {
        $this->limit = absint($limit);
    }

    public function distinct(): self
    {
        $this->distinct = true;

        return $this;
    }

    /**
     * Set a where clause
     *
     * @param string|array     $field
     * @param string|null      $operator
     * @param string|int|array $value
     * @param string           $boolean
     *
     * @return array
     */
    private function get_where_clause($field, string $operator = null, $value = null, string $boolean = 'AND'): array
    {
        // allows to omit operator
        if (null === $value) {
            $value = $operator;
            $operator = '=';
        }

        $where = [
            'nested'   => false,
            'boolean'  => strtoupper($boolean),
            'field'    => $field,
            'operator' => strtoupper($operator),
            'value'    => $value,
        ];

        // set default join
        if ($field === 'post_type' && ! $this->join) {
            $this->join();
        }

        $nested = [];

        if (is_array($field)) {
            $count = count($field);
            for ($i = 0; $i < $count; $i++) {
                $nested[] = array_pop($this->where);
            }
        }

        if ($nested) {
            $where['nested'] = true;
            $where['field'] = array_reverse($nested);
        }

        return $where;
    }

    /**
     * @see get_where_clause()
     */
    public function remove_where($field, string $operator = null, $value = null, string $boolean = 'AND'): self
    {
        $where = $this->get_where_clause($field, $operator, $value, $boolean);

        foreach ($this->where as $k => $v) {
            if ($v == $where) {
                unset($this->where[$k]);
            }
        }

        return $this;
    }

    /**
     * @see get_where_clause()
     */
    public function where($field, string $operator = null, $value = null, string $boolean = 'AND'): self
    {
        $this->where[] = $this->get_where_clause($field, $operator, $value, $boolean);

        return $this;
    }

    /**
     * @see get_where_clause()
     */
    public function or_where($field, string $operator = null, $value = null): self
    {
        return $this->where($field, $operator, $value, 'OR');
    }

    public function where_in(array $in): self
    {
        return $this->where('id', 'in', $in);
    }

    public function where_is_null($field): self
    {
        return $this->where($field, '', 'IS NULL');
    }

    public function where_post_type(string $post_type): self
    {
        return $this->where('post_type', '=', $post_type);
    }

    public function where_post_types(array $post_types): self
    {
        return $this->where('post_type', 'in', $post_types);
    }

    private function parse_field(string $field): string
    {
        switch ($field) {
            case 'id':
                $field = $this->join ? 'pt.' . $this->query->primary_id_column : 'mt' . $this->query->meta_id_column;

                break;
            case 'meta_key':
            case 'meta_value':
                $field = 'mt.' . $field;

                break;
            case 'taxonomy':
            case 'post_type':
                $field = 'pt.' . $field;

                break;
        }

        return $field;
    }

    private function parse_where(string $where, array $clauses): string
    {
        global $wpdb;

        foreach ($clauses as $clause) {
            if ($clause['nested']) {
                $clause['field'][0]['boolean'] = null;

                $where .= sprintf(' %s ( %s ) ', $clause['boolean'], $this->parse_where('', $clause['field']));
            } else {
                switch ($clause['operator']) {
                    case 'IN':
                        $clause['value'] = sprintf(' ( %s ) ', implode(', ', array_map('intval', $clause['value'])));

                        break;
                    default:
                        $valid_raw = ['IS NULL', 'IS NOT NULL'];

                        if ( ! in_array($clause['value'], $valid_raw)) {
                            $clause['value'] = $wpdb->prepare('%s', $clause['value']);
                        }
                }

                $clause['field'] = $this->parse_field($clause['field']);

                $where .= implode(' ', $clause);
            }
        }

        return $where;
    }

    public function get(): array
    {
        global $wpdb;

        if ( ! $this->query) {
            return [];
        }

        // parse SELECT
        $select = 'SELECT ';
        $select .= $this->distinct ? 'DISTINCT ' : '';

        if (empty($this->select)) {
            $this->select('id');
        }

        $fields = [];

        foreach ($this->select as $field) {
            $parsed = $this->parse_field($field);

            // output 'id' in the results
            if ('id' === $field) {
                $parsed .= ' AS id';
            }

            $fields[] = $parsed;
        }

        if ($this->count) {
            $fields[] = sprintf('COUNT(%s) AS count', $this->parse_field($this->count));
        }

        $select .= implode(', ', $fields);

        // parse FROM
        $from_tpl = ' FROM %s AS %s';

        $from = sprintf($from_tpl, $this->query->meta_table, 'mt');
        $join = '';

        if ($this->join) {
            $from = sprintf($from_tpl, $this->query->primary_table, 'pt');
            $join = sprintf(
                ' %s JOIN %s AS mt ON mt.%s = pt.%s %s',
                $this->join,
                $this->query->meta_table,
                $this->query->meta_id_column,
                $this->query->primary_id_column,
                $this->parse_where('', $this->join_where)
            );
        }

        // parse WHERE
        $where = $this->parse_where(' WHERE 1=1', $this->where);

        // parse GROUP BY
        $group_by = '';

        if ($this->group_by) {
            $group_by = ' GROUP BY ' . $this->parse_field($this->group_by);
        }

        // parse ORDER BY
        $order_by = '';

        if ( ! empty($this->order_by)) {
            $order_by_clauses = [];

            foreach ($this->order_by as $order_by_clause) {
                $order_by_clauses[] = $this->parse_field(
                        $order_by_clause['order_by']
                    ) . ' ' . $order_by_clause['order'];
            }

            $order_by = ' ORDER BY ' . implode(', ', $order_by_clauses);
        }

        $limit = '';

        if ($this->limit) {
            $limit = ' LIMIT ' . $this->limit;
        }

        // build query and store it
        $sql = $select . $from . $join . $where . $group_by . $order_by . $limit;

        $this->set_sql($sql);

        $results = $wpdb->get_results($sql);

        if ( ! is_array($results)) {
            return [];
        }

        $return = $results;

        if (count($fields) === 1) {
            $return = [];
            $field = $this->select[0];

            foreach ($results as $result) {
                $return[] = $result->$field;
            }
        }

        return $return;
    }

    /**
     * Return last SQL query that was queried
     */
    public function get_sql(): string
    {
        $sql = preg_replace('/ +/', ' ', $this->sql);
        $sql = preg_replace(
            '/(SELECT|FROM|LEFT|INNER|WHERE|(AND|OR) \(|(AND|OR) (?!\()|ORDER BY|GROUP BY|LIMIT)/',
            "\n$1",
            $sql
        );

        return $sql . "\n";
    }

    private function set_sql(string $sql): void
    {
        $this->sql = $sql;
    }

    public function get_query(): WP_Meta_Query
    {
        return $this->query;
    }

    private function set_query(string $type): void
    {
        global $wpdb;

        switch ($type) {
            case 'user':
                $table = $wpdb->users;
                $id = 'ID';

                break;
            case 'comment':
                $table = $wpdb->comments;
                $id = 'comment_ID';

                break;
            case 'post':
                $table = $wpdb->posts;
                $id = 'ID';

                break;
            case 'term':
                $table = $wpdb->terms;
                $id = 'term_id';

                break;

            default:
                return;
        }

        $this->query = new WP_Meta_Query();
        $this->query->get_sql($type, $table, $id);
    }

}