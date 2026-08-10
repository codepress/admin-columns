<?php

namespace Pods\Whatsit {
    /**
     * Legacy Object class.
     *
     * @since 2.8.0
     */
    class Legacy_Object extends \Pods\Whatsit
    {
        /**
         * {@inheritdoc}
         */
        public function get_clean_args()
        {
        }
        /**
         * {@inheritdoc}
         */
        public function get_arg($arg, $default = null, $strict = false, $raw = false)
        {
        }
        /**
         * {@inheritdoc}
         */
        public function get_name()
        {
        }
    }
    /**
     * Group class.
     *
     * @since 2.8.0
     */
    class Group extends \Pods\Whatsit
    {
        /**
         * {@inheritdoc}
         */
        protected static $type = 'group';
        /**
         * {@inheritdoc}
         */
        public function get_args()
        {
        }
        /**
         * {@inheritdoc}
         */
        public function get_fields(array $args = [])
        {
        }
        /**
         * {@inheritdoc}
         */
        public function get_groups(array $args = [])
        {
        }
        /**
         * {@inheritdoc}
         */
        public function get_arg($arg, $default = null, $strict = false, $raw = false)
        {
        }
    }
    /**
     * Field class.
     *
     * @since 2.8.0
     */
    class Field extends \Pods\Whatsit
    {
        /**
         * {@inheritdoc}
         */
        protected static $type = 'field';
        /**
         * {@inheritdoc}
         */
        public function get_table_info()
        {
        }
        /**
         * Get the type-specific object argument value.
         *
         * @since 2.8.9
         *
         * @param string     $arg     Argument name.
         * @param mixed|null $default Default to use if not set.
         * @param bool       $strict  Whether to check only normal arguments and not special arguments.
         *
         * @return null|mixed Argument value, or null if not set.
         */
        public function get_type_arg($arg, $default = null, $strict = false)
        {
        }
        /**
         * {@inheritdoc}
         */
        public function get_arg($arg, $default = null, $strict = false, $raw = false)
        {
        }
        /**
         * Determine whether this is a required field.
         *
         * @since 2.9.0
         *
         * @return bool Whether this is a required field.
         */
        public function is_required()
        {
        }
        /**
         * Determine whether this is a unique field.
         *
         * @since 2.9.0
         *
         * @return bool Whether this is a unique field.
         */
        public function is_unique()
        {
        }
        /**
         * Determine whether this is a repeatable field.
         *
         * @since 2.9.0
         *
         * @return bool Whether this is a repeatable field.
         */
        public function is_repeatable()
        {
        }
        /**
         * Get related object type from field.
         *
         * @since 2.8.0
         *
         * @return string|null The related object type, or null if not found.
         */
        public function get_related_object_type()
        {
        }
        /**
         * Get related object name from field.
         *
         * @since 2.8.0
         *
         * @return string|null The related object name, or null if not found.
         */
        public function get_related_object_name()
        {
        }
        /**
         * Get related object data from field.
         *
         * @since 2.8.0
         *
         * @return array|null The related object data, or null if not found.
         */
        public function get_related_object_data()
        {
        }
        /**
         * Get the related Pod object if it exists.
         *
         * @since 2.8.0
         *
         * @return \Pods\Whatsit|array|null The related object, or null if not found.
         */
        public function get_related_object()
        {
        }
        /**
         * Determine whether this is a relationship field (pick/file/etc).
         *
         * @since 2.8.9
         *
         * @return bool Whether this is a relationship field (pick/file/etc).
         */
        public function is_relationship()
        {
        }
        /**
         * Determine whether this is a relationship field (pick/file/etc).
         *
         * @since 2.9.7
         *
         * @return bool Whether this is a relationship field (pick/file/etc).
         */
        public function is_file()
        {
        }
        /**
         * Determine whether this is an autocomplete relationship field.
         *
         * @since 2.9.4
         *
         * @return bool Whether this is an autocomplete relationship field.
         */
        public function is_autocomplete_relationship()
        {
        }
        /**
         * Determine whether the relationship field is a simple relationship.
         *
         * @since 2.8.9
         *
         * @return bool|null Whether the relationship field is a simple relationship, or null if not a relationship field.
         */
        public function is_simple_relationship()
        {
        }
        /**
         * Determine whether the separator is excluded for this field.
         *
         * @since 2.9.8
         *
         * @return bool Whether the separator is excluded for this field.
         */
        public function is_separator_excluded()
        {
        }
        /**
         * Get the bi-directional field if it is set.
         *
         * @since 2.8.0
         *
         * @return \Pods\Whatsit|null The bi-directional field if it is set.
         */
        public function get_bidirectional_field()
        {
        }
        /**
         * Get field value limit from field.
         *
         * @since 2.8.0
         *
         * @return int The field value limit.
         */
        public function get_limit()
        {
        }
        /**
         * Get whether the field allows for single or multi tableless field values.
         *
         * @since 2.8.22
         *
         * @return string Whether the field allows for single or multi tableless field values.
         */
        public function get_single_multi()
        {
        }
        /**
         * Determine whether this is a multiple value field.
         *
         * @since 2.9.0
         *
         * @return bool Whether this is a multiple value field.
         */
        public function is_multi_value()
        {
        }
        /**
         * {@inheritdoc}
         */
        public function get_fields(array $args = [])
        {
        }
        /**
         * {@inheritdoc}
         */
        public function get_groups(array $args = [])
        {
        }
    }
    /**
     * Object_Field class.
     *
     * @since 2.8.0
     */
    class Object_Field extends \Pods\Whatsit\Field
    {
        /**
         * {@inheritdoc}
         */
        protected static $type = 'object-field';
    }
    /**
     * Store class.
     *
     * @since 2.8.0
     */
    class Store
    {
        /**
         * @var string
         */
        protected $salt = '';
        /**
         * @var Store[]
         */
        protected static $instances = [];
        /**
         * @var string[]
         */
        protected $object_types = [];
        /**
         * @var string[]
         */
        protected $storage_types = [];
        /**
         * @var Storage[]
         */
        protected $storage_engine = [];
        /**
         * @var \Pods\Whatsit[]
         */
        protected $objects = [];
        /**
         * @var string[]
         */
        protected $object_ids = [];
        /**
         * @var array[]
         */
        protected $objects_in_storage = [];
        public const PLACEHOLDER = '__DEFAULT_LABEL_PLACEHOLDER__';
        /**
         * Store constructor.
         */
        protected function __construct()
        {
        }
        /**
         * Get list of default object type classes.
         *
         * @return string[] List of object type classes.
         */
        public function get_default_object_types()
        {
        }
        /**
         * Get list of default storage type classes.
         *
         * @return string[] List of storage type classes.
         */
        public function get_default_storage_types()
        {
        }
        public static function get_default_object_labels(): array
        {
        }
        /**
         * Get list of default objects.
         *
         * @return array List of objects.
         */
        public function get_default_objects()
        {
        }
        /**
         * Get the Store instance.
         *
         * @param int|null $blog_id The blog ID for the Store instance.
         *
         * @return self The Store instance.
         */
        public static function get_instance($blog_id = null)
        {
        }
        /**
         * Destroy the Store instance.
         *
         * @param int|null $blog_id The blog ID for the Store instance.
         */
        public static function destroy($blog_id = null)
        {
        }
        /**
         * Register an object type to collection.
         *
         * @param string $object_type Pods object type.
         * @param string $class_name  Object class name.
         */
        public function register_object_type($object_type, $class_name)
        {
        }
        /**
         * Unregister an object type to collection.
         *
         * @param string $object_type Pods object type.
         *
         * @return boolean Whether the object type was successfully unregistered.
         */
        public function unregister_object_type($object_type)
        {
        }
        /**
         * Remove all object types from collection.
         */
        public function flush_object_types()
        {
        }
        /**
         * Get list of object types.
         *
         * @return array List of object types.
         */
        public function get_object_types()
        {
        }
        /**
         * Register an object storage type to collection.
         *
         * @param string         $storage_type Pods object storage type.
         * @param string|Storage $class_name   Object storage class name or object.
         */
        public function register_storage_type($storage_type, $class_name)
        {
        }
        /**
         * Unregister an object storage type to collection.
         *
         * @param string $storage_type Pods object storage type.
         *
         * @return boolean Whether the object storage type was successfully unregistered.
         */
        public function unregister_storage_type($storage_type)
        {
        }
        /**
         * Remove all object storage types from collection.
         */
        public function flush_storage_types()
        {
        }
        /**
         * Get list of object storage types.
         *
         * @return array List of object storage types.
         */
        public function get_object_storage_types()
        {
        }
        /**
         * Register an object to collection.
         *
         * @param \Pods\Whatsit|array $object Pods object.
         */
        public function register_object($object)
        {
        }
        /**
         * Remove all objects from collection.
         */
        public function flush_objects()
        {
        }
        /**
         * Unregister an object to collection.
         *
         * @param string|\Pods\Whatsit|array $identifier Object identifier, ID, or Pods object instance.
         *
         * @return boolean Whether the object was successfully unregistered.
         */
        public function unregister_object($identifier)
        {
        }
        /**
         * Flatten objects so that PHP objects are removed but are still registered.
         */
        public function flatten_objects()
        {
        }
        /**
         * Flatten objects so that PHP objects are removed but are still registered.
         */
        public function flatten_object($identifier)
        {
        }
        /**
         * Delete all objects and then flush them from collection.
         */
        public function delete_objects()
        {
        }
        /**
         * Get storage type object.
         *
         * @param string $storage_type Object storage type.
         *
         * @return Storage Storage type object.
         */
        public function get_storage_object($storage_type)
        {
        }
        /**
         * Get object storage type class.
         *
         * @param string $storage_type Object storage type.
         *
         * @return string Storage type class.
         */
        public function get_object_storage_type($storage_type)
        {
        }
        /**
         * Get objects from collection.
         *
         * @param array|null $storage_types The storage types to retrieve.
         *
         * @return \Pods\Whatsit[] List of objects.
         */
        public function get_objects(array $args = [])
        {
        }
        /**
         * Get object from a specific object storage type.
         *
         * @param string                    $object_storage_type The object storage type.
         * @param string|null|\Pods\Whatsit|array $identifier          Object identifier, ID, or the object/array itself.
         *
         * @return \Pods\Whatsit|null Object or null if not found.
         */
        public function get_object_from_storage($object_storage_type, $identifier)
        {
        }
        /**
         * Get object from collection.
         *
         * @param string|null|\Pods\Whatsit|array $identifier Object identifier, ID, or the object/array itself.
         *
         * @return \Pods\Whatsit|null Object or null if not found.
         */
        public function get_object($identifier)
        {
        }
        /**
         * Setup object if it needs to be.
         *
         * @param \Pods\Whatsit|array $object Pods object or array.
         *
         * @return \Pods\Whatsit|null Pods object or null if not able to setup.
         */
        public function setup_object($object)
        {
        }
        /**
         * Get object type class.
         *
         * @param string $object_type Object type.
         *
         * @return string Object type class.
         */
        public function get_object_type($object_type)
        {
        }
        /**
         * Get the current salt for the store.
         *
         * @since 2.9.10
         *
         * @return string
         */
        public function get_salt()
        {
        }
        /**
         * Refresh the salt for the store to indicate a change.
         *
         * @since 2.9.10
         */
        public function refresh_salt()
        {
        }
        /**
         * Rebuild the index of objects.
         *
         * @since 2.9.10
         */
        public function rebuild_index()
        {
        }
        /**
         * Index an object identifier based on args.
         *
         * @since 2.9.10
         *
         * @param string $identifier The object identifier to index.
         * @param array  $args       The list of indexable arguments.
         */
        public function index($identifier, array $args)
        {
        }
        /**
         * Deindex an object identifier based on args.
         *
         * @since 2.9.10
         *
         * @param string $identifier The object identifier to index.
         * @param array  $args       The list of indexable arguments.
         */
        public function deindex($identifier, array $args)
        {
        }
    }
    /**
     * Block_Field class.
     *
     * @since 2.8.0
     */
    class Block_Field extends \Pods\Whatsit\Field
    {
        /**
         * {@inheritdoc}
         */
        protected static $type = 'block-field';
        /**
         * Get list of block args used for each field type.
         *
         * @since 2.8.0
         *
         * @return array[] List of block args used for each field type.
         */
        protected function get_block_arg_mapping()
        {
        }
        /**
         * Get list of Block API arguments to use.
         *
         * @since 2.8.0
         *
         * @return array|null List of Block API arguments or null if not valid.
         */
        public function get_block_args()
        {
        }
        /**
         * Get block args for a pick field type.
         *
         * @return array Block args.
         */
        public function get_pick_block_args()
        {
        }
        /**
         * Get block args for a boolean field type.
         *
         * @return array Block args.
         */
        public function get_boolean_block_args()
        {
        }
        /**
         * {@inheritdoc}
         */
        public function get_table_info()
        {
        }
        /**
         * {@inheritdoc}
         */
        public function get_arg($arg, $default = null, $strict = false, $raw = false)
        {
        }
        /**
         * {@inheritdoc}
         */
        public function get_related_object_type()
        {
        }
        /**
         * {@inheritdoc}
         */
        public function get_related_object_name()
        {
        }
    }
    /**
     * Storage class.
     *
     * @since 2.8.0
     */
    abstract class Storage
    {
        /**
         * @var string
         */
        protected static $type = '';
        /**
         * @var array
         */
        protected $primary_args = [];
        /**
         * @var array
         */
        protected $secondary_args = [];
        /**
         * @var bool
         */
        protected $fallback_mode = true;
        /**
         * Storage constructor.
         *
         * @since 2.8.0
         */
        public function __construct()
        {
        }
        /**
         * Get the object storage label.
         *
         * @return string The object storage label.
         */
        public function get_label()
        {
        }
        /**
         * Get the object storage type.
         *
         * @since 2.8.0
         *
         * @return string The object storage type.
         */
        public function get_object_storage_type()
        {
        }
        /**
         * Get object from storage.
         *
         * @since 2.8.0
         *
         * @return \Pods\Whatsit|null
         */
        public function get()
        {
        }
        /**
         * Get object by identifier from storage.
         *
         * @since 2.9.10
         *
         * @param string|int|\Pods\Whatsit      $identifier The object identifier.
         * @param null|string|int|\Pods\Whatsit $parent     The parent object.
         *
         * @return \Pods\Whatsit|null
         */
        public function get_by_identifier($identifier, $parent = null)
        {
        }
        /**
         * Find objects in storage.
         *
         * @since 2.8.0
         *
         * @param array $args Arguments to use.
         *
         * @return \Pods\Whatsit[]
         */
        public function find(array $args = [])
        {
        }
        /**
         * Setup arg with any potential variations.
         *
         * @since 2.8.0
         *
         * @param array  $args List of arguments.
         * @param string $arg  Argument to setup.
         *
         * @return array List of arguments with arg values setup.
         */
        public function setup_arg(array $args, $arg)
        {
        }
        /**
         * Get arg value.
         *
         * @since 2.8.0
         *
         * @param array  $args List of arguments.
         * @param string $arg  Argument to get values for.
         *
         * @return array|string|int|null Arg value(s).
         */
        public function get_arg_value($args, $arg)
        {
        }
        /**
         * Add an object.
         *
         * @since 2.8.0
         *
         * @param \Pods\Whatsit $object Object to add.
         *
         * @return string|int|false Object name, object ID, or false if not added.
         */
        protected function add_object(\Pods\Whatsit $object)
        {
        }
        /**
         * Add an object.
         *
         * @since 2.8.0
         *
         * @param \Pods\Whatsit $object Object to add.
         *
         * @return string|int|false Object name, object ID, or false if not added.
         */
        public function add(\Pods\Whatsit $object)
        {
        }
        /**
         * Save an object.
         *
         * @since 2.8.0
         *
         * @param \Pods\Whatsit $object Object to save.
         *
         * @return string|int|false Object name, object ID, or false if not saved.
         */
        protected function save_object(\Pods\Whatsit $object)
        {
        }
        /**
         * Save an object.
         *
         * @since 2.8.0
         *
         * @param \Pods\Whatsit $object Object to save.
         *
         * @return string|int|false Object name, object ID, or false if not saved.
         */
        public function save(\Pods\Whatsit $object)
        {
        }
        /**
         * Duplicate an object.
         *
         * @since 2.8.0
         *
         * @param \Pods\Whatsit $object Object to duplicate.
         *
         * @return string|int|false Duplicated object name, duplicated object ID, or false if not duplicated.
         */
        public function duplicate(\Pods\Whatsit $object)
        {
        }
        /**
         * Delete an object.
         *
         * @since 2.8.0
         *
         * @param \Pods\Whatsit $object Object to delete.
         *
         * @return bool
         */
        protected function delete_object(\Pods\Whatsit $object)
        {
        }
        /**
         * Delete an object.
         *
         * @since 2.8.0
         *
         * @param \Pods\Whatsit $object Object to delete.
         *
         * @return bool
         */
        public function delete(\Pods\Whatsit $object)
        {
        }
        /**
         * Reset an object's item data.
         *
         * @since 2.8.0
         *
         * @param \Pods\Whatsit $object Object of items to reset.
         *
         * @return bool
         */
        public function reset(\Pods\Whatsit $object)
        {
        }
        /**
         * Get object argument data.
         *
         * @since 2.8.0
         *
         * @param \Pods\Whatsit $object Object with arguments to save.
         *
         * @return array
         */
        public function get_args(\Pods\Whatsit $object)
        {
        }
        /**
         * Save object argument data.
         *
         * @since 2.8.0
         *
         * @param \Pods\Whatsit $object Object with arguments to save.
         *
         * @return bool
         */
        public function save_args(\Pods\Whatsit $object)
        {
        }
        /**
         * Whether to enable fallback mode for falling back to parent storage options.
         *
         * @param bool $enabled Whether to enable fallback mode.
         */
        public function fallback_mode($enabled = true)
        {
        }
        /**
         * Setup object from an identifier.
         *
         * @since 2.9.10
         *
         * @param string $value         The identifier.
         * @param bool   $force_refresh Whether to force the refresh of the object.
         *
         * @return \Pods\Whatsit|null
         */
        public function to_object($value, $force_refresh = false)
        {
        }
    }
}
namespace Pods\Whatsit\Storage {
    /**
     * Collection class.
     *
     * @since 2.8.0
     */
    class Collection extends \Pods\Whatsit\Storage
    {
        /**
         * {@inheritdoc}
         */
        protected static $type = 'collection';
        /**
         * @var array
         */
        protected static $compatible_types = ['collection' => 'collection', 'file' => 'file'];
        /**
         * @var array
         */
        protected $secondary_args = [];
        /**
         * {@inheritdoc}
         */
        public function get_label()
        {
        }
        /**
         * {@inheritdoc}
         */
        public function get(array $args = [])
        {
        }
        /**
         * {@inheritdoc}
         */
        public function get_by_identifier($identifier, $parent = null)
        {
        }
        /**
         * {@inheritdoc}
         */
        public function find(array $args = [])
        {
        }
        /**
         * {@inheritdoc}
         */
        protected function save_object(\Pods\Whatsit $object)
        {
        }
        /**
         * {@inheritdoc}
         */
        protected function delete_object(\Pods\Whatsit $object)
        {
        }
        /**
         * {@inheritdoc}
         */
        public function save_args(\Pods\Whatsit $object)
        {
        }
        /**
         * Setup object from an identifier.
         *
         * @param string $value         The identifier.
         * @param bool   $force_refresh Whether to force the refresh of the object.
         *
         * @return \Pods\Whatsit|null
         */
        public function to_object($value, $force_refresh = false)
        {
        }
    }
    /**
     * Post_Type class.
     *
     * @since 2.8.0
     */
    class Post_Type extends \Pods\Whatsit\Storage\Collection
    {
        /**
         * {@inheritdoc}
         */
        protected static $type = 'post_type';
        /**
         * @var array
         */
        protected $primary_args = ['object_type' => 'object_type', 'object_storage_type' => 'object_storage_type', 'ID' => 'id', 'post_name' => 'name', 'post_title' => 'label', 'post_content' => 'description', 'post_parent' => 'parent', 'menu_order' => 'weight'];
        /**
         * @var array
         */
        protected $secondary_args = ['type', 'object'];
        /**
         * {@inheritdoc}
         */
        public function get_label()
        {
        }
        /**
         * {@inheritdoc}
         */
        public function get(array $args = [])
        {
        }
        /**
         * {@inheritdoc}
         */
        public function get_by_identifier($identifier, $parent = null)
        {
        }
        /**
         * {@inheritdoc}
         */
        public function find(array $args = [])
        {
        }
        /**
         * {@inheritdoc}
         */
        protected function add_object(\Pods\Whatsit $object)
        {
        }
        /**
         * {@inheritdoc}
         */
        protected function save_object(\Pods\Whatsit $object)
        {
        }
        /**
         * {@inheritdoc}
         */
        public function get_args(\Pods\Whatsit $object)
        {
        }
        /**
         * {@inheritdoc}
         */
        public function save_args(\Pods\Whatsit $object)
        {
        }
        /**
         * {@inheritdoc}
         */
        protected function delete_object(\Pods\Whatsit $object)
        {
        }
        /**
         * {@inheritdoc}
         */
        public function to_object($value, $force_refresh = false)
        {
        }
        /**
         * Setup object from a Post ID or Post object.
         *
         * @param \WP_Post|array|int $post          Post object or ID of the object.
         * @param bool               $force_refresh Whether to force the refresh of the object.
         *
         * @return \Pods\Whatsit|null
         */
        public function to_object_from_post($post, $force_refresh = false)
        {
        }
    }
    /**
     * File class.
     *
     * @since 2.9.0
     */
    class File extends \Pods\Whatsit\Storage\Collection
    {
        /**
         * {@inheritdoc}
         */
        protected static $type = 'file';
        /**
         * @var array
         */
        protected static $compatible_types = ['file' => 'file'];
        /**
         * {@inheritdoc}
         */
        public function get_label()
        {
        }
    }
}
namespace Pods\Whatsit {
    /**
     * Pod class.
     *
     * @since 2.8.0
     */
    class Pod extends \Pods\Whatsit
    {
        /**
         * {@inheritdoc}
         */
        protected static $type = 'pod';
        /**
         * Get the storage used for the Pod data (meta, table, etc).
         *
         * @since 2.8.1
         *
         * @param boolean $strict Whether to only get the argument, otherwise the default will be returned.
         *
         * @return string The storage used for the Pod data (meta, table, etc).
         */
        public function get_storage($strict = false)
        {
        }
        /**
         * Get the default storage used for the Pod data (meta, table, etc) based on the current Pod type.
         *
         * @since 2.9.16
         *
         * @return string The default storage used for the Pod data (meta, table, etc).
         */
        public function get_default_storage()
        {
        }
        /**
         * {@inheritdoc}
         */
        public function get_args()
        {
        }
        /**
         * {@inheritdoc}
         */
        public function export(array $args = [])
        {
        }
        /**
         * {@inheritdoc}
         */
        public function get_arg($arg, $default = null, $strict = false, $raw = false)
        {
        }
        /**
         * {@inheritdoc}
         */
        public function get_object_fields()
        {
        }
        /**
         * {@inheritdoc}
         */
        public function count_object_fields()
        {
        }
        /**
         * {@inheritdoc}
         */
        public function get_table_info()
        {
        }
        /**
         * Determine whether this is a table-based Pod.
         *
         * @since 2.9.0
         *
         * @return bool Whether this is a table-based Pod.
         */
        public function is_table_based()
        {
        }
        /**
         * Determine whether this is a meta-based Pod.
         *
         * @since 2.9.0
         *
         * @return bool Whether this is a meta-based Pod.
         */
        public function is_meta_based()
        {
        }
        /**
         * Determine whether the Pod is an extending an existing content type.
         *
         * @since 2.8.4
         *
         * @return bool Whether the Pod is an extending an existing content type.
         */
        public function is_extended()
        {
        }
        /**
         * Count the total rows for the pod.
         *
         * @since 2.8.9
         *
         * @return int The total rows for the Pod.
         */
        public function count_rows()
        {
        }
        /**
         * Count the total row meta for the pod.
         *
         * @since 2.8.9
         *
         * @return int The total row meta for the Pod.
         */
        public function count_row_meta()
        {
        }
        /**
         * Count the total wp_podsrel rows for the pod.
         *
         * @since 2.8.9
         *
         * @return int The total wp_podsrel rows for the pod.
         */
        public function count_podsrel_rows()
        {
        }
    }
    /**
     * Block class.
     *
     * @since 2.8.0
     */
    class Block extends \Pods\Whatsit\Pod
    {
        /**
         * {@inheritdoc}
         */
        protected static $type = 'block';
        /**
         * Get list of Block API arguments to use.
         *
         * @since 2.8.0
         *
         * @return array List of Block API arguments.
         */
        public function get_block_args()
        {
        }
        /**
         * Render the template for the block.
         *
         * @since 2.8.0
         *
         * @param array         $attributes The block instance argument values.
         * @param string        $content    The block inner content.
         * @param \WP_Block|null $block_obj  The block object.
         *
         * @return  string   The HTML render for the block.
         */
        public function render($attributes, $content, $block_obj = null)
        {
        }
        /**
         * Render the template for the block.
         *
         * @since 2.8.0
         *
         * @param string        $template_path The block render template path.
         * @param array         $attributes    The block instance argument values.
         * @param string        $content       The block inner content.
         * @param \WP_Block|null $block_obj     The block object.
         *
         * @return  string   The HTML render for the block.
         */
        public function render_template($template_path, $attributes, $content, $block_obj = null)
        {
        }
        /**
         * Get list of Block API fields for the block.
         *
         * @since 2.8.0
         *
         * @return array List of Block API fields.
         */
        public function get_block_fields()
        {
        }
        /**
         * {@inheritdoc}
         */
        public function get_args()
        {
        }
        /**
         * {@inheritdoc}
         */
        public function get_fields(array $args = [])
        {
        }
    }
    /**
     * Block Collection class.
     *
     * @since 2.8.0
     */
    class Block_Collection extends \Pods\Whatsit\Pod
    {
        /**
         * {@inheritdoc}
         */
        protected static $type = 'block-collection';
        /**
         * Get list of Block Collection API arguments to use.
         *
         * @since 2.8.0
         *
         * @return array List of Block Collection API arguments.
         */
        public function get_block_collection_args()
        {
        }
        /**
         * {@inheritdoc}
         */
        public function get_args()
        {
        }
        /**
         * {@inheritdoc}
         */
        public function get_fields(array $args = [])
        {
        }
        /**
         * {@inheritdoc}
         */
        public function get_table_info()
        {
        }
    }
    /**
     * Page class.
     *
     * @since 2.8.0
     */
    class Page extends \Pods\Whatsit\Legacy_Object
    {
        /**
         * {@inheritdoc}
         */
        protected static $type = 'page';
    }
    /**
     * Template class.
     *
     * @since 2.8.0
     */
    class Template extends \Pods\Whatsit\Legacy_Object
    {
        /**
         * {@inheritdoc}
         */
        protected static $type = 'template';
    }
}
namespace {
    /**
     * @package Pods\Fields
     */
    class PodsField_Pick extends \PodsField
    {
        /**
         * {@inheritdoc}
         */
        public static $group = 'Relationships / Media';
        /**
         * {@inheritdoc}
         */
        public static $type = 'pick';
        /**
         * {@inheritdoc}
         */
        public static $label = 'Relationship';
        /**
         * {@inheritdoc}
         */
        protected static $api = \false;
        /**
         * Available Related Objects.
         *
         * @var array
         * @since 2.3.0
         */
        public static $related_objects = array();
        /**
         * Custom Related Objects
         *
         * @var array
         * @since 2.3.0
         */
        public static $custom_related_objects = array();
        /**
         * Data used during validate / save to avoid extra queries.
         *
         * @var array
         * @since 2.3.0
         */
        public static $related_data = array();
        /**
         * Data used during input method (mainly for autocomplete).
         *
         * @var array
         * @since 2.3.0
         */
        public static $field_data = array();
        /**
         * {@inheritdoc}
         */
        public function setup()
        {
        }
        /**
         * {@inheritdoc}
         */
        public function admin_init()
        {
        }
        /**
         * {@inheritdoc}
         */
        public function options()
        {
        }
        /**
         * {@inheritdoc}
         */
        public function prepare($options = \null)
        {
        }
        /**
         * Register a related object.
         *
         * @param string $name    Object name.
         * @param string $label   Object label.
         * @param array  $options Object options.
         *
         * @return array|boolean Object array or false if unsuccessful
         * @since 2.3.0
         */
        public function register_related_object($name, $label, $options = \null)
        {
        }
        /**
         * Setup related objects.
         *
         * @param boolean $force Whether to force refresh of related objects.
         *
         * @return bool True when data has been loaded
         * @since 2.3.0
         */
        public function setup_related_objects($force = \false)
        {
        }
        /**
         * Return available related objects
         *
         * @param boolean $force Whether to force refresh of related objects.
         *
         * @return array Field selection array
         * @since 2.3.0
         */
        public function related_objects($force = \false)
        {
        }
        /**
         * Return available simple object names
         *
         * @return array Simple object names
         * @since 2.3.0
         */
        public function simple_objects()
        {
        }
        /**
         * Return available bidirectional object names
         *
         * @return array Bidirectional object names
         * @since 2.3.4
         */
        public function bidirectional_objects()
        {
        }
        /**
         * {@inheritdoc}
         */
        public function schema($options = \null)
        {
        }
        /**
         * {@inheritdoc}
         */
        public function display($value = \null, $name = \null, $options = \null, $pod = \null, $id = \null)
        {
        }
        /**
         * {@inheritdoc}
         */
        public function input($name, $value = \null, $options = \null, $pod = \null, $id = \null)
        {
        }
        /**
         * {@inheritdoc}
         */
        public function build_dfv_field_options($options, $args)
        {
        }
        /**
         * Build DFV autocomplete AJAX data.
         *
         * @param array|\Pods\Whatsit\Field  $options DFV options.
         * @param object $args    {
         *  Field information arguments.
         *
         *     @type string     $name    Field name.
         *     @type string     $type    Field type.
         *     @type array      $options Field options.
         *     @type mixed      $value   Current value.
         *     @type array      $pod     Pod information.
         *     @type int|string $id      Current item ID.
         * }
         * @param bool   $ajax    True if ajax mode should be used.
         *
         * @return array
         */
        public function build_dfv_autocomplete_ajax_data($options, $args, $ajax = \false)
        {
        }
        /**
         * {@inheritdoc}
         */
        public function build_dfv_field_config($args)
        {
        }
        /**
         * {@inheritdoc}
         */
        public function build_dfv_field_item_data($args)
        {
        }
        /**
         * Loop through relationship data and expand item data with additional information for DFV.
         *
         * @param array  $data    Item data to expand.
         * @param object $args    {
         *      Field information arguments.
         *
         *     @type string     $name    Field name.
         *     @type string     $type    Field type.
         *     @type array      $options Field options.
         *     @type mixed      $value   Current value.
         *     @type array      $pod     Pod information.
         *     @type int|string $id      Current item ID.
         * }
         *
         * @return array
         */
        public function build_dfv_field_item_data_recurse($data, $args)
        {
        }
        /**
         * Loop through relationship data and expand item data with additional information for DFV.
         *
         * @param int|string $item_id    Item ID.
         * @param string     $item_title Item title.
         * @param object     $args       {
         *      Field information arguments.
         *
         *     @type string      $name    Field name.
         *     @type string      $type    Field type.
         *     @type array       $options Field options.
         *     @type mixed       $value   Current value.
         *     @type array       $pod     Pod information.
         *     @type int|string  $id      Current item ID.
         * }
         *
         * @return array
         */
        public function build_dfv_field_item_data_recurse_item($item_id, $item_title, $args)
        {
        }
        /**
         * {@inheritdoc}
         */
        public function validate($value, $name = \null, $options = \null, $fields = \null, $pod = \null, $id = \null, $params = \null)
        {
        }
        /**
         * {@inheritdoc}
         */
        public function save($value, $id = \null, $name = \null, $options = \null, $fields = \null, $pod = \null, $params = \null)
        {
        }
        /**
         * Delete the value from the DB
         *
         * @param int|null    $id      Item ID.
         * @param string|null $name    Field name.
         * @param array|null  $options Field options.
         * @param array|null  $pod     Pod options.
         *
         * @since 2.3.0
         */
        public function delete($id = \null, $name = \null, $options = \null, $pod = \null)
        {
        }
        /**
         * {@inheritdoc}
         */
        public function ui($id, $value, $name = \null, $options = \null, $fields = \null, $pod = \null)
        {
        }
        /**
         * Get the raw data from the field data provided.
         *
         * @since 2.9.9
         *
         * @param array|\Pods\Whatsit\Field $field The field data.
         *
         * @return array|mixed
         */
        public function get_raw_data($field)
        {
        }
        /**
         * {@inheritdoc}
         */
        public function data($name, $value = \null, $options = \null, $pod = \null, $id = \null, $in_form = \true)
        {
        }
        /**
         * Convert a simple value to the correct value
         *
         * @param string            $name    The name of the field.
         * @param string|array|null $value   The value of the field.
         * @param array|null        $options Field options.
         * @param array|null        $pod     Pod data.
         * @param int|null          $id      Item ID.
         * @param boolean           $raw     Whether to return the raw list of keys (true) or convert to key=>value (false).
         *
         * @return mixed Corrected value
         */
        public function simple_value($name, $value = \null, $options = \null, $pod = \null, $id = \null, $raw = \false)
        {
        }
        /**
         * Get the label from a pick value.
         *
         * @param string            $name    The name of the field.
         * @param string|array|null $value   The value of the field.
         * @param array|null        $options Field options.
         * @param array|null        $pod     Pod data.
         * @param int|null          $id      Item ID.
         *
         * @return string
         *
         * @since 2.2.0
         */
        public function value_to_label($name, $value = \null, $options = \null, $pod = \null, $id = \null)
        {
        }
        /**
         * Get available items from a relationship field.
         *
         * @param array|string $field         Field array or field name.
         * @param array        $deprecated       Field options array overrides.
         * @param array        $object_params Additional get_object_data options.
         *
         * @return array An array of available items from a relationship field
         */
        public function get_field_data($field, $deprecated = \null, $object_params = array())
        {
        }
        /**
         * Get data from relationship objects.
         *
         * @param array $object_params Object data parameters.
         *
         * @return array|bool Object data
         */
        public function get_object_data($object_params = \null)
        {
        }
        /**
         * Handle autocomplete AJAX.
         *
         * @since 2.3.0
         */
        public function admin_ajax_relationship()
        {
        }
        /**
         * Data callback for Post Stati.
         *
         * @param string|null       $name    The name of the field.
         * @param string|array|null $value   The value of the field.
         * @param array|null        $options Field options.
         * @param array|null        $pod     Pod data.
         * @param int|null          $id      Item ID.
         *
         * @return array
         *
         * @since 2.3.0
         */
        public function data_post_stati($name = \null, $value = \null, $options = \null, $pod = \null, $id = \null)
        {
        }
        /**
         * Data callback for Post Stati (with any).
         *
         * @param string|null       $name    The name of the field.
         * @param string|array|null $value   The value of the field.
         * @param array|null        $options Field options.
         * @param array|null        $pod     Pod data.
         * @param int|null          $id      Item ID.
         *
         * @return array
         *
         * @since 2.9.10
         */
        public function data_post_stati_with_any($name = \null, $value = \null, $options = \null, $pod = \null, $id = \null)
        {
        }
        /**
         * Data callback for User Roles.
         *
         * @param string|null       $name    The name of the field.
         * @param string|array|null $value   The value of the field.
         * @param array|null        $options Field options.
         * @param array|null        $pod     Pod data.
         * @param int|null          $id      Item ID.
         *
         * @return array
         *
         * @since 2.3.0
         */
        public function data_roles($name = \null, $value = \null, $options = \null, $pod = \null, $id = \null)
        {
        }
        /**
         * Data callback for User Capabilities.
         *
         * @param string|null       $name    The name of the field.
         * @param string|array|null $value   The value of the field.
         * @param array|null        $options Field options.
         * @param array|null        $pod     Pod data.
         * @param int|null          $id      Item ID.
         *
         * @return array
         *
         * @since 2.3.0
         */
        public function data_capabilities($name = \null, $value = \null, $options = \null, $pod = \null, $id = \null)
        {
        }
        /**
         * Data callback for Image Sizes.
         *
         * @param string|null       $name    The name of the field.
         * @param string|array|null $value   The value of the field.
         * @param array|null        $options Field options.
         * @param array|null        $pod     Pod data.
         * @param int|null          $id      Item ID.
         *
         * @return array
         *
         * @since 2.3.0
         */
        public function data_image_sizes($name = \null, $value = \null, $options = \null, $pod = \null, $id = \null)
        {
        }
        /**
         * Data callback for Post Types
         *
         * @param string       $name    The name of the field
         * @param string|array $value   The value of the field
         * @param array        $options Field options
         * @param array        $pod     Pod data
         * @param int          $id      Item ID
         *
         * @return array
         *
         * @since 2.3.0
         */
        public function data_post_types($name = \null, $value = \null, $options = \null, $pod = \null, $id = \null)
        {
        }
        /**
         * Data callback for Taxonomies
         *
         * @param string       $name    The name of the field
         * @param string|array $value   The value of the field
         * @param array        $options Field options
         * @param array        $pod     Pod data
         * @param int          $id      Item ID
         *
         * @return array
         *
         * @since 2.3.0
         */
        public function data_taxonomies($name = \null, $value = \null, $options = \null, $pod = \null, $id = \null)
        {
        }
        /**
         * Data callback for Countries.
         *
         * @param string|null       $name    The name of the field.
         * @param string|array|null $value   The value of the field.
         * @param array|null        $options Field options.
         * @param array|null        $pod     Pod data.
         * @param int|null          $id      Item ID.
         *
         * @return array
         *
         * @since 2.3.0
         */
        public function data_countries($name = \null, $value = \null, $options = \null, $pod = \null, $id = \null)
        {
        }
        /**
         * Data callback for US States.
         *
         * @param string|null       $name    The name of the field.
         * @param string|array|null $value   The value of the field.
         * @param array|null        $options Field options.
         * @param array|null        $pod     Pod data.
         * @param int|null          $id      Item ID.
         *
         * @return array
         *
         * @since 2.3.0
         */
        public function data_us_states($name = \null, $value = \null, $options = \null, $pod = \null, $id = \null)
        {
        }
        /**
         * Data callback for CA Provinces.
         *
         * @param string|null       $name    The name of the field.
         * @param string|array|null $value   The value of the field.
         * @param array|null        $options Field options.
         * @param array|null        $pod     Pod data.
         * @param int|null          $id      Item ID.
         *
         * @return array
         *
         * @since 2.3.0
         */
        public function data_ca_provinces($name = \null, $value = \null, $options = \null, $pod = \null, $id = \null)
        {
        }
        /**
         * Data callback for Days of the Week.
         *
         * @param string|null       $name    The name of the field.
         * @param string|array|null $value   The value of the field.
         * @param array|null        $options Field options.
         * @param array|null        $pod     Pod data.
         * @param int|null          $id      Item ID.
         *
         * @return array
         *
         * @since 2.3.0
         */
        public function data_days_of_week($name = \null, $value = \null, $options = \null, $pod = \null, $id = \null)
        {
        }
        /**
         * Data callback for Months of the Year.
         *
         * @param string|null       $name    The name of the field.
         * @param string|array|null $value   The value of the field.
         * @param array|null        $options Field options.
         * @param array|null        $pod     Pod data.
         * @param int|null          $id      Item ID.
         *
         * @return array
         *
         * @since 2.3.0
         */
        public function data_months_of_year($name = \null, $value = \null, $options = \null, $pod = \null, $id = \null)
        {
        }
        /**
         * Add our modal input to the form so we can track whether we're in our modal during saving or not.
         */
        public function admin_modal_input()
        {
        }
        /**
         * Bail to send new saved data back to our modal handler.
         *
         * @param int    $item_id    Item ID.
         * @param string $item_title Item title.
         * @param object $field_args Field arguments.
         */
        public function admin_modal_bail($item_id, $item_title, $field_args)
        {
        }
        /**
         * Bail to send new saved data back to our modal handler.
         *
         * @param int    $item_id    Item ID.
         * @param string $item_title Item title.
         * @param object $field_args Field arguments.
         */
        public function admin_modal_bail_JSON($item_id, $item_title, $field_args)
        {
        }
        /**
         * Bail on Post save redirect for Admin modal.
         *
         * @param string $location The destination URL.
         * @param int    $post_id  The post ID.
         *
         * @return string
         */
        public function admin_modal_bail_post_redirect($location, $post_id)
        {
        }
        /**
         * Hook into term updating process to bail on redirect.
         */
        public function admin_modal_bail_term_action()
        {
        }
        /**
         * Hook into term creation process to bail after success.
         *
         * @todo Try and catch the added tr node on the table tbody.
         */
        public function admin_modal_bail_term_action_add_new()
        {
        }
        /**
         * Bail on Term save redirect for Admin modal.
         *
         * @param int    $term_id  Term ID.
         * @param int    $tt_id    Term taxonomy ID.
         * @param string $taxonomy Taxonomy slug.
         */
        public function admin_modal_bail_term($term_id, $tt_id, $taxonomy)
        {
        }
        /**
         * Hook into user updating process to bail on redirect.
         */
        public function admin_modal_bail_user_action()
        {
        }
        /**
         * Bail on User save redirect for Admin modal.
         *
         * @param string $location The destination URL.
         *
         * @return string
         */
        public function admin_modal_bail_user_redirect($location)
        {
        }
        /**
         * Bail on Pod item save for Admin modal.
         *
         * @param int       $id     Item ID.
         * @param array     $params save_pod_item parameters.
         * @param null|Pods $obj    Pod object (if set).
         */
        public function admin_modal_bail_pod($id, $params, $obj)
        {
        }
        /**
         * Build field data for Pods DFV.
         *
         * @param object $args            {
         *     Field information arguments.
         *
         *     @type string     $name            Field name.
         *     @type string     $type            Field type.
         *     @type array      $options         Field options.
         *     @type mixed      $value           Current value.
         *     @type array      $pod             Pod information.
         *     @type int|string $id              Current item ID.
         *     @type string     $form_field_type HTML field type.
         * }
         *
         * @return array
         */
        public function build_dfv_field_data($args)
        {
        }
    }
    /**
     * @package Pods
     */
    class PodsForm
    {
        /**
         * @var PodsForm
         */
        protected static $instance = \null;
        /**
         * @var string
         */
        public static $field = \null;
        /**
         * @var string
         */
        public static $field_group = \null;
        /**
         * @var string
         */
        public static $field_type = \null;
        /**
         * @var array
         */
        public static $field_types = array();
        /**
         * @var array
         */
        public static $loaded = array();
        /**
         * @var int
         */
        public static $form_counter = 0;
        /**
         * Singleton handling for a basic pods_form() request
         *
         * @return \PodsForm
         *
         * @since 2.3.5
         */
        public static function init()
        {
        }
        /**
         * Output a field's label
         *
         * @since 2.0.0
         */
        /**
         * Output a field's label
         *
         * @param string $name    Field name
         * @param string $label   Label text
         * @param string $help    Help text
         * @param array  $options Field options
         *
         * @return string Label HTML
         *
         * @since 2.0.0
         */
        public static function label($name, $label, $help = '', $options = \null)
        {
        }
        /**
         * Output a Field Comment Paragraph
         *
         * @param string $name    Field name
         * @param string $message Field comments
         * @param array  $options Field options
         *
         * @return string Comment HTML
         *
         * @since 2.0.0
         */
        public static function comment($name, $message = \null, $options = \null)
        {
        }
        /**
         * Output a field
         *
         * @param string     $name    Field name
         * @param mixed      $value   Field value
         * @param string     $type    Field type
         * @param array      $options Field options
         * @param array|Pods $pod     Pod data or the Pods object.
         * @param int        $id      Item ID
         *
         * @return string Field HTML
         *
         * @since 2.0.0
         */
        public static function field($name, $value, $type = 'text', $options = \null, $pod = \null, $id = \null)
        {
        }
        /**
         * Output field type 'db'
         *
         * Used for field names and other places where only [a-z0-9_] is accepted
         *
         * @since 2.0.0
         *
         * @param      $name
         * @param null $value
         * @param null $options
         *
         * @return mixed|void
         */
        protected static function field_db($name, $value = \null, $options = \null)
        {
        }
        /**
         * Output a hidden field
         *
         * @param      $name
         * @param null $value
         * @param null $options
         *
         * @return mixed|void
         */
        protected static function field_hidden($name, $value = \null, $options = \null)
        {
        }
        /**
         * Returns a submit button, with provided text and appropriate class, copied from WP Core for use on the frontend
         *
         * @see   get_submit_button
         *
         * @param string       $text             The text of the button (defaults to 'Save Changes')
         * @param string       $type             The type of button. One of: primary, secondary, delete
         * @param string       $name             The HTML name of the submit button. Defaults to "submit". If no id
         *                                       attribute is given in $other_attributes below, $name will be used as the
         *                                       button's id.
         * @param bool         $wrap             True if the output button should be wrapped in a paragraph tag,
         *                                       false otherwise. Defaults to true
         * @param array|string $other_attributes Other attributes that should be output with the button,
         *                                       mapping attributes to their values, such as array( 'tabindex' => '1' ).
         *                                       These attributes will be output as attribute="value", such as
         *                                       tabindex="1".
         *                                       Defaults to no other attributes. Other attributes can also be provided as
         *                                       a
         *                                       string such as 'tabindex="1"', though the array format is typically
         *                                       cleaner.
         *
         * @since 2.7.0
         * @return string
         */
        public static function submit_button($text = \null, $type = 'primary large', $name = 'submit', $wrap = \true, $other_attributes = \null)
        {
        }
        /**
         * Output a row (label, field, and comment)
         *
         * @param string $name    Field name
         * @param mixed  $value   Field value
         * @param string $type    Field type
         * @param array  $options Field options
         * @param array  $pod     Pod data
         * @param int    $id      Item ID
         *
         * @return string Row HTML
         *
         * @since 2.0.0
         */
        public static function row($name, $value, $type = 'text', $options = \null, $pod = \null, $id = \null)
        {
        }
        /**
         * Output a field's attributes
         *
         * @since 2.0.0
         *
         * @param      $attributes
         * @param null       $name
         * @param null       $type
         * @param null       $options
         */
        public static function attributes($attributes, $name = \null, $type = \null, $options = \null)
        {
        }
        /**
         * Output a field's data (for use with jQuery)
         *
         * @since 2.0.0
         *
         * @param      $data
         * @param null $name
         * @param null $type
         * @param null $options
         */
        public static function data($data, $name = \null, $type = \null, $options = \null)
        {
        }
        /**
         * Merge attributes and handle classes
         *
         * @since 2.0.0
         *
         * @param        $attributes
         * @param null       $name
         * @param null       $type
         * @param null       $options
         * @param string     $classes
         *
         * @return array
         */
        public static function merge_attributes($attributes, $name = \null, $type = \null, $options = \null, $classes = '')
        {
        }
        /**
         * Setup options for a field and store them for later use
         *
         * @param $type
         * @param $options
         *
         * @return array
         *
         * @static
         *
         * @since 2.0.0
         */
        public static function options($type, $options)
        {
        }
        /**
         * Get options for a field type and setup defaults
         *
         * @static
         *
         * @param      $type
         *
         * @param null $options
         *
         * @return array|null
         * @since 2.0.0
         */
        public static function options_setup($type = \null, $options = \null)
        {
        }
        /**
         * Get Admin options for a field type and setup defaults
         *
         * @static
         *
         * @param $type
         *
         * @return array|null
         *
         * @since 2.0.0
         */
        public static function ui_options($type)
        {
        }
        /**
         * Get options for a field and setup defaults
         *
         * @param null $fields
         * @param null $core_defaults
         * @param bool $single
         *
         * @return array|null
         *
         * @static
         * @since 2.0.0
         */
        public static function fields_setup($fields = \null, $core_defaults = \null, $single = \false)
        {
        }
        /**
         * Get options for a field and setup defaults
         *
         * @static
         *
         * @param null|array|string|\Pods\Whatsit\Field $field
         * @param null|array $core_defaults
         * @param null|string $type
         *
         * @return array|null
         *
         * @since 2.0.0
         */
        public static function field_setup($field = \null, $core_defaults = \null, $type = \null)
        {
        }
        /**
         * Setup dependency / exclusion classes
         *
         * @param array  $options array( 'depends-on' => ..., 'excludes-on' => ...)
         * @param string $prefix
         *
         * @return array
         * @static
         * @since 2.0.0
         */
        public static function dependencies($options, $prefix = 'pods-form-ui-')
        {
        }
        /**
         * Change the value of the field
         *
         * @param        $type
         * @param mixed  $value
         * @param string $name
         * @param array  $options
         * @param array  $pod
         * @param int    $id
         * @param array  $traverse
         *
         * @return array|mixed|null|object
         * @internal param array $fields
         * @since 2.3.0
         */
        public static function value($type, $value = \null, $name = \null, $options = \null, $pod = \null, $id = \null, $traverse = \null)
        {
        }
        /**
         * Change the way the value of the field is displayed with Pods::get
         *
         * @param        $type
         * @param mixed  $value
         * @param string $name
         * @param array  $options
         * @param array  $pod
         * @param int    $id
         * @param array  $traverse
         *
         * @return array|mixed|null|void
         * @internal param array $fields
         * @since 2.0.0
         */
        public static function display($type, $value = \null, $name = \null, $options = \null, $pod = \null, $id = \null, $traverse = \null)
        {
        }
        /**
         * Setup regex for JS / PHP
         *
         * @static
         *
         * @param $type
         * @param $options
         *
         * @return mixed|void
         * @since 2.0.0
         */
        public static function regex($type, $options)
        {
        }
        /**
         * Setup value preparation for sprintf
         *
         * @static
         *
         * @param $type
         * @param $options
         *
         * @return mixed|void
         * @since 2.0.0
         */
        public static function prepare($type, $options)
        {
        }
        /**
         * Validate a value before it's saved
         *
         * @param string       $type
         * @param mixed        $value
         * @param string       $name
         * @param array        $options
         * @param array        $fields
         * @param array        $pod
         * @param int          $id
         * @param array|object $params
         *
         * @static
         *
         * @since 2.0.0
         * @return bool|mixed|void
         */
        public static function validate($type, $value, $name = \null, $options = \null, $fields = \null, $pod = \null, $id = \null, $params = \null)
        {
        }
        /**
         * Change the value or perform actions after validation but before saving to the DB
         *
         * @param string $type
         * @param mixed  $value
         * @param int    $id
         * @param string $name
         * @param array  $options
         * @param array  $fields
         * @param array  $pod
         * @param object $params
         *
         * @static
         *
         * @since 2.0.0
         * @return mixed
         */
        public static function pre_save($type, $value, $id = \null, $name = \null, $options = \null, $fields = \null, $pod = \null, $params = \null)
        {
        }
        /**
         * Save the value to the DB
         *
         * @param string $type
         * @param mixed  $value
         * @param int    $id
         * @param string $name
         * @param array  $options
         * @param array  $fields
         * @param array  $pod
         * @param object $params
         *
         * @static
         *
         * @since 2.3.0
         * @return null
         */
        public static function save($type, $value, $id = \null, $name = \null, $options = \null, $fields = \null, $pod = \null, $params = \null)
        {
        }
        /**
         * Delete the value from the DB
         *
         * @param string $type
         * @param int    $id
         * @param string $name
         * @param array  $options
         * @param array  $pod
         *
         * @static
         *
         * @since 2.3.0
         * @return null
         */
        public static function delete($type, $id = \null, $name = \null, $options = \null, $pod = \null)
        {
        }
        /**
         * Check if a user has permission to be editing a field
         *
         * @param      $type
         * @param null $name
         * @param null $options
         * @param null $fields
         * @param null $pod
         * @param null $id
         * @param null $params
         *
         * @static
         *
         * @since 2.0.0
         * @return bool
         */
        public static function permission($type, $name = \null, $options = \null, $fields = \null, $pod = \null, $id = \null, $params = \null)
        {
        }
        /**
         * Parse the default the value
         *
         * @since 2.0.0
         *
         * @param        $value
         * @param string $type
         * @param null   $name
         * @param null   $options
         * @param null   $pod
         * @param null   $id
         *
         * @return mixed|void
         */
        public static function default_value($value, $type = 'text', $name = \null, $options = \null, $pod = \null, $id = \null)
        {
        }
        /**
         * Clean a value for use in class / id
         *
         * @since 2.0.0
         *
         * @param      $input
         * @param bool  $noarray
         * @param bool  $db_field
         *
         * @return mixed|string
         */
        public static function clean($input, $noarray = \false, $db_field = \false)
        {
        }
        /**
         * Run admin_init methods for each field type
         *
         * @since 2.3.0
         */
        public function admin_init()
        {
        }
        /**
         * Autoload a Field Type's class
         *
         * @param string $field_type Field Type identifier
         * @param string $file       The Field Type class file location
         *
         * @return string
         * @access public
         * @static
         * @since 2.0.0
         */
        public static function field_loader($field_type, $file = '')
        {
        }
        /**
         * Run a method from a Field Type's class
         *
         * @return mixed
         * @internal param string $field_type Field Type identifier
         * @internal param string $method Method name
         * @internal param mixed $arg More arguments
         *
         * @access   public
         * @static
         * @since 2.0.0
         */
        public static function field_method()
        {
        }
        /**
         * Add a new Pod field type
         *
         * @param string $type The new field type identifier
         * @param string $file The new field type class file location
         *
         * @return array Field Type data
         *
         * @since 2.3.0
         */
        public static function register_field_type($type, $file = \null)
        {
        }
        /**
         * Get a list of all available Pod types (no labels).
         *
         * @return string[] List of Pod types.
         *
         * @since 2.8.0
         * @deprecated 2.9.17 Use pods_api()->get_pod_types() instead.
         */
        public static function pod_types_list()
        {
        }
        /**
         * Get a list of all available Field types.
         *
         * @return string[] List of Field types.
         *
         * @since 2.8.0
         */
        public static function field_types_list()
        {
        }
        /**
         * Get a list of all available field types and include
         *
         * @return array Registered Field Types data
         *
         * @since 2.3.0
         */
        public static function field_types()
        {
        }
        /**
         * Get the list of available tableless field types.
         *
         * @since 2.3.0
         *
         * @return array The list of available tableless field types.
         */
        public static function tableless_field_types()
        {
        }
        /**
         * Get the list of available file field types.
         *
         * @since 2.3.0
         *
         * @return array The list of available file field types.
         */
        public static function file_field_types()
        {
        }
        /**
         * Get the list of available repeatable field types.
         *
         * @since 2.3.0
         *
         * @return array The list of available repeatable field types.
         */
        public static function repeatable_field_types()
        {
        }
        /**
         * Get the list of available number field types.
         *
         * @since 2.3.0
         *
         * @return array The list of available number field types.
         */
        public static function number_field_types()
        {
        }
        /**
         * Get the list of available date field types.
         *
         * @since 2.3.0
         *
         * @return array The list of available date field types.
         */
        public static function date_field_types()
        {
        }
        /**
         * Get the list of available text field types.
         *
         * @since 2.3.0
         *
         * @return array The list of available text field types.
         */
        public static function text_field_types()
        {
        }
        /**
         * Get the list of available Layout field types (backwards compatible version).
         *
         * @since 2.3.0
         *
         * @deprecated since 2.3.0
         * @see PodsForm::layout_field_types()
         *
         * @return array The list of available Layout field types.
         */
        public static function block_field_types()
        {
        }
        /**
         * Get the list of available Layout field types.
         *
         * @since 2.8.0
         *
         * @return array The list of available Layout field types.
         */
        public static function layout_field_types()
        {
        }
        /**
         * Get the list of available Non-Input field types.
         *
         * @since 2.8.0
         *
         * @return array The list of available Non-Input field types.
         */
        public static function non_input_field_types()
        {
        }
        /**
         * Get the list of field types that do not use serial comma separators.
         *
         * @since 2.9.4
         *
         * @return array The list of field types that do not use serial comma separators.
         */
        public static function separator_excluded_field_types()
        {
        }
        /**
         * Get the list of revisionable field types.
         *
         * @since 3.2.0
         *
         * @return array The list of revisionable field types.
         */
        public static function revisionable_field_types(): array
        {
        }
        /**
         * Get the list of simple tableless objects.
         *
         * @since 2.3.0
         *
         * @return array The list of simple tableless objects.
         */
        public static function simple_tableless_objects()
        {
        }
        /**
         * Render the postbox header in a compatible way.
         *
         * @since 2.7.22
         *
         * @param string $title Header title.
         */
        public static function render_postbox_header($title)
        {
        }
    }
}