<?php

namespace {
    /**
     * Create plugin shortcodes.
     *
     * @package    Meta Box
     * @subpackage MB Relationships
     */
    /**
     * Shortcodes class.
     */
    class MBR_Shortcodes
    {
        /**
         * The relationship factory object.
         *
         * @var MBR_Relationship_Factory
         */
        protected $rel_factory;
        /**
         * The object factory.
         *
         * @var MBR_Object_Factory
         */
        protected $obj_factory;
        /**
         * MBR_Shortcodes constructor.
         *
         * @param MBR_Relationship_Factory $rel_factory The relationship factory object.
         * @param MBR_Object_Factory       $obj_factory The post object.
         */
        public function __construct(\MBR_Relationship_Factory $rel_factory, \MBR_Object_Factory $obj_factory)
        {
        }
        /**
         * Initialization.
         */
        public function init()
        {
        }
        /**
         * Render the shortcode.
         *
         * @param array $atts Shortcode attributes.
         *
         * @return string
         */
        public function render($atts)
        {
        }
    }
    /**
     * Plugin loader.
     *
     * @package    Meta Box
     * @subpackage MB Relationships
     */
    /**
     * The loader class.
     */
    class MBR_Loader
    {
        /**
         * Plugin activation.
         */
        public function activate()
        {
        }
        /**
         * Initialization.
         */
        public function init()
        {
        }
        /**
         * Create relationships table.
         */
        protected function create_table()
        {
        }
        /**
         * Load plugin files.
         */
        protected function load_files()
        {
        }
    }
    /**
     * Storage handler, which sets the correct storage for meta box objects.
     *
     * @package    Meta Box
     * @subpackage MB Relationships
     */
    /**
     * Storage handler class.
     */
    class MBR_Storage_Handler
    {
        /**
         * Reference to relationship factory.
         *
         * @var MBR_Relationship_Factory
         */
        protected $factory;
        /**
         * The storage object for relationships table.
         *
         * @var RWMB_Storage_Interface
         */
        protected $storage;
        /**
         * Constructor.
         *
         * @param MBR_Relationship_Factory $factory Reference to relationship factory.
         */
        public function __construct(\MBR_Relationship_Factory $factory)
        {
        }
        /**
         * Class initialize.
         */
        public function init()
        {
        }
        /**
         * Filter storage object.
         *
         * @param RWMB_Storage_Interface $storage     Storage object.
         * @param string                 $object_type Object type.
         * @param RW_Meta_Box            $meta_box    Meta box object.
         *
         * @return mixed
         */
        public function filter_storage($storage, $object_type, $meta_box)
        {
        }
        /**
         * Check if meta box is relationships.
         *
         * @param RW_Meta_Box $meta_box Meta box object.
         *
         * @return bool
         */
        protected function is_relationships($meta_box)
        {
        }
        /**
         * Delete object data in cache and in the database.
         *
         * @param int $object_id Object ID.
         */
        public function delete_object_data($object_id)
        {
        }
        /**
         * Delete all relationships to an object.
         *
         * @param int    $object_id ID of the object metadata is for.
         * @param string $type      The relationship type.
         * @param string $target    The relationship target.
         */
        protected function delete_object_relationships($object_id, $type, $target)
        {
        }
    }
    class MBR_Storage
    {
        public function __construct(\MBR_Relationship_Factory $factory)
        {
        }
        /**
         * Retrieve metadata for the specified object.
         *
         * @param int        $object_id ID of the object metadata is for. In this case, it will be a row's id
         *                              of table.
         * @param string     $meta_key  Optional. Metadata key. If not specified, retrieve all metadata for
         *                              the specified object. In this case, it will be column name.
         * @param bool|array $args      Optional, default is false.
         *                              If true, return only the first value of the specified meta_key.
         *                              If is array, use the `single` element.
         *                              This parameter has no effect if meta_key is not specified.
         *
         * @return mixed Single metadata value, or array of values.
         */
        public function get($object_id, $meta_key, $args = \false)
        {
        }
        /**
         * Add metadata to cache
         *
         * @param int    $object_id  ID of the object metadata is for.
         * @param string $meta_key   Metadata key.
         * @param mixed  $meta_value Metadata value. Must be serializable if non-scalar.
         * @param bool   $unique     Optional, default is false.
         *                           Whether the specified metadata key should be unique for the object.
         *                           If true, and the object already has a value for the specified metadata key,
         *                           no change will be made.
         */
        public function add($object_id, $meta_key, $meta_value, $unique = \false)
        {
        }
        /**
         * Update object relationships.
         *
         * @param int    $object_id  ID of the object metadata is for.
         * @param string $meta_key   Metadata key.
         * @param mixed  $meta_value Metadata value. Must be serializable if non-scalar.
         * @param mixed  $prev_value Optional. If specified, only update existing metadata entries with
         *                           the specified value. Otherwise, update all entries.
         *
         * @return bool
         */
        public function update($object_id, $meta_key, $meta_value, $prev_value = '')
        {
        }
        /**
         * Delete object relationships.
         *
         * @param int    $object_id  ID of the object metadata is for.
         * @param string $meta_key   Metadata key. If empty, delete row.
         * @param mixed  $meta_value Optional. Metadata value. Must be serializable if non-scalar. If specified, only delete
         *                           metadata entries with this value. Otherwise, delete all entries with the specified meta_key.
         *                           Pass `null, `false`, or an empty string to skip this check. (For backward compatibility,
         *                           it is not possible to pass an empty string to delete those entries with an empty string
         *                           for a value).
         * @param bool   $delete_all Optional, default is false. If true, delete matching metadata entries for all objects,
         *                           ignoring the specified object_id. Otherwise, only delete matching metadata entries for
         *                           the specified object_id.
         *
         * @return bool True on successful delete, false on failure.
         */
        public function delete($object_id, $meta_key = '', $meta_value = '', $delete_all = \false)
        {
        }
    }
    /**
     * Create tables for the plugin.
     *
     * @package    Meta Box
     * @subpackage MB Relationships
     */
    /**
     * The tables class
     */
    class MBR_Table
    {
        /**
         * Constructor.
         */
        public function __construct()
        {
        }
        /**
         * Create shared table for all relationships.
         */
        public function create()
        {
        }
    }
    /**
     * REST API to manage relationships via JSON API
     */
    class MB_Relationships_REST_API
    {
        /**
         * The namespace of this controller’s route.
         *
         * @var string
         */
        const NAMESPACE = 'mb-relationships/v1';
        public function init()
        {
        }
        public function register_routes()
        {
        }
        /**
         * API arguments.
         */
        public function relationship_args(): array
        {
        }
        /**
         * Arguments for the connected from API endpoint.
         */
        public function connected_from_relationship_args(): array
        {
        }
        /**
         * Arguments for the connected to API endpoint.
         */
        public function connected_to_relationship_args(): array
        {
        }
        /**
         * Additional arguments for the create API endpoint.
         */
        public function create_relationship_args(): array
        {
        }
        /**
         * Validate a request argument based on details registered to the route.
         *
         * @param  mixed           $value   Value of the 'filter' argument.
         * @param  WP_REST_Request $request The current request object.
         * @param  string          $param   Key of the parameter.
         * @return WP_Error|boolean
         */
        public function validate_integer($value, $request, $param)
        {
        }
        /**
         * Validate a request argument based on details registered to the route.
         *
         * @param  mixed           $value   Value of the 'filter' argument.
         * @param  WP_REST_Request $request The current request object.
         * @param  string          $param   Key of the parameter.
         * @return WP_Error|boolean
         */
        public function validate_relationship_id($value, $request, $param)
        {
        }
        /**
         * Determine whether the current user has permission to use the has_relationship endpoint.
         *
         * @return WP_Error|bool
         */
        public function read_relationship_permission()
        {
        }
        /**
         * Determine whether the current user has permission to use the create_relationship endpoint.
         *
         * @return WP_Error|bool
         */
        public function create_relationship_permission()
        {
        }
        /**
         * Determine whether the current user has permission to use the delete_relationship endpoint.
         *
         * @return WP_Error|bool
         */
        public function delete_relationship_permission()
        {
        }
        /**
         * Checks if the given from and to have a relationship for the given relationship ID.
         *
         * @param WP_REST_Request $request Request object.
         *
         * @return WP_REST_Response|WP_Error Response object on success or WP_Error object on failure.
         */
        public function has_relationship($request)
        {
        }
        /**
         * Returns objects connected to the specified from object.
         *
         * @param WP_REST_Request $request Request object.
         *
         * @return WP_REST_Response|WP_Error Response object on success or WP_Error object on failure.
         */
        public function connected_from_relationship($request)
        {
        }
        /**
         * Returns objects connected to the specified from object.
         *
         * @param WP_REST_Request $request Request object.
         *
         * @return WP_REST_Response|WP_Error Response object on success or WP_Error object on failure.
         */
        public function connected_to_relationship($request)
        {
        }
        /**
         * Creates a relationship.
         *
         * @param WP_REST_Request $request Request object.
         *
         * @return WP_REST_Response|WP_Error Response object on success or WP_Error object on failure.
         */
        public function create_relationship($request)
        {
        }
        /**
         * Deletes a relationship.
         *
         * @param WP_REST_Request $request Request object.
         *
         * @return WP_REST_Response|WP_Error Response object on success or WP_Error object on failure.
         */
        public function delete_relationship($request)
        {
        }
    }
    /**
     * The simple relationship factory.
     *
     * @package    Meta Box
     * @subpackage MB Relationships
     */
    /**
     * Relationship factory class.
     */
    class MBR_Relationship_Factory
    {
        /**
         * Constructor.
         *
         * @param MBR_Object_Factory $object_factory Reference to object factory.
         */
        public function __construct(\MBR_Object_Factory $object_factory)
        {
        }
        /**
         * Build a new relationship.
         *
         * @param array $settings Relationship settings.
         *
         * @return MBR_Relationship
         */
        public function build($settings)
        {
        }
        public function get($id)
        {
        }
        public function get_settings($id)
        {
        }
        public function all()
        {
        }
        public function all_settings()
        {
        }
        /**
         * Filter relationships by object type.
         *
         * @param string $type Object type.
         *
         * @return array
         */
        public function filter_by($type)
        {
        }
        /**
         * Check if relationship has an object type on either side.
         *
         * @param MBR_Relationship $relationship Relationship object.
         *
         * @return bool
         */
        protected function is_filtered(\MBR_Relationship $relationship)
        {
        }
        /**
         * Normalize relationship settings.
         *
         * @param array $settings Relationship settings.
         *
         * @return array
         */
        protected function normalize($settings)
        {
        }
        /**
         * Normalize settings for a "from" or "to" side.
         *
         * @param array|string $settings  Array of settings or post type (string) for short.
         */
        protected function normalize_side($settings, $label): array
        {
        }
    }
    class MBR_Admin_Columns
    {
        /**
         * Constructor.
         *
         * @param array              $settings       Relationship settings.
         * @param MBR_Object_Factory $object_factory The instance of the API class.
         */
        public function __construct($settings, \MBR_Object_Factory $object_factory)
        {
        }
        /**
         * Magic method to quick access to relationship settings.
         *
         * @param string $name Setting name.
         *
         * @return mixed
         */
        public function __get($name)
        {
        }
        /**
         * Setup hooks to create admin columns.
         */
        public function init()
        {
        }
        /**
         * Add admin columns for 'from' side.
         *
         * @param  array $columns Existing columns.
         * @return array
         */
        public function from_columns($columns)
        {
        }
        /**
         * Add admin columns for 'to' side.
         *
         * @param  array $columns Existing columns.
         * @return array
         */
        public function to_columns($columns)
        {
        }
        /**
         * Display column data for posts on 'from' side.
         *
         * @param  string $column    Column ID.
         * @param  int    $object_id Object ID.
         */
        public function post_from_column_data($column, $object_id)
        {
        }
        /**
         * Display column data for terms and users on 'from' side.
         *
         * @param  string $content   Content of the column.
         * @param  string $column    Column ID.
         * @param  int    $object_id Object ID.
         */
        public function from_column_data($content, $column, $object_id)
        {
        }
        /**
         * Display column data for posts on 'to' side.
         *
         * @param  string $column    Column ID.
         * @param  int    $object_id Object ID.
         */
        public function post_to_column_data($column, $object_id)
        {
        }
        /**
         * Display column data for terms and users on 'to' side.
         *
         * @param  string $content   Content of the column.
         * @param  string $column    Column ID.
         * @param  int    $object_id Object ID.
         */
        public function to_column_data($content, $column, $object_id)
        {
        }
    }
    class MBR_Admin_Filter
    {
        const LIMIT = 20;
        const LIMIT_LABEL_OPTION = 50;
        public function __construct()
        {
        }
        public function execute(): void
        {
        }
        public function add_filter_for_posts(): void
        {
        }
        public function filter_posts_by_relationships(\WP_Query $query): void
        {
        }
        public function enqueue_assets(): void
        {
        }
        /**
         * The ajax callback to search for related posts in the select2 fields
         */
        public function ajax_get_options(): void
        {
        }
    }
    /**
     * The meta boxes class.
     * Registers meta boxes for relationships objects.
     *
     * @package    Meta Box
     * @subpackage MB Relationships
     */
    /**
     * The meta boxes class.
     *
     * @property array  $from From side settings.
     * @property array  $to   To side settings.
     * @property string $id   Relationship ID.
     */
    class MBR_Meta_Boxes
    {
        /**
         * Constructor.
         *
         * @param array $settings Relationship settings.
         */
        public function __construct($settings)
        {
        }
        /**
         * Magic method to quick access to relationship settings.
         *
         * @param string $name Setting name.
         *
         * @return mixed
         */
        public function __get($name)
        {
        }
        /**
         * Setup hooks to create meta boxes for relationships, using Meta Box API.
         */
        public function init()
        {
        }
        /**
         * Register 2 meta boxes for "from" and "to" sides.
         *
         * @param array $meta_boxes Meta boxes array.
         *
         * @return array
         */
        public function register_meta_boxes($meta_boxes)
        {
        }
    }
    /**
     * The relationship class.
     * Registers meta boxes and custom fields for objects, displays and handles data.
     *
     * @package    Meta Box
     * @subpackage MB Relationships
     */
    /**
     * The relationship class.
     *
     * @property array  $from From side settings.
     * @property array  $to   To side settings.
     * @property string $id   Relationship ID.
     */
    class MBR_Relationship
    {
        /**
         * The relationship settings.
         *
         * @var array
         */
        protected $settings;
        /**
         * Register a relationship.
         *
         * @param array              $settings       Relationship settings.
         * @param MBR_Object_Factory $object_factory The instance of the API class.
         */
        public function __construct($settings, \MBR_Object_Factory $object_factory)
        {
        }
        /**
         * Magic method to quick access to relationship settings.
         *
         * @param string $name Setting name.
         *
         * @return mixed
         */
        public function __get($name)
        {
        }
        /**
         * Check if 2 objects has a relationship.
         *
         * @param int $from From object ID.
         * @param int $to   To object ID.
         *
         * @return bool
         */
        public function has($from, $to)
        {
        }
        /**
         * Add a relationship for 2 objects.
         *
         * @param int $from       From object ID.
         * @param int $to         To object ID.
         * @param int $order_from The order on the "from" side.
         * @param int $order_to   The order on the "to" side.
         *
         * @return bool
         */
        public function add($from, $to, $order_from, $order_to)
        {
        }
        /**
         * Delete a relationship for 2 objects.
         *
         * @param int $from From object ID.
         * @param int $to   To object ID.
         *
         * @return bool
         */
        public function delete($from, $to)
        {
        }
        /**
         * Get relationship object types.
         *
         * @param string $side "from" or "to".
         *
         * @return string
         */
        public function get_object_type($side)
        {
        }
        /**
         * Check if the relationship has an object type on either side.
         *
         * @param mixed $type Object type.
         *
         * @return bool
         */
        public function has_object_type($type)
        {
        }
        /**
         * Get the database ID field of "from" or "to" object.
         *
         * @param string $side "from" or "to".
         *
         * @return string
         */
        public function get_db_field($side)
        {
        }
    }
    /**
     * The simple object factory.
     *
     * @package    Meta Box
     * @subpackage MB Relationships
     */
    /**
     * Object factory class.
     */
    class MBR_Object_Factory
    {
        /**
         * For storing instances.
         *
         * @var array
         */
        protected $data = [];
        /**
         * Get object based on type.
         *
         * @param string $type Object type.
         *
         * @return MBR_Object_Interface
         */
        public function build($type)
        {
        }
    }
    /**
     * The interface for objects (posts, terms, users, etc.).
     *
     * @package    Meta Box
     * @subpackage MB Relationships
     */
    /**
     * Object interface.
     */
    interface MBR_Object_Interface
    {
        /**
         * Get current object ID in the admin area.
         *
         * @return int
         */
        public function get_current_admin_id();
        /**
         * Get current object ID.
         *
         * @return int
         */
        public function get_current_id();
        /**
         * Render HTML of the object to show in the frontend.
         *
         * @param mixed $item The object.
         *
         * @return string
         */
        public function render($item, $atts);
        /**
         * Get HTML link to the object.
         *
         * @param int $id Object ID.
         *
         * @return string
         */
        public function get_link($id);
        /**
         * Get database ID field.
         *
         * @return string
         */
        public function get_db_field();
    }
    /**
     * The post object that handle query arguments for "to" and list for "from" relationships.
     *
     * @package    Meta Box
     * @subpackage MB Relationships
     */
    /**
     * The post object.
     */
    class MBR_Post implements \MBR_Object_Interface
    {
        /**
         * Get current object ID.
         *
         * @return int
         */
        public function get_current_admin_id()
        {
        }
        /**
         * Get current object ID.
         *
         * @return int
         */
        public function get_current_id()
        {
        }
        /**
         * Get HTML link to the object.
         *
         * @param int $id Object ID.
         *
         * @return string
         */
        public function get_link($id)
        {
        }
        /**
         * Render HTML of the object to show in the frontend.
         *
         * @param WP_Post $item Post object.
         * @return string
         */
        public function render($item, $atts)
        {
        }
        /**
         * Render HTML of the object on the back end (admin column).
         *
         * @param WP_Post $item Post object.
         * @return string
         */
        public function render_admin($item, $config)
        {
        }
        /**
         * Get database ID field.
         *
         * @return string
         */
        public function get_db_field()
        {
        }
    }
    /**
     * The term object that handle query arguments for "to" and list for "from" relationships.
     *
     * @package    Meta Box
     * @subpackage MB Relationships
     */
    /**
     * The term object.
     */
    class MBR_Term implements \MBR_Object_Interface
    {
        /**
         * Get current object ID.
         *
         * @return int
         */
        public function get_current_admin_id()
        {
        }
        /**
         * Get current object ID.
         *
         * @return int
         */
        public function get_current_id()
        {
        }
        /**
         * Get HTML link to the object.
         *
         * @param int $id Object ID.
         *
         * @return string
         */
        public function get_link($id)
        {
        }
        /**
         * Render HTML of the object to show in the frontend.
         *
         * @param WP_Term $item Term object.
         *
         * @return string
         */
        public function render($item, $atts)
        {
        }
        /**
         * Render HTML of the object on the back end (admin column).
         *
         * @param WP_Post $item Post object.
         * @return string
         */
        public function render_admin($item, $config)
        {
        }
        /**
         * Get database ID field.
         *
         * @return string
         */
        public function get_db_field()
        {
        }
    }
    /**
     * The user object that handle query arguments for "to" and list for "from" relationships.
     *
     * @package    Meta Box
     * @subpackage MB Relationships
     */
    /**
     * The user object.
     */
    class MBR_User implements \MBR_Object_Interface
    {
        /**
         * Get current object ID.
         *
         * @return int
         */
        public function get_current_admin_id()
        {
        }
        /**
         * Get current object ID.
         *
         * @return int
         */
        public function get_current_id()
        {
        }
        /**
         * Get HTML link to the object.
         *
         * @param int $id Object ID.
         *
         * @return string
         */
        public function get_link($id)
        {
        }
        /**
         * Render HTML of the object to show in the frontend.
         *
         * @param WP_User $item User object.
         *
         * @return string
         */
        public function render($item, $atts)
        {
        }
        /**
         * Render HTML of the object on the back end (admin column).
         *
         * @param WP_Post $item Post object.
         * @return string
         */
        public function render_admin($item, $config)
        {
        }
        /**
         * Get database ID field.
         *
         * @return string
         */
        public function get_db_field()
        {
        }
    }
    /**
     * Public API helper functions.
     */
    class MB_Relationships_API
    {
        public static function set_relationship_factory(\MBR_Relationship_Factory $factory)
        {
        }
        public static function set_post_query(\MBR_Query_Post $post_query)
        {
        }
        public static function set_term_query(\MBR_Query_Term $term_query)
        {
        }
        public static function set_user_query(\MBR_Query_User $user_query)
        {
        }
        /**
         * Register a relationship.
         *
         * @param array $settings Relationship parameters.
         * @return MBR_Relationship
         */
        public static function register($settings)
        {
        }
        public static function get_relationship($id)
        {
        }
        public static function get_relationship_settings($id)
        {
        }
        public static function get_all_relationships()
        {
        }
        public static function get_all_relationships_settings()
        {
        }
        /**
         * Check if 2 objects has a relationship.
         *
         * @param int    $from From object ID.
         * @param int    $to   To object ID.
         * @param string $id   Relationship ID.
         * @return bool
         */
        public static function has($from, $to, $id)
        {
        }
        /**
         * Add a relationship for 2 objects.
         *
         * @param int    $from       From object ID.
         * @param int    $to         To object ID.
         * @param string $id         Relationship ID.
         * @param int    $order_from The order on the "from" side.
         * @param int    $order_to   The order on the "to" side.
         * @return bool
         */
        public static function add($from, $to, $id, $order_from = 1, $order_to = 1)
        {
        }
        /**
         * Delete a relationship for 2 objects.
         *
         * @param int    $from From object ID.
         * @param int    $to   To object ID.
         * @param string $id   Relationship ID.
         * @return bool
         */
        public static function delete($from, $to, $id)
        {
        }
        /**
         * Get connected items for each object in the list.
         *
         * @param array $args       Relationship query arguments.
         * @param array $query_vars Extra query variables.
         */
        public static function each_connected($args, $query_vars = [])
        {
        }
        /**
         * Get connected items.
         *
         * @param array $args Relationship arguments.
         * @return array
         */
        public static function get_connected($args)
        {
        }
    }
    /**
     * Query for related posts using WP_Query.
     *
     * @package    Meta Box
     * @subpackage MB Relationships
     */
    /**
     * Post query class.
     */
    class MBR_Query_Post
    {
        /**
         * Query normalizer.
         *
         * @var MBR_Query_Normalizer
         */
        protected $normalizer;
        /**
         * Constructor
         *
         * @param MBR_Query_Normalizer $normalizer Query normalizer.
         */
        public function __construct(\MBR_Query_Normalizer $normalizer)
        {
        }
        /**
         * Filter the WordPress query to get connected posts.
         */
        public function init()
        {
        }
        /**
         * Parse query variables.
         * Fires after the main query vars have been parsed.
         *
         * @param WP_Query $query The WP_Query instance (passed by reference).
         */
        public function parse_query(\WP_Query $query)
        {
        }
        /**
         * Filters all query clauses at once, for convenience.
         *
         * Covers the WHERE, GROUP BY, JOIN, ORDER BY, DISTINCT,
         * fields (SELECT), and LIMITS clauses.
         *
         * @param array    $clauses The list of clauses for the query.
         * @param WP_Query $query   The WP_Query instance (passed by reference).
         *
         * @return array
         */
        public function posts_clauses($clauses, \WP_Query $query)
        {
        }
        /**
         * Query and get list of items.
         *
         * @param array            $args         Relationship arguments.
         * @param array            $query_vars   Extra query variables.
         * @param MBR_Relationship $relationship Relationship object.
         *
         * @return array
         */
        public function query($args, $query_vars, $relationship)
        {
        }
    }
    /**
     * Normalizes the query arguments.
     *
     * @package    Meta Box
     * @subpackage MB Relationships
     */
    /**
     * Normalizer class.
     */
    class MBR_Query_Normalizer
    {
        /**
         * The relationship factory.
         *
         * @var MBR_Relationship_Factory
         */
        protected $factory;
        /**
         * Constructor
         *
         * @param MBR_Relationship_Factory $factory The relationship factory.
         */
        public function __construct(\MBR_Relationship_Factory $factory)
        {
        }
        /**
         * Normalize relationship query args.
         *
         * @param array $args Query arguments.
         */
        public function normalize(&$args)
        {
        }
        /**
         * Get object IDs from list of objects.
         *
         * @param array  $items    Array of objects or IDs.
         * @param string $id_field Object ID field.
         *
         * @return array
         */
        protected function get_ids($items, $id_field)
        {
        }
        /**
         * Normalizes single relationship query arguments.
         *
         * @param array $args Query arguments.
         */
        protected function normalize_args($args)
        {
        }
    }
    /**
     * Query for related terms using get_terms().
     *
     * @package    Meta Box
     * @subpackage MB Relationships
     */
    /**
     * Term query class.
     */
    class MBR_Query_Term
    {
        /**
         * Query normalizer.
         *
         * @var MBR_Query_Normalizer
         */
        protected $normalizer;
        /**
         * Constructor
         *
         * @param MBR_Query_Normalizer $normalizer Query normalizer.
         */
        public function __construct(\MBR_Query_Normalizer $normalizer)
        {
        }
        /**
         * Filter the WordPress query to get connected terms.
         */
        public function init()
        {
        }
        /**
         * Filters all query clauses at once, for convenience.
         *
         * Covers the WHERE, GROUP BY, JOIN, ORDER BY, DISTINCT,
         * fields (SELECT), and LIMITS clauses.
         *
         * @param array $clauses    Terms query SQL clauses.
         * @param array $taxonomies An array of taxonomies.
         * @param array $args       An array of terms query arguments.
         *
         * @return array
         */
        public function terms_clauses($clauses, $taxonomies, $args)
        {
        }
        /**
         * Query and get list of items.
         *
         * @param array            $args         Relationship arguments.
         * @param array            $query_vars   Extra query variables.
         * @param MBR_Relationship $relationship Relationship object.
         *
         * @return array
         */
        public function query($args, $query_vars, $relationship)
        {
        }
    }
    /**
     * Query for related users using WP_Query.
     *
     * @package    Meta Box
     * @subpackage MB Relationships
     */
    /**
     * Class MBR_Query_User
     */
    class MBR_Query_User
    {
        /**
         * Query normalizer.
         *
         * @var MBR_Query_Normalizer
         */
        protected $normalizer;
        /**
         * Constructor
         *
         * @param MBR_Query_Normalizer $normalizer Query normalizer.
         */
        public function __construct(\MBR_Query_Normalizer $normalizer)
        {
        }
        /**
         * Filter the WordPress query to get connected users.
         */
        public function init()
        {
        }
        /**
         * Parse query variables.
         * Fires after the main query vars have been parsed.
         *
         * @param WP_User_Query $query The current WP_User_Query instance, passed by reference.
         */
        public function parse_query(\WP_User_Query $query)
        {
        }
        /**
         * Query and get list of items.
         *
         * @param array            $args         Relationship arguments.
         * @param array            $query_vars   Extra query variables.
         * @param MBR_Relationship $relationship Relationship object.
         *
         * @return array
         */
        public function query($args, $query_vars, $relationship)
        {
        }
    }
    /**
     * The relationship query class that alters the WordPress query to get the connected items.
     */
    class MBR_Query
    {
        public function __construct($args)
        {
        }
        /**
         * Modify the WordPress query to get connected object.
         *
         * @param array  $clauses         Query clauses.
         * @param string $id_column       Database column for object ID.
         * @param bool   $pass_thru_order If TRUE use the WP_Query orderby clause.
         *
         * @return mixed
         */
        public function alter_clauses(&$clauses, $id_column, $pass_thru_order = \false)
        {
        }
        /**
         * Modify query JOIN statement. Do not support querying by multiple relationships.
         *
         * @param array  $clauses         Query clauses.
         * @param string $id_column       Database column for object ID.
         * @param bool   $pass_thru_order If TRUE use the WP_Query orderby clause.
         */
        public function handle_single_relationship_join(&$clauses, $id_column, $pass_thru_order)
        {
        }
        /**
         * Modify query to get sibling items. Do not support querying by multiple relationships.
         *
         * @param array  $clauses   Query clauses.
         * @param string $id_column Database column for object ID.
         */
        public function handle_single_relationship_sibling(&$clauses, $id_column)
        {
        }
        /**
         * Modify query join & where statement for multi-relationship.
         *
         * @param string $clauses    Query clauses.
         * @param string $id_column  ID column name.
         */
        public function handle_multiple_relationships(&$clauses, $id_column)
        {
        }
    }
}
namespace MetaBox\CustomTable {
    class API
    {
        /**
         * Create table, use dbDelta() function.
         *
         * @param string $table_name     Table name without prefix.
         * @param array  $columns        Table columns, is an array with key is column name
         *                               and value is column structure.
         * @param array  $keys           Table keys, is a numeric array contain key name and
         *                               column. Example: post_name (post_name).
         * @param bool   $auto_increment Deprecated.
         */
        public static function create(string $table, array $columns, array $keys = [], bool $auto_increment = false): void
        {
        }
        public static function exists(int $object_id, string $table): bool
        {
        }
        /**
         * Get a row from table.
         *
         * @param int    $object_id Row ID
         * @param string $table Table name
         * @param bool   $force Force to get from DB, not from cache
         *
         * @return mixed
         */
        public static function get(int $object_id, string $table, bool $force = true)
        {
        }
        /**
         * Set $object_id to null for auto-increment table (for models).
         *
         * @param ?int   $object_id
         * @param string $table
         *
         * @return bool|int Rows inserted.
         */
        public static function add(?int $object_id, string $table, array $row)
        {
        }
        /**
         * @return bool|int Rows affected.
         */
        public static function update(int $object_id, string $table, array $row)
        {
        }
        /**
         * Delete a row from table.
         *
         * @return bool|int Rows affected.
         */
        public static function delete(int $object_id, string $table)
        {
        }
        /**
         * Get value of a field.
         *
         * @return mixed
         */
        public static function get_value(string $field_id, int $object_id, string $table)
        {
        }
    }
}
namespace {
    class MB_Custom_Table_API extends \MetaBox\CustomTable\API
    {
    }
}
namespace MetaBox\CustomTable {
    class Loader
    {
        public function __construct()
        {
        }
        /**
         * Filter meta box class name for custom model.
         *
         * @param  string $class_name Meta box class name.
         * @param  array  $args       Meta box settings.
         * @return string
         */
        public function meta_box_class_name($class_name, $args)
        {
        }
        public function get_storage($storage, $object_type, $meta_box)
        {
        }
        /**
         * This function is called each time a meta box saves data.
         * To avoid updating multiple times, we need to run only when the last meta box saves data.
         */
        public function update_object_data($object_id)
        {
        }
        /**
         * This function is called by rwmb_set_meta hook.
         * To save data in cache to database.
         */
        public function flush_data($object_id, $field, $args = [])
        {
        }
        public function delete_object_data($object_id)
        {
        }
        public function delete_term_data(int $object_id, int $tt_id, string $taxonomy)
        {
        }
    }
    class Cache
    {
        /**
         * Get a row
         *
         * @param int|string|null $object_id Row ID
         * @param string          $table Table name
         * @param bool            $force Force to get from DB, not from cache
         *
         * @return array
         */
        public static function get($object_id, string $table, bool $force = false): array
        {
        }
        /**
         * Set a row to cache.
         */
        public static function set(int $object_id, string $table, array $row)
        {
        }
        public static function delete(?int $object_id, string $table)
        {
        }
    }
}
namespace MetaBox\CustomTable\Utils {
    /**
     * Resolve the callback paramters for class methods.
     * This helps user define the callback closure regardless the position of arguments.
     *
     * @since 1.0
     */
    class Resolver
    {
        /**
         * Bind parameter with the app variables.
         *
         * @var array
         */
        protected $bindMaps;
        /**
         * Bind parameter with the app variables.
         *
         * @param array $bind the key is the variable name and the value is the app variables.
         *
         * @return $this
         */
        public function bind(array $bind = [])
        {
        }
        /**
         * Resolve the closure and do the magic here
         *
         * @param mixed $closure
         *
         * @return mixed
         */
        public function resolve($closure)
        {
        }
    }
    class Helpers
    {
        public static function is_edit_screen(): bool
        {
        }
    }
}
namespace MetaBox\CustomTable {
    class Storage
    {
        public $table;
        /**
         * Retrieve metadata for the specified object.
         *
         * @param int        $object_id ID of the object metadata is for. In this case, it will be a row's id
         *                              of table.
         * @param string     $meta_key  Optional. Metadata key. If not specified, retrieve all metadata for
         *                              the specified object. In this case, it will be column name.
         * @param bool|array $args      Optional, default is false.
         *                              If true, return only the first value of the specified meta_key.
         *                              If is array, use the `single` element.
         *                              This parameter has no effect if meta_key is not specified.
         *
         * @return mixed Single metadata value, or array of values.
         */
        public function get($object_id, $meta_key, $args = false)
        {
        }
        /**
         * Add metadata to cache
         *
         * @param int    $object_id  ID of the object metadata is for.
         * @param string $meta_key   Metadata key.
         * @param mixed  $meta_value Metadata value. Must be serializable if non-scalar.
         * @param bool   $unique     Optional, default is false.
         *                           Whether the specified metadata key should be unique for the object.
         *                           If true, and the object already has a value for the specified metadata key,
         *                           no change will be made.
         *
         * @return bool
         */
        public function add($object_id, $meta_key, $meta_value, $unique = false)
        {
        }
        /**
         * Update metadata to cache.
         *
         * @param int    $object_id  ID of the object metadata is for.
         * @param string $meta_key   Metadata key.
         * @param mixed  $meta_value Metadata value. Must be serializable if non-scalar.
         * @param mixed  $prev_value Optional. If specified, only update existing metadata entries with
         *                           the specified value. Otherwise, update all entries.
         *
         * @return bool
         */
        public function update($object_id, $meta_key, $meta_value, $prev_value = '')
        {
        }
        /**
         * Delete metadata.
         *
         * @param int    $object_id  ID of the object metadata is for.
         * @param string $meta_key   Metadata key. If empty, delete row.
         * @param mixed  $meta_value Optional. Metadata value. Must be serializable if non-scalar. If specified, only delete
         *                           metadata entries with this value. Otherwise, delete all entries with the specified meta_key.
         *                           Pass `null, `false`, or an empty string to skip this check. (For backward compatibility,
         *                           it is not possible to pass an empty string to delete those entries with an empty string
         *                           for a value).
         * @param bool   $delete_all Optional, default is false. If true, delete matching metadata entries for all objects,
         *                           ignoring the specified object_id. Otherwise, only delete matching metadata entries for
         *                           the specified object_id.
         *
         * @return bool True on successful delete, false on failure.
         */
        public function delete($object_id, $meta_key = '', $meta_value = '', $delete_all = false)
        {
        }
        public function row_exists($object_id)
        {
        }
        public function update_row($object_id, $row)
        {
        }
        public function insert_row($row)
        {
        }
        public function delete_row($object_id)
        {
        }
    }
}
namespace MetaBox\CustomTable\Model {
    class Factory
    {
        public static function make($name, $args)
        {
        }
        public static function get($key = null)
        {
        }
        public static function add($key, $value)
        {
        }
    }
    class MetaBox extends \RW_Meta_Box
    {
        public function __construct($args)
        {
        }
        protected function object_hooks()
        {
        }
        public function enqueue($args = [])
        {
        }
        public function load()
        {
        }
        public function save_model()
        {
        }
        /**
         * Save model data from frontend submission.
         *
         * @return void
         */
        public function frontend_save_model($config)
        {
        }
        public function get_current_object_id()
        {
        }
        public function is_edit_screen($screen = null)
        {
        }
        public function register_fields()
        {
        }
    }
    class Ajax
    {
        public function __construct()
        {
        }
        public function bulk_actions()
        {
        }
        public function bulk_delete_bulk_action($ids, $model)
        {
        }
    }
    class ListTable extends \WP_List_Table
    {
        public function __construct($args)
        {
        }
        public function prepare_items()
        {
        }
        protected function extra_tablenav($which)
        {
        }
        public function get_columns()
        {
        }
        public function column_cb($item)
        {
        }
        public function column_id($item)
        {
        }
        public function column_default($item, $column_name)
        {
        }
        public function get_sortable_columns()
        {
        }
        protected function handle_row_actions($item, $column_name, $primary)
        {
        }
        public function get_bulk_actions()
        {
        }
    }
    class TableSchema
    {
        public function __construct(\MetaBox\CustomTable\Model\Model $model)
        {
        }
        public function modify_table_schema(string $table, array &$columns, array &$keys): void
        {
        }
    }
    class Model
    {
        public $name;
        public function __construct($name, $args)
        {
        }
        public function __get($name)
        {
        }
        public function __set($name, $value)
        {
        }
        public function supports(string $feature): bool
        {
        }
    }
    class SupportData
    {
        public function __construct(\MetaBox\CustomTable\Model\Model $model)
        {
        }
        public function add(array $row, ?int $object_id, string $table): array
        {
        }
        public function update(array $row, int $object_id, string $table): array
        {
        }
    }
    class Admin
    {
        public $list_table;
        public function __construct(\MetaBox\CustomTable\Model\Model $model)
        {
        }
        public function add_menu(): void
        {
        }
        public function add_body_class_hook(): void
        {
        }
        public function add_body_class(string $body_classes): string
        {
        }
        public function load_add_edit(): void
        {
        }
        public function render_submit_box(): void
        {
        }
        public function load_list_table()
        {
        }
        public function set_screen_option($status, $option, $value)
        {
        }
        public function enqueue()
        {
        }
        public function render()
        {
        }
    }
}
namespace {
    /**
     * The slider field which users jQueryUI slider widget.
     */
    class RWMB_Slider_Field extends \RWMB_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get div HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         *
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
    }
    /**
     * The WYSIWYG (editor) field.
     */
    class RWMB_Wysiwyg_Field extends \RWMB_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Change field value on save.
         *
         * @param mixed $new     The submitted meta value.
         * @param mixed $old     The existing meta value.
         * @param int   $post_id The post ID.
         * @param array $field   The field parameters.
         * @return string
         */
        public static function value($new, $old, $post_id, $field)
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
    }
    /**
     * The abstract input field which is used for all <input> fields.
     */
    abstract class RWMB_Input_Field extends \RWMB_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field Field parameters.
         * @param mixed $value Meta value.
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
        protected static function datalist(array $field): string
        {
        }
    }
    /**
     * The date and time picker field which allows users to select both date and time via jQueryUI datetime picker.
     */
    class RWMB_Datetime_Field extends \RWMB_Input_Field
    {
        /**
         * Translate date format from jQuery UI date picker to PHP date().
         * It's used to store timestamp value of the field.
         * Missing:  '!' => '', 'oo' => '', '@' => '', "''" => "'".
         *
         * @var array
         */
        protected static $date_formats = ['d' => 'j', 'dd' => 'd', 'oo' => 'z', 'D' => 'D', 'DD' => 'l', 'm' => 'n', 'mm' => 'm', 'M' => 'M', 'MM' => 'F', 'y' => 'y', 'yy' => 'Y', 'o' => 'z'];
        /**
         * Translate time format from jQuery UI time picker to PHP date().
         * It's used to store timestamp value of the field.
         * Missing: 't' => '', T' => '', 'm' => '', 's' => ''.
         *
         * @var array
         */
        protected static $time_formats = ['H' => 'G', 'HH' => 'H', 'h' => 'g', 'hh' => 'h', 'mm' => 'i', 'ss' => 's', 'l' => 'u', 'tt' => 'a', 'TT' => 'A'];
        public static function register_assets()
        {
        }
        /**
         * Enqueue scripts and styles.
         */
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  The field meta value.
         * @param array $field The field parameters.
         *
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Calculates the timestamp from the datetime string and returns it if $field['timestamp'] is set or the datetime string if not.
         *
         * @param mixed $new     The submitted meta value.
         * @param mixed $old     The existing meta value.
         * @param int   $post_id The post ID.
         * @param array $field   The field parameters.
         *
         * @return string|int
         */
        public static function value($new, $old, $post_id, $field)
        {
        }
        /**
         * Get meta value.
         *
         * @param int   $post_id The post ID.
         * @param bool  $saved   Whether the meta box is saved at least once.
         * @param array $field   The field parameters.
         *
         * @return mixed
         */
        public static function meta($post_id, $saved, $field)
        {
        }
        /**
         * Format meta value if set 'timestamp'.
         */
        public static function from_timestamp($meta, array $field): array
        {
        }
        /**
         * Transform meta value from save format to the JS format.
         */
        public static function from_save_format($meta, array $field): string
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field The field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field The field parameters.
         * @param mixed $value The meta value.
         *
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
        /**
         * Returns a date() compatible format string from the JavaScript format.
         * @link http://www.php.net/manual/en/function.date.php
         */
        protected static function get_php_format(array $js_options): string
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param string   $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
    }
    /**
     * The abstract choice field.
     */
    abstract class RWMB_Choice_Field extends \RWMB_Field
    {
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        public static function transform_options($options): array
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param string   $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
    }
    /**
     * The object choice class which allows users to select specific objects (post, user, taxonomy) in WordPress.
     */
    abstract class RWMB_Object_Choice_Field extends \RWMB_Choice_Field
    {
        /**
         * Show field HTML.
         * Populate field options before showing to make sure query is made only once.
         *
         * @param array $field   Field parameters.
         * @param bool  $saved   Whether the meta box is saved at least once.
         * @param int   $post_id Post ID.
         */
        public static function show(array $field, bool $saved, $post_id = 0)
        {
        }
        abstract public static function query($meta, array $field): array;
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        public static function add_new_form(array $field): string
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Set ajax parameters.
         *
         * @param array $field Field settings.
         */
        protected static function set_ajax_params(&$field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field Field parameters.
         * @param mixed $value Meta value.
         *
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get correct rendering class for the field.
         */
        protected static function get_type_class(array $field): string
        {
        }
    }
    /**
     * The taxonomy field which aims to replace the built-in WordPress taxonomy UI with more options.
     */
    class RWMB_Taxonomy_Field extends \RWMB_Object_Choice_Field
    {
        public static function add_actions()
        {
        }
        public static function ajax_get_terms()
        {
        }
        /**
         * Add default value for 'taxonomy' field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        public static function query($meta, array $field): array
        {
        }
        /**
         * Get meta values to save.
         *
         * @param mixed $new     The submitted meta value.
         * @param mixed $old     The existing meta value.
         * @param int   $post_id The post ID.
         * @param array $field   The field parameters.
         *
         * @return array
         */
        public static function value($new, $old, $post_id, $field)
        {
        }
        /**
         * Save meta value.
         *
         * @param mixed $new     The submitted meta value.
         * @param mixed $old     The existing meta value.
         * @param int   $post_id The post ID.
         * @param array $field   The field parameters.
         */
        public static function save($new, $old, $post_id, $field)
        {
        }
        /**
         * Add new terms if users created some.
         *
         * @param array $field Field settings.
         * @return int|null Term ID if added successfully, null otherwise.
         */
        protected static function add_term($field)
        {
        }
        /**
         * Get raw meta value.
         *
         * @param int   $object_id Object ID.
         * @param array $field     Field parameters.
         * @param array $args      Arguments of {@see rwmb_meta()} helper.
         *
         * @return mixed
         */
        public static function raw_meta($object_id, $field, $args = [])
        {
        }
        /**
         * Get the field value.
         * Return list of post term objects.
         *
         * @param  array $field   Field parameters.
         * @param  array $args    Additional arguments.
         * @param  ?int  $post_id Post ID.
         *
         * @return array List of post term objects.
         */
        public static function get_value($field, $args = [], $post_id = \null)
        {
        }
        /**
         * Format a single value for the helper functions.
         *
         * @param array   $field   Field parameters.
         * @param WP_Term $value   The term object.
         * @param array   $args    Additional arguments. Rarely used. See specific fields for details.
         * @param ?int    $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
        public static function add_new_form(array $field): string
        {
        }
        public static function admin_enqueue_scripts()
        {
        }
        protected static function remove_default_meta_box(array $field)
        {
        }
        protected static function get_taxonomy_singular_name(array $field): string
        {
        }
    }
    /**
     * The select field.
     */
    class RWMB_Select_Field extends \RWMB_Choice_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field Field parameters.
         * @param mixed $value Meta value.
         *
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
        /**
         * Get html for select all|none for multiple select.
         *
         * @param array $field Field parameters.
         * @return string
         */
        public static function get_select_all_html($field)
        {
        }
    }
    /**
     * The beautiful select field using select2 library.
     */
    class RWMB_Select_Advanced_Field extends \RWMB_Select_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field Field parameters.
         * @param mixed $value Meta value.
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
    }
    /**
     * The Button group.
     */
    class RWMB_Button_Group_Field extends \RWMB_Choice_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field Field parameters.
         * @param mixed $value Meta value.
         *
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
    }
    /**
     * The icon field.
     */
    class RWMB_Icon_Field extends \RWMB_Select_Advanced_Field
    {
        const CACHE_GROUP = 'meta-box-icon-field';
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Normalize field settings.
         *
         * @param array $field Field settings.
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Format value for the helper functions.
         *
         * @param array        $field   Field parameters.
         * @param string|array $value   The field meta value.
         * @param array        $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null     $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
    }
    /**
     * The file upload file which allows users to upload files via the default HTML <input type="file">.
     */
    class RWMB_File_Field extends \RWMB_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        public static function add_actions()
        {
        }
        public static function post_edit_form_tag()
        {
        }
        public static function ajax_delete_file()
        {
        }
        /**
         * Recursively search needle in haystack
         */
        protected static function in_array_r($needle, $haystack, $strict = \false): bool
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         *
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Get HTML for uploaded files.
         *
         * @param array $files List of uploaded files.
         * @param array $field Field parameters.
         * @return string
         */
        protected static function get_uploaded_files($files, $field)
        {
        }
        /**
         * Get HTML for uploaded file.
         *
         * @param int   $file  Attachment (file) ID.
         * @param int   $index File index.
         * @param array $field Field data.
         * @return string
         */
        protected static function file_html($file, $index, $field)
        {
        }
        protected static function file_info_custom_dir(string $file, array $field): array
        {
        }
        /**
         * Get meta values to save.
         *
         * @param mixed $new     The submitted meta value.
         * @param mixed $old     The existing meta value.
         * @param int   $post_id The post ID.
         * @param array $field   The field parameters.
         *
         * @return array|mixed
         */
        public static function value($new, $old, $post_id, $field)
        {
        }
        /**
         * Get meta values to save for cloneable fields.
         *
         * @param array $new         The submitted meta value.
         * @param array $old         The existing meta value.
         * @param int   $object_id   The object ID.
         * @param array $field       The field settings.
         * @param array $data_source Data source. Either $_POST or custom array. Used in group to get uploaded files.
         *
         * @return mixed
         */
        public static function clone_value($new, $old, $object_id, $field, $data_source = \null)
        {
        }
        /**
         * Handle file upload.
         * Consider upload to Media Library or custom folder.
         *
         * @param string $file_id File ID in $_FILES when uploading.
         * @param int    $post_id Post ID.
         * @param array  $field   Field settings.
         *
         * @return \WP_Error|int|string WP_Error if has error, attachment ID if upload in Media Library, URL to file if upload to custom folder.
         */
        protected static function handle_upload($file_id, $post_id, $field)
        {
        }
        /**
         * Transform $_FILES from $_FILES['field']['key']['index'] to $_FILES['field_index']['key'].
         *
         * @param string $input_name The field input name.
         *
         * @return int The number of uploaded files.
         */
        protected static function transform($input_name): int
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the field value. Return meaningful info of the files.
         *
         * @param  array    $field   Field parameters.
         * @param  array    $args    Not used for this field.
         * @param  int|null $post_id Post ID. null for current post. Optional.
         *
         * @return mixed Full info of uploaded files
         */
        public static function get_value($field, $args = [], $post_id = \null)
        {
        }
        /**
         * Get uploaded files information.
         *
         * @param array $field Field parameters.
         * @param array $files Files IDs.
         * @param array $args  Additional arguments (for image size).
         * @return array
         */
        public static function files_info($field, $files, $args)
        {
        }
        /**
         * Get uploaded file information.
         *
         * @param int   $file  Attachment file ID (post ID). Required.
         * @param array $args  Array of arguments (for size).
         * @param array $field Field settings.
         *
         * @return array|bool False if file not found. Array of (id, name, path, url) on success.
         */
        public static function file_info($file, $args = [], $field = [])
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param array    $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
        /**
         * Handle upload for files in custom directory.
         *
         * @param string $file_id File ID in $_FILES when uploading.
         * @param array  $field   Field settings.
         *
         * @return string URL to uploaded file.
         */
        public static function handle_upload_custom_dir($file_id, $field)
        {
        }
        public static function convert_path_to_url(string $path): string
        {
        }
    }
    /**
     * Media field class which users WordPress media popup to upload and select files.
     */
    class RWMB_Media_Field extends \RWMB_File_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get meta value.
         *
         * @param int   $post_id Post ID.
         * @param bool  $saved   Whether the meta box is saved at least once.
         * @param array $field   Field parameters.
         *
         * @return mixed
         */
        public static function meta($post_id, $saved, $field)
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         *
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field Field parameters.
         * @param mixed $value Meta value.
         *
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
        protected static function get_mime_extensions(): array
        {
        }
        /**
         * Get meta values to save.
         *
         * @param mixed $new     The submitted meta value.
         * @param mixed $old     The existing meta value.
         * @param int   $post_id The post ID.
         * @param array $field   The field parameters.
         *
         * @return array
         */
        public static function value($new, $old, $post_id, $field)
        {
        }
        /**
         * Save meta value.
         *
         * @param mixed $new     The submitted meta value.
         * @param mixed $old     The existing meta value.
         * @param int   $post_id The post ID.
         * @param array $field   The field parameters.
         */
        public static function save($new, $old, $post_id, $field)
        {
        }
    }
    /**
     * The advanced image upload field which uses WordPress media popup to upload and select images.
     */
    class RWMB_Image_Advanced_Field extends \RWMB_Media_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the field value.
         *
         * @param array $field   Field parameters.
         * @param array $args    Additional arguments.
         * @param ?int  $post_id Post ID.
         * @return mixed
         */
        public static function get_value($field, $args = [], $post_id = \null)
        {
        }
        /**
         * Get uploaded file information.
         *
         * @param int   $file  Attachment image ID (post ID). Required.
         * @param array $args  Array of arguments (for size).
         * @param array $field Field settings.
         *
         * @return array|bool False if file not found. Array of image info on success.
         */
        public static function file_info($file, $args = [], $field = [])
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param array    $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
    }
    /**
     * The advanced image upload field which uses WordPress media popup to upload and select images.
     */
    class RWMB_Single_Image_Field extends \RWMB_Image_Advanced_Field
    {
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get meta values to save.
         *
         * @param mixed $new     The submitted meta value.
         * @param mixed $old     The existing meta value.
         * @param int   $post_id The post ID.
         * @param array $field   The field parameters.
         *
         * @return array|mixed
         */
        public static function value($new, $old, $post_id, $field)
        {
        }
        /**
         * Get the field value. Return meaningful info of the files.
         *
         * @param  array    $field   Field parameters.
         * @param  array    $args    Not used for this field.
         * @param  int|null $post_id Post ID. null for current post. Optional.
         *
         * @return mixed Full info of uploaded files
         */
        public static function get_value($field, $args = [], $post_id = \null)
        {
        }
    }
    /**
     * The input list field which displays choices in a list of inputs.
     */
    class RWMB_Input_List_Field extends \RWMB_Choice_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field Field parameters.
         * @param mixed $value Meta value.
         *
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
        /**
         * Get html for select all|none for multiple checkbox.
         *
         * @param array $field Field parameters.
         * @return string
         */
        public static function get_select_all_html($field)
        {
        }
    }
    /**
     * The radio field.
     */
    class RWMB_Radio_Field extends \RWMB_Input_List_Field
    {
        public static function normalize($field)
        {
        }
    }
    /**
     * The time picker field.
     */
    class RWMB_Time_Field extends \RWMB_Datetime_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Returns a date() compatible format string from the JavaScript format.
         * @link http://www.php.net/manual/en/function.date.php
         */
        protected static function get_php_format(array $js_options): string
        {
        }
    }
    /**
     * The number field which uses HTML <input type="number">.
     */
    class RWMB_Number_Field extends \RWMB_Input_Field
    {
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field Field parameters.
         * @param mixed $value Meta value.
         *
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
    }
    /**
     * The file input field which allows users to enter a file URL or select it from the Media Library.
     */
    class RWMB_File_Input_Field extends \RWMB_Input_Field
    {
        /**
         * Enqueue scripts and styles.
         */
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         *
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field Field parameters.
         * @param mixed $value Meta value.
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
    }
    /**
     * The image upload field which allows users to drag and drop images.
     */
    class RWMB_Image_Upload_Field extends \RWMB_Image_Advanced_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
    }
    /**
     * The checkbox list field which shows a list of choices and allow users to select multiple options.
     */
    class RWMB_Checkbox_List_Field extends \RWMB_Input_List_Field
    {
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
    }
    /**
     * The post field which allows users to select existing posts.
     */
    class RWMB_Post_Field extends \RWMB_Object_Choice_Field
    {
        public static function add_actions()
        {
        }
        public static function ajax_get_posts()
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        public static function query($meta, array $field): array
        {
        }
        /**
         * Only search posts by title.
         * WordPress searches by either title or content which is confused when users can't find their posts.
         *
         * @link https://developer.wordpress.org/reference/hooks/posts_search/
         */
        public static function search_by_title($search, $wp_query)
        {
        }
        /**
         * Get meta value.
         * If field is cloneable, value is saved as a single entry in DB.
         * Otherwise value is saved as multiple entries (for backward compatibility).
         *
         * @see "save" method for better understanding
         *
         * @param int   $post_id Post ID.
         * @param bool  $saved   Is the meta box saved.
         * @param array $field   Field parameters.
         *
         * @return mixed
         */
        public static function meta($post_id, $saved, $field)
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array $field   Field parameters.
         * @param int   $value   The value.
         * @param array $args    Additional arguments. Rarely used. See specific fields for details.
         * @param ?int  $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
        public static function add_new_form(array $field): string
        {
        }
    }
    /**
     * The Google Maps field.
     */
    class RWMB_Map_Field extends \RWMB_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         *
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the field value.
         * The difference between this function and 'meta' function is 'meta' function always returns the escaped value
         * of the field saved in the database, while this function returns more meaningful value of the field.
         *
         * @param  array    $field   Field parameters.
         * @param  array    $args    Not used for this field.
         * @param  int|null $post_id Post ID. null for current post. Optional.
         *
         * @return mixed Array(latitude, longitude, zoom)
         */
        public static function get_value($field, $args = [], $post_id = \null)
        {
        }
        /**
         * Format value before render map
         * @param array $field    Field settings.
         * @param mixed $value    Field value.
         * @param mixed $args     Additional arguments.
         * @param mixed $post_id  Post ID.
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
        /**
         * Render a map in the frontend.
         *
         * @param string $location The "latitude,longitude[,zoom]" location.
         * @param array  $args     Additional arguments for the map.
         *
         * @return string
         */
        public static function render_map($location, $args = [])
        {
        }
    }
    /**
     * The divider field which displays a simple horizontal line.
     */
    class RWMB_Divider_Field extends \RWMB_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        protected static function begin_html(array $field): string
        {
        }
        public static function end_html(array $field): string
        {
        }
    }
    /**
     * The heading field which displays a simple heading text.
     */
    class RWMB_Heading_Field extends \RWMB_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        protected static function begin_html(array $field): string
        {
        }
        protected static function end_html(array $field): string
        {
        }
    }
    /**
     * This class implements common methods used in fields which have multiple values
     * like checkbox list, autocomplete, etc.
     *
     * The difference when handling actions for these fields are the way they get/set
     * meta value. Briefly:
     * - If field is cloneable, value is saved as a single entry in the database
     * - Otherwise value is saved as multiple entries
     */
    abstract class RWMB_Multiple_Values_Field extends \RWMB_Field
    {
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param string   $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
    }
    /**
     * The text list field which allows users to enter multiple texts.
     */
    class RWMB_Text_List_Field extends \RWMB_Multiple_Values_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         *
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Set value of meta before saving into database.
         * Do not save if all inputs has no value.
         *
         * @param mixed $new     The submitted meta value.
         * @param mixed $old     The existing meta value.
         * @param int   $post_id The post ID.
         * @param array $field   The field parameters.
         *
         * @return mixed
         */
        public static function value($new, $old, $post_id, $field)
        {
        }
        /**
         * Format value for the helper functions.
         *
         * @param array        $field   Field parameters.
         * @param string|array $value   The field meta value.
         * @param array        $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null     $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_value($field, $value, $args, $post_id)
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param array    $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
    }
    /**
     * The user select field.
     */
    class RWMB_User_Field extends \RWMB_Object_Choice_Field
    {
        public static function add_actions()
        {
        }
        public static function ajax_get_users()
        {
        }
        /**
         * Update object cache to make sure query method below always get the fresh list of users.
         * Unlike posts and terms, WordPress doesn't set 'last_changed' for users.
         * So we have to do it ourselves.
         *
         * @see clean_post_cache()
         */
        public static function update_cache()
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        public static function query($meta, array $field): array
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param int      $value   User ID.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
        public static function add_new_form(array $field): string
        {
        }
    }
    /**
     * The image select field which behaves similar to the radio field but uses images as options.
     */
    class RWMB_Image_Select_Field extends \RWMB_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field Field parameters.
         * @param mixed $value Meta value.
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param string   $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
    }
    /**
     * The background field.
     */
    class RWMB_Background_Field extends \RWMB_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field settings.
         *
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param array    $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
    }
    /**
     * The key-value field which allows users to add pairs of keys and values.
     */
    class RWMB_Key_Value_Field extends \RWMB_Input_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         *
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        protected static function begin_html(array $field): string
        {
        }
        protected static function input_description(array $field): string
        {
        }
        /**
         * Sanitize field value.
         *
         * @param mixed $new     The submitted meta value.
         * @param mixed $old     The existing meta value.
         * @param int   $post_id The post ID.
         * @param array $field   The field parameters.
         *
         * @return array
         */
        public static function value($new, $old, $post_id, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Format value for the helper functions.
         *
         * @param array        $field   Field parameters.
         * @param string|array $value   The field meta value.
         * @param array        $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null     $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_clone_value($field, $value, $args, $post_id)
        {
        }
    }
    /**
     * The secured password field.
     */
    class RWMB_Password_Field extends \RWMB_Input_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        public static function html($meta, $field)
        {
        }
        /**
         * Store secured password in the database.
         *
         * @param mixed $new     The submitted meta value.
         * @param mixed $old     The existing meta value.
         * @param int   $post_id The post ID.
         * @param array $field   The field parameters.
         * @return string
         */
        public static function value($new, $old, $post_id, $field)
        {
        }
    }
    /**
     * The date picker field, which uses built-in jQueryUI date picker widget.
     */
    class RWMB_Date_Field extends \RWMB_Datetime_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Returns a date() compatible format string from the JavaScript format.
         * @link http://www.php.net/manual/en/function.date.php
         */
        protected static function get_php_format(array $js_options): string
        {
        }
    }
    /**
     * The file upload field which allows users to drag and drop files to upload.
     */
    class RWMB_File_Upload_Field extends \RWMB_Media_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
    }
    /**
     * The color field which uses WordPress color picker to select a color.
     */
    class RWMB_Color_Field extends \RWMB_Input_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field Field parameters.
         * @param mixed $value Meta value.
         *
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param string   $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
    }
    /**
     * Video field which uses WordPress media popup to upload and select video.
     */
    class RWMB_Video_Field extends \RWMB_Media_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get uploaded file information.
         *
         * @param int   $file_id Attachment image ID (post ID). Required.
         * @param array $args    Array of arguments (for size).
         * @param array $field   Field settings.
         *
         * @return array|bool False if file not found. Array of image info on success.
         */
        public static function file_info($file_id, $args = [], $field = [])
        {
        }
        /**
         * Format value for a clone.
         *
         * @param array        $field   Field parameters.
         * @param string|array $value   The field meta value.
         * @param array        $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null     $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_clone_value($field, $value, $args, $post_id)
        {
        }
    }
    class RWMB_Block_Editor_Field extends \RWMB_Field
    {
        public static function admin_enqueue_scripts(): void
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get field HTML.
         *
         * @param string $meta  Meta value.
         * @param array  $field Field settings.
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Format the value on the front end.
         *
         * @param array  $field   Field settings.
         * @param string $value   The saved value.
         * @param array  $args    Additional arguments.
         * @param int    $post_id Current post ID.
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
        protected static function get_editor_settings(array $field): array
        {
        }
    }
    /**
     * The Switch field.
     */
    class RWMB_Switch_Field extends \RWMB_Input_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         *
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field The field parameters.
         * @param mixed $value The attribute value.
         *
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param string   $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
    }
    /**
     * The textarea field.
     */
    class RWMB_Textarea_Field extends \RWMB_Field
    {
        /**
         * Get field HTML.
         *
         * @param mixed $meta Meta value.
         * @param array $field Field parameters.
         *
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field Field parameters.
         * @param mixed $value Meta value.
         *
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
    }
    /**
     * The checkbox field.
     */
    class RWMB_Checkbox_Field extends \RWMB_Input_Field
    {
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        protected static function input_description(array $field): string
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param string   $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
    }
    /**
     * The HTML5 range field.
     */
    class RWMB_Range_Field extends \RWMB_Number_Field
    {
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Ensure number in range.
         *
         * @param mixed $new     The submitted meta value.
         * @param mixed $old     The existing meta value.
         * @param int   $post_id The post ID.
         * @param array $field   The field parameters.
         *
         * @return int
         */
        public static function value($new, $old, $post_id, $field)
        {
        }
    }
    /**
     * The select tree field.
     */
    class RWMB_Select_Tree_Field extends \RWMB_Select_Advanced_Field
    {
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field Field parameters.
         * @param mixed $value Meta value.
         *
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
    }
    /**
     * The autocomplete field.
     */
    class RWMB_Autocomplete_Field extends \RWMB_Multiple_Values_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         * @return string
         */
        public static function html($meta, $field)
        {
        }
    }
    /**
     * The oEmbed field which allows users to enter oEmbed URLs.
     */
    class RWMB_OEmbed_Field extends \RWMB_Input_Field
    {
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        public static function admin_enqueue_scripts()
        {
        }
        public static function add_actions()
        {
        }
        public static function ajax_get_embed()
        {
        }
        /**
         * Get embed html from url.
         *
         * @param string $url           URL.
         * @param string $not_available Not available string displayed to users.
         * @return string
         */
        public static function get_embed($url, $not_available = '')
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field Field parameters.
         * @param mixed $value Meta value.
         *
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param string   $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
    }
    /**
     * The Open Street Map field.
     */
    class RWMB_OSM_Field extends \RWMB_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         *
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the field value.
         * The difference between this function and 'meta' function is 'meta' function always returns the escaped value
         * of the field saved in the database, while this function returns more meaningful value of the field.
         *
         * @param  array    $field   Field parameters.
         * @param  array    $args    Not used for this field.
         * @param  int|null $post_id Post ID. null for current post. Optional.
         *
         * @return mixed Array(latitude, longitude, zoom)
         */
        public static function get_value($field, $args = [], $post_id = \null)
        {
        }
        /**
         * Format value before render map
         * @param array $field    Field settings.
         * @param mixed $value    Field value.
         * @param mixed $args     Additional arguments.
         * @param mixed $post_id  Post ID.
         * @return string HTML.
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
        /**
         * Render a map in the frontend.
         *
         * @param string|array $location The "latitude,longitude[,zoom]" location.
         * @param array        $args     Additional arguments for the map.
         *
         * @return string
         */
        public static function render_map($location, $args = [])
        {
        }
    }
    /**
     * Taxonomy advanced field which saves terms' IDs in the post meta in CSV format.
     */
    class RWMB_Taxonomy_Advanced_Field extends \RWMB_Taxonomy_Field
    {
        /**
         * Save terms in form of comma-separated IDs.
         *
         * @param mixed $new     The submitted meta value.
         * @param mixed $old     The existing meta value.
         * @param int   $post_id The post ID.
         * @param array $field   The field parameters.
         *
         * @return string
         */
        public static function value($new, $old, $post_id, $field)
        {
        }
        /**
         * Save meta value.
         *
         * @param mixed $new     The submitted meta value.
         * @param mixed $old     The existing meta value.
         * @param int   $post_id The post ID.
         * @param array $field   The field parameters.
         */
        public static function save($new, $old, $post_id, $field)
        {
        }
        /**
         * Get raw meta value.
         *
         * @param int   $object_id Object ID.
         * @param array $field     Field parameters.
         * @param array $args      Arguments of {@see rwmb_meta()} helper.
         *
         * @return mixed
         */
        public static function raw_meta($object_id, $field, $args = [])
        {
        }
        /**
         * Get the field value.
         * Return list of post term objects.
         *
         * @param  array    $field   Field parameters.
         * @param  array    $args    Additional arguments.
         * @param  int|null $post_id Post ID. null for current post. Optional.
         *
         * @return array List of post term objects.
         */
        public static function get_value($field, $args = [], $post_id = \null)
        {
        }
        /**
         * Get terms information.
         *
         * @param array  $field    Field parameters.
         * @param string $term_ids Term IDs, in CSV format.
         * @param array  $args     Additional arguments (for image size).
         *
         * @return array
         */
        public static function terms_info($field, $term_ids, $args)
        {
        }
    }
    /**
     * The text fieldset field, which allows users to enter content for a list of text fields.
     */
    class RWMB_Fieldset_Text_Field extends \RWMB_Input_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         *
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        protected static function input_description(array $field): string
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Format value for the helper functions.
         *
         * @param array        $field   Field parameters.
         * @param string|array $value   The field meta value.
         * @param array        $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null     $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_value($field, $value, $args, $post_id)
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param array    $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
        /**
         * Since we're using an array of text fields, we need to check if all of them are empty.
         * Otherwise, there is no way to know if the field is empty or not.
         */
        public static function value($new, $old, $post_id, $field)
        {
        }
    }
    /**
     * The sidebar select field.
     */
    class RWMB_Sidebar_Field extends \RWMB_Object_Choice_Field
    {
        public static function normalize($field)
        {
        }
        public static function query($meta, array $field): array
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param string   $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
    }
    /**
     * The image field which uploads images via HTML <input type="file">.
     */
    class RWMB_Image_Field extends \RWMB_File_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get HTML for uploaded file.
         *
         * @param int   $file  Attachment (file) ID.
         * @param int   $index File index.
         * @param array $field Field data.
         *
         * @return string
         */
        protected static function file_html($file, $index, $field)
        {
        }
        /**
         * Normalize field settings.
         *
         * @param array $field Field settings.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param array    $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
        /**
         * Get uploaded file information.
         *
         * @param int   $file  Attachment image ID (post ID). Required.
         * @param array $args  Array of arguments (for size).
         * @param array $field Field settings.
         *
         * @return array|bool False if file not found. Array of image info on success.
         */
        public static function file_info($file, $args = [], $field = [])
        {
        }
        /**
         * Get image meta data.
         *
         * @param  int $attachment_id Attachment ID.
         * @return array
         */
        protected static function get_image_meta_data($attachment_id)
        {
        }
    }
    /**
     * The button field. Simply displays a HTML button which might be used for JavaScript actions.
     */
    class RWMB_Button_Field extends \RWMB_Field
    {
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field The field parameters.
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field The field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field The field parameters.
         * @param mixed $value The attribute value.
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
    }
    /**
     * The custom HTML field which allows users to output any kind of content to the meta box.
     */
    class RWMB_Custom_Html_Field extends \RWMB_Field
    {
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         *
         * @return string
         */
        public static function html($meta, $field)
        {
        }
    }
}
namespace {
    function mb_register_model($name, $args)
    {
    }
    /**
     * Load plugin files after Meta Box is loaded
     */
    function mb_custom_table_load(): void
    {
    }
}