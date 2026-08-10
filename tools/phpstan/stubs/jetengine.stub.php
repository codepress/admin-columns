<?php

namespace Jet_Engine\Relations {
    /**
     * Arguments schema:
     *
     * 'parent_object'  => 'posts::page' - information about parent object in type::subtype format
     * 'child_object'   => 'posts::post' - information about child object in type::subtype format
     * 'parent_rel'     => null - ID of parent relation
     * 'type'           => 'one_to_one' - relation type, allowed values - 'one_to_one', 'one_to_many', 'many_to_many'
     * 'db_table'       => true - register or not separate DB table to store all related items
     * 'parent_control' => true - register or not control for related children on the parent object edit page
     * 'child_control'  => true - register or not control for related parents on the children objects edit page
     * 'parent_manager' => true - allow to create new objects of children type from parent object edit page
     * 'child_manager'  => true - allow to create new objects of parent type from children objects edit page
     * 'parent_allow_delete' => true - allow to delete objects of children type from parent object edit page
     * 'child_allow_delete'  => true - allow to delete objects of parent type from children objects edit page
     * 'parent_table' => array( 'image' => array( 'enable' => true, 'callback' => '', 'name' => 'Image' ) ) - additional table columns for parent object edit page control
     * 'child_table'  => array( 'image' => array( 'enable' => true, 'callback' => '', 'name' => 'Image' ) ) - additional table columns for child object edit page control
     * 'meta_fields'    => array() - list of meta fields for relation
     * 'id'             => $id - relation ID
     *
     */
    class Relation
    {
        protected $raw_args = array();
        protected $rel_id = array();
        protected $controls;
        protected $rel_cache_group = 'jet_engine_rel';
        protected $update_context = null;
        protected $control_context = null;
        protected $query_order = array();
        /**
         * @var Storage\DB
         */
        public $db;
        /**
         * @var Storage\DB
         */
        public $meta_db;
        /**
         * @param integer $rel_id relation ID
         * @param array   $args   relation arguments
         * @param boolean $silent is silent activation or not. if silent - only props will be filled, no hooks added
         */
        public function __construct($rel_id = 0, $args = array(), $silent = false)
        {
        }
        public function init_public_rest_api()
        {
        }
        /**
         * Check if this relations is can be correctly registered
         *
         * @return boolean [description]
         */
        public function is_valid()
        {
        }
        /**
         * Return object type class instance for specific relation side
         *
         * @param string $object
         * @return false|object
         */
        public function get_object_type_for($object = 'child_object')
        {
        }
        /**
         * Return object name for specific relation side
         *
         * @param string $object
         * @return string|false
         */
        public function get_object_name_for($object = 'child_object')
        {
        }
        /**
         * Register context for current relation into allowed context list
         *
         * @return [type] [description]
         */
        public function register_context($context)
        {
        }
        /**
         * Return object for relation context
         *
         * @return [type] [description]
         */
        public function apply_context()
        {
        }
        /**
         * Returns context name
         *
         * @return [type] [description]
         */
        public function get_context_name()
        {
        }
        /**
         * Return rel id
         *
         * @return [type] [description]
         */
        public function get_id()
        {
        }
        /**
         * Return raw arguments of relation intsance
         *
         * @return [type] [description]
         */
        public function get_args($key = false, $default = false)
        {
        }
        /**
         * Setup DB manager instance
         *
         * @return [type] [description]
         */
        public function setup_db()
        {
        }
        /**
         * Check if current relation can have only one child item
         *
         * @return boolean [description]
         */
        public function is_single_child()
        {
        }
        /**
         * Check if current relation can have only one parent item
         *
         * @return boolean [description]
         */
        public function is_single_parent()
        {
        }
        /**
         * Setup DB manager instance
         *
         * @return [type] [description]
         */
        public function setup_controls()
        {
        }
        /**
         * retyurns list of the fields for the create item control
         * @return [type] [description]
         */
        public function get_create_control_fields($for)
        {
        }
        /**
         * Returns new instance of controls class
         *
         * @param  [type] $class       [description]
         * @param  array  $object_data [description]
         * @param  string $type        [description]
         * @param  string $label       [description]
         * @return [type]              [description]
         */
        public function init_controls_class($class, $object_data = array())
        {
        }
        /**
         * Setup object controls
         *
         * @return [type] [description]
         */
        public function setup_object_controls($object, $type)
        {
        }
        /**
         * Initialize users control class
         * @return [type] [description]
         */
        public function init_users_controls_class($object_data)
        {
        }
        /**
         * Returns relation metafields
         *
         * @return [type] [description]
         */
        public function get_meta_fields($format = false, $filter = false, $return = ARRAY_N)
        {
        }
        /**
         * Register new column for current relation
         * @param [type] $key      [description]
         * @param [type] $callback [description]
         */
        public function add_table_column($object, $key, $name, $callback)
        {
        }
        /**
         * Returns available columns list fro given object
         *
         * @return [type] [description]
         */
        public function get_table_columns_for_object($object)
        {
        }
        /**
         * Check if given object has given columns enabled for edit page table of this object
         *
         * @param  [type] $object [description]
         * @param  [type] $column [description]
         * @return [type]         [description]
         */
        public function object_has_column($object, $column)
        {
        }
        /**
         * Returns information about given columns for given object
         *
         * @param  [type] $object [description]
         * @param  [type] $column [description]
         * @return [type]         [description]
         */
        public function get_object_column($object, $column = null)
        {
        }
        /**
         * Returns relation name by objects
         *
         * @return [type] [description]
         */
        public function get_relation_name()
        {
        }
        /**
         * Returns related items for given object
         * Automatically detects - we need to get children or parent items by object.
         *
         * @param  [type] $object [description]
         * @return [type]         [description]
         */
        public function get_related_items_for_object($object = null, $object_type = null)
        {
        }
        /**
         * Returns related children id/ids
         *
         * @param  [type] $parent_id [description]
         * @return [type]            [description]
         */
        public function get_children($parent_id, $fields = 'all')
        {
        }
        /**
         * Returns related parent id/ids
         *
         * @param  [type] $parent_id [description]
         * @return [type]            [description]
         */
        public function get_parents($child_id, $fields = 'all')
        {
        }
        /**
         * Returns related siblings list
         *
         * @param  [type] $object_id [description]
         * @param  [type] $from      [description]
         * @param  string $fields    [description]
         * @return [type]            [description]
         */
        public function get_siblings($object_id = null, $from = 'child_object', $fields = 'all')
        {
        }
        /**
         * Internal wrapper to db::query method
         *
         * @param  array  $query_args [description]
         * @return [type]             [description]
         */
        public function get_items($query_args = [])
        {
        }
        /**
         * Check if given object type and name combination is arent fro current relation
         *
         * @return boolean [description]
         */
        public function is_parent($type, $name)
        {
        }
        /**
         * Callback to remove related items on deletion of initial item from given object
         *
         * @param  [type] $object  [description]
         * @param  [type] $item_id [description]
         * @return [type]          [description]
         */
        public function cleanup_relation($object, $item_id)
        {
        }
        /**
         * Delete parent - child pair for current relation.
         *
         * if $parent_object is empty - will be deleted all rows for current relations which has $child_object
         * if $child_object is empty - will be deleted all rows for current relations which has $parent_object
         * if both empty - will be deleted all rows for current relation
         *
         * @param  [type]  $parent_object [description]
         * @param  [type]  $child_object  [description]
         * @return [type]                 [description]
         */
        public function delete_rows($parent_object = false, $child_object = false, $clear_meta = true)
        {
        }
        /**
         * Clean up meta fields which are exists in the DB
         * @param  boolean $meta_fields [description]
         * @return [type]               [description]
         */
        public function cleanup_meta($parent_object = null, $child_object = null, $meta_fields = false)
        {
        }
        /**
         * Update relation data in the DB
         *
         * @param  [type]  $parent_object [description]
         * @param  [type]  $child_object  [description]
         * @return [type]                 [description]
         */
        public function update_all_meta($new_meta = array(), $parent_object = null, $child_object = null)
        {
        }
        /**
         * Get formatted meta data output for editor
         *
         * @param  array  $meta [description]
         * @return [type]       [description]
         */
        public function format_meta($meta = array())
        {
        }
        /**
         * Sanitize meta field by field data
         *
         * @param  [type] $input [description]
         * @param  [type] $field [description]
         * @return [type]        [description]
         */
        public function sanitize_meta($input, $field)
        {
        }
        /**
         * Update meta for parent+child pair
         *
         * @param  [type] $parent_object [description]
         * @param  [type] $child_object  [description]
         * @param  [type] $meta_key      [description]
         * @param  string $meta_value    [description]
         * @return [type]                [description]
         */
        public function update_meta($parent_object, $child_object, $meta_key, $meta_value = '')
        {
        }
        /**
         * Delete meta row
         *
         * @param  [type] $parent_object [description]
         * @param  [type] $child_object  [description]
         * @param  [type] $meta_key      [description]
         * @return [type]                [description]
         */
        public function delete_meta($parent_object, $child_object = '', $meta_key = null)
        {
        }
        /**
         * Get meta value by key for parent+child pair
         *
         * @param  [type] $parent_object [description]
         * @param  [type] $child_object  [description]
         * @param  [type] $meta_key      [description]
         * @return [type]                [description]
         */
        public function get_meta($parent_object, $child_object, $meta_key)
        {
        }
        /**
         * Get all existing meta for parent+child pair
         *
         * @param  [type] $parent_object [description]
         * @param  [type] $child_object  [description]
         * @return [type]                [description]
         */
        public function get_all_meta($parent_object, $child_object = '')
        {
        }
        /**
         * Returns meta for current object
         *
         * @param  [type] $key [description]
         * @return [type]      [description]
         */
        public function get_current_meta($key)
        {
        }
        /**
         * Return WP cahce key for current relation parent+child pair
         *
         * @param  [type] $parent_object [description]
         * @param  [type] $child_object  [description]
         * @return [type]                [description]
         */
        public function get_cache_key($parent_object, $child_object, $custom_args = array())
        {
        }
        /**
         * Update relation data in the DB
         *
         * @param  [type]  $parent_object [description]
         * @param  [type]  $child_object  [description]
         * @return [type]                 [description]
         */
        public function update($parent_object, $child_object)
        {
        }
        public function flush_cache($parent_object, $child_object)
        {
        }
        public function reset_db_cache()
        {
        }
        /**
         * Reset current update context.
         * Should be called after each relation update to avoid contexts overlaping
         * @return [type] [description]
         */
        public function reset_update_context()
        {
        }
        /**
         * Set current update context.
         * Should be called before relation update to specify in what context we updating it - add parents from child or vice versa
         * parent - means we seeting up parent related items for the child object (update initiated from child object)
         * child  - means we seeting up children related items for the parent object (update initiated from parent object)
         *
         * @return [type] [description]
         */
        public function set_update_context($context)
        {
        }
        /**
         * Get current update context
         *
         * @return [type] [description]
         */
        public function get_update_context()
        {
        }
    }
    /**
     * JetSmartFilters compatibility class
     */
    class Filters
    {
        public function __construct()
        {
        }
        /**
         * Printe helper notes for relations on filters edit page
         *
         * @return [type] [description]
         */
        public function helper_notes()
        {
        }
        /**
         * Admin dynamic query for JSF query variable
         */
        public function helper_dynamic_query($dynamic_query_manager)
        {
        }
        /**
         * Check if relations was requested for indexing - tries to get posts for relations
         *
         * @param  [type] $result   [description]
         * @param  [type] $metadata [description]
         * @return [type]           [description]
         */
        public function index_posts_relations($result, $metadata)
        {
        }
        /**
         * Check if relations was requested for indexing - tries to get users for relations
         *
         * @param  [type] $result   [description]
         * @param  [type] $metadata [description]
         * @return [type]           [description]
         */
        public function index_users_relations($result, $metadata)
        {
        }
        /**
         * Re-index relations indexer data on each update of post or user
         *
         * @param  [type] $result      [description]
         * @param  [type] $filter_data [description]
         * @return [type]              [description]
         */
        public function index_single_item($result, $filters_data, $type)
        {
        }
        /**
         * Check if relation object mets indexer type requirements
         *
         * @return boolean [description]
         */
        public function is_supported_type($type, $object)
        {
        }
        /**
         * Returns related items data for indexer
         *
         * @param  [type] $metadata [description]
         * @param  [type] $type     [description]
         * @return [type]           [description]
         */
        public function get_related_meta($metadata = array(), $type = 'post', $flush = false)
        {
        }
        /**
         * Add relation query arguments
         *
         * @param [type] $args [description]
         */
        public function add_relation_query($args)
        {
        }
        /**
         * Prevent relation meta keys from indexing with default CCT logic
         *
         * @param  [type] $result [description]
         * @param  [type] $key    [description]
         * @return [type]         [description]
         */
        public function prevent_default_indexing_cct_relations($result, $key)
        {
        }
        /**
         * Index CCT relations meta
         *
         * @param  [type] $data       [description]
         * @param  [type] $provider   [description]
         * @param  [type] $query_args [description]
         * @param  [type] $indexer    [description]
         * @return [type]             [description]
         */
        public function index_cct_relations($data, $provider, $query_args, $indexer)
        {
        }
        /**
         * Extract relation ID from key
         * @param  [type] $key [description]
         * @return [type]      [description]
         */
        public function get_relation_id_from_key($key)
        {
        }
        /**
         * Check if given filter key is relation-associated key
         *
         * @param  [type]  $key [description]
         * @return boolean      [description]
         */
        public function is_relation_filter($key)
        {
        }
        /**
         * Chek if is children related items filter
         *
         * @param  [type]  $key [description]
         * @return boolean      [description]
         */
        public function is_children_filter($key)
        {
        }
        /**
         * Chek if is parents related items filter
         *
         * @param  [type]  $key [description]
         * @return boolean      [description]
         */
        public function is_parents_filter($key)
        {
        }
        /**
         * Adds relation query arguments to existing filter query args
         *
         * @param [type] $args [description]
         * @param [type] $data [description]
         */
        public function add_relation_args($args, $data, $is_inner = false)
        {
        }
    }
}
namespace Jet_Engine\Relations\Types {
    abstract class Base
    {
        /**
         * Returns type name
         * @return [type] [description]
         */
        abstract public function get_name();
        /**
         * Returns type label
         * @return [type] [description]
         */
        abstract public function get_label();
        /**
         * Returns subtypes list
         * @return [type] [description]
         */
        abstract public function get_object_names();
        /**
         * Returns type items
         * @return [type] [description]
         */
        abstract public function get_items($object_name, $relation);
        /**
         * Retrieves the title of a specific type item.
         *
         * @param string                         $item_id     Item ID.
         * @param string                         $object_name Object name (post/user/etc.).
         * @param \Jet_Engine\Relations\Relation $relation    Relation instance.
         *
         * @return string The title of the specified item.
         */
        abstract public function get_type_item_title($item_id, $object_name, $relation);
        /**
         * Returns item edit URL by object type data and item ID
         *
         * @param string   $item_id     Item ID.
         * @param string   $object_name Object name (post/user/etc.).
         *
         * @param \Jet_Engine\Relations\Relation $relation Relation instance.
         *
         * @return string Item edit link.
         */
        abstract public function get_type_item_edit_url($item_id, $object_name, $relation);
        /**
         * Returns item view URL by object type data and item ID
         *
         * @param  [type] $type    [description]
         * @param  [type] $item_id [description]
         * @return [type]          [description]
         */
        abstract public function get_type_item_view_url($item_id, $object_name, $relation);
        /**
         * Returns query type for current relation type.
         * Used for match relations query type with appropriate query builder query type.
         *
         * @return string
         */
        public function get_query_type()
        {
        }
        /**
         * Delete given item.
         * By default not allowed, should be set for each type individually with appropriate capability check
         *
         * @param  [type] $item_id [description]
         * @return [type]          [description]
         */
        public function delete_item($item_id, $object_name)
        {
        }
        /**
         * Checkk type specific user capabilities
         *
         * @return [type] [description]
         */
        public function current_user_can($cap, $item_id, $object_name)
        {
        }
        /**
         * Returns fields list required to create item of given type
         *
         * @param  [type] $object_name [description]
         * @return [type]       [description]
         */
        public function get_create_control_fields($object_name, $relation)
        {
        }
        /**
         * Create new item of given typer by given data
         *
         * @return [type] [description]
         */
        public function create_item($data, $object_name)
        {
        }
        /**
         * Check if $object is belongs to current type
         *
         * @param  [type]  $object      [description]
         * @param  [type]  $object_name [description]
         * @return boolean              [description]
         */
        public function is_object_of_type($object, $object_name)
        {
        }
        /**
         * Returns object of current type by item ID of this object
         *
         * @return [type] [description]
         */
        public function get_object_by_id($item_id, $object_name)
        {
        }
        /**
         * Sanitize type-specific arguments of relation on edit.
         * Is placeholder method, by default returs input data without changes.
         * Rewrite this method in the child class if you pass any additional controls into relation.
         *
         * @param  array  $final_args   [description]
         * @param  array  $request_data [description]
         * @return [type]               [description]
         */
        public function sanitize_relation_edit_args($final_args = array(), $request_data = array())
        {
        }
        public function filtered_arg($object_name = '')
        {
        }
        /**
         * Ensure the \Jet_Engine\Relations\Types\Type_Query class is loaded.
         *
         * @return void
         */
        public function ensure_type_query_classs()
        {
        }
        /**
         * Perform a query for the current type by given arguments.
         *
         * The arguments array should contain `related_items_ids` key which holds
         * actual IDs of related items. Arguments should be formatted according
         * to the current type logic.
         *
         * @param array                            $args        Query arguments.
         * @param string                           $object_name Object name (post type/user/etc.).
         * @param \Jet_Engine\Relations\Relation $relation    Relation instance.
         *
         * @return array
         */
        abstract public function query($args, $object_name, $relation);
        /**
         * Return JetSmartFilters-prepared query arguments array of given ids for given object type
         *
         * @return array()
         */
        public function filtered_query_args($ids = array(), $object_name = '')
        {
        }
        /**
         * Return JetSmartFilters-prepared query arguments array of given ids for given object type
         *
         * @return array()
         */
        public function merge_filtered_query_args($args = array(), $new_args = array(), $object_name = '')
        {
        }
        /**
         * Register appropriate cleanup hook for current type items.
         * This hook should be called on deletion of item of current type and call clean up method from relation
         * See the default types for examples.
         *
         * @param  string $object_name [description]
         * @param  [type] $callback    [description]
         * @return [type]              [description]
         */
        public function register_cleanup_hook($object_name = '', $callback = null, $type_name = '')
        {
        }
    }
    class Mix extends \Jet_Engine\Relations\Types\Base
    {
        /**
         * Returns type name
         * @return [type] [description]
         */
        public function get_name()
        {
        }
        /**
         * Returns type label
         * @return [type] [description]
         */
        public function get_label()
        {
        }
        /**
         * Returns subtypes list
         * @return [type] [description]
         */
        public function get_object_names()
        {
        }
        /**
         * Checkk type specific user capabilities
         *
         * @return [type] [description]
         */
        public function current_user_can($cap, $item_id, $object_name)
        {
        }
        /**
         * Returns type items
         * @return [type] [description]
         */
        public function get_items($object_name, $relation)
        {
        }
        /**
         * Returns type items
         * @return [type] [description]
         */
        public function get_type_item_title($item_id, $object_name, $relation)
        {
        }
        /**
         * Returns item edit URL by object type data and item ID
         */
        public function get_type_item_edit_url($item_id, $object_name, $relation)
        {
        }
        /**
         * Returns item view URL by object type data and item ID
         *
         * @param  [type] $type    [description]
         * @param  [type] $item_id [description]
         * @return [type]          [description]
         */
        public function get_type_item_view_url($item_id, $object_name, $relation)
        {
        }
        /**
         * Trash given post
         *
         * @return [type] [description]
         */
        public function delete_item($item_id, $object_name)
        {
        }
        /**
         * Returns fields list required to create item of given type
         *
         * @param  [type] $object_name [description]
         * @return [type]       [description]
         */
        public function get_create_control_fields($object_name, $relation)
        {
        }
        /**
         * Returns available user roles list to use as options
         *
         * @return array
         */
        public function get_user_roles()
        {
        }
        /**
         * Create new item of given typer by given data
         *
         * @return [type] [description]
         */
        public function create_item($data, $object_name)
        {
        }
        /**
         * Query items of mix type by provided arguments.
         *
         * @param array                            $args        Query arguments.
         * @param string                           $object_name Object name.
         * @param \Jet_Engine\Relations\Relation $relation    Relation instance.
         *
         * @return array
         */
        public function query($args, $object_name, $relation)
        {
        }
        /**
         * Returns object of current type by item ID of this object
         *
         * @return [type] [description]
         */
        public function get_object_by_id($item_id, $object_name)
        {
        }
        /**
         * Check if $object is belongs to current type
         *
         * @param  [type]  $object      [description]
         * @param  [type]  $object_name [description]
         * @return boolean              [description]
         */
        public function is_object_of_type($object, $object_name)
        {
        }
        public function filtered_arg($object_name = '')
        {
        }
        /**
         * Return JetSmartFilters-prepared query arguments array of given ids for given object type
         *
         * @return array()
         */
        public function filtered_query_args($ids = array(), $object_name = '')
        {
        }
        /**
         * Register appropriate cleanup hook for current type items.
         * This hook should be called on deletion of item of current type and call clean up method from relation
         * See the default types for examples.
         *
         * @param  string $object_name [description]
         * @param  [type] $callback    [description]
         * @return [type]              [description]
         */
        public function register_cleanup_hook($object_name = '', $callback = null, $type_name = '')
        {
        }
    }
    class Terms extends \Jet_Engine\Relations\Types\Base
    {
        /**
         * Returns type name
         * @return [type] [description]
         */
        public function get_name()
        {
        }
        /**
         * Returns type label
         * @return [type] [description]
         */
        public function get_label()
        {
        }
        /**
         * Returns subtypes list
         * @return [type] [description]
         */
        public function get_object_names()
        {
        }
        /**
         * Checkk type specific user capabilities
         *
         * @return [type] [description]
         */
        public function current_user_can($cap, $item_id, $object_name)
        {
        }
        /**
         * Returns type items
         * @return [type] [description]
         */
        public function get_items($object_name, $relation)
        {
        }
        /**
         * Returns type items
         */
        public function get_type_item_title($item_id, $object_name, $relation)
        {
        }
        /**
         * Returns item edit URL by object type data and item ID
         */
        public function get_type_item_edit_url($item_id, $object_name, $relation)
        {
        }
        /**
         * Returns item view URL by object type data and item ID
         *
         * @param  [type] $type    [description]
         * @param  [type] $item_id [description]
         * @return [type]          [description]
         */
        public function get_type_item_view_url($item_id, $object_name, $relation)
        {
        }
        /**
         * Trash given post
         *
         * @return [type] [description]
         */
        public function delete_item($item_id, $object_name)
        {
        }
        /**
         * Returns fields list required to create item of given type
         *
         * @param  [type] $object_name [description]
         * @return [type]       [description]
         */
        public function get_create_control_fields($object_name, $relation)
        {
        }
        /**
         * Create new item of given typer by given data
         *
         * @return [type] [description]
         */
        public function create_item($data, $object_name)
        {
        }
        /**
         * Query terms of given taxonomy by provided arguments.
         *
         * @param array                            $args        Query arguments.
         * @param string                           $object_name Taxonomy slug.
         * @param \Jet_Engine\Relations\Relation $relation    Relation instance.
         *
         * @return array
         */
        public function query($args, $object_name, $relation)
        {
        }
        /**
         * Returns object of current type by item ID of this object
         *
         * @return [type] [description]
         */
        public function get_object_by_id($item_id, $object_name)
        {
        }
        /**
         * Check if $object is belongs to current type
         *
         * @param  [type]  $object      [description]
         * @param  [type]  $object_name [description]
         * @return boolean              [description]
         */
        public function is_object_of_type($object, $object_name)
        {
        }
        public function filtered_arg($object_name = '')
        {
        }
        /**
         * Register appropriate cleanup hook for current type items.
         * This hook should be called on deletion of item of current type and call clean up method from relation
         * See the default types for examples.
         *
         * @param  string $object_name [description]
         * @param  [type] $callback    [description]
         * @return [type]              [description]
         */
        public function register_cleanup_hook($object_name = '', $callback = null, $type_name = '')
        {
        }
    }
    /**
     * Type_Query class, which provides a standardized way to communicate with results of the query()
     * method from Jet_Engine\Relations\Types object.
     */
    class Type_Query
    {
        protected $items = array();
        protected $total_count = 0;
        protected $final_query = array();
        public function __construct($items = array(), $total_count = 0, $final_query = array())
        {
        }
        public function get_items()
        {
        }
        public function get_total_count()
        {
        }
        public function get_final_query()
        {
        }
    }
    class Posts extends \Jet_Engine\Relations\Types\Base
    {
        /**
         * Returns type name
         * @return [type] [description]
         */
        public function get_name()
        {
        }
        /**
         * Returns type label
         * @return [type] [description]
         */
        public function get_label()
        {
        }
        /**
         * Returns subtypes list
         * @return [type] [description]
         */
        public function get_object_names()
        {
        }
        /**
         * Checkk type specific user capabilities
         *
         * @return [type] [description]
         */
        public function current_user_can($cap, $item_id, $object_name)
        {
        }
        /**
         * Returns type items
         * @return [type] [description]
         */
        public function get_items($object_name, $relation)
        {
        }
        /**
         * Returns type items
         */
        public function get_type_item_title($item_id, $object_name, $relation)
        {
        }
        /**
         * Returns item edit URL by object type data and item ID
         */
        public function get_type_item_edit_url($item_id, $object_name, $relation)
        {
        }
        /**
         * Returns item view URL by object type data and item ID
         *
         * @param  [type] $type    [description]
         * @param  [type] $item_id [description]
         * @return [type]          [description]
         */
        public function get_type_item_view_url($item_id, $object_name, $relation)
        {
        }
        /**
         * Trash given post
         *
         * @return [type] [description]
         */
        public function delete_item($item_id, $object_name)
        {
        }
        /**
         * Returns fields list required to create item of given type
         *
         * @param  [type] $object_name [description]
         * @return [type]       [description]
         */
        public function get_create_control_fields($object_name, $relation)
        {
        }
        /**
         * Create new item of given typer by given data
         *
         * @return [type] [description]
         */
        public function create_item($data, $object_name)
        {
        }
        /**
         * Query posts of given post type by provided arguments.
         *
         * @param array                            $args        Query arguments.
         * @param string                           $object_name Post type name.
         * @param \Jet_Engine\Relations\Relation $relation    Relation instance.
         *
         * @return object
         */
        public function query($args, $object_name, $relation)
        {
        }
        /**
         * Returns object of current type by item ID of this object
         *
         * @return [type] [description]
         */
        public function get_object_by_id($item_id, $object_name)
        {
        }
        /**
         * Check if $object is belongs to current type
         *
         * @param  [type]  $object      [description]
         * @param  [type]  $object_name [description]
         * @return boolean              [description]
         */
        public function is_object_of_type($object, $object_name)
        {
        }
        public function filtered_arg($object_name = '')
        {
        }
        /**
         * Register appropriate cleanup hook for current type items.
         * This hook should be called on deletion of item of current type and call clean up method from relation
         * See the default types for examples.
         *
         * @param  string $object_name [description]
         * @param  [type] $callback    [description]
         * @return [type]              [description]
         */
        public function register_cleanup_hook($object_name = '', $callback = null, $type_name = '')
        {
        }
    }
}
namespace Jet_Engine\Relations {
    /**
     * Sources manager.
     * Keep information about all sources where we can get initial IDs for relation objects
     */
    class Sources
    {
        /**
         * Add available sources list
         * @return [type] [description]
         */
        public function get_sources()
        {
        }
        /**
         * Get realtions sources as array of objects with format {value:...,label:...}
         *
         * @return array
         */
        public function get_sources_for_js()
        {
        }
        /**
         * Get object ID by source
         *
         * @param  string $source [description]
         * @param  string $var    [description]
         * @return [type]         [description]
         */
        public function get_id_by_source($source = '', $var = '')
        {
        }
        /**
         * Returns object of given type from stack
         *
         * @param  [type] $object_type [description]
         * @return [type]              [description]
         */
        public function get_object_from_stack($type)
        {
        }
        /**
         * Returns obhject of given type by item ID of this object
         *
         * @param  [type] $type    [description]
         * @param  [type] $item_id [description]
         * @return [type]          [description]
         */
        public function get_source_object_by_id($type, $item_id)
        {
        }
    }
}
namespace Jet_Engine\Relations\Forms\Jet_Engine_Forms {
    /**
     * Define Notification class
     */
    class Preset
    {
        public $preset_source = null;
        public function __construct()
        {
        }
        /**
         * Sanitize preset source
         *
         * @param  [type] $res    [description]
         * @param  [type] $preset [description]
         * @return [type]         [description]
         */
        public function sanitize_source($res, $preset)
        {
        }
        /**
         * Apply form preset
         *
         * @param  [type] $value      [description]
         * @param  [type] $field_data [description]
         * @param  [type] $args       [description]
         * @param  [type] $source     [description]
         * @return [type]             [description]
         */
        public function apply_preset($value, $field_data, $args, $source)
        {
        }
        /**
         * Register preset source
         *
         * @param  [type] $sources [description]
         * @return [type]          [description]
         */
        public function register_source($sources)
        {
        }
        /**
         * Custom controls for the specific source
         *
         * @return [type] [description]
         */
        public function preset_controls_source()
        {
        }
        /**
         * Show notice for global preset controls
         *
         * @return [type] [description]
         */
        public function preset_controls_global()
        {
        }
        /**
         * Show notice for single field map controls
         *
         * @return [type] [description]
         */
        public function preset_controls_field()
        {
        }
    }
    class Manager
    {
        public function __construct()
        {
        }
        /**
         * Initialize JetEngine forms notification
         *
         * @return [type] [description]
         */
        public function init_notification()
        {
        }
        /**
         * Initialize JetEngine forms preset
         *
         * @return [type] [description]
         */
        public function init_preset()
        {
        }
    }
    /**
     * Define Notification class
     */
    class Notification
    {
        public $slug = null;
        public function __construct()
        {
        }
        /**
         * Register notification assets
         * @return [type] [description]
         */
        public function assets()
        {
        }
        /**
         * Print notification component template
         *
         * @return [type] [description]
         */
        public function notification_component_template()
        {
        }
        /**
         * Register new notification type
         *
         * @return [type] [description]
         */
        public function register_notification($notifications)
        {
        }
        /**
         * Render additional notification fields
         *
         * @return [type] [description]
         */
        public function notification_fields()
        {
        }
        /**
         * Handle form notification
         *
         * @return [type] [description]
         */
        public function handle_notification($args, $notifications)
        {
        }
    }
}
namespace Jet_Engine\Relations\Forms {
    class Manager
    {
        /**
         * relation slug
         *
         * @var string
         */
        public $slug = 'connect_relation_items';
        public function __construct()
        {
        }
        /**
         * Initialize JetEngine forms compatibility
         *
         * @return [type] [description]
         */
        public function init_jet_engine_forms()
        {
        }
        /**
         * Returns notification slug
         *
         * @return [type] [description]
         */
        public function slug()
        {
        }
        public function action_title()
        {
        }
        /**
         * Update related item from form action/notification
         *
         * @param  array  $args [description]
         * @return [type]       [description]
         */
        public function update_related_items($args = array())
        {
        }
        /**
         * Returns allowed sources for object IDs in preset
         *
         * @return [type] [description]
         */
        public function get_preset_id_sources()
        {
        }
        /**
         * Update related item from form action/notification
         *
         * @param  array  $args [description]
         * @return [type]       [description]
         */
        public function get_preset_items($args = array())
        {
        }
        /**
         * Returns the instance.
         *
         * @since  1.0.0
         * @access public
         * @return Jet_Engine
         */
        public static function instance()
        {
        }
    }
}
namespace Jet_Engine\Relations\Forms\Jet_Form_Builder_Forms {
    class Preset
    {
        public function __construct()
        {
        }
        public function preset_controls($config)
        {
        }
    }
    class Action extends \Jet_Form_Builder\Actions\Types\Base
    {
        public function get_id()
        {
        }
        public function get_name()
        {
        }
        /**
         * @param array $request
         * @param \Jet_Form_Builder\Actions\Action_Handler $handler
         *
         * @return void
         * @throws \Jet_Form_Builder\Exceptions\Action_Exception
         */
        public function do_action(array $request, \Jet_Form_Builder\Actions\Action_Handler $handler)
        {
        }
        public function self_script_name()
        {
        }
        public function editor_labels()
        {
        }
        public function action_data()
        {
        }
    }
    class Preset_Source extends \Jet_Form_Builder\Presets\Sources\Base_Source
    {
        public function get_id()
        {
        }
        public function is_need_prop()
        {
        }
        /**
         * @return false
         * @throws \Jet_Form_Builder\Exceptions\Preset_Exception
         */
        public function query_source()
        {
        }
    }
    class Actions_Manager
    {
        public function __construct()
        {
        }
        public function editor_assets()
        {
        }
    }
    class Manager
    {
        public function __construct()
        {
        }
    }
}
namespace Jet_Engine\Relations\Traits {
    trait Related_Items_By_Args
    {
        /**
         * Get relation object by args
         *
         * @param array $args
         * @return false|object
         */
        public function get_relation($args = [])
        {
        }
        /**
         * Return related items IDs by given arguments.
         * Allows to use the same logic to get related items accross different instances
         * by using the same arguments structure.
         *
         * @param array $args
         * @return array
         */
        public function get_related_items($args = [])
        {
        }
    }
}
namespace Jet_Engine\Relations\Dynamic_Tags {
    class Related_Item_Meta extends \Elementor\Core\DynamicTags\Data_Tag
    {
        public function get_name()
        {
        }
        public function get_title()
        {
        }
        public function get_group()
        {
        }
        public function get_categories()
        {
        }
        public function is_settings_required()
        {
        }
        protected function register_controls()
        {
        }
        public function get_value(array $options = array())
        {
        }
    }
    class Related_Items extends \Elementor\Core\DynamicTags\Data_Tag
    {
        public function get_name()
        {
        }
        public function get_title()
        {
        }
        public function get_group()
        {
        }
        public function get_categories()
        {
        }
        public function is_settings_required()
        {
        }
        protected function register_controls()
        {
        }
        public function get_value(array $options = array())
        {
        }
    }
    class Related_Siblings extends \Jet_Engine\Relations\Dynamic_Tags\Related_Items
    {
        public function get_name()
        {
        }
        public function get_title()
        {
        }
        protected function register_controls()
        {
        }
        public function get_value(array $options = array())
        {
        }
    }
    class Related_Items_Count extends \Elementor\Core\DynamicTags\Tag
    {
        public function get_name()
        {
        }
        public function get_title()
        {
        }
        public function get_group()
        {
        }
        public function get_categories()
        {
        }
        public function is_settings_required()
        {
        }
        protected function register_controls()
        {
        }
        public function render(array $options = array())
        {
        }
    }
}
namespace Jet_Engine\Relations {
    /**
     * Define Relations Manager class
     */
    class Manager extends \Jet_Engine_Base_WP_Intance
    {
        /**
         * Base slug for CPT-related pages
         * @var string
         */
        public $page = 'jet-engine-relations';
        /**
         * Action request key
         *
         * @var string
         */
        public $action_key = 'cpt_relation_action';
        /**
         * Set object type
         * @var string
         */
        public $object_type = '';
        /**
         * Active relations list
         *
         * @var Relation[]
         */
        public $_active_relations = array();
        /**
         * Legacy relations instance
         * @var Legacy\Manager
         */
        public $legacy = null;
        /**
         * Storage-related manager instance
         * @var Storage\Manager
         */
        public $storage = null;
        /**
         * Listings integration manager
         *
         * @var Listing
         */
        public $listing = null;
        /**
         * Sources manager instance
         *
         * @var Sources
         */
        public $sources = null;
        /**
         * Hierarchy manager instance
         *
         * @var Hierarchy
         */
        public $hierachy = null;
        /**
         * Types helper instance
         *
         * @var Types_Helper
         */
        public $types_helper = null;
        /**
         * Constructor for the class
         */
        function __construct()
        {
        }
        /**
         * Register relations related macros
         *
         * @return [type] [description]
         */
        public function register_macros()
        {
        }
        /**
         * [register_elementor_dynamic_tags description]
         * @return [type] [description]
         */
        public function register_elementor_dynamic_tags($dynamic_tags, $tags_module)
        {
        }
        /**
         * Initiizlize post type specific API endpoints
         *
         * @param  Jet_Engine_REST_API $api_manager API manager instance.
         * @return void
         */
        public function init_rest($api_manager)
        {
        }
        /**
         * Return allowed relations types
         *
         * @return array
         */
        public function get_relations_types()
        {
        }
        /**
         * Returns allowed relations types prepared to use in JS formst
         *
         * @return [type] [description]
         */
        public function get_relations_types_for_js()
        {
        }
        /**
         * Get relations for JS
         * @return [type] [description]
         */
        public function get_relations_for_js($raw = false, $placeholder = false)
        {
        }
        /**
         * Returns allowed meta fields list of all existing relation or for requested relation if $relation_id parameter was passed
         *
         * @return [type] [description]
         */
        public function get_active_relations_meta_fields($relation_id = false)
        {
        }
        /**
         * Return path to file inside component
         *
         * @param  [type] $path_inside_component [description]
         * @return [type]                        [description]
         */
        public function component_path($path_inside_component)
        {
        }
        /**
         * Return URL to file inside component
         *
         * @param  [type] $path_inside_component [description]
         * @return [type]                        [description]
         */
        public function component_url($path_inside_component)
        {
        }
        /**
         * Init data instance
         *
         * @return [type] [description]
         */
        public function init_data()
        {
        }
        /**
         * Initizlize 3rd party compatibility classes
         *
         * @return [type] [description]
         */
        public function init_3rd_party()
        {
        }
        /**
         * Register relations meta boxes
         *
         * @return void
         */
        public function register_instances()
        {
        }
        /**
         * Flush cache after relation(meta) update / delete
         *
         * @return void
         */
        public function flush_cache()
        {
        }
        /**
         * Register legacy relations instances
         *
         * @param  array  $legacy_relations [description]
         * @return [type]                   [description]
         */
        public function register_legacy_relations($legacy_relations = array())
        {
        }
        /**
         * Add active relation to relations list
         */
        public function add_active_relation($rel_id, $relation_instance)
        {
        }
        /**
         * Returns active relations list
         *
         * @return [type] [description]
         */
        public function get_active_relations($rel_id = false)
        {
        }
        /**
         * Return admin pages for current instance
         *
         * @return array
         */
        public function get_instance_pages()
        {
        }
        /**
         * Enqueue reindex script and vraibles for it
         * @return [type] [description]
         */
        public function enqueue_reindex($id = false)
        {
        }
        /**
         * Returns current menu page title (for JetEngine submenu)
         * @return [type] [description]
         */
        public function get_page_title()
        {
        }
        /**
         * Returns current instance slug
         *
         * @return [type] [description]
         */
        public function instance_slug()
        {
        }
        /**
         * Returns default config for add/edit page
         *
         * @param  array  $config [description]
         * @return [type]         [description]
         */
        public function get_admin_page_config($config = array())
        {
        }
        /**
         * Returns list of supported field types for relations builder admin UI
         *
         * @return [type] [description]
         */
        public function field_types_supports()
        {
        }
        /**
         * Legacy part to ensure 3rd parties used jet_engine()->relations->... methods will continue to work properly
         */
        public function is_relation_key($key)
        {
        }
        public function get_relation_info($key)
        {
        }
        public function get_related_posts($args = array())
        {
        }
        public function process_meta($result = null, $post_id = null, $meta_key = '', $related_posts = array())
        {
        }
    }
}
namespace Jet_Engine\Relations\Legacy {
    /**
     * Define Jet_Engine_Relations_Legacy class
     */
    class Manager extends \Jet_Engine\Relations\Manager
    {
        /**
         * Legacy relations list
         */
        public $legacy_relations = array();
        public $hierarchy = null;
        public $convert = null;
        public $_active_relations = array();
        public $_relations_for_post_types = array();
        /**
         * Constructor for the class
         */
        function __construct()
        {
        }
        public function set_legacy_relations($relations = array())
        {
        }
        public function get_legacy_relations()
        {
        }
        /**
         * Enqueue relations assets to posts edit screen
         *
         * @param  [type] $hook [description]
         * @return [type]       [description]
         */
        public function relations_box_assets($hook)
        {
        }
        /**
         * Returns unique relation name for post types pair
         *
         * @param  [type] $post_type_1 [description]
         * @param  [type] $post_type_2 [description]
         * @return [type]              [description]
         */
        public function get_relation_hash($post_type_1, $post_type_2)
        {
        }
        /**
         * Register relations meta boxes
         *
         * @return void
         */
        public function register_instances()
        {
        }
        /**
         * Store relation meta keys for post type
         *
         * @param string $meta_key [description]
         * @param array  $relation [description]
         */
        public function add_relation_to_post_types($meta_key = null, $relation = array())
        {
        }
        /**
         * Get values for relations meta fields early
         *
         * @param  [type] $result  [description]
         * @param  [type] $post    [description]
         * @param  [type] $key     [description]
         * @param  [type] $default [description]
         * @param  [type] $field   [description]
         * @return [type]          [description]
         */
        public function get_meta($result, $post, $key, $default, $field)
        {
        }
        /**
         * Check if is relation meta key
         *
         * @param  [type]  $meta_key [description]
         * @return boolean           [description]
         */
        public function is_relation_key($meta_key = null)
        {
        }
        /**
         * Returns active relations list
         *
         * @return [type] [description]
         */
        public function get_active_relations($rel_id = false)
        {
        }
        /**
         * Synchronize related meta on post save
         *
         * @return void
         */
        public function process_meta($result = null, $post_id = null, $meta_key = '', $related_posts = array())
        {
        }
        /**
         * Returns relation meta keys for passed post type
         *
         * @param  string $post_type Post type name
         * @return array
         */
        public function get_relation_fields_for_post_type($post_type = null)
        {
        }
        /**
         * Returns info about relationby hash
         */
        public function get_relation_info($key)
        {
        }
        /**
         * Returns related posts
         *
         * @return void
         */
        public function get_related_posts($args = array())
        {
        }
        /**
         * Clear relations meta on delete a relation post.
         *
         * @param $post_id
         */
        public function clear_relations_meta_on_delete_post($post_id)
        {
        }
    }
    /**
     * Define Jet_Engine_Relations_Hierarchy class
     */
    class Hierarchy
    {
        /**
         * Constructor for the class
         */
        public function __construct()
        {
        }
        public function test_relations()
        {
        }
        /**
         * Add %get_grandparent% and %get_grandchild%
         * @return [type] [description]
         */
        public function register_grand_parent_child_macros($macros = array())
        {
        }
        /**
         * Has hierarchy
         *
         * @return boolean [description]
         */
        public function has_hierarchy()
        {
        }
        /**
         * Handler for %get_grandparent% macros
         * @param  [type] $value          [description]
         * @param  [type] $from_post_type [description]
         * @return [type]                 [description]
         */
        public function get_grandparent_macros($value, $from_post_type)
        {
        }
        /**
         * Handler for %get_grandchild% macros
         * @param  [type] $value          [description]
         * @param  [type] $from_post_type [description]
         * @return [type]                 [description]
         */
        public function get_grandchild_macros($value, $from_post_type)
        {
        }
        /**
         * Returns grandparent posts for current post type
         *
         * @param  [type] $value     [description]
         * @param  [type] $post_type [description]
         * @return [type]            [description]
         */
        public function get_grandparent($from_post_type = null, $current = null, $post_id = null)
        {
        }
        /**
         * Returns grandchild posts for current post type
         *
         * @param  [type] $value     [description]
         * @param  [type] $post_type [description]
         * @return [type]            [description]
         */
        public function get_grandchild($from_post_type = null, $current = null, $post_id = null)
        {
        }
        /**
         * Returns posts by post types and meta keys trail
         *
         * @return [type] [description]
         */
        public function get_posts_by_trail($post_id = null, $trail = array(), $dir = 'down', $column = false)
        {
        }
        /**
         * Returns trail from post type to post type
         *
         * @param  [type] $from [description]
         * @param  [type] $to   [description]
         * @return [type]       [description]
         */
        public function get_trail_data($from = null, $to = null)
        {
        }
        /**
         * Create hierarchy description
         *
         * @param  [type] $relations [description]
         * @return [type]            [description]
         */
        public function create_hierarchy($relations = array())
        {
        }
        /**
         * Prepare relations array.
         *
         * @param  array $relations
         * @return array
         */
        public function prepare_relations($relations = array())
        {
        }
        /**
         * Add relations to existing hierarchy or create new
         *
         * @param string $key
         * @param array  $relation
         * @param array  $_result
         */
        public function search_for_children($key = null, $relation = null, $_result = null)
        {
        }
    }
    class Convert
    {
        public function __construct()
        {
        }
        /**
         * AJAX callback to convert relation
         *
         * @return [type] [description]
         */
        public function convert_callback()
        {
        }
        /**
         * This verify convert or clear request
         *
         * @return [type] [description]
         */
        public function verify_request()
        {
        }
        /**
         * Check if relations has legacy data
         *
         * @return boolean [description]
         */
        public function has_legacy_data()
        {
        }
        /**
         * Returns URL to clear legacy data
         *
         * @return [type] [description]
         */
        public function clear_legacy_data_url()
        {
        }
        /**
         * Clear legacy data
         *
         * @return [type] [description]
         */
        public function clear_legacy_data()
        {
        }
        public function clear_legacy_data_on_delete($item_id)
        {
        }
        /**
         * Convert relation by ID
         *
         * @param  [type] $rel_id [description]
         * @return [type]         [description]
         */
        public function convert_relation($rel_id)
        {
        }
        /**
         * Convert legacy realtion items
         *
         * @param  [type] $args [description]
         * @return [type]       [description]
         */
        public function convert_legacy_relation_items($args)
        {
        }
    }
}
namespace Jet_Engine\Relations {
    class Types_Helper
    {
        public $types = null;
        /**
         * @var Jet_Engine\Relations\Types\Base[]
         */
        public $types_instances = array();
        /**
         * Returns delimiter for type name parts
         *
         * @return [type] [description]
         */
        public function type_delimiter()
        {
        }
        /**
         * Get full type name by type parts
         *
         * @param  [type] $type   [description]
         * @param  [type] $object [description]
         * @return [type]         [description]
         */
        public function type_name_by_parts($type, $object)
        {
        }
        /**
         * Check if given object are belongs to the given type
         *
         * @param  [type]  $object [description]
         * @param  [type]  $type   [description]
         * @return boolean         [description]
         */
        public function is_of_type($object = null, $type = null)
        {
        }
        /**
         * Get full type name by type parts
         *
         * @param  [type] $type   [description]
         * @param  [type] $object [description]
         * @return [type]         [description]
         */
        public function type_parts_by_name($type_name)
        {
        }
        /**
         * Check if given object string is object of given type
         *
         * @param  [type] $object      [description]
         * @param  [type] $type        [description]
         * @param  [type] $object_name [description]
         * @return [type]              [description]
         */
        public function object_is($object, $type, $object_name = null)
        {
        }
        /**
         * Returns istems list for given type and object (subtype)
         * @param  [type] $type   [description]
         * @param  [type] $object [description]
         * @return [type]         [description]
         */
        public function get_type_items($type, $object = null, $relation = false, $existing = array())
        {
        }
        /**
         * Returns types instances list
         *
         * @param  string $slug Type slug
         * 
         * @return Types\Base|Types\Base[]|false If $slug passed - type instance or false, array of types instances if no $slug provided
         */
        public function get_instances($slug = null)
        {
        }
        /**
         * Get registered types list
         *
         * @return [type] [description]
         */
        public function get_types()
        {
        }
        /**
         * returns types list for JS
         *
         * @return [type] [description]
         */
        public function get_types_for_js()
        {
        }
        /**
         * Returns label or relation type
         *
         * @param  string $context     [description]
         * @param  [type] $object_type [description]
         * @param  [type] $object      [description]
         * @return [type]              [description]
         */
        public function get_type_label($context = 'plural', $object_type = null, $object = null)
        {
        }
        /**
         * Returns label for control of given object in the given relation
         *
         * @param  array  $relation [description]
         * @param  string $object   [description]
         * @return [type]           [description]
         */
        public function get_relation_label($relation, $object_type = '', $object_name = '', $prefix = '', $is_parent_processed = null)
        {
        }
        /**
         * Returns verbosed relation objects string
         *
         * @param  [type] $parent_object [description]
         * @param  [type] $child_object  [description]
         * @return [type]                [description]
         */
        public function relation_verbose($parent_object, $child_object, $delimiter = '->')
        {
        }
        /**
         * Returns item title by object type data and item ID
         *
         * @param  string $type    Relation type
         * @param  string $item_id Item ID
         * @return string          Item title
         */
        public function get_type_item_title($type, $item_id, $relation)
        {
        }
        /**
         * Returns item edit URL by object type data and item ID
         *
         * @param  [type] $type    [description]
         * @param  [type] $item_id [description]
         * @return [type]          [description]
         */
        public function get_type_item_edit_url($type, $item_id, $relation)
        {
        }
        /**
         * Check given capability fro current user + object type + item ID combination
         *
         * @param  [type] $cap     [description]
         * @param  [type] $type    [description]
         * @param  [type] $item_id [description]
         * @return [type]          [description]
         */
        public function current_user_can($cap, $type, $item_id, $object_name = null)
        {
        }
        /**
         * Returns fields list required to create item of given type
         *
         * @param  [type] $type [description]
         * @return [type]       [description]
         */
        public function get_create_control_fields($type, $relation)
        {
        }
        /**
         * Delete given item of given type
         *
         * @param  [type] $type    [description]
         * @param  [type] $item_id [description]
         * @return [type]          [description]
         */
        public function delete_item($type, $item_id)
        {
        }
        /**
         * Returns item view URL by object type data and item ID
         *
         * @param  [type] $type    [description]
         * @param  [type] $item_id [description]
         * @return [type]          [description]
         */
        public function get_type_item_view_url($type, $item_id, $relation)
        {
        }
        /**
         * Create new item of given typer by given data
         *
         * @return [type] [description]
         */
        public function create_item($type, $data = array())
        {
        }
        /**
         * Find exact type of given object
         *
         * @param  [type] $object [description]
         * @return [type]         [description]
         */
        public function get_type_for_object($object)
        {
        }
        /**
         * Sanitize type-specific arguments of relation on edit
         *
         * @param  array  $final_args   [description]
         * @param  array  $request_data [description]
         * @return [type]               [description]
         */
        public function sanitize_relation_edit_args($final_args = array(), $request_data = array())
        {
        }
        /**
         * Return JetSmartFilters-prepared query arguments array of given ids for given object type
         *
         * @return array()
         */
        public function filtered_query_args($type, $ids = array())
        {
        }
        public function merge_filtered_query_args($args = array(), $type = '', $new_args = array())
        {
        }
        /**
         * register callback to clean relations data on removing items of selected type
         *
         * @param  [type] $type [description]
         * @return [type]       [description]
         */
        public function register_cleanup_hook($type, $callback)
        {
        }
        /**
         * Returns list of related object names by IDs and object type
         *
         * @param  [type] $from_object [description]
         * @param  array  $related_ids [description]
         * @return [type]              [description]
         */
        public function verbose_related_objects($from_object = null, $related_ids = array(), $relation = null)
        {
        }
    }
    /**
     * Define Listing class
     */
    class Listing
    {
        public function __construct()
        {
        }
        /**
         * Check id we trying to get relation property and get the appropriate data
         */
        public function get_dynamic_field_prop($result, $prop, $object)
        {
        }
        /**
         * Register listing source
         */
        public function add_dynamic_field_props($groups)
        {
        }
        public function get_prefixed_relations_sources()
        {
        }
        /**
         * Set currently processed relation object
         *
         * @param [type] $relation [description]
         */
        public function set_relation($relation)
        {
        }
        /**
         * Setup current listing for relation
         */
        public function set_listing($listing_id)
        {
        }
        /**
         * Reset listing and relation when its processed
         */
        public function reset_listing($listing_id)
        {
        }
        /**
         * Register meta source for realtions meta data
         *
         * @param [type] $sources [description]
         */
        public function add_meta_source($sources)
        {
        }
        /**
         * Process meta value
         *
         * @return [type] [description]
         */
        public function return_meta_value($result, $settings)
        {
        }
        /**
         * Returns relation meta value for selected settings from all settings list
         *
         * @param  [type] $setting  [description]
         * @param  [type] $settings [description]
         * @return [type]           [description]
         */
        public function get_meta_from($setting, $settings)
        {
        }
        /**
         * Renders custom image for given relation meta
         *
         * @return [type] [description]
         */
        public function custom_image_renderer($result = false, $settings = array(), $size = 'full', $render = null)
        {
        }
        /**
         * Returns custom link URL for Dynamic Field widget/block
         *
         * @param  [type] $result   [description]
         * @param  [type] $settings [description]
         * @return [type]           [description]
         */
        public function custom_link_url($result, $settings)
        {
        }
        /**
         * Returns custom link URL for image link for Dynamic Image widget/block
         *
         * @param  [type] $result   [description]
         * @param  [type] $settings [description]
         * @return [type]           [description]
         */
        public function custom_image_url($result, $settings)
        {
        }
        /**
         * Returns meta fields list for the requested context
         *
         * @param  [type] $context [description]
         * @return [type]          [description]
         */
        public function get_meta_fields_for_options($context = 'elementor', $prefix = false, $type = array())
        {
        }
        /**
         * Register relations meta fields for the block editor configuration
         *
         * @param  [type] $config [description]
         * @return [type]         [description]
         */
        public function blocks_register_relations_meta($config)
        {
        }
        /**
         * Register `dynamic_field_relation_meta` attribute in the Dynamic Field block.
         *
         * @param  array $atts Block attributes array.
         * @return array
         */
        public function register_relations_meta_attr($atts = array())
        {
        }
        /**
         * Register realtion meta source control for the Elementor dynamic field widget
         *
         * @param  [type] $widget [description]
         * @return [type]         [description]
         */
        public function elementor_dynamic_field_controls($widget)
        {
        }
        /**
         * Returns list of allowed media meta fields
         *
         * @param  [type] $result [description]
         * @return [type]         [description]
         */
        public function dynamic_image_controls($result)
        {
        }
        /**
         * Returns list of allowed fields to use as links
         *
         * @param  [type] $result [description]
         * @return [type]         [description]
         */
        public function dynamic_link_controls($result)
        {
        }
    }
}
namespace Jet_Engine\Relations\Storage {
    /**
     * Define Base DB class
     */
    class DB extends \Jet_Engine_Base_DB
    {
        public static $prefix = 'jet_rel_';
        public $relations_cache = [];
        public $table_keys = false;
        /**
         * Insert booking
         *
         * @param  array  $booking [description]
         * @return [type]          [description]
         */
        public function insert($data = array())
        {
        }
        /**
         * Update appointment info
         *
         * @param  array  $new_data [description]
         * @param  array  $where    [description]
         * @return [type]           [description]
         */
        public function update($new_data = array(), $where = array())
        {
        }
        /**
         * Set table keys to create indexes with the table creation
         * 
         * @param boolean $keys [description]
         */
        public function set_table_keys($keys = false)
        {
        }
        /**
         * Returns table columns schema
         *
         * @return [type] [description]
         */
        public function get_table_schema()
        {
        }
        /**
         * Get unique string cache key for given relations request
         * 
         * @param  array   $args   [description]
         * @param  integer $limit  [description]
         * @param  integer $offset [description]
         * @param  array   $order  [description]
         * @param  string  $rel    [description]
         * @return [type]          [description]
         */
        public function get_cache_key($args = array(), $limit = 0, $offset = 0, $order = array(), $rel = 'AND')
        {
        }
        /**
         * Reset relations object cache
         * @return [type] [description]
         */
        public function reset_cache()
        {
        }
        /**
         * Query data from db table
         *
         * @return [type] [description]
         */
        public function query($args = array(), $limit = 0, $offset = 0, $order = array(), $filter = false, $rel = 'AND')
        {
        }
    }
    /**
     * Define Manager class
     */
    class Manager
    {
        /**
         * Get default DB
         *
         * @return [type] [description]
         */
        public function get_default_db()
        {
        }
        /**
         * Get default DB
         *
         * @return [type] [description]
         */
        public function get_default_meta_db()
        {
        }
        /**
         * Returns new DB instance
         * @param  string $table  [description]
         * @param  array  $schema [description]
         * @return [type]         [description]
         */
        public function get_db_instance($table = 'default', $schema = array())
        {
        }
        /**
         * Returns schema of relations table
         *
         * @return [type] [description]
         */
        public function get_db_schema()
        {
        }
        /**
         * Returns schema of relations meta table
         *
         * @return [type] [description]
         */
        public function get_meta_db_schema()
        {
        }
    }
    /**
     * Define ordering handler class
     */
    class Ordering
    {
        /**
         * Returns ordering mode.
         * Mode can be adjusted (or totally disabled) for client project requirements
         *
         * @return mixed
         */
        public function get_mode()
        {
        }
        /**
         * Process items reorder
         *
         * @param  Relation $relation relation instance
         * @param  array    $items    updated items order
         * @return void
         */
        public function reorder_relation_items($relation, $items)
        {
        }
        /**
         * Process reorder based on new IDs
         *
         * @param  Relation $relation relation instance
         * @param  array    $items    updated items order
         * @return void
         */
        public function process_id_reorder($relation, $items = [])
        {
        }
        /**
         * Process reorder based on new dates
         *
         * @param  Relation $relation relation instance
         * @param  array    $items    updated items order
         * @return void
         */
        public function process_date_reorder($relation, $items = [])
        {
        }
        /**
         * Returns the instance.
         *
         * @since  1.0.0
         * @access public
         * @return Jet_Engine
         */
        public static function instance()
        {
        }
    }
}
namespace Jet_Engine\Relations {
    /**
     * Admin filters compatibility class
     */
    class Admin_Filters
    {
        public function __construct()
        {
        }
        public function apply_filter($query, $filter, $value, $admin_filters)
        {
        }
        /**
         * Render admin filter dropdown
         *
         * @param  [type] $filter [description]
         * @param  [type] $index  [description]
         * @return [type]         [description]
         */
        public function render_filter($filter, $index, $admin_filters)
        {
        }
        /**
         * Render custom controls for the relations admin filter
         *
         * @return [type] [description]
         */
        public function register_controls()
        {
        }
        /**
         * Register new filters type for relations
         *
         * @return [type] [description]
         */
        public function register_type($types)
        {
        }
        /**
         * We need only relations where at least one item of type Posts
         *
         * @return boolean [description]
         */
        public function set_relations_for_filters()
        {
        }
    }
}
namespace Jet_Engine\Relations\Controls {
    class Base
    {
        public $args = array();
        /**
         * @var \Jet_Engine\Relations\Relation
         */
        public $relation = null;
        public $control_type = null;
        public static $common_printed = false;
        public function __construct($args)
        {
        }
        /**
         * Returns relation arguments
         *
         * @return [type] [description]
         */
        public function get_args()
        {
        }
        /**
         * Perform an control element wrapper initializtion
         * @return [type] [description]
         */
        public function init()
        {
        }
        /**
         * Setup type of current control
         * what we'll manage with it - parent related item or children
         *
         * @return [type] [description]
         */
        public function setup_type()
        {
        }
        /**
         * Returns type of current control, not the page where it located,
         * bu tyoe of items which will be controlled by it
         *
         * @return [type] [description]
         */
        public function get_control_type()
        {
        }
        /**
         * Check if cotnrol type is equal to given type
         *
         * @param  [type]  $type [description]
         * @return boolean       [description]
         */
        public function _is($type)
        {
        }
        /**
         * Register common assets required for all control types
         *
         * @return [type] [description]
         */
        public function common_assets()
        {
        }
        /**
         * Check if relation has media fields
         *
         * @return boolean [description]
         */
        public function has_media_fields()
        {
        }
        /**
         * Get object data current control is responsible for
         *
         * @return [type] [description]
         */
        public function get_object_for_control()
        {
        }
        /**
         * Returns labels list for give control
         *
         * @return [type] [description]
         */
        public function get_control_labels()
        {
        }
        /**
         * Returns given label from the relations labels list
         *
         * @param  [type] $key [description]
         * @return [type]      [description]
         */
        public function get_label($key, $default = false)
        {
        }
        /**
         * Returns general title of the current control
         *
         * @return [type] [description]
         */
        public function get_control_title()
        {
        }
        /**
         * Add required JS data
         *
         * @return [type] [description]
         */
        public function localize_data()
        {
        }
        /**
         * Returns text of error message when your object ID is empty
         *
         * @return [type] [description]
         */
        public function get_empty_object_help()
        {
        }
        /**
         * Print JS variable containinig current JS object
         *
         * @param  [type] $id [description]
         * @return [type]     [description]
         */
        public function print_current_object_id_for_js($id)
        {
        }
        /**
         * Returns an ID of HTML wrapper for control app
         *
         * @return [type] [description]
         */
        public function get_el_id()
        {
        }
        /**
         * Check if current control page is currently displayed
         *
         * @return boolean [description]
         */
        public function is_control_page()
        {
        }
    }
    class User_Meta extends \Jet_Engine\Relations\Controls\Base
    {
        /**
         * Show on profile or not
         *
         * @return [type] [description]
         */
        public function show_on_profile()
        {
        }
        /**
         * Check if current control page is currently displayed
         *
         * @return boolean [description]
         */
        public function is_control_page()
        {
        }
        /**
         * Perform an control element wrapper initializtion
         * @return [type] [description]
         */
        public function init()
        {
        }
        /**
         * Returns current user ID
         *
         * @return [type] [description]
         */
        public function get_user_id()
        {
        }
        /**
         * Render control app wrapper inside mat box
         *
         * @return [type] [description]
         */
        public function render_meta_box()
        {
        }
    }
    class Term_Meta extends \Jet_Engine\Relations\Controls\Base
    {
        /**
         * Check if current control page is currently displayed
         *
         * @return boolean [description]
         */
        public function is_control_page()
        {
        }
        /**
         * Perform an control element wrapper initializtion
         * @return [type] [description]
         */
        public function init()
        {
        }
        /**
         * Rewrite render control wrapper class to add title
         *
         * @return [type] [description]
         */
        public function render_meta_box($term, $tax)
        {
        }
    }
    class Post_Meta extends \Jet_Engine\Relations\Controls\Base
    {
        /**
         * Check if current control page is currently displayed
         *
         * @return boolean [description]
         */
        public function is_control_page()
        {
        }
        /**
         * Perform an control element wrapper initializtion
         * @return [type] [description]
         */
        public function init()
        {
        }
        /**
         * Initialize meta box
         *
         * @return [type] [description]
         */
        public function init_meta_box()
        {
        }
        /**
         * Render control app wrapper inside mat box
         *
         * @return [type] [description]
         */
        public function render_meta_box()
        {
        }
    }
}
namespace Jet_Engine\Relations {
    abstract class Mix_Types_Helper
    {
        public function __construct()
        {
        }
        /**
         * Returns object type name
         *
         * @return string
         */
        abstract public function get_object_name();
        /**
         * Returns object type label
         *
         * @return string
         */
        abstract public function get_object_label();
        /**
         * Returns object type label singular
         *
         * @return string
         */
        abstract public function get_object_label_single();
        /**
         * Returns object items list
         *
         * @return string
         */
        abstract public function get_object_items();
        /**
         * Check given user capability for custom type
         *
         * @param  $cap - can be 'edit' or 'delete'. You nned to check current user agains approprited capabilities related for your post type.
         *                Or maybe agains global capability - for example current_user_can( 'manage_options' ) means only admin-level users will be able
         *                to edit related items of this type.
         *
         * @return string
         */
        abstract public function check_capability($res, $cap, $item_id);
        /**
         * Returns current item ID
         *
         * @param  [type] $default [description]
         * @param  [type] $item_id [description]
         * @return [type]          [description]
         */
        public function get_item_title($default, $item_id)
        {
        }
        /**
         * Returns current item edit URL
         *
         * @return [type] [description]
         */
        public function get_item_edit_url($default, $item_id)
        {
        }
        /**
         * Returns view URL for current item
         *
         * @param  [type] $default [description]
         * @param  [type] $item_id [description]
         * @return [type]          [description]
         */
        public function get_item_view_url($default, $item_id)
        {
        }
        /**
         * Delete current item
         *
         * @param  [type] $default [description]
         * @param  [type] $item_id [description]
         * @return [type]          [description]
         */
        public function delete_item($default, $item_id)
        {
        }
        /**
         * Returns controls list required to create relation item
         *
         * @param  [type] $default [description]
         * @return [type]          [description]
         */
        public function get_create_control_fields($default)
        {
        }
        /**
         * Create new item and returns created item ID
         *
         * @return [type] [description]
         */
        public function create_item($result, $data = array())
        {
        }
        /**
         * Check if given object belongs current type
         *
         * @return boolean [description]
         */
        public function is_object_of_type($result, $object)
        {
        }
        /**
         * Returns object of current type by item ID of this object
         *
         * @return [type] [description]
         */
        public function get_object_by_id($result, $item_id)
        {
        }
        /**
         * Return JetSmartFilters-prepared query arguments array of given ids for given object type
         *
         * @return array()
         */
        public function filtered_query_args($result, $ids)
        {
        }
        /**
         * Add object data
         *
         * @param [type] $objects [description]
         */
        public function add_object_data($objects)
        {
        }
        /**
         * Register appropriate cleanup hook for current type items.
         * This hook should be called on deletion of item of current type and call clean up method from relation
         * See the default types for examples.
         *
         * @param  string $object_name [description]
         * @param  [type] $callback    [description]
         * @return [type]              [description]
         */
        public function cleanup_hook($callback, $type_name)
        {
        }
    }
}
namespace Jet_Engine\Relations\Macros {
    /**
     * Required methods:
     * macros_tag()  - here you need to set macros tag for JetEngine core
     * macros_name() - here you need to set human-readable macros name for different UIs where macros are available
     * macros_callback() - the main function of the macros. Returns the value
     * macros_args() - Optional, arguments list for the macros. Arguments format is the same ad for Elementor controls
     */
    class Get_Related_Items extends \Jet_Engine_Base_Macros
    {
        use \Jet_Engine\Relations\Traits\Related_Items_By_Args;
        /**
         * Returns macros tag
         *
         * @return string
         */
        public function macros_tag()
        {
        }
        /**
         * Returns macros name
         *
         * @return string
         */
        public function macros_name()
        {
        }
        /**
         * Callback function to return macros value
         *
         * @return string
         */
        public function macros_callback($args = array())
        {
        }
        /**
         * Optionally return custom macros attributes array
         *
         * @return array
         */
        public function macros_args()
        {
        }
    }
    /**
     * Required methods:
     * macros_tag()  - here you need to set macros tag for JetEngine core
     * macros_name() - here you need to set human-readable macros name for different UIs where macros are available
     * macros_callback() - the main function of the macros. Returns the value
     * macros_args() - Optional, arguments list for the macros. Arguments format is the same ad for Elementor controls
     */
    class Get_Related_Item_Meta extends \Jet_Engine_Base_Macros
    {
        /**
         * Returns macros tag
         *
         * @return string
         */
        public function macros_tag()
        {
        }
        /**
         * Returns macros name
         *
         * @return string
         */
        public function macros_name()
        {
        }
        /**
         * Callback function to return macros value
         *
         * @return string
         */
        public function macros_callback($args = array())
        {
        }
        /**
         * Optionally return custom macros attributes array
         *
         * @return array
         */
        public function macros_args()
        {
        }
    }
    /**
     * Required methods:
     * macros_tag()  - here you need to set macros tag for JetEngine core
     * macros_name() - here you need to set human-readable macros name for different UIs where macros are available
     * macros_callback() - the main function of the macros. Returns the value
     * macros_args() - Optional, arguments list for the macros. Arguments format is the same ad for Elementor controls
     */
    class Get_Related_Siblings extends \Jet_Engine\Relations\Macros\Get_Related_Items
    {
        /**
         * Returns macros tag
         *
         * @return string
         */
        public function macros_tag()
        {
        }
        /**
         * Returns macros name
         *
         * @return string
         */
        public function macros_name()
        {
        }
        /**
         * Callback function to return macros value
         *
         * @return string
         */
        public function macros_callback($args = array())
        {
        }
    }
    /**
     * Required methods:
     * macros_tag()  - here you need to set macros tag for JetEngine core
     * macros_name() - here you need to set human-readable macros name for different UIs where macros are available
     * macros_callback() - the main function of the macros. Returns the value
     * macros_args() - Optional, arguments list for the macros. Arguments format is the same ad for Elementor controls
     */
    class Get_Related_Grandparents extends \Jet_Engine_Base_Macros
    {
        /**
         * Returns macros tag
         *
         * @return string
         */
        public function macros_tag()
        {
        }
        /**
         * Returns macros name
         *
         * @return string
         */
        public function macros_name()
        {
        }
        /**
         * Callback function to return macros value
         *
         * @return string
         */
        public function macros_callback($args = array())
        {
        }
        /**
         * Returns related IDs list
         * @param  [type] $rel_id    [description]
         * @param  [type] $object_id [description]
         * @return [type]            [description]
         */
        public function get_related_ids($rel_id, $object_id)
        {
        }
        /**
         * Returns object option label
         * @return [type] [description]
         */
        public function object_option_label()
        {
        }
        /**
         * Optionally return custom macros attributes array
         *
         * @return array
         */
        public function macros_args()
        {
        }
    }
    /**
     * Required methods:
     * macros_tag()  - here you need to set macros tag for JetEngine core
     * macros_name() - here you need to set human-readable macros name for different UIs where macros are available
     * macros_callback() - the main function of the macros. Returns the value
     * macros_args() - Optional, arguments list for the macros. Arguments format is the same ad for Elementor controls
     */
    class Get_Related_Items_Count extends \Jet_Engine_Base_Macros
    {
        /**
         * Returns macros tag
         *
         * @return string
         */
        public function macros_tag()
        {
        }
        /**
         * Returns macros name
         *
         * @return string
         */
        public function macros_name()
        {
        }
        /**
         * Callback function to return macros value
         *
         * @return string
         */
        public function macros_callback($args = array())
        {
        }
        /**
         * Optionally return custom macros attributes array
         *
         * @return array
         */
        public function macros_args()
        {
        }
    }
    /**
     * Required methods:
     * macros_tag()  - here you need to set macros tag for JetEngine core
     * macros_name() - here you need to set human-readable macros name for different UIs where macros are available
     * macros_callback() - the main function of the macros. Returns the value
     * macros_args() - Optional, arguments list for the macros. Arguments format is the same ad for Elementor controls
     */
    class Get_Related_Grandchildren extends \Jet_Engine\Relations\Macros\Get_Related_Grandparents
    {
        /**
         * Returns macros tag
         *
         * @return string
         */
        public function macros_tag()
        {
        }
        /**
         * Returns macros name
         *
         * @return string
         */
        public function macros_name()
        {
        }
        /**
         * Returns related IDs list
         * @param  [type] $rel_id    [description]
         * @param  [type] $object_id [description]
         * @return [type]            [description]
         */
        public function get_related_ids($rel_id, $object_id)
        {
        }
        /**
         * Returns object option label
         * @return [type] [description]
         */
        public function object_option_label()
        {
        }
    }
}
namespace Jet_Engine\Relations {
    /**
     * Define Relations Data class
     */
    class Data extends \Jet_Engine_Base_Data
    {
        /**
         * Edit slug
         *
         * @var string
         */
        public $edit = 'edit-relation';
        public $option_name = 'jet_engine_relations';
        public $replaced_option_name = 'jet_engine_relations_replaced';
        /**
         * Table name
         *
         * @var string
         */
        public $table = 'post_types';
        /**
         * Query arguments
         *
         * @var array
         */
        public $query_args = array('status' => 'relation');
        /**
         * Sanitizr post type request
         *
         * @return void
         */
        public function sanitize_item_request()
        {
        }
        /**
         * Prepare post data from request to write into database
         *
         * @return array
         */
        public function sanitize_item_from_request()
        {
        }
        /**
         * Find related posts for ppassed relation key and current post ID pair
         *
         * @param  [type] $meta_key [description]
         * @param  [type] $post_id  [description]
         * @return [type]           [description]
         */
        public function find_related_posts($meta_key, $post_id)
        {
        }
        public function get_unique_name($name = 'field', $initial = 'field', $list = array())
        {
        }
        /**
         * Delete all related meta contains passed $post_id
         *
         * @param  [type] $meta_key [description]
         * @param  [type] $post_id  [description]
         * @return [type]           [description]
         */
        public function delete_all_related_meta($meta_key, $post_id)
        {
        }
        public function get_field_by_name($field_name, $fields)
        {
        }
        /**
         * Sanitize meta fields
         *
         * @param  [type] $meta_fields [description]
         * @return [type]              [description]
         */
        public function sanitize_meta_fields($meta_fields)
        {
        }
        /**
         * Legacy. Not used for the new relation
         *
         * @param  [type] $id [description]
         * @return [type]     [description]
         */
        public function get_item_by_id($id)
        {
        }
        /**
         * Filter post type for edit
         *
         * @return array
         */
        public function filter_item_for_edit($item)
        {
        }
        /**
         * Return blacklisted items names
         *
         * @return array
         */
        public function items_blacklist()
        {
        }
        /**
         * Filter callback to apply legacy option
         *
         * @param  [type] $item [description]
         * @return [type]       [description]
         */
        public function filter_item_for_register($item)
        {
        }
        /**
         * Ensure labels array is corrctly set up
         * @param  array  $item [description]
         * @return [type]       [description]
         */
        public function setup_labels($item = array())
        {
        }
        /**
         * Returns post type in prepared for register format
         *
         * @return array
         */
        public function get_item_for_register()
        {
        }
        /**
         * Returns items by args without filtering
         *
         * @return array
         */
        public function get_raw($args = array())
        {
        }
        /**
         * Move legacy relations from an option to DB
         *
         * @param  array  $relations [description]
         * @return [type]            [description]
         */
        public function move_option_to_db($relations = array())
        {
        }
        /**
         * Query post types
         *
         * @return array
         */
        public function get_items()
        {
        }
        /**
         * Remove related data on relation deletion
         *
         * @param  [type] $item_id [description]
         * @return [type]          [description]
         */
        public function before_item_delete($item_id)
        {
        }
    }
    class Hierarchy
    {
        public function __construct()
        {
        }
        /**
         * Register relations related macros
         *
         * @return [type] [description]
         */
        public function register_macros()
        {
        }
        /**
         * Returns related grandchildren for given object_id, object_id should be cgrandparent object ID,
         * $rel_id should be ID of grandchildren relation
         *
         * @param  [type] $relation_id [description]
         * @param  [type] $object_id   [description]
         * @return [type]              [description]
         */
        public function get_grandchildren($relation_id, $object_id)
        {
        }
        /**
         * Returns related grandparent for given object_id, object_id should be children object from
         * @param  [type] $relation_id [description]
         * @param  [type] $object_id   [description]
         * @return [type]              [description]
         */
        public function get_grandparents($relation_id, $object_id)
        {
        }
    }
    /**
     * Define Relations Manager class
     */
    class Ajax_Handlers
    {
        public function __construct()
        {
        }
        /**
         * Change Rel ID column type fro text to varchar(40)
         *
         * @param  [type] $db_instance [description]
         * @return [type]              [description]
         */
        public function fix_rel_id_column_type($db_instance)
        {
        }
        /**
         * Reindex relations
         *
         * @return [type] [description]
         */
        public function reindex()
        {
        }
        /**
         * Returns error message
         *
         * @return [type] [description]
         */
        public function get_error_message()
        {
        }
        /**
         * Create new item of given type
         *
         * @return [type] [description]
         */
        public function create_item_of_type()
        {
        }
        /**
         * Get items callback
         *
         * @return [type] [description]
         */
        public function get_type_items()
        {
        }
        /**
         * Retrieve relation object from request data
         *
         * @return [type] [description]
         */
        public function get_relation_from_request()
        {
        }
        /**
         * Ajax callback to retrieve related items list
         *
         * @return [type] [description]
         */
        public function get_related_items()
        {
        }
        /**
         * Process disconnect item request
         *
         * @return [type] [description]
         */
        public function disconnect_relation_items()
        {
        }
        /**
         * save_relation_meta callback
         *
         * @return [type] [description]
         */
        public function save_relation_meta()
        {
        }
        public function get_related_item_meta()
        {
        }
        /**
         * Returns typical data required for most of callbacks
         *
         * @return [type] [description]
         */
        public function get_data_from_request()
        {
        }
        /**
         * Reorder items callback
         *
         * @return [type] [description]
         */
        public function reorder_relation_items()
        {
        }
        /**
         * Update items callback
         *
         * @return [type] [description]
         */
        public function update_relation_items()
        {
        }
        /**
         * Returns correctly fromatted related items list for editor
         *
         * @param  [type]  $current_object      [description]
         * @param  [type]  $relation            [description]
         * @param  boolean $is_parent_processed [description]
         * @return [type]                       [description]
         */
        public function get_related_list($current_object, $relation, $is_parent_processed = false)
        {
        }
        /**
         * Returns array wiith content of table columns for given item
         *
         * @param  array $columns     Array of table columns
         * @param  string $type       Relation type
         * @param  string $item_id    Related item ID
         * @param  Relation $relation Relation instance
         * @param  string $current_id Current object ID
         *
         * @return array              Columns content
         */
        public function get_columns_contents($columns, $type, $item_id, $relation, $current_id)
        {
        }
        /**
         * Verify request nonce
         *
         * @return void
         */
        public function check_nonce()
        {
        }
        /**
         * Check user access
         *
         * @return void
         */
        public function check_user_access()
        {
        }
    }
}
namespace {
    /**
     * Define Jet_Engine_Relations_Page_List class
     */
    class Jet_Engine_Relations_Page_List extends \Jet_Engine_CPT_Page_Base
    {
        public $is_default = \true;
        /**
         * Class constructor
         */
        public function __construct($manager)
        {
        }
        /**
         * Add new  post type button
         */
        public function add_new_btn($page)
        {
        }
        /**
         * Page slug
         *
         * @return string
         */
        public function get_slug()
        {
        }
        /**
         * Page name
         *
         * @return string
         */
        public function get_name()
        {
        }
        /**
         * Register add controls
         * @return [type] [description]
         */
        public function page_specific_assets()
        {
        }
        /**
         * Print add/edit page template
         */
        public function add_page_template()
        {
        }
        /**
         * Renderer callback
         *
         * @return void
         */
        public function render_page()
        {
        }
    }
    /**
     * Define Jet_Engine_Relations_Page_Edit class
     */
    class Jet_Engine_Relations_Page_Edit extends \Jet_Engine_CPT_Page_Base
    {
        /**
         * Page slug
         *
         * @return string
         */
        public function get_slug()
        {
        }
        /**
         * Page name
         *
         * @return string
         */
        public function get_name()
        {
        }
        /**
         * Returns currently requested items ID.
         * If this funciton returns an empty result - this is add new item page
         *
         * @return [type] [description]
         */
        public function item_id()
        {
        }
        /**
         * Register add controls
         * @return [type] [description]
         */
        public function page_specific_assets()
        {
        }
        /**
         * Print add/edit page template
         */
        public function add_page_template()
        {
        }
        /**
         * Returns existing relations list except requested
         * @return [type] [description]
         */
        public function get_existing_relations($id = \false, $is_legacy = \false)
        {
        }
        /**
         * Renderer callback
         *
         * @return void
         */
        public function render_page()
        {
        }
    }
}
namespace Jet_Engine\Relations\Query_Builder {
    class Query_Editor extends \Jet_Engine\Query_Builder\Query_Editor\Base_Query
    {
        /**
         * Query type ID
         */
        public function get_id()
        {
        }
        /**
         * Query type name
         */
        public function get_name()
        {
        }
        /**
         * Returns Vue component name for the Query editor for the current type.
         *
         * @return string
         */
        public function editor_component_name()
        {
        }
        /**
         * Returns Vue component template for the Query editor for the current type.
         *
         * @return mixed|void
         */
        public function editor_component_data()
        {
        }
        /**
         * Returns Vue component template for the Query editor for the current type.
         *
         * @return false|string
         */
        public function editor_component_template()
        {
        }
        /**
         * Returns Vue component template for the Query editor for the current type.
         *
         * @return string
         */
        public function editor_component_file()
        {
        }
    }
    class Manager
    {
        /**
         * A reference to an instance of this class.
         *
         * @access private
         * @var    object
         */
        public static $instance = null;
        public $slug = 'relations-query';
        /**
         * Class constructor
         */
        public function __construct()
        {
        }
        /**
         * Adjust query type for the filters request
         *
         * @param  array  $props
         * @param  string $query_id
         * @param  object $query
         *
         * @return array
         */
        public function adjust_query_type_for_filters($props, $provider, $query_id, $query)
        {
        }
        /**
         * Register editor component for the query builder
         *
         * @param  $manager
         *
         * @return void
         */
        public function register_editor_component($manager)
        {
        }
        /**
         * Register query class
         *
         * @param  $manager
         *
         * @return void
         */
        public function register_query($manager)
        {
        }
        /**
         * Returns the instance.
         *
         * @access public
         * @return object
         */
        public static function instance()
        {
        }
    }
    class Relations_Query extends \Jet_Engine\Query_Builder\Queries\Base_Query
    {
        use \Jet_Engine\Relations\Traits\Related_Items_By_Args;
        use \Jet_Engine\Query_Builder\Queries\Traits\Meta_Query_Trait;
        use \Jet_Engine\Query_Builder\Queries\Traits\Tax_Query_Trait;
        protected $current_query = null;
        /**
         * Returns queries items
         *
         * @return array|object
         */
        public function _get_items()
        {
        }
        /**
         * Get current query object
         *
         * @return \Jet_Engine\Relations\Types\Type_Query|null
         */
        public function get_current_query()
        {
        }
        /**
         * Returns query type for 3rd party integrations.
         * For any internal usage take property directly
         *
         * @return string
         */
        public function get_query_type()
        {
        }
        /**
         * Returns total found items count
         *
         * @return mixed
         */
        public function get_items_total_count()
        {
        }
        /**
         * Return current listing grid page
         *
         * @return false|float|int
         */
        public function get_current_items_page()
        {
        }
        /**
         * Returns count of the items visible per single listing grid loop/page
         *
         * @return int
         */
        public function get_items_per_page()
        {
        }
        /**
         * Returns queried items count per page
         *
         * @return mixed
         */
        public function get_items_page_count()
        {
        }
        /**
         * Returns queried items pages count
         *
         * @return false|float|int
         */
        public function get_items_pages_count()
        {
        }
        /**
         * Set filtered prop in specific for current query type way
         *
         * @param string $prop
         * @param null   $value
         */
        public function set_filtered_prop($prop = '', $value = null)
        {
        }
        /**
         * Set filtering order for current query type way
         *
         * @param $key
         * @param $value
         */
        public function set_filtered_order($key, $value)
        {
        }
        /**
         * Array of arguments where string should be exploded into array
         *
         * @return string[]
         */
        public function get_args_to_explode()
        {
        }
        /**
         * Reset Query.
         *
         * @return void
         */
        public function reset_query()
        {
        }
    }
}
namespace Jet_Engine\Relations\Rest {
    /**
     * Get info about single Relation endpoint
     */
    class Get_Relation extends \Jet_Engine_Base_API_Endpoint
    {
        /**
         * Returns route name
         *
         * @return string
         */
        public function get_name()
        {
        }
        /**
         * API callback
         *
         * @return void
         */
        public function callback($request)
        {
        }
        /**
         * Returns endpoint request method - GET/POST/PUT/DELTE
         *
         * @return string
         */
        public function get_method()
        {
        }
        /**
         * Check user access to current end-popint
         *
         * @return bool
         */
        public function permission_callback($request)
        {
        }
        /**
         * Get query param. Regex with query parameters
         *
         * @return string
         */
        public function get_query_params()
        {
        }
    }
    /**
     * Add/Update relation endpoint
     */
    class Edit_Relation extends \Jet_Engine_Base_API_Endpoint
    {
        /**
         * Returns route name
         *
         * @return string
         */
        public function get_name()
        {
        }
        public function safe_get($args = array(), $group = '', $key = '', $default = false)
        {
        }
        /**
         * API callback
         *
         * @return void
         */
        public function callback($request)
        {
        }
        /**
         * Returns endpoint request method - GET/POST/PUT/DELTE
         *
         * @return string
         */
        public function get_method()
        {
        }
        /**
         * Check user access to current end-popint
         *
         * @return bool
         */
        public function permission_callback($request)
        {
        }
        /**
         * Get query param. Regex with query parameters
         *
         * @return string
         */
        public function get_query_params()
        {
        }
    }
    /**
     * Delete relation endpoint
     */
    class Delete_Relation extends \Jet_Engine_Base_API_Endpoint
    {
        /**
         * Returns route name
         *
         * @return string
         */
        public function get_name()
        {
        }
        /**
         * API callback
         *
         * @return void
         */
        public function callback($request)
        {
        }
        /**
         * Returns endpoint request method - GET/POST/PUT/DELETE
         *
         * @return string
         */
        public function get_method()
        {
        }
        /**
         * Check user access to current end-popint
         *
         * @return bool
         */
        public function permission_callback($request)
        {
        }
        /**
         * Get query param. Regex with query parameters
         *
         * @return string
         */
        public function get_query_params()
        {
        }
    }
    /**
     * Get all relations endpoint
     */
    class Get_Relations extends \Jet_Engine_Base_API_Endpoint
    {
        /**
         * Returns route name
         *
         * @return string
         */
        public function get_name()
        {
        }
        /**
         * API callback
         *
         * @return void
         */
        public function callback($request)
        {
        }
        /**
         * Prepare items to sent into editor
         *
         * @param  [type] $items [description]
         * @return [type]        [description]
         */
        public function prepare_items($items)
        {
        }
        /**
         * Returns endpoint request method - GET/POST/PUT/DELTE
         *
         * @return string
         */
        public function get_method()
        {
        }
        /**
         * Check user access to current end-popint
         *
         * @return bool
         */
        public function permission_callback($request)
        {
        }
    }
    /**
     * Add relation endpoint
     */
    class Add_Relation extends \Jet_Engine_Base_API_Endpoint
    {
        /**
         * Returns route name
         *
         * @return string
         */
        public function get_name()
        {
        }
        public function safe_get($args = array(), $group = '', $key = '', $default = false)
        {
        }
        /**
         * API callback
         *
         * @return void
         */
        public function callback($request)
        {
        }
        /**
         * Returns endpoint request method - GET/POST/PUT/DELTE
         *
         * @return string
         */
        public function get_method()
        {
        }
        /**
         * Check user access to current end-popint
         *
         * @return bool
         */
        public function permission_callback($request)
        {
        }
    }
    class Public_Controller
    {
        public $slug = 'jet-rel';
        public function base_url()
        {
        }
        public function register_routes($args = array())
        {
        }
        public function get_rel_from_request($request)
        {
        }
        public function prepare_get_args($type_slug)
        {
        }
        public function prepare_post_args($type_slug)
        {
        }
        public function get_items($request)
        {
        }
        public function check_user_permissions($request, $context)
        {
        }
        public function get_items_permissions_check($request)
        {
        }
        public function get_item($request)
        {
        }
        public function get_item_permissions_check($request)
        {
        }
        public function update_item($request)
        {
        }
        public function update_item_permissions_check($request)
        {
        }
    }
}
namespace Jet_Engine\Glossaries {
    class Filters
    {
        public function __construct()
        {
        }
        public function setup_indexer_agrs($args = array(), $filter_id = 0, $indexer_args = array())
        {
        }
        public function apply_glossary_options($options, $filter_id, $filter)
        {
        }
        public function get_glossary_options($glossary_id = 0, $fallback = array())
        {
        }
        public function register_controls($fields)
        {
        }
        public function register_source($sources = array())
        {
        }
        public function insert_after($source = array(), $after = null, $insert = array())
        {
        }
    }
    class Settings
    {
        public $items = false;
        public $nonce_key = 'jet-engine-glossaries';
        public $order_option_name = 'jet_engine_glossaries_orders';
        /**
         * Constructor for the class
         */
        public function __construct()
        {
        }
        public function delete_item()
        {
        }
        /**
         * Ajax callback to save settings
         *
         * @return [type] [description]
         */
        public function save_item()
        {
        }
        public function save_orders()
        {
        }
        public function get_fields_from_file()
        {
        }
        /**
         * Register settings JS file
         *
         * @return [type] [description]
         */
        public function register_settings_js()
        {
        }
        /**
         * Print VU template for maps settings
         *
         * @return [type] [description]
         */
        public function print_templates()
        {
        }
        /**
         * Returns all settings
         *
         * @return [type] [description]
         */
        public function get($item_id = false)
        {
        }
        /**
         * Unslash fields of glossary
         *
         * @param  [type] $item [description]
         * @return [type]       [description]
         */
        public function unslah_fields($item)
        {
        }
        /**
         * Register settings tab
         *
         * @return [type] [description]
         */
        public function register_settings_tab()
        {
        }
    }
}
namespace Jet_Engine\Meta_Boxes\Option_Sources {
    class Manual_Options
    {
        public $source_name = 'manual';
        public function __construct()
        {
        }
        /**
         * Apply options of current source
         * 
         * @return [type] [description]
         */
        public function apply_options($options = [], $field = [])
        {
        }
        /**
         * Init optional part of the source
         * 
         * @return [type] [description]
         */
        public function init()
        {
        }
        /**
         * Check if given field belongs to current source
         * 
         * @param  array   $field [description]
         * @return boolean        [description]
         */
        public function is_field_of_current_source($field = [])
        {
        }
        /**
         * Merge new custom value to field options
         * 
         * @param  [type] $field        [description]
         * @param  [type] $custom_value [description]
         * @return [type]               [description]
         */
        public function merge_custom_values($field, $custom_value)
        {
        }
    }
    class Manual_Bulk_Options extends \Jet_Engine\Meta_Boxes\Option_Sources\Manual_Options
    {
        public $source_name = 'manual_bulk';
        /**
         * Apply options of current source
         * 
         * @return [type] [description]
         */
        public function apply_options($options = [], $field = [])
        {
        }
        /**
         * Check if given field belongs to current source
         * 
         * @param  array   $field [description]
         * @return boolean        [description]
         */
        public function is_field_of_current_source($field = [])
        {
        }
        /**
         * Get options for field settings
         *
         * @param  array $field Fields settings.
         * @return array
         */
        public function parse_options($field)
        {
        }
        /**
         * Merge new custom value to field options
         * 
         * @param  [type] $field        [description]
         * @param  [type] $custom_value [description]
         * @return [type]               [description]
         */
        public function merge_custom_values($field, $custom_value)
        {
        }
    }
}
namespace Jet_Engine\Glossaries {
    class Meta_Fields extends \Jet_Engine\Meta_Boxes\Option_Sources\Manual_Bulk_Options
    {
        public $source_name = 'glossary';
        /**
         * Custom part of init
         * 
         * @return [type] [description]
         */
        public function init()
        {
        }
        public function add_data_to_config($config)
        {
        }
        public function is_field_of_current_source($field = array())
        {
        }
        public function add_custom_values_to_glossary($field = null, $field_data = array())
        {
        }
        public function get_glossary_for_field($glossary_id)
        {
        }
        public function format_list($list = array())
        {
        }
        public function parse_options($field = array())
        {
        }
    }
    /**
     * Define Jet_Engine_Options_Data class
     */
    class Data extends \Jet_Engine_Base_Data
    {
        /**
         * Table name
         *
         * @var string
         */
        public $table = 'post_types';
        /**
         * Query arguments
         *
         * @var array
         */
        public $query_args = array('status' => 'glossary');
        /**
         * Table format
         *
         * @var string
         */
        public $table_format = array('%s', '%s', '%s', '%s', '%s');
        /**
         * Found items
         *
         * @var array
         */
        public $found_items = array();
        /**
         * Returns blacklisted post types slugs
         *
         * @return array
         */
        public function items_blacklist()
        {
        }
        /**
         * Returns blacklisted post types slugs
         *
         * @return array
         */
        public function meta_blacklist()
        {
        }
        /**
         * Sanitizr post type request
         *
         * @return void
         */
        public function sanitize_item_request()
        {
        }
        /**
         * Prepare post data from request to write into database
         *
         * @return array
         */
        public function sanitize_item_from_request()
        {
        }
        /**
         * Sanitize meta fields
         *
         * @param  [type] $meta_fields [description]
         * @return [type]              [description]
         */
        public function sanitize_meta_fields($meta_fields)
        {
        }
        /**
         * Filter post type for register
         *
         * @return array
         */
        public function filter_item_for_register($item)
        {
        }
        /**
         * Filter post type for edit
         *
         * @return array
         */
        public function filter_item_for_edit($item)
        {
        }
        public function get_item_for_edit($id)
        {
        }
        public function clear_cache()
        {
        }
        public function get_fields_from_file($item)
        {
        }
    }
    /**
     * Define Jet_Engine_Glossaries class
     */
    class Manager
    {
        use \Jet_Engine_Notices_Trait;
        public $data;
        public $settings;
        public $meta_fields;
        public $forms;
        public $filters;
        public function __construct()
        {
        }
        public function init_options_source()
        {
        }
        public function ensure_allowed_import_mimes($mimes)
        {
        }
        /**
         * Init data instance
         *
         * @return [type] [description]
         */
        public function init_data()
        {
        }
        public function init_rest($api_manager)
        {
        }
        /**
         * Return path to file inside component
         *
         * @param  [type] $path_inside_component [description]
         * @return [type]                        [description]
         */
        public function component_path($path_inside_component)
        {
        }
        /**
         * Return URL of the file inside component
         *
         * @param  [type] $path_inside_component [description]
         * @return [type]                        [description]
         */
        public function component_url($path_inside_component)
        {
        }
        /**
         * Returns glossaries
         *
         * @return [type] [description]
         */
        public function get_glossaries_for_js()
        {
        }
        /**
         * Returns labels from selected glossary for given values
         *
         * @param  [type] $value       [description]
         * @param  [type] $glossary_id [description]
         * @param  string $delimiter   [description]
         * @return [type]              [description]
         */
        public function get_labels_for_values($value = null, $glossary_id = null, $delimiter = ', ')
        {
        }
        /**
         * Search label by value in the given glossary data
         *
         * @param  [type] $value    [description]
         * @param  array  $glossary [description]
         * @return [type]           [description]
         */
        public function search_label($value = null, $glossary = array())
        {
        }
    }
    class Forms
    {
        public function __construct()
        {
        }
        public function apply_glossary_options($options, $args)
        {
        }
        public function register_source($sources = array())
        {
        }
        public function register_controls()
        {
        }
    }
}
namespace Jet_Engine\Glossaries\Rest {
    class Search_Fields extends \Jet_Engine_Base_API_Endpoint
    {
        /**
         * Returns route name
         *
         * @return string
         */
        public function get_name()
        {
        }
        /**
         * API callback
         *
         * @param $request
         *
         * @return void|\WP_Error|\WP_REST_Response
         */
        public function callback($request)
        {
        }
        /**
         * Returns endpoint request method - GET/POST/PUT/DELTE
         *
         * @return string
         */
        public function get_method()
        {
        }
        /**
         * Check user access to current end-popint
         *
         * @return bool
         */
        public function permission_callback($request)
        {
        }
        /**
         * Returns arguments config
         *
         * @return array
         */
        public function get_args()
        {
        }
    }
}
namespace Jet_Engine\Glossaries {
    /**
     * Define Fixer class
     */
    class Fixer
    {
        protected $nonce = '_jet-engine-glossary-%s-fix-nonce';
        protected $updated = false;
        public function __construct($glossary)
        {
        }
        public function ensure_glossary_structure($item = array())
        {
        }
        public function do_update()
        {
        }
        public function init_notices()
        {
        }
    }
}
namespace {
    /**
     * Define Jet_Engine_CPT_Meta class
     */
    class Jet_Engine_CPT_Meta
    {
        public static $index = 0;
        public $post_type;
        public $meta_box;
        public $custom_css = array();
        public $is_allowed_on_admin_hook = \null;
        public static $wrappers_hooked = \false;
        public static $edit_styles_rendered = \false;
        public $box_id = \null;
        public $layout_now = \false;
        public $current_component = \false;
        public $current_panel = \false;
        public $edit_link = \false;
        public $show_in_rest = array();
        public $hide_field_names = \false;
        /**
         * Trigger to define which fields format should be used - plain or blocks
         * for plain - all options stored in value => label format
         * for blocks - options stored as array with value and label keys
         * array(
         * 'value' => 'val',
         * 'label' => 'label',
         * )
         * @var boolean
         */
        public $blocks_flag = \false;
        /**
         * Constructor for the class
         */
        function __construct($post_type = \null, $meta_box = \null, $title = '', $context = 'normal', $priority = 'high', $args = array())
        {
        }
        /**
         * Enable blocks flag
         */
        public function set_blocks_flag()
        {
        }
        /**
         * Returns blocks flag
         */
        public function is_blocks()
        {
        }
        public function add_edit_link($link)
        {
        }
        public function maybe_hook_render_link($res, $post, $meta_box)
        {
        }
        public function render_edit_link()
        {
        }
        public function get_box_id()
        {
        }
        /**
         * Returns builder for meta.
         *
         * @since  3.2.0 Added `$args` parameter.
         * @access public
         *
         * @param array $args List of custom arguments.
         *
         * @return CX_Interface_Builder
         */
        public function get_builder_for_meta($args = [])
        {
        }
        /**
         * Add wrappers hooks.
         */
        public function add_wrappers_hooks()
        {
        }
        /**
         * Open meta wrap
         * @return void
         */
        public function open_meta_wrap()
        {
        }
        /**
         * Open meta wrap
         * @return void
         */
        public function close_meta_wrap()
        {
        }
        /**
         * Get CSS classses for the field by given field data
         *
         * @param  array  $field Field data
         * @return string
         */
        public function get_field_css_class($field = array())
        {
        }
        /**
         * Prepare meta fields for registering
         *
         * @param  array  $meta_box Meta box fields list
         * @return [type]           [description]
         */
        public function prepare_meta_fields($meta_box = array())
        {
        }
        public function is_allowed_on_current_admin_hook($hook)
        {
        }
        /**
         * Maybe add custom css
         *
         * @return [type] [description]
         */
        public function maybe_enqueue_custom_css($hook)
        {
        }
        /**
         * Maybe add inline js
         */
        public function maybe_enqueue_inline_js($hook)
        {
        }
        /**
         * Get CSS wrapper selector.
         *
         * @return string
         */
        public function get_css_wrapper_selector()
        {
        }
        /**
         * Enqueue date-related assets
         *
         * @param  [type] $hook [description]
         * @return [type]       [description]
         */
        public function enqueue_date_assets($hook = \false)
        {
        }
        public function date_assets()
        {
        }
        /**
         * Returns default icon data
         *
         * @return array
         */
        public function get_icon_data()
        {
        }
        public function prepare_repeater_fields($repeater_fields = array())
        {
        }
        public function prepare_radio_options($options = array(), $field = array())
        {
        }
        /**
         * Prepare options for select
         * @return [type] [description]
         */
        public function prepare_select_options($field = array())
        {
        }
        /**
         * Prepare field conditions.
         *
         * @param  array $field
         * @param  array $all_fields
         * @return array
         */
        public function prepare_field_conditions($field = array(), $all_fields = array())
        {
        }
        /**
         * Return filtered options list
         *
         * @param  array  $options [description]
         * @param  array  $field   [description]
         * @return [type]          [description]
         */
        public function filter_options_list($options = array(), $field = array())
        {
        }
        /**
         * Get field type by name.
         *
         * @param  string $name
         * @return string|null
         */
        public function get_field_type_by_name($name)
        {
        }
        /**
         * Get field args by name.
         *
         * @param  string $name
         * @param  array  $fields
         * @return string|null
         */
        public function get_field_args_by_name($name = \null, $fields = array())
        {
        }
    }
    /**
     * Define Jet_Engine_CPT_User_Meta class
     */
    class Jet_Engine_CPT_User_Meta extends \Jet_Engine_CPT_Meta
    {
        public $builder;
        /**
         * Constructor for the class
         */
        public function __construct($args, $meta_box)
        {
        }
        /**
         * Returns processed user ID
         * @return [type] [description]
         */
        public function get_user_id()
        {
        }
        /**
         * Initialize on allowed screens
         *
         * @return [type] [description]
         */
        public function init_on_allowed_screens($current_screen)
        {
        }
        /**
         * Register user meta fields
         *
         * @param  boolean $profile [description]
         * @return [type]           [description]
         */
        public function register_fields($profile = \false)
        {
        }
        /**
         * Initialize builder and register fields
         *
         * @return [type] [description]
         */
        public function init_builder()
        {
        }
        /**
         * Prepare field value.
         *
         * @param $field
         * @param $value
         *
         * @return array
         */
        public function prepare_field_value($field, $value)
        {
        }
        /**
         * Returns date converted from timestamp
         *
         * @return [type] [description]
         */
        public function get_date($format, $time)
        {
        }
        /**
         * Safely get attribute from field settings array.
         *
         * @since  1.0.0
         * @param  array            $field   arguments array.
         * @param  string|int|float $arg     argument key.
         * @param  mixed            $default default argument value.
         * @return mixed
         */
        public function get_arg($field = array(), $arg = '', $default = '')
        {
        }
        /**
         * Retrieve post meta field.
         *
         * @since  1.1.0
         * @since  1.2.0 Process default value.
         *
         * @param  object $post    Current post object.
         * @param  string $key     The meta key to retrieve.
         * @param  mixed  $default Default value.
         * @param  array  $field   Meta field apropriate to current key.
         * @return string
         */
        public function get_meta($key = '', $default = \false, $field = array())
        {
        }
        /**
         * Is date field
         *
         * @param  [type]  $input_type [description]
         * @return boolean             [description]
         */
        public function to_timestamp($field)
        {
        }
        /**
         * Render fields
         *
         * @return [type] [description]
         */
        public function render_fields()
        {
        }
        /**
         * Open meta wrap
         * @return void
         */
        public function open_meta_wrap()
        {
        }
        /**
         * Get CSS wrapper selector.
         *
         * @return string
         */
        public function get_css_wrapper_selector()
        {
        }
        /**
         * Fires on users edited by admin
         *
         * @param  [type] $user_id [description]
         * @return [type]          [description]
         */
        public function edit_user_update($user_id)
        {
        }
        /**
         * Fires when user editing own profile
         *
         * @return [type] [description]
         */
        public function personal_profile_update($user_id)
        {
        }
        /**
         * Update user data
         *
         * @return [type] [description]
         */
        public function update_meta($user_id)
        {
        }
        /**
         * Sanitize passed meta value
         *
         * @since  1.1.3
         * @param  array $field Meta field to sanitize.
         * @param  mixed $value Meta value.
         * @return mixed
         */
        public function sanitize_meta($field, $value)
        {
        }
        /**
         * Cleare value with sanitize_text_field if not is array
         *
         * @since  1.1.3
         * @param  mixed $value Passed value.
         * @return mixed
         */
        public function sanitize_deafult($value)
        {
        }
        public function is_allowed_on_current_admin_hook($hook)
        {
        }
        /**
         * Returns the repeater separate field key.
         *
         * @param $repeater_key
         * @param $field_key
         *
         * @return string
         */
        public function get_repeater_separate_field_key($repeater_key, $field_key)
        {
        }
        /**
         * Delete all separate fields of repeater.
         *
         * @param $user_id
         * @param $key
         * @param $field
         */
        public function delete_repeater_separate_fields($user_id, $key, $field)
        {
        }
        /**
         * Save the value of each repeater field as a separate field.
         *
         * @param $user_id
         * @param $key
         * @param $value
         * @param $field
         */
        public function save_repeater_separate_fields($user_id, $key, $value, $field)
        {
        }
    }
}
namespace Jet_Engine\Meta_Boxes\MCP {
    class Controller
    {
        public function __construct()
        {
        }
        public function register_tools($registry)
        {
        }
    }
    class Tool_Add_Meta_Box
    {
        public function __construct()
        {
        }
        public function callback($input = array())
        {
        }
        protected function normalize_general_settings($settings)
        {
        }
        protected function ensure_active_conditions($settings)
        {
        }
        protected function ensure_active_condition($active, $key)
        {
        }
        protected function prepare_meta_fields($fields)
        {
        }
        protected function sanitize_existing_field($field)
        {
        }
        protected function build_field_from_descriptor($field)
        {
        }
        protected function maybe_apply_manual_options($prepared, $source)
        {
        }
        protected function prepare_bulk_options($options)
        {
        }
        protected function sanitize_user_screen($value)
        {
        }
        protected function sanitize_slug_array($values)
        {
        }
        protected function sanitize_post_ids($values)
        {
        }
        protected function sanitize_terms_array($values)
        {
        }
        protected function sanitize_bool($value, $default = false)
        {
        }
        protected function sanitize_field_name($name)
        {
        }
        protected function sanitize_generic_value($value)
        {
        }
        protected function humanize($string)
        {
        }
        protected function get_object_type_enum()
        {
        }
    }
}
namespace {
    /**
     * Define Jet_Engine_CPT_Quick_Edit class
     */
    class Jet_Engine_CPT_Quick_Edit
    {
        public static $hook_after_save_all = array();
        public $post_type = \null;
        public $field = \null;
        public $trigger_col = 'jet_engine_quick_edit';
        public static $add_inline_css = \false;
        public static $is_first = \true;
        public function __construct($post_type, $field)
        {
        }
        /**
         * Run cx_post_meta/after_save hook after save all quick edit boxes to better compatibility with Cherry_X_Post_Meta
         *
         * @return void
         */
        public function after_save_all()
        {
        }
        /**
         * Post type slug current field is related to
         *
         * @return string
         */
        public function get_post_type()
        {
        }
        /**
         * Save field on quick edit update call
         *
         * @return null
         */
        public function save_field()
        {
        }
        /**
         * Get current field data or spicified argument
         *
         * @param  [type] $key [description]
         * @return [type]      [description]
         */
        public function get_field($key = \null)
        {
        }
        /**
         * Set field arg
         *
         * @param [type] $key   [description]
         * @param [type] $value [description]
         */
        public function set_field($key, $value)
        {
        }
        /**
         * Get column name related to current field
         *
         * @return [type] [description]
         */
        public function get_trigger_col()
        {
        }
        /**
         * Hide column related to current field
         *
         * @param  [type] $hidden [description]
         * @param  [type] $screen [description]
         * @return [type]         [description]
         */
        public function hide_quick_edit_trigger($hidden, $screen)
        {
        }
        /**
         * Print current field value into prepared column
         *
         * @param [type] $column  [description]
         * @param [type] $post_id [description]
         */
        public function set_column_value($column, $post_id)
        {
        }
        /**
         * Register related columne for current field
         *
         * @param  [type] $columns [description]
         * @return [type]          [description]
         */
        public function register_quick_edit_trigger($columns)
        {
        }
        /**
         * Render field related control into quick edit section
         *
         * @param  [type] $column    [description]
         * @param  [type] $post_type [description]
         * @return [type]            [description]
         */
        public function render_control($column, $post_type)
        {
        }
    }
    /**
     * Define Jet_Engine_CPT_Tax_Meta class
     */
    class Jet_Engine_CPT_Tax_Meta extends \Jet_Engine_CPT_Meta
    {
        public $tax;
        public $show_in_rest = array();
        /**
         * Constructor for the class
         */
        function __construct($taxonomy, $meta_box, $args = array())
        {
        }
        public function maybe_render_edit_link($args)
        {
        }
        /**
         * Add wrappers hooks.
         */
        public function add_wrappers_hooks()
        {
        }
        /**
         * Open meta wrap
         * @return void
         */
        public function open_meta_wrap()
        {
        }
        public function is_allowed_on_current_admin_hook($hook)
        {
        }
    }
    /**
     * Define Jet_Engine_Meta_Boxes_Conditions class
     */
    class Jet_Engine_Meta_Boxes_Conditions
    {
        public function __construct()
        {
        }
        /**
         * Register allowed visibility conditions for Meta boxes
         *
         * @return [type] [description]
         */
        public function register_conditions()
        {
        }
        /**
         * Resgister new condition instance
         *
         * @param  [type] $condition_instance [description]
         * @return [type]                     [description]
         */
        public function register_condition_type($condition_instance)
        {
        }
        /**
         * Get all conditions list
         *
         * @return [type] [description]
         */
        public function get_conditions($key = \null)
        {
        }
        /**
         * Get conditions data for using in JS of Meta Box edit page
         * @return [type] [description]
         */
        public function get_conditions_data_for_edit()
        {
        }
        /**
         * Register conditions to check with AJAX from post/term/user editor
         * @param [type] $screen [description]
         * @param [type] $data   [description]
         */
        public function add_to_ajax_stack($screen, $data)
        {
        }
        /**
         * [get_screen_name description]
         * @return [type] [description]
         */
        public function get_screen_name($args = array())
        {
        }
        /**
         * [get_ajax_stack description]
         * @return [type] [description]
         */
        public function get_ajax_stack()
        {
        }
        /**
         * Enqueue JS with ajax conditions
         * @return [type] [description]
         */
        public function enqueue_ajax_handler()
        {
        }
        /**
         * Ajax callback to check given meta box conditions
         */
        public function ajax_check_conditions()
        {
        }
        /**
         * Check conditions
         *
         * @param  [type] $args [description]
         * @return [type]       [description]
         */
        public function check_conditions($id, $args)
        {
        }
    }
    /**
     * Define Jet_Engine_Meta_Boxes_Data class
     */
    class Jet_Engine_Meta_Boxes_Data extends \Jet_Engine_Base_Data
    {
        /**
         * Edit slug
         *
         * @var string
         */
        public $edit = 'edit';
        public $option_name = 'jet_engine_meta_boxes';
        /**
         * Update post post type
         *
         * @return void
         */
        public function delete_item($redirect = \true)
        {
        }
        /**
         * Update item in DB
         *
         * @param  [type] $item [description]
         * @return [type]       [description]
         */
        public function update_item_in_db($item)
        {
        }
        /**
         * Returns actual numeric ID
         * @return [type] [description]
         */
        public function get_numeric_id()
        {
        }
        /**
         * Sanitizr post type request
         *
         * @return void
         */
        public function sanitize_item_request()
        {
        }
        /**
         * Prepare post data from request to write into database
         *
         * @return array
         */
        public function sanitize_item_from_request()
        {
        }
        /**
         * Retrieve post for edit
         *
         * @return array
         */
        public function get_item_for_edit($id)
        {
        }
        /**
         * Return sanitized repeater field
         *
         * @param  [type] $fields [description]
         * @return [type]         [description]
         */
        public function sanitize_repeater_fields($fields)
        {
        }
        /**
         * Unset collapsed value
         *
         * @param  [type] $collapsed [description]
         * @return [type]            [description]
         */
        public function unset_collapsed($list)
        {
        }
        /**
         * Returns post type in prepared for register format
         *
         * @return array
         */
        public function get_item_for_register()
        {
        }
        /**
         * Returns items by args without filtering
         *
         * @return array
         */
        public function get_raw($args = array())
        {
        }
        /**
         * Query post types
         *
         * @return array
         */
        public function get_items()
        {
        }
        /**
         * Return totals post types count
         *
         * @return int
         */
        public function total_items()
        {
        }
        /**
         * Stored in wp_options, so always true
         *
         * @return [type] [description]
         */
        public function ensure_db_table()
        {
        }
        /**
         * Filter post type for register
         *
         * @return array
         */
        public function filter_item_for_register($item)
        {
        }
        /**
         * Filter post type for edit
         *
         * @return array
         */
        public function filter_item_for_edit($item)
        {
        }
        /**
         * Return blacklisted items names
         *
         * @return array
         */
        public function items_blacklist()
        {
        }
        /**
         * Before item update
         */
        public function before_item_update($item = array())
        {
        }
        /**
         * Before item delete
         */
        public function before_item_delete($id = \null)
        {
        }
        /**
         * Maybe delete metadata on update item
         */
        public function delete_metadata_on_update($item = array())
        {
        }
        /**
         * Delete metadata of MetaBox
         */
        public function delete_metadata($item = array(), $keys_to_delete = array(), $on_delete = \false)
        {
        }
    }
    /**
     * Define Jet_Engine_Meta_Boxes class
     */
    class Jet_Engine_Meta_Boxes extends \Jet_Engine_Base_WP_Intance
    {
        /**
         * Base slug for CPT-related pages
         * @var string
         */
        public $page = 'jet-engine-meta';
        /**
         * Action request key
         *
         * @var string
         */
        public $action_key = 'cpt_meta_action';
        /**
         * Set object type
         * @var string
         */
        public $object_type = '';
        /**
         * Meta fields for object
         *
         * @var null|array
         */
        public $meta_fields = array();
        /**
         * Conditions manager instance
         *
         * @var Jet_Engine_Meta_Boxes_Conditions
         */
        public $conditions = \null;
        /**
         * Meta fields with `save custom` option
         *
         * @var array
         */
        public $meta_fields_save_custom = array();
        /**
         * Init data instance
         *
         * @return [type] [description]
         */
        public function init_data()
        {
        }
        /**
         * Initialize conditions manager
         *
         * @return [type] [description]
         */
        public function init_conditions()
        {
        }
        /**
         * Returns allowed sources list
         *
         * @return [type] [description]
         */
        public function get_sources()
        {
        }
        /**
         * Add meta fields editor to components where it allowed
         */
        public function add_meta_fields_to_rel_components()
        {
        }
        /**
         * Initiizlize post type specific API endpoints
         *
         * @param  Jet_Engine_REST_API $api_manager API manager instance.
         * @return void
         */
        public function init_rest($api_manager)
        {
        }
        /**
         * Return path to file inside component
         *
         * @param  [type] $path_inside_component [description]
         * @return [type]                        [description]
         */
        public function component_path($path_inside_component)
        {
        }
        /**
         * Regiter custom group
         *
         * @param  [type] $name  [description]
         * @param  [type] $label [description]
         * @return [type]        [description]
         */
        public function register_custom_group($name, $label)
        {
        }
        /**
         * Register metaboxes
         *
         * @return void
         */
        public function register_instances()
        {
        }
        /**
         * Register the same metabox as default but from outside of this instance
         *
         * @return [type] [description]
         */
        public function register_metabox($post_type = '', $meta_fields = array(), $title = '', $object_name = \null, $context = 'post_type')
        {
        }
        /**
         * Strore information aboutt all registered fields
         *
         * @param  string $post_type   [description]
         * @param  array  $meta_fields [description]
         * @return [type]              [description]
         */
        public function store_fields($object_type = 'post', $meta_fields = array(), $context = 'post_type')
        {
        }
        /**
         * Returns fields for the given context and object
         * Should be called on hook 'init' with priority 11 or later
         *
         * @param  string $context Context to get fields for.
         * @param  string $object  Object inside context to get fields from. If not assed -returns all fields, grouped by objects
         * @return array
         */
        public function get_fields_for_context($context = 'post_type', $object = \null)
        {
        }
        /**
         * Return all registered meta fields
         *
         * @return array
         */
        public function get_registered_fields()
        {
        }
        /**
         * Try to get current post ID from request
         *
         * @return [type] [description]
         */
        public function get_post_id()
        {
        }
        /**
         * Return fields list registered for users
         *
         * @return [type] [description]
         */
        public function store_default_user_meta_fields()
        {
        }
        /**
         * Return list of meta fields for post type
         *
         * @param  string $object [description]
         * @return [type]            [description]
         */
        public function get_meta_fields_for_object($object = 'post')
        {
        }
        /**
         * Returns all registered options (or depends on context) to use in select
         *
         * @return [type] [description]
         */
        public function get_fields_for_select($context = 'plain', $where = 'elementor', $for = 'all', $add_object_type_to_output = \false)
        {
        }
        /**
         * Return admin pages for current instance
         *
         * @return array
         */
        public function get_instance_pages()
        {
        }
        /**
         * Returns current menu page title (for JetEngine submenu)
         * @return [type] [description]
         */
        public function get_page_title()
        {
        }
        /**
         * Returns current instance slug
         *
         * @return [type] [description]
         */
        public function instance_slug()
        {
        }
        /**
         * Returns default config for add/edit page
         *
         * @param  array  $config [description]
         * @return [type]         [description]
         */
        public function get_admin_page_config($config = array())
        {
        }
        /**
         * Remove post type from `allowed_post_type` param in the meta boxes.
         *
         * @param $deleted_post_type
         */
        public function remove_deleted_post_type_from_meta_boxes($deleted_post_type)
        {
        }
        /**
         * Update the post type slug in the meta boxes after it has been changed.
         *
         * @param $new_post_type
         * @param $initial_post_type
         */
        public function update_post_type_in_meta_boxes($new_post_type, $initial_post_type)
        {
        }
        /**
         * Remove tax from `allowed_tax` param in meta boxes.
         *
         * @param $deleted_tax
         */
        public function remove_deleted_tax_from_meta_boxes($deleted_tax)
        {
        }
        /**
         * Update the tax slug in the meta boxes after it has been changed.
         *
         * @param $new_tax
         * @param $initial_tax
         */
        public function update_tax_in_meta_boxes($new_tax, $initial_tax)
        {
        }
        /**
         * Update the post type/taxonomy slug in the meta boxes after it has been changed.
         *
         * To delete the post type/taxonomy in the meta boxes, set $new_obj_slug to false.
         *
         * @param $new_obj_slug
         * @param $obj_slug
         * @param $type
         */
        public function update_object_type_in_meta_boxes($new_obj_slug = \null, $obj_slug = \null, $type = 'post')
        {
        }
    }
    /**
     * Define Jet_Engine_Meta_Boxes_Option_Source class
     */
    class Jet_Engine_Meta_Boxes_Option_Sources
    {
        public $meta_fields = [];
        public function __construct()
        {
        }
        /**
         * Find meta fields with enabling `save custom` option
         *
         * @param $object_type
         * @param $sub_type
         * @param $fields
         * @param $item_id
         * @param $data_handler
         * @param $is_built_in
         */
        public function find_meta_fields_with_save_custom($object_type, $sub_type, $fields, $item_id, $data_handler, $is_built_in = \false)
        {
        }
        /**
         * Add hooks to save custom values
         */
        public function add_hooks_to_save_custom_values()
        {
        }
        /**
         * Save custom values
         *
         * @param $id Object ID
         */
        public function save_custom_values($id, $data_handler, $object_type = \false, $sub_type = \false)
        {
        }
        /**
         * Maybe add custom values to options.
         *
         * @param $meta_fields
         * @param $field
         * @param $field_args
         *
         * @return mixed
         */
        public function maybe_add_custom_values_to_options($meta_fields, $field, $field_args)
        {
        }
        /**
         * Returns meta field with custom value merged options.
         * It's only wrapper method. Implementation depends on options source
         * 
         * @param  [type] $field        [description]
         * @param  [type] $custom_value [description]
         * @return [type]               [description]
         */
        public function get_field_with_merged_options($field, $custom_value)
        {
        }
        /**
         * Returns list of allowed option sources
         * 
         * @return [type] [description]
         */
        public function get_allowed_sources()
        {
        }
        /**
         * Returns list of allowed option sources adated to use in JS components
         * 
         * @return [type] [description]
         */
        public function get_allowed_sources_for_js()
        {
        }
        /**
         * Returns the instance.
         *
         * @since  1.0.0
         * @access public
         * @return static
         */
        public static function instance()
        {
        }
    }
}
namespace Jet_Engine\Components\Meta_Boxes\Conditions {
    /**
     * Define Base class
     */
    abstract class Base
    {
        public function __construct()
        {
        }
        /**
         * Returns conditions name to show in options
         *
         * @return [type] [description]
         */
        abstract public function get_name();
        /**
         * Returns appropriate setting key for this condition
         *
         * @return [type] [description]
         */
        abstract public function get_key();
        /**
         * Returns appropriate UI control for current condition
         *
         * @return string
         */
        abstract public function get_control();
        /**
         * Check condition
         *
         * @return [type] [description]
         */
        abstract public function check_condition($args = array());
        /**
         * Remove condition button
         *
         * @return [type] [description]
         */
        public function remove_button()
        {
        }
        /**
         * Returns array of allowed sources
         *
         * @return [type] [description]
         */
        public function allowed_sources()
        {
        }
        /**
         * Renders appropriate UI control for current condition
         *
         * @return string
         */
        public function render_control()
        {
        }
        /**
         * Return arguments list prepared for AJAX by given meta box arguments array
         *
         * @return array
         */
        public function get_ajax_data_from_args($args = array())
        {
        }
        /**
         * Determine is codition checked on AJAX request or not
         *
         * @return boolean [description]
         */
        public function is_ajax()
        {
        }
        /**
         * Returns JS handler to pas data into AJAX request
         *
         * @return [type] [description]
         */
        public function get_js_handler()
        {
        }
    }
    /**
     * Define Base class
     */
    class Include_User_Roles extends \Jet_Engine\Components\Meta_Boxes\Conditions\Base
    {
        /**
         * Returns conditions name to show in options
         *
         * @return [type] [description]
         */
        public function get_name()
        {
        }
        /**
         * Returns appropriate setting key for this condition
         *
         * @return [type] [description]
         */
        public function get_key()
        {
        }
        /**
         * Expression to check current condition
         *
         * @return [type] [description]
         */
        public function check($roles, $roles_to_check)
        {
        }
        /**
         * Check condition
         *
         * @return [type] [description]
         */
        public function check_condition($args = array())
        {
        }
        /**
         * Renders appropriate UI control for current condition
         *
         * @return array
         */
        public function get_control()
        {
        }
    }
    /**
     * Define Base class
     */
    class Exclude_User_Roles extends \Jet_Engine\Components\Meta_Boxes\Conditions\Include_User_Roles
    {
        /**
         * Returns conditions name to show in options
         *
         * @return [type] [description]
         */
        public function get_name()
        {
        }
        /**
         * Returns appropriate setting key for this condition
         *
         * @return [type] [description]
         */
        public function get_key()
        {
        }
        /**
         * Expression to check current condition
         *
         * @return [type] [description]
         */
        public function check($roles, $roles_to_check)
        {
        }
        /**
         * Renders appropriate UI control for current condition
         *
         * @return array
         */
        public function get_control()
        {
        }
    }
    /**
     * Define Base class
     */
    class Post_Has_Terms extends \Jet_Engine\Components\Meta_Boxes\Conditions\Base
    {
        /**
         * Returns conditions name to show in options
         *
         * @return [type] [description]
         */
        public function get_name()
        {
        }
        /**
         * Returns appropriate setting key for this condition
         *
         * @return [type] [description]
         */
        public function get_key()
        {
        }
        /**
         * Check condition
         *
         * @return [type] [description]
         */
        public function check_condition($args = array())
        {
        }
        /**
         * Returns sanitized array of terms of required taxonomy
         *
         * @return array
         */
        public function get_terms_from_request($tax, $request = array())
        {
        }
        /**
         * Renders appropriate UI control for current condition
         *
         * @return array
         */
        public function get_control()
        {
        }
        /**
         * Returns array of allowed sources
         *
         * @return [type] [description]
         */
        public function allowed_sources()
        {
        }
        /**
         * Determine is codition checked on AJAX request or not
         *
         * @return boolean [description]
         */
        public function is_ajax()
        {
        }
        /**
         * Return arguments list prepared for AJAX by given meta box arguments array
         *
         * @return array
         */
        public function get_ajax_data_from_args($args = array())
        {
        }
        /**
         * Returns JS handler to pas data into AJAX request
         *
         * @return [type] [description]
         */
        public function get_js_handler()
        {
        }
    }
    /**
     * Define Base class
     */
    class Include_Posts extends \Jet_Engine\Components\Meta_Boxes\Conditions\Base
    {
        /**
         * Returns conditions name to show in options
         *
         * @return [type] [description]
         */
        public function get_name()
        {
        }
        /**
         * Returns appropriate setting key for this condition
         *
         * @return [type] [description]
         */
        public function get_key()
        {
        }
        /**
         * Expression to check current condition
         *
         * @return [type] [description]
         */
        public function check($post_id, $posts)
        {
        }
        /**
         * Check condition
         *
         * @return [type] [description]
         */
        public function check_condition($args = array())
        {
        }
        /**
         * Try to get current post ID from request
         *
         * @return [type] [description]
         */
        public function get_post_id()
        {
        }
        /**
         * Renders appropriate UI control for current condition
         *
         * @return array
         */
        public function get_control()
        {
        }
        /**
         * Returns array of allowed sources
         *
         * @return [type] [description]
         */
        public function allowed_sources()
        {
        }
    }
    /**
     * Define Exclude_Posts class
     */
    class Exclude_Posts extends \Jet_Engine\Components\Meta_Boxes\Conditions\Include_Posts
    {
        /**
         * Returns conditions name to show in options
         *
         * @return [type] [description]
         */
        public function get_name()
        {
        }
        /**
         * Returns appropriate setting key for this condition
         *
         * @return [type] [description]
         */
        public function get_key()
        {
        }
        /**
         * Expression to check current condition
         *
         * @return [type] [description]
         */
        public function check($post_id, $posts)
        {
        }
        /**
         * Renders appropriate UI control for current condition
         *
         * @return array
         */
        public function get_control()
        {
        }
    }
}
namespace {
    /**
     * Define Jet_Engine_CPT_Revisions class
     */
    class Jet_Engine_CPT_Revisions
    {
        public $post_type = \null;
        public $field = \null;
        public $field_name = \null;
        public $post_meta_instance = \null;
        public function __construct($post_type = '', $field = array())
        {
        }
        public function check_post_has_changed($post_has_changed, $last_revision, $post)
        {
        }
        public function is_allowed_post($post)
        {
        }
        public function add_revision_field($fields, $post)
        {
        }
        public function is_revision_screen()
        {
        }
        public function is_diff_request()
        {
        }
        public function wp_post_revision_field($value, $field_name, $post, $direction)
        {
        }
        public function save_revision($revision_id)
        {
        }
        public function get_post_meta_instance()
        {
        }
        public function get_current_field_value()
        {
        }
        public function restore_revision($post_id, $revision_id)
        {
        }
    }
    /**
     * Define Jet_Engine_Meta_Boxes_Page_List class
     */
    class Jet_Engine_Meta_Boxes_Page_List extends \Jet_Engine_CPT_Page_Base
    {
        public $is_default = \true;
        /**
         * Class constructor
         */
        public function __construct($manager)
        {
        }
        /**
         * Add new  post type button
         */
        public function add_new_btn($page)
        {
        }
        /**
         * Page slug
         *
         * @return string
         */
        public function get_slug()
        {
        }
        /**
         * Page name
         *
         * @return string
         */
        public function get_name()
        {
        }
        /**
         * Register add controls
         * @return [type] [description]
         */
        public function page_specific_assets()
        {
        }
        /**
         * Print add/edit page template
         */
        public function add_page_template()
        {
        }
        /**
         * Renderer callback
         *
         * @return void
         */
        public function render_page()
        {
        }
    }
    /**
     * Define Jet_Engine_Meta_Boxes_Page_Edit class
     */
    class Jet_Engine_Meta_Boxes_Page_Edit extends \Jet_Engine_CPT_Page_Base
    {
        /**
         * Page slug
         *
         * @return string
         */
        public function get_slug()
        {
        }
        /**
         * Page name
         *
         * @return string
         */
        public function get_name()
        {
        }
        /**
         * Returns currently requested items ID.
         * If this funciton returns an empty result - this is add new item page
         *
         * @return [type] [description]
         */
        public function item_id()
        {
        }
        /**
         * Include meta fields component related assets and templates
         *
         * @return [type] [description]
         */
        public static function enqueue_meta_fields($args = array())
        {
        }
        /**
         * Register add controls
         * @return [type] [description]
         */
        public function page_specific_assets()
        {
        }
        /**
         * Print add/edit page template
         */
        public function add_page_template()
        {
        }
        /**
         * Adds template for meta fields component
         */
        public static function add_meta_fields_template()
        {
        }
        /**
         * Renderer callback
         *
         * @return void
         */
        public function render_page()
        {
        }
    }
    /**
     * Add/Update post type endpoint
     */
    class Jet_Engine_Meta_Boxes_Rest_Get_All extends \Jet_Engine_Base_API_Endpoint
    {
        /**
         * Returns route name
         *
         * @return string
         */
        public function get_name()
        {
        }
        /**
         * API callback
         *
         * @return void
         */
        public function callback($request)
        {
        }
        /**
         * Prepare post type item to return
         *
         * @param  array $item Item data
         * @return array
         */
        public function prepare_item($item)
        {
        }
        /**
         * Returns endpoint request method - GET/POST/PUT/DELTE
         *
         * @return string
         */
        public function get_method()
        {
        }
        /**
         * Check user access to current end-popint
         *
         * @return bool
         */
        public function permission_callback($request)
        {
        }
    }
    /**
     * Add/get single meta box by id endpoint
     */
    class Jet_Engine_Meta_Boxes_Rest_Get extends \Jet_Engine_Base_API_Endpoint
    {
        /**
         * Returns route name
         *
         * @return string
         */
        public function get_name()
        {
        }
        /**
         * API callback
         *
         * @return void
         */
        public function callback($request)
        {
        }
        /**
         * Returns endpoint request method - GET/POST/PUT/DELTE
         *
         * @return string
         */
        public function get_method()
        {
        }
        /**
         * Check user access to current end-popint
         *
         * @return bool
         */
        public function permission_callback($request)
        {
        }
        /**
         * Get query param. Regex with query parameters
         *
         * @return string
         */
        public function get_query_params()
        {
        }
    }
    /**
     * Define Jet_Engine_Rest_Post_Meta class
     */
    class Jet_Engine_Rest_Post_Meta
    {
        public $field = array();
        public $object_subtype = \null;
        public function __construct($field = array(), $post_type = \null)
        {
        }
        public function get_object_type()
        {
        }
        public function prepare_object()
        {
        }
        public function get_field_type()
        {
        }
        public function get_rest_schema($field_type)
        {
        }
        public function register_field()
        {
        }
        public function prepare_object_value($value, $request, $args)
        {
        }
    }
    /**
     * Define Jet_Engine_Rest_User_Meta class
     */
    class Jet_Engine_Rest_User_Meta extends \Jet_Engine_Rest_Post_Meta
    {
        public function get_object_type()
        {
        }
        public function prepare_object()
        {
        }
    }
    /**
     * Define Jet_Engine_Rest_Term_Meta class
     */
    class Jet_Engine_Rest_Term_Meta extends \Jet_Engine_Rest_Post_Meta
    {
        public function get_object_type()
        {
        }
        public function prepare_object()
        {
        }
    }
    /**
     * Add meta box endpoint
     */
    class Jet_Engine_Meta_Boxes_Rest_Add extends \Jet_Engine_Base_API_Endpoint
    {
        /**
         * Returns route name
         *
         * @return string
         */
        public function get_name()
        {
        }
        /**
         * API callback
         *
         * @return void
         */
        public function callback($request)
        {
        }
        /**
         * Returns endpoint request method - GET/POST/PUT/DELTE
         *
         * @return string
         */
        public function get_method()
        {
        }
        /**
         * Check user access to current end-popint
         *
         * @return bool
         */
        public function permission_callback($request)
        {
        }
    }
    /**
     * Edit meta box endpoint
     */
    class Jet_Engine_Meta_Boxes_Rest_Edit extends \Jet_Engine_Base_API_Endpoint
    {
        /**
         * Returns route name
         *
         * @return string
         */
        public function get_name()
        {
        }
        public function safe_get($args = array(), $group = '', $key = '', $default = \false)
        {
        }
        /**
         * API callback
         *
         * @return void
         */
        public function callback($request)
        {
        }
        /**
         * Returns endpoint request method - GET/POST/PUT/DELTE
         *
         * @return string
         */
        public function get_method()
        {
        }
        /**
         * Check user access to current end-popint
         *
         * @return bool
         */
        public function permission_callback($request)
        {
        }
        /**
         * Get query param. Regex with query parameters
         *
         * @return string
         */
        public function get_query_params()
        {
        }
    }
    /**
     * Delete meta box endpoint
     */
    class Jet_Engine_Meta_Boxes_Rest_Delete extends \Jet_Engine_Base_API_Endpoint
    {
        /**
         * Returns route name
         *
         * @return string
         */
        public function get_name()
        {
        }
        /**
         * API callback
         *
         * @return void
         */
        public function callback($request)
        {
        }
        /**
         * Returns endpoint request method - GET/POST/PUT/DELETE
         *
         * @return string
         */
        public function get_method()
        {
        }
        /**
         * Check user access to current end-popint
         *
         * @return bool
         */
        public function permission_callback($request)
        {
        }
        /**
         * Get query param. Regex with query parameters
         *
         * @return string
         */
        public function get_query_params()
        {
        }
    }
}