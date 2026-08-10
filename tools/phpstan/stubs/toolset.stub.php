<?php

namespace OTGS\Toolset\Common\M2M {
    /**
     * Helper class for the Public Relationship API.
     *
     * @package OTGS\Toolset\Common\M2M
     * @since 2.6.4
     */
    class PublicApiService
    {
        /**
         * @param string|string[] $relationship Relationship slug or a pair of post type slugs identifying a legacy relationship.
         *
         * @return \IToolset_Relationship_Definition|null
         */
        public function get_relationship_definition($relationship)
        {
        }
        /**
         * Transform a relationship definition to an associative array that the API will offer to third-party
         * software.
         *
         * @param \IToolset_Relationship_Definition $relationship_definition
         *
         * @return array|null Relationship information or null if the relationship definition doesn't exist.
         */
        public function format_relationship_definition(\IToolset_Relationship_Definition $relationship_definition)
        {
        }
        /**
         * @return \OTGS\Toolset\Common\Relationships\API\Factory
         */
        public function get_factory()
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1 {
    /**
     * Checks for the existence of m2m database tables and creates them if they're missing.
     *
     * Optimized not to repeat any actions unless necessary.
     *
     * @since Types 3.3.11
     */
    class TableExistenceCheck
    {
        /**
         * After this method is called, relationship tables ought to exist unless:
         *
         * - The toolset_m2m_skip_table_existence_check was used.
         * - There's something wrong with the database that prevents new tables from being created (which is a basic
         *   requirement of WordPress, so it's safe to assume).
         */
        public function ensure_tables_exist()
        {
        }
    }
}
namespace {
    /**
     * Pseudo-enum that holds possible comparison operators.
     *
     * Used, for example, on the meta() query condition.
     * Can be further extended.
     *
     * @since 2.6.1
     */
    class Toolset_Query_Comparison_Operator
    {
        // These need to be valid MySQL operators.
        const EQUALS = '=';
        const LIKE = 'LIKE';
        const LTE = '<=';
        const LT = '<';
        const GTE = '>=';
        const GT = '>';
        /**
         * All accepted values.
         *
         * @return string[]
         */
        public static function all()
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\API {
    /**
     * Represents a query condition that can be used for different types of queries.
     *
     * @since 4.0
     */
    interface QueryCondition
    {
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause();
        /**
         * Get a part of the JOIN clause that is required by the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used as: $table_as_unique_alias_on_condition_1 $table_as_unique_alias_on_condition_2 ...
         *     (meaning that every clause should start with its own "JOIN"
         */
        public function get_join_clause();
    }
}
namespace {
    /**
     * Represents a single condition for the Tooset_Relationship_Query_V2.
     *
     * @since m2m
     * @deprecated use RelationshipQueryCondition instead
     */
    interface IToolset_Relationship_Query_Condition extends \OTGS\Toolset\Common\Relationships\API\QueryCondition
    {
    }
}
namespace OTGS\Toolset\Common\Relationships\API {
    /**
     * Represents a single condition for the RelationshipQuery.
     *
     * @since 4.0
     */
    interface RelationshipQueryCondition extends \IToolset_Relationship_Query_Condition
    {
    }
}
namespace {
    /**
     * @deprecated use QueryCondition instead.
     */
    interface IToolset_Query_Condition extends \OTGS\Toolset\Common\Relationships\API\QueryCondition
    {
    }
}
namespace OTGS\Toolset\Common\Relationships\API {
    /**
     * Represents a single condition for the AssociationQuery.
     *
     * Note: It is very important that if an OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Element_Selector_Provider instance
     * is passed to the condition, it doesn't try to obtain the element selector object
     * within its constructor.
     */
    interface AssociationQueryCondition extends \IToolset_Query_Condition
    {
    }
}
namespace OTGS\Toolset\Common\Relationships\GenericQuery\Condition {
    /**
     * A condition that is always false.
     *
     * It can be useful in situations where we need to make sure that the query will produce no results
     * (e.g. querying for something that clearly isn't there).
     *
     * @since 2.5.8
     */
    class Contradiction implements \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition, \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
    {
        public function get_join_clause()
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
    }
    /**
     * Abstract condition for implementing operators in the MySQL query.
     *
     * @since 2.5.4
     */
    abstract class Operator implements \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition, \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
    {
        /** @var \OTGS\Toolset\Common\Relationships\API\QueryCondition[] */
        protected $conditions = array();
        /**
         * Toolset_Query_Condition_Operator constructor.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\QueryCondition[]|array $conditions If a nested array of conditions
         *     is provided, it will be handled as a nested $op ($op is the operation):
         *     ( $condition1 ) $op ( ( $condition2_1 ) $op ( $condition2_2 ) ) $op ...etc.
         */
        public function __construct($conditions)
        {
        }
        /**
         * Just joins the join clauses from nested conditions.
         *
         * @return string
         */
        public function get_join_clause()
        {
        }
        /**
         * Return an instance of self with provided conditions.
         *
         * Used for nesting when a nested array of conditions is passed to the constructor.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\QueryCondition[] $conditions
         *
         * @return \OTGS\Toolset\Common\Relationships\API\QueryCondition
         */
        abstract protected function instantiate_self($conditions);
    }
    /**
     * A condition that is always true.
     *
     * It can be useful in situations where we need to return a condition object but don't want to influence
     * the query.
     *
     * Remember, the first rule of the Tautology Club is the first rule of the Tautology Club!
     *
     * @since 2.5.6
     * @since 2.5.8 Adjusted for usage in Toolset_Association_Query_V2 as well.
     */
    class Tautology implements \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition, \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
    {
        public function get_join_clause()
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
    }
    /**
     * Chains multiple IToolset_Query_Condition with OR.
     *
     * @since m2m
     */
    class OrOperator extends \OTGS\Toolset\Common\Relationships\GenericQuery\Condition\Operator
    {
        /**
         * @inheritdoc
         *
         * @return string
         */
        public function get_where_clause()
        {
        }
        /**
         * @inheritdoc
         *
         * @param \OTGS\Toolset\Common\Relationships\API\QueryCondition[] $conditions
         *
         * @return \OTGS\Toolset\Common\Relationships\API\QueryCondition
         */
        protected function instantiate_self($conditions)
        {
        }
    }
    /**
     * Negation of a provided condition.
     *
     * @since 2.6.7
     */
    class Not implements \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition, \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
    {
        /**
         * Toolset_Query_Condition_Not constructor.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\QueryCondition $condition
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\API\QueryCondition $condition)
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
        /**
         * Get a part of the JOIN clause that is required by the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used as: $table_as_unique_alias_on_condition_1 $table_as_unique_alias_on_condition_2 ...
         *     (meaning that every clause should start with its own "JOIN"
         */
        public function get_join_clause()
        {
        }
    }
    /**
     * Chains multiple IToolset_Query_Condition with AND.
     *
     * @since m2m
     */
    class AndOperator extends \OTGS\Toolset\Common\Relationships\GenericQuery\Condition\Operator
    {
        /**
         * @inheritdoc
         *
         * @return string
         */
        public function get_where_clause()
        {
        }
        /**
         * @inheritdoc
         *
         * @param \OTGS\Toolset\Common\Relationships\API\QueryCondition[] $conditions
         *
         * @return \OTGS\Toolset\Common\Relationships\API\QueryCondition
         */
        protected function instantiate_self($conditions)
        {
        }
        public function get_inner_conditions()
        {
        }
    }
}
namespace {
    /**
     * Factory for instantiating IToolset_Association objects.
     *
     * This should not be used from outside
     * of the m2m API. Everything required for working with associations should be
     * implemented on IToolset_Relationship_Definition.
     *
     * @since 2.5.8
     */
    class Toolset_Association_Factory
    {
        /**
         * Toolset_Association_Factory constructor.
         *
         * @param Toolset_Relationship_Definition_Repository|null $definition_repository_di
         * @param Toolset_Element_Factory|null $element_factory_di
         * @param Toolset_WPML_Compatibility|null $wpml_service_di
         */
        public function __construct(\Toolset_Relationship_Definition_Repository $definition_repository_di = \null, \Toolset_Element_Factory $element_factory_di = \null, \Toolset_WPML_Compatibility $wpml_service_di = \null)
        {
        }
        /**
         * @param Toolset_Relationship_Definition $relationship
         * @param int|IToolset_Element $parent_source
         * @param int|IToolset_Element $child_source
         * @param int|IToolset_Post $intermediary_source
         * @param int $association_uid Can be zero for associations that are not stored in the database yet.
         *
         * @return IToolset_Association
         * @throws Toolset_Element_Exception_Element_Doesnt_Exist
         */
        public function create(\Toolset_Relationship_Definition $relationship, $parent_source, $child_source, $intermediary_source, $association_uid = 0)
        {
        }
        /**
         * @param int $relationship_id
         * @param int $parent_id
         * @param int $child_id
         * @param int $intermediary_id
         * @param int $association_uid Can be zero for associations that are not stored in the database yet.
         *
         * @return IToolset_Association
         * @throws RuntimeException Thrown if an invalid relationship slug is provided.
         * @throws Toolset_Element_Exception_Element_Doesnt_Exist
         */
        public function create_by_relationship_id($relationship_id, $parent_id, $child_id, $intermediary_id, $association_uid = 0)
        {
        }
    }
    /**
     * Represents an association between two elements.
     *
     * @since m2m
     */
    interface IToolset_Association
    {
        /**
         * Unique identifier of the association.
         *
         * Depending on the implementation, this may be an association row ID, trid or anything else.
         * The only guarantee is that each association's UID is unique.
         *
         * @return int|string
         */
        public function get_uid();
        /**
         * @return Toolset_Relationship_Definition
         */
        public function get_definition();
        /**
         * Tell if the association has custom fields.
         *
         * Note that this value is based on field definitions, not on the actual values in the database.
         *
         * @return bool
         */
        public function has_fields();
        /**
         * Check if the association has particular custom field.
         *
         * Note that this value is based on field definitions, not on the actual values in the database.
         *
         * @param string|Toolset_Field_Definition $field_source Field definition or slug.
         *
         * @return bool
         * @since m2m
         */
        public function has_field($field_source);
        /**
         * Get all association field instances.
         *
         * @return Toolset_Field_Instance[]
         * @since m2m
         */
        public function get_fields();
        /**
         * Get a particular association field instance.
         *
         * @param string|Toolset_Field_Definition $field_source Field definition or slug.
         *
         * @return Toolset_Field_Instance
         * @throws InvalidArgumentException
         */
        public function get_field($field_source);
        /**
         * Get an association element.
         *
         * Instantiates an element from its ID if that hasn't been done yet.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $element_role
         *
         * @return Toolset_Element|null Null can be returned for the intermediary role, if there is no
         *     intermediary post.
         *
         * @throws InvalidArgumentException
         * @throws Toolset_Element_Exception_Element_Doesnt_Exist
         * @since m2m
         */
        public function get_element($element_role);
        /**
         * Get an ID of the association element.
         *
         * Note that if WPML is active and the element is translated, this will return the ID of the
         * translation.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $element_role
         *
         * @return int
         */
        public function get_element_id($element_role);
        /**
         * Check that the element role is valid.
         *
         * @param string $element_role
         *
         * @throws InvalidArgumentException
         * @since m2m
         */
        public static function validate_element_role($element_role);
        /**
         * Shortcut to the relationship driver.
         *
         * @return Toolset_Relationship_Driver_Base
         */
        public function get_driver();
        /**
         * Get the ID of the intermediary post with association fields.
         *
         * Use with consideration.
         *
         * @return int Post ID or zero if no post exists.
         * @since 2.5.8
         */
        public function get_intermediary_id();
        /**
         * Check whether an intermediary post exists for this association.
         *
         * @return bool
         * @since 2.5.10
         */
        public function has_intermediary_post();
    }
    /**
     * Translation-unaware m2m association between two elements.
     *
     * This can be used only when the multilingual mode is off/transitional
     *
     * Not to be used directly outside of the m2m API.
     *
     * @since m2m
     */
    class Toolset_Association implements \IToolset_Association
    {
        /**
         * @var Toolset_Element[] Actual elements, loaded on demand. Use self::get_element() to obtain them.
         */
        protected $elements = array();
        /**
         * Toolset_Association constructor.
         *
         * Note that no checks about elements with respect to the relationship definition are being performed here.
         * The caller needs to ensure everything is valid (domains, types, other conditions). This is handled well in the
         * association factory.
         *
         * It is assumed that the relationship definition uses the Toolset_Relationship_Driver driver.
         *
         * @param int $uid Unique association ID.
         * @param Toolset_Relationship_Definition $relationship_definition
         * @param array $element_sources Associative array with both element keys. Each item can be either an ID
         *     or a matching Toolset_Element instance.
         * @param int|Toolset_Post $intermediary_source Intermediary post with association fields or its ID. If a
         *    Toolset_Post instance is provided, it must have the type matching with the relationship definition.
         * @param \OTGS\Toolset\Common\WPML\WpmlService $wpml_service_di
         * @param Toolset_Element_Factory|null $element_factory_di
         *
         * @throws Toolset_Element_Exception_Element_Doesnt_Exist
         * @since m2m
         */
        public function __construct($uid, \Toolset_Relationship_Definition $relationship_definition, $element_sources, $intermediary_source, \OTGS\Toolset\Common\WPML\WpmlService $wpml_service_di = \null, \Toolset_Element_Factory $element_factory_di = \null)
        {
        }
        /**
         * @return Toolset_Relationship_Definition
         */
        public function get_definition()
        {
        }
        /**
         * Get domain of selected association element.
         *
         * @param string $element_role
         *
         * @return string Valid domain name as defined in Toolset_Field_Utils.
         * @since m2m
         */
        protected function get_element_domain($element_role)
        {
        }
        /**
         * Get an ID of an element in the associaton.
         *
         * @param string|IToolset_Relationship_Role $element_role Must be a valid role.
         *
         * @return int
         * @since m2m
         * @throws Toolset_Element_Exception_Element_Doesnt_Exist
         */
        public function get_element_id($element_role)
        {
        }
        /**
         * Get an association element.
         *
         * Instantiates an element from its ID if that hasn't been done yet.
         *
         * @param string $element_role
         *
         * @return IToolset_Element|null
         * @throws InvalidArgumentException
         * @since m2m
         * @throws Toolset_Element_Exception_Element_Doesnt_Exist
         */
        public function get_element($element_role)
        {
        }
        /**
         * Check that the element role is valid.
         *
         * @param string $element_role
         *
         * @throws InvalidArgumentException
         * @since m2m
         * @deprecated Use methods in Toolset_Relationship_Role instead.
         */
        public static function validate_element_role($element_role)
        {
        }
        /**
         * Shortcut to the relationship driver.
         *
         * @return Toolset_Relationship_Driver_Base
         */
        public function get_driver()
        {
        }
        /**
         * Get the unique identifier for the association.
         *
         * An integer value indicates that it's an ID from the associations table.
         *
         * It may be zero for associations that are not persisted yet.
         *
         * @return int
         * @since m2m
         */
        public function get_uid()
        {
        }
        /**
         * Get the translation group ID of the association.
         *
         * @return int Translation group ID or zero if not supported.
         * @deprecated Use get_uid() instead.
         */
        public function get_trid()
        {
        }
        /**
         * @return null|IToolset_Post
         * @throws Toolset_Element_Exception_Element_Doesnt_Exist
         */
        protected function get_intermediary_post()
        {
        }
        /**
         * @inheritdoc
         * @return bool|Toolset_Field_Instance[]
         * @throws Toolset_Element_Exception_Element_Doesnt_Exist
         */
        public function get_fields()
        {
        }
        /**
         * @inheritdoc
         *
         * @param string|Toolset_Field_Definition $field_source
         *
         * @return bool|Toolset_Field_Instance
         * @throws Toolset_Element_Exception_Element_Doesnt_Exist
         */
        public function get_field($field_source)
        {
        }
        /**
         * Get the ID of the intermediary post with association fields.
         *
         * Required for the [types] shortcode, but use with consideration.
         *
         * @return int Post ID or zero if no post exists.
         * @throws Toolset_Element_Exception_Element_Doesnt_Exist
         * @since m2m
         */
        public function get_intermediary_id()
        {
        }
        /**
         * @return bool
         * @throws Toolset_Element_Exception_Element_Doesnt_Exist
         * @since m2m
         */
        public function has_intermediary_post()
        {
        }
        /**
         * @inheritdoc
         *
         * This needs to be called (internally) before accessing the intermediary post object.
         *
         * @return bool
         * @throws Toolset_Element_Exception_Element_Doesnt_Exist
         * @since m2m
         */
        public function has_fields()
        {
        }
        /**
         * @inheritdoc
         *
         * @param string|Toolset_Field_Definition $field_source
         *
         * @return bool
         * @throws Toolset_Element_Exception_Element_Doesnt_Exist
         */
        public function has_field($field_source)
        {
        }
    }
    /**
     * Delete a batch of dangling intermediary posts (DIP).
     *
     * A DIP is a post belonging to an intermediary post type that is not involved in an association
     * and is not a translation of any such post. DIPs should not exist and this class queries
     * and permanently deletes them.
     *
     * Only a single batch is deleted on each pass because this might be an expensive operation
     * which can be called from various contexts like WP-Cron or an user-triggered batch process.
     *
     * @since 2.5.10
     */
    class Toolset_Association_Cleanup_Dangling_Intermediary_Posts extends \Toolset_Wpdb_User
    {
        const OPTION_POST_TYPES_TO_DELETE = 'toolset_deleted_ipts';
        /**
         * Toolset_Association_Cleanup_Dangling_Intermediary_Posts constructor.
         *
         * @param wpdb|null $wpdb_di
         * @param Toolset_Post_Type_Query_Factory|null $post_type_query_factory_di
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory|null $database_layer_factory
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory $database_layer_factory, \wpdb $wpdb_di = \null, \Toolset_Post_Type_Query_Factory $post_type_query_factory_di = \null)
        {
        }
        /**
         * Perform one batch of DIP deletions.
         *
         * @since 2.5.10
         */
        public function do_batch()
        {
        }
        /**
         * After a batch operation was performed, this will return false if there are no
         * remaining DIPs to be deleted. Otherwise returns true.
         *
         * @return bool
         */
        public function has_remaining_posts()
        {
        }
        /**
         * After a batch operation was performed, this will return the number of posts
         * that have actually been deleted.
         *
         * @return int
         */
        public function get_deleted_posts()
        {
        }
        public function mark_deletion_by_post_type($post_type_slug)
        {
        }
    }
    /**
     * Factory for objects handling cleaning up and removing m2m-related data.
     *
     * @since 2.5.10
     */
    class Toolset_Association_Cleanup_Factory
    {
        /**
         * Toolset_Association_Cleanup_Factory constructor.
         *
         * @refactoring avoid creating new instances other than via DIC, then make the parameters mandatory.
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory $database_layer_factory
         * @since 4.0
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory $database_layer_factory = \null)
        {
        }
        /**
         * @return \OTGS\Toolset\Common\Relationships\DatabaseLayer\Cleanup\PostCleanupInterface
         */
        public function post()
        {
        }
        /**
         * @return Toolset_Association_Cleanup_Association
         */
        public function association()
        {
        }
        /**
         * @return Toolset_Association_Cleanup_Cron_Handler
         */
        public function cron_handler()
        {
        }
        /**
         * @return Toolset_Association_Cleanup_Dangling_Intermediary_Posts
         */
        public function dangling_intermediary_posts()
        {
        }
        /**
         * @return Toolset_Association_Cleanup_Cron_Event
         */
        public function cron_event()
        {
        }
        /**
         * @return Toolset_Association_Cleanup_Troubleshooting_Section
         */
        public function troubeshooting_section()
        {
        }
    }
    /**
     * Removes a single association from the database and cleans up after.
     *
     * That means also deleting the intermediary post, if it exists.
     *
     * @since 2.5.10
     */
    class Toolset_Association_Cleanup_Association
    {
        /**
         * Toolset_Association_Cleanup_Association constructor.
         *
         * @param Toolset_Association_Intermediary_Post_Persistence|null $intermediary_post_persistence_di
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory $database_layer_factory
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory $database_layer_factory = \null, \Toolset_Association_Intermediary_Post_Persistence $intermediary_post_persistence_di = \null)
        {
        }
        /**
         * Permanently delete the provided association.
         *
         * @param IToolset_Association $association Association to delete. Do not use the instance
         *     after passing it to this method.
         *
         * @return Toolset_Result
         */
        public function delete(\IToolset_Association $association)
        {
        }
    }
    /**
     * The class fetches all post ids of all published posts given a post type and deletes them programmatically, without
     * letting m2m API run additional filters to delete Asociations and their data too.
     *
     * Class Toolset_Association_Cleanup_Post_Type
     */
    class Toolset_Association_Cleanup_Post_Type extends \Toolset_Wpdb_User
    {
        /**
         * Toolset_Association_Cleanup_Post_Type constructor.
         *
         * @param wpdb|null $wpdb_di
         */
        public function __construct(\wpdb $wpdb_di = \null)
        {
        }
        /**
         * @param string $post_type
         *
         * @return array
         */
        protected function get_post_type_posts_ids($post_type)
        {
        }
        /**
         * @param string $post_type
         *
         * @return array
         */
        public function clean_up_posts($post_type)
        {
        }
    }
    /**
     * A WP-Cron event definition.
     *
     * The event hook is added in Toolset_Relationship_Controller.
     *
     * This event should be scheduled when there are dangling intermediary posts that need to be removed.
     *
     * @since 2.5.10
     */
    class Toolset_Association_Cleanup_Cron_Event extends \Toolset_Cron_Event
    {
        /**
         * @inheritdoc
         *
         * @return string
         */
        public function get_unique_slug()
        {
        }
        /**
         * @inheritdoc
         *
         * @return string
         */
        public function get_parent_plugin()
        {
        }
        /**
         * @inheritdoc
         *
         * @return string
         */
        public function get_interval()
        {
        }
    }
    /**
     * Manage the troubleshooting section for manually deleting dangling intermediary posts.
     *
     * @since 2.5.10
     */
    class Toolset_Association_Cleanup_Troubleshooting_Section
    {
        const TROUBLESHOOTING_SECTION_SLUG = 'cleanup_intermediary_posts';
        /**
         * Toolset_Association_Cleanup_Troubleshooting_Section constructor.
         *
         * @param Toolset_Association_Cleanup_Factory $cleanup_factory
         * @param Toolset_Cron|null $cron_di
         */
        public function __construct(\Toolset_Association_Cleanup_Factory $cleanup_factory, \Toolset_Cron $cron_di = \null)
        {
        }
        /**
         * Find out whether there are posts to clean up.
         *
         * Instead of running the query, we just check whether the cleanup WP-Cron job is already scheduled.
         *
         * @return bool
         */
        public function is_cleanup_needed()
        {
        }
        /**
         * Register the troubleshooting section and a dismissable admin notice that will point towards it.
         */
        public function register()
        {
        }
        /**
         * Add new troubleshooting section definition.
         *
         * @param array $sections
         *
         * @return array
         */
        public function add_troubleshooting_section($sections)
        {
        }
        /**
         * Show an admin notice if there are dangling intermediary posts.
         *
         * @param array $notices
         *
         * @return array
         * @throws Exception
         */
        public function add_notice($notices)
        {
        }
    }
    /**
     * Handle the Toolset_Association_Cleanup_Cron_Event event.
     *
     * Perform the cleanup action and unschedule the event in case there are no dangling
     * intermediary posts left.
     *
     * @since 2.5.10
     */
    class Toolset_Association_Cleanup_Cron_Handler
    {
        /**
         * Toolset_Association_Cleanup_Cron_Handler constructor.
         *
         * @param Toolset_Association_Cleanup_Factory $cleanup_factory
         * @param Toolset_Cron|null $cron_di
         */
        public function __construct(\Toolset_Association_Cleanup_Factory $cleanup_factory, \Toolset_Cron $cron_di = \null)
        {
        }
        /**
         * Handle the WP-Cron event.
         */
        public function handle_event()
        {
        }
        /**
         * Schedule the cleanup event.
         *
         * @since 3.0.5
         */
        public function schedule_event()
        {
        }
    }
    /**
     * Defines an element type that can take a role in a relationship.
     *
     * It encapsulates the domain of the element and its types within the domain. It also hides the polymorphism away
     * in a lower abstraction level.
     *
     * @since m2m
     */
    class Toolset_Relationship_Element_Type
    {
        // Currently, only posts are supported.
        /**
         * @deprecated Use Toolset_Element_Domain::POSTS instead.
         */
        const DOMAIN_POSTS = 'posts';
        // Constants for the entity type definition array.
        const DA_DOMAIN = 'domain';
        const DA_TYPES = 'types';
        /**
         * Toolset_Relationship_Element_Type constructor.
         *
         * @param string[] $element_type_definition Element type definition array, which is usually part of the relationship
         *     definition array. If you need to create a new instance from scratch, consider using one of the static 
         *     helper methods.
         *
         * @throws InvalidArgumentException
         * @since m2m
         */
        public function __construct($element_type_definition)
        {
        }
        /**
         * Get entity domain.
         * 
         * @return string
         * @since m2m
         */
        public function get_domain()
        {
        }
        /**
         * @return string[]
         */
        public function get_types()
        {
        }
        /**
         * Determine if this is a polymorphic entity.
         * 
         * @return bool
         * @since m2m
         */
        public function is_polymorphic()
        {
        }
        /**
         * Build a definition array for persisting in the database.
         * 
         * It should not be used for other reasons.
         * 
         * @return array
         * @since m2m
         */
        public function get_definition_array()
        {
        }
        /**
         * Create an instance for a single post type - the simplest  and most common scenario.
         * 
         * @param string $post_type_slug Valid post type slug.
         *
         * @return Toolset_Relationship_Element_Type
         * @since m2m
         */
        public static function build_for_post_type($post_type_slug)
        {
        }
        /**
         * Determine whether an element matches this type.
         *
         * @param IToolset_Element $element
         * @return bool
         * @since m2m
         */
        public function is_match($element)
        {
        }
        /**
         * Check whether an element matching this type definition can be translatable.
         *
         * For polymorphic post type definitions, true is returned if at least one post type is translatable.
         *
         * The result is cached for performance reasons.
         *
         * @return bool
         * @since m2m
         */
        public function is_translatable()
        {
        }
    }
    /**
     * Holds a relationship cardinality information.
     *
     * This is an immutable class which holds the minimal and maximal limits of elements for both sides of the relationship.
     * See the constructor for further details.
     *
     * @since m2m
     */
    class Toolset_Relationship_Cardinality
    {
        // Constants for better code readability.
        const ONE_ELEMENT = 1;
        const ZERO_ELEMENTS = 0;
        // These values should never be used directly, and never changed.
        const INFINITY = -1;
        const INVALID_VALUE = -2;
        // Internal keys for identifying limits. Never use directly.
        const MIN = 'min';
        const MAX = 'max';
        /**
         * Toolset_Relationship_Cardinality constructor.
         *
         * There are several ways to provide the limit values.
         *
         * (a) Parent and child maximum item limits as integers via both arguments:
         *          new Toolset_Relationship_Cardinality( self::ONE, self::INFINITY )
         *
         * (b) Parent and child maximum item limits as one array:
         *          $max_limits = array(
         *              Toolset_Association_Base::PARENT => self::ONE,
         *              Toolset_Association_Base::CHILD => self::INFINITY
         *          );
         *          new Toolset_Relationship_Cardinality( $max_limits );
         *
         * (c) Both minimum and maximum limits in one array:
         *          $limits = array(
         *              Toolset_Association_Base::PARENT => array(
         *                  self::ZERO,
         *                  self::ONE
         *              ),
         *              Toolset_Association_Base::CHILD => array(
         *                  self::ONE,
         *                  self::INFINITY
         *              )
         *          );
         *          new Toolset_Relationship_Cardinality( $limits );
         *
         * In the case of (a) and (b), the minimum limits will default to zero.
         *
         * Obviously, the maximum limit must be equal or lower than the minimum one and it must not be a zero.
         * If an array is provided as a first argument, the second one is ignored.
         *
         * @param int|int[]|int[][] $parent_limit_or_limits
         * @param null|int $child_limit
         * @since m2m
         * @throws InvalidArgumentException
         */
        public function __construct($parent_limit_or_limits, $child_limit = \null)
        {
        }
        /**
         * Get a limit value per element and limit key.
         *
         * @param string|IToolset_Relationship_Role_Parent_Child $element_role
         * @param string $limit_key
         *
         * @return int Limit value.
         * @since m2m
         */
        public function get_limit($element_role, $limit_key = self::MAX)
        {
        }
        /**
         * Convenience method.
         *
         * @param string $limit_key
         * @return int
         */
        public function get_parent($limit_key = self::MAX)
        {
        }
        /**
         * Convenience method.
         *
         * @param string $limit_key
         * @return int
         */
        public function get_child($limit_key = self::MAX)
        {
        }
        public function has_numeric_limits($limit_key = self::MAX)
        {
        }
        public function has_limit_to_one()
        {
        }
        public function is_one_to_many()
        {
        }
        public function is_many_to_one()
        {
        }
        public function is_many_to_many()
        {
        }
        public function is_one_to_one()
        {
        }
        public function get_definition_array()
        {
        }
        public static function get_one_to_many()
        {
        }
        /**
         * An opposite of to_string(), create a ccardinality instance from its string representation.
         *
         * @param string $value
         *
         * @return Toolset_Relationship_Cardinality
         * @throws InvalidArgumentException
         */
        public static function from_string($value)
        {
        }
        /**
         * Return a non-ambiguous string representation of the cardinality.
         *
         * @return string
         */
        public function to_string()
        {
        }
        /**
         * Return the cardinality in a two-dimensional associative array.
         *
         * First key is 'parent'|'child' and the second one is 'min'|'max'. Value -1 stands for infinity (no limit).
         * @return int[][]
         */
        public function to_array()
        {
        }
        /**
         * Get a string representation of the cardinality type.
         *
         * @return string 'many-to-many'|'one-to-many'|'many-to-one'|'one-to-one'
         */
        public function get_type()
        {
        }
    }
    /**
     * Abstract relationship driver.
     *
     * Relationship driver encapsulates all database operations specific to an individual relationship.
     *
     * @since m2m
     */
    abstract class Toolset_Relationship_Driver_Base
    {
        /** @var Toolset_Association_Factory */
        protected $association_factory;
        /** @var \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory */
        protected $database_layer_factory;
        /**
         * Toolset_Relationship_Driver_Base constructor.
         *
         * @param Toolset_Relationship_Definition $definition Relationship definition that is going to be using this driver.
         * @param array $setup Driver setup array provided by the relationship definition.
         * @param Toolset_Association_Factory|null $association_factory_di
         * @param Toolset_Element_Factory|null $element_factory_di
         *
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory|null $database_layer_factory
         *
         * @since m2m
         */
        public function __construct(\Toolset_Relationship_Definition $definition, $setup, \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory $database_layer_factory, \Toolset_Association_Factory $association_factory_di = \null, \Toolset_Element_Factory $element_factory_di = \null)
        {
        }
        /**
         * @return Toolset_Relationship_Definition
         */
        protected function get_relationship_definition()
        {
        }
        /**
         * @return string
         */
        protected function get_relationship_slug()
        {
        }
        /**
         * @param int|Toolset_Element|WP_Post $parent_source
         * @param int|Toolset_Element|WP_Post $child_source
         * @param array $args Optional arguments, implementation-specific
         *
         * @return Toolset_Association|Toolset_Result ID of the new association on success or a result information with an error.
         */
        abstract public function create_association($parent_source, $child_source, $args = array());
        /**
         * Delete an association from the database.
         *
         * @param Toolset_Association $association
         *
         * @return Toolset_Result
         * @since m2m
         */
        abstract public function delete_association($association);
        /**
         * Check if the driver can offer some field definitions for the relationship.
         *
         * @return bool
         */
        public function has_field_definitions()
        {
        }
        /**
         * Check if fields for the managed relationship are translatable.
         *
         * @return bool
         * @since m2m
         */
        public function has_translatable_fields()
        {
        }
        /**
         * Get the field definitions for the relationship this driver is managing.
         *
         * @return Toolset_Field_Definition[]
         */
        public function get_field_definitions()
        {
        }
        /**
         * Get information from the driver setup.
         *
         * @param null|string $argument Key from the setup array or null to return the whole array.
         * @param mixed $default Value to return when the requested argument is not defined.
         *
         * @return mixed Whole setup array if no argument is provided, or argument value.
         * @since m2m
         */
        public function get_setup($argument = \null, $default = \null)
        {
        }
        protected function set_setup_argument($argument, $value)
        {
        }
        protected function is_association_match($association)
        {
        }
        /**
         * @return Toolset_Element_Factory
         * @since 2.5.9
         */
        protected function get_element_factory()
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships {
    /**
     * Sets the initial state (when there's no relevant value defined) of the whole relationship
     * functionality and creates required database tables if necessary
     *
     * @since 4.0
     */
    class InitialStateSetup
    {
        /**
         * InitialStateSetup constructor.
         *
         * @param MainController $relationships_controller
         * @param \Toolset_Condition_Plugin_Types_Has_Legacy_Relationships $has_legacy_relationships
         * @param \Toolset_Condition_Plugin_Types_Ready_For_M2M $is_ready_for_m2m
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerMode $database_layer_mode
         * @param \wpdb $wpdb
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names
         * @param \Toolset_Constants $constants
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\MainController $relationships_controller, \Toolset_Condition_Plugin_Types_Has_Legacy_Relationships $has_legacy_relationships, \Toolset_Condition_Plugin_Types_Ready_For_M2M $is_ready_for_m2m, \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerMode $database_layer_mode, \wpdb $wpdb, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names, \Toolset_Constants $constants)
        {
        }
        /**
         * Determine whether relationships should be enabled by default.
         *
         * We do that only if there are no legacy post relationships defined. Otherwise, the user needs to
         * manually trigger the migration (and go through the first version of the database layer and then
         * migrate to the second one - cumbersome but at this point, whoever wants to seriously use relationships
         * has migrated from legacy relationships anyway).
         *
         * If this runs on a fresh site, this will also create necessary database tables.
         *
         * Finally, this method updates the toggle option so we don't need to run this check on each request.
         *
         * @return bool True if relationships have been enabled.
         */
        public function set_initial_state()
        {
        }
        /**
         * Switch directly to the second version of database layer.
         *
         * The previous approach using Toolset_Relationship_Migration_Controller::do_native_dbdelta()
         * is no longer necessary here, as this is the no-migration route (without any data to migrate).
         *
         * Note: This is public because of Toolset CLI.
         *
         * @return bool
         */
        public function enable_relationships()
        {
        }
        /**
         * Update the option storing the state of the relationship functionality.
         *
         * Note: This is public because of Toolset CLI.
         *
         * @param bool $enable_m2m
         */
        public function store_state($enable_m2m)
        {
        }
        public function all_tables_exist()
        {
        }
    }
}
namespace {
    /**
     * Native Toolset relationship driver.
     *
     * @since m2m
     * @deprecated Split code into database operations classes or elsewhere.
     */
    class Toolset_Relationship_Driver extends \Toolset_Relationship_Driver_Base
    {
        const DA_INTERMEDIARY_POST_TYPE = 'intermediary_post_type';
        /**
         * Create new native association in the database.
         *
         * @param int|Toolset_Element|WP_Post $parent_source
         * @param int|Toolset_Element|WP_Post $child_source
         * @param array $args Association arguments:
         *     - 'intermediary_id': ID of the intermediary post; defaults to zero.
         *
         * @return IToolset_Association|Toolset_Result ID of the new association on success or a result information with an
         *     error.
         * @throws Toolset_Element_Exception_Element_Doesnt_Exist
         */
        public function create_association($parent_source, $child_source, $args = array())
        {
        }
        /**
         * Get the slug of the indermediary post type that holds association fields.
         *
         * @return string|null Post type slug or null if undefined/invalid.
         * @since m2m
         */
        public function get_intermediary_post_type()
        {
        }
        /**
         * @return IToolset_Post_Type_From_Types|null
         */
        public function get_intermediary_post_type_object()
        {
        }
        /**
         * Create a new intermediary post type if it doesn't exist yet.
         *
         * @param null|string $new_slug_candidate Use this post slug if possible.
         * @param bool $show_in_menu If the intermediary post type should be visible in the admin menu.
         *
         * @return string Post type slug.
         */
        public function create_intermediary_post_type($new_slug_candidate = \null, $show_in_menu = \false)
        {
        }
        /**
         * Set the intermediary post type for a relationship.
         *
         * Also update the "is_intermediary" flag in the new and previous type (if they exist).
         *
         * @param IToolset_Post_Type_From_Types|null $post_type Post type or null to unlink an intermediary post type.
         * @param bool $show_in_menu If the intermediary post type should be visible in the admin menu.
         */
        public function set_intermediary_post_type(\IToolset_Post_Type_From_Types $post_type = \null, $show_in_menu = \false)
        {
        }
        /**
         * @inheritdoc
         *
         * @return Toolset_Field_Definition[]
         * @since m2m
         */
        public function get_field_definitions()
        {
        }
        /**
         * @inheritdoc
         *
         * In the context of native Toolset relationships, the association fields are translatable when the intermediary
         * post type is translatable.
         *
         * @return bool
         */
        public function has_translatable_fields()
        {
        }
        /**
         * Delete an association from the database.
         *
         * Also delete an intermediary post if it exists.
         *
         * @param Toolset_Association|IToolset_Association $association
         *
         * @return Toolset_Result
         *
         * @deprecated Use Toolset_Association_Persistence::delete_association() instead.
         * @since m2m
         */
        public function delete_association($association)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\API {
    /**
     * Represents one element's role in a relationship.
     *
     * Always expect this interface rather than relying on \IToolset_Relationship_Role.
     *
     * @since 4.0
     */
    interface RelationshipRole
    {
        /**
         * Role name.
         *
         * @return string
         */
        public function get_name();
        /**
         * @return bool
         */
        public function is_parent_child();
        /**
         * Convert this to a role name string.
         *
         * @return string
         */
        public function __toString();
        /**
         * Returns true if the other role is the same as this one.
         *
         * @param RelationshipRole $other
         *
         * @return bool
         */
        public function equals(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $other);
        /**
         * Returns true if this role can be found also in the provided array.
         *
         * @param RelationshipRole[] $roles
         *
         * @return bool
         */
        public function is_in_array($roles);
    }
}
namespace {
    /**
     * Represents a role that one element can take in a relationship.
     *
     * @since m2m
     * @deprecated use \OTGS\Toolset\Common\Relationships\API\RelationshipRole instead.
     */
    interface IToolset_Relationship_Role extends \OTGS\Toolset\Common\Relationships\API\RelationshipRole
    {
    }
    /**
     * Note: Keep the IToolset_Relationship_Role interface here for backward compatibility purposes.
     * All role classes must implement it, just RelationshipRole is not enough. Code like this
     * still needs to pass:
     *
     * `$role instanceof IToolset_Relationship_Role`
     */
    abstract class Toolset_Relationship_Role_Abstract implements \IToolset_Relationship_Role
    {
        public function __toString()
        {
        }
        /**
         * @inheritDoc
         */
        public function equals(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $other)
        {
        }
        /**
         * @inheritDoc
         */
        public function is_in_array($roles)
        {
        }
    }
    /**
     * Enum class. Defines names of roles that elements can take in a relationship.
     *
     * Note that this enum also supports the strongly-typed approach with objects implementing
     * the IToolset_Relationship_Role interface. It is recommended to use this instead of
     * encouraging more stringly-typed code.
     *
     * @since m2m
     */
    abstract class Toolset_Relationship_Role
    {
        // Don't change these values. They're used also in the database context.
        const PARENT = 'parent';
        const CHILD = 'child';
        const INTERMEDIARY = 'intermediary';
        /**
         * Get the array of parent and child roles for easy looping.
         *
         * @return IToolset_Relationship_Role_Parent_Child[]
         */
        public static function parent_child()
        {
        }
        /**
         * Get the array of parent and child role names for easy looping.
         *
         * @return string[]
         */
        public static function parent_child_role_names()
        {
        }
        /**
         * Get the array of parent, child and intermediary roles for easy looping.
         *
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipRole[]
         */
        public static function all()
        {
        }
        /**
         * Get the array of parent, child and intermediary role names for easy looping.
         *
         * @return string[]
         */
        public static function all_role_names()
        {
        }
        public static function is_valid($role_name)
        {
        }
        /**
         * Throw an exception if a given role name isn't valid.
         *
         * @param string|IToolset_Relationship_Role $role
         * @param null|string[] $valid_roles Array of roles to accept, defaults to all() roles.
         *
         * @since m2m
         */
        public static function validate($role, $valid_roles = \null)
        {
        }
        /**
         * Get the other role name.
         *
         * @param string $role Parent or child role name.
         *
         * @return string
         * @throws InvalidArgumentException
         */
        public static function other($role)
        {
        }
        /**
         * Organize two elements into an array of parent and child.
         *
         * @param $first_element
         * @param $second_element
         * @param string|IToolset_Relationship_Role_Parent_Child $first_role Role of the first element (parent or child
         *     expected)
         *
         * @return array Two provided elements orderd as parent and child.
         */
        public static function sort_elements($first_element, $second_element, $first_role)
        {
        }
        /**
         * @param string $role_name
         *
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipRole
         */
        public static function role_from_name($role_name)
        {
        }
        /**
         * @param string $role_name
         *
         * @return IToolset_Relationship_Role_Parent_Child
         * @since 2.5.10
         */
        public static function parent_or_child_from_name($role_name)
        {
        }
        /**
         * @param string|IToolset_Relationship_Role $role_or_name
         *
         * @return string
         */
        public static function name_from_role($role_or_name)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\API {
    /**
     * Represents a relationship role that is either parent or a child.
     *
     * Always expect this interface rather than relying on \IToolset_Relationship_Role_Parent_Child.
     *
     * @since 4.0
     */
    interface RelationshipRoleParentChild extends \OTGS\Toolset\Common\Relationships\API\RelationshipRole
    {
        /**
         * @return RelationshipRoleParentChild The opposite role.
         */
        public function other();
    }
}
namespace {
    /**
     * Represents a parent or a child (not intermediary) role of an element in a relationship.
     *
     * @since m2m
     * @deprecated Use RelationshipRoleParentChild instead.
     */
    interface IToolset_Relationship_Role_Parent_Child extends \IToolset_Relationship_Role, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild
    {
    }
    /**
     * Represents a parent role of an element in a relationship.
     *
     * @since m2m
     */
    class Toolset_Relationship_Role_Parent extends \Toolset_Relationship_Role_Abstract implements \IToolset_Relationship_Role_Parent_Child
    {
        /**
         * Role name.
         *
         * @return string
         */
        public function get_name()
        {
        }
        /**
         * @inheritdoc
         * @return bool
         */
        public function is_parent_child()
        {
        }
        /**
         * @inheritdoc
         *
         * @return IToolset_Relationship_Role_Parent_Child
         */
        public function other()
        {
        }
    }
    /**
     * Represents an intermediary post role in a relationship.
     *
     * @since m2m
     */
    class Toolset_Relationship_Role_Intermediary extends \Toolset_Relationship_Role_Abstract
    {
        /**
         * Role name.
         *
         * @return string
         */
        public function get_name()
        {
        }
        /**
         * @inheritdoc
         * @return bool
         */
        public function is_parent_child()
        {
        }
    }
    /**
     * Represents a child role of an element in a relationship.
     *
     * @since m2m
     */
    class Toolset_Relationship_Role_Child extends \Toolset_Relationship_Role_Abstract implements \IToolset_Relationship_Role_Parent_Child
    {
        /**
         * Role name.
         *
         * @return string
         */
        public function get_name()
        {
        }
        /**
         * @inheritdoc
         * @return bool
         */
        public function is_parent_child()
        {
        }
        /**
         * @inheritdoc
         *
         * @return IToolset_Relationship_Role_Parent_Child
         */
        public function other()
        {
        }
    }
    /**
     * Relationship scope model.
     *
     * Handles the parsing of scope conditions for a particular relationship definition and evaluating them.
     *
     * Stub only, until a proper implementation is decided.
     *
     * Notes for the future implementation:
     *  - scope_data should be an array of conditions
     *  - we might support different types of conditions, be prepared for that
     *  - Toolset_Element models should eventually get magic properties/property discovery mechanism so that these properties
     *    can be used for conditions.
     *
     * @since m2m
     */
    class Toolset_Relationship_Scope
    {
        /**
         * Toolset_Relationship_Scope constructor.
         *
         * @param mixed $scope_data
         * @param Toolset_Relationship_Definition $relationship_definition
         */
        public function __construct($scope_data, $relationship_definition)
        {
        }
        public function can_associate($elements)
        {
        }
        public function query_possible_associations($element, $side)
        {
        }
        public function get_scope_data()
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\Relationship {
    /**
     * Handles cleanup when deleting a relationship definition.
     *
     * @since Types 3.3
     */
    class Cleanup
    {
        /**
         * Cleanup constructor.
         *
         * @param \Toolset_Relationship_Definition $definition
         * @param \OTGS\Toolset\Common\Relationships\API\AssociationDatabaseOperations $database_operations
         * @param \Toolset_Association_Cleanup_Factory $cleanup_factory
         * @param \Toolset_Cron $cron
         * @param \Toolset_Post_Type_Repository $post_type_repository
         * @param \Toolset_Field_Group_Post_Factory $post_field_group_factory
         */
        public function __construct(\Toolset_Relationship_Definition $definition, \OTGS\Toolset\Common\Relationships\API\AssociationDatabaseOperations $database_operations, \Toolset_Association_Cleanup_Factory $cleanup_factory, \Toolset_Cron $cron, \Toolset_Post_Type_Repository $post_type_repository, \Toolset_Field_Group_Post_Factory $post_field_group_factory)
        {
        }
        /**
         * Clean up after the given relationship definition.
         *
         * @return \Toolset_Result_Set
         */
        public function do_cleanup()
        {
        }
    }
}
namespace {
    /**
     * Validate the relationship slug before renaming.
     *
     * @since m2m
     */
    class Toolset_Relationship_Slug_Validator
    {
        /**
         * Toolset_Relationship_Slug_Validator constructor.
         *
         * @param string $slug_candidate
         * @param Toolset_Relationship_Definition $relationship_to_rename
         * @param Toolset_Relationship_Definition_Repository|null $definition_repository_di
         */
        public function __construct($slug_candidate, \Toolset_Relationship_Definition $relationship_to_rename, \Toolset_Relationship_Definition_Repository $definition_repository_di = \null)
        {
        }
        /**
         * Validate and return the result with a user-friendly message.
         *
         * @return Toolset_Result
         */
        public function validate()
        {
        }
    }
    /**
     * Factory for instantiating relationship definitions.
     *
     * For internal m2m API use only.
     *
     * @since m2m
     */
    class Toolset_Relationship_Definition_Factory
    {
        /**
         * @param array $definition_array Definition array of the relationship.
         *
         * @return Toolset_Relationship_Definition
         * @throws InvalidArgumentException
         */
        public function create($definition_array)
        {
        }
    }
    /**
     * Interface of the relationship definition.
     *
     * @since m2m
     */
    interface IToolset_Relationship_Definition
    {
        /**
         * Get the relationship slug.
         *
         * This value is unique and cannot change (except in special cases to be handled by a database transformation).
         *
         * @return string
         * @since m2m
         */
        public function get_slug();
        /**
         * Get the display name of the relationship.
         *
         * @return string
         * @since m2m
         */
        public function get_display_name();
        /**
         * Update the relationship (plural) display name.
         *
         * @param string $display_name
         *
         * @since m2m
         */
        public function set_display_name($display_name);
        /**
         * Synonymous to get_display_name().
         *
         * @return string
         * @since m2m
         */
        public function get_display_name_plural();
        /**
         * Get the singular display name of the relationship.
         *
         * @return string
         * @since m2m
         */
        public function get_display_name_singular();
        /**
         * Update the relationship singular display name.
         *
         * @param string $display_name
         *
         * @since m2m
         */
        public function set_display_name_singular($display_name);
        /**
         * Get the parent entity type definition.
         *
         * @return Toolset_Relationship_Element_Type
         * @since m2m
         */
        public function get_parent_type();
        /**
         * Get the child entity type definition.
         *
         * @return Toolset_Relationship_Element_Type
         * @since m2m
         */
        public function get_child_type();
        public function get_parent_domain();
        public function get_child_domain();
        public function get_domain($element_role);
        /**
         * Get a type of elements that can take a role in the relationship.
         *
         * @param string|IToolset_Relationship_Role $element_role
         *
         * @return Toolset_Relationship_Element_Type
         * @since m2m
         */
        public function get_element_type($element_role);
        /**
         * Set a type of elements that can take a role in the relationship.
         *
         * Use with caution. Without further adjustments, this can cause a database inconsistency.
         *
         * @param Toolset_Relationship_Element_Type $element_type
         * @param IToolset_Relationship_Role_Parent_Child|string $role
         *
         * @return void
         * @since 2.5.6
         */
        public function set_element_type($role, \Toolset_Relationship_Element_Type $element_type);
        /**
         * Determine if there are posts on the given side of the relationship.
         *
         * @param string|IToolset_Relationship_Role $element_role
         *
         * @return bool
         * @since m2m
         */
        public function is_post($element_role);
        /**
         * @return Toolset_Relationship_Cardinality
         */
        public function get_cardinality();
        /**
         * Update the relationship cardinality.
         *
         * @param Toolset_Relationship_Cardinality $value
         *
         * @throws InvalidArgumentException
         * @since m2m
         */
        public function set_cardinality($value);
        /**
         * Check if this relationship has some association fields defined.
         *
         * @return bool
         * @since m2m
         */
        public function has_association_field_definitions();
        /**
         * Get definitions of association fields.
         *
         * @return Toolset_Field_Definition[]
         * @since m2m
         */
        public function get_association_field_definitions();
        /**
         * Creates an association of this relationship between two elements.
         *
         * So far, only native relationships are supported. In their case, an intermediary post is created automatically,
         * if the relationship requires it.
         *
         * @param int|WP_Post|IToolset_Element $parent Parent element (of matching domain, type and other conditions)
         * @param int|WP_Post|IToolset_Element $child Child element (of matching domain, type and other conditions)
         * @param null|int $intermediary_id ID of the intermediary post to set. If null, the post will be
         * 		created automatically (if the relationship needs it)
         *
         * @return Toolset_Result|IToolset_Association The newly created association or a negative Toolset_Result when it could not have been created.
         * @throws RuntimeException when the association cannot be created because of a known reason. The exception would
         *     contain a displayable error message.
         * @throws InvalidArgumentException when the method is used improperly.
         * @throws Toolset_Element_Exception_Element_Doesnt_Exist
         *
         * @since m2m
         */
        public function create_association($parent, $child, $intermediary_id = \null);
        /**
         * @param IToolset_Association $association
         *
         * @return \OTGS\Toolset\Common\Result\ResultInterface
         */
        public function delete_association(\IToolset_Association $association);
        /**
         * Determine or set whether the relationship is distinct, which means that only one association between
         * each two elements can exist.
         *
         * @param null|bool $new_value If a boolean value is provided, it will be set.
         *
         * @return bool
         * @since m2m
         */
        public function is_distinct($new_value = \null);
        /**
         * Determine whether this relationship involves translatable elements.
         *
         * That includes possible parent and child types as well as association fields.
         *
         * Note that the value is cached for performance reasons and it may apply a lot of WPML filters on the first time.
         *
         * @return bool
         * @since m2m
         */
        public function is_translatable();
        /**
         * Get a custom role name that should be recognized in shortcodes instead of parent, child, etc.
         *
         * @param string|IToolset_Relationship_Role $role One of the Toolset_Relationship_Role values.
         *
         * @return string Custom role name.
         * @since m2m
         */
        public function get_role_name($role);
        /**
         * Get all custom role names as an associative array.
         *
         * @return string[string]
         * @since m2m
         */
        public function get_role_names();
        /**
         * Update a custom role name.
         *
         * The name will be sanitized and the value actually saved will be returned.
         *
         * @param string|IToolset_Relationship_Role $role One of the Toolset_Relationship_Role values.
         * @param string $custom_name Custom name for the role.
         *
         * @return string Sanitized custom name
         * @since m2m
         */
        public function set_role_name($role, $custom_name);
        /**
         * If the relationship was migrated from the legacy post relationships, we need to
         * provide backward compatibility for it.
         *
         * @return bool
         * @since m2m
         */
        public function needs_legacy_support();
        /**
         * Defines whether the relationship is active on the site (whether it should be taken into account at all).
         *
         * @param null|bool $value
         *
         * @return bool
         */
        public function is_active($value = \null);
        /**
         * Defines whether intermediary posts of this relationship should be automatically deleted
         * together with an association.
         *
         * @param null|bool $value If a boolean value is provided, it will be set.
         *
         * @return bool
         * @since Types 3.2
         */
        public function is_autodeleting_intermediary_posts($value = \null);
        /**
         * @return IToolset_Relationship_Origin
         * @since m2m
         */
        public function get_origin();
        /**
         * Set origin
         * Can be set by using the origin keyword or the class
         *
         * @param IToolset_Relationship_Origin|string $origin
         *
         * @return void
         * @since m2m
         */
        public function set_origin($origin);
        /**
         * @return int
         */
        public function get_row_id();
        /**
         * Return the number of existing associations belonging to the relationships
         *
         * @param string|IToolset_Relationship_Role $role Role.
         *
         * @return int
         * @since m2m
         */
        public function get_max_associations($role);
        /**
         * Get the intermediary post type, if it exists.
         *
         * Note that its existence doesn't necessarily mean that there are association fields.
         *
         * @return null|string
         */
        public function get_intermediary_post_type();
        /**
         * Set the intermediary post type for this relationship.
         *
         * Use with caution.
         *
         * @param IToolset_Post_Type_From_Types|null $post_type
         * @param bool $override_integrity_check If this is true, do not check whether the given post type can be used as an intermediary.
         *
         * @since 3.0.5
         */
        public function set_intermediary_post_type($post_type, $override_integrity_check = \false);
        /**
         * Update a custom role singular name.
         *
         * The name will be sanitized and the value actually saved will be returned.
         *
         * @param string|IToolset_Relationship_Role $element_role One of the Toolset_Relationship_Role values.
         * @param string $custom_name Custom name for the role.
         *
         * @return string Sanitized custom name
         * @since m2m
         */
        public function set_role_label_singular($element_role, $custom_name);
        /**
         * Update a custom role plural name.
         *
         * The name will be sanitized and the value actually saved will be returned.
         *
         * @param string|IToolset_Relationship_Role $element_role One of the Toolset_Relationship_Role values.
         * @param string $custom_name Custom name for the role.
         *
         * @return string Sanitized custom name
         * @since m2m
         */
        public function set_role_label_plural($element_role, $custom_name);
        /**
         * Get all custom role singular names as an associative array.
         *
         * @param boolean $translate If it has to be translated.
         *
         * @return string[]
         * @since m2m
         * @since 3.0 New parameter $translate
         */
        public function get_role_labels_singular($translate = \true);
        /**
         * Get all custom role plural names as an associative array.
         *
         * @param bool $translate If it has to be translated.
         *
         * @return string[]
         * @since m2m
         * @since 3.0 New parameter $translate
         */
        public function get_role_labels_plural($translate = \true);
        /**
         * Lists default aliases by role type
         *
         * @return array
         * @since m2m
         */
        public function get_default_labels();
    }
    /**
     * Translates between a database row from the toolset_relationships table and a relationship definition.
     *
     * This is the only place when such process is supposed to take place.
     *
     * Never use this class outside of the m2m API.
     *
     * @since 2.5.2
     */
    class Toolset_Relationship_Definition_Translator
    {
        public function __construct(\Toolset_Relationship_Definition_Factory $definition_factory_di = \null)
        {
        }
        /**
         * Convert a relationship definition into a database row
         *
         * @param Toolset_Relationship_Definition $definition
         * @return array
         * @since m2m
         */
        public function to_database_row($definition)
        {
        }
        /**
         * Load a single relationship definition from a definition array.
         *
         * @param object $database_row
         * @return null|Toolset_Relationship_Definition The relationship definition or null if it was not
         *     possible to load it (which means that the definition array was invalid).
         * @since m2m
         */
        public function from_database_row($database_row)
        {
        }
        /**
         * Get an array of formats for $wpdb when working with the database row generated by this class.
         *
         * @return string[]
         */
        public function get_database_row_formats()
        {
        }
    }
    /**
     * Relationship definition.
     *
     * Besides acting as a data model, it also intermediates the relationship driver.
     *
     * Here is the only code that understands the relationship definition array (specifically, read_definition_array()
     * and get_definition_array()), which should never ever be used or accessed anywhere else (except loading and
     * saving in the factory class).
     *
     * All instances of this class should be managed exclusively by methods of the
     * Toolset_Relationship_Definition_Repository class.
     *
     * @since m2m
     */
    class Toolset_Relationship_Definition implements \IToolset_Relationship_Definition
    {
        // Definition array keys
        const DA_SLUG = 'slug';
        const DA_DISPLAY_NAME_PLURAL = 'display_name_plural';
        const DA_DISPLAY_NAME_SINGULAR = 'display_name_singular';
        const DA_DRIVER = 'driver';
        const DA_PARENT_TYPE = 'parent';
        const DA_CHILD_TYPE = 'child';
        const DA_PARENT_TYPE_SET_ID = 'parent_type_set_id';
        const DA_CHILD_TYPE_SET_ID = 'child_type_set_id';
        const DA_CARDINALITY = 'cardinality';
        const DA_DRIVER_SETUP = 'driver_setup';
        const DA_OWNERSHIP = 'ownership';
        const DA_IS_DISTINCT = 'is_distinct';
        const DA_SCOPE = 'scope';
        const DA_ROLE_NAMES = 'role_names';
        const DA_ROLE_LABELS_SINGULAR = 'role_labels_singular';
        const DA_ROLE_LABELS_PLURAL = 'role_labels_plural';
        const DA_NEEDS_LEGACY_SUPPORT = 'needs_legacy_support';
        const DA_IS_ACTIVE = 'is_active';
        const DA_AUTODELETE_INTERMEDIARY = 'autodelete_intermediary';
        const DA_ORIGIN = 'origin';
        const DA_ROW_ID = 'row_id';
        // Supported relationship driver names.
        // At the moment, only the native Toolset relationships are supported.
        const DRIVER_NATIVE = 'toolset';
        const OWNER_IS_PARENT = 'parent';
        const OWNER_IS_CHILD = 'child';
        /**
         * Toolset_Relationship_Definition constructor.
         *
         * @param array $definition_array Valid definition array.
         * @param Toolset_Potential_Association_Query_Factory|null $potential_association_query_factory_di
         *
         * @param \OTGS\Toolset\Common\WPML\Package\RelationshipDefinitionTranslationPackage|null $wpml_package_di
         * @param Toolset_WPML_Compatibility|null $wpml_compatibility_di
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory|null $database_layer_factory_di
         * @param \OTGS\Toolset\Common\Relationships\UserPermissions\Factory $user_permissions_factory
         *
         * @refactoring Unit tests are hard to do with this constructor
         * @since m2m
         */
        public function __construct($definition_array, \Toolset_Potential_Association_Query_Factory $potential_association_query_factory_di = \null, \OTGS\Toolset\Common\WPML\Package\RelationshipDefinitionTranslationPackage $wpml_package_di = \null, \Toolset_WPML_Compatibility $wpml_compatibility_di = \null, \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory $database_layer_factory_di = \null, \OTGS\Toolset\Common\Relationships\UserPermissions\Factory $user_permissions_factory = \null)
        {
        }
        /**
         * @inheritdoc
         *
         * @return string
         * @since m2m
         */
        public function get_slug()
        {
        }
        /**
         * Update the relationship slug.
         *
         * The usage of this method is strictly limited to the m2m API, always change the slug via
         * Toolset_Relationship_Definition_Repository::change_definition_slug().
         *
         * At the very least, it is assumed that the new slug value is validated via Toolset_Relationship_Slug_Validator.
         *
         * @param string $new_slug
         *
         * @since m2m
         */
        public function set_slug($new_slug)
        {
        }
        /**
         * @inheritdoc
         *
         * @param boolean $translate If it has to be translated.
         *
         * @return string
         * @since m2m
         * @since 3.0 New parameter $translate
         */
        public function get_display_name($translate = \true)
        {
        }
        /**
         * @inheritdoc
         *
         * @param string $display_name
         *
         * @since m2m
         */
        public function set_display_name($display_name)
        {
        }
        /**
         * Synonymous to get_display_name().
         *
         * @param boolean $translate If it has to be translated.
         *
         * @return string
         * @since m2m
         * @since 3.0 New parameter $translate
         */
        public function get_display_name_plural($translate = \true)
        {
        }
        /**
         * Get the singular display name of the relationship.
         *
         * @param boolean $translate If it has to be translated.
         *
         * @return string
         * @since m2m
         * @since 3.0 New parameter $translate
         */
        public function get_display_name_singular($translate = \true)
        {
        }
        /**
         * Update the relationship singular display name.
         *
         * @param string $display_name
         *
         * @since m2m
         */
        public function set_display_name_singular($display_name)
        {
        }
        /**
         * Get the parent entity type definition.
         *
         * @return Toolset_Relationship_Element_Type
         * @since m2m
         */
        public function get_parent_type()
        {
        }
        /**
         * Get the child entity type definition.
         *
         * @return Toolset_Relationship_Element_Type
         * @since m2m
         */
        public function get_child_type()
        {
        }
        public function get_parent_domain()
        {
        }
        public function get_child_domain()
        {
        }
        /**
         * @param string|IToolset_Relationship_Role $element_role
         *
         * @return string
         */
        public function get_domain($element_role)
        {
        }
        /**
         * Get a relationship entity type.
         *
         * @param string|IToolset_Relationship_Role $element_role
         *
         * @return Toolset_Relationship_Element_Type
         * @since m2m
         */
        public function get_element_type($element_role)
        {
        }
        /**
         * Get an set_id that references type slugs in the toolset_type_sets table for a given role.
         * Obviously, never use this outside of m2m API.
         *
         * @param IToolset_Relationship_Role_Parent_Child|string $element_role
         *
         * @return int Set ID or zero if the type set is not persisted yet.
         */
        public function get_element_type_set_id($element_role)
        {
        }
        /**
         * Set type of a relationship role (parent or child).
         *
         * Must not be used outside m2m API.
         *
         * @param string|IToolset_Relationship_Role $element_role
         * @param Toolset_Relationship_Element_Type $type
         *
         * @since m2m
         */
        public function set_element_type($element_role, \Toolset_Relationship_Element_Type $type)
        {
        }
        /**
         * Determine if there are posts on the given side of the relationship.
         *
         * @param string|IToolset_Relationship_Role $element_role
         *
         * @return bool
         * @since m2m
         */
        public function is_post($element_role)
        {
        }
        /**
         * Build a definition array for persisting the definition.
         *
         * @param boolean $translate If it has to be translated.
         *
         * @return array
         * @since m2m
         * @since 3.0 New parameter $translate
         */
        public function get_definition_array($translate = \true)
        {
        }
        /**
         * Get the relationship driver. Initialize it if called for the first time.
         *
         * @return Toolset_Relationship_Driver
         * @since m2m
         */
        public function get_driver()
        {
        }
        public function get_cardinality()
        {
        }
        /**
         * Update the relationship cardinality.
         *
         * @param Toolset_Relationship_Cardinality $value
         *
         * @throws InvalidArgumentException
         * @since m2m
         */
        public function set_cardinality($value)
        {
        }
        /**
         * Check if this relationship has some association fields defined.
         *
         * @return bool
         * @since m2m
         */
        public function has_association_field_definitions()
        {
        }
        /**
         * Get definitions of association fields.
         *
         * @return Toolset_Field_Definition[]
         * @since m2m
         */
        public function get_association_field_definitions()
        {
        }
        /**
         * Get the intermediary post type, if it exists.
         *
         * Note that its existence doesn't necessarily mean that there are association fields.
         *
         * @return null|string
         * @since m2m
         */
        public function get_intermediary_post_type()
        {
        }
        /**
         * Set the intermediary post type for this relationship.
         *
         * Use with caution.
         *
         * @param IToolset_Post_Type_From_Types|null $post_type Post type to be used as intermediary or null to unset
         *     the current IPT from the relationship.
         * @param bool $override_integrity_check If this is true, do not check whether the given post type can be used as
         *     an intermediary.
         *
         * @since 3.0.5
         */
        public function set_intermediary_post_type($post_type, $override_integrity_check = \false)
        {
        }
        public function is_ownership()
        {
        }
        public function get_owner()
        {
        }
        /**
         * @param IToolset_Element|IToolset_Element[] $parent_or_elements
         * @param IToolset_Element|null $child
         *
         * @return bool
         * @throws InvalidArgumentException
         * @since m2m
         * @since 2.5.7 Deprecated.
         * @deprecated Use PotentialAssociationQuery::check_single_element() instead.
         */
        public function can_associate($parent_or_elements, $child = \null)
        {
        }
        /**
         * Creates an association of this relationship between two elements.
         *
         * So far, only native relationships are supported. In their case, an intermediary post is created automatically,
         * if the relationship requires it.
         *
         * @param int|WP_Post|Toolset_Element $parent Parent element (of matching domain, type and other conditions)
         * @param int|WP_Post|Toolset_Element $child Child element (of matching domain, type and other conditions)
         * @param null|int $intermediary_id ID of the intermediary post to set. If null, the post will be
         *        created automatically (if the relationship needs it)
         *
         * @return Toolset_Result|IToolset_Association The newly created association or a negative Toolset_Result when it
         *     could not have been created.
         * @throws RuntimeException when the association cannot be created because of a known reason. The exception would
         *     contain a displayable error message.
         * @throws InvalidArgumentException when the method is used improperly.
         * @throws Toolset_Element_Exception_Element_Doesnt_Exist
         *
         * @since m2m
         */
        public function create_association($parent, $child, $intermediary_id = \null)
        {
        }
        public function delete_association(\IToolset_Association $association)
        {
        }
        /**
         * Determine or set whether the relationship is distinct, which means that only one association between
         * each two elements can exist.
         *
         * @param null|bool $new_value If a boolean value is provided, it will be set.
         *
         * @return bool
         * @since m2m
         */
        public function is_distinct($new_value = \null)
        {
        }
        /**
         * Determine whether this relationship has a scope defined.
         *
         * @return bool
         */
        public function has_scope()
        {
        }
        /**
         * @return null|Toolset_Relationship_Scope
         */
        public function get_scope()
        {
        }
        /**
         * Determine whether this relationship involves translatable elements.
         *
         * That includes possible parent and child types as well as association fields.
         *
         * Note that the value is cached for performance reasons and it may apply a lot of WPML filters on the first time.
         *
         * @return bool
         * @since m2m
         */
        public function is_translatable()
        {
        }
        /**
         * Get a custom role name that should be recognized in shortcodes instead of parent, child, etc.
         *
         * @param string|IToolset_Relationship_Role $element_role One of the Toolset_Relationship_Role values.
         *
         * @return string Custom role name.
         * @since m2m
         */
        public function get_role_name($element_role)
        {
        }
        /**
         * Get a custom role singular name that should be recognized in shortcodes instead of parent, child, etc.
         *
         * @param string|IToolset_Relationship_Role $element_role One of the Toolset_Relationship_Role values.
         * @param boolean $translate If it has to be translated.
         *
         * @return string Custom role name.
         * @since m2m
         * @since 3.0 New parameter $translate
         */
        public function get_role_label_singular($element_role, $translate = \true)
        {
        }
        /**
         * Get a custom role plural name that should be recognized in shortcodes instead of parent, child, etc.
         *
         * @param string|IToolset_Relationship_Role $element_role One of the Toolset_Relationship_Role values.
         * @param boolean $translate If it has to be translated.
         *
         * @return string Custom role name.
         * @since m2m
         * @since 3.0 New parameter $translate
         */
        public function get_role_label_plural($element_role, $translate = \true)
        {
        }
        /**
         * Get all custom role names as an associative array.
         *
         * @return string[string]
         * @since m2m
         */
        public function get_role_names()
        {
        }
        /**
         * @inheritDoc
         */
        public function get_role_labels_singular($translate = \true)
        {
        }
        /**
         * @inheritDoc
         */
        public function get_role_labels_plural($translate = \true)
        {
        }
        /**
         * @inheritDoc
         */
        public function get_default_labels()
        {
        }
        /**
         * Update a custom role name.
         *
         * The name will be sanitized and the value actually saved will be returned.
         *
         * @param string|IToolset_Relationship_Role $element_role One of the Toolset_Relationship_Role values.
         * @param string $custom_name Custom name for the role.
         *
         * @return string Sanitized custom name
         * @since m2m
         */
        public function set_role_name($element_role, $custom_name)
        {
        }
        /**
         * @inheritDoc
         */
        public function set_role_label_singular($element_role, $custom_name)
        {
        }
        /**
         * @inheritDoc
         */
        public function set_role_label_plural($element_role, $custom_name)
        {
        }
        /**
         * If the relationship was migrated from the legacy post relationships, we need to
         * provide backward compatibility for it.
         *
         * @return bool
         * @since m2m
         */
        public function needs_legacy_support()
        {
        }
        /**
         * Set the status of legacy support requirement.
         *
         * This MUST NOT be used anywhere except the migration procedure.
         *
         * @param bool $is_legacy_support_needed
         *
         * @since m2m
         */
        public function set_legacy_support_requirement($is_legacy_support_needed)
        {
        }
        /**
         * Defines whether the relationship is active on the site (whether it should be taken into account at all).
         *
         * @param null|bool $value
         *
         * @return bool
         */
        public function is_active($value = \null)
        {
        }
        /**
         * Defines whether intermediary posts of this relationship should be automatically deleted
         * together with an association.
         *
         * @param null|bool $value If a boolean value is provided, it will be set.
         *
         * @return bool
         * @since Types 3.2
         */
        public function is_autodeleting_intermediary_posts($value = \null)
        {
        }
        /**
         * @return IToolset_Relationship_Origin
         * @since m2m
         */
        public function get_origin()
        {
        }
        /**
         * Set origin
         * Can be set by using the origin keyword or the class
         *
         * @param IToolset_Relationship_Origin|string $origin
         *
         * @since m2m
         */
        public function set_origin($origin)
        {
        }
        /**
         * Get an ID of the database row where this relationship definition is stored.
         *
         * @return int Careful: This can be zero if no ID is available (relationship is not saved yet).
         */
        public function get_row_id()
        {
        }
        /**
         * Return the number of existing associations belonging to the relationships
         *
         * @param string|IToolset_Relationship_Role $role Role.
         *
         * @return int
         * @since m2m
         */
        public function get_max_associations($role)
        {
        }
        /**
         * Set default role aliases
         *
         * @since m2m
         */
        public function set_default_role_labels()
        {
        }
        /**
         * Gets WPML compatibility
         *
         * Needed to be public because I need to inject a mock into a mock :(
         *
         * @return Toolset_WPML_Compatibility
         * @since 3.0
         */
        public function get_wpml_compatibility()
        {
        }
        /**
         * Creates a PermissionService instance with the relationship post types.
         *
         * @return \OTGS\Toolset\Common\Relationships\UserPermissions\PermissionService
         */
        public function get_user_permissions()
        {
        }
    }
    /**
     * Handles the persistence of relationship definitions, from IToolset_Relationship_Definition object to a wpdb call.
     *
     * For internal purposes of the m2m API only. Use Toolset_Relationship_Definition_Repository().
     *
     * @since 2.5.2
     */
    class Toolset_Relationship_Definition_Persistence
    {
        public function __construct(\wpdb $wpdb_di = \null, \Toolset_Relationship_Definition_Translator $definition_translator_di = \null, \Toolset_Relationship_Table_Name $table_name_di = \null, \Toolset_Post_Type_Repository $post_type_repository = \null, \OTGS\Toolset\Common\WPML\Package\RelationshipDefinitionTranslationPackage $wpml_package_definition_di = \null, \OTGS\Toolset\Common\WPML\WpmlService $wpml_compatibility_di = \null)
        {
        }
        /**
         * Update a single relationship definition.
         *
         * @param Toolset_Relationship_Definition $relationship_definition
         *
         * @return \OTGS\Toolset\Common\Result\SingleResult
         */
        public function persist_definition(\Toolset_Relationship_Definition $relationship_definition)
        {
        }
        /**
         * Insert a new relationship definition record into the database.
         *
         * @param Toolset_Relationship_Definition $relationship_definition
         *
         * @return null|Toolset_Relationship_Definition
         */
        public function insert_definition(\Toolset_Relationship_Definition $relationship_definition)
        {
        }
        /**
         * Delete a relationship definition record from the database.
         *
         * @param Toolset_Relationship_Definition $relationship_definition
         */
        public function delete_definition(\Toolset_Relationship_Definition $relationship_definition)
        {
        }
    }
    /**
     * Factory class for relationship definitions.
     *
     * Use as a singleton in production code.
     *
     * All relationship definitions are stored in a form of definition arrays in a single option.
     * When this class is instantiated, they will be all loaded at once.
     *
     * After making changes to relationship definitions, those must be persisted by calling save_definitions().
     *
     * TODO Lot of things here can be optimized now that we store definitions in their own table.
     *
     * @since m2m
     */
    class Toolset_Relationship_Definition_Repository
    {
        /**
         * @return Toolset_Relationship_Definition_Repository
         * @noinspection PhpDocMissingThrowsInspection
         */
        public static function get_instance()
        {
        }
        /**
         * Toolset_Relationship_Definition_Repository constructor.
         *
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory|null $database_layer_factory
         * @param Toolset_Relationship_Definition_Persistence|null $definition_persistence_di
         * @param Toolset_Relationship_Definition_Translator|null $definition_translator_di
         *
         * @throws \OTGS\Toolset\Common\Auryn\InjectionException
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory $database_layer_factory = \null, \Toolset_Relationship_Definition_Persistence $definition_persistence_di = \null, \Toolset_Relationship_Definition_Translator $definition_translator_di = \null)
        {
        }
        /**
         * Load relationship definitions.
         *
         * Never use from outside the class, except testing.
         *
         * @since m2m
         */
        public function load_definitions()
        {
        }
        /**
         * Remove a definition from the array of managed ones.
         *
         * If it isn't there already, it does nothing.
         *
         * @param IToolset_Relationship_Definition|string $definition Definition itself or its slug.
         *
         * @param bool $do_cleanup true to delete related associations,
         *     intermediary post type and the intermediary post field group, if they exist.
         *
         * @return \OTGS\Toolset\Common\Result\ResultSet
         * @since m2m
         */
        public function remove_definition($definition, $do_cleanup = \true)
        {
        }
        /**
         * Get all relationship definitions.
         *
         * @return IToolset_Relationship_Definition[]
         */
        public function get_definitions()
        {
        }
        /**
         * Determine if a relationship definition with a given slug exists.
         *
         * @param string $slug
         * @return bool
         * @since m2m
         */
        public function definition_exists($slug)
        {
        }
        /**
         * Get a relationship definition with given slug.
         *
         * @param string $slug
         * @return null|IToolset_Relationship_Definition
         * @since m2m
         */
        public function get_definition($slug)
        {
        }
        /**
         * Get a relationship definition with a given row ID.
         *
         * @param int $row_id
         *
         * @return null|Toolset_Relationship_Definition
         */
        public function get_definition_by_row_id($row_id)
        {
        }
        /**
         * Create a new definition, persist it in the database and start managing it.
         *
         * @param string $slug Valid (sanitized) relationship slug.
         * @param Toolset_Relationship_Element_Type $parent Parent entity type.
         * @param Toolset_Relationship_Element_Type $child Child entity type.
         *
         * @param bool $allow_slug_adjustment
         *
         * @return IToolset_Relationship_Definition
         * @since m2m
         * @since 2.5.5 persists the relationship in the database.
         */
        public function create_definition($slug, $parent, $child, $allow_slug_adjustment = \true)
        {
        }
        /**
         * Creates a definition for the Post Reference Field
         *
         * @param string $field_slug
         * @param string $field_group_slug
         * @param string $post_reference_type
         * @param Toolset_Relationship_Element_Type $parent
         * @param Toolset_Relationship_Element_Type $child
         *
         * @return IToolset_Relationship_Definition
         * @since m2m
         * @noinspection PhpUnusedParameterInspection
         */
        public function create_definition_post_reference_field($field_slug, $field_group_slug, $post_reference_type, $parent, $child)
        {
        }
        /**
         * Persist all relationship definitions in the database.
         *
         * @deprecated Use persist_definition() only on the relationship that has been changed.
         * @since m2m
         */
        public function save_definitions()
        {
        }
        /**
         * Update a single relationship definition.
         *
         * @param IToolset_Relationship_Definition $relationship_definition
         *
         * @since 2.5.2
         * @return \OTGS\Toolset\Common\Result\ResultInterface
         */
        public function persist_definition(\IToolset_Relationship_Definition $relationship_definition)
        {
        }
        /**
         * Look for a relationship between posts that was migrated from the legacy post relationships.
         *
         * @param string $parent_post_type
         * @param string $child_post_type
         *
         * @return IToolset_Relationship_Definition|null Relationship definition or null if none exists.
         * @since m2m
         *
         * todo This can be optimized greatly by extending Toolset_Relationship_Query
         */
        public function get_legacy_definition($parent_post_type, $child_post_type)
        {
        }
        /**
         * Rename the relationship definition slug properly.
         *
         * Ensure that:
         * - the database integrity is maintained
         * - the cache in this repository is updated
         *
         * @param IToolset_Relationship_Definition $relationship_definition
         * @param string $new_slug
         *
         * @return \OTGS\Toolset\Common\Result\SingleResult
         *
         * @since m2m
         */
        public function change_definition_slug($relationship_definition, $new_slug)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\UserPermissions {
    /**
     * Factory for instantiating relationship user permissions.
     *
     * @since Types 3.4.2
     */
    class Factory
    {
        /**
         * @param string[] ...$post_types Array of post type slugs.
         *
         * @return PermissionService
         *
         * @throws \InvalidArgumentException Thrown in case any of the post types isn't a string.
         */
        public function create(...$post_types)
        {
        }
    }
    /**
     * The class provides the necessary interface with Toolset Access APIs / WP Capabilities APIs.
     * And the necessary methods to check how a relationship post type capabilities are managed.
     *
     * @since Types 3.4.2
     */
    class PermissionService
    {
        /**
         * The key in this array refers to Toolset Access Option name.
         * And the value refers to the WordPress capability name.
         *
         * @var string[]
         */
        const OPTIONS = ['publish' => 'publish_posts', 'edit_any' => 'edit_others_posts', 'edit_own' => 'edit_posts', 'delete_any' => 'delete_others_posts', 'delete_own' => 'delete_posts'];
        /**
         * PermissionService constructor.
         *
         * @param string[] ...$post_types
         *
         * @throws \InvalidArgumentException Thrown in case any of the post types isn't a string.
         */
        public function __construct(...$post_types)
        {
        }
        /**
         * Returns a keyed array where the key is the capability option name and the value is a boolean.
         *
         * @param string $type Post type slug.
         *
         * @return bool[]
         */
        public function get_user_caps($type)
        {
        }
    }
}
namespace {
    /**
     * Interface for all potential association query filters.
     *
     * @since m2m
     * TODO create a properly namespaced alias for this
     */
    interface Toolset_Potential_Association_Query_Filter_Interface
    {
        /**
         * Main method to modiy the query arguments.
         *
         * @param array $query_arguments
         *
         * @since m2m
         */
        public function filter(array $query_arguments);
    }
    /**
     * Filter the potential posts association query by the author of the option.
     *
     * Each Toolset individual plugin can extend this filter to add its own API filters, using the filter_by_plugin method.
     *
     * @since m2m
     * TODO create a properly namespaced alias for this
     */
    class Toolset_Potential_Association_Query_Filter_Posts_Author implements \Toolset_Potential_Association_Query_Filter_Interface
    {
        /**
         * Maybe filter the list of available posts to connect to a given post by their post author.
         *
         * Free method for individual Toolset plugins to subclass and implement.
         *
         * @param mixed $force_author The original value is a boolean but it might be filtered to become an integer or a string.
         *
         * @return mixed
         *
         * @since m2m
         */
        protected function filter_by_plugin($force_author)
        {
        }
        /**
         * Maybe filter the list of available posts to connect to a given post by their post author.
         *
         * Decides whether a filter by post author needs to be set by cascading a series of filters:
         * - toolset_force_author_in_related_post
         *
         * Those filters should return either a post author ID or the keyword '$current', which is a placeholder
         * for the currently logged in user; in case no user is logged in, we force empty query results.
         *
         * Note that individual Toolset plugins can include their own filters by subclassing this one
         * and including just a filter_by_plugin method containing their API filters chain.
         *
         * @param array $query_arguments The potential association query arguments.
         *
         * @return array
         *
         * @since m2m
         */
        public function filter(array $query_arguments)
        {
        }
    }
    /**
     * Filter the potential posts association query by the post status.
     *
     * Each Toolset individual plugin can extend this filter to add its own API filters, using the filter_by_plugin method.
     *
     * @since 3.0.2
     * TODO create a properly namespaced alias for this
     */
    class Toolset_Potential_Association_Query_Filter_Posts_Status implements \Toolset_Potential_Association_Query_Filter_Interface
    {
        /**
         * Maybe filter the list of available posts to connect to a given post by their status.
         *
         * Free method for individual Toolset plugins to subclass and implement.
         *
         * @param string|string[] $post_status
         * @return string|string[]
         */
        protected function filter_by_plugin($post_status)
        {
        }
        /**
         * Maybe filter the list of available posts to connect to a given post by their status.
         *
         * Decides whether a filter by post status needs to be set by cascading a series of filters:
         * - toolset_force_post_status_related_post
         * - filters in subclasses
         *
         * Those filters should return either a single post status or array of statuses.
         *
         * Note that individual Toolset plugins can include their own filters by subclassing this one
         * and including just a filter_by_plugin method containing their API filters chain.
         *
         * @param array $query_arguments The potential association query arguments.
         * @return array
         */
        public function filter(array $query_arguments)
        {
        }
    }
    /**
     * Toolset potential associations query arguments controller.
     *
     * @since m2m
     * TODO create a properly namespaced alias for this
     */
    class Toolset_Potential_Association_Query_Arguments
    {
        /**
         * Register a filter to modify the query arguments.
         *
         * @since m2m
         *
         * @param Toolset_Potential_Association_Query_Filter_Interface $filter
         * @return $this
         */
        public function addFilter(\Toolset_Potential_Association_Query_Filter_Interface $filter)
        {
        }
        /**
         * Apply the filters to the query arguments.
         *
         * @since m2m
         */
        public function get()
        {
        }
    }
    /**
     * Filter the potential association query by a given string.
     *
     * @since m2m
     * TODO create a properly namespaced alias for this
     */
    class Toolset_Potential_Association_Query_Filter_Search_String implements \Toolset_Potential_Association_Query_Filter_Interface
    {
        /**
         * Maybe filter the list of options by a given string.
         *
         * @param array $query_arguments The potential association query arguments.
         *
         * @return array
         *
         * @since m2m
         */
        public function filter(array $query_arguments)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\API {
    /**
     * Factory for providing access to instances of objects from the Relationship API.
     *
     * Actually, this may combine a factory and a repository pattern.
     *
     * You can instantiate it directly (in which case don't use any constructor parameters, as they may change),
     * but the preferred way is to do it via DIC during bootstrap phase.
     *
     * @since 4.0
     */
    class Factory
    {
        /**
         * Factory constructor.
         *
         * If using outside of the Relationships API codebase, never provide any parameters.
         *
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory|null $database_layer_factory
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory $database_layer_factory = null)
        {
        }
        /**
         * @return RelationshipQuery
         */
        public function relationship_query()
        {
        }
        /**
         * @return AssociationQuery
         */
        public function association_query()
        {
        }
        /**
         * @param \IToolset_Relationship_Definition $for_relationship
         * @param RelationshipRoleParentChild $for_role
         * @param \IToolset_Element $for_element
         * @param array $args
         *
         * @return PotentialAssociationQuery
         */
        public function potential_association_query(\IToolset_Relationship_Definition $for_relationship, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role, \IToolset_Element $for_element, $args = array())
        {
        }
        /**
         * @return AssociationDatabaseOperations
         */
        public function database_operations()
        {
        }
        /**
         * @return RelationshipRole
         */
        public function role_parent()
        {
        }
        /**
         * @return RelationshipRole
         */
        public function role_child()
        {
        }
        /**
         * @return RelationshipRole
         */
        public function role_intermediary()
        {
        }
        /**
         * Gateway to low-level operations.
         *
         * Within Toolset plugins, CONSULT BEFORE USING THIS IN YOUR CODE.
         * Outside of Toolset, NEVER USE THIS.
         *
         * @return LowLevelGateway
         */
        public function low_level_gateway()
        {
        }
    }
    /**
     * Enum for identifying an element in association query result by its role in the translation group.
     *
     * @since 4.0
     */
    final class ElementIdentification
    {
        /** @var string Default language element, if it exists. */
        const DEFAULT_LANGUAGE = 'default_language';
        /** @var string Current language or default, if it exists. */
        const CURRENT_LANGUAGE_IF_POSSIBLE = 'current_language';
        /** @var string Original language element (it is supposed to always exist). */
        const ORIGINAL_LANGUAGE = 'original_language';
        /**
         * All acceptable values.
         *
         * @return string[]
         */
        public static function all()
        {
        }
        /**
         * Interpret previously used values as this enum.
         *
         * True or 1 corresponds to "translate if possible" and false or 0 means "don't translate".
         *
         * @param bool|int|string $value
         *
         * @return string Valid enum value.
         */
        public static function parse($value)
        {
        }
    }
    /**
     * This is a gateway to working with relationships at a very low level,
     * to be used only by Toolset itself, instead of referencing the internals
     * of the relationships codebase directly.
     *
     * That rule is, obviously, not being kept in all cases, but any new interaction
     * really should go exclusively through the API.
     *
     * @since 4.0
     */
    class LowLevelGateway
    {
        /**
         * LowLevelGateway constructor.
         *
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory $database_layer_factory
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory $database_layer_factory)
        {
        }
        /**
         * @return bool
         */
        public function can_do_after_migration_cleanup()
        {
        }
        /**
         * @return \OTGS\Toolset\Common\Result\ResultInterface
         * @throws \Exception
         */
        public function do_after_migration_cleanup()
        {
        }
        public function can_do_after_migration_rollback()
        {
        }
        public function do_after_migration_rollback()
        {
        }
        /**
         * @param string|null $from_layer
         *
         * @return \OTGS\Toolset\Common\Relationships\DatabaseLayer\Migration\MigrationControllerInterface|null
         */
        public function get_available_migration_controller($from_layer = null)
        {
        }
        /**
         * Get the code for the database layer mode that is being used at the moment.
         *
         * @return string
         */
        public function get_current_database_layer_mode()
        {
        }
        /**
         * @param \IToolset_Relationship_Definition|null $relationship_definition
         *
         * @return IntermediaryPostPersistence
         */
        public function intermediary_post_persistence(\IToolset_Relationship_Definition $relationship_definition = null)
        {
        }
    }
    /**
     * Relationship query with a OOP/functional approach.
     *
     * Allows for chaining query conditions and avoiding passing query arguments as associative arrays.
     * It makes it also possible to build queries with nested AND & OR statements in an arbitrary way.
     * The object model may be complex but all the complexity is hidden from the user, they need to know
     * only the methods on this class.
     *
     * Example usage:
     *
     * $query = $factory->relationship_query()
     *
     * $results = $query
     *     ->add(
     *         $query->has_domain( 'posts' )
     *     )
     *     ->add(
     *         $query->do_or(
     *             $query->has_type( 'attachment', $factory->role_parent() ),
     *             $query->do_and(
     *                 $query->has_type( 'page', $factory->role_parent() ),
     *                 $query->is_legacy( false )
     *             )
     *         )
     *     )
     *     ->add( $query->is_active( '*' ) )
     *     ->get_results();
     *
     * Note:
     * - If no is_active() condition is used when constructing the query, is_active(true) is used. To get both
     *     active and non-active relationship definitions, you need to manually add is_active('*').
     * - If no has_active_post_types() condition is used when constructing the query, has_active_post_types(true)
     *     is used for both parent and child role.
     * - If no origin() condition is used, origin( 'wizard' ) is added by default.
     * - This mechanism doesn't recognize where, how and if these conditions are actually applied, so even
     *     $query->do_if( false, $query->is_active( true ) ) will disable the default is_active() condition.
     *
     * @since 4.0
     */
    interface RelationshipQuery
    {
        /**
         * Add another condition to the query.
         *
         * @param RelationshipQueryCondition $condition
         *
         * @return $this
         */
        public function add(\OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition $condition);
        /**
         * @return $this
         */
        public function do_not_add_default_conditions();
        /**
         * Apply stored conditions and perform the query.
         *
         * @return \IToolset_Relationship_Definition[]
         */
        public function get_results();
        /**
         * Get just the number of found relationships directly.
         *
         * @return int
         * @since 4.0
         */
        public function get_found_rows_directly();
        /**
         * Chain multiple conditions with OR.
         *
         * The whole statement will evaluate to true if at least one of provided conditions is true.
         *
         * @param RelationshipQueryCondition[] $conditions
         * @return RelationshipQueryCondition
         */
        public function do_or(...$conditions);
        /**
         * Chain multiple conditions with AND.
         *
         * The whole statement will evaluate to true if all provided conditions are true.
         *
         * @param RelationshipQueryCondition[] [$condition1, $condition2, ...]
         * @return RelationshipQueryCondition
         */
        public function do_and(...$conditions);
        /**
         * Condition that the relationship involves a certain domain.
         *
         * @param string $domain_name One of the Toolset_Element_Domain values.
         * @param RelationshipRole|null $in_role If null is provided, the type
         *    can be in both parent or child role for the condition to be true.
         *
         * @return RelationshipQueryCondition
         */
        public function has_domain($domain_name, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $in_role = null);
        /**
         * Condition that the relationship comes from a certain source
         *
         * @param string|null $origin One of the keywords from IToolset_Relationship_Origin or null to include relationships with all origins.
         *
         * @return RelationshipQueryCondition
         */
        public function origin($origin);
        /**
         * Condition that the relationship includes a certain intermediary object.
         *
         * @param string $intermediary_type An intermediary object slug.
         *
         * @return RelationshipQueryCondition
         *
         * @since 2.6.7
         */
        public function intermediary_type($intermediary_type);
        /**
         * Condition that the relationship has a certain type in a given role.
         *
         * @param string $type
         * @param RelationshipRoleParentChild|null $in_role If null is provided, the type
         *    can be in both parent or child role for the condition to be true.
         *
         * @return RelationshipQueryCondition
         */
        public function has_type($type, $in_role = null);
        /**
         * Condition that the relationship has a certain type in a given role.
         *
         * @param string $type
         * @param RelationshipRoleParentChild|null $in_role If null is provided, the type
         *    can be in both parent or child role for the condition to be true.
         *
         * @return RelationshipQueryCondition
         */
        public function exclude_type($type, $in_role = null);
        /**
         * Condition that the relationship has a certain type and a domain in a given role.
         *
         * @param string $type
         * @param string $domain One of the Toolset_Element_Domain values.
         * @param RelationshipRole|null $in_role If null is provided, the type
         *    can be in both parent or child role for the condition to be true.
         *
         * @return RelationshipQueryCondition
         */
        public function has_domain_and_type($type, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $in_role = null);
        /**
         * Condition that the relationship was migrated from the legacy implementation.
         *
         * @param bool $should_be_legacy
         *
         * @return RelationshipQueryCondition
         */
        public function is_legacy($should_be_legacy = true);
        /**
         * Condition that the relationship is active.
         *
         * @param bool $should_be_active
         *
         * @return RelationshipQueryCondition
         */
        public function is_active($should_be_active = true);
        /**
         * Condition that the relationship has at least one active post type in a given role (or another domain than posts).
         *
         * @param bool $has_active_post_types
         * @param RelationshipRoleParentChild|null $in_role
         *
         * @return RelationshipQueryCondition
         */
        public function has_active_post_types($has_active_post_types = true, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $in_role = null);
        /**
         * Get a factory of cardinality constrains, which can be used as an argument for $this->has_cardinality().
         *
         * @return \Toolset_Relationship_Query_Cardinality_Match_Factory
         */
        public function cardinality();
        /**
         * Condition that a relationship has a certain cardinality.
         *
         * Use methods on $this->cardinality() to obtain a valid argument for this method.
         *
         * @param \IToolset_Relationship_Query_Cardinality_Match $cardinality_match Object
         *     that holds cardinality constraints.
         *
         * @return RelationshipQueryCondition
         */
        public function has_cardinality(\IToolset_Relationship_Query_Cardinality_Match $cardinality_match);
        /**
         * Choose a query condition depending on a boolean expression.
         *
         * @param bool $statement A boolean condition statement.
         * @param RelationshipQueryCondition $if_branch Query condition that will be used
         *     if the statement is true.
         * @param RelationshipQueryCondition|null $else_branch Query condition that will be
         *     used if the statement is false. If none is provided, a tautology is used (always true).
         *
         * @return RelationshipQueryCondition
         * @since 2.5.6
         */
        public function do_if($statement, \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition $if_branch, \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition $else_branch = null);
        /**
         * Indicate that the query should also determine the total number of found rows.
         *
         * This has to be set to true if you plan using get_found_rows().
         *
         * @param bool $is_needed
         *
         * @since 2.5.8
         * @return $this
         */
        public function need_found_rows($is_needed = true);
        /**
         * Return a number of found rows.
         *
         * This can be called only after get_results() if need_found_rows() was set to true
         * while building the query. Otherwise, an exception will be thrown.
         *
         * @return int
         * @throws \RuntimeException
         * @since 2.5.8
         */
        public function get_found_rows();
        /**
         * Condition that excludes a relationship.
         *
         * @param \IToolset_Relationship_Definition $relationship Relationship Definition.
         *
         * @return RelationshipQueryCondition
         */
        public function exclude_relationship($relationship);
        /**
         * Define whether a cache can be used for this query. Defaults to true.
         *
         * @param bool $use_cache
         * @return $this
         * @since Types 3.4.7
         */
        public function use_cache($use_cache = true);
    }
    /**
     * Constant relevant for using the Relationships API.
     *
     * @since 4.0
     */
    abstract class Constants
    {
        /**
         * Warning: Changing this value in any way may break existing sites.
         */
        const MAXIMUM_RELATIONSHIP_SLUG_LENGTH = 190;
        const ORDER_ASC = 'ASC';
        const ORDER_DESC = 'DESC';
    }
    /**
     * Available values for the "element_status" query condition.
     *
     * @since 4.0
     */
    abstract class ElementStatusCondition
    {
        const STATUS_AVAILABLE = 'is_available';
        const STATUS_PUBLIC = 'is_public';
        const STATUS_ANY = 'any';
        // This is a special constant for my friend Juan!
        const STATUS_ANY_BUT_AUTODRAFT = 'any_but_autodraft';
    }
    /**
     * Handles the persistence of intermediary posts.
     *
     * @since 4.0
     */
    interface IntermediaryPostPersistence
    {
        /**
         * Create an intermediary post for a new association.
         *
         * @param int $parent_id Association parent id.
         * @param int $child_id Association child id.
         *
         * @return int|null ID of the new post or null if the post creation failed.
         */
        public function create_intermediary_post($parent_id, $child_id);
        /**
         * It there are associations belonging to the definition, intermediary post without field values has to be created.
         *
         * @param int $limit The number of associations in a loop.
         */
        public function create_empty_associations_intermediary_posts($limit = 0);
        /**
         * Removes intermediary post from associations.
         *
         * @param int $limit The number of associations in a loop.
         *
         * @return int Number of associations updated.
         */
        public function remove_associations_intermediary_posts($limit = 0);
        /**
         * Creates an empty association intermediary post
         *
         * @param \IToolset_Association $association Association.
         *
         * @return int Post ID
         */
        public function create_empty_association_intermediary_post($association);
        /**
         * Delete the intermediary post if it exists and it's not disabled by a filter.
         *
         * This also deletes all its translations.
         *
         * @param \IToolset_Association $association
         */
        public function maybe_delete_intermediary_post(\IToolset_Association $association);
        /**
         * Delete the intermediary post if it's not disabled by a filter.
         *
         * This also deletes all its translations.
         *
         * @param int $post_id
         */
        public function delete_intermediary_post($post_id);
    }
    /**
     * Association query class with a more OOP/functional approach.
     *
     * Allows for chaining query conditions and avoiding passing query arguments as associative arrays.
     * It makes it also possible to build queries with nested AND & OR statements in an arbitrary way.
     * The object model may be complex but all the complexity is hidden from the user, they need to know
     * only the methods on this class.
     *
     * Example usage:
     *
     * $query = $factory->association_query()
     *
     * $results = $query
     *     ->add(
     *         $query->has_domain( 'posts', new Toolset_Relationship_Role_Parent() )
     *     )
     *     ->add(
     *         $query->do_or(
     *             $query->has_type( 'attachment', new Toolset_Relationship_Role_Parent() ),
     *             $query->do_and(
     *                 $query->has_type( 'page', new Toolset_Relationship_Role_Child() ),
     *                 $query->has_type( 'post', new Toolset_Relationship_Role_Child() ),
     *             )
     *         )
     *     )
     *     ->add(
     *         $query->search( 'some string', new Toolset_Relationship_Role_Parent() )
     *     )
     *     ->order_by_field_value( $custom_field_definition )
     *     ->order( 'DESC' )
     *     ->limit( 50 )
     *     ->offset( 100 )
     *     ->return_association_instances()
     *     ->get_results();
     *
     * Note about default conditions:
     * - If no element status (element_status() or has_available_elements()) condition is used when constructing the query,
     *   has_available_elements() is used.
     * - If no has_active_relationship() condition is used when constructing the query, has_active_relationship(true)
     *   is used.
     * - This mechanism doesn't recognize where, how and if these conditions are actually applied, so even
     *   $query->do_if( false, $query->has_active_relationship( true ) ) will disable the default
     *   has_active_relationship() condition.
     * - You can prevent the adding of default conditions by $query->do_not_add_default_conditions().
     *
     * @since 4.0
     */
    interface AssociationQuery
    {
        /**
         * Add another condition to the query.
         *
         * @param AssociationQueryCondition $condition
         *
         * @return $this
         */
        public function add(\OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition $condition);
        /**
         * Prevent the query from adding any default conditions. WYSIWYG.
         *
         * @return $this
         */
        public function do_not_add_default_conditions();
        /**
         * Apply stored conditions and perform the query.
         *
         * @return \IToolset_Association[]|int[]|\IToolset_Element[]
         */
        public function get_results();
        /**
         * Chain multiple conditions with OR.
         *
         * The whole statement will evaluate to true if at least one of provided conditions is true.
         *
         * @param AssociationQueryCondition[] $conditions
         *
         * @return AssociationQueryCondition
         */
        public function do_or(...$conditions);
        /**
         * Chain multiple conditions with AND.
         *
         * The whole statement will evaluate to true if all provided conditions are true.
         *
         * @param AssociationQueryCondition[] $conditions
         *
         * @return AssociationQueryCondition
         */
        public function do_and(...$conditions);
        /**
         * Choose a query condition depending on a boolean expression.
         *
         * @param bool $statement A boolean condition statement.
         * @param AssociationQueryCondition $if_branch Query condition that will be used
         *     if the statement is true.
         * @param AssociationQueryCondition|null $else_branch Query condition that will be
         *     used if the statement is false. If none is provided, a tautology is used (always true).
         *
         * @return AssociationQueryCondition
         * @since 2.5.6
         */
        public function do_if($statement, \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition $if_branch, \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition $else_branch = null);
        public function not(\OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition $condition);
        /**
         * Query by a row ID of a relationship definition.
         *
         * @param int $relationship_id
         *
         * @return AssociationQueryCondition
         */
        public function relationship_id($relationship_id);
        /**
         * Query by a row intermediary_id of a relationship definition.
         *
         * @param int $relationship_id
         *
         * @return AssociationQueryCondition
         */
        public function intermediary_id($relationship_id);
        /**
         * Query by a relationship definition.
         *
         * @param \IToolset_Relationship_Definition $relationship_definition
         *
         * @return AssociationQueryCondition
         */
        public function relationship(\IToolset_Relationship_Definition $relationship_definition);
        /**
         * Query by a relationship definition slug.
         *
         * @param string $slug
         *
         * @return AssociationQueryCondition
         */
        public function relationship_slug($slug);
        /**
         * Query by an ID of an element in the selected role.
         *
         * Warning: This is an WPML-unaware query.
         *
         * @param int $element_id
         * @param RelationshipRole $for_role
         * @param bool $need_wpml_unaware_query Set this to true to avoid a _doing_it_wrong notice.
         *
         * @return AssociationQueryCondition
         */
        public function element_id($element_id, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $need_wpml_unaware_query = true);
        /**
         * Query by an ID of an element in the selected role.
         *
         * @param int $element_id
         * @param string $domain
         * @param RelationshipRole $for_role
         * @param bool $query_original_element If true, the query will check the element ID in the original language
         *     as stored in the association table. Default is false.
         * @param bool $translate_provided_id If true, this will try to translate the element ID (if
         *     applicable on the domain) and use the translated one in the final condition. Default is true.
         * @param bool $set_its_translation_language If true, the query may try to use the element's language
         *     to determine the desired language of the results (see determine_translation_language() for details)
         * @param null|string $element_identification_to_query_by Available only since the second database layer version.
         *     Must be one of the ElementIdentification values or null, in which case $query_original_element will be used.
         *     If this is not null, $query_original_element is ignored.
         *
         * @return AssociationQueryCondition
         * @since 2.5.10
         */
        public function element_id_and_domain($element_id, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $query_original_element = false, $translate_provided_id = true, $set_its_translation_language = true, $element_identification_to_query_by = null);
        /**
         * Query by an element TRID if possible, otherwise fall back to querying by element ID and domain.
         *
         * See element_id_and_domain() for further details.
         *
         * @param int $trid Element TRID or 0 if it isn't set. Passing a non-zero value for a translatable relationship role
         *     will filter results by this TRID, in any other case, filtering as in element_id_and_domain() will be used.
         * @param int $element_id
         * @param string $domain
         * @param RelationshipRole $for_role
         * @param bool $translate_provided_id
         * @param bool $set_its_translation_language
         * @param string $element_identification_to_query_by
         *
         * @return AssociationQueryCondition
         */
        public function element_trid_or_id_and_domain($trid, $element_id, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $translate_provided_id = true, $set_its_translation_language = true, $element_identification_to_query_by = \OTGS\Toolset\Common\Relationships\API\ElementIdentification::CURRENT_LANGUAGE_IF_POSSIBLE);
        /**
         * Query by a set of element IDs in the selected role.
         *
         * @param int[] $element_ids
         * @param string $domain
         * @param RelationshipRole $for_role
         * @param bool $query_original_element If true, the query will check the element ID in the original language
         *     as stored in the association table. Default is false.
         * @param bool $translate_provided_ids If true, this will try to translate the element ID (if
         *     applicable on the domain) and use the translated one in the final condition. Default is true.
         *
         * @return AssociationQueryCondition
         * @since 3.0.3
         */
        public function multiple_elements($element_ids, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $query_original_element = false, $translate_provided_ids = true);
        /**
         * Query by an element in the selected role.
         *
         * @param \IToolset_Element $element
         * @param RelationshipRole|null $for_role If null is provided, the query will involve all roles.
         * @param bool $query_original_element If true, the query will check the element ID in the original language
         *     as stored in the association table. Default is false.
         * @param bool $translate_provided_id If true, this will try to translate the element ID (if
         *     applicable on the domain) and use the translated one in the final condition. Default is true.
         * @param bool $set_its_translation_language If true, the query may try to use the element's language
         *     to determine the desired language of the results (see determine_translation_language() for details)
         *
         * @return AssociationQueryCondition
         */
        public function element(\IToolset_Element $element, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role = null, $query_original_element = false, $translate_provided_id = true, $set_its_translation_language = true);
        /**
         * Exclude associations with a particular element in the selected role.
         *
         * @param \IToolset_Element $element
         * @param RelationshipRole $for_role
         * @param bool $query_original_element If true, the query will check the element ID in the original language
         *     as stored in the association table. Default is false.
         * @param bool $translate_provided_id If true, this will try to translate the element ID (if
         *     applicable on the domain) and use the translated one in the final condition. Default is true.
         *
         * @return AssociationQueryCondition
         */
        public function exclude_element(\IToolset_Element $element, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $query_original_element = false, $translate_provided_id = true);
        /**
         * Query by a parent element.
         *
         * @param \IToolset_Element $element_source
         *
         * @return AssociationQueryCondition
         */
        public function parent(\IToolset_Element $element_source);
        /**
         * Query by a parent element ID.
         *
         * @param int $parent_id
         * @param string $domain
         *
         * @return AssociationQueryCondition
         */
        public function parent_id($parent_id, $domain = \Toolset_Element_Domain::POSTS);
        /**
         * Query by a child element.
         *
         * @param \IToolset_Element $element
         *
         * @return AssociationQueryCondition
         */
        public function child(\IToolset_Element $element);
        /**
         * Query by a child element ID.
         *
         * @param int $child_id
         * @param string $domain
         *
         * @return AssociationQueryCondition
         */
        public function child_id($child_id, $domain = \Toolset_Element_Domain::POSTS);
        /**
         * Query by an element status.
         *
         * @param string|string[] $statuses Value from ElementStatusCondition or one or more specific status values in an
         *     array. Meaning of these options is domain-dependant.
         * @param RelationshipRole|null $for_role
         *
         * @return AssociationQueryCondition
         */
        public function element_status($statuses, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role = null);
        /**
         * Query only associations that have both elements available (see element_status()).
         *
         * @return AssociationQueryCondition
         */
        public function has_available_elements();
        /**
         * Query associations by the activity status of the relationship.
         *
         * @param bool $is_active
         *
         * @return AssociationQueryCondition
         */
        public function has_active_relationship($is_active = true);
        /**
         * Query associations by the fact whether the relationship was migrated from the legacy implementation.
         *
         * @param bool $needs_legacy_support
         *
         * @return AssociationQueryCondition
         */
        public function has_legacy_relationship($needs_legacy_support = true);
        /**
         * Query associations by the element domain on a specified role.
         *
         * @param string $domain
         * @param RelationshipRoleParentChild $for_role
         *
         * @return AssociationQueryCondition
         */
        public function has_domain($domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role);
        /**
         * Query associations based on element type.
         *
         * Warning: This doesn't query for the domain. Make sure you at least add
         * a separate element domain condition. Otherwise, the results will be unpredictable.
         *
         * The best way is to use the has_domain_and_type() condition instead, which whill allow
         * for some more advanced optimizations.
         *
         * @param string $type Element type.
         * @param RelationshipRoleParentChild $for_role
         *
         * @return AssociationQueryCondition
         */
        public function has_type($type, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role);
        /**
         * Query associations based on element domain and type.
         *
         * @param string $domain Element domain.
         * @param string $type Element type
         * @param RelationshipRoleParentChild $for_role
         *
         * @return AssociationQueryCondition
         */
        public function has_domain_and_type($domain, $type, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role);
        /**
         * Condition that a relationship has a certain origin.
         *
         * @param String $origin Origin.
         *
         * @return AssociationQueryCondition
         */
        public function has_origin($origin);
        /**
         * Condition that the association has an intermediary id.
         *
         * @return AssociationQueryCondition
         */
        public function has_intermediary_id();
        /**
         * Query by a WP_Query arguments applied on an element of a specified role.
         *
         * WARNING: It is important that you read the documentation of OTGS\Toolset\Common\Relationships\DatabaseLayer
         * \Version1\Toolset_Association_Query_Condition_Wp_Query before using this.
         *
         * This may not be implemented in all versions of the database layer.
         *
         * @param RelationshipRole $for_role
         * @param array $query_args
         * @param string|null $confirmation 'i_know_what_i_am_doing'
         *
         * @return AssociationQueryCondition
         *
         * @throws \InvalidArgumentException Thrown if you don't know what you are doing.
         * @throws \RuntimeException Thrown when the query condition is not available.
         */
        public function wp_query(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $query_args, $confirmation = null);
        /**
         * Query by a string search in elements of a selected role.
         *
         * Note that the behaviour may be different per domain.
         *
         * @param string $search_string
         * @param RelationshipRole $for_role
         * @param bool $is_exact
         *
         * @return AssociationQueryCondition
         */
        public function search($search_string, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $is_exact = false);
        /**
         * Query by a specific association ID.
         *
         * This will also set the limit of the result count to one.
         *
         * @param int $association_id
         *
         * @return AssociationQueryCondition
         */
        public function association_id($association_id);
        public function meta($meta_key, $meta_value, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role = null, $comparison = \Toolset_Query_Comparison_Operator::EQUALS);
        /**
         * Query associations by the fact whether they have an intermediary post that can be automatically deleted
         * together with the association (which is a setting of the relationship definition).
         *
         * @param bool $expected_value Value of the condition.
         *
         * @return AssociationQueryCondition
         */
        public function has_autodeletable_intermediary_post($expected_value = true);
        /**
         * Indicate that get_results() should return instances of IToolset_Association.
         *
         * @return $this
         */
        public function return_association_instances();
        /**
         * Indicate that get_results() should return UIDs of associations.
         *
         * @return $this
         */
        public function return_association_uids();
        /**
         * Indicate that get_results() should return element IDs from a selected role.
         *
         * @param RelationshipRole $role
         *
         * @return $this
         */
        public function return_element_ids(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role);
        /**
         * Indicate that get_results() should return IToolset_Element instances from a selected role.
         *
         * @param RelationshipRole $role
         *
         * @return $this
         */
        public function return_element_instances(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role);
        /**
         * Indicate that get_results() should return arrays with elements indexed by their role names.
         *
         * This needs further configuration, see OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Result_Transformation_Element_Per_Role for
         * further details.
         *
         * @return \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Result_Transformation_Element_Per_Role
         * @since 3.0.9
         */
        public function return_per_role();
        /**
         * Set an offset for the query.
         *
         * @param int $value
         *
         * @return $this
         * @throws \InvalidArgumentException Thrown if an invalid value is provided.
         */
        public function offset($value);
        /**
         * Limit a number of results for the query.
         *
         * Note that by default, the limit is set at a certain value, and the query can never be unlimited.
         *
         * @param int $value
         *
         * @return $this
         * @throws \InvalidArgumentException Thrown if an invalid value is provided.
         */
        public function limit($value);
        /**
         * Set the sorting order.
         *
         * @param string $value 'ASC'|'DESC'
         *
         * @return $this
         */
        public function order($value);
        /**
         * Indicate whether the query should also retrieve the total number of results.
         *
         * This is required for get_found_rows() to work.
         *
         * @param bool $is_needed
         *
         * @return $this
         */
        public function need_found_rows($is_needed = true);
        /**
         * Return the total number of found results after get_results() was called.
         *
         * For this to work, need_found_rows() needs to be called when building the query.
         *
         * @return int
         * @throws \RuntimeException
         */
        public function get_found_rows();
        /**
         * Indicate that no result ordering is needed.
         *
         * @return $this
         */
        public function dont_order();
        /**
         * Order results by a title of element of given role.
         *
         * Note that ordering by intermediary posts will cause the associations without those to be excluded from results.
         *
         * @param RelationshipRole $for_role
         *
         * @return $this
         */
        public function order_by_title(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role);
        /**
         * Order results by a value of a certain custom field on a selected element role.
         *
         * @param \Toolset_Field_Definition $field_definition
         * @param RelationshipRole $for_role
         *
         * @return $this
         * @throws \RuntimeException Thrown if the element domain is not supported.
         */
        public function order_by_field_value(\Toolset_Field_Definition $field_definition, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role);
        /**
         * Order results by a value of the element metadata.
         *
         * @param string $meta_key Meta key that should be used for ordering.
         * @param string $domain Valid element domain. At the moment, only posts are supported.
         * @param RelationshipRole $for_role Role of the element whose metadata should be used for ordering.
         * @param bool $is_numeric If true, numeric ordering will be used.
         *
         * @return $this
         * @throws \RuntimeException If unsupported element domain is used.
         * @throws \InvalidArgumentException
         * @since 2.6.1
         */
        public function order_by_meta($meta_key, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $is_numeric = false);
        /**
         * Make sure that the elements in results will never get translated.
         *
         * @return $this
         * @since 2.6.4
         */
        public function dont_translate_results();
        /**
         * Set the preferred translation language.
         *
         * See determine_translation_language() for details.
         *
         * @param string $lang_code Valid language code.
         *
         * @return $this
         */
        public function set_translation_language($lang_code);
        /**
         * Allow forcing a particular language for a given role.
         *
         * That means, only associations with translated posts will be used, and those without translations
         * will be skipped from the results. Use with great caution.
         *
         * @deprecated Do not use, it may not be implemented in all database layer versions.
         *
         * @param RelationshipRole $role
         * @param string $lang_code Default language, current language or '*'.
         */
        public function force_language_per_role(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role, $lang_code);
        /**
         * Set the preferred translation language from a given element ID and domain.
         *
         * See determine_translation_language() for details.
         *
         * @param int $element_id ID of the element to take the language from.
         * @param string $domain Element domain.
         *
         * @return $this
         * @since 2.6.8
         */
        public function set_translation_language_by_element_id_and_domain($element_id, $domain);
        /**
         * Perform the query to only return the number of found rows, if we're not interested in
         * the actual results.
         *
         * @return int Number of results matching the query.
         */
        public function get_found_rows_directly();
        public function use_cache($use_cache = true);
        public function build_cache_key($query_string);
        /**
         * For translatable element roles, include the original language element ID, if it exists.
         *
         * Note that this is implemented only in the second version of the database layer (and above).
         *
         * @param bool $include
         * @return $this
         * @throws \OTGS\Toolset\Common\Exception\NotImplementedException
         */
        public function include_original_language($include = true);
        /**
         * Treat all translatable post types as "display as translated" regardless of their actual translation mode.
         *
         * Non-translatable post types won't be affected. This is useful for querying in the admin where we might
         * want to fall back to the default language if it exists despite of the post type settings.
         *
         * Note: This does nothing in the first version of the database layer, since only post types in the "display as
         * translated" modes are supported there.
         *
         * @param bool $do_force
         * @return $this
         * @since 4.0
         */
        public function force_display_as_translated_mode($do_force = true);
    }
    /**
     * Represents a class for performing database operations related to associations between elements.
     *
     * @since 4.0
     */
    interface AssociationDatabaseOperations
    {
        /**
         * Create new association and persist it.
         *
         * @param \IToolset_Relationship_Definition|string $relationship_definition_source Can also contain slug of
         *     existing relationship definition.
         * @param int|\Toolset_Element|\WP_Post $parent_source
         * @param int|\Toolset_Element|\WP_Post $child_source
         * @param int $intermediary_id
         * @param bool $instantiate Whether to create an instance of the newly created association
         *     or only return a result on success
         *
         * @return \IToolset_Association|\Toolset_Result
         * @throws \Toolset_Element_Exception_Element_Doesnt_Exist
         */
        public function create_association($relationship_definition_source, $parent_source, $child_source, $intermediary_id, $instantiate = true);
        /**
         * Delete all associations of a given relationships that have the given element in the given role.
         *
         * @param \IToolset_Relationship_Definition $relationship
         * @param string $element_role_name
         * @param int $element_id
         */
        public function delete_associations_by_element($relationship, $element_role_name, $element_id);
        public function delete_association_by_element_in_any_role(\IToolset_Element $element);
        /**
         * Delete all associations from a given relationship.
         *
         * @param int $relationship_row_id
         *
         * @return \Toolset_Result_Updated
         */
        public function delete_associations_by_relationship($relationship_row_id);
        /**
         * @param \IToolset_Association $association
         *
         * @return \Toolset_Result
         */
        public function delete_association(\IToolset_Association $association);
        /**
         * Delete intermediary posts from all associations in a given relationship that have
         * the given element in the given role.
         *
         * @param \IToolset_Relationship_Definition $relationship
         * @param string $element_role_name
         * @param int $element_id
         */
        public function delete_intermediary_posts_by_element($relationship, $element_role_name, $element_id);
        /**
         * When a relationship definition slug is renamed, update the association table (where the slug is used as a
         * foreign key).
         *
         * @param \IToolset_Relationship_Definition $old_definition
         * @param \IToolset_Relationship_Definition $new_definition
         *
         * @return \Toolset_Result
         * @deprecated Always change the slug via Toolset_Relationship_Definition_Repository::change_definition_slug().
         */
        public function update_associations_on_definition_renaming(\IToolset_Relationship_Definition $old_definition, \IToolset_Relationship_Definition $new_definition);
        /**
         * Updates association intermediary post
         *
         * @param int $association_id Association trID
         * @param int $intermediary_id New intermediary ID
         */
        public function update_association_intermediary_id($association_id, $intermediary_id);
        /**
         * Returns the maximun number of associations of a relationship for a parent id and a child id
         *
         * @param int $relationship_id Relationship ID.
         * @param string $role_name Role name.
         *
         * @return int
         * @throws \InvalidArgumentException In case of error.
         */
        public function count_max_associations($relationship_id, $role_name);
        /**
         * @param array $intermediary_post_types
         * @param array $post_types_to_delete_by
         *
         * @return array
         */
        public function get_dangling_intermediary_posts(array $intermediary_post_types, array $post_types_to_delete_by);
        /**
         * Determines whether the relationship database layer needs a default language post version to connect
         * translatable posts.
         *
         * @return bool
         */
        public function requires_default_language_post();
    }
    /**
     * When you have a relationship and a specific element in one role, this
     * query will help you to find elements that can be associated with it.
     *
     * It takes into account all the aspects, like whether the relationship is distinct or not.
     *
     * Important terminology for the potential association query codebase:
     *
     * - $for_element: The element for which we're searching posts that can be connected to it.
     * - $target_role: Role on the opposite side of $for_element in the given relationship.
     *
     * @since 4.0
     */
    interface PotentialAssociationQuery
    {
        /**
         * @param bool $check_can_connect_another_element Check wheter it is possible to connect any other element at all,
         *     and return an empty result if not.
         * @param bool $check_distinct_relationships Exclude elements that would break the "distinct" property of a
         *     relationship. You can set this to false if you're overwriting an existing association.
         *
         * @return \IToolset_Element[]
         */
        public function get_results($check_can_connect_another_element = true, $check_distinct_relationships = true);
        /**
         * Returns the number of found elements _after_ the query has been performed (via get_results()).
         *
         * @return int
         */
        public function get_found_elements();
        /**
         * Check whether a specific single element can be associated.
         *
         * The relationship, target role and the other element are those provided in the constructor.
         *
         * @param \IToolset_Element $association_candidate Element that wants to be associated.
         * @param bool $check_is_already_associated Perform the check that the element is already associated for distinct
         *     relationships. Default is true. Set to false only if the check was performed manually before.
         *
         * @return \Toolset_Result Result with an user-friendly message in case the association is denied.
         */
        public function check_single_element(\IToolset_Element $association_candidate, $check_is_already_associated = true);
        /**
         * Check whether the element provided in the constructor can accept any new association whatsoever.
         *
         * @return \Toolset_Result Result with an user-friendly message in case the association is denied.
         */
        public function can_connect_another_element();
        /**
         * Check whether there already exists an association between the the target element and the provided one.
         *
         * Note that it doesn't always have to be a problem, it depends on whether the relationship is distinct or not.
         * This was made public to optimize performance during the m2m migration process.
         *
         * @param \IToolset_Element $element
         *
         * @return bool
         */
        public function is_element_already_associated(\IToolset_Element $element);
    }
}
namespace {
    /**
     * Various and constants for the Toolset relationships functionality.
     *
     * @deprecated This should be replaced by the relationship query or repository class.
     */
    class Toolset_Relationship_Utils
    {
        /**
         * @param string|Toolset_Relationship_Definition $relationship_definition_source
         *
         * @return null|IToolset_Relationship_Definition
         */
        public static function get_relationship_definition($relationship_definition_source)
        {
        }
        /**
         * This method returns all relationships, which includes at least one translated post type
         *
         * @param Toolset_Relationship_Definition_Repository|null $relationship_definition_repository
         *
         * @return IToolset_Relationship_Definition[]
         */
        public static function get_all_translated_relationships(\Toolset_Relationship_Definition_Repository $relationship_definition_repository = \null)
        {
        }
    }
    /**
     * Factory of RelationshipQueryCondition classes.
     *
     * @since 2.5.4
     */
    class Toolset_Relationship_Query_Condition_Factory
    {
        /**
         * Chain multiple conditions with OR.
         *
         * The whole statement will evaluate to true if at least one of provided conditions is true.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition[] $operands
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition
         */
        public function do_or($operands)
        {
        }
        /**
         * Chain multiple conditions with AN.
         *
         * The whole statement will evaluate to true if all provided conditions are true.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition[] $operands
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition
         */
        public function do_and($operands)
        {
        }
        /**
         * Condition that the relationship involves a certain domain.
         *
         * @param string $domain_name 'posts'|'users'|'terms'
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $in_role
         *
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition
         */
        public function has_domain($domain_name, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $in_role)
        {
        }
        /**
         * Condition that the relationship comes from a certain source
         *
         * @param string $origin
         *
         * @return Toolset_Relationship_Query_Condition_Origin
         */
        public function origin($origin)
        {
        }
        /**
         * Condition that the relationship uses a given intermediary post type
         *
         * @param string $intermediary_type
         *
         * @return IToolset_Relationship_Query_Condition
         *
         * @since 2.6.7
         */
        public function intermediary_type($intermediary_type)
        {
        }
        /**
         * Condition that the relationship has a certain type in a given role.
         *
         * @param string $type
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $in_role
         *
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition
         */
        public function has_type($type, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $in_role)
        {
        }
        /**
         * Condition that the relationship has not a certain type in a given role.
         *
         * @param string $type
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $in_role
         *
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition
         */
        public function exclude_type($type, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $in_role)
        {
        }
        /**
         * Condition that the relationship was migrated from the legacy implementation.
         *
         * @param bool $should_be_legacy
         *
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition
         */
        public function is_legacy($should_be_legacy = \true)
        {
        }
        /**
         * Condition that the relationship is active.
         *
         * @param bool $should_be_active
         *
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition
         */
        public function is_active($should_be_active = \true)
        {
        }
        /**
         * Condition that the relationship has at least one active post type in a given role (or another domain than posts).
         *
         * @param bool $has_active_post_types
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $in_role
         *
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition
         */
        public function has_active_post_types($has_active_post_types, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $in_role)
        {
        }
        /**
         * Condition that a relationship cardinality matches certain constraints.
         *
         * @param IToolset_Relationship_Query_Cardinality_Match $cardinality_match
         *
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition
         */
        public function has_cardinality(\IToolset_Relationship_Query_Cardinality_Match $cardinality_match)
        {
        }
        /**
         * A condition that is always true.
         *
         * @since 2.5.6
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition
         */
        public function tautology()
        {
        }
        /**
         * A condition that is always false.
         *
         * @since Types 3.3.11
         * @return IToolset_Relationship_Query_Condition
         */
        public function contradiction()
        {
        }
        /**
         * Condition that excludes a relationship.
         *
         * @param IToolset_Relationship_Definition $relationship Relationship Definition.
         *
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition
         */
        public function exclude_relationship($relationship)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\RelationshipQuery {
    /**
     * Relationship query class with a more OOP/functional approach.
     *
     * Replaces Toolset_Relationship_Query.
     *
     * Allows for chaining query conditions and avoiding passing query arguments as associative arrays.
     * It makes it also possible to build queries with nested AND & OR statements in an arbitrary way.
     * The object model may be complex but all the complexity is hidden from the user, they need to know
     * only the methods on this class.
     *
     * Example usage:
     *
     * $query = new Toolset_Relationship_Query_V2();
     *
     * $results = $query
     *     ->add(
     *         $query->has_domain( 'posts' )
     *     )
     *     ->add(
     *         $query->do_or(
     *             $query->has_type( 'attachment', new Toolset_Relationship_Role_Parent() ),
     *             $query->do_and(
     *                 $query->has_type( 'page', new Toolset_Relationship_Role_Parent() ),
     *                 $query->is_legacy( false )
     *             )
     *         )
     *     )
     *     ->add( $query->is_active( '*' ) )
     *     ->get_results();
     *
     * Note:
     * - If no is_active() condition is used when constructing the query, is_active(true) is used. To get both
     *     active and non-active relationship definitions, you need to manually add is_active('*').
     * - If no has_active_post_types() condition is used when constructing the query, has_active_post_types(true)
     *     is used for both parent and child role.
     * - If no origin() condition is used, origin( 'wizard' ) is added by default.
     * - This mechanism doesn't recognize where, how and if these conditions are actually applied, so even
     *     $query->do_if( false, $query->is_active( true ) ) will disable the default is_active() condition.
     *
     * @since m2m
     */
    class RelationshipQuery implements \OTGS\Toolset\Common\Relationships\API\RelationshipQuery
    {
        /**
         * Toolset_Relationship_Query_V2 constructor.
         *
         * @param \wpdb|null $wpdb_di
         * @param \Toolset_Relationship_Definition_Translator|null $definition_translator_di
         * @param \Toolset_Relationship_Query_Sql_Expression_Builder|null $expression_builder_di
         * @param \Toolset_Relationship_Query_Condition_Factory|null $condition_factory_di
         * @param \Toolset_Relationship_Query_Cardinality_Match_Factory|null $cardinality_match_factory_di
         */
        public function __construct(\wpdb $wpdb_di = null, \Toolset_Relationship_Definition_Translator $definition_translator_di = null, \Toolset_Relationship_Query_Sql_Expression_Builder $expression_builder_di = null, \Toolset_Relationship_Query_Condition_Factory $condition_factory_di = null, \Toolset_Relationship_Query_Cardinality_Match_Factory $cardinality_match_factory_di = null, \OTGS\Toolset\Common\Relationships\DatabaseLayer\RelationshipQuery\RelationshipQueryCache $query_cache_di = null)
        {
        }
        /**
         * Add another condition to the query.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition $condition
         *
         * @return $this
         */
        public function add(\OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition $condition)
        {
        }
        /**
         * @return $this
         */
        public function do_not_add_default_conditions()
        {
        }
        /**
         * Apply stored conditions and perform the query.
         *
         * Todo: Add the results to the relationship repository.
         *
         * @return \IToolset_Relationship_Definition[]
         */
        public function get_results()
        {
        }
        /**
         * Chain multiple conditions with OR.
         *
         * The whole statement will evaluate to true if at least one of provided conditions is true.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition[] $conditions
         *
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition
         */
        public function do_or(...$conditions)
        {
        }
        /**
         * Chain multiple conditions with AND.
         *
         * The whole statement will evaluate to true if all provided conditions are true.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition[] $conditions
         *
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition
         */
        public function do_and(...$conditions)
        {
        }
        /**
         * Condition that the relationship involves a certain domain.
         *
         * @param string $domain_name One of the Toolset_Element_Domain values.
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole|null $in_role If null is provided, the type
         *    can be in both parent or child role for the condition to be true.
         *
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition
         */
        public function has_domain($domain_name, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $in_role = null)
        {
        }
        /**
         * Condition that the relationship comes from a certain source
         *
         * @param string|null $origin One of the keywords from IToolset_Relationship_Origin or null to include
         *     relationships with all origins.
         *
         * @return \Toolset_Relationship_Query_Condition_Origin
         */
        public function origin($origin)
        {
        }
        /**
         * Condition that the relationship includes a certain intermediary object.
         *
         * @param string $intermediary_type An intermediary object slug.
         *
         * @return \IToolset_Relationship_Query_Condition
         *
         * @since 2.6.7
         */
        public function intermediary_type($intermediary_type)
        {
        }
        /**
         * Condition that the relationship has a certain type in a given role.
         *
         * @param string $type
         * @param \IToolset_Relationship_Role_Parent_Child|null $in_role If null is provided, the type
         *    can be in both parent or child role for the condition to be true.
         *
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition
         */
        public function has_type($type, $in_role = null)
        {
        }
        /**
         * Condition that the relationship has a certain type in a given role.
         *
         * @param string $type
         * @param \IToolset_Relationship_Role_Parent_Child|null $in_role If null is provided, the type
         *    can be in both parent or child role for the condition to be true.
         *
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition
         */
        public function exclude_type($type, $in_role = null)
        {
        }
        /**
         * Condition that the relationship has a certain type and a domain in a given role.
         *
         * @param string $type
         * @param string $domain One of the Toolset_Element_Domain values.
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole|null $in_role If null is provided, the type
         *    can be in both parent or child role for the condition to be true.
         *
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition
         * @since 2.5.6
         */
        public function has_domain_and_type($type, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $in_role = null)
        {
        }
        /**
         * Condition that the relationship was migrated from the legacy implementation.
         *
         * @param bool $should_be_legacy
         *
         * @return \IToolset_Relationship_Query_Condition
         */
        public function is_legacy($should_be_legacy = true)
        {
        }
        /**
         * Condition that the relationship is active.
         *
         * @param bool $should_be_active
         *
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition
         */
        public function is_active($should_be_active = true)
        {
        }
        /**
         * Condition that the relationship has at least one active post type in a given role (or another domain than posts).
         *
         * @param bool $has_active_post_types
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild|null $in_role
         *
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition
         */
        public function has_active_post_types($has_active_post_types = true, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $in_role = null)
        {
        }
        /**
         * Get a factory of cardinality constrains, which can be used as an argument for $this->has_cardinality().
         *
         * @return \Toolset_Relationship_Query_Cardinality_Match_Factory
         */
        public function cardinality()
        {
        }
        /**
         * Condition that a relationship has a certain cardinality.
         *
         * Use methods on $this->cardinality() to obtain a valid argument for this method.
         *
         * @param \IToolset_Relationship_Query_Cardinality_Match $cardinality_match Object
         *     that holds cardinality constraints.
         *
         * @return \IToolset_Relationship_Query_Condition
         */
        public function has_cardinality(\IToolset_Relationship_Query_Cardinality_Match $cardinality_match)
        {
        }
        /**
         * Choose a query condition depending on a boolean expression.
         *
         * @param bool $statement A boolean condition statement.
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition $if_branch Query condition that will be used
         *     if the statement is true.
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition|null $else_branch Query condition that will be
         *     used if the statement is false. If none is provided, a tautology is used (always true).
         *
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition
         * @since 2.5.6
         */
        public function do_if($statement, \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition $if_branch, \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition $else_branch = null)
        {
        }
        /**
         * Indicate that the query should also determine the total number of found rows.
         *
         * This has to be set to true if you plan using get_found_rows().
         *
         * @param bool $is_needed
         *
         * @return RelationshipQuery
         * @since 2.5.8
         */
        public function need_found_rows($is_needed = true)
        {
        }
        /**
         * Return a number of found rows.
         *
         * This can be called only after get_results() if need_found_rows() was set to true
         * while building the query. Otherwise, an exception will be thrown.
         *
         * @return int
         * @throws \RuntimeException
         * @since 2.5.8
         */
        public function get_found_rows()
        {
        }
        /**
         * @inheritDoc
         * @since 4.0
         */
        public function get_found_rows_directly()
        {
        }
        /**
         * Condition that excludes a relationship.
         *
         * @param \IToolset_Relationship_Definition $relationship Relationship Definition.
         *
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition
         */
        public function exclude_relationship($relationship)
        {
        }
        /**
         * @inheritDoc
         * @since Types 3.4.7
         */
        public function use_cache($use_cache = true)
        {
        }
    }
}
namespace {
    /**
     * An empty interface just to recognize cardinality matchers accepted by
     * Toolset_Relationship_Query_Condition_Has_Cardinality.
     *
     * @since 2.5.5
     */
    interface IToolset_Relationship_Query_Cardinality_Match
    {
    }
    /**
     * Cardinality matcher that holds a set of single matchers.
     *
     * @since 2.5.5
     */
    class Toolset_Relationship_Query_Cardinality_Match_Conjunction implements \IToolset_Relationship_Query_Cardinality_Match
    {
        public function __construct($matchers)
        {
        }
        public function get_matchers()
        {
        }
    }
    /**
     * Factory for building cardinality matchers, especially for the most common cases.
     *
     * Do not use this directly outside of the m2m API, but go through
     * Toolset_Relationship_Query_V2::cardinality().
     *
     * Hide away the complexity involving cardinalities and comparing, especially if there are custom limits.
     *
     * @since 2.5.5
     */
    class Toolset_Relationship_Query_Cardinality_Match_Factory
    {
        /**
         * Matches all one-to-many relationships.
         *
         * @return Toolset_Relationship_Query_Cardinality_Match_Conjunction
         */
        public function one_to_many()
        {
        }
        /**
         * Matches all many-to-one relationships.
         *
         * @return Toolset_Relationship_Query_Cardinality_Match_Conjunction
         */
        public function many_to_one()
        {
        }
        /**
         * Matches all one-to-one relationships.
         *
         * @return Toolset_Relationship_Query_Cardinality_Match_Conjunction
         */
        public function one_to_one()
        {
        }
        /**
         * Matches all one-to-one and one-to-many relationships.
         *
         * @return Toolset_Relationship_Query_Cardinality_Match_Single
         */
        public function one_to_something()
        {
        }
        /**
         * Matches all many-to-many relationships.
         *
         * @return Toolset_Relationship_Query_Cardinality_Match_Conjunction
         */
        public function many_to_many()
        {
        }
        /**
         * Matches all relationships with the exact cardinality.
         *
         * Keep in mind the implications for relationships with custom limits.
         * Always prefer another method if you can.
         *
         * @param Toolset_Relationship_Cardinality $cardinality
         *
         * @return Toolset_Relationship_Query_Cardinality_Match_Conjunction
         */
        public function by_cardinality(\Toolset_Relationship_Cardinality $cardinality)
        {
        }
    }
    /**
     * Enum class with accepted operators for Toolset_Relationship_Query_Cardinality_Match_Single.
     *
     * @since 2.5.5
     */
    class Toolset_Relationship_Query_Cardinality_Match_Operators
    {
        // these must be valid MySQL operators
        const EQUAL = '=';
        const LOWER_THAN = '<';
        const LOWER_OR_EQUAL = '<=';
        const HIGHER_THAN = '>';
        const HIGHER_OR_EQUAL = '>=';
        const NOT_EQUAL = '!=';
        /**
         * @return string[] All valid operators.
         */
        public static function all()
        {
        }
    }
    /**
     * Cardinality matcher that holds a single rule for one of the four cardinality values.
     *
     * These can be conjuncted together via Toolset_Relationship_Query_Cardinality_Match_Conjunction.
     * @since 2.5.5
     */
    class Toolset_Relationship_Query_Cardinality_Match_Single implements \IToolset_Relationship_Query_Cardinality_Match
    {
        /**
         * Toolset_Relationship_Query_Cardinality_Match_Single constructor.
         *
         * @param IToolset_Relationship_Role_Parent_Child $role
         * @param string $boundary Which cardinality boundary this involves (use MIN or MAX constants
         *     on the Toolset_Relationship_Cardinality class).
         * @param string $operator Operator to compare the cardinality with given value (use one of the
         *     operators defined in Toolset_Relationship_Query_Cardinality_Match_Operators).
         * @param int $value Value to compare the cardinality to.
         */
        public function __construct(\IToolset_Relationship_Role_Parent_Child $role, $boundary, $operator, $value)
        {
        }
        /**
         * @return IToolset_Relationship_Role_Parent_Child
         */
        public function get_role()
        {
        }
        /**
         * @return string
         */
        public function get_boundary()
        {
        }
        /**
         * @return string
         */
        public function get_operator()
        {
        }
        /**
         * @return int
         */
        public function get_value()
        {
        }
    }
    /**
     * Builds the MySQL for the relationship query out of IToolset_Relationship_Query_Condition instances.
     *
     * @since 2.5.4
     */
    class Toolset_Relationship_Query_Sql_Expression_Builder
    {
        /**
         * Toolset_Relationship_Query_Sql_Expression_Builder constructor.
         *
         * @param Toolset_Relationship_Table_Name|null $table_name_di
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\RelationshipDatabaseOperations|null $database_operations_di
         */
        public function __construct(\Toolset_Relationship_Table_Name $table_name_di = \null, \OTGS\Toolset\Common\Relationships\DatabaseLayer\RelationshipDatabaseOperations $database_operations_di = \null)
        {
        }
        /**
         * Build a complete MySQL query from the conditions.
         *
         * Also make sure that the query results will be easily recognizable by Toolset_Relationship_Definition_Translator.
         *
         * @param IToolset_Relationship_Query_Condition $root_condition
         * @param bool $need_found_rows
         * @return string
         * @since 2.5.8 Can calculate found rows.
         */
        public function build(\IToolset_Relationship_Query_Condition $root_condition, $need_found_rows)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\RelationshipQuery {
    /**
     * Holds a result of a relationship query and the number of found rows (if available).
     *
     * @since Types 3.4.7
     */
    class CachedQueryResult
    {
        /**
         * CachedQueryResult constructor.
         *
         * @param \IToolset_Relationship_Definition[] $results
         * @param int|null $found_rows
         */
        public function __construct($results, $found_rows = null)
        {
        }
        /**
         * @return \IToolset_Relationship_Definition[]
         */
        public function get_results()
        {
        }
        /**
         * @return int|null
         */
        public function get_found_rows()
        {
        }
    }
}
namespace {
    /**
     * Condition for the Toolset_Relationship_Query_V2.
     *
     * Provides a wpdb instance to all its subclasses.
     *
     * @since m2m
     */
    abstract class Toolset_Relationship_Query_Condition implements \OTGS\Toolset\Common\Relationships\API\RelationshipQueryCondition
    {
        /**
         * By default, there is nothing to join.
         *
         * @return string
         */
        public function get_join_clause()
        {
        }
        /**
         * Get the alias of the post type set table that's joined to the query for a given role.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $role
         *
         * @return string
         */
        protected function get_type_set_table_alias($role)
        {
        }
    }
    /**
     * Condition that a relationship involves a certain intermediary post type.
     *
     * @since 2.6.7
     */
    class Toolset_Relationship_Query_Condition_Intermediary_Type extends \Toolset_Relationship_Query_Condition
    {
        /**
         * Toolset_Relationship_Query_Condition_Intermediary_Type constructor.
         *
         * @param string
         * @throws InvalidArgumentException
         */
        public function __construct($intermediary_type)
        {
        }
        /**
         * @inheritdoc
         * @return string
         */
        public function get_where_clause()
        {
        }
    }
    /**
     * Condition that a relationship cardinality matches certain constraints.
     *
     * The constraits are defined by a "matcher" object. See the "has_cardinality" method on the
     * query class for more information.
     *
     * @since 2.5.5
     */
    class Toolset_Relationship_Query_Condition_Has_Cardinality extends \Toolset_Relationship_Query_Condition
    {
        /**
         * Toolset_Relationship_Query_Condition_Has_Cardinality constructor.
         *
         * @param IToolset_Relationship_Query_Cardinality_Match $cardinality_match
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\RelationshipDatabaseOperations|null $database_operations_di
         */
        public function __construct(\IToolset_Relationship_Query_Cardinality_Match $cardinality_match, \OTGS\Toolset\Common\Relationships\DatabaseLayer\RelationshipDatabaseOperations $database_operations_di = \null)
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
    }
    /**
     * Condition that a relationship involves a certain type in a certain relationship role.
     *
     * @since m2m
     */
    class Toolset_Relationship_Query_Condition_Type extends \Toolset_Relationship_Query_Condition
    {
        /** @var IToolset_Relationship_Role_Parent_Child */
        protected $role;
        /** @var string */
        protected $type;
        /**
         * Toolset_Relationship_Query_Condition_Type constructor.
         *
         * @param string $type
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $role What relationship role to query for
         */
        public function __construct($type, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $role)
        {
        }
        /**
         * @inheritdoc
         *
         * @return string
         */
        public function get_where_clause()
        {
        }
    }
    /**
     * Condition that a relationship has not a certain type in a certain relationship role.
     *
     * @since m2m
     */
    class Toolset_Relationship_Query_Condition_Exclude_Type extends \Toolset_Relationship_Query_Condition_Type
    {
        /**
         * @inheritdoc
         *
         * @return string
         */
        public function get_where_clause()
        {
        }
    }
    /**
     * Condition that excludes a relationship.
     *
     * @since m2m
     */
    class Toolset_Relationship_Query_Condition_Exclude_Relationship extends \Toolset_Relationship_Query_Condition
    {
        /**
         * Toolset_Relationship_Query_Condition_Exclude_Relationship constructor.
         *
         * @param IToolset_Relationship_Definition $relationship Relationship to be excluded.
         */
        public function __construct(\IToolset_Relationship_Definition $relationship)
        {
        }
        /**
         * @inheritdoc
         *
         * @return string
         */
        public function get_where_clause()
        {
        }
    }
    /**
     * Abstract condition for querying by a boolean flag stored as 1/0.
     *
     * @since 2.5.4
     */
    abstract class Toolset_Relationship_Query_Condition_Is_Boolean_Flag extends \Toolset_Relationship_Query_Condition
    {
        /**
         * Toolset_Relationship_Query_Condition_Is_Active constructor.
         *
         * @param bool|string $flag_value '*' will match anything.
         */
        public function __construct($flag_value)
        {
        }
        /**
         * @inheritdoc
         * @return string
         */
        public function get_where_clause()
        {
        }
        /**
         * @return string Name of the database column to query by.
         */
        abstract protected function get_flag_column_name();
    }
    /**
     * Condition that a relationship has a certain origin (was created through a wizard or as a
     * post reference field or a repeatable field group).
     *
     * @since m2m
     */
    class Toolset_Relationship_Query_Condition_Origin extends \Toolset_Relationship_Query_Condition
    {
        /**
         * Toolset_Relationship_Query_Condition_Origin constructor.
         *
         * @param string|null $origin Null value to return all origins.
         * @throws InvalidArgumentException
         */
        public function __construct($origin)
        {
        }
        /**
         * @inheritdoc
         * @return string
         */
        public function get_where_clause()
        {
        }
    }
    /**
     * Condition that the relationship has at least one active post type in a given role (or another domain than posts).
     *
     * Note that if polymorphic relationships are introduced, a relationship with a mix of inactive and active post types
     * in one role will pass the condition but it will not have the information about the inactive types. That may become
     * an issue when editing relationships (however, there a query is not used, but relationships are loaded directly).
     *
     * @since 2.5.4
     */
    class Toolset_Relationship_Query_Condition_Has_Active_Types extends \Toolset_Relationship_Query_Condition
    {
        /** @var \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild */
        protected $for_role;
        /**
         * Toolset_Relationship_Query_Condition_Has_Active_Types constructor.
         *
         * @param bool $only_active_types
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\RelationshipDatabaseOperations|null $database_operations
         * @param Toolset_Post_Type_Query_Factory|null $post_type_query_factory_di
         */
        public function __construct($only_active_types, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\RelationshipDatabaseOperations $database_operations = \null, \Toolset_Post_Type_Query_Factory $post_type_query_factory_di = \null)
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
    }
    /**
     * Condition that a relationship involves a certain element domain.
     *
     * @since m2m
     */
    class Toolset_Relationship_Query_Condition_Has_Domain extends \Toolset_Relationship_Query_Condition
    {
        /**
         * Toolset_Relationship_Query_Condition_Has_Domain constructor.
         *
         * @param string $domain_name One of the Toolset_Field_Utils::DOMAIN_* values.
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $role
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\RelationshipDatabaseOperations|null $database_operations_di
         * @throws InvalidArgumentException
         */
        public function __construct($domain_name, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\RelationshipDatabaseOperations $database_operations_di = \null)
        {
        }
        /**
         * @inheritdoc
         *
         * @return string
         */
        public function get_where_clause()
        {
        }
    }
    /**
     * Condition that a relationship is active.
     *
     * @since m2m
     */
    class Toolset_Relationship_Query_Condition_Is_Active extends \Toolset_Relationship_Query_Condition_Is_Boolean_Flag
    {
        protected function get_flag_column_name()
        {
        }
    }
    /**
     * Condition that a relationship needs legacy support (because it was migrated
     * from the legacy implementation).
     *
     * @since m2m
     */
    class Toolset_Relationship_Query_Condition_Is_Legacy extends \Toolset_Relationship_Query_Condition_Is_Boolean_Flag
    {
        protected function get_flag_column_name()
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\RelationshipQuery {
    /**
     * Cache for relationship query results. Handle as a singleton.
     *
     * @since Types 3.4.7
     */
    class RelationshipQueryCache
    {
        /**
         * @return RelationshipQueryCache
         */
        public static function get_instance()
        {
        }
        /**
         * RelationshipQueryCache constructor.
         *
         * @param \OTGS\Toolset\Common\Utils\InMemoryCache $in_memory_cache
         */
        public function __construct(\OTGS\Toolset\Common\Utils\InMemoryCache $in_memory_cache)
        {
        }
        /**
         * Initialize the cache, add invalidation hooks.
         */
        public function initialize()
        {
        }
        /**
         * @param string $key
         * @param CachedQueryResult $value
         */
        public function push($key, \OTGS\Toolset\Common\Relationships\DatabaseLayer\RelationshipQuery\CachedQueryResult $value)
        {
        }
        /**
         * @param string $key
         *
         * @return CachedQueryResult|null
         */
        public function get($key)
        {
        }
        /**
         * Delete all used cache records.
         */
        public function flush()
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer {
    /**
     * Simple in-memory cache for association query results.
     *
     * @since 3.0.3
     */
    class AssociationQueryCache
    {
        /**
         * @return AssociationQueryCache
         */
        public static function get_instance()
        {
        }
        public function initialize()
        {
        }
        /**
         * @param string $key
         * @param mixed $value
         */
        public function push($key, $value)
        {
        }
        /**
         * @param string $key
         * @param null|&bool $found
         *
         * @return mixed
         */
        public function get($key, &$found)
        {
        }
        /**
         * Delete all used cache records.
         *
         * @since Types 3.1.3
         */
        public function flush()
        {
        }
    }
}
namespace {
    /**
     * Enum class. Holds names of m2m tables and provides methods that return full table names
     * with correct $wpdb prefixes.
     *
     * NOT to be used outside the m2m API under any circumstances.
     *
     * @since m2m
     */
    class Toolset_Relationship_Table_Name
    {
        /**
         * Toolset_Relationship_Table_Name constructor.
         *
         * @param wpdb|null $wpdb_di
         */
        public function __construct(\wpdb $wpdb_di = \null)
        {
        }
        public function association_table()
        {
        }
        public function relationship_table()
        {
        }
        public function type_set_table()
        {
        }
        /**
         * @deprecated Instantiate the class before using it.
         * @return string
         */
        public static function associations()
        {
        }
        // fixme check all usages and update to the new table structure
        public static function association_translations()
        {
        }
        /**
         * @deprecated Instantiate the class before using it.
         * @return string
         */
        public static function relationships()
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1 {
    /**
     * Handles the persistence of intermediary posts.
     *
     * @since m2m
     */
    class Toolset_Association_Intermediary_Post_Persistence implements \OTGS\Toolset\Common\Relationships\API\IntermediaryPostPersistence
    {
        /**
         * Number of items handled each loop
         */
        const DEFAULT_LIMIT = 50;
        /**
         * Class constructor
         *
         * @param \IToolset_Relationship_Definition $relationship Relationship.
         * @param \OTGS\Toolset\Common\WPML\WpmlService|null $wpml_service_di
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory|null $database_layer_factory
         *
         * @since m2m
         */
        public function __construct(\IToolset_Relationship_Definition $relationship = null, \OTGS\Toolset\Common\WPML\WpmlService $wpml_service_di = null, \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory $database_layer_factory = null)
        {
        }
        /**
         * Create an intermediary post for a new association.
         *
         * @param int $parent_id Association parent id.
         * @param int $child_id Association child id.
         *
         * @return int|null ID of the new post or null if the post creation failed.
         * @since m2m
         */
        public function create_intermediary_post($parent_id, $child_id)
        {
        }
        /**
         * It there are associations belonging to the definition, intermediary post without field values has to be created.
         *
         * @param int $limit The number of associations in a loop.
         *
         * @since 2.3
         */
        public function create_empty_associations_intermediary_posts($limit = 0)
        {
        }
        /**
         * Removes intermediary post from associations.
         *
         * @param int $limit The number of associations in a loop.
         *
         * @return int Number of associations updated.
         * @since 2.3
         */
        public function remove_associations_intermediary_posts($limit = 0)
        {
        }
        /**
         * Creates an empty association intermediary post
         *
         * @param \IToolset_Association $association Association.
         *
         * @return int Post ID
         * @since m2m
         */
        public function create_empty_association_intermediary_post($association)
        {
        }
        /**
         * Delete the intermediary post if it exists and it's not disabled by a filter.
         *
         * This also deletes all its translations.
         *
         * @param \IToolset_Association $association
         */
        public function maybe_delete_intermediary_post(\IToolset_Association $association)
        {
        }
        /**
         * Delete the intermediary post if it's not disabled by a filter.
         *
         * This also deletes all its translations.
         *
         * @param $post_id
         */
        public function delete_intermediary_post($post_id)
        {
        }
    }
    /**
     * Translate the association data between the IToolset_Association model and a database row.
     *
     * @since 2.5.9
     */
    class Toolset_Association_Translator
    {
        /**
         * Toolset_Association_Translator constructor.
         *
         * @param \Toolset_Relationship_Definition_Repository|null $definition_repository_di
         * @param \Toolset_Association_Factory|null $association_factory_di
         * @param \Toolset_Element_Factory|null $element_factory_di
         * @param \Toolset_WPML_Compatibility|null $wpml_service_di
         */
        public function __construct(\Toolset_Relationship_Definition_Repository $definition_repository_di = null, \Toolset_Association_Factory $association_factory_di = null, \Toolset_Element_Factory $element_factory_di = null, \Toolset_WPML_Compatibility $wpml_service_di = null)
        {
        }
        /**
         * @param object $database_row Object returned from the wpdb->get_results() query.
         * @param null|array $id_column_map Allows for overriding the names of the columns used to
         *    access element IDs. If not null, this must contain a map of columns for parent and child roles.
         *    The intermediary role is optional - if not provided, it is assumed the intermediary post ID is zero.
         *
         * @return \IToolset_Association
         * @throws \Toolset_Element_Exception_Element_Doesnt_Exist
         */
        public function from_database_row($database_row, $id_column_map = null)
        {
        }
        /**
         * Translate a database row to an association instance if element translations are available.
         *
         * @param object $database_row
         * @param array $id_column_map Nested associative array with:
         *     role --> language code --> name of the column with the element ID.
         *     In the database row, the IDs may be zero for translated (non-default language) parent
         *     or child or any intermediary posts.
         *
         * @return \IToolset_Association
         * @throws \Toolset_Element_Exception_Element_Doesnt_Exist
         */
        public function from_translated_database_row($database_row, $id_column_map)
        {
        }
        /**
         * @param \IToolset_Association $association
         *
         * @return array Database row as an associative array.
         * @throws \RuntimeException
         */
        public function to_database_row(\IToolset_Association $association)
        {
        }
        /**
         * @return string[] Column formats for columns as returned by to_database_row().
         */
        public function get_database_row_formats()
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer {
    /**
     * Interface for handling the persistence of associations, from IToolset_Association object
     * to a wpdb call and back.
     *
     * Like Toolset_Relationship_Definition_Persistence, this should not be used from outside
     * of the m2m API. Everything required for working with associations should be
     * implemented on IToolset_Relationship_Definition.
     *
     * @since 4.0
     */
    interface AssociationPersistence
    {
        /**
         * Load a native association from the database.
         *
         * @param int $association_uid Association UID.
         *
         * @return null|\IToolset_Association The association instance
         *     or null if it couln't have been loaded.
         * @deprecated Do not use this outside of the m2m API, instead, use the association query.
         */
        public function load_association_by_uid($association_uid);
        /**
         * Insert a new association in the database.
         *
         * @param \IToolset_Association $association
         *
         * @return \IToolset_Association
         * @throws \Toolset_Element_Exception_Element_Doesnt_Exist
         */
        public function insert_association(\IToolset_Association $association);
        /**
         * Delete an association from the database.
         *
         * Also delete an intermediary post if it exists.
         *
         * @param \IToolset_Association $association
         *
         * @return \Toolset_Result
         * @since m2m
         */
        public function delete_association(\IToolset_Association $association);
        /**
         * Do the toolset_before_association_delete action.
         *
         * See report_association_change() for action parameter information.
         *
         * @param \IToolset_Association $association
         *
         * @since 2.7
         */
        public function report_before_association_delete(\IToolset_Association $association);
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1 {
    /**
     * Handles the persistence of associations, from IToolset_Association object
     * to a wpdb call and back.
     *
     * Like Toolset_Relationship_Definition_Persistence, this should not be used from outside
     * of the m2m API. Everything required for working with associations should be
     * implemented on IToolset_Relationship_Definition.
     *
     * @since 2.5.8
     */
    class Toolset_Association_Persistence implements \OTGS\Toolset\Common\Relationships\DatabaseLayer\AssociationPersistence
    {
        /** @var null|\Toolset_Association_Cleanup_Factory */
        protected $_cleanup_factory;
        /**
         * Toolset_Association_Persistence constructor.
         *
         * @param \Toolset_Association_Factory|null $association_factory_di
         * @param \Toolset_Relationship_Table_Name|null $table_name_di
         * @param \wpdb|null $wpdb_di
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Translator|null $association_translator_di
         * @param \Toolset_Association_Cleanup_Factory|null $cleanup_factory_di
         */
        public function __construct(\Toolset_Association_Factory $association_factory_di = null, \Toolset_Relationship_Table_Name $table_name_di = null, \wpdb $wpdb_di = null, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Translator $association_translator_di = null, \Toolset_Association_Cleanup_Factory $cleanup_factory_di = null)
        {
        }
        /**
         * Load a native association from the database.
         *
         * @param int $association_uid Association UID.
         *
         * @return null|\IToolset_Association The association instance
         *     or null if it couln't have been loaded.
         * @deprecated Do not use this outside of the m2m API, instead, use the association query.
         */
        public function load_association_by_uid($association_uid)
        {
        }
        /**
         * Insert a new association in the database.
         *
         * @param \IToolset_Association $association
         *
         * @return \IToolset_Association
         * @throws \Toolset_Element_Exception_Element_Doesnt_Exist
         */
        public function insert_association(\IToolset_Association $association)
        {
        }
        /**
         * Delete an association from the database.
         *
         * Also delete an intermediary post if it exists.
         *
         * @param \IToolset_Association $association
         *
         * @return \Toolset_Result
         * @since m2m
         */
        public function delete_association(\IToolset_Association $association)
        {
        }
        /**
         * Do the toolset_before_association_delete action.
         *
         * See report_association_change() for action parameter information.
         *
         * @param \IToolset_Association $association
         *
         * @since 2.7
         */
        public function report_before_association_delete(\IToolset_Association $association)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\PotentialAssociation {
    /**
     * Shared functionality for adjusting the WP_Query behaviour.
     */
    abstract class WpQueryAdjustment extends \Toolset_Wpdb_User
    {
        /** @var \IToolset_Relationship_Definition */
        protected $relationship;
        /** @var \IToolset_Element */
        protected $for_element;
        /** @var \IToolset_Relationship_Role_Parent_Child */
        protected $target_role;
        /** @var \OTGS\Toolset\Common\WPML\WpmlService */
        protected $wpml_service;
        /** @var \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\PotentialAssociation\JoinManager */
        protected $join_manager;
        /**
         * Determine whether the WP_Query should be augmented.
         *
         * @return bool
         */
        abstract protected function is_actionable();
        /**
         * WpQueryAdjustment constructor.
         *
         * @param \IToolset_Relationship_Definition $relationship
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $target_role
         * @param \IToolset_Element $for_element
         * @param JoinManager $join_manager
         * @param \OTGS\Toolset\Common\WPML\WpmlService $wpml_service_di
         * @param \wpdb|null $wpdb_di
         */
        public function __construct(\IToolset_Relationship_Definition $relationship, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $target_role, \IToolset_Element $for_element, \OTGS\Toolset\Common\Relationships\DatabaseLayer\PotentialAssociation\JoinManager $join_manager, \OTGS\Toolset\Common\WPML\WpmlService $wpml_service_di = null, \wpdb $wpdb_di = null)
        {
        }
        /**
         * Hooks to filters in order to add extra clauses to the MySQL query.
         */
        public function before_query()
        {
        }
        /**
         * Cleanup - unhooks the filters added in before_query().
         */
        public function after_query()
        {
        }
        /**
         * @inheritDoc
         */
        public function add_join_clauses($join)
        {
        }
        /**
         * @inheritDoc
         */
        public function add_where_clauses($where)
        {
        }
        /**
         * @inheritDoc
         * @noinspection PhpUnusedParameterInspection
         */
        public function add_orderby_clauses($orderby, \WP_Query $wp_query)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1 {
    /**
     * Subclass of WpQueryAdjustment with some specifics for the version 1 database layer only.
     *
     * @since 4.0
     */
    abstract class WpQueryAdjustment extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\PotentialAssociation\WpQueryAdjustment
    {
        /**
         * WpQueryAdjustment constructor.
         *
         * @param \IToolset_Relationship_Definition $relationship
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $target_role
         * @param \IToolset_Element $for_element
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\PotentialAssociation\JoinManager $join_manager
         * @param \Toolset_WPML_Compatibility|null $wpml_service_di
         * @param \Toolset_Relationship_Table_Name|null $table_names_di
         * @param \wpdb|null $wpdb_di
         */
        public function __construct(\IToolset_Relationship_Definition $relationship, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $target_role, \IToolset_Element $for_element, \OTGS\Toolset\Common\Relationships\DatabaseLayer\PotentialAssociation\JoinManager $join_manager, \Toolset_WPML_Compatibility $wpml_service_di = null, \Toolset_Relationship_Table_Name $table_names_di = null, \wpdb $wpdb_di = null)
        {
        }
        /**
         * @return \Toolset_Relationship_Table_Name
         */
        protected function get_table_names()
        {
        }
        /**
         * @return \wpdb
         */
        protected function get_wpdb()
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\PotentialAssociation {
    /**
     * Augments WP_Query to check whether the posts can accept another association according to the relationship
     * cardinality.
     *
     * This is used in OTGS\Toolset\Common\Relationships\DatabaseLayer\PotentialAssociation\PostQuery.
     *
     * Both before_query() and after_query() methods need to be called as close to the actual
     * querying as possible, otherwise things will get broken.
     *
     * @since 2.8
     */
    class CardinalityPostQuery extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\WpQueryAdjustment
    {
        /**
         * CardinalityPostQuery constructor.
         *
         * @param \IToolset_Relationship_Definition $relationship
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $target_role
         * @param \IToolset_Element $for_element
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\PotentialAssociation\JoinManager $join_manager
         * @param \Toolset_Relationship_Table_Name|null $table_names_di
         * @param \wpdb|null $wpdb_di
         * @param \Toolset_WPML_Compatibility|null $wpml_service_di
         */
        public function __construct(\IToolset_Relationship_Definition $relationship, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $target_role, \IToolset_Element $for_element, \OTGS\Toolset\Common\Relationships\DatabaseLayer\PotentialAssociation\JoinManager $join_manager, \Toolset_Relationship_Table_Name $table_names_di = null, \wpdb $wpdb_di = null, \Toolset_WPML_Compatibility $wpml_service_di = null)
        {
        }
        public function is_actionable()
        {
        }
        /**
         * Add a JOIN clause to the WP_Query's MySQL query string.
         *
         * If WPML is active, we just need to make sure that we'll have the default language version of the posts available.
         *
         * @param string $join
         * @return string
         */
        public function add_join_clauses($join)
        {
        }
        /**
         * Add a WHERE clause to the WP_Query's MySQL query string.
         *
         * Excludes elements that have already reached the cardinality limit.
         *
         * @param string $where
         * @return string
         */
        public function add_where_clauses($where)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\PotentialAssociation {
    /**
     * Handle the MySQL JOIN clause construction when augmenting the WP_Query in
     * \OTGS\Toolset\Common\Relationships\DatabaseLayer\PotentialAssociation\PostQuery.
     *
     * Make sure that JOINs come in the right order and are not duplicated.
     *
     * Note that hook() and unhook() must be called around the WP_Query usage for proper function.
     *
     * @since 2.8
     */
    interface JoinManager
    {
        public function hook();
        public function unhook();
        /**
         * Indicate that a certain table (or tables) need to be joined.
         *
         * @param string $table_keyword One of the JOIN_ constants
         */
        public function register_join($table_keyword);
        /**
         * Add all registered JOINs to the JOIN clause.
         *
         * Note that this has to be idempotent since the filter may be applied several times within a single WP_Query
         * instance.
         *
         * @param string $join
         *
         * @return string
         */
        public function add_join_clauses($join);
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\PotentialAssociation {
    /**
     * Handle the MySQL JOIN clause construction when augmenting the WP_Query in OTGS\Toolset\Common\Relationships\DatabaseLayer\PotentialAssociation\PostQuery.
     *
     * Make sure that JOINs come in the right order and are not duplicated.
     *
     * Note that hook() and unhook() must be called around the WP_Query usage for proper function.
     *
     * @since 2.8
     */
    class JoinManager extends \Toolset_Wpdb_User implements \OTGS\Toolset\Common\Relationships\DatabaseLayer\PotentialAssociation\JoinManager
    {
        /** @var \IToolset_Relationship_Definition */
        protected $relationship;
        /** @var \IToolset_Element */
        protected $for_element;
        /** @var \IToolset_Relationship_Role_Parent_Child */
        protected $target_role;
        /** @var \Toolset_WPML_Compatibility */
        protected $wpml_service;
        // Keywords for specific JOINs
        const JOIN_DEFAULT_POST_TRANSLATION = 'default_post_translation';
        const JOIN_ASSOCIATIONS_TABLE = 'associations_table';
        const JOIN_DEFAULT_LANG_ASSOCIATIONS = 'default_lang_associations';
        /**
         * JoinManager constructor.
         *
         * @param \IToolset_Relationship_Definition $relationship
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $target_role
         * @param \IToolset_Element $for_element
         * @param \Toolset_Relationship_Table_Name|null $table_names_di
         * @param \wpdb|null $wpdb_di
         * @param \Toolset_WPML_Compatibility|null $wpml_service_di
         */
        public function __construct(\IToolset_Relationship_Definition $relationship, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $target_role, \IToolset_Element $for_element, \Toolset_Relationship_Table_Name $table_names_di = null, \wpdb $wpdb_di = null, \Toolset_WPML_Compatibility $wpml_service_di = null)
        {
        }
        public function hook()
        {
        }
        public function unhook()
        {
        }
        /**
         * Indicate that a certain table (or tables) need to be joined.
         *
         * @param string $table_keyword One of the JOIN_ constants
         */
        public function register_join($table_keyword)
        {
        }
        protected function get_table_names()
        {
        }
        /**
         * Add all registered JOINs to the JOIN clause.
         *
         * Note that this has to be idempotent since the filter may be applied several times within a single WP_Query instance.
         *
         * @param string $join
         * @return string
         */
        public function add_join_clauses($join)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1 {
    /**
     * Augments WP_Query to check whether posts are associated with a particular other element ID,
     * and dismisses those posts.
     *
     * This is used in OTGS\Toolset\Common\Relationships\DatabaseLayer\PotentialAssociation\PostQuery to handle distinct
     * relationships.
     *
     * Both before_query() and after_query() methods need to be called as close to the actual
     * querying as possible, otherwise things will get broken.
     *
     * @since m2m
     */
    class Toolset_Relationship_Distinct_Post_Query extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\WpQueryAdjustment
    {
        protected function is_actionable()
        {
        }
        /**
         * Add a JOIN clause to the WP_Query's MySQL query string.
         *
         * That will connect the row from the associations table, if there is an association
         * with the correct relationship and the $for_element.
         *
         * Otherwise, those columns will be NULL, because we're doing a LEFT JOIN here.
         *
         * If WPML is active, we also do the same comparison for the default language version of the
         * queried post, if it exists.
         *
         * @param string $join
         *
         * @return string
         */
        public function add_join_clauses($join)
        {
        }
        /**
         * Add a WHERE clause to the WP_Query's MySQL query string.
         *
         * After adding the JOIN, we only need to check that there's not an ID of the
         * column with $for_element: That means there's no association between the queried
         * post and $for_element, and we can offer the post as a result.
         *
         * If WPML is active, we also have to check that there's no default language translation
         * of the queried post that would be part of such an association.
         *
         * @param string $where
         *
         * @return string
         */
        public function add_where_clauses($where)
        {
        }
    }
    /**
     * Manages the way element IDs are obtained when building the MySQL query for associations.
     *
     * Generates SELECT clauses for the element IDs. Allows for injecting additional JOIN clauses
     * into the final query.
     *
     * @since 2.5.10
     */
    interface IToolset_Association_Query_Element_Selector
    {
        /**
         * The element selector needs to be initialized early so that it can interact
         * with the join manager object, if needed.
         *
         * See OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Sql_Expression_Builder::build() for detailed information.
         *
         * @return void
         */
        public function initialize();
        /**
         * Get an alias for an element ID that will be used in the SELECT clause.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param bool $translate_if_possible
         *
         * @return string
         */
        public function get_element_id_alias(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $translate_if_possible = true);
        /**
         * Tell whether there may be a different element ID value for the current and the default language.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         *
         * @return mixed
         */
        public function has_element_id_translated(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role);
        /**
         * Get a name of the table and the column that contains an element ID.
         *
         * This is different from the alias because it can be used within the query itself
         * for other purposes.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param bool $translate_if_possible
         *
         * @return string Unambiguous "column" or "table.column" that contains ID of the element.
         */
        public function get_element_id_value(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $translate_if_possible = true);
        /**
         * Get all the select clauses for all the element IDs.
         *
         * Individual clauses must be connected with a comma, but there must not be
         * a trailing comma present.
         *
         * @return string
         */
        public function get_select_clauses();
        /**
         * Get all JOIN clauses that need to be included in the query.
         *
         * The only assumption these JOINs can make is that there might be the relationships table joined
         * first (if the element selector requires it). Anything else coming from the join manager
         * will be joined after.
         *
         * @return string
         */
        public function get_join_clauses();
        /**
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         *
         * @return void
         */
        public function request_element_in_results(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role);
        /**
         * Call this to make sure the association ID and relationship ID will be included in the SELECT clause.
         *
         * @return void
         * @since 2.6.1
         */
        public function request_association_and_relationship_in_results();
        /**
         * Call this to make sure the DISTINCT keyword will be used.
         *
         * @return void
         * @since 2.6.1
         */
        public function request_distinct_query();
        /**
         * Get the DISTINCT keyword or an empty string.
         *
         * @return string
         * @since 2.6.1
         */
        public function maybe_get_distinct_modifier();
        /**
         * Get roles that have been already requested.
         *
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipRole[]
         */
        public function get_requested_element_roles();
        /**
         * Signal whether the intermediary post column can be skipped from the results.
         *
         * Note that this is really only concerning the result transformation object, which can then make a more informed
         * decision about calling request_element_in_results().
         *
         * @param bool $skip
         *
         * @return void
         */
        public function skip_intermediary_posts($skip = true);
        /**
         * Returns true if the intermediary post column may be skipped in for the result transformation process.
         *
         * @return bool
         * @see self::skip_intermediary_posts()
         */
        public function should_skip_intermediary_posts();
    }
    /**
     * Shared functionality for all element selector implementations.
     *
     * @since 2.5.10
     */
    abstract class Toolset_Association_Query_Element_Selector_Abstract implements \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\IToolset_Association_Query_Element_Selector
    {
        /** @var \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Relationship_Database_Operations */
        protected $database_operations;
        /** @var \Toolset_Relationship_Database_Unique_Table_Alias */
        protected $table_alias;
        /** @var Toolset_Association_Query_Table_Join_Manager */
        protected $join_manager;
        /** @var \wpdb */
        protected $wpdb;
        /** @var \Toolset_WPML_Compatibility */
        protected $wpml_service;
        /** @var \OTGS\Toolset\Common\Relationships\API\RelationshipRole[] */
        protected $requested_roles = array();
        /**
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Element_Selector_Abstract
         * constructor.
         *
         * @param \Toolset_Relationship_Database_Unique_Table_Alias $table_alias
         * @param Toolset_Association_Query_Table_Join_Manager $join_manager
         * @param \wpdb|null $wpdb_di
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Relationship_Database_Operations|null $database_operations_di
         * @param \Toolset_WPML_Compatibility|null $wpml_compatibility_di
         */
        public function __construct(\Toolset_Relationship_Database_Unique_Table_Alias $table_alias, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager, \wpdb $wpdb_di = null, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Relationship_Database_Operations $database_operations_di = null, \Toolset_WPML_Compatibility $wpml_compatibility_di = null)
        {
        }
        /**
         * @inheritdoc
         */
        public function initialize()
        {
        }
        /**
         * Get the element ID column name of the associations table.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         *
         * @return string
         */
        protected function get_id_column(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role)
        {
        }
        /**
         * @inheritdoc
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         */
        public function request_element_in_results(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role)
        {
        }
        /**
         * @inheritdoc
         */
        public function request_association_and_relationship_in_results()
        {
        }
        /**
         * Get the select clauses for association and relationship IDs if they have been requested.
         *
         * @return string[]
         * @since 2.6.1
         */
        protected function maybe_get_association_and_relationship()
        {
        }
        /**
         * @inheritdoc
         *
         * @since 2.6.1
         */
        public function request_distinct_query()
        {
        }
        /**
         * @inheritdoc
         *
         * @return string
         * @since 2.6.1
         */
        public function maybe_get_distinct_modifier()
        {
        }
        /**
         * @inheritDoc
         */
        public function get_requested_element_roles()
        {
        }
        /**
         * @inheritDoc
         *
         * @param bool $skip
         */
        public function skip_intermediary_posts($skip = true)
        {
        }
        /**
         * @inheritDoc
         */
        public function should_skip_intermediary_posts()
        {
        }
    }
    /**
     * Element selector that translates post elements and chooses the best element ID
     * (the translated one, but defaults to the original if the translation doesn't exist).
     *
     * @since 2.5.10
     */
    class Toolset_Association_Query_Element_Selector_Wpml extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Element_Selector_Abstract
    {
        /** @var string This is hardcoded across the association query classes. */
        const ASSOCIATIONS_ALIAS = 'associations';
        /**
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Element_Selector_Wpml
         * constructor.
         *
         * @param \Toolset_Relationship_Database_Unique_Table_Alias $table_alias
         * @param Toolset_Association_Query_Table_Join_Manager $join_manager
         * @param array $unnecessary_wpml_table_joins
         * @param array $roles_with_only_translated_posts
         * @param \wpdb|null $wpdb_di
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Relationship_Database_Operations|null $database_operations_di
         * @param \Toolset_WPML_Compatibility|null $wpml_compatibility_di
         */
        public function __construct(\Toolset_Relationship_Database_Unique_Table_Alias $table_alias, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager, array $unnecessary_wpml_table_joins, array $roles_with_only_translated_posts, \wpdb $wpdb_di = null, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Relationship_Database_Operations $database_operations_di = null, \Toolset_WPML_Compatibility $wpml_compatibility_di = null)
        {
        }
        /**
         * @inheritdoc
         */
        public function initialize()
        {
        }
        /**
         * Build all parts of the query and other values needed for a single element role which
         * is required to use the translated language only.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         */
        public function build_data_for_role_with_forced_translation(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role)
        {
        }
        /**
         * Get the language that will be used for the query results (besides the default language).
         *
         * @return string
         * @since 2.6.8
         */
        protected function get_translation_language()
        {
        }
        /**
         * @inheritdoc
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param bool $translate_if_possible
         *
         * @return string
         */
        public function get_element_id_alias(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $translate_if_possible = true)
        {
        }
        /**
         * @inheritdoc
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param bool $translate_if_possible
         *
         * @return string
         */
        public function get_element_id_value(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $translate_if_possible = true)
        {
        }
        /**
         * @inheritdoc
         *
         * @return string
         */
        public function get_join_clauses()
        {
        }
        /**
         * @inheritdoc
         *
         * @return string
         */
        public function get_select_clauses()
        {
        }
        /**
         * @inheritdoc
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         */
        public function request_element_in_results(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role)
        {
        }
        /**
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         */
        public function request_element_in_join_only(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role)
        {
        }
        /**
         * Tell whether there may be a different element ID value for the current and the default language.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         *
         * @return mixed
         */
        public function has_element_id_translated(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role)
        {
        }
    }
    /**
     * Element selector that translates post elements and chooses the best element ID
     * when the current language is "all" (to display all content disregarding their language).
     *
     * This selector uses a specific provided language instead, or uses the default language.
     *
     * The Toolset_Association_Query_V2 is responsible for determining the correct language code.
     *
     * @since 2.6.8
     */
    class Toolset_Association_Query_Element_Selector_Wpml_Lang_All extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Element_Selector_Wpml
    {
        /**
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Element_Selector_Wpml_Lang_All
         * constructor.
         *
         * @param \Toolset_Relationship_Database_Unique_Table_Alias $table_alias
         * @param Toolset_Association_Query_Table_Join_Manager $join_manager
         * @param string|null $translation_language Language code (except 'all') or null for default language.
         * @param string[] $unnecessary_wpml_table_joins
         * @param \wpdb|null $wpdb_di
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Relationship_Database_Operations|null $database_operations_di
         * @param \Toolset_WPML_Compatibility|null $wpml_compatibility_di
         */
        public function __construct(\Toolset_Relationship_Database_Unique_Table_Alias $table_alias, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager, $translation_language, $unnecessary_wpml_table_joins, \wpdb $wpdb_di = null, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Relationship_Database_Operations $database_operations_di = null, \Toolset_WPML_Compatibility $wpml_compatibility_di = null)
        {
        }
        /**
         * Get the language that will be used for the query results (besides the default language).
         *
         * @return string
         * @since 2.6.8
         */
        protected function get_translation_language()
        {
        }
    }
    /**
     * Provider for the element selector.
     *
     * It creates the correct one depending on the state of WPML and the current language
     * and then keeps providing the same instance every time.
     *
     * Together with the restriction that condition classes must not use the element selector
     * in their constructor, this allows us to inject this dependency to query conditions
     * but wait until all conditions are instantiated before we decide which element selector
     * to actually use.
     *
     * @since 2.5.10
     */
    class Toolset_Association_Query_Element_Selector_Provider
    {
        const FILTER_WPML_SELECTOR = 'toolset_association_query_use_wpml_element_selector';
        /**
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Element_Selector_Provider
         * constructor.
         *
         * @param \Toolset_Condition_Plugin_Wpml_Is_Active_And_Configured|null $is_wpml_active_di
         * @param \Toolset_Condition_Plugin_Wpml_Is_Current_Language_Default|null $is_current_language_default_di
         * @param \Toolset_WPML_Compatibility|null $wpml_service_di
         */
        public function __construct(\Toolset_Condition_Plugin_Wpml_Is_Active_And_Configured $is_wpml_active_di = null, \Toolset_Condition_Plugin_Wpml_Is_Current_Language_Default $is_current_language_default_di = null, \Toolset_WPML_Compatibility $wpml_service_di = null)
        {
        }
        /**
         * Get the selector instance once it has been created.
         *
         * @return IToolset_Association_Query_Element_Selector|null
         */
        public function get_selector()
        {
        }
        /**
         * Create an appropriate element selector.
         *
         * This can be called only once.
         *
         * @param \Toolset_Relationship_Database_Unique_Table_Alias $table_alias
         * @param Toolset_Association_Query_Table_Join_Manager $join_manager
         * @param Toolset_Association_Query_V2 $query
         * @param \IToolset_Relationship_Role[] $unnecessary_wpml_table_joins
         * @param bool $can_skip_intermediary_posts
         *
         * @return IToolset_Association_Query_Element_Selector
         * @throws \RuntimeException When trying to create the element selector for the second time.
         */
        public function create_selector(\Toolset_Relationship_Database_Unique_Table_Alias $table_alias, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_V2 $query, array $unnecessary_wpml_table_joins, $can_skip_intermediary_posts)
        {
        }
        /**
         * Set whether element translation should be attempted at all (by default, it is true).
         *
         * Setting this to false will completely ignore WPML when building the MySQL query.
         *
         * @param bool $should_translate
         *
         * @since 2.6.4
         */
        public function attempt_translating_elements($should_translate)
        {
        }
        /**
         * Set the translation language that may be used instead of the current language.
         *
         * @param string $lang_code Valid language code.
         *
         * @since 2.6.8
         */
        public function set_translation_language($lang_code)
        {
        }
        /**
         * Allow forcing a particular language for a given role.
         *
         * That means, only associations with translated posts will be used, and those without translations
         * will be skipped from the results. Use with great caution.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         * @param string $lang_code Default language, current language or '*'.
         */
        public function force_language_per_role(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role, $lang_code)
        {
        }
    }
    /**
     * Trivial element selector that works in the standard mode,
     * if there is no need to translate anything.
     *
     * @since 2.5.10
     */
    class Toolset_Association_Query_Element_Selector_Default extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Element_Selector_Abstract
    {
        /**
         * @inheritdoc
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param bool $translate_if_possible
         *
         * @return string
         */
        public function get_element_id_alias(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $translate_if_possible = true)
        {
        }
        /**
         * @inheritdoc
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param bool $translate_if_possible
         *
         * @return string
         */
        public function get_element_id_value(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $translate_if_possible = true)
        {
        }
        /**
         * @inheritdoc
         *
         * @return string
         */
        public function get_select_clauses()
        {
        }
        /**
         * Generate a SELECT clause for a single role.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         *
         * @return string
         */
        protected function get_select_clause_for_role(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role)
        {
        }
        /**
         * @inheritdoc
         *
         * @return string
         */
        public function get_join_clauses()
        {
        }
        /**
         * Tell whether there may be a different element ID value for the current and the default language.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         *
         * @return mixed
         */
        public function has_element_id_translated(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role)
        {
        }
    }
    /**
     * A factory for AssociationQueryCondition implementations.
     *
     * @since 2.5.8
     */
    class Toolset_Association_Query_Condition_Factory
    {
        /**
         * Chain multiple conditions with OR.
         *
         * The whole statement will evaluate to true if at least one of provided conditions is true.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition[] $operands
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function do_or($operands)
        {
        }
        /**
         * Chain multiple conditions with AN.
         *
         * The whole statement will evaluate to true if all provided conditions are true.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition[] $operands
         *
         * @return \Toolset_Query_Condition_And
         */
        public function do_and($operands)
        {
        }
        /**
         * A condition that is always true.
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function tautology()
        {
        }
        /**
         * A condition that is always false.
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function contradiction()
        {
        }
        /**
         * Condition to query associations by a specific relationship (row) ID.
         *
         * @param int $relationship_id
         * @param \IToolset_Relationship_Definition|null $definition
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function relationship_id($relationship_id, \IToolset_Relationship_Definition $definition = null)
        {
        }
        /**
         * Condition to query associations by a specific intermediary (row) ID.
         *
         * @param int $intermediary_id
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function intermediary_id($intermediary_id)
        {
        }
        /**
         * Condition to query associations having intermediary id.
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function has_intermediary_id()
        {
        }
        /**
         * Condition to query associations by a particular element involved in a particular role.
         *
         * Warning: WPML-unaware implementation.
         *
         * @param int $element_id
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param Toolset_Association_Query_Element_Selector_Provider $element_selector_provider
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function element_id($element_id, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Element_Selector_Provider $element_selector_provider)
        {
        }
        /**
         * Condition to query associations by a particular element involved in a particular role.
         *
         * @param int $element_id
         * @param string $domain
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param Toolset_Association_Query_Element_Selector_Provider $element_selector_provider
         * @param $query_original_element
         * @param $translate_provided_id
         *
         * @return Toolset_Association_Query_Condition_Element_Id_And_Domain
         */
        public function element_id_and_domain($element_id, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Element_Selector_Provider $element_selector_provider, $query_original_element, $translate_provided_id)
        {
        }
        /**
         * Condition to query associations that do not contain a particular element in a particular role.
         *
         * @param int $element_id
         * @param string $domain
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param Toolset_Association_Query_Element_Selector_Provider $element_selector_provider
         * @param $query_original_element
         * @param $translate_provided_id
         *
         * @return Toolset_Association_Query_Condition_Element_Id_And_Domain
         */
        public function exclude_element($element_id, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Element_Selector_Provider $element_selector_provider, $query_original_element, $translate_provided_id)
        {
        }
        /**
         * Condition to query associations by a status of an element in a particular role.
         *
         * @param string|string[] $status
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param Toolset_Association_Query_Table_Join_Manager $join_manager
         * @param Toolset_Association_Query_Element_Selector_Provider $element_selector_provider
         *
         * @param \OTGS\Toolset\Common\PostStatus $post_status
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function element_status($status, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Element_Selector_Provider $element_selector_provider, \OTGS\Toolset\Common\PostStatus $post_status)
        {
        }
        /**
         * Query associations by the activity status of the relationship.
         *
         * @param bool $is_active
         * @param Toolset_Association_Query_Table_Join_Manager $join_manager
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function has_active_relationship($is_active, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager)
        {
        }
        /**
         * Query associations by the element domain on a specified role.
         *
         * @param string $domain Domain name.
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role
         * @param Toolset_Association_Query_Table_Join_Manager $join_manager
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function has_domain($domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager)
        {
        }
        /**
         * @param bool $needs_legacy_support
         * @param Toolset_Association_Query_Table_Join_Manager $join_manager
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function has_legacy_relationship($needs_legacy_support, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager)
        {
        }
        /**
         * Query associations by element type on a given role.
         *
         * Warning: This doesn't query for the domain. Make sure you at least add
         * a separate element domain condition. Otherwise, the results will be unpredictable.
         *
         * The best way is to use the has_domain_and_type() condition instead, which whill allow
         * for some more advanced optimizations.
         *
         * @param string $type
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role
         * @param Toolset_Association_Query_Table_Join_Manager $join_manager
         * @param \Toolset_Relationship_Database_Unique_Table_Alias $unique_table_alias
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function has_type($type, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager, \Toolset_Relationship_Database_Unique_Table_Alias $unique_table_alias)
        {
        }
        /**
         * @param string $domain
         * @param string $type
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role
         * @param Toolset_Association_Query_Table_Join_Manager $join_manager
         * @param \Toolset_Relationship_Database_Unique_Table_Alias $unique_table_alias
         *
         * @return Toolset_Association_Query_Condition_Has_Domain_And_Type
         */
        public function has_domain_and_type($domain, $type, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager, \Toolset_Relationship_Database_Unique_Table_Alias $unique_table_alias)
        {
        }
        /**
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param array $query_args
         * @param Toolset_Association_Query_Table_Join_Manager $join_manager
         * @param \Toolset_Relationship_Database_Unique_Table_Alias $table_alias
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function wp_query(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $query_args, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager, \Toolset_Relationship_Database_Unique_Table_Alias $table_alias)
        {
        }
        /**
         * @param string $search_string
         * @param bool $is_exact_search
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param Toolset_Association_Query_Table_Join_Manager $join_manager
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function search($search_string, $is_exact_search, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager)
        {
        }
        /**
         * @param int $association_id
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function association_id($association_id)
        {
        }
        /**
         * @param string $meta_key
         * @param string $meta_value
         * @param string $comparison_operator
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param Toolset_Association_Query_Table_Join_Manager $join_manager
         *
         * @return Toolset_Association_Query_Condition_Postmeta
         */
        public function postmeta($meta_key, $meta_value, $comparison_operator, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager)
        {
        }
        /**
         * Condition that a relationship has a certain origin.
         *
         * @param string $origin Origin: wizard, ...
         * @param Toolset_Association_Query_Table_Join_Manager $join_manager Join manager.
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function has_origin($origin, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager)
        {
        }
        /**
         * @param \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition $condition
         *
         * @return \Toolset_Query_Condition_Not
         */
        public function not(\OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition $condition)
        {
        }
        /**
         * @param int[] $element_ids
         * @param string $domain
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param Toolset_Association_Query_Element_Selector_Provider $element_selector_provider
         * @param bool $query_original_element
         * @param bool $translate_provided_ids
         *
         * @return Toolset_Association_Query_Condition_Multiple_Elements
         */
        public function multiple_elements($element_ids, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Element_Selector_Provider $element_selector_provider, $query_original_element, $translate_provided_ids)
        {
        }
        /**
         * Instantiate HasAutodeletableIntermediaryPost.
         *
         * @param bool $expected_value Value of the condition.
         * @param Toolset_Association_Query_Table_Join_Manager $join_manager The join manager object from the association
         *     query.
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function has_autodeletable_intermediary_post($expected_value, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager)
        {
        }
        /**
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function has_empty_intermediary()
        {
        }
    }
    /**
     * A wrapper class around a wpdb instance that redirects all calls to it, and
     * allows to use all its properties, but overrides the value of $wpdb->posts to return
     * an alias instead, that is specific for a selected element role.
     *
     * This is being used by
     * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition_Wp_Query, check it for
     * more information.
     *
     * @since 2.5.8
     */
    class Toolset_Association_Query_Wpdb_Wrapper
    {
        /**
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Wpdb_Wrapper constructor.
         *
         * @param \wpdb $wpdb
         * @param Toolset_Association_Query_Table_Join_Manager $join_manager
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         */
        public function __construct(\wpdb $wpdb, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role)
        {
        }
        /**
         * Get a $wpdb property name.
         *
         * Override $wpdb->posts.
         *
         * @param string $property_name
         *
         * @return mixed
         */
        public function __get($property_name)
        {
        }
        /**
         * Implement empty() and isset() checks for $wpdb properties.
         *
         * @param string $property_name
         *
         * @return bool
         */
        public function __isset($property_name)
        {
        }
        /**
         * Call a method on $wpdb.
         *
         * @param string $method_name
         * @param array $arguments
         *
         * @return mixed
         */
        public function __call($method_name, $arguments)
        {
        }
    }
    /**
     * Manages JOIN clauses shared between different conditions within one association query.
     *
     * Use methods in this class to obtain aliases for the tables you need. By doing that,
     * those tables will be added to the final JOIN clause. There is no risk of alias
     * conflicts as long as all conditions use the same instance of
     * Toolset_Relationship_Database_Unique_Table_Alias as is provided here in the constructor.
     *
     * @since 2.5.8
     */
    class Toolset_Association_Query_Table_Join_Manager
    {
        /**
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager
         * constructor.
         *
         * @param \Toolset_Relationship_Database_Unique_Table_Alias $unique_table_alias
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Relationship_Database_Operations|null $database_operations_di
         * @param \Toolset_Relationship_Table_Name|null $table_name_di
         * @param \wpdb|null $wpdb_di
         */
        public function __construct(\Toolset_Relationship_Database_Unique_Table_Alias $unique_table_alias, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Relationship_Database_Operations $database_operations_di = null, \Toolset_Relationship_Table_Name $table_name_di = null, \wpdb $wpdb_di = null)
        {
        }
        /**
         * Get an alias for a wp_posts table JOINed on a particular element role.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         *
         * @return string Table alias.
         */
        public function wp_posts(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role)
        {
        }
        /**
         * Get an alias for a wp_postmeta table JOINed on a particular element role and a meta_key value.
         *
         * This creates LEFT JOIN clauses, so that even with missing postmeta, the end results are not affected.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param string $meta_key
         *
         * @return string
         * @throws \InvalidArgumentException
         */
        public function wp_postmeta(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $meta_key)
        {
        }
        /**
         * Get an alias for a relationships table JOINed on the relationships_id column.
         *
         * @return string
         */
        public function relationships()
        {
        }
        /**
         * Build the final MySQL query part containing all requested JOIN clauses.
         *
         * @param IToolset_Association_Query_Element_Selector $element_selector
         *
         * @return string
         */
        public function get_join_clause(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\IToolset_Association_Query_Element_Selector $element_selector)
        {
        }
    }
    /**
     * Builds the MySQL expression for the association query.
     *
     * @since 2.5.10
     */
    class Toolset_Association_Query_Sql_Expression_Builder
    {
        /**
         * Toolset_Relationship_Query_Sql_Expression_Builder constructor.
         *
         * @param Toolset_Association_Query_Table_Join_Manager $join_manager
         * @param \Toolset_Relationship_Table_Name|null $table_name_di
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Relationship_Database_Operations|null $database_operations_di
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager, \Toolset_Relationship_Table_Name $table_name_di = null, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Relationship_Database_Operations $database_operations_di = null)
        {
        }
        /**
         * Build a complete MySQL query from the conditions.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition $root_condition
         * @param int $offset
         * @param int $limit
         * @param IToolset_Association_Query_Orderby $orderby
         * @param IToolset_Association_Query_Element_Selector $element_selector
         * @param bool $need_found_rows
         * @param IToolset_Association_Query_Result_Transformation $result_transformation
         *
         * @return string
         */
        public function build(\OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition $root_condition, $offset, $limit, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\IToolset_Association_Query_Orderby $orderby, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\IToolset_Association_Query_Element_Selector $element_selector, $need_found_rows, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\IToolset_Association_Query_Result_Transformation $result_transformation)
        {
        }
    }
    /**
     * Association query class with a more OOP/functional approach.
     *
     * Replaces Toolset_Association_Query.
     *
     * Allows for chaining query conditions and avoiding passing query arguments as associative arrays.
     * It makes it also possible to build queries with nested AND & OR statements in an arbitrary way.
     * The object model may be complex but all the complexity is hidden from the user, they need to know
     * only the methods on this class.
     *
     * Example usage:
     *
     * $query = new Toolset_Association_Query_V2();
     *
     * $results = $query
     *     ->add(
     *         $query->has_domain( 'posts', new Toolset_Relationship_Role_Parent() )
     *     )
     *     ->add(
     *         $query->do_or(
     *             $query->has_type( 'attachment', new Toolset_Relationship_Role_Parent() ),
     *             $query->do_and(
     *                 $query->has_type( 'page', new Toolset_Relationship_Role_Child() ),
     *                 $query->has_type( 'post', new Toolset_Relationship_Role_Child() ),
     *             )
     *         )
     *     )
     *     ->add(
     *         $query->search( 'some string', new Toolset_Relationship_Role_Parent() )
     *     )
     *     ->order_by_field_value( $custom_field_definition )
     *     ->order( 'DESC' )
     *     ->limit( 50 )
     *     ->offset( 100 )
     *     ->return_association_instances()
     *     ->get_results();
     *
     * Note about default conditions:
     * - If no element status (element_status() or has_available_elements()) condition is used when constructing the query,
     *   has_available_elements() is used.
     * - If no has_active_relationship() condition is used when constructing the query, has_active_relationship(true)
     *   is used.
     * - This mechanism doesn't recognize where, how and if these conditions are actually applied, so even
     *   $query->do_if( false, $query->has_active_relationship( true ) ) will disable the default
     *   has_active_relationship() condition.
     * - You can prevent the adding of default conditions by $query->do_not_add_default_conditions().
     *
     * @since 2.5.8
     */
    class Toolset_Association_Query_V2 extends \Toolset_Wpdb_User implements \OTGS\Toolset\Common\Relationships\API\AssociationQuery
    {
        /**
         * Toolset_Association_Query_V2 constructor.
         *
         * @param \wpdb|null $wpdb_di
         * @param \Toolset_Relationship_Database_Unique_Table_Alias|null $unique_table_alias_di
         * @param Toolset_Association_Query_Sql_Expression_Builder|null $expression_builder_di
         * @param Toolset_Association_Query_Condition_Factory|null $condition_factory_di
         * @param Toolset_Association_Translator|null $association_translator_di
         * @param \Toolset_Relationship_Definition_Repository|null $definition_repository_di
         * @param Toolset_Association_Query_Table_Join_Manager|null $join_manager_di
         * @param Toolset_Association_Query_Orderby_Factory|null $orderby_factory_di
         * @param Toolset_Association_Query_Element_Selector_Provider|null $element_selector_provider_di
         * @param \Toolset_WPML_Compatibility|null $wpml_service_di
         * @param Toolset_Association_Query_Cache|null $cache_object_di
         * @param Toolset_Association_Query_Result_Transformation_Factory|null $result_transformation_factory_di
         * @param \OTGS\Toolset\Common\PostStatus|null $post_status_di
         */
        public function __construct(\wpdb $wpdb_di = null, \Toolset_Relationship_Database_Unique_Table_Alias $unique_table_alias_di = null, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Sql_Expression_Builder $expression_builder_di = null, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition_Factory $condition_factory_di = null, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Translator $association_translator_di = null, \Toolset_Relationship_Definition_Repository $definition_repository_di = null, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager_di = null, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Orderby_Factory $orderby_factory_di = null, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Element_Selector_Provider $element_selector_provider_di = null, \Toolset_WPML_Compatibility $wpml_service_di = null, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Cache $cache_object_di = null, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Result_Transformation_Factory $result_transformation_factory_di = null, \OTGS\Toolset\Common\PostStatus $post_status_di = null)
        {
        }
        /**
         * Add another condition to the query.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition $condition
         *
         * @return $this
         */
        public function add(\OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition $condition)
        {
        }
        /**
         * Prevent the query from adding any default conditions. WYSIWYG.
         *
         * @return $this
         */
        public function do_not_add_default_conditions()
        {
        }
        /**
         * Apply stored conditions and perform the query.
         *
         * @return \IToolset_Association[]|int[]|\IToolset_Element[]
         */
        public function get_results()
        {
        }
        /**
         * Chain multiple conditions with OR.
         *
         * The whole statement will evaluate to true if at least one of provided conditions is true.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition[] $conditions
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function do_or(...$conditions)
        {
        }
        /**
         * Chain multiple conditions with AND.
         *
         * The whole statement will evaluate to true if all provided conditions are true.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition[] $conditions
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function do_and(...$conditions)
        {
        }
        /**
         * Choose a query condition depending on a boolean expression.
         *
         * @param bool $statement A boolean condition statement.
         * @param \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition $if_branch Query condition that will be used
         *     if the statement is true.
         * @param \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition|null $else_branch Query condition that will be
         *     used if the statement is false. If none is provided, a tautology is used (always true).
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         * @since 2.5.6
         */
        public function do_if($statement, \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition $if_branch, \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition $else_branch = null)
        {
        }
        public function not(\OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition $condition)
        {
        }
        /**
         * Query by a row ID of a relationship definition.
         *
         * @param int $relationship_id
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function relationship_id($relationship_id)
        {
        }
        /**
         * Query by a row intermediary_id of a relationship definition.
         *
         * @param int $relationship_id
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function intermediary_id($relationship_id)
        {
        }
        /**
         * Query by a relationship definition.
         *
         * @param \IToolset_Relationship_Definition $relationship_definition
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function relationship(\IToolset_Relationship_Definition $relationship_definition)
        {
        }
        /**
         * Query by a relationship definition slug.
         *
         * @param string $slug
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function relationship_slug($slug)
        {
        }
        /**
         * Query by an ID of an element in the selected role.
         *
         * Warning: This is an WPML-unaware query.
         *
         * @param int $element_id
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param bool $need_wpml_unaware_query Set this to true to avoid a _doing_it_wrong notice.
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function element_id($element_id, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $need_wpml_unaware_query = true)
        {
        }
        /**
         * Query by an ID of an element in the selected role.
         *
         * @param int $element_id
         * @param string $domain
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param bool $query_original_element If true, the query will check the element ID in the original language
         *     as stored in the association table. Default is false.
         * @param bool $translate_provided_id If true, this will try to translate the element ID (if
         *     applicable on the domain) and use the translated one in the final condition. Default is true.
         * @param bool $set_its_translation_language If true, the query may try to use the element's language
         *     to determine the desired language of the results (see determine_translation_language() for details)
         * @param null $ignored
         *
         * @return Toolset_Association_Query_Condition_Element_Id_And_Domain
         * @since 2.5.10
         */
        public function element_id_and_domain($element_id, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $query_original_element = false, $translate_provided_id = true, $set_its_translation_language = true, $ignored = null)
        {
        }
        /**
         * Query by a set of element IDs in the selected role.
         *
         * @param int[] $element_ids
         * @param string $domain
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param bool $query_original_element If true, the query will check the element ID in the original language
         *     as stored in the association table. Default is false.
         * @param bool $translate_provided_ids If true, this will try to translate the element ID (if
         *     applicable on the domain) and use the translated one in the final condition. Default is true.
         *
         * @return Toolset_Association_Query_Condition_Multiple_Elements
         * @since 3.0.3
         */
        public function multiple_elements($element_ids, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $query_original_element = false, $translate_provided_ids = true)
        {
        }
        /**
         * Query by an element in the selected role.
         *
         * @param \IToolset_Element $element
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole|null $for_role If null is provided, the query will involve all roles.
         * @param bool $query_original_element If true, the query will check the element ID in the original language
         *     as stored in the association table. Default is false.
         * @param bool $translate_provided_id If true, this will try to translate the element ID (if
         *     applicable on the domain) and use the translated one in the final condition. Default is true.
         * @param bool $set_its_translation_language If true, the query may try to use the element's language
         *     to determine the desired language of the results (see determine_translation_language() for details)
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function element(\IToolset_Element $element, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role = null, $query_original_element = false, $translate_provided_id = true, $set_its_translation_language = true)
        {
        }
        /**
         * Exclude associations with a particular element in the selected role.
         *
         * @param \IToolset_Element $element
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param bool $query_original_element If true, the query will check the element ID in the original language
         *     as stored in the association table. Default is false.
         * @param bool $translate_provided_id If true, this will try to translate the element ID (if
         *     applicable on the domain) and use the translated one in the final condition. Default is true.
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function exclude_element(\IToolset_Element $element, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $query_original_element = false, $translate_provided_id = true)
        {
        }
        /**
         * Query by a parent element.
         *
         * @param \IToolset_Element $element_source
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function parent(\IToolset_Element $element_source)
        {
        }
        /**
         * Query by a parent element ID.
         *
         * @param int $parent_id
         * @param string $domain
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function parent_id($parent_id, $domain = \Toolset_Element_Domain::POSTS)
        {
        }
        /**
         * Query by a child element.
         *
         * @param \IToolset_Element $element
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function child(\IToolset_Element $element)
        {
        }
        /**
         * Query by a child element ID.
         *
         * @param int $child_id
         * @param string $domain
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function child_id($child_id, $domain = \Toolset_Element_Domain::POSTS)
        {
        }
        /**
         * Query by an element status.
         *
         * @param string|string[] $statuses 'any'|'is_available'|'is_public' or one or more specific status values in an
         *     array. Meaning of these options is domain-dependant.
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole|null $for_role
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function element_status($statuses, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role = null)
        {
        }
        /**
         * Query only associations that have both elements available (see element_status()).
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function has_available_elements()
        {
        }
        /**
         * Query associations by the activity status of the relationship.
         *
         * @param bool $is_active
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function has_active_relationship($is_active = true)
        {
        }
        /**
         * Query associations by the fact whether the relationship was migrated from the legacy implementation.
         *
         * @param bool $needs_legacy_support
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function has_legacy_relationship($needs_legacy_support = true)
        {
        }
        /**
         * Query associations by the element domain on a specified role.
         *
         * @param string $domain
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function has_domain($domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role)
        {
        }
        /**
         * Query associations based on element type.
         *
         * Warning: This doesn't query for the domain. Make sure you at least add
         * a separate element domain condition. Otherwise, the results will be unpredictable.
         *
         * The best way is to use the has_domain_and_type() condition instead, which whill allow
         * for some more advanced optimizations.
         *
         * @param string $type Element type.
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function has_type($type, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role)
        {
        }
        /**
         * Query associations based on element domain and type.
         *
         * @param string $domain Element domain.
         * @param string $type Element type
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function has_domain_and_type($domain, $type, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role)
        {
        }
        /**
         * Condition that a relationship has a certain origin.
         *
         * @param String $origin Origin.
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function has_origin($origin)
        {
        }
        /**
         * Condition that the association has an intermediary id.
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function has_intermediary_id()
        {
        }
        /**
         * Query by a WP_Query arguments applied on an element of a specified role.
         *
         * WARNING: It is important that you read the documentation of
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition_Wp_Query before
         * using this.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param array $query_args
         * @param string|null $confirmation 'i_know_what_i_am_doing'
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         *
         * @throws \InvalidArgumentException Thrown if you don't know what you are doing.
         */
        public function wp_query(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $query_args, $confirmation = null)
        {
        }
        /**
         * Query by a string search in elements of a selected role.
         *
         * Note that the behaviour may be different per domain.
         *
         * @param string $search_string
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param bool $is_exact
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function search($search_string, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $is_exact = false)
        {
        }
        /**
         * Query by a specific association ID.
         *
         * This will also set the limit of the result count to one.
         *
         * @param int $association_id
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function association_id($association_id)
        {
        }
        public function meta($meta_key, $meta_value, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role = null, $comparison = \Toolset_Query_Comparison_Operator::EQUALS)
        {
        }
        /**
         * Query associations by the fact whether they have an intermediary post that can be automatically deleted
         * together with the association (which is a setting of the relationship definition).
         *
         * @param bool $expected_value Value of the condition.
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function has_autodeletable_intermediary_post($expected_value = true)
        {
        }
        public function has_empty_intermediary()
        {
        }
        /**
         * Indicate that get_results() should return instances of IToolset_Association.
         *
         * @return $this
         */
        public function return_association_instances()
        {
        }
        /**
         * Indicate that get_results() should return UIDs of associations.
         *
         * @return $this
         */
        public function return_association_uids()
        {
        }
        /**
         * Indicate that get_results() should return element IDs from a selected role.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         *
         * @return $this
         */
        public function return_element_ids(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role)
        {
        }
        /**
         * Indicate that get_results() should return IToolset_Element instances from a selected role.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         *
         * @return $this
         */
        public function return_element_instances(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role)
        {
        }
        /**
         * Indicate that get_results() should return arrays with elements indexed by their role names.
         *
         * This needs further configuration, see
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Result_Transformation_Element_Per_Role
         * for further details.
         *
         * @return Toolset_Association_Query_Result_Transformation_Element_Per_Role
         * @since 3.0.9
         */
        public function return_per_role()
        {
        }
        /**
         * Set an offset for the query.
         *
         * @param int $value
         *
         * @return $this
         * @throws \InvalidArgumentException Thrown if an invalid value is provided.
         */
        public function offset($value)
        {
        }
        /**
         * Limit a number of results for the query.
         *
         * Note that by default, the limit is set at a certain value, and the query can never be unlimited.
         *
         * @param int $value
         *
         * @return $this
         * @throws \InvalidArgumentException Thrown if an invalid value is provided.
         */
        public function limit($value)
        {
        }
        /**
         * Set the sorting order.
         *
         * @param string $value 'ASC'|'DESC'
         *
         * @return $this
         */
        public function order($value)
        {
        }
        /**
         * Indicate whether the query should also retrieve the total number of results.
         *
         * This is required for get_found_rows() to work.
         *
         * @param bool $is_needed
         *
         * @return $this
         */
        public function need_found_rows($is_needed = true)
        {
        }
        /**
         * Return the total number of found results after get_results() was called.
         *
         * For this to work, need_found_rows() needs to be called when building the query.
         *
         * @return int
         * @throws \RuntimeException
         */
        public function get_found_rows()
        {
        }
        /**
         * Indicate that no result ordering is needed.
         *
         * @return $this
         */
        public function dont_order()
        {
        }
        /**
         * Order results by a title of element of given role.
         *
         * Note that ordering by intermediary posts will cause the associations without those to be excluded from results.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         *
         * @return $this
         */
        public function order_by_title(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role)
        {
        }
        /**
         * Order results by a value of a certain custom field on a selected element role.
         *
         * @param \Toolset_Field_Definition $field_definition
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         *
         * @return $this
         * @throws \RuntimeException Thrown if the element domain is not supported.
         */
        public function order_by_field_value(\Toolset_Field_Definition $field_definition, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role)
        {
        }
        /**
         * Order results by a value of the element metadata.
         *
         * @param string $meta_key Meta key that should be used for ordering.
         * @param string $domain Valid element domain. At the moment, only posts are supported.
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role Role of the element whose metadata should be used for ordering.
         * @param bool $is_numeric If true, numeric ordering will be used.
         *
         * @return $this
         * @throws \RuntimeException If unsupported element domain is used.
         * @throws \InvalidArgumentException
         * @since 2.6.1
         */
        public function order_by_meta($meta_key, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $is_numeric = false)
        {
        }
        /**
         * Make sure that the elements in results will never get translated.
         *
         * @return $this
         * @since 2.6.4
         */
        public function dont_translate_results()
        {
        }
        /**
         * Set the preferred translation language.
         *
         * See determine_translation_language() for details.
         *
         * @param string $lang_code Valid language code.
         *
         * @return $this
         */
        public function set_translation_language($lang_code)
        {
        }
        /**
         * Allow forcing a particular language for a given role.
         *
         * That means, only associations with translated posts will be used, and those without translations
         * will be skipped from the results. Use with great caution.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         * @param string $lang_code Default language, current language or '*'.
         */
        public function force_language_per_role(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role, $lang_code)
        {
        }
        /**
         * Set the preferred translation language from a given element ID and domain.
         *
         * See determine_translation_language() for details.
         *
         * @param int $element_id ID of the element to take the language from.
         * @param string $domain Element domain.
         *
         * @return $this
         * @since 2.6.8
         */
        public function set_translation_language_by_element_id_and_domain($element_id, $domain)
        {
        }
        /**
         * Perform the query to only return the number of found rows, if we're not interested in
         * the actual results.
         *
         * @return int Number of results matching the query.
         */
        public function get_found_rows_directly()
        {
        }
        public function use_cache($use_cache = true)
        {
        }
        public function build_cache_key($query_string)
        {
        }
        /**
         * @inheritDoc
         */
        public function include_original_language($include = true)
        {
        }
        /**
         * @inheritDoc
         */
        public function force_display_as_translated_mode($do_force = true)
        {
        }
        /**
         * @inheritDoc
         */
        public function element_trid_or_id_and_domain($trid, $element_id, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $translate_provided_id = true, $set_its_translation_language = true, $element_identification_to_query_by = \OTGS\Toolset\Common\Relationships\API\ElementIdentification::CURRENT_LANGUAGE_IF_POSSIBLE)
        {
        }
    }
    /**
     * Interface for objects that handle the ORDER BY clause when building the association query.
     *
     * A dedicated set of classes is needed because sometimes, this also involves joining additional tables.
     *
     * @since 2.5.8
     */
    interface IToolset_Association_Query_Orderby
    {
        /**
         * Set the order direction.
         *
         * @param string $order 'ASC'|'DESC'
         *
         * @return void
         */
        public function set_order($order);
        /**
         * Build the ORDER BY clause (not including the "ORDER BY" keyword).
         *
         * @return string
         */
        public function get_orderby_clause();
        /**
         * If the class uses a join manager, request all needed joins now.
         *
         * @return void
         */
        public function register_joins();
    }
    /**
     * Shared functionality for most OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\IToolset_Association_Query_Orderby classes.
     *
     * @since 2.5.8
     */
    abstract class Toolset_Association_Query_Orderby implements \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\IToolset_Association_Query_Orderby
    {
        /** @var string */
        protected $order = 'ASC';
        /** @var Toolset_Association_Query_Table_Join_Manager */
        protected $join_manager;
        /**
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Orderby constructor.
         *
         * @param Toolset_Association_Query_Table_Join_Manager $join_manager
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager)
        {
        }
        /**
         * Set the direction of sorting.
         *
         * @param string $order 'ASC'|'DESC'
         *
         * @throws \InvalidArgumentException
         */
        public function set_order($order)
        {
        }
        /**
         * @inheritdoc
         */
        public function register_joins()
        {
        }
    }
    /**
     * Order associations by title of an element of given role.
     *
     * Note: Currently, only the posts domain is supported.
     *
     * Note: Ordering by intermediary posts will exclude associations that don't have one.
     *
     * @since 2.5.8
     */
    class Toolset_Association_Query_Orderby_Title extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Orderby
    {
        /**
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Orderby_Title constructor.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         * @param Toolset_Association_Query_Table_Join_Manager $join_manager
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager)
        {
        }
        /**
         * @inheritdoc
         */
        public function register_joins()
        {
        }
        /**
         * @inheritdoc
         * @return string
         */
        public function get_orderby_clause()
        {
        }
    }
    /**
     * Order associations by a postmeta value of an (post) element of given role.
     *
     * Note: Using this on an element of a wrong domain will exclude all associations from the results.
     *
     * @since 2.5.8
     */
    class Toolset_Association_Query_Orderby_Postmeta extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Orderby
    {
        /**
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Orderby_Postmeta constructor.
         *
         * @param string $meta_key
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         * @param Toolset_Association_Query_Table_Join_Manager $join_manager
         * @param string $cast_to If the metakey needs to be casted into a different type
         *
         * @throws \InvalidArgumentException
         */
        public function __construct($meta_key, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager, $cast_to = null)
        {
        }
        /**
         * @inheritdoc
         */
        public function register_joins()
        {
        }
        /**
         * @inheritdoc
         * @return string
         */
        public function get_orderby_clause()
        {
        }
    }
    /**
     * Don't order associations by anything.
     *
     * @since 2.5.8
     */
    class Toolset_Association_Query_Orderby_Nothing implements \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\IToolset_Association_Query_Orderby
    {
        public function get_orderby_clause()
        {
        }
        public function set_order($order)
        {
        }
        public function register_joins()
        {
        }
    }
    /**
     * Condition for the Toolset_Association_Query_V2.
     *
     * Provides a wpdb instance to all its subclasses.
     *
     * @since 2.5.8
     */
    abstract class Toolset_Association_Query_Condition implements \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
    {
        /**
         * By default, there is nothing to join.
         *
         * @return string
         */
        public function get_join_clause()
        {
        }
    }
    /**
     * Query associations by a flag of a relationship they belong to.
     *
     * @since 2.5.8
     */
    abstract class Toolset_Association_Query_Condition_Relationship_Flag extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition
    {
        /**
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition_Has_Active_Relationship
         * constructor.
         *
         * @param bool $expected_value
         * @param Toolset_Association_Query_Table_Join_Manager $join_manager
         */
        public function __construct($expected_value, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager)
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
        /**
         * Get the name of the column in the relationships table to query by.
         *
         * @return string
         */
        abstract protected function get_flag_name();
    }
    /**
     * Condition that filters associations by the fact whether they have an intermediary post
     * that can be automatically deleted together with the association (which is a setting of the relationship definition).
     *
     * @since Types 3.2
     */
    class HasAutodeletableIntermediaryPost extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition_Relationship_Flag
    {
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
        /**
         * Get the name of the column in the relationships table to query by.
         *
         * @return string
         */
        protected function get_flag_name()
        {
        }
    }
    /**
     * A WP_Query condition.
     *
     * It allows for filtering the results of the association query by a WP_Query being applied on
     * elements (posts) of a selected association role.
     *
     * WARNINGS and limitations:
     *
     * - The process to generate the query abuses WP_Query and is rather expensive in terms of performance.
     * - This is untested and highly experimental.
     *   The WP_Query hack is so ugly that it's beautiful, if you ask me. But let's see if we actually
     *   put this to an use.
     * - If used on non-post elements, the results are unpredictable. Never assume you're dealing only
     *   with post relationships.
     * - Only subsets of WP_Query arguments are supported. Basically, anything that requires joining other
     *   tables than wp_posts should be considered unreliable (in need of extra testing) and if you use
     *   this query condition multiple times inside one association query, overreaching wp_posts
     *   will most definitely cause a collision of table aliases.
     * - If you intend to use this only for searching elements by a string, please don't.
     *   Use $query->search() instead, which is much lighter and will become domain-agnostic
     *   when another domains become supported.
     * - This usage of WP_Query doesn't support sticky posts, filter suppressing and WPML language queries.
     *
     * @since 2.5.8
     */
    class Toolset_Association_Query_Condition_Wp_Query extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition
    {
        /**
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition_Wp_Query
         * constructor.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param array $query_args
         * @param Toolset_Association_Query_Table_Join_Manager $join_manager
         * @param \Toolset_Relationship_Database_Unique_Table_Alias $unique_table_alias
         * @param \Toolset_Relationship_Query_Factory|null $query_factory_di
         * @param \wpdb|null $wpdb_di
         *
         * @throws \InvalidArgumentException
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $query_args, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager, \Toolset_Relationship_Database_Unique_Table_Alias $unique_table_alias, \Toolset_Relationship_Query_Factory $query_factory_di = null, \wpdb $wpdb_di = null)
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
        /**
         * @inheritdoc
         *
         * @return string
         */
        public function get_join_clause()
        {
        }
    }
    /**
     * Condition to query associations by a specific association ID.
     *
     * @since 2.5.8
     */
    class Toolset_Association_Query_Condition_Association_Id extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition
    {
        /**
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition_Association_Id
         * constructor.
         *
         * @param int $association_id
         *
         * @throws \InvalidArgumentException
         */
        public function __construct($association_id)
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
    }
    /**
     * Condition to query associations by a specific intermediary post (row) ID.
     *
     * @since 2.6.7
     */
    class Toolset_Association_Query_Condition_Intermediary_Id extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition
    {
        /**
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition_Intermediary_Id
         * constructor.
         *
         * @param int $intermediary_id
         *
         * @throws \InvalidArgumentException
         */
        public function __construct($intermediary_id)
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
    }
    /**
     * Query condition by a postmeta value of a selected element role.
     *
     * Note: Using this will immediately exclude all non-post elements.
     *
     * @since 2.6.1
     */
    class Toolset_Association_Query_Condition_Postmeta extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition
    {
        /**
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition_Postmeta
         * constructor.
         *
         * @param string $meta_key
         * @param string $meta_value
         * @param string $comparison_operator
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param Toolset_Association_Query_Table_Join_Manager $join_manager
         *
         * @throws \InvalidArgumentException
         */
        public function __construct($meta_key, $meta_value, $comparison_operator, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager)
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
    }
    /**
     * Condition to query associations by a particular element involved in a particular role.
     *
     * @since 2.5.8
     */
    class Toolset_Association_Query_Condition_Element_Id_And_Domain extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition
    {
        /**
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition_Element_Id
         * constructor.
         *
         * @param int $element_id
         * @param string $domain
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Element_Selector_Provider $element_selector_provider
         * @param $query_original_element
         * @param $translate_provided_id
         */
        public function __construct($element_id, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Element_Selector_Provider $element_selector_provider, $query_original_element, $translate_provided_id)
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
        protected function get_operator()
        {
        }
    }
    /**
     * Condition to exclude a particular element from the results.
     *
     * See the parent class for details.
     *
     * @since 2.5.10
     */
    class Toolset_Association_Query_Condition_Exclude_Element extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition_Element_Id_And_Domain
    {
        protected function get_operator()
        {
        }
    }
    /**
     * Query associations by the is_active value of a relationship they belong to.
     *
     * @since 2.5.8
     */
    class Toolset_Association_Query_Condition_Has_Active_Relationship extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition_Relationship_Flag
    {
        /**
         * @inheritdoc
         * @return string
         */
        protected function get_flag_name()
        {
        }
    }
    /**
     * Condition to query by a set of elements in a selected role.
     *
     * If any of the provided IDs match, the row is accepted.
     *
     * @since 3.0.3
     */
    class Toolset_Association_Query_Condition_Multiple_Elements extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition
    {
        /**
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition_Element_Id
         * constructor.
         *
         * @param int[] $element_ids
         * @param string $domain
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param Toolset_Association_Query_Element_Selector_Provider $element_selector_provider
         * @param $query_original_element
         * @param $translate_provided_ids
         */
        public function __construct($element_ids, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Element_Selector_Provider $element_selector_provider, $query_original_element, $translate_provided_ids)
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
        public function get_element_id_to_query($element_id)
        {
        }
    }
    /**
     * Query associations by the fact whether the relationship they belong to was migrated from the legacy implementation
     * or not.
     *
     * @since 2.5.8
     */
    class Toolset_Association_Query_Condition_Has_Legacy_Relationship extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition_Relationship_Flag
    {
        /**
         * @inheritdoc
         * @return string
         */
        protected function get_flag_name()
        {
        }
    }
    /**
     * Query by searching a text in elements of a given role.
     *
     * Note: This currently supports only posts, but in the future, it should be domain-agnostic.
     *
     * @since 2.5.8
     */
    class Toolset_Association_Query_Condition_Search extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition
    {
        /**
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition_Search constructor.
         *
         * @param string $search_string
         * @param bool $is_exact_search
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param Toolset_Association_Query_Table_Join_Manager $join_manager
         * @param \wpdb|null $wpdb_di
         *
         * @throws \InvalidArgumentException
         */
        public function __construct($search_string, $is_exact_search, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager, \wpdb $wpdb_di = null)
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
    }
    /**
     * Condition to query associations by a type (not domain) of elements in the given role.
     *
     * @since 2.5.8
     */
    class Toolset_Association_Query_Condition_Has_Type extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition
    {
        /**
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition_Has_Type
         * constructor.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role
         * @param string $type
         * @param Toolset_Association_Query_Table_Join_Manager $join_manager
         * @param \Toolset_Relationship_Database_Unique_Table_Alias $unique_table_alias
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Relationship_Database_Operations|null $database_operations_di
         * @param \Toolset_Relationship_Table_Name|null $table_name_di
         *
         * @throws \InvalidArgumentException
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role, $type, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager, \Toolset_Relationship_Database_Unique_Table_Alias $unique_table_alias, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Relationship_Database_Operations $database_operations_di = null, \Toolset_Relationship_Table_Name $table_name_di = null)
        {
        }
        /**
         * @inheritdoc
         * @return string
         */
        public function get_join_clause()
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
    }
    /**
     * Condition to query associations by a specific intermediary post (row) ID.
     *
     * @since 2.6.7
     */
    class Toolset_Association_Query_Condition_Has_Intermediary_Id extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition
    {
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
    }
    /**
     * Condition to query associations by a specific relationship (row) ID.
     *
     * @since 2.5.8
     */
    class Toolset_Association_Query_Condition_Relationship_Id extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition
    {
        /**
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition_Relationship_Id
         * constructor.
         *
         * @param int $relationship_id
         * @param \IToolset_Relationship_Definition|null $relationship_definition Optional, pass only when already available
         *     to allow additional optimizations.
         *
         * @throws \InvalidArgumentException When an obviously invalid relationship ID is provided.
         */
        public function __construct($relationship_id, \IToolset_Relationship_Definition $relationship_definition = null)
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
        /**
         * Returns condition operator
         *
         * @return string
         * @since m2m
         */
        protected function get_operator()
        {
        }
        /**
         * @return \IToolset_Relationship_Definition|null
         */
        public function get_relationship_definition()
        {
        }
    }
    /**
     * Condition to query associations by a status of an element in a particular role.
     *
     * Allows querying for a specific status or for a set of statuses that may be
     * depending on other circumstances (e.g. capabilities of the current user).
     *
     * Note that the functionality may be different per each domain. Currently, only posts
     * are supported.
     *
     * @since 2.5.8
     */
    class Toolset_Association_Query_Condition_Element_Status extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition
    {
        /**
         * @deprecated Use ElementStatusCondition instead.
         * @var string
         */
        const STATUS_AVAILABLE = 'is_available';
        /**
         * @deprecated Use ElementStatusCondition instead.
         * @var string
         */
        const STATUS_PUBLIC = 'is_public';
        /**
         * @deprecated Use ElementStatusCondition instead.
         * @var string
         */
        const STATUS_ANY = 'any';
        /**
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition_Element_Status
         * constructor.
         *
         * @param string|string[] $statuses One or more status values.
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param Toolset_Association_Query_Table_Join_Manager $join_manager
         * @param Toolset_Association_Query_Element_Selector_Provider $element_selector_provider
         * @param \OTGS\Toolset\Common\PostStatus $post_status
         */
        public function __construct($statuses, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Element_Selector_Provider $element_selector_provider, \OTGS\Toolset\Common\PostStatus $post_status)
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
    }
    /**
     * Condition to query associations by a particular element involved in a particular role.
     *
     * Warning: This is not WPML-aware query. It simply compares the provided ID with the ID in
     * the correct column in the associations table. In most cases, you will need the translation
     * mechanism to be involved and use OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition_Element_Id_And_Domain
     * instead.
     *
     * @since 2.5.8
     */
    class Toolset_Association_Query_Condition_Element_Id extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition
    {
        /**
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition_Element_Id
         * constructor.
         *
         * @param int $element_id
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Element_Selector_Provider $element_selector_provider
         *
         * @throws \InvalidArgumentException
         */
        public function __construct($element_id, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Element_Selector_Provider $element_selector_provider)
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
    }
    /**
     * Query associations by the domain of selected role.
     *
     * @since 2.5.8
     */
    class Toolset_Association_Query_Condition_Has_Domain extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition
    {
        /**
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition_Has_Active_Relationship
         * constructor.
         *
         * @param string $domain
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role
         * @param Toolset_Association_Query_Table_Join_Manager $join_manager
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Relationship_Database_Operations|null $database_operations_di
         */
        public function __construct($domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Relationship_Database_Operations $database_operations_di = null)
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
        /**
         * @return string The element domain set on this condition.
         * @since 2.5.10
         */
        public function get_domain()
        {
        }
        public function get_for_role()
        {
        }
    }
    /**
     * Condition to filter results by element domain and type at the same time.
     *
     * Actually, this doesn't do anything but to tie those two together so that the association query
     * can perform some more advanced optimizations.
     *
     * @since m2m
     */
    class Toolset_Association_Query_Condition_Has_Domain_And_Type extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition
    {
        /**
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition_Has_Type constructor.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role
         * @param string $domain
         * @param string $type
         * @param Toolset_Association_Query_Table_Join_Manager $join_manager
         * @param \Toolset_Relationship_Database_Unique_Table_Alias $unique_table_alias
         * @param Toolset_Association_Query_Condition_Factory $condition_factory
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role, $domain, $type, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager, \Toolset_Relationship_Database_Unique_Table_Alias $unique_table_alias, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition_Factory $condition_factory)
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
        /**
         * @inheritdoc
         *
         * @return string
         */
        public function get_join_clause()
        {
        }
        /**
         * @return string The element domain set in this condition.
         */
        public function get_domain()
        {
        }
        /**
         * @return string The element type set in this condition.
         */
        public function get_type()
        {
        }
        /**
         * @return \IToolset_Relationship_Role
         */
        public function get_for_role()
        {
        }
    }
    /**
     * Condition to query associations without intermediary post. Needed when fields are added and association have to be
     * updated.
     *
     * @refactoring this is being used directly, instead of having a method in the association query.
     * @since 2.5.8
     */
    class Toolset_Association_Query_Condition_Empty_Intermediary extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition
    {
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
    }
    /**
     * Query associations by the origin value of a relationship they belong to.
     *
     * @since m2m
     */
    class Toolset_Association_Query_Condition_Relationship_Origin extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition
    {
        /**
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition_Has_Active_Relationship
         * constructor.
         *
         * @param bool $expected_value
         * @param Toolset_Association_Query_Table_Join_Manager $join_manager
         */
        public function __construct($expected_value, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager)
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
    }
    /**
     * Interface OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\IToolset_Association_Query_Result_Transformation
     *
     * Object that performs a transformation of a single database row from the
     * association query into a the desired result.
     *
     * @since 2.5.8
     */
    interface IToolset_Association_Query_Result_Transformation
    {
        /**
         * @param object $database_row It is safe to expect only properties that are always
         *     preset in results of a query from OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Sql_Expression_Builder.
         *
         * @param IToolset_Association_Query_Element_Selector $element_selector
         *
         * @return mixed
         */
        public function transform($database_row, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\IToolset_Association_Query_Element_Selector $element_selector);
        /**
         * Talk to the element selector so that it includes only elements that are actually needed.
         *
         * @param IToolset_Association_Query_Element_Selector $element_selector
         *
         * @return void
         * @since 2.5.10
         */
        public function request_element_selection(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\IToolset_Association_Query_Element_Selector $element_selector);
        /**
         * Determine what roles *may* need to be included in the results.
         *
         * That means, if a role is not returned by this method, it will definitely *not* be needed during the result
         * transformation. It doesn't work the opposite way, though.
         *
         * @return \IToolset_Relationship_Role[]
         */
        public function get_maximum_requested_roles();
    }
    /**
     * Transform association query results grouped by role, it requires another transformation class to return the
     * transformation
     *
     * @since 3.0.9
     */
    class Toolset_Association_Query_Result_Transformation_Element_Per_Role implements \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\IToolset_Association_Query_Result_Transformation
    {
        /**
         * Toolset_Association_Query_Result_Transformation_Per_Role constructor.
         *
         * @param Toolset_Association_Query_Result_Transformation_Factory $result_transformation_factory
         * @param Toolset_Association_Query_V2 $query
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Result_Transformation_Factory $result_transformation_factory, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_V2 $query)
        {
        }
        /**
         * @param \IToolset_Relationship_Role $role
         *
         * @return $this
         */
        public function return_element_ids(\IToolset_Relationship_Role $role)
        {
        }
        /**
         * @param \IToolset_Relationship_Role $role
         *
         * @return $this
         */
        public function return_element_instances(\IToolset_Relationship_Role $role)
        {
        }
        /**
         * Return the query object by which this transformation class has been created, so that it is possible to continue
         * method chaining.
         *
         * @return Toolset_Association_Query_V2
         */
        public function done()
        {
        }
        /**
         * @param object $database_row It is safe to expect only properties that are always
         *     preset in results of a query from OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Sql_Expression_Builder.
         *
         * @param IToolset_Association_Query_Element_Selector $element_selector
         *
         * @return mixed
         */
        public function transform($database_row, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\IToolset_Association_Query_Element_Selector $element_selector)
        {
        }
        /**
         * Talk to the element selector so that it includes only elements that are actually needed.
         *
         * @param IToolset_Association_Query_Element_Selector $element_selector
         *
         * @return void
         * @since 2.5.10
         */
        public function request_element_selection(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\IToolset_Association_Query_Element_Selector $element_selector)
        {
        }
        /**
         * @inheritDoc
         *
         * @return \IToolset_Relationship_Role[]
         */
        public function get_maximum_requested_roles()
        {
        }
    }
    /**
     * Transform association query results into instances of IToolset_Association.
     *
     * @since 2.5.8
     */
    class Toolset_Association_Query_Result_Transformation_Association_Instance implements \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\IToolset_Association_Query_Result_Transformation
    {
        /**
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Result_Transformation_Association_Instance
         * constructor.
         *
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Translator|null $association_translator_di
         * @param \Toolset_WPML_Compatibility|null $wpml_service
         * @param \Toolset_Condition_Plugin_Wpml_Is_Current_Language_Default|null $is_current_language_default_di
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Translator $association_translator_di = null, \Toolset_WPML_Compatibility $wpml_service = null, \Toolset_Condition_Plugin_Wpml_Is_Current_Language_Default $is_current_language_default_di = null)
        {
        }
        /**
         * @inheritdoc
         *
         * @param object $database_row
         *
         * @return \IToolset_Association
         */
        public function transform($database_row, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\IToolset_Association_Query_Element_Selector $element_selector)
        {
        }
        /**
         * Talk to the element selector so that it includes only elements that are actually needed.
         *
         * @param IToolset_Association_Query_Element_Selector $element_selector
         *
         * @return void
         * @since 2.5.10
         */
        public function request_element_selection(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\IToolset_Association_Query_Element_Selector $element_selector)
        {
        }
        /**
         * @inheritDoc
         * @return \IToolset_Relationship_Role[]
         */
        public function get_maximum_requested_roles()
        {
        }
    }
    /**
     * Transform association query results into element IDs of chosen role.
     *
     * @since 2.5.8
     */
    class Toolset_Association_Query_Result_Transformation_Element_Id implements \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\IToolset_Association_Query_Result_Transformation
    {
        /**
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Result_Transformation_Element_Id
         * constructor.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role)
        {
        }
        /**
         * @inheritdoc
         *
         * @param object $database_row
         *
         * @return int
         */
        public function transform($database_row, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\IToolset_Association_Query_Element_Selector $element_selector)
        {
        }
        /**
         * Talk to the element selector so that it includes only elements that are actually needed.
         *
         * @param IToolset_Association_Query_Element_Selector $element_selector
         *
         * @since 2.5.10
         */
        public function request_element_selection(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\IToolset_Association_Query_Element_Selector $element_selector)
        {
        }
        /**
         * @inheritDoc
         * @return \IToolset_Relationship_Role[]
         */
        public function get_maximum_requested_roles()
        {
        }
    }
    /**
     * Transform association query results into association UIDs.
     *
     * @since 2.5.8
     */
    class Toolset_Association_Query_Result_Transformation_Association_Uid implements \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\IToolset_Association_Query_Result_Transformation
    {
        /**
         * @inheritdoc
         *
         * @param object $database_row
         *
         * @return int
         */
        public function transform($database_row, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\IToolset_Association_Query_Element_Selector $element_selector)
        {
        }
        /**
         * Talk to the element selector so that it includes only elements that are actually needed.
         *
         * @param IToolset_Association_Query_Element_Selector $element_selector
         *
         * @return void
         * @since 2.5.10
         */
        public function request_element_selection(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\IToolset_Association_Query_Element_Selector $element_selector)
        {
        }
        /**
         * @inheritDoc
         * @return \IToolset_Relationship_Role[]
         */
        public function get_maximum_requested_roles()
        {
        }
    }
    /**
     * Transform association query results into instances of elements of the chosen role.
     *
     * Note: At the moment, only the posts domain is supported.
     *
     * @since 2.5.8
     */
    class Toolset_Association_Query_Result_Transformation_Element_Instance implements \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\IToolset_Association_Query_Result_Transformation
    {
        /**
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Result_Transformation_Element_Instance
         * constructor.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         * @param \Toolset_Element_Factory|null $element_factory_di
         * @param \Toolset_WPML_Compatibility|null $wpml_service_di
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role, \Toolset_Element_Factory $element_factory_di = null, \Toolset_WPML_Compatibility $wpml_service_di = null)
        {
        }
        /**
         * @inheritdoc
         *
         * Note: This will require some adjustments when other element domains are supported.
         * The best course will be to instruct $element_selector to also include the relationships
         * table in request_element_selection() and then obtain the domain information from there.
         *
         * @param object $database_row
         *
         * @return \IToolset_Element
         */
        public function transform($database_row, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\IToolset_Association_Query_Element_Selector $element_selector)
        {
        }
        /**
         * Talk to the element selector so that it includes only elements that are actually needed.
         *
         * @param IToolset_Association_Query_Element_Selector $element_selector
         *
         * @since 2.5.10
         */
        public function request_element_selection(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\IToolset_Association_Query_Element_Selector $element_selector)
        {
        }
        /**
         * @inheritDoc
         * @return \IToolset_Relationship_Role[]
         */
        public function get_maximum_requested_roles()
        {
        }
    }
    /**
     * Factory for the
     * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\IToolset_Association_Query_Result_Transformation classes.
     *
     * @since 3.0.9
     */
    class Toolset_Association_Query_Result_Transformation_Factory
    {
        /**
         * @return Toolset_Association_Query_Result_Transformation_Association_Instance
         */
        public function association_instance()
        {
        }
        /**
         * @return Toolset_Association_Query_Result_Transformation_Association_Uid
         */
        public function association_uids()
        {
        }
        /**
         * @param Toolset_Association_Query_V2 $associaton_query Query instance where the transformation class will be used.
         *
         * @return Toolset_Association_Query_Result_Transformation_Element_Per_Role
         */
        public function element_per_role(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_V2 $associaton_query)
        {
        }
        /**
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         *
         * @return Toolset_Association_Query_Result_Transformation_Element_Id
         */
        public function element_ids(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role)
        {
        }
        /**
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         *
         * @return Toolset_Association_Query_Result_Transformation_Element_Instance
         */
        public function element_instances(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role)
        {
        }
    }
    /**
     * Represents an object that can restrict the association query in certain cases,
     * making it less complex and more performant.
     *
     * Note: No implementation yet, but ideas:
     * - disable the WPML version of element selector when it's not needed
     *     - the domain of all elements is known to be something non-translatable
     *     - the post types involved are all known and non-translatable
     * - if the above is true only for one role, make the element selector use the non-WPML way
     *     only for that role
     * - etc.
     *
     * @since 2.5.10
     */
    interface IToolset_Association_Query_Restriction
    {
        /**
         * Apply the restrictions.
         *
         * @return void
         */
        public function apply();
        /**
         * Clear the restrictions after the query has been run.
         *
         * @return void
         */
        public function clear();
    }
    /**
     * Factory for OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\IToolset_Association_Query_Orderby.
     */
    class Toolset_Association_Query_Orderby_Factory
    {
        /**
         * @return IToolset_Association_Query_Orderby
         */
        public function nothing()
        {
        }
        /**
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         * @param Toolset_Association_Query_Table_Join_Manager $join_manager
         *
         * @return IToolset_Association_Query_Orderby
         */
        public function title(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager)
        {
        }
        /**
         * @param string $meta_key
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         * @param Toolset_Association_Query_Table_Join_Manager $join_manager
         * @param string|null $cast_to If the metakey needs to be casted into a different type
         *
         * @return IToolset_Association_Query_Orderby
         */
        public function postmeta($meta_key, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager $join_manager, $cast_to = null)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\WpQueryExtension {
    /**
     * Shared functionality for the toolset_relationships WP_Query extension
     * when relationships are active (non-legacy mode).
     *
     * @see \OTGS\Toolset\Common\WpQueryExtension\AbstractRelationshipsExtension
     * @since 4.0 (some code extracted from Version1\Toolset_Wp_Query_Adjustments_M2m)
     */
    abstract class AbstractRelationshipsExtension extends \OTGS\Toolset\Common\WpQueryExtension\AbstractRelationshipsExtension
    {
        /** @var \Toolset_Element_Factory */
        protected $element_factory;
        /** @var \Toolset_Relationship_Definition_Repository */
        protected $definition_repository;
        /** @var \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory */
        protected $database_layer_factory;
        /**
         * AbstractRelationshipsExtension constructor.
         *
         * @param \wpdb $wpdb
         * @param \Toolset_Element_Factory $element_factory
         * @param \Toolset_Relationship_Definition_Repository $definition_repository
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory $database_layer_factory
         */
        public function __construct(\wpdb $wpdb, \Toolset_Element_Factory $element_factory, \Toolset_Relationship_Definition_Repository $definition_repository, \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory $database_layer_factory)
        {
        }
        /**
         * Get the actual JOIN clause for the whole toolset_relationships query argument.
         *
         * The output needs to be safe to append to a pre-existing JOIN statement.
         *
         * @param \WP_Query $wp_query
         *
         * @return string
         */
        abstract protected function get_join_clause(\WP_Query $wp_query);
        /**
         * Get the WHERE clause for a given sub-query.
         *
         * The output needs to be safe to append to a pre-existing WHERE statement.
         *
         * @param \WP_Query $wp_query
         * @param string $relationship_slug
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role_to_return
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role_to_query_by
         * @param \IToolset_Post $related_to_post
         *
         * @return string
         */
        abstract protected function get_where_clause(\WP_Query $wp_query, $relationship_slug, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role_to_return, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role_to_query_by, \IToolset_Post $related_to_post);
        /**
         * @inheritdoc
         */
        public function initialize()
        {
        }
        /**
         * Add conditions to the WHERE clause.
         *
         * @param string $where
         * @param \WP_Query $wp_query
         *
         * @return string
         */
        public function posts_where($where, $wp_query)
        {
        }
        /**
         * Add tables to the JOIN clause.
         *
         * @param string $join
         * @param \WP_Query $wp_query
         *
         * @return string
         */
        public function posts_join($join, $wp_query)
        {
        }
        /**
         * Check the postmeta query arguments and if we detect an understandable usage of
         * the legacy relationship postmeta, transform it into a toolset_relationships query.
         *
         * We check for:
         * - meta_key and meta_value or meta_value_num
         * - meta_query
         *
         * There are several limitations as to what we can parse:
         *
         * - Only legacy (migrated) relationships are supported.
         * - It must be possible to determine a single relationship from the information passed to the query:
         *     - The postmeta already contains the parent post type slug.
         *     - For the child slug, either there's some information in the "post_type" query argument, or
         *       we check against all post types.
         *
         *       For example, if there are legacy relationships between CPTS: A >> B, A >> C, B >> C,
         *
         *       we will always succeed with a meta_query for "_wpcf_belongs_b_id" (because there is only a
         *       single relationship that has B post type as a parent), but we will succeed with
         *       "_wpcf_belongs_a_id" only if the query also contains a post_type argument that doesn't contain
         *       both B and C post types.
         *
         *       Non-legacy relationships are completely ignored here.
         * - Only the topmost level of the 'meta_query' is processed, and we ignore anything nested.
         * - 'meta_compare' argument or 'compare' within 'meta_query' must be '=' (or missing, since this is the default
         * value)
         * - 'relation' within 'meta_query' must be 'AND' (or missing).
         *
         * If we hit any of these limitations, we don't do anything and let the query run as-is.
         *
         * Otherwise, the condition is turned into a toolset_relationships one and removed.
         *
         * Note that we also remove meta_key together with meta_value/meta_value_num, which is necessary for the
         * query to yield correct results. meta_key might be *theoretically* used for ordering, but it makes
         * very little sense to order by IDs of parent post, so we take the risk.
         *
         * This is WPML-compatible and should yield results according to the allowed translation mode of post types
         * involved in a relationship.
         *
         * @param \WP_Query $query
         *
         * @since 2.6.4
         */
        public function process_legacy_meta_query($query)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1 {
    /**
     * Adjust the WP_Query functionality for m2m relationships.
     *
     * This assumes m2m is enabled.
     *
     * See the superclass for details.
     *
     * Additionally, we also check for meta_key, meta_value, meta_value_num and meta_query
     * for the legacy relationship postmeta and try to transform it into a toolset_relationships condition.
     * See process_legacy_meta_query() for details.
     *
     * @since 2.6.1
     */
    class Toolset_Wp_Query_Adjustments_M2m extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\WpQueryExtension\AbstractRelationshipsExtension
    {
        /**
         * Get the table join manager object attached to the WP_Query instance or create and attach a new one.
         *
         * @param \WP_Query $query
         *
         * @return Toolset_Wp_Query_Adjustments_Table_Join_Manager
         */
        protected function get_table_join_manager(\WP_Query $query)
        {
        }
        protected function get_join_clause(\WP_Query $wp_query)
        {
        }
        protected function get_where_clause(\WP_Query $wp_query, $relationship_slug, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role_to_return, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role_to_query_by, \IToolset_Post $related_to_post)
        {
        }
    }
    /**
     * Collect the JOINed tables in Toolset_Wp_Query_Adjustments_M2m and generate the JOIN clause.
     *
     * @since 2.6.1
     */
    class Toolset_Wp_Query_Adjustments_Table_Join_Manager extends \Toolset_Wpdb_User
    {
        /**
         * Toolset_Wp_Query_Adjustments_Table_Join_Manager constructor.
         *
         * @param \wpdb|null $wpdb_di
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\UniqueTableAlias|null $unique_table_alias_di
         * @param \Toolset_Relationship_Table_Name|null $table_name_di
         * @param Toolset_Relationship_Database_Operations|null $database_operations_di
         * @param \Toolset_Relationship_Definition_Repository|null $definition_repository_di
         * @param \OTGS\Toolset\Common\WPML\WpmlService|null $wpml_service_di
         */
        public function __construct(\wpdb $wpdb_di = null, \OTGS\Toolset\Common\Relationships\DatabaseLayer\UniqueTableAlias $unique_table_alias_di = null, \Toolset_Relationship_Table_Name $table_name_di = null, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Relationship_Database_Operations $database_operations_di = null, \Toolset_Relationship_Definition_Repository $definition_repository_di = null, \OTGS\Toolset\Common\WPML\WpmlService $wpml_service_di = null)
        {
        }
        /**
         * Generate the JOIN clause based on previously made requests for table aliases.
         *
         * @return string
         */
        public function get_join_clauses()
        {
        }
        /**
         * Request an alias for the associations table.
         *
         * Each call will cause a new JOIN and return a new unique table alias.
         *
         * The table will be JOINed on wp_posts.ID by a given relationship slug and element role.
         *
         * @param string $relationship_slug
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role_to_return
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role_to_query_by
         * @param $query_by_element_id
         *
         * @return string
         */
        public function associations_table($relationship_slug, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role_to_return, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role_to_query_by, $query_by_element_id)
        {
        }
    }
    /**
     * Factory for the Toolset_Wp_Query_Adjustments_Table_Join_Manager class.
     *
     * @since 2.6.3
     */
    class Toolset_Wp_Query_Adjustments_Table_Join_Manager_Factory
    {
        public function create()
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Cleanup {
    /**
     * Interface for a class that handles a cleanup after a single post has been deleted.
     *
     * Needs to be hooked to the before_delete_post and after_delete_post actions (which
     * should be happening in Toolset_Relationship_Controller).
     *
     * Please refer to individual implementations as this is very different for each
     * database layer version.
     *
     * @since 4.0
     */
    interface PostCleanupInterface
    {
        /**
         * Clean up affected associations before a post is permanently deleted.
         *
         * @param int $post_id
         * @return void
         */
        public function cleanup_before_delete($post_id);
        /**
         * Perform necessary clean-up after a post has been permanently deleted.
         *
         * @param int $post_id
         * @return void
         */
        public function cleanup_after_delete($post_id);
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Cleanup {
    /**
     * Perform a cleanup after a single post has been deleted.
     *
     * Needs to be hooked to the before_delete_post action.
     *
     * Short version:
     *
     * This situation is much more tricky than when just deleting a single association. One post
     * can be involved in many associations and deleting those might trigger also deleting of
     * intermediary posts and their translations.
     *
     * Long version:
     *
     * Associations themselves can be handled with a single MySQL query,
     * but for deleting intermediary posts, we have to perform consecutive wp_delete_post() calls,
     * which in turn may trigger further deletions if those intermediary posts are translated
     * to more languages.
     *
     * We simply cannot afford to delete all intermediary posts at once, because that might be
     * easily much more than the server can handle, and we can't immediately show a
     * batch deletion dialog because we don't know in which context the initial post is deleted.
     * It may be even during an AJAX call or whatnot.
     *
     * The problem is that we don't want to have lingering intermediary posts because the user
     * might use them in a View, for example, and assume that an intermediary post == an association.
     *
     * Here, a compromise solution is implemented: We immediately delete a certain number of
     * intermediary posts, which will cover 99% of these cases, and for the remaining 1%
     * of big deletions, offer a clean-up routine on the Toolset Troubleshooting page.
     *
     * If we detect that such a cleanup is needed, we'll display a notice until the user goes
     * to the troubleshooting page and clicks the button.
     *
     * On top of that, a CRON job will be created to complete the cleanup if the user doesn't
     * take action soon enough.
     *
     * @since 2.5.10
     */
    class Toolset_Association_Cleanup_Post extends \Toolset_Wpdb_User implements \OTGS\Toolset\Common\Relationships\DatabaseLayer\Cleanup\PostCleanupInterface
    {
        /**
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Cleanup\Toolset_Association_Cleanup_Post constructor.
         *
         * @param \Toolset_Element_Factory|null $element_factory_di
         * @param \wpdb|null $wpdb_di
         * @param \Toolset_Cron|null $cron_di
         * @param \Toolset_Association_Cleanup_Factory|null $cleanup_factory_di
         * @param \Toolset_Association_Intermediary_Post_Persistence|null $intermediary_post_persistence_di
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory $database_layer_factory
         */
        public function __construct(\Toolset_Association_Cleanup_Factory $cleanup_factory_di, \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory $database_layer_factory, \Toolset_Element_Factory $element_factory_di = null, \wpdb $wpdb_di = null, \Toolset_Cron $cron_di = null, \Toolset_Association_Intermediary_Post_Persistence $intermediary_post_persistence_di = null)
        {
        }
        /**
         * Clean up affected associations before a post is permanently deleted.
         *
         * @param int $post_id
         */
        public function cleanup_before_delete($post_id)
        {
        }
        public function cleanup_after_delete($post_id)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1 {
    /**
     * Holds helper methods related to native Toolset associations.
     *
     * Throughout m2m API, only these classes should directly touch the database:
     *
     * - Toolset_Relationship_Database_Operations
     * - Toolset_Relationship_Migration_Controller
     * - Toolset_Relationship_Driver
     * - Toolset_Relationship_Translation_View_Management
     * - Toolset_Association_Query
     *
     * @since m2m
     */
    class Toolset_Relationship_Database_Operations implements \OTGS\Toolset\Common\Relationships\API\AssociationDatabaseOperations
    {
        public function __construct(\wpdb $wpdb_di = null, \Toolset_Relationship_Table_Name $table_name_di = null, \Toolset_WPML_Compatibility $wpml_service_di = null)
        {
        }
        /**
         * Careful. This class is NOT meant to be a singleton. This is a temporary solution for easier transition
         * from using static methods.
         *
         * @return Toolset_Relationship_Database_Operations
         */
        public static function get_instance()
        {
        }
        /**
         * Create new association and persist it.
         *
         * From outside of the m2m API, use Toolset_Relationship_Definition::create_association().
         *
         * @param \Toolset_Relationship_Definition|string $relationship_definition_source Can also contain slug of
         *     existing relationship definition.
         * @param int|\Toolset_Element|\WP_Post $parent_source
         * @param int|\Toolset_Element|\WP_Post $child_source
         * @param int $intermediary_id
         * @param bool $instantiate Whether to create an instance of the newly created association
         *     or only return a result on success
         *
         * @return \IToolset_Association|\Toolset_Result
         * @throws \Toolset_Element_Exception_Element_Doesnt_Exist
         * @since m2m
         */
        public function create_association($relationship_definition_source, $parent_source, $child_source, $intermediary_id, $instantiate = true)
        {
        }
        // The _id columns in the associations table
        const COLUMN_ID = '_id';
        // Columns in the relationships table
        const COLUMN_DOMAIN = '_domain';
        const COLUMN_TYPES = '_types';
        const COLUMN_CARDINALITY_MAX = 'cardinality_%s_max';
        const COLUMN_CARDINALITY_MIN = 'cardinality_%s_min';
        /**
         * For a given role name, return the corresponding column in the associations table.
         *
         * @param string|\IToolset_Relationship_Role $role
         * @param string $column
         *
         * @return string
         * @since m2m
         */
        public function role_to_column($role, $column = self::COLUMN_ID)
        {
        }
        /**
         * Update the database to support the native m2m implementation.
         *
         * Practically that means creating the wp_toolset_associations table.
         *
         * @return \Toolset_Result_Set
         * @since m2m
         */
        public function do_native_dbdelta()
        {
        }
        /**
         * Determine if a table exists in the database.
         *
         * @param string $table_name
         *
         * @return bool
         * @since m2m
         */
        public function table_exists($table_name)
        {
        }
        /**
         * When a relationship definition slug is renamed, update the association table (where the slug is used as a
         * foreign key).
         *
         * The usage of this method is strictly limited to the m2m API, always change the slug via
         * Toolset_Relationship_Definition_Repository::change_definition_slug().
         *
         * @param \IToolset_Relationship_Definition $old_definition
         * @param \IToolset_Relationship_Definition $new_definition
         *
         * @return \Toolset_Result
         *
         * @since m2m
         */
        public function update_associations_on_definition_renaming(\IToolset_Relationship_Definition $old_definition, \IToolset_Relationship_Definition $new_definition)
        {
        }
        /**
         * Delete all associations from a given relationship.
         *
         * @param int $relationship_row_id
         *
         * @return \Toolset_Result_Updated
         */
        public function delete_associations_by_relationship($relationship_row_id)
        {
        }
        /**
         * Updates association intermediary post
         *
         * @param int $association_id Association trID
         * @param int $intermediary_id New intermediary ID
         *
         * @since m2m
         */
        public function update_association_intermediary_id($association_id, $intermediary_id)
        {
        }
        /**
         * Returns the maximun number of associations of a relationship for a parent id and a child id
         *
         * @param int $relationship_id Relationship ID.
         * @param string $role_name Role name.
         *
         * @return int
         * @throws \InvalidArgumentException In case of error.
         */
        public function count_max_associations($relationship_id, $role_name)
        {
        }
        /**
         * Delete all associations of a given relationships that have the given element in the given role.
         *
         * @param \IToolset_Relationship_Definition $relationship
         * @param string $element_role_name
         * @param int $element_id
         */
        public function delete_associations_by_element($relationship, $element_role_name, $element_id)
        {
        }
        public function delete_association_by_element_in_any_role(\IToolset_Element $element)
        {
        }
        /**
         * Delete intermediary posts from all associations in a given relationship that have
         * the given element in the given role.
         *
         * @param \IToolset_Relationship_Definition $relationship
         * @param string $element_role_name
         * @param int $element_id
         */
        public function delete_intermediary_posts_by_element($relationship, $element_role_name, $element_id)
        {
        }
        /**
         * @inheritDoc
         */
        public function delete_association(\IToolset_Association $association)
        {
        }
        public function get_dangling_intermediary_posts(array $intermediary_post_types, array $post_types_to_delete_by)
        {
        }
        /**
         * @inheritDoc
         */
        public function requires_default_language_post()
        {
        }
    }
    /**
     * Manages the migration from legacy post relationships to m2m data structures.
     *
     * The install_m2m() method is to be called once on TCL upgrade.
     *
     * @since m2m
     *
     * WARNING, this class may be used outside of the DatabaseLayer\Version1 namespace in some cases.
     * The migration from legacy post relationships has been implemented specifically into the first version
     * of the m2m database structure.
     */
    class Toolset_Relationship_Migration_Controller extends \Toolset_Wpdb_User
    {
        const MESSAGE_SEPARATOR = "\n> ";
        /**
         * Toolset_Relationship_Migration_Controller constructor.
         *
         * @param \wpdb|null $wpdb_di
         * @param Toolset_Relationship_Database_Operations|null $database_operations_di
         * @param \Toolset_Relationship_Definition_Repository|null $relationship_definition_repository_di
         * @param Toolset_Relationship_Migration_Associations|null $association_migrator_di
         * @param \Toolset_Relationship_Table_Name|null $table_name_di
         * @param \Toolset_Post_Type_Repository|null $post_type_repository_di
         * @param \Toolset_WPML_Compatibility|null $wpml_service_di
         */
        public function __construct(\wpdb $wpdb_di = null, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Relationship_Database_Operations $database_operations_di = null, \Toolset_Relationship_Definition_Repository $relationship_definition_repository_di = null, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Relationship_Migration_Associations $association_migrator_di = null, \Toolset_Relationship_Table_Name $table_name_di = null, \Toolset_Post_Type_Repository $post_type_repository_di = null, \Toolset_WPML_Compatibility $wpml_service_di = null)
        {
        }
        /**
         * Update the database to support the native m2m implementation.
         *
         * Practically that means creating the wp_toolset_associations table.
         *
         * @since m2m
         *
         * @refactoring TODO is it possible to reliably detect dbDelta failure?
         */
        public function do_native_dbdelta()
        {
        }
        /**
         * If it's enabled by filter, drop all m2m-related tables.
         *
         * Useful mainly when debugging the migration process.
         *
         * @return \Toolset_Result|\Toolset_Result_Set
         * @since m2m
         */
        public function maybe_drop_m2m_tables()
        {
        }
        /**
         * Read legacy post relationship settings and convert them into one-to-many relationship definitions.
         *
         * Relationship slugs will be {$parent_post_type}_{$child_post_type}. Overwrites existing definitions.
         *
         * @param bool $adjust_translation_mode
         *
         * @return \Toolset_Result_Set
         * @since m2m
         */
        public function migrate_relationship_definitions($adjust_translation_mode)
        {
        }
        /**
         * Read the legacy relationships data stored in an option and transform it into array that can be
         * processed more easily.
         *
         * @return array[] Each item is an array with 'parent' and 'child' post type, and also with a proposed 'slug'
         *     for the relationship definition.
         * @since m2m
         */
        public function get_legacy_relationship_post_type_pairs()
        {
        }
        /**
         * Migrate post relationship data from the old Types post relationships to the native m2m.
         *
         * @param int $offset
         * @param int $limit
         * @param bool $create_default_language_if_missing
         * @param bool $copy_post_content_when_creating
         *
         * @return \Toolset_Result_Updated|\Toolset_Result_Set
         * @since m2m
         *
         */
        public function migrate_associations($offset, $limit, $create_default_language_if_missing, $copy_post_content_when_creating)
        {
        }
        /**
         * Read a batch of legacy association data and prepare it for migration.
         *
         * @param int $offset
         * @param int $limit
         *
         * @return array[] Each element has 'parent_id', 'child_id' and a 'relationship_slug'.
         * @since m2m
         */
        public function get_associations_to_migrate($offset, $limit)
        {
        }
        /**
         * Final migration step.
         *
         * @since m2m
         */
        public function finish()
        {
        }
    }
    /**
     * Helper class for migrating a single legacy association between two posts into m2m.
     *
     * Not to be used outside the m2m API.
     *
     * @since m2m
     */
    class Toolset_Relationship_Migration_Associations
    {
        /**
         * Toolset_Relationship_Migration_Associations constructor.
         *
         * @param \Toolset_Relationship_Definition_Repository $definition_repository
         * @param bool $create_default_language_if_missing
         * @param bool $copy_post_content_when_creating
         * @param \Toolset_Element_Factory|null $element_factory_di
         * @param \Toolset_Potential_Association_Query_Factory|null $potential_association_query_factory_di
         * @param bool $do_detailed_logging
         */
        public function __construct(\Toolset_Relationship_Definition_Repository $definition_repository, $create_default_language_if_missing, $copy_post_content_when_creating, \Toolset_Element_Factory $element_factory_di = null, \Toolset_Potential_Association_Query_Factory $potential_association_query_factory_di = null, $do_detailed_logging = true)
        {
        }
        /**
         * @param int $parent_id
         * @param int $child_id
         * @param int $relationship_slug
         *
         * @return \Toolset_Result
         */
        public function migrate_association($parent_id, $child_id, $relationship_slug)
        {
        }
    }
    /**
     * Handle the creation of a default languge post translation during the m2m migration.
     *
     * The behaviour depends on user's settings passed to the constructor.
     *
     * @since 2.5.11
     */
    class Toolset_Relationship_Migration_Post_Translation
    {
        /**
         * Toolset_Relationship_Migration_Post_Translation constructor.
         *
         * @param \IToolset_Post $post
         * @param \IToolset_Post $parent
         * @param \IToolset_Post $child
         * @param $create_default_language_if_missing
         * @param $copy_post_content_when_creating
         * @param \Toolset_WPML_Compatibility|null $wpml_service_di
         */
        public function __construct(\IToolset_Post $post, \IToolset_Post $parent, \IToolset_Post $child, $create_default_language_if_missing, $copy_post_content_when_creating, \Toolset_WPML_Compatibility $wpml_service_di = null)
        {
        }
        /**
         * Check for the missing translation and either create it or report a failure, depending
         * on the user's choice.
         *
         * @return \Toolset_Result
         */
        public function run()
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\PotentialAssociation {
    /**
     * A WP_Query adjustment class that make sure that if a post search string is an exact match for particular posts,
     * these posts will be ordered as first in query results.
     *
     * @since Types 3.1.3
     */
    class PostResultOrder extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\PotentialAssociation\WpQueryAdjustment
    {
        /**
         * Determine whether the WP_Query should be augmented.
         *
         * @return bool
         */
        protected function is_actionable()
        {
        }
        /**
         * Prepend an orderby clause that gives absolute priority to exact matches of post_title and the search string.
         *
         * @param $orderby
         * @param \WP_Query $wp_query
         *
         * @return string
         */
        public function add_orderby_clauses($orderby, \WP_Query $wp_query)
        {
        }
    }
    /**
     * When you have a relationship and a specific element in one role, this
     * query class will help you to find elements that can be associated with it.
     *
     * It takes into account all the aspects, like whether the relationship is distinct or not.
     *
     * This class works for querying posts (disregarding the domain of the element to connect to).
     *
     * Note that relationship cardinality limitation is not checked in get_results(). It is assumed that
     * they've been checked before even querying for posts to associate.
     *
     * @since m2m
     */
    class PostQuery implements \OTGS\Toolset\Common\Relationships\API\PotentialAssociationQuery
    {
        const POST_STATUS_AVAILABLE = 'is_available';
        /** @var \IToolset_Relationship_Definition */
        protected $relationship;
        /** @var \OTGS\Toolset\Common\Relationships\API\RelationshipRole */
        protected $target_role;
        /** @var \IToolset_Element */
        protected $for_element;
        /** @var array */
        protected $args;
        /** @var int|null */
        protected $found_results;
        /** @var \Toolset_Element_Factory */
        protected $element_factory;
        /** @var \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory */
        protected $database_layer_factory;
        /**
         * To be used instead of __return_true() as a filter callback when we're modifying
         * the WP_Query behaviour.
         *
         * @var callable
         * @return bool
         * @since 4.0
         */
        protected $return_true;
        /**
         * Toolset_Potential_Association_Query constructor.
         *
         * @param \IToolset_Relationship_Definition $relationship Relationship to query for.
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $target_role Element role. Only parent
         *     or child are accepted.
         * @param \IToolset_Element $for_element Element that may be connected with the result of the query.
         * @param array $args Additional query arguments:
         *     - search_string: string
         *     - count_results: bool
         *     - items_per_page: int
         *     - page: int
         *     - wp_query_override: array
         *     - exclude_elements: IToolset_Element[] Elements to exclude from the results and when checking
         *       whether the target element ($for_element) can accept another association.
         *     - post_status: string[]|string If provided, it will override the standard value ('publish').
         *     POST_STATUS_AVAILABLE is also being accepted.
         * @param \Toolset_Element_Factory|null $element_factory_di
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory|null $database_layer_factory
         */
        public function __construct(\IToolset_Relationship_Definition $relationship, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $target_role, \IToolset_Element $for_element, $args, \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory $database_layer_factory, \Toolset_Element_Factory $element_factory_di = null)
        {
        }
        /**
         * @param bool $check_can_connect_another_element Check wheter it is possible to connect any other element at all,
         *     and return an empty result if not.
         * @param bool $check_distinct_relationships Exclude elements that would break the "distinct" property of a
         *     relationship. You can set this to false if you're overwriting an existing association.
         *
         * @return \IToolset_Post[]
         * @throws \Toolset_Element_Exception_Element_Doesnt_Exist
         */
        public function get_results($check_can_connect_another_element = true, $check_distinct_relationships = true)
        {
        }
        protected function get_target_post_types()
        {
        }
        protected function needs_found_rows()
        {
        }
        protected function get_page()
        {
        }
        protected function get_post_statuses()
        {
        }
        protected function get_items_per_page()
        {
        }
        /**
         * @return int
         */
        public function get_found_elements()
        {
        }
        /**
         * Check whether a specific single element can be associated.
         *
         * The relationship, target role and the other element are those provided in the constructor.
         *
         * @param \IToolset_Element $association_candidate Element that wants to be associated.
         * @param bool $check_is_already_associated Perform the check that the element is already associated for distinct
         *     relationships. Default is true. Set to false only if the check was performed manually before.
         *
         * @return \OTGS\Toolset\Common\Result\SingleResult Result with an user-friendly message in case the association is denied.
         * @since 2.5.6
         */
        public function check_single_element(\IToolset_Element $association_candidate, $check_is_already_associated = true)
        {
        }
        /**
         * @inheritdoc
         *
         * @param \IToolset_Element $element
         *
         * @return bool
         */
        public function is_element_already_associated(\IToolset_Element $element)
        {
        }
        /**
         * Check whether the element provided in the constructor can accept any new association whatsoever.
         *
         * @return \OTGS\Toolset\Common\Result\SingleResult Result with an user-friendly message in case the association is denied.
         * @since 2.5.6
         */
        public function can_connect_another_element()
        {
        }
        /**
         * Alter WPML behavior directly before running the query.
         *
         * To be overridden when needed.
         *
         * @since 4.0
         */
        protected function alter_wpml_query_hooks_before_query()
        {
        }
        /**
         * Revert any changes made in alter_wpml_query_hooks_before_query().
         *
         * @since 4.0
         */
        protected function alter_wpml_query_hooks_after_query()
        {
        }
    }
    class O2OPostQuery extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\PotentialAssociation\PostQuery
    {
        public function get_results($check_can_connect_another_element = true, $check_distinct_relationships = true)
        {
        }
    }
    class M2OPostQuery extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\PotentialAssociation\PostQuery
    {
        public function get_results($check_can_connect_another_element = true, $check_distinct_relationships = true)
        {
        }
    }
    class O2MPostQuery extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\PotentialAssociation\PostQuery
    {
        public function get_results($check_can_connect_another_element = true, $check_distinct_relationships = true)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer {
    /**
     * Generates unique alias values for a given table name.
     *
     * Works under the assumption that there are no tables with similar names different only by a numeric suffix "_$n". :)
     * The generated values are unique within one class instance.
     *
     * @since 2.5.4
     */
    class UniqueTableAlias
    {
        /**
         * Generate a new unique value
         *
         * @param string $table_name
         * @param bool $always_suffix Add a suffix even if using the table alias for the first time.
         * @param string $additional_suffix Suffix that will be always added at the very end of the alias.
         *     Doesn't guarantee uniqueness, it just can be used to describe the alias semantically.
         *
         * @return string
         */
        public function generate($table_name, $always_suffix = false, $additional_suffix = '')
        {
        }
    }
    /**
     * Constants used internally in the DatabaseLayer sub-namespace.
     */
    abstract class Constants
    {
        /**
         * Delimiter used in GROUP_CONCAT MySQL function.
         */
        const GROUP_CONCAT_DELIMITER = ',';
        /**
         * Filter that can be used to indicate that an intermediary post is deleted
         * purposefully, and that the association shouldn't be removed.
         *
         * @since 2.6.8
         */
        const IS_DELETING_INTERMEDIARY_POST_FILTER = 'toolset_is_deleting_intermediary_post_purposefully';
        const DELETE_POSTS_PER_BATCH = 25;
    }
    /**
     * Provides and manages the version of the relationships database layer.
     *
     * This is the single source of information but it should be used only by the DatabaseLayerFactory
     *
     * @since 4.0
     */
    class DatabaseLayerMode
    {
        /** @var string Name of the option that stores the database layer mode value. */
        const OPTION_NAME = 'toolset_relationship_db_layer';
        /** @var string First version of the database layer (introduced in Types 3.0) */
        const VERSION_1 = 'version1';
        /**
         * @var string Fallback mode of the second version (with full support for translatable associations,
         *     since Types 3.4).
         */
        const FALLBACK = 'version2_fallback';
        /** @var array Represents the second version of the database structure, whatever particular mode is being used. */
        const VERSION_2 = [self::FALLBACK];
        /** @var string[] Valid database layer modes. */
        const VALID_MODES = [self::VERSION_1, self::FALLBACK];
        /**
         * Retrieve the database layer mode.
         *
         * @return string Always a valid mode.
         */
        public function get()
        {
        }
        /**
         * Set a new database layer mode.
         *
         * Use with great caution.
         *
         * @param $new_database_layer_mode
         *
         * @throws \InvalidArgumentException
         */
        public function set($new_database_layer_mode)
        {
        }
        /**
         * Safely compare the given mode value to the current mode.
         *
         * If an array of values is provided, the function returns true if at least one of them matches
         * the current mode.
         *
         * @param string[]|string $mode
         *
         * @return bool
         */
        public function is($mode)
        {
        }
    }
}
namespace {
    interface IToolset_Relationship_Database_Issue
    {
        public function handle();
    }
    /**
     * Handle a missing element that might be involved in a number of associations.
     *
     * This will delete all affected associations and also intermediary posts of such associations.
     * If invalid parameters are provided, the method does nothing.
     *
     * @since 2.5.6
     */
    class Toolset_Relationship_Database_Issue_Missing_Element implements \IToolset_Relationship_Database_Issue
    {
        /**
         * Toolset_Relationship_Database_Issue_Missing_Element constructor.
         *
         * @param string $domain Element domain.
         * @param int $element_id ID of the missing element.
         * @param wpdb|null $wpdb_di
         * @param \OTGS\Toolset\Common\Relationships\API\Factory|null $relationships_factory
         */
        public function __construct($domain, $element_id, \wpdb $wpdb_di = \null, \OTGS\Toolset\Common\Relationships\API\Factory $relationships_factory = \null)
        {
        }
        /**
         * Handle the issue.
         */
        public function handle()
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer {
    /**
     * Checks for the existence of m2m database tables and creates them if they're missing.
     *
     * Optimized not to repeat any actions unless necessary.
     *
     * @since Types 3.3.11
     * @since 4.0 Ported to the Translatable Associations project with support for different database layer versions.
     */
    class TableExistenceCheck
    {
        /**
         * TableExistenceCheck constructor.
         *
         * @param DatabaseLayerMode $database_layer_mode
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerMode $database_layer_mode)
        {
        }
        /**
         * After this method is called, relationship tables ought to exist unless:
         *
         * - The toolset_m2m_skip_table_existence_check was used.
         * - There's something wrong with the database that prevents new tables from being created (which is a basic
         *   requirement of WordPress, so it's safe to assume).
         */
        public function ensure_tables_exist()
        {
        }
    }
    /**
     * Database operations related to relationship definitions.
     *
     * @since 4.0
     */
    class RelationshipDatabaseOperations extends \Toolset_Wpdb_User
    {
        // Columns in the relationships table
        const COLUMN_DOMAIN = '_domain';
        const COLUMN_TYPES = '_types';
        const COLUMN_CARDINALITY_MAX = 'cardinality_%s_max';
        const COLUMN_CARDINALITY_MIN = 'cardinality_%s_min';
        public function __construct(\wpdb $wpdb = null, \Toolset_Relationship_Table_Name $table_name = null)
        {
        }
        /**
         * For a given role name, return the corresponding column in the relationships table.
         *
         * @param string|\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         * @param string $column
         *
         * @return string
         * @deprecated Use RelationshipTable::role_to_column() instead.
         */
        public function role_to_column($role, $column)
        {
        }
        public function load_all_relationships()
        {
        }
        /**
         * Build the part of the SELECT clause that is required for proper loading of a relationship definition.
         *
         * @param string $relationships_table_alias
         * @param string $parent_types_table_alias
         * @param string $child_types_table_alias
         *
         * @return string
         * @since 2.5.4
         */
        public function get_standard_relationships_select_clause($relationships_table_alias = 'relationships', $parent_types_table_alias = 'parent_types_table', $child_types_table_alias = 'child_types_table')
        {
        }
        /**
         * Build the part of the JOIN clause that is required for proper loading of a relationship definition.
         *
         * @param $type_set_table_name
         * @param string $relationships_table_alias
         * @param string $parent_types_table_alias
         * @param string $child_types_table_alias
         *
         * @return string
         * @since 2.5.4
         */
        public function get_standard_relationships_join_clause($type_set_table_name, $relationships_table_alias = 'relationships', $parent_types_table_alias = 'parent_types_table', $child_types_table_alias = 'child_types_table')
        {
        }
        /**
         * Build the part of the GROUP BY clause that is required for proper loading of a relationship definition.
         *
         * @param string $relationships_table_alias
         *
         * @return string
         * @since 2.5.4
         */
        public function get_standards_relationship_group_by_clause($relationships_table_alias = 'relationships')
        {
        }
        /**
         * Update 'type' on 'toolset_type_sets'
         *
         * @param string $new_type
         * @param string $old_type
         *
         * @return \Toolset_Result
         */
        public function update_type_on_type_sets($new_type, $old_type)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableColumns {
    /**
     * Holds names of columns of the relationship table.
     *
     * This is the only place within the DatabaseLayer\Version2 namespace where these values may be hardcoded.
     *
     * @since 4.0
     */
    final class RelationshipTable
    {
        const CURRENT_VERSION = 1;
        const ID = \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\berlindb\PrimaryKeyColumn::COLUMN_NAME;
        const SLUG = 'slug';
        const IS_ACTIVE = 'is_active';
        const NEEDS_LEGACY_SUPPORT = 'needs_legacy_support';
        const PARENT_DOMAIN = 'parent_domain';
        const PARENT_TYPES = 'parent_types';
        const CHILD_DOMAIN = 'child_domain';
        const CHILD_TYPES = 'child_types';
        const DISPLAY_NAME_PLURAL = 'display_name_plural';
        const DISPLAY_NAME_SINGULAR = 'display_name_singular';
        const DRIVER = 'driver';
        const INTERMEDIARY_TYPE = 'intermediary_type';
        const OWNERSHIP = 'ownership';
        const CARDINALITY_PARENT_MAX = 'cardinality_parent_max';
        const CARDINALITY_PARENT_MIN = 'cardinality_parent_min';
        const CARDINALITY_CHILD_MIN = 'cardinality_child_min';
        const CARDINALITY_CHILD_MAX = 'cardinality_child_max';
        const IS_DISTINCT = 'is_distinct';
        const SCOPE = 'scope';
        const ORIGIN = 'origin';
        const ROLE_NAME_PARENT = 'role_name_parent';
        const ROLE_NAME_CHILD = 'role_name_child';
        const ROLE_NAME_INTERMEDIARY = 'role_name_intermediary';
        const ROLE_LABEL_PARENT_SINGULAR = 'role_label_parent_singular';
        const ROLE_LABEL_CHILD_SINGULAR = 'role_label_child_singular';
        const ROLE_LABEL_PARENT_PLURAL = 'role_label_parent_plural';
        const ROLE_LABEL_CHILD_PLURAL = 'role_label_child_plural';
        const AUTODELETE_INTERMEDIARY = 'autodelete_intermediary';
        const COLUMN_TYPE_DOMAIN = 'domain';
        const COLUMN_TYPE_TYPES = 'types';
        const COLUMNS_PER_ROLE = [self::COLUMN_TYPE_DOMAIN => [\Toolset_Relationship_Role::PARENT => self::PARENT_DOMAIN, \Toolset_Relationship_Role::CHILD => self::CHILD_DOMAIN], self::COLUMN_TYPE_TYPES => [\Toolset_Relationship_Role::PARENT => self::PARENT_TYPES, \Toolset_Relationship_Role::CHILD => self::CHILD_TYPES, \Toolset_Relationship_Role::INTERMEDIARY => self::INTERMEDIARY_TYPE]];
        public static function role_to_column(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role, $column_type)
        {
        }
    }
    /**
     * Holds column names of the icl_translations table defined in WPML.
     *
     * These values are expected to never change but we keep them as constant to allow for navigating
     * the codebase by semantics rather than by hardcoded values (like element_id) which can be
     * interpreted in numerous ways.
     *
     * @since 4.0
     */
    final class IclTranslationsTable
    {
        const TRANSLATION_ID = 'translation_id';
        const ELEMENT_ID = 'element_id';
        const LANG_CODE = 'language_code';
        const TRID = 'trid';
        const ELEMENT_TYPE = 'element_type';
        const SOURCE_LANG_CODE = 'source_language_code';
        /** @var string The prefix for posts in the element_type column of the icl_translations table. */
        const POST_ELEMENT_TYPE_PREFIX = 'post_';
    }
    /**
     * Holds names of columns of the connected element table.
     *
     * This is the only place within the DatabaseLayer\Version2 namespace where these values may be hardcoded.
     *
     * @since 4.0
     */
    final class ConnectedElementTable
    {
        const CURRENT_VERSION = 1;
        const ID = \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\berlindb\PrimaryKeyColumn::COLUMN_NAME;
        const GROUP_ID = 'group_id';
        const ELEMENT_ID = 'element_id';
        const DOMAIN = 'domain';
        const WPML_TRID = 'wpml_trid';
        const LANG_CODE = 'lang_code';
    }
    /**
     * Holds names of columns of the type set table.
     *
     * This is the only place within the DatabaseLayer\Version2 namespace where these values may be hardcoded.
     *
     * @since 4.0
     */
    final class TypeSetTable
    {
        const CURRENT_VERSION = 1;
        const ID = \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\berlindb\PrimaryKeyColumn::COLUMN_NAME;
        const SET_ID = 'set_id';
        const TYPE = 'type';
    }
    /**
     * Holds names of columns of the association table.
     *
     * This is the only place within the DatabaseLayer\Version2 namespace where these values may be hardcoded.
     *
     * @since 4.0
     */
    final class AssociationTable
    {
        const CURRENT_VERSION = 1;
        const ID = \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\berlindb\PrimaryKeyColumn::COLUMN_NAME;
        const RELATIONSHIP_ID = 'relationship_id';
        const PARENT_ID = 'parent_id';
        const CHILD_ID = 'child_id';
        const INTERMEDIARY_ID = 'intermediary_id';
        /**
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         *
         * @return string
         */
        public static function role_to_column(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\PotentialAssociationQuery {
    /**
     * Augments WP_Query to check whether the posts can accept another association according to the relationship
     * cardinality.
     *
     * This is used in OTGS\Toolset\Common\Relationships\DatabaseLayer\PotentialAssociation\PostQuery.
     *
     * Both before_query() and after_query() methods need to be called as close to the actual
     * querying as possible, otherwise things will get broken.
     *
     * How this works specifically: We join the connected elements table for the target role on post IDs (while
     * taking into account translatability) and then count how many associations of the given relationship
     * exist for each post. If it's more than the allowed cardinality limit, the post is excluded. Obviously,
     * this is done only when there actually is a limit.
     *
     * @since 4.0
     */
    class CardinalityPostQuery extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\PotentialAssociation\WpQueryAdjustment
    {
        /**
         * CardinalityPostQuery constructor.
         *
         * @param \IToolset_Relationship_Definition $relationship
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $target_role
         * @param \IToolset_Element $for_element
         * @param JoinManager $join_manager
         * @param \OTGS\Toolset\Common\WPML\WpmlService $wpml_service
         * @param \wpdb $wpdb
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names
         */
        public function __construct(\IToolset_Relationship_Definition $relationship, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $target_role, \IToolset_Element $for_element, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\PotentialAssociationQuery\JoinManager $join_manager, \OTGS\Toolset\Common\WPML\WpmlService $wpml_service, \wpdb $wpdb, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names)
        {
        }
        /**
         * @inheritDoc
         */
        protected function is_actionable()
        {
        }
        /**
         * @inheritDoc
         */
        public function add_join_clauses($join)
        {
        }
        /**
         * @inheritDoc
         */
        public function add_where_clauses($where)
        {
        }
    }
    /**
     * Potential association query for posts when using the second version of the database layer mode.
     *
     * Obviously, see the parent class for full context.
     *
     * @since 4.0
     */
    class PostQuery extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\PotentialAssociation\PostQuery
    {
        /**
         * This key can be passed as an argument for the query to influence whether the "display as translated"
         * mode should be enforced for all post types. That may be useful when using the query for the front-end.
         */
        const FORCE_DISPLAY_AS_TRANSLATED_MODE_ARG = 'force_display_as_translated';
        /**
         * @inheritDoci
         */
        protected function alter_wpml_query_hooks_before_query()
        {
        }
        /**
         * @inheritDoc
         */
        protected function alter_wpml_query_hooks_after_query()
        {
        }
    }
    class O2OPostQuery extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\PotentialAssociationQuery\PostQuery
    {
        public function get_results($check_can_connect_another_element = true, $check_distinct_relationships = true)
        {
        }
    }
    class M2OPostQuery extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\PotentialAssociationQuery\PostQuery
    {
        public function get_results($check_can_connect_another_element = true, $check_distinct_relationships = true)
        {
        }
    }
    /**
     * Augments WP_Query to check whether posts are associated with a particular other element ($for_element),
     * and dismisses those posts.
     *
     * This is used in OTGS\Toolset\Common\Relationships\DatabaseLayer\PotentialAssociation\PostQuery to handle distinct
     * relationships - to prevent connecting the same pair of elements twice.
     *
     * Both before_query() and after_query() methods need to be called as close to the actual
     * querying as possible, otherwise things will get broken.
     *
     * How this works specifically: We join a set of tables:
     * - connected elements table (while accounting for translatability) on the post ID
     * - associations table on the target role, with a specific restriction to rows where $for_element is connected
     *   on the opposite role
     *
     * Then we just need to check that there is no association row JOINed (that means $for_element isn't there yet
     * and we're free to create a new association with it).
     *
     * @since 4.0
     */
    class DistinctPostQuery extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\PotentialAssociation\WpQueryAdjustment
    {
        /**
         * @inheritDoc
         */
        protected function is_actionable()
        {
        }
        /**
         * @inheritDoc
         */
        public function add_join_clauses($join)
        {
        }
        /**
         * @inheritDoc
         */
        public function add_where_clauses($where)
        {
        }
    }
    /**
     * Handle the MySQL JOIN clause construction when augmenting the WP_Query in
     * OTGS\Toolset\Common\Relationships\DatabaseLayer\PotentialAssociation\PostQuery.
     *
     * Make sure that JOINs come in the right order and are not duplicated.
     *
     * Note that hook() and unhook() must be called around the WP_Query usage for proper function.
     *
     * @since 4.0
     */
    class JoinManager implements \OTGS\Toolset\Common\Relationships\DatabaseLayer\PotentialAssociation\JoinManager
    {
        // Values that can be used as parameters of the register_join() method.
        //
        //
        const JOIN_CONNECTED_ELEMENT_TABLE_TARGET_ROLE = 'join_connected_element_table_target';
        const JOIN_ASSOCIATIONS_TABLE = 'join_associations_table';
        // Table aliases of JOINed tables that can be used in WHERE clauses.
        //
        //
        const ALIAS_CONNECTED_ELEMENTS_TARGET_ROLE = 'toolset_pa_connected_elements_target_role';
        const ALIAS_ASSOCIATIONS = 'toolset_pa_associations';
        /**
         * JoinManager constructor.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $target_role
         * @param \IToolset_Relationship_Definition $relationship
         * @param \IToolset_Element $for_element
         * @param \wpdb $wpdb
         * @param \OTGS\Toolset\Common\WPML\WpmlService $wpml_service
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $target_role, \IToolset_Relationship_Definition $relationship, \IToolset_Element $for_element, \wpdb $wpdb, \OTGS\Toolset\Common\WPML\WpmlService $wpml_service, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names)
        {
        }
        /**
         * @inheritDoc
         */
        public function hook()
        {
        }
        /**
         * @inheritDoc
         */
        public function unhook()
        {
        }
        /**
         * @inheritDoc
         */
        public function register_join($table_keyword)
        {
        }
        /**
         * @inheritDoc
         */
        public function add_join_clauses($join)
        {
        }
    }
    class O2MPostQuery extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\PotentialAssociationQuery\PostQuery
    {
        public function get_results($check_can_connect_another_element = true, $check_distinct_relationships = true)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery {
    /**
     * Manages JOIN clauses shared between different conditions within one association query.
     *
     * Use methods in this class to obtain aliases for the tables you need. By doing that,
     * those tables will be added to the final JOIN clause. There is no risk of alias
     * conflicts as long as all conditions use the same instance of
     * UniqueTableAlias provided via the setup() method.
     *
     * The setup() method must obviously be called before any further use of the class.
     *
     * @since 4.0
     */
    class TableJoinManager
    {
        const ALIAS_ASSOCIATIONS = 'associations';
        const ALIAS_RELATIONSHIPS = 'relationships';
        /**
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Table_Join_Manager
         * constructor.
         *
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names
         * @param \wpdb $wpdb
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names, \wpdb $wpdb)
        {
        }
        /**
         * Setup the object for use in a particular context.
         *
         * Must be called before any further usage.
         *
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\UniqueTableAlias $unique_table_alias
         */
        public function setup(\OTGS\Toolset\Common\Relationships\DatabaseLayer\UniqueTableAlias $unique_table_alias)
        {
        }
        /**
         * Get an alias for a wp_posts table JOINed on a particular element role.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         *
         * @return string Table alias.
         */
        public function wp_posts(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role)
        {
        }
        /**
         * Get an alias for a wp_postmeta table JOINed on a particular element role and a meta_key value.
         *
         * This creates LEFT JOIN clauses, so that even with missing postmeta, the end results are not affected.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param string $meta_key
         *
         * @return string
         * @throws \InvalidArgumentException
         */
        public function wp_postmeta(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $meta_key)
        {
        }
        /**
         * Get an alias for a relationships table JOINed on the relationships_id column.
         *
         * @return string
         */
        public function relationships()
        {
        }
        /**
         * Build the final MySQL query part containing all requested JOIN clauses.
         *
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorInterface $element_selector
         *
         * @return string
         */
        public function get_join_clause(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorInterface $element_selector)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector {
    /**
     * Manages the way element IDs are obtained when building the MySQL query for associations.
     *
     * Generates SELECT clauses for the element IDs. Allows for injecting additional JOIN clauses
     * into the final query.
     *
     * @since 4.0
     */
    interface ElementSelectorInterface
    {
        /**
         * The element selector needs to be initialized early so that it can interact
         * with the join manager object, if needed.
         *
         * See SqlExpressionBuilder::build() for detailed information.
         *
         * @return void
         */
        public function initialize();
        /**
         * Get an alias for an element ID that will be used in the SELECT clause.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param string|bool $which_element Determines which language version of the element should be returned.
         *    For historical reasons, this also accepts true as ElementIdentification::CURRENT_LANGUAGE_IF_POSSIBLE and
         *    and false as ElementIdentification::DEFAULT_LANGUAGE.
         *
         * @return string|null
         */
        public function get_element_id_alias(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $which_element = \OTGS\Toolset\Common\Relationships\API\ElementIdentification::CURRENT_LANGUAGE_IF_POSSIBLE);
        /**
         * Tell whether there may be a different element ID value for the current and the default language.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         *
         * @return mixed
         */
        public function may_have_element_id_translated(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role);
        /**
         * Get a name of the table and the column that contains an element ID.
         *
         * This is different from the alias because it can be used within the query itself
         * for other purposes.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param string|bool $which_element Determines which language version of the element should be returned.
         *    For historical reasons, this also accepts true as ElementIdentification::CURRENT_LANGUAGE_IF_POSSIBLE and
         *    and false as ElementIdentification::DEFAULT_LANGUAGE.
         *
         * @return string Unambiguous "column" or "table.column" that contains ID of the element.
         */
        public function get_element_id_value(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $which_element = \OTGS\Toolset\Common\Relationships\API\ElementIdentification::CURRENT_LANGUAGE_IF_POSSIBLE);
        /**
         * Provide the name of the table and the column that contains element's TRID.
         *
         * Null is returned if the relationship role isn't translatable.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @return string|null Unambiguous "column" or "table.column" that contains ID of the element, or null.
         */
        public function get_element_trid_value(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role);
        /**
         * Get all the select clauses for all the element IDs.
         *
         * Individual clauses must be connected with a comma, but there must not be
         * a trailing comma present.
         *
         * @return string
         */
        public function get_select_clauses();
        /**
         * Get all JOIN clauses that need to be included in the query.
         *
         * The only assumption these JOINs can make is that there might be the relationships table joined
         * first (if the element selector requires it). Anything else coming from the join manager
         * will be joined after.
         *
         * @return string
         */
        public function get_join_clauses();
        /**
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         *
         * @return void
         */
        public function request_element_in_results(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role);
        /**
         * Call this to make sure the association ID and relationship ID will be included in the SELECT clause.
         *
         * @return void
         */
        public function request_association_and_relationship_in_results();
        /**
         * Call this to make sure the DISTINCT keyword will be used.
         *
         * @return void
         */
        public function request_distinct_query();
        /**
         * Get the DISTINCT keyword or an empty string.
         *
         * @return string
         */
        public function maybe_get_distinct_modifier();
        /**
         * Get roles that have been already requested.
         *
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipRole[]
         */
        public function get_requested_element_roles();
        /**
         * Signal whether the intermediary post column can be skipped from the results.
         *
         * Note that this is really only concerning the result transformation object, which can then make a more informed
         * decision about calling request_element_in_results().
         *
         * @param bool $skip
         *
         * @return void
         */
        public function skip_intermediary_posts($skip = true);
        /**
         * Returns true if the intermediary post column may be skipped in for the result transformation process.
         *
         * @return bool
         * @see self::skip_intermediary_posts()
         */
        public function should_skip_intermediary_posts();
    }
    /**
     * Shared functionality for all element selector implementations.
     *
     * @since 4.0
     */
    abstract class AbstractSelector implements \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorInterface
    {
        /** @var \OTGS\Toolset\Common\Relationships\DatabaseLayer\UniqueTableAlias */
        protected $table_alias;
        /** @var \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager */
        protected $join_manager;
        /** @var \wpdb */
        protected $wpdb;
        /** @var \OTGS\Toolset\Common\WPML\WpmlService */
        protected $wpml_service;
        /** @var \OTGS\Toolset\Common\Relationships\API\RelationshipRole[] */
        protected $requested_roles = array();
        /** @var \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames */
        protected $table_names;
        /**
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\UniqueTableAlias $table_alias
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager
         * @param \wpdb $wpdb
         * @param \OTGS\Toolset\Common\WPML\WpmlService $wpml_service
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\DatabaseLayer\UniqueTableAlias $table_alias, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager, \wpdb $wpdb, \OTGS\Toolset\Common\WPML\WpmlService $wpml_service, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names)
        {
        }
        /**
         * @inheritdoc
         */
        public function initialize()
        {
        }
        /**
         * @inheritdoc
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         */
        public function request_element_in_results(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role)
        {
        }
        /**
         * @inheritdoc
         */
        public function request_association_and_relationship_in_results()
        {
        }
        /**
         * Get the select clauses for association and relationship IDs if they have been requested.
         *
         * @return string[]
         */
        protected function maybe_get_association_and_relationship()
        {
        }
        /**
         * @inheritdoc
         *
         * @since 2.6.1
         */
        public function request_distinct_query()
        {
        }
        /**
         * @inheritdoc
         *
         * @return string
         * @since 2.6.1
         */
        public function maybe_get_distinct_modifier()
        {
        }
        /**
         * @inheritDoc
         */
        public function get_requested_element_roles()
        {
        }
        /**
         * @inheritDoc
         *
         * @param bool $skip
         */
        public function skip_intermediary_posts($skip = true)
        {
        }
        /**
         * @inheritDoc
         */
        public function should_skip_intermediary_posts()
        {
        }
        /**
         * @inheritDoc
         */
        public function get_element_trid_value(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role)
        {
        }
    }
    /**
     * Element selector that translates post elements and chooses the best element ID available.
     */
    class TranslatableElementSelector extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\AbstractSelector
    {
        // These constants are used in the element selection query.
        const DISPLAY_AS_TRANSLATED_VALUE = 1;
        const STANDARD_TRANSLATE_VALUE = 2;
        const NON_TRANSLATABLE_VALUE = 3;
        const AUTODRAFT_MODE_VALUE = 4;
        /**
         * TranslatableElementSelector constructor.
         *
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\UniqueTableAlias $table_alias
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager
         * @param \wpdb $wpdb
         * @param \OTGS\Toolset\Common\WPML\WpmlService $wpml_service
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names
         * @param $unnecessary_wpml_table_joins
         * @param bool $include_original_language
         * @param bool $force_display_as_translated_mode
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole[] $roles_to_maybe_include_auto_drafts
         * @param string[] $post_type_constraints
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\DatabaseLayer\UniqueTableAlias $table_alias, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager, \wpdb $wpdb, \OTGS\Toolset\Common\WPML\WpmlService $wpml_service, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names, $unnecessary_wpml_table_joins, $include_original_language, $force_display_as_translated_mode, $roles_to_maybe_include_auto_drafts, $post_type_constraints)
        {
        }
        public function initialize()
        {
        }
        /**
         * @inheritdoc
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         */
        public function request_element_in_results(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role)
        {
        }
        /**
         * @inheritDoc
         */
        public function may_have_element_id_translated(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role)
        {
        }
        /**
         * @inheritDoc
         */
        public function get_element_id_value(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $which_element = \OTGS\Toolset\Common\Relationships\API\ElementIdentification::CURRENT_LANGUAGE_IF_POSSIBLE)
        {
        }
        /**
         * @inheritDoc
         */
        public function get_select_clauses()
        {
        }
        /**
         * @inheritDoc
         */
        public function get_join_clauses()
        {
        }
        /**
         * Get the language that will be used for the query results (besides the default language).
         *
         * @return string
         */
        protected function get_translation_language()
        {
        }
        /**
         * @inheritDoc
         */
        public function get_element_id_alias(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $which_element = \OTGS\Toolset\Common\Relationships\API\ElementIdentification::CURRENT_LANGUAGE_IF_POSSIBLE)
        {
        }
        /**
         * @inheritDoc
         */
        public function get_element_trid_value(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role)
        {
        }
    }
    /**
     * Pseudo-enum for standardized aliases of columns in the SELECT clause of association queries.
     *
     * FIXED_* values can be relied on throughout the database layer. Element ID column aliases should be
     * obtained exclusively via the element selector object.
     *
     * Do not mix with actual column names defined in \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableColumns.
     *
     * This is the only place where these aliases may be defined.
     *
     * @since 4.0
     */
    class SelectedColumnAliases
    {
        const FIXED_ALIAS_ID = 'id';
        const FIXED_ALIAS_RELATIONSHIP_ID = 'relationship_id';
        const PARENT_ID = 'selected_parent_id';
        const CHILD_ID = 'selected_child_id';
        const INTERMEDIARY_ID = 'selected_intermediary_id';
        /**
         * Translate a role name to a preferred column alias.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @return string
         */
        public static function role_to_name(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role)
        {
        }
    }
    /**
     * Provider for the element selector.
     *
     * It creates the correct one depending on the state of WPML and the current language
     * and then keeps providing the same instance every time.
     *
     * Together with the restriction that condition classes must not use the element selector
     * in their constructor, this allows us to inject this dependency to query conditions
     * but wait until all conditions are instantiated before we decide which element selector
     * to actually use.
     *
     * @since 4.0
     */
    class ElementSelectorProvider
    {
        /**
         * @param \Toolset_Condition_Plugin_Wpml_Is_Active_And_Configured $is_wpml_active
         * @param \OTGS\Toolset\Common\WPML\WpmlService $wpml_service
         * @param \wpdb $wpdb
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names
         */
        public function __construct(\Toolset_Condition_Plugin_Wpml_Is_Active_And_Configured $is_wpml_active, \OTGS\Toolset\Common\WPML\WpmlService $wpml_service, \wpdb $wpdb, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names)
        {
        }
        /**
         * Get the selector instance once it has been created.
         *
         * @return ElementSelectorInterface
         * @throws \RuntimeException
         */
        public function get_selector()
        {
        }
        /**
         * Set the translation language that may be used instead of the current language.
         *
         * @param string $lang_code Valid language code.
         */
        public function set_translation_language($lang_code)
        {
        }
        /**
         * Create an appropriate element selector.
         *
         * This can be called only once.
         *
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\UniqueTableAlias $table_alias
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager
         *
         * @param array $unnecessary_wpml_table_joins
         * @param bool $can_skip_intermediary_posts
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole[] $roles_to_maybe_include_auto_drafts
         * @param string[] $post_type_constraints
         *
         * @return ElementSelectorInterface
         */
        public function create_selector(\OTGS\Toolset\Common\Relationships\DatabaseLayer\UniqueTableAlias $table_alias, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager, array $unnecessary_wpml_table_joins, $can_skip_intermediary_posts, $roles_to_maybe_include_auto_drafts, $post_type_constraints)
        {
        }
        /**
         * Set whether element translation should be attempted at all (by default, it is true).
         *
         * Setting this to false will completely ignore WPML when building the MySQL query.
         *
         * @param bool $should_translate
         */
        public function attempt_translating_elements($should_translate)
        {
        }
        public function include_original_language($include = true)
        {
        }
        /**
         * See AssociationQuery::force_display_as_translated_mode().
         *
         * @param bool $do_force
         */
        public function force_display_as_translated_mode($do_force = true)
        {
        }
    }
    /**
     * Default element selector that takes the element ID directly from the connected element table.
     * Suitable for queries with only non-translatable elements.
     *
     * @since 4.0
     */
    class DefaultSelector extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\AbstractSelector
    {
        /**
         * @inheritDoc
         */
        public function get_element_id_alias(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $which_element = \OTGS\Toolset\Common\Relationships\API\ElementIdentification::CURRENT_LANGUAGE_IF_POSSIBLE)
        {
        }
        /**
         * @inheritDoc
         */
        public function request_element_in_results(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role)
        {
        }
        /**
         * @inheritDoc
         */
        public function get_element_id_value(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $which_element = \OTGS\Toolset\Common\Relationships\API\ElementIdentification::CURRENT_LANGUAGE_IF_POSSIBLE)
        {
        }
        /**
         * @inheritDoc
         */
        public function get_select_clauses()
        {
        }
        /**
         * @inheritDoc
         */
        public function get_join_clauses()
        {
        }
        /**
         * @inheritDoc
         */
        public function may_have_element_id_translated(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role)
        {
        }
    }
    /**
     * Element selector that translates post elements and chooses the best element ID
     * when the current language is "all" (to display all content disregarding their language).
     *
     * This selector uses a specific provided language instead, or uses the default language.
     *
     * The association query class is responsible for determining the correct language code.
     */
    class AllLanguagesSelector extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\TranslatableElementSelector
    {
        /**
         * AllLanguagesSelector constructor.
         *
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\UniqueTableAlias $table_alias
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager
         * @param \wpdb $wpdb
         * @param \OTGS\Toolset\Common\WPML\WpmlService $wpml_service
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names
         * @param $unnecessary_wpml_table_joins
         * @param bool $include_original_language
         * @param string $translation_language
         * @param bool $force_display_as_translated_mode
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole[] $roles_to_maybe_include_auto_drafts
         * @param $post_type_constraints
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\DatabaseLayer\UniqueTableAlias $table_alias, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager, \wpdb $wpdb, \OTGS\Toolset\Common\WPML\WpmlService $wpml_service, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names, $unnecessary_wpml_table_joins, $include_original_language, $translation_language, $force_display_as_translated_mode, $roles_to_maybe_include_auto_drafts, $post_type_constraints)
        {
        }
        protected function get_translation_language()
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ResultTransformation {
    /**
     * Object that performs a transformation of a single database row from the
     * association query into a the desired result.
     *
     * @since 4.0
     */
    interface ResultTransformationInterface
    {
        /**
         * @param array $database_row It is safe to expect only properties that are always
         *     preset in results of a query from SqlExpressionBuilder.
         *
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorInterface $element_selector
         *
         * @return mixed
         */
        public function transform($database_row, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorInterface $element_selector);
        /**
         * Talk to the element selector so that it includes only elements that are actually needed.
         *
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorInterface $element_selector
         *
         * @return void
         */
        public function request_element_selection(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorInterface $element_selector);
        /**
         * Determine what roles *may* need to be included in the results.
         *
         * That means, if a role is not returned by this method, it will definitely *not* be needed during the result
         * transformation. It doesn't work the opposite way, though.
         *
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipRole[]
         */
        public function get_maximum_requested_roles();
    }
    /**
     * Transform association query results grouped by role.
     *
     * Encapsulates other transformation objects to produce the results.
     *
     * @since 4.0
     */
    class ElementPerRole implements \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ResultTransformation\ResultTransformationInterface
    {
        /**
         * Toolset_Association_Query_Result_Transformation_Per_Role constructor.
         *
         * @param ResultTransformationFactory $result_transformation_factory
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\Query $query
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ResultTransformation\ResultTransformationFactory $result_transformation_factory, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\Query $query)
        {
        }
        /**
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         *
         * @return $this
         */
        public function return_element_ids(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role)
        {
        }
        /**
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         *
         * @return $this
         */
        public function return_element_instances(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role)
        {
        }
        /**
         * Return the query object by which this transformation class has been created, so that it is possible to continue
         * method chaining.
         *
         * @return \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\Query
         */
        public function done()
        {
        }
        /**
         * @param array $database_row It is safe to expect only properties that are always
         *     preset in results of a query from OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Sql_Expression_Builder.
         *
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorInterface $element_selector
         *
         * @return mixed
         */
        public function transform($database_row, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorInterface $element_selector)
        {
        }
        /**
         * Talk to the element selector so that it includes only elements that are actually needed.
         *
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorInterface $element_selector
         *
         * @return void
         */
        public function request_element_selection(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorInterface $element_selector)
        {
        }
        /**
         * @inheritDoc
         *
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipRole[]
         */
        public function get_maximum_requested_roles()
        {
        }
    }
    /**
     * Transform association query results into instances of elements of the chosen role.
     *
     * Note: At the moment, only the posts domain is supported.
     */
    class ElementInstance implements \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ResultTransformation\ResultTransformationInterface
    {
        /**
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         * @param \Toolset_Element_Factory $element_factory
         * @param \OTGS\Toolset\Common\WPML\WpmlService $wpml_service
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role, \Toolset_Element_Factory $element_factory, \OTGS\Toolset\Common\WPML\WpmlService $wpml_service)
        {
        }
        /**
         * @inheritdoc
         *
         * Note: This will require some adjustments when other element domains are supported.
         * The best course will be to instruct $element_selector to also include the relationships
         * table in request_element_selection() and then obtain the domain information from there.
         *
         * @param array $database_row
         *
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorInterface $element_selector
         *
         * @return \IToolset_Element
         */
        public function transform($database_row, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorInterface $element_selector)
        {
        }
        /**
         * Talk to the element selector so that it includes only elements that are actually needed.
         *
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorInterface $element_selector
         */
        public function request_element_selection(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorInterface $element_selector)
        {
        }
        /**
         * @inheritDoc
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipRole[]
         */
        public function get_maximum_requested_roles()
        {
        }
    }
    /**
     * Factory for the ResultTransformationInstance implementations.
     *
     * setup() must be called before further use.
     *
     * @since 4.0
     * @codeCoverageIgnore
     */
    class ResultTransformationFactory
    {
        /**
         * ResultTransformationFactory constructor.
         *
         * @param \Toolset_Element_Factory $element_factory
         * @param \Toolset_WPML_Compatibility $wpml_service
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Persistence\AssociationTranslator $association_translator
         */
        public function __construct(\Toolset_Element_Factory $element_factory, \Toolset_WPML_Compatibility $wpml_service, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Persistence\AssociationTranslator $association_translator)
        {
        }
        /**
         * Setup the factory to be used in a particular context.
         *
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\Query $query
         */
        public function setup(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\Query $query)
        {
        }
        /**
         * @return AssociationInstance
         */
        public function association_instance()
        {
        }
        /**
         * @return AssociationUid
         */
        public function association_uid()
        {
        }
        /**
         * @return ElementPerRole
         */
        public function element_per_role()
        {
        }
        /**
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         *
         * @return ElementId
         */
        public function element_id(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role)
        {
        }
        /**
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         *
         * @return ElementInstance
         */
        public function element_instance(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role)
        {
        }
    }
    /**
     * Transform association query results into instances of IToolset_Association.
     *
     * @since 4.0
     */
    class AssociationInstance implements \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ResultTransformation\ResultTransformationInterface
    {
        /**
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Persistence\AssociationTranslator $association_translator
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Persistence\AssociationTranslator $association_translator)
        {
        }
        /**
         * @inheritdoc
         *
         * @param array $database_row
         *
         * @return \IToolset_Association
         */
        public function transform($database_row, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorInterface $element_selector)
        {
        }
        /**
         * Talk to the element selector so that it includes only elements that are actually needed.
         *
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorInterface $element_selector
         *
         * @return void
         */
        public function request_element_selection(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorInterface $element_selector)
        {
        }
        /**
         * @inheritDoc
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipRole[]
         */
        public function get_maximum_requested_roles()
        {
        }
    }
    /**
     * Transform association query results into element IDs of the chosen role.
     *
     * @since 4.0
     */
    class ElementId implements \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ResultTransformation\ResultTransformationInterface
    {
        /**
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role)
        {
        }
        /**
         * @inheritdoc
         *
         * @param array $database_row
         *
         * @return int
         */
        public function transform($database_row, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorInterface $element_selector)
        {
        }
        /**
         * Talk to the element selector so that it includes only elements that are actually needed.
         *
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorInterface $element_selector
         */
        public function request_element_selection(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorInterface $element_selector)
        {
        }
        /**
         * @inheritDoc
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipRole[]
         */
        public function get_maximum_requested_roles()
        {
        }
    }
    /**
     * Transforms the association query result into an association UID.
     *
     * @since 4.0
     */
    class AssociationUid implements \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ResultTransformation\ResultTransformationInterface
    {
        /**
         * @inheritdoc
         *
         * @param array $database_row
         *
         * @return int
         */
        public function transform($database_row, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorInterface $element_selector)
        {
        }
        /**
         * Talk to the element selector so that it includes only elements that are actually needed.
         *
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorInterface $element_selector
         *
         * @return void
         */
        public function request_element_selection(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorInterface $element_selector)
        {
        }
        /**
         * @inheritDoc
         * @return \Toolset\DynamicSources\ToolsetSources\RelationshipRole[]
         */
        public function get_maximum_requested_roles()
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery {
    /**
     * Factory for AssociationQueryCondition implementations.
     *
     * The setup() method must be called before any further use.
     *
     * @since 4.0
     * @codeCoverageIgnore
     */
    class ConditionFactory
    {
        /**
         * ConditionFactory constructor.
         *
         * @param \wpdb $wpdb
         * @param \OTGS\Toolset\Common\PostStatus $post_status
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names
         * @param \OTGS\Toolset\Common\WPML\WpmlService $wpml_service
         */
        public function __construct(\wpdb $wpdb, \OTGS\Toolset\Common\PostStatus $post_status, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names, \OTGS\Toolset\Common\WPML\WpmlService $wpml_service)
        {
        }
        /**
         * Setup the factory for usage in a particular context.
         *
         * Must be called before further use.
         *
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorProvider $element_selector_provider
         * @param TableJoinManager $table_join_manager
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\UniqueTableAlias $unique_table_alias
         */
        public function setup(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorProvider $element_selector_provider, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $table_join_manager, \OTGS\Toolset\Common\Relationships\DatabaseLayer\UniqueTableAlias $unique_table_alias)
        {
        }
        public function do_or(array $operands)
        {
        }
        public function do_and(array $operands)
        {
        }
        public function tautology()
        {
        }
        public function contradiction()
        {
        }
        public function not(\OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition $condition)
        {
        }
        public function association_id($association_id)
        {
        }
        public function element_id($element_id, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role)
        {
        }
        public function element_id_and_domain($element_id, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $element_identification_to_query_by, $translate_original_id)
        {
        }
        /**
         * @param int $trid
         * @param int $element_id
         * @param string $domain
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param string $element_identification_to_query_by
         * @param bool $translate_original_id
         *
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
         */
        public function element_trid_or_id_and_domain($trid, $element_id, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $element_identification_to_query_by, $translate_original_id)
        {
        }
        public function element_status($statuses, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role)
        {
        }
        public function exclude_element($element_id, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $element_identification_to_query_by, $translate_original_id)
        {
        }
        public function has_active_relationship($expected_value)
        {
        }
        public function has_autodeletable_intermediary($expected_value)
        {
        }
        public function has_domain($domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role)
        {
        }
        public function has_domain_and_type($domain, $type, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role)
        {
        }
        public function has_intermediary_id()
        {
        }
        public function has_legacy_relationship($expected_value)
        {
        }
        public function has_type($type, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role)
        {
        }
        public function multiple_elements($element_ids, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $query_original_element, $translate_original_id)
        {
        }
        public function post_meta($meta_key, $meta_value, $comparison_operator, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role)
        {
        }
        public function relationship_id($relationship_id, \IToolset_Relationship_Definition $relationship_definition = null)
        {
        }
        public function relationship_origin($expected_value)
        {
        }
        public function search($search_string, $is_exact_search, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role)
        {
        }
    }
    /**
     * Builds the MySQL expression for the association query.
     */
    class SqlExpressionBuilder
    {
        /**
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names)
        {
        }
        public function setup(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager)
        {
        }
        /**
         * Build a complete MySQL query from the conditions.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition $root_condition
         * @param int $offset
         * @param int $limit
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\OrderBy\OrderByInterface $orderby
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorInterface $element_selector
         * @param bool $need_found_rows
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ResultTransformation\ResultTransformationInterface $result_transformation
         *
         * @return string
         */
        public function build(\OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition $root_condition, $offset, $limit, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\OrderBy\OrderByInterface $orderby, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorInterface $element_selector, $need_found_rows, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ResultTransformation\ResultTransformationInterface $result_transformation)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\OrderBy {
    /**
     * Classes implementing this interface define ordering of results in an association query.
     *
     * @since 4.0
     */
    interface OrderByInterface
    {
        /**
         * Set the order direction.
         *
         * @param string $order Constants::ORDER_ASC or ORDER_DESC.
         * @return void
         */
        public function set_order($order);
        /**
         * Build the ORDER BY clause (not including the "ORDER BY" keyword).
         *
         * @return string
         */
        public function get_orderby_clause();
        /**
         * If the class uses a join manager, request all needed joins now.
         *
         * @return void
         */
        public function register_joins();
    }
    /**
     * Shared functionality for OrderbyInterface implementations.
     */
    abstract class AbstractOrderBy implements \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\OrderBy\OrderByInterface
    {
        /** @var string */
        protected $order = 'ASC';
        /** @var \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager */
        protected $join_manager;
        /**
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager)
        {
        }
        /**
         * Set the direction of sorting.
         *
         * @param string $order 'ASC'|'DESC'
         *
         * @throws \InvalidArgumentException
         */
        public function set_order($order)
        {
        }
        /**
         * @inheritdoc
         */
        abstract public function register_joins();
    }
    /**
     * Order associations by title of an element of given role.
     *
     * Note: Currently, only the posts domain is supported.
     *
     * Note: Ordering by intermediary posts will exclude associations that don't have one.
     */
    class OrderByTitle extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\OrderBy\AbstractOrderBy
    {
        /**
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager)
        {
        }
        /**
         * @inheritdoc
         */
        public function register_joins()
        {
        }
        /**
         * @inheritdoc
         * @return string
         */
        public function get_orderby_clause()
        {
        }
    }
    /**
     * Don't order associations by anything.
     */
    class OrderByNothing implements \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\OrderBy\OrderByInterface
    {
        public function get_orderby_clause()
        {
        }
        public function set_order($order)
        {
        }
        public function register_joins()
        {
        }
    }
    /**
     * Order associations by a postmeta value of an (post) element of given role.
     *
     * Note: Using this on an element of a wrong domain will exclude all associations from the results.
     *
     * @since 4.0
     */
    class OrderByPostmeta extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\OrderBy\AbstractOrderBy
    {
        /**
         * List of allowed casting types
         *
         * @var string[]
         */
        const ALLOWED_MYSQL_TYPES = ['SIGNED', 'UNSIGNED', 'DATE', 'DATETIME', 'CHAR'];
        /**
         * @param string $meta_key
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager
         * @param string $cast_to If the metakey needs to be casted into a different type
         *
         * @throws \InvalidArgumentException
         */
        public function __construct($meta_key, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager, $cast_to = null)
        {
        }
        /**
         * @inheritdoc
         */
        public function register_joins()
        {
        }
        /**
         * @inheritdoc
         * @return string
         */
        public function get_orderby_clause()
        {
        }
    }
    /**
     * Factory for OrderByInterface
     *
     * @codeCoverageIgnore
     */
    class OrderByFactory
    {
        /**
         * @return OrderByInterface
         */
        public function nothing()
        {
        }
        /**
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager
         *
         * @return OrderByInterface
         */
        public function title(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager)
        {
        }
        /**
         * @param string $meta_key
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager
         * @param string|null $cast_to If the metakey needs to be casted into a different type
         *
         * @return OrderByInterface
         */
        public function postmeta($meta_key, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager, $cast_to = null)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\Condition {
    /**
     * Condition for the association query.
     */
    abstract class AbstractCondition implements \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition
    {
        /**
         * By default, there is nothing to join.
         *
         * @return string
         */
        public function get_join_clause()
        {
        }
    }
    /**
     * Query associations by a flag of a relationship they belong to.
     *
     * @since 4.0
     */
    abstract class RelationshipFlag extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\Condition\AbstractCondition
    {
        /**
         * @param bool $expected_value
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager
         */
        public function __construct($expected_value, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager)
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
        /**
         * Get the name of the column in the relationships table to query by.
         *
         * @return string
         */
        abstract protected function get_flag_name();
    }
    /**
     * Query associations by the is_active value of a relationship they belong to.
     */
    class HasActiveRelationship extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\Condition\RelationshipFlag
    {
        /**
         * @inheritdoc
         * @return string
         */
        protected function get_flag_name()
        {
        }
    }
    /**
     * Condition that filters associations by the fact whether they have an intermediary post
     * that can be automatically deleted together with the association (which is a setting of the relationship definition).
     *
     * @since 4.0
     */
    class HasAutodeletableIntermediaryPost extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\Condition\RelationshipFlag
    {
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
        /**
         * Get the name of the column in the relationships table to query by.
         *
         * @return string
         */
        protected function get_flag_name()
        {
        }
    }
    /**
     * Query associations by the origin value of a relationship they belong to.
     */
    class RelationshipOrigin extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\Condition\AbstractCondition
    {
        /**
         * @param string $expected_value
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager
         */
        public function __construct($expected_value, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager)
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
    }
    /**
     * Condition to query associations by a particular element involved in a particular role.
     */
    class ElementIdAndDomain extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\Condition\AbstractCondition
    {
        /**
         * @param int $element_id
         * @param string $domain
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorProvider $element_selector_provider
         * @param string $element_identification_to_query_by
         * @param bool $translate_provided_id
         * @param \OTGS\Toolset\Common\WPML\WpmlService $wpml_service
         */
        public function __construct($element_id, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorProvider $element_selector_provider, $element_identification_to_query_by, $translate_provided_id, \OTGS\Toolset\Common\WPML\WpmlService $wpml_service)
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
        protected function get_operator()
        {
        }
        /**
         * @return int ID of the element to query as provided in the constructor. It may be different from the element
         *     to actually query by (e.g. due to translation).
         */
        public function get_element_id()
        {
        }
        /**
         * @return string Element domain to query by.
         */
        public function get_domain()
        {
        }
        /**
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipRole Role to query by.
         */
        public function get_role()
        {
        }
    }
    /**
     * Condition to exclude a particular element from the results.
     *
     * See the parent class for details.
     */
    class ExcludeElement extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\Condition\ElementIdAndDomain
    {
        protected function get_operator()
        {
        }
    }
    /**
     * Condition to query by a set of elements in a selected role.
     *
     * If any of the provided IDs match, the row is accepted.
     */
    class MultipleElements extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\Condition\AbstractCondition
    {
        /**
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition_Element_Id
         * constructor.
         *
         * @param int[] $element_ids
         * @param string $domain
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorProvider $element_selector_provider
         * @param $query_original_element
         * @param $translate_provided_ids
         */
        public function __construct($element_ids, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorProvider $element_selector_provider, $query_original_element, $translate_provided_ids)
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
    }
    /**
     * Query condition by a postmeta value of a selected element role.
     *
     * Note: Using this will immediately exclude all non-post elements.
     */
    class PostMeta extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\Condition\AbstractCondition
    {
        /**
         * @param string $meta_key
         * @param string $meta_value
         * @param string $comparison_operator
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager
         */
        public function __construct($meta_key, $meta_value, $comparison_operator, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager)
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
    }
    /**
     * Condition to query associations by a specific relationship (row) ID.
     */
    class RelationshipId extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\Condition\AbstractCondition
    {
        /**
         * @param int $relationship_id
         * @param \IToolset_Relationship_Definition|null $relationship_definition Optional, pass only when already available
         *     to allow additional optimizations.
         *
         * @throws \InvalidArgumentException When an obviously invalid relationship ID is provided.
         */
        public function __construct($relationship_id, \IToolset_Relationship_Definition $relationship_definition = null)
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
        /**
         * Returns condition operator
         *
         * @return string
         * @since m2m
         */
        protected function get_operator()
        {
        }
        /**
         * @return \IToolset_Relationship_Definition|null
         */
        public function get_relationship_definition()
        {
        }
    }
    /**
     * Query by searching a text in elements of a given role.
     *
     * Note: This currently supports only posts, but in the future, it should be domain-agnostic.
     */
    class Search extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\Condition\AbstractCondition
    {
        /**
         * @param string $search_string
         * @param bool $is_exact_search
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager
         * @param \wpdb $wpdb
         */
        public function __construct($search_string, $is_exact_search, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager, \wpdb $wpdb)
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
    }
    /**
     * Condition to query associations by a specific association ID.
     *
     * @since 4.0
     */
    class AssociationId extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\Condition\AbstractCondition
    {
        /**
         * @param int $association_id
         *
         * @throws \InvalidArgumentException
         */
        public function __construct($association_id)
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
    }
    /**
     * Condition to query associations by a particular element involved in a particular role.
     *
     * Warning: This is not WPML-aware query. It simply compares the provided ID with the ID in
     * the correct column in the associations table. In most cases, you will need the translation
     * mechanism to be involved and use OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition_Element_Id_And_Domain
     * instead.
     */
    class ElementId extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\Condition\AbstractCondition
    {
        /**
         * @param int $element_id
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorProvider $element_selector_provider
         *
         * @throws \InvalidArgumentException
         */
        public function __construct($element_id, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorProvider $element_selector_provider)
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
    }
    /**
     * Condition to query associations by a type (not domain) of elements in the given role.
     *
     * @since 4.0
     */
    class HasType extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\Condition\AbstractCondition
    {
        /**
         * OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition_Has_Type
         * constructor.
         *
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role
         * @param string $type
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\UniqueTableAlias $unique_table_alias
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role, $type, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager, \OTGS\Toolset\Common\Relationships\DatabaseLayer\UniqueTableAlias $unique_table_alias, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names)
        {
        }
        /**
         * @inheritdoc
         * @return string
         */
        public function get_join_clause()
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
    }
    /**
     * Query associations by the domain of selected role.
     */
    class HasDomain extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\Condition\AbstractCondition
    {
        /**
         * @param string $domain
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager
         */
        public function __construct($domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager)
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
        /**
         * @return string The element domain set on this condition.
         * @since 2.5.10
         */
        public function get_domain()
        {
        }
        public function get_for_role()
        {
        }
    }
    /**
     * Condition to query associations by a status of an element in a particular role.
     *
     * Allows querying for a specific status or for a set of statuses that may be
     * depending on other circumstances (e.g. capabilities of the current user).
     *
     * Note that the functionality may be different per each domain. Currently, only posts
     * are supported.
     *
     * @since 4.0
     */
    class ElementStatus extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\Condition\AbstractCondition
    {
        /**
         * @param string|string[] $statuses One or more status values.
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager
         * @param \OTGS\Toolset\Common\PostStatus $post_status
         */
        public function __construct($statuses, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager, \OTGS\Toolset\Common\PostStatus $post_status)
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
        /**
         * @return bool Determine whether auto-draft posts MAY be included in the result after this condition
         *     is applied for the given role. This is important for MySQL query optimization in the context of WPML.
         */
        public function includes_auto_draft()
        {
        }
    }
    /**
     * Condition to query elements by TRID if possible, and otherwise use the ElementIdAndDomain condition.
     *
     * @since 4.0
     */
    class ElementTridOrIdAndDomain extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\Condition\AbstractCondition
    {
        /**
         * ElementTridOrIdAndDomain constructor.
         *
         * @param int $trid
         * @param int $element_id
         * @param string $domain
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorProvider $element_selector_provider
         * @param string $element_identification_to_query_by
         * @param bool $translate_provided_id
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ConditionFactory $condition_factory
         */
        public function __construct($trid, $element_id, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorProvider $element_selector_provider, $element_identification_to_query_by, $translate_provided_id, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ConditionFactory $condition_factory)
        {
        }
        /**
         * @inheritDoc
         */
        public function get_where_clause()
        {
        }
        /**
         * @inheritDoc
         */
        public function get_join_clause()
        {
        }
    }
    /**
     * Condition to query associations by a specific intermediary post (row) ID.
     */
    class HasIntermediaryId extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\Condition\AbstractCondition
    {
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
    }
    /**
     * Query associations by the fact whether the relationship they belong to was migrated from the legacy implementation
     * or not.
     *
     * @since 4.0
     */
    class HasLegacyRelationship extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\Condition\RelationshipFlag
    {
        /**
         * @inheritdoc
         * @return string
         */
        protected function get_flag_name()
        {
        }
    }
    /**
     * Condition to filter results by element domain and type at the same time.
     *
     * Actually, this doesn't do anything but to tie those two together so that the association query
     * can perform some more advanced optimizations.
     */
    class HasDomainAndType extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\Condition\AbstractCondition
    {
        /**
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role
         * @param string $domain
         * @param string $type
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ConditionFactory $condition_factory
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role, $domain, $type, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ConditionFactory $condition_factory)
        {
        }
        /**
         * Get a part of the WHERE clause that applies the condition.
         *
         * @return string Valid part of a MySQL query, so that it can be
         *     used in WHERE ( $condition1 ) AND ( $condition2 ) AND ( $condition3 ) ...
         */
        public function get_where_clause()
        {
        }
        /**
         * @inheritdoc
         *
         * @return string
         */
        public function get_join_clause()
        {
        }
        /**
         * @return string The element domain set in this condition.
         */
        public function get_domain()
        {
        }
        /**
         * @return string The element type set in this condition.
         */
        public function get_type()
        {
        }
        /**
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipRole
         */
        public function get_for_role()
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery {
    class Query implements \OTGS\Toolset\Common\Relationships\API\AssociationQuery
    {
        /**
         * Query constructor.
         *
         * @param \wpdb $wpdb
         * @param ConditionFactory $condition_factory
         * @param \OTGS\Toolset\Common\WPML\WpmlService $wpml_service
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\AssociationQueryCache $query_cache
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorProvider $element_selector_provider
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\UniqueTableAlias $unique_table_alias
         * @param TableJoinManager $join_manager
         * @param SqlExpressionBuilder $expression_builder
         * @param \Toolset_Relationship_Definition_Repository $relationship_definition_repository
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\OrderBy\OrderByFactory $orderby_factory
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ResultTransformation\ResultTransformationFactory $result_transformation_factory
         */
        public function __construct(\wpdb $wpdb, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ConditionFactory $condition_factory, \OTGS\Toolset\Common\WPML\WpmlService $wpml_service, \OTGS\Toolset\Common\Relationships\DatabaseLayer\AssociationQueryCache $query_cache, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorProvider $element_selector_provider, \OTGS\Toolset\Common\Relationships\DatabaseLayer\UniqueTableAlias $unique_table_alias, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\TableJoinManager $join_manager, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\SqlExpressionBuilder $expression_builder, \Toolset_Relationship_Definition_Repository $relationship_definition_repository, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\OrderBy\OrderByFactory $orderby_factory, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ResultTransformation\ResultTransformationFactory $result_transformation_factory)
        {
        }
        /**
         * @inheritDoc
         */
        public function add(\OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition $condition)
        {
        }
        /**
         * @inheritDoc
         */
        public function do_not_add_default_conditions()
        {
        }
        /**
         * @inheritDoc
         */
        public function get_results()
        {
        }
        /**
         * @inheritDoc
         */
        public function do_or(...$conditions)
        {
        }
        /**
         * @inheritDoc
         */
        public function do_and(...$conditions)
        {
        }
        /**
         * @inheritDoc
         */
        public function do_if($statement, \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition $if_branch, \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition $else_branch = null)
        {
        }
        public function not(\OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition $condition)
        {
        }
        /**
         * @inheritDoc
         */
        public function relationship_id($relationship_id)
        {
        }
        /**
         * @inheritDoc
         */
        public function intermediary_id($element_id)
        {
        }
        /**
         * @inheritDoc
         */
        public function relationship(\IToolset_Relationship_Definition $relationship_definition)
        {
        }
        /**
         * @inheritDoc
         */
        public function relationship_slug($slug)
        {
        }
        /**
         * @inheritDoc
         */
        public function element_id($element_id, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $need_wpml_unaware_query = true)
        {
        }
        /**
         * @inheritDoc
         */
        public function element_id_and_domain($element_id, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $query_original_element = false, $translate_provided_id = true, $set_its_translation_language = true, $element_identification_to_query_by = null)
        {
        }
        /**
         * @inheritDoc
         */
        public function element_trid_or_id_and_domain($trid, $element_id, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $translate_provided_id = true, $set_its_translation_language = true, $element_identification_to_query_by = \OTGS\Toolset\Common\Relationships\API\ElementIdentification::CURRENT_LANGUAGE_IF_POSSIBLE)
        {
        }
        /**
         * @inheritDoc
         */
        public function multiple_elements($element_ids, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $query_original_element = false, $translate_provided_ids = true)
        {
        }
        /**
         * @inheritDoc
         */
        public function element(\IToolset_Element $element, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role = null, $query_original_element = false, $translate_provided_id = true, $set_its_translation_language = true)
        {
        }
        /**
         * @inheritDoc
         */
        public function exclude_element(\IToolset_Element $element, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $query_original_element = false, $translate_provided_id = true)
        {
        }
        /**
         * @inheritDoc
         */
        public function parent(\IToolset_Element $element_source)
        {
        }
        /**
         * @inheritDoc
         */
        public function parent_id($parent_id, $domain = \Toolset_Element_Domain::POSTS)
        {
        }
        /**
         * @inheritDoc
         */
        public function child(\IToolset_Element $element)
        {
        }
        /**
         * @inheritDoc
         */
        public function child_id($child_id, $domain = \Toolset_Element_Domain::POSTS)
        {
        }
        /**
         * @inheritDoc
         */
        public function element_status($statuses, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role = null)
        {
        }
        /**
         * @inheritDoc
         */
        public function has_available_elements()
        {
        }
        /**
         * @inheritDoc
         */
        public function has_active_relationship($is_active = true)
        {
        }
        /**
         * @inheritDoc
         */
        public function has_legacy_relationship($needs_legacy_support = true)
        {
        }
        /**
         * @inheritDoc
         */
        public function has_domain($domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role)
        {
        }
        /**
         * @inheritDoc
         */
        public function has_type($type, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role)
        {
        }
        /**
         * @inheritDoc
         */
        public function has_domain_and_type($domain, $type, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role)
        {
        }
        /**
         * @inheritDoc
         */
        public function has_origin($origin)
        {
        }
        /**
         * @inheritDoc
         */
        public function has_intermediary_id()
        {
        }
        /**
         * @inheritDoc
         */
        public function wp_query(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $query_args, $confirmation = null)
        {
        }
        /**
         * @inheritDoc
         */
        public function search($search_string, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $is_exact = false)
        {
        }
        /**
         * @inheritDoc
         */
        public function association_id($association_id)
        {
        }
        public function meta($meta_key, $meta_value, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role = null, $comparison = \Toolset_Query_Comparison_Operator::EQUALS)
        {
        }
        /**
         * @inheritDoc
         */
        public function has_autodeletable_intermediary_post($expected_value = true)
        {
        }
        /**
         * @inheritDoc
         */
        public function return_association_instances()
        {
        }
        /**
         * @inheritDoc
         */
        public function return_association_uids()
        {
        }
        /**
         * @inheritDoc
         */
        public function return_element_ids(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role)
        {
        }
        /**
         * @inheritDoc
         */
        public function return_element_instances(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role)
        {
        }
        /**
         * @inheritDoc
         */
        public function return_per_role()
        {
        }
        /**
         * @inheritDoc
         */
        public function offset($value)
        {
        }
        /**
         * @inheritDoc
         */
        public function limit($value)
        {
        }
        /**
         * @inheritDoc
         */
        public function order($value)
        {
        }
        /**
         * @inheritDoc
         */
        public function need_found_rows($is_needed = true)
        {
        }
        /**
         * @inheritDoc
         */
        public function get_found_rows()
        {
        }
        /**
         * @inheritDoc
         */
        public function dont_order()
        {
        }
        /**
         * @inheritDoc
         */
        public function order_by_title(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role)
        {
        }
        /**
         * @inheritDoc
         */
        public function order_by_field_value(\Toolset_Field_Definition $field_definition, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role)
        {
        }
        /**
         * @inheritDoc
         */
        public function order_by_meta($meta_key, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $is_numeric = false)
        {
        }
        /**
         * @inheritDoc
         */
        public function dont_translate_results()
        {
        }
        /**
         * @inheritDoc
         */
        public function set_translation_language($lang_code)
        {
        }
        /**
         * @inheritDoc
         * @depecated
         */
        public function force_language_per_role(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role, $lang_code)
        {
        }
        /**
         * @inheritDoc
         */
        public function set_translation_language_by_element_id_and_domain($element_id, $domain)
        {
        }
        /**
         * @inheritDoc
         */
        public function get_found_rows_directly()
        {
        }
        public function use_cache($use_cache = true)
        {
        }
        public function build_cache_key($query_string)
        {
        }
        /**
         * @inheritDoc
         */
        public function include_original_language($include = true)
        {
        }
        /**
         * @inheritDoc
         */
        public function force_display_as_translated_mode($do_force = true)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2 {
    /**
     * Define the database structure and register it with the provided SchemaController.
     *
     * @codeCoverageIgnore This is more a database structure definition than testable code.
     * @since 4.0
     */
    class DatabaseStructure
    {
        // Maximum lengths of various columns. Do not change.
        //
        //
        const MAX_DOMAIN_LENGTH = 20;
        const LANG_CODE_LENGTH = 7;
        const RELATIONSHIP_SLUG_LENGTH = \OTGS\Toolset\Common\Relationships\API\Constants::MAXIMUM_RELATIONSHIP_SLUG_LENGTH;
        const DISPLAY_NAME_LENGTH = 255;
        const POST_TYPE_SLUG_LENGTH = 20;
        const ORIGIN_LENGTH = 50;
        /**
         * DatabaseStructure constructor.
         *
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\berlindb\SchemaController $schema_controller
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\berlindb\DatabaseInterfaceProvider $database_interface_provider
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\berlindb\SchemaController $schema_controller, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\berlindb\DatabaseInterfaceProvider $database_interface_provider)
        {
        }
        /**
         * Instantiate the table definitions and register them with the schema controller.
         */
        public function initialize()
        {
        }
        /**
         * Obtain a table definition.
         *
         * @param string $table_name Valid table name.
         * @return \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\berlindb\Table
         * @throws \InvalidArgumentException
         */
        public function get_table($table_name)
        {
        }
    }
    /**
     * Represents a group of connected elements.
     *
     * That basically means a row in the connected elements table and all element translations it refers to.
     *
     * @since 4.0
     */
    class ConnectedElementGroup
    {
        /**
         * ConnectedElementGroup constructor.
         *
         * @param int $group_id
         * @param int[] $element_ids
         * @param string $domain
         * @param int $directly_stored_id
         * @param int $wpml_trid
         */
        public function __construct($group_id, $element_ids, $domain, $directly_stored_id, $wpml_trid)
        {
        }
        /**
         * @return int ID of the group.
         */
        public function get_id()
        {
        }
        /**
         * @return string Domain of elements in the group.
         */
        public function get_domain()
        {
        }
        /**
         * @return int[] IDs of all elements in the group.
         */
        public function get_element_ids()
        {
        }
        /**
         * @return int ID of the element that is stored directly in the row of the connected elements table.
         */
        public function get_directly_stored_id()
        {
        }
        /**
         * @return bool True if there is exactly one element in the group.
         */
        public function has_last_element()
        {
        }
        /**
         * @return int TRID stored with the element group (zero if none).
         */
        public function get_trid()
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Cleanup {
    /**
     * Handles the clean-up when permanently deleting posts.
     *
     * Following scenarios need to be managed here:
     *
     * - A post which is involved in one or more associations is being deleted.
     *     - Delete associations it is involved in, but only if it's the last one in
     *       its connected element group.
     * - When deleting any associations, also delete involved intermediary posts if the
     *   relationship has the appropriate option set, but don't
     *   trigger an infinite recursion by considering the previous scenario.
     * - Only delete a certain number of intermediary posts, schedule the rest to be deleted
     *   via WP CRON in order to prevent a request timeout (we need to be careful since
     *   posts can be deleted in a number of different contexts).
     * - After a post has been deleted, also update its connected element group, if one exists.
     *
     * Please also see the previous implementation:
     * @see Toolset_Association_Cleanup_Post
     */
    class PostCleanupHandler implements \OTGS\Toolset\Common\Relationships\DatabaseLayer\Cleanup\PostCleanupInterface
    {
        /**
         * @param \Toolset_Association_Cleanup_Factory $cleanup_factory
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory $database_layer_factory
         * @param \Toolset_Element_Factory $element_factory
         * @param \Toolset_Cron $cron
         * @param \Toolset_Association_Intermediary_Post_Persistence $intermediary_post_persistence
         */
        public function __construct(\Toolset_Association_Cleanup_Factory $cleanup_factory, \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory $database_layer_factory, \Toolset_Element_Factory $element_factory, \Toolset_Cron $cron, \Toolset_Association_Intermediary_Post_Persistence $intermediary_post_persistence)
        {
        }
        /**
         * Clean up affected associations before a post is permanently deleted.
         *
         * @param int $post_id
         */
        public function cleanup_before_delete($post_id)
        {
        }
        /**
         * After a post has been permanently deleted, make sure we don't leave behind any obsolete
         * data in the connected elements table.
         *
         * @param int $post_id
         */
        public function cleanup_after_delete($post_id)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\WpQueryExtension {
    /**
     * Collects requests for JOINs for the toolset_relationships WP_Query extension, made by the Extension class,
     * and produces the JOIN clause on request.
     *
     * An instance of this class is supposed to be attached to the WP_Query object.
     *
     * @since 4.0
     */
    class JoinManager
    {
        /**
         * JoinManager constructor.
         *
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\UniqueTableAlias $unique_table_alias
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names
         * @param \Toolset_Relationship_Definition_Repository $definition_repository
         * @param \OTGS\Toolset\Common\WPML\WpmlService $wpml_service
         * @param \wpdb $wpdb
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\DatabaseLayer\UniqueTableAlias $unique_table_alias, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names, \Toolset_Relationship_Definition_Repository $definition_repository, \OTGS\Toolset\Common\WPML\WpmlService $wpml_service, \wpdb $wpdb)
        {
        }
        /**
         * @return string
         */
        public function get_join_clauses()
        {
        }
        public function associations_table($relationship_slug, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role_to_return, \IToolset_Post $related_to_post)
        {
        }
    }
    /**
     * The toolset_relationships WP_Query extension for the second database layer version.
     *
     * See superclasses for further information.
     *
     * The JoinManager does the heavy lifting here.
     *
     * @since 4.0
     */
    class Extension extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\WpQueryExtension\AbstractRelationshipsExtension
    {
        /**
         * Get the table join manager object attached to the WP_Query instance or create and attach a new one.
         *
         * @param \WP_Query $query
         *
         * @return JoinManager
         */
        protected function get_table_join_manager(\WP_Query $query)
        {
        }
        /**
         * @inheritDoc
         */
        protected function get_join_clause(\WP_Query $wp_query)
        {
        }
        /**
         * @inheritDoc
         */
        protected function get_where_clause(\WP_Query $wp_query, $relationship_slug, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role_to_return, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $role_to_query_by, \IToolset_Post $related_to_post)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Persistence {
    /**
     * Translate the association data between the IToolset_Association model and a database row.
     *
     * @since 4.0
     */
    class AssociationTranslator
    {
        /**
         * AssociationTranslator constructor.
         *
         * @param \Toolset_Relationship_Definition_Repository $definition_repository
         * @param \Toolset_Association_Factory $association_factory
         * @param \OTGS\Toolset\Common\WPML\WpmlService $wpml_service
         * @param \Toolset_Element_Factory $element_factory
         * @param ConnectedElementPersistence $connected_element_persistence
         */
        public function __construct(\Toolset_Relationship_Definition_Repository $definition_repository, \Toolset_Association_Factory $association_factory, \OTGS\Toolset\Common\WPML\WpmlService $wpml_service, \Toolset_Element_Factory $element_factory, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Persistence\ConnectedElementPersistence $connected_element_persistence)
        {
        }
        /**
         * Instantiate the association from a database row coming from the association query.
         *
         * @param array $database_row Database row as an associative array.
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorInterface $element_selector The element selector used in the query that will provide
         *     information about selected column names for each element role.
         * @param bool|null $use_wpml Whether WPML interoperability should be taken into account, or null to
         *     decide by the plugin status.
         *
         * @return \IToolset_Association
         * @throws BrokenAssociationException Thrown when association data is incomplete or when the association model
         *     cannot be instantiated.
         */
        public function from_database_row_query(array $database_row, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\AssociationQuery\ElementSelector\ElementSelectorInterface $element_selector, $use_wpml = null)
        {
        }
        /**
         * Instantiate an association from a database row of the associations table directly (using standard
         * column names to obtain element group IDs).
         *
         * @param array $database_row
         *
         * @return \IToolset_Association
         * @throws BrokenAssociationException
         */
        public function from_database_row_direct(array $database_row)
        {
        }
        /**
         * Turn an association to a database row (to be used in $wpdb->insert()).
         *
         * Doesn't include the association UID.
         *
         * @param \IToolset_Association $association
         *
         * @return array
         * @throws BrokenAssociationException
         */
        public function to_database_row(\IToolset_Association $association)
        {
        }
        /**
         * @return string[] Column formats for columns as returned by to_database_row().
         */
        public function get_database_row_formats()
        {
        }
    }
    /**
     * Informs about an association that couldn't have been loaded or created.
     *
     * @since 4.0
     */
    class BrokenAssociationException extends \RuntimeException
    {
        /**
         * BrokenAssociationException constructor.
         *
         * @param int $association_uid ID of the broken association if available.
         * @param string $message
         * @param int $code
         * @param \Exception|null $previous
         */
        public function __construct($association_uid, $message = "", $code = 0, \Exception $previous = null)
        {
        }
        /**
         * @return int|null ID of the broken association.
         */
        public function get_association_uid()
        {
        }
    }
    /**
     * Handles the persistence of rows in the connected elements table.
     *
     * Needs to be handled as a singleton.
     *
     * @since 4.0
     */
    class ConnectedElementPersistence
    {
        /**
         * ConnectedElementPersistence constructor.
         *
         * @param \wpdb $wpdb
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names
         * @param \OTGS\Toolset\Common\WPML\WpmlService $wpml_service
         * @param \Toolset_Element_Factory $element_factory
         */
        public function __construct(\wpdb $wpdb, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names, \OTGS\Toolset\Common\WPML\WpmlService $wpml_service, \Toolset_Element_Factory $element_factory)
        {
        }
        /**
         * For a given element, obtain its group_id. Caching on the element object is used.
         *
         * New group_id will be generated if $create_if_missing is true.
         *
         * @param \IToolset_Element $element
         * @param bool $create_if_missing
         *
         * @return int|null
         */
        public function obtain_element_group_id(\IToolset_Element $element, $create_if_missing = true)
        {
        }
        /**
         * A much less optimised version of obtain_element_group_id() that doesn't require
         * the element model to exist.
         *
         * @param int $element_id
         * @param string $domain
         *
         * @return int Zero if the group_id isn't assigned.
         */
        public function query_element_group_id_directly($element_id, $domain)
        {
        }
        /**
         * From an element group_id, instantiate a IToolset_Element model for it.
         *
         * @param int $group_id
         *
         * @return \IToolset_Element
         * @throws \Toolset_Element_Exception_Element_Doesnt_Exist
         */
        public function get_element_by_group_id($group_id)
        {
        }
        /**
         * Build a model of a specific element group.
         *
         * @param int $group_id
         *
         * @return \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\ConnectedElementGroup|null Null if the group ID doesn't correspond with any information.
         */
        public function get_connected_element_group($group_id)
        {
        }
        /**
         * Load an element group by a TRID value that's stored in it.
         *
         * @param $trid
         *
         * @return \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\ConnectedElementGroup|null
         */
        public function get_element_group_by_trid($trid)
        {
        }
        /**
         * Load an element group by the directly stored element ID.
         *
         * @param int $element_id
         * @param string $domain
         *
         * @return \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\ConnectedElementGroup|null
         */
        public function get_element_group_by_element_id($element_id, $domain)
        {
        }
        /**
         * Remove a given element from an element group.
         *
         * Note that all data is assumed valid. The provided $element_id must be part of the group
         * and the element's domain must match the group's.
         *
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\ConnectedElementGroup $group
         * @param $element_id
         */
        public function remove_element_from_group(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\ConnectedElementGroup $group, $element_id)
        {
        }
        /**
         * Completely remove given element group from the connected elements table.
         *
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\ConnectedElementGroup $group
         */
        public function delete_group(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\ConnectedElementGroup $group)
        {
        }
        /**
         * Set a new TRID for a particular element group.
         *
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\ConnectedElementGroup $group
         * @param int $new_trid
         */
        public function update_group_trid(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\ConnectedElementGroup $group, $new_trid)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Persistence\WpmlTranslationUpdate {
    /**
     * Holds parsed and completed information about a wpml_translation_update event.
     *
     * @since 4.0
     */
    class UpdateDescription
    {
        /**
         * UpdateDescription constructor.
         *
         * @param string $action_type
         * @param int $previous_trid
         * @param int $current_trid
         * @param int $affected_post_id
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\ConnectedElementGroup $affected_element_group
         */
        public function __construct($action_type, $previous_trid, $current_trid, $affected_post_id, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\ConnectedElementGroup $affected_element_group = null)
        {
        }
        /**
         * @return string One of values from the ActionType pseudo-enum.
         */
        public function get_action_type()
        {
        }
        /**
         * @return int Zero if not available.
         */
        public function get_previous_trid()
        {
        }
        /**
         * @return int Zero if not available.
         */
        public function get_current_trid()
        {
        }
        /**
         * @return int Zero if not available.
         */
        public function get_affected_post_id()
        {
        }
        /**
         * The element group based on the previous TRID, if it exists.
         *
         * @return \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\ConnectedElementGroup|null
         */
        public function get_affected_element_group()
        {
        }
    }
    /**
     * Appropriately respond to a wpml_translation_update event by updating the connected elements group.
     *
     * @since 4.0
     */
    class WpmlTranslationUpdateHandler
    {
        /** @var string Value of the context key we're interested in. */
        const CONTEXT_POST = 'post';
        /**
         * WpmlTranslationUpdateHandler constructor.
         *
         * @param UpdateDescriptionParser $description_parser
         * @param \wpdb $wpdb
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Persistence\ConnectedElementPersistence $connected_element_persistence
         * @param \Toolset_Association_Cleanup_Factory $cleanup_factory
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory $database_layer_factory
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Persistence\WpmlTranslationUpdate\UpdateDescriptionParser $description_parser, \wpdb $wpdb, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Persistence\ConnectedElementPersistence $connected_element_persistence, \Toolset_Association_Cleanup_Factory $cleanup_factory, \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory $database_layer_factory)
        {
        }
        /**
         * Process the wpml_translation_update event.
         *
         * @link https://onthegosystems.myjetbrains.com/youtrack/issue/wpmlcore-3237
         * @link https://onthegosystems.myjetbrains.com/youtrack/issue/wpmlcore-7203
         *
         * @param $update_description
         */
        public function on_wpml_translation_update($update_description)
        {
        }
    }
    /**
     * Pseudo-enum for keys of the wpml_translation_update action arguments.
     *
     * @since 4.0
     */
    class DescriptionKey
    {
        /**
         * ID of the translation row in the icl_translations table.
         */
        const TRANSLATION_ID = 'translation_id';
        /**
         * ID of the affected element.
         */
        const ELEMENT_ID = 'element_id';
        const ACTION_TYPE = 'type';
        /**
         * May be 'post' or something else.
         */
        const CONTEXT = 'context';
        /**
         * Affected TRID. If the event is about a TRID change, this will hold the new TRID value.
         */
        const TRID = 'trid';
        /**
         * Element type from the icl_translations table.
         */
        const ELEMENT_TYPE = 'element_type';
        /**
         * Affected post type (post type slug, no post_ prefix).
         */
        const POST_TYPE = 'post_type';
    }
    /**
     * Pseudo-enum for possible actions of the wpml_translation_update action.
     *
     * @since 4.0
     */
    abstract class ActionType
    {
        const DELETE = 'delete';
        const UPDATE = 'update';
        const INSERT = 'insert';
        const BEFORE_DELETE = 'before_delete';
        const BEFORE_LANGUAGE_DELETE = 'before_language_delete';
        const RESET = 'reset';
        const INITIALIZE_LANGUAGE_FOR_POST_TYPE = 'initialize_language_for_post_type';
    }
    /**
     * Read the data provided by the wpml_translation_update action and turn them into an UpdateDescription instance.
     *
     * The action provides a varying amount and specificity of information in numerous contexts. Here, we add the
     * missing parts whenever it's possible.
     *
     * Note that this works only for contexts that involve a particular element, not site-wide actions which
     * may need special handling.
     *
     * @since 4.0
     */
    class UpdateDescriptionParser
    {
        /**
         * UpdateDescriptionParser constructor.
         *
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Persistence\ConnectedElementPersistence $connected_element_persistence
         * @param \OTGS\Toolset\Common\WPML\WpmlService $wpml_service
         * @param \wpdb $wpdb
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Persistence\ConnectedElementPersistence $connected_element_persistence, \OTGS\Toolset\Common\WPML\WpmlService $wpml_service, \wpdb $wpdb, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names)
        {
        }
        /**
         * Parse the update description as given by the wpml_translation_update action.
         *
         * @param $update_description
         *
         * @return UpdateDescription
         */
        public function parse($update_description)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Persistence {
    /**
     * Handles the persistence of associations.
     *
     * See interface description for further info.
     *
     * @since 4.0
     */
    class AssociationPersistence implements \OTGS\Toolset\Common\Relationships\DatabaseLayer\AssociationPersistence
    {
        /**
         * AssociationPersistence constructor.
         *
         * @param \wpdb $wpdb
         * @param ConnectedElementPersistence $connected_element_persistence
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names
         * @param \Toolset_Relationship_Definition_Repository $relationship_definition_repository
         * @param AssociationTranslator $association_translator
         * @param \Toolset_Association_Cleanup_Factory $cleanup_factory
         */
        public function __construct(\wpdb $wpdb, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Persistence\ConnectedElementPersistence $connected_element_persistence, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names, \Toolset_Relationship_Definition_Repository $relationship_definition_repository, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Persistence\AssociationTranslator $association_translator, \Toolset_Association_Cleanup_Factory $cleanup_factory)
        {
        }
        /**
         * @inheritDoc
         */
        public function load_association_by_uid($association_uid)
        {
        }
        /**
         * @inheritDoc
         */
        public function insert_association(\IToolset_Association $association)
        {
        }
        /**
         * @inheritDoc
         */
        public function delete_association(\IToolset_Association $association)
        {
        }
        /**
         * Do the toolset_before_association_delete action.
         *
         * See report_association_change() for action parameter information.
         *
         * @param \IToolset_Association $association
         *
         * @since 2.7
         */
        public function report_before_association_delete(\IToolset_Association $association)
        {
        }
    }
    /**
     * Handles the persistence of intermediary posts.
     *
     * @since 4.0
     */
    class IntermediaryPostPersistence implements \OTGS\Toolset\Common\Relationships\API\IntermediaryPostPersistence
    {
        /**
         * Number of items handled each loop.
         */
        const DEFAULT_LIMIT = 50;
        /**
         * Class constructor
         *
         * @param \IToolset_Relationship_Definition|null $relationship Relationship.
         * @param \OTGS\Toolset\Common\WPML\WpmlService $wpml_service
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory $database_layer_factory
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory $database_layer_factory, \OTGS\Toolset\Common\WPML\WpmlService $wpml_service, \IToolset_Relationship_Definition $relationship = null)
        {
        }
        /**
         * @inheritDoc
         */
        public function create_intermediary_post($parent_id, $child_id)
        {
        }
        /**
         * @inheritDoc
         */
        public function create_empty_associations_intermediary_posts($limit = 0)
        {
        }
        /**
         * @inheritDoc
         */
        public function remove_associations_intermediary_posts($limit = 0)
        {
        }
        /**
         * @inheritDoc
         */
        public function create_empty_association_intermediary_post($association)
        {
        }
        /**
         * @inheritDoc
         */
        public function maybe_delete_intermediary_post(\IToolset_Association $association)
        {
        }
        /**
         * @inheritDoc
         *
         * TODO this may be simplified since the default language no longer plays major role when dealing with IPTs.
         *
         * @param $post_id
         */
        public function delete_intermediary_post($post_id)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2 {
    /**
     * Holds names of database tables in the second version of the database layer.
     *
     * @since 4.0
     */
    class TableNames
    {
        const ASSOCIATIONS = 'toolset_associations';
        const CONNECTED_ELEMENTS = 'toolset_connected_elements';
        const RELATIONSHIPS = 'toolset_relationships';
        const TYPE_SETS = 'toolset_type_sets';
        const ALL_RELATIONSHIP_TABLES = [self::ASSOCIATIONS, self::CONNECTED_ELEMENTS, self::RELATIONSHIPS, self::TYPE_SETS];
        const ICL_TRANSLATIONS = 'icl_translations';
        /**
         * TableNames constructor.
         *
         * @param \wpdb $wpdb
         */
        public function __construct(\wpdb $wpdb)
        {
        }
        /**
         * Determine the full name of a table how it exists (or should exist) in the database for the current site.
         *
         * @param string $table_name One of the well-defined table names from this class.
         *
         * @return string
         */
        public function get_full_table_name($table_name)
        {
        }
        /**
         * Checks if a table exists in the database.
         *
         * @param string $full_table_name Name of the table.
         * @return bool
         * @since 4.0.10
         */
        public function table_exists($full_table_name)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\berlindb {
    /**
     * Base class used for each column for a custom table.
     *
     * @since 4.0
     */
    abstract class Column
    {
        /**
         * @param string $type Type of database column
         * @param string $name Name of database column
         * @param int $length Length of database column
         * @param bool $is_unsigned Is integer unsigned?
         * @param bool $allow_null Is null an allowed value?
         * @param mixed $default_value Typically empty/null, or date value
         * @param string $extra auto_increment, etc...
         * @param string $encoding Typically inherited from wpdb
         * @param string $collation Typically inherited from wpdb
         * @param bool $is_primary Is this the primary column?
         * @param bool $is_uuid Is this the column used as a universally unique identifier?
         * @param callable $validator A callback function used to validate on save.
         * @param array $aliases Array of possible column name aliases.
         *
         */
        public function __construct($type, $name, $length, $encoding = null, $collation = null, $is_unsigned = false, $is_primary = false, $allow_null = true, $default_value = null, $extra = '', $is_uuid = false, $validator = null, $aliases = [])
        {
        }
        /**
         * Return if a column type is numeric or not.
         *
         * @return bool
         */
        public function is_numeric()
        {
        }
        public function is_primary()
        {
        }
        public function get_name()
        {
        }
        /**
         * Fallback to validate a datetime value if no other is set.
         *
         * This assumes NO_ZERO_DATES is off or overridden.
         *
         * If MySQL drops support for zero dates, this method will need to be
         * updated to support different default values based on the environment.
         *
         * @param string $value Default '0000-00-00 00:00:00'. A datetime value that needs validating
         *
         * @return string A valid datetime value
         */
        public function validate_datetime($value = '0000-00-00 00:00:00')
        {
        }
        /**
         * Validate a decimal
         *
         * (Recommended decimal column length is '18,9'.)
         *
         * This is used to validate a mixed value before it is saved into a decimal
         * column in a database table.
         *
         * Uses number_format() which does rounding to the last decimal if your
         * value is longer than specified.
         *
         * @param mixed $value Default empty string. The decimal value to validate
         * @param int $decimals Default 9. The number of decimal points to accept
         *
         * @return float
         */
        public function validate_decimal($value = 0, $decimals = 9)
        {
        }
        /**
         * Validate a UUID.
         *
         * This uses the v4 algorithm to generate a UUID that is used to uniquely
         * and universally identify a given database row without any direct
         * connection or correlation to the data in that row.
         *
         * From http://php.net/manual/en/function.uniqid.php#94959
         *
         * @param string $value The UUID value (empty on insert, string on update)
         *
         * @return string Generated UUID.
         */
        public function validate_uuid($value = '')
        {
        }
        /**
         * Return a string representation of what this column's properties look like
         * in a MySQL.
         *
         * @return string
         * @todo
         */
        public function to_string()
        {
        }
    }
    /**
     * Column of a varchar type.
     *
     * @since 4.0
     */
    class VarcharColumn extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\berlindb\Column
    {
        /**
         * VarcharColumn constructor.
         *
         * @param string $name
         * @param int $length
         * @param bool $allow_null
         * @param string $default
         */
        public function __construct($name, $length, $allow_null = false, $default = '')
        {
        }
    }
    /**
     * Column set up to be used as a primary key.
     *
     * @since 4.0
     */
    class PrimaryKeyColumn extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\berlindb\Column
    {
        const COLUMN_NAME = 'id';
        public function __construct()
        {
        }
    }
    /**
     * Represents a generic database index.
     */
    class Index
    {
        /**
         * Index constructor.
         *
         * @param string $name
         * @param Column[] $columns
         * @param bool $is_primary
         */
        public function __construct($name, array $columns, $is_primary = false)
        {
        }
        /**
         * Build the MySQL syntax that can be used in the CREATE TABLE command, for example.
         *
         * @return string
         */
        public function to_string()
        {
        }
        /**
         * @return bool
         */
        public function is_primary()
        {
        }
    }
    /**
     * Boolean database column implemented as a tinyint.
     *
     * @since 4.0
     */
    class BoolColumn extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\berlindb\Column
    {
        public function __construct($name, $allow_null = false, $default = 0)
        {
        }
    }
    /**
     * A base database table schema class, which houses the collection of columns
     * that a table is made out of.
     *
     * This class is intended to be extended for each unique database table,
     * including global tables for multisite, and users tables.
     *
     * @since 4.0
     */
    class TableSchema
    {
        /**
         * Invoke new column objects based on array of column data.
         *
         * @param Column[] $columns
         * @param Index[] $indexes
         */
        public function __construct(array $columns, array $indexes)
        {
        }
        /**
         * Return the schema in string form.
         *
         * @return string Calls get_create_string() on every column.
         */
        public function to_string()
        {
        }
    }
    class DatabaseInterfaceProvider
    {
        /**
         * DatabaseInterfaceProvider constructor.
         *
         * @param \wpdb $wpdb
         */
        public function __construct(\wpdb $wpdb)
        {
        }
        /**
         * @return \wpdb
         */
        public function get_wpdb()
        {
        }
        /**
         * Check if a wpdb operation succeeded.
         *
         * @param mixed $result
         * @param bool $no_rows_means_success
         *
         * @return \OTGS\Toolset\Common\Result\ResultInterface
         */
        public function parse_result($result = false, $no_rows_means_success = false)
        {
        }
    }
    class SchemaController
    {
        public function __construct($plugin_file = '')
        {
        }
        public function register_hooks()
        {
        }
        /**
         * @param Table $table
         */
        public function register_table(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\berlindb\Table $table)
        {
        }
        public function maybe_upgrade_tables()
        {
        }
        /**
         * Obtain a table definition, if it exists.
         *
         * @param string $table_name Name of an existing table (without prefix).
         * @return Table
         * @throws \InvalidArgumentException
         */
        public function get_table($table_name)
        {
        }
        /**
         * Returns true if all registered tables exist in the database and are up-to-date
         * with the current schema.
         *
         * @return bool
         */
        public function is_everything_up_to_date()
        {
        }
    }
    /**
     * Base database row class.
     *
     * This class exists solely for other classes to extend (and to encapsulate
     * database schema changes for those objects) to help separate the needs of the
     * application layer from the requirements of the database layer.
     *
     * For example, if a database column is renamed or a return value needs to be
     * formatted differently, this class will make sure old values are still
     * supported and new values do not conflict.
     */
    abstract class Row
    {
        /**
         * Construct a database object.
         *
         * @param mixed Null by default, Array/Object if not
         *
         * @since 1.0.0
         *
         */
        public function __construct($item = null)
        {
        }
        /**
         * Magic isset'ter for immutability.
         *
         * @param string $key
         *
         * @return mixed
         * @since 1.0.0
         *
         */
        public function __isset($key = '')
        {
        }
        /**
         * Magic getter for immutability.
         *
         * @param string $key
         *
         * @return mixed
         * @since 1.0.0
         *
         */
        public function __get($key = '')
        {
        }
        /**
         * Determines whether the current row exists.
         *
         * @return bool
         * @since 1.0.0
         */
        public function exists()
        {
        }
        /**
         * Set class variables from arguments.
         *
         * @param array $args
         *
         * @since 1.0.0
         */
        protected function set_vars($args = array())
        {
        }
        public function to_array()
        {
        }
    }
    /**
     * Column for IDs from WordPress (BIGINT(20)).
     *
     * @since 4.0
     */
    class IdColumn extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\berlindb\Column
    {
        public function __construct($name, $allow_null = false, $default = false)
        {
        }
    }
    /**
     * Represents a table index which is based on a single column.
     *
     * @since 4.0
     */
    class SingleColumnIndex extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\berlindb\Index
    {
        /**
         * SingleColumnIndex constructor.
         *
         * @param Column $column
         * @param bool $is_primary
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\berlindb\Column $column, $is_primary = false)
        {
        }
    }
    /**
     * Longtext database column.
     *
     * @since 4.0
     */
    class LongtextColumn extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\berlindb\Column
    {
        public function __construct($name, $allow_null = true, $default = '')
        {
        }
    }
    /**
     * Pseudo-enum for holding MySQL data types.
     *
     * @since 4.0
     */
    abstract class DataType
    {
        const INT = 'INT';
        const BIGINT = 'BIGINT';
        const TINYINT = 'TINYINT';
        const VARCHAR = 'VARCHAR';
        const LONGTEXT = 'LONGTEXT';
    }
    /**
     * A base database table class, which facilitates the creation of (and schema
     * changes to) individual database tables.
     *
     * This class is intended to be extended for each unique database table,
     * including global tables for multisite, and users tables.
     *
     * It exists to make managing database tables as easy as possible.
     *
     * Extending this class comes with several automatic benefits:
     * - Activation hook makes it great for plugins
     * - Tables store their versions in the database independently
     * - Tables upgrade via independent upgrade abstract methods
     * - Multisite friendly - site tables switch on "switch_blog" action
     *
     * @since 4.0
     */
    class Table
    {
        /**
         * Hook into queries, admin screens, and more!
         *
         * @param string $name
         * @param int|string $current_version
         * @param DatabaseInterfaceProvider $database_interface_provider
         * @param TableSchema $schema
         */
        public function __construct($name, $current_version, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\berlindb\DatabaseInterfaceProvider $database_interface_provider, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\berlindb\TableSchema $schema)
        {
        }
        /**
         * Maybe upgrade the database table. Handles creation & schema changes.
         *
         * Hooked to the `admin_init` action.
         *
         * @since 1.0.0
         */
        public function maybe_upgrade()
        {
        }
        /**
         * Return whether this table needs an upgrade.
         *
         * @param mixed $version Database version to check if upgrade is needed
         *
         * @return bool True if table needs upgrading. False if not.
         */
        public function needs_upgrade($version = false)
        {
        }
        /**
         * Return the current table version from the codebase.
         * For obtaining the version from the database, use get_database_version().
         *
         * @return string
         */
        public function get_current_version()
        {
        }
        /**
         * Install a database table by creating the table and setting the version.
         *
         * @since 1.0.0
         */
        public function install()
        {
        }
        /**
         * Destroy a database table by dropping the table and deleting the version.
         *
         * @since 1.0.0
         */
        public function uninstall()
        {
        }
        /**
         * Check if table already exists.
         *
         * @return bool
         * @since 1.0.0
         */
        public function exists()
        {
        }
        /**
         * Check if table already exists.
         *
         * @param string $name
         *
         * @return bool
         * @since 1.0.0
         */
        public function column_exists($name = '')
        {
        }
        /**
         * Create the table.
         *
         * @return bool
         */
        public function create()
        {
        }
        /**
         * Drop the database table.
         *
         * @return bool
         */
        public function drop()
        {
        }
        /**
         * Truncate the database table.
         *
         * @return bool
         */
        public function truncate()
        {
        }
        /**
         * Delete all items from the database table.
         *
         * @return mixed
         */
        public function delete_all()
        {
        }
        /**
         * Count the number of items in the database table.
         *
         * @return int
         */
        public function count()
        {
        }
        /**
         * Upgrade this database table.
         *
         * @since 1.0.0
         * @return \OTGS\Toolset\Common\Result\ResultInterface
         */
        public function upgrade()
        {
        }
        /**
         * Get the table version from the database.
         */
        public function get_database_version()
        {
        }
        /**
         * Sanitize a table name string.
         *
         * Used to make sure that a table name value meets MySQL expectations.
         *
         * Applies the following formatting to a string:
         * - Trim whitespace
         * - No accents
         * - No special characters
         * - No hyphens
         * - No double underscores
         * - No trailing underscores
         *
         * @param string $name The name of the database table
         *
         * @return string Sanitized database table name
         * @since 1.0.0
         *
         */
        protected function sanitize_table_name($name = '')
        {
        }
        /**
         * Name of the table, without a prefix.
         *
         * @return string
         */
        public function get_name()
        {
        }
        /**
         * Full name of the table as it's defined in the MySQL database.
         *
         * @return string
         */
        public function get_full_name()
        {
        }
        public function set_name($name)
        {
        }
    }
    /**
     * Represents a primary key of a table.
     *
     * @since 4.0
     */
    class PrimaryKey extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\berlindb\SingleColumnIndex
    {
        /**
         * PrimaryKey constructor.
         *
         * @param Column $column
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\berlindb\Column $column)
        {
        }
    }
    /**
     * Integer database column.
     *
     * @since 4.0
     */
    class IntColumn extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\berlindb\Column
    {
        public function __construct($name, $is_unsigned = false, $length = 10, $allow_null = false, $default = 0)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration {
    /**
     * If this option is set to a truthy value, it means that the associations are currently being migrated between
     * database layer versions.
     *
     * This can be used to ensure data consistency across the old and new database tables until the migration is completed.
     *
     * @since 4.0.10
     * @codeCoverageIgnore
     */
    class IsMigrationUnderwayOption extends \OTGS\Toolset\Common\Wordpress\Option\AOption
    {
        public function getKey()
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Migration {
    /**
     * Represents a particular migration step (that belongs to a specific migration controller).
     *
     * @since 4.0
     */
    interface MigrationStepInterface
    {
        /**
         * Get the unique identifier of this step.
         *
         * @return string
         */
        public function get_id();
        /**
         * Step number (for the purpose of understanding the progress by a GUI).
         *
         * Doesn't have to be unique or consecutive in all cases.
         *
         * @return int
         */
        public function get_number();
        /**
         * Perform the step based on the provided current state of the database.
         *
         * May throw all sorts of exceptions when things go wrong.
         *
         * @param MigrationStateInterface $previous_state Current state of the database.
         *
         * @return MigrationStateInterface Current state of the database after the step had been run.
         * @throws \Exception
         */
        public function run(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Migration\MigrationStateInterface $previous_state);
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration {
    /**
     * Standard migration step for \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration\MigrationController.
     *
     * @since 4.0
     */
    abstract class MigrationStep implements \OTGS\Toolset\Common\Relationships\DatabaseLayer\Migration\MigrationStepInterface
    {
        const STEP_NUMBER = 0;
        const STORAGE_KEY_PREFIX = '_toolset_migration_step_state_%s';
        /**
         * @inheritDoc
         * @return string
         */
        public function get_id()
        {
        }
        public function get_number()
        {
        }
        protected function validate_state(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Migration\MigrationStateInterface $migration_state)
        {
        }
        /**
         * Determine if a table exists in the database.
         *
         * @param string $table_name
         *
         * @return bool
         */
        protected function table_exists($table_name)
        {
        }
        public function return_error($error_message, $do_rollback = true)
        {
        }
        /**
         * Persists a state variable to database as a WordPress option
         * @param $key
         * @param $value
         */
        protected function persistState($key, $value)
        {
        }
        /**
         * Returns a state variable used by the migration step
         *
         * @param $key
         * @param bool $defaultValue
         *
         * @return bool|mixed|void
         */
        protected function getState($key, $defaultValue = false)
        {
        }
        /**
         * Clear state variable
         *
         * @param $key
         *
         * @return bool
         */
        protected function clearState($key)
        {
        }
    }
    /**
     * Special migration step used for cleaning up the backup association table that is left behind
     * when the migration finishes.
     *
     * After the cleanup, rollback is no longer possible.
     *
     * @since 4.0
     */
    class CleanupStep extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration\MigrationStep
    {
        /**
         * RollbackStep constructor.
         *
         * @param \wpdb $wpdb
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names
         */
        public function __construct(\wpdb $wpdb, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names)
        {
        }
        /**
         * @inheritDoc
         */
        public function run(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Migration\MigrationStateInterface $previous_state)
        {
        }
    }
    /**
     * Ensure the relationship data integrity *while* the migration is underway.
     *
     * This is the only problematic situation that we actually need to handle is when one or more associations are being
     * deleted. If they have already been migrated, they would magically reappear after the migration completes.
     *
     * @since 4.0.10
     */
    class DuringMigrationIntegrity
    {
        /**
         * DuringMigrationIntegrity constructor.
         *
         * @param IsMigrationUnderwayOption $is_migration_underway_option
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names
         * @param \wpdb $wpdb
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Persistence\ConnectedElementPersistence $connected_element_persistence
         * @param \Toolset_Relationship_Definition_Repository $relationship_definition_repository
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration\IsMigrationUnderwayOption $is_migration_underway_option, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names, \wpdb $wpdb, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Persistence\ConnectedElementPersistence $connected_element_persistence, \Toolset_Relationship_Definition_Repository $relationship_definition_repository)
        {
        }
        /**
         * Handle a single association being deleted.
         *
         * Here, we identify its ID in the new associations table and delete it there, too.
         *
         * @param string $relationship_slug
         * @param int $parent_id
         * @param int $child_id
         */
        public function synchronize_deleted_association($relationship_slug, $parent_id, $child_id)
        {
        }
        /**
         * Handle the situation when permanently deleting an element with associations.
         *
         * @param int $element_id
         * @param string $element_domain
         */
        public function synchronize_deleted_associations_by_element($element_id, $element_domain)
        {
        }
    }
    /**
     * Migrate associations to new temporary tables.
     *
     * @since 4.0
     */
    class Step04MigrateAssociations extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration\MigrationStep
    {
        const STEP_NUMBER = 4;
        const NEXT_STEP = \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration\Step05MaintenanceModeOn::class;
        /** @var string Custom property in MigrationStep to store the size of the previous batch. */
        const BATCH_SIZE_KEY = 'batch_size';
        /**
         * @var string Custom property in MigrationStep for the flag to not use a MySQL transaction for
         *     each association batch.
         */
        const NO_SQL_TRANSACTION_KEY = 'no_sql_transaction';
        /**
         * @var string Custom property in MigrationStep that will contain a comma-separated string with
         *     IDs of relationships whose associations should be migrated. The rest will be skipped.
         *     This is for troubleshooting purposes only.
         */
        const ONLY_RELATIONSHIPS_KEY = 'only_relationships';
        const POST_ID_COLUMNS = [\OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableColumns\AssociationTable::PARENT_ID, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableColumns\AssociationTable::CHILD_ID, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableColumns\AssociationTable::INTERMEDIARY_ID];
        const STATE_ASSOCIATION_RESULTS = 'association_results';
        const STATE_LAST_ASSOCIATION_ID = 'last_association_id';
        const STATE_PREVIOUS_LAST_ASSOCIATION_ID = 'previous_last_association_id';
        /**
         * Step04MigrateAssociations constructor.
         *
         * @param \wpdb $wpdb
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names
         * @param \Toolset_Condition_Plugin_Wpml_Is_Active_And_Configured $is_wpml_active
         * @param BatchSizeHelper $batch_size_helper
         */
        public function __construct(\wpdb $wpdb, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names, \Toolset_Condition_Plugin_Wpml_Is_Active_And_Configured $is_wpml_active, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration\BatchSizeHelper $batch_size_helper, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration\IsMigrationUnderwayOption $is_migration_underway_option)
        {
        }
        /**
         * @inheritDoc
         */
        public function run(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Migration\MigrationStateInterface $previous_state)
        {
        }
    }
    /**
     * Test the most probable things that may fail even before we start doing anything.
     *
     * @since 4.0
     */
    class Step01PreMigrationCheck extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration\MigrationStep
    {
        const STEP_NUMBER = 1;
        const NEXT_STEP = \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration\Step02DropTemporaryTables::class;
        const TEMPORARY_TABLE_NAME = 'toolset_migration_precondition_test_table';
        const MINIMAL_REQUIRED_WPML_VERSION = '4.4.0';
        /**
         * Step01PreMigrationCheck constructor.
         *
         * @param \wpdb $wpdb
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerMode $database_layer_mode
         * @param \OTGS\Toolset\Common\WPML\WpmlService $wpml_service
         */
        public function __construct(\wpdb $wpdb, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names, \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerMode $database_layer_mode, \OTGS\Toolset\Common\WPML\WpmlService $wpml_service)
        {
        }
        public function run(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Migration\MigrationStateInterface $previous_state)
        {
        }
    }
    /**
     * Update the current database layer mode in site options.
     *
     * @since 4.0
     */
    class Step07UpdateDbLayerMode extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration\MigrationStep
    {
        const STEP_NUMBER = 7;
        const NEXT_STEP = \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration\Step08MaintenanceModeOff::class;
        /**
         * Step07UpdateDbLayerMode constructor.
         *
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerMode $database_layer_mode
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerMode $database_layer_mode)
        {
        }
        /**
         * @inheritDoc
         */
        public function run(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Migration\MigrationStateInterface $previous_state)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Migration {
    /**
     * Represents a state of the database before, during or after a specific migration.
     *
     * It must contain all the necessary information so that the next migration step can
     * be performed.
     *
     * @since 4.0
     */
    interface MigrationStateInterface extends \Serializable
    {
        /**
         * Store the previous step that had been performed.
         *
         * May be relevant for the next step in some cases.
         *
         * @param string $step_identifier Unique identifier of the previous step.
         * @param int $step_number
         *
         * @return void
         */
        public function set_previous_step($step_identifier, $step_number);
        /**
         * Store a value representing progress that will be relevant for the next step.
         *
         * It may be, for example, the number of processed items.
         *
         * @param int $progress_value
         *
         * @return void
         */
        public function set_progress($progress_value);
        /**
         * Return the progress value if it has been set.
         *
         * @return int|null
         */
        public function get_progress();
        /**
         * Define the step that needs to be performed next.
         *
         * @param string $step_identifier Unique identifier of the migration step.
         *
         * @return void
         */
        public function set_next_step($step_identifier, $step_number);
        /**
         * Get the migration step that needs to be performed next.
         *
         * @return string Unique identifier of a migration step.
         */
        public function get_next_step();
        /**
         * Return the number of the previous step if available.
         *
         * @return int|null
         */
        public function get_previous_step_number();
        /**
         * Return the number of the next step if available.
         *
         * @return int
         */
        public function get_next_step_number();
        /**
         * Set the result of a previous step.
         *
         * @param \OTGS\Toolset\Common\Result\ResultInterface $result
         *
         * @return void
         */
        public function set_result(\OTGS\Toolset\Common\Result\ResultInterface $result);
        /**
         * Get the result of the previous step.
         *
         * If there has been no previous step, a success result should be returned.
         *
         * @return \OTGS\Toolset\Common\Result\ResultInterface
         */
        public function get_result();
        /**
         * Determine whether there is a next migration step.
         *
         * @return bool
         */
        public function can_continue();
        /**
         * Get the number of the current substep (if the step has substeps).
         *
         * Value -1 indicates that substeps exist but the actual number is not known.
         *
         * @return int|null
         */
        public function get_current_substep();
        /**
         * Get the total number of substeps (if the step has substeps).
         *
         * Value -1 indicates that substeps exist but the actual number is not known.
         *
         * @return int|null
         */
        public function get_substep_count();
        /**
         * Set a custom scalar property.
         *
         * @param string $key
         * @param string|int $value
         */
        public function set_property($key, $value);
        /**
         * Get a custom property.
         *
         * @param string $key
         *
         * @return mixed|string|int|null
         */
        public function get_property($key);
    }
    /**
     * Basic migration state implementation that can be reused.
     *
     * @since 4.0
     */
    abstract class AbstractMigrationState implements \OTGS\Toolset\Common\Relationships\DatabaseLayer\Migration\MigrationStateInterface
    {
        /** @var string|null */
        protected $previous_step_identifier;
        /** @var string|null */
        protected $next_step_identifier;
        /** @var int|null */
        protected $progress_value;
        /** @var \OTGS\Toolset\Common\Result\ResultInterface|null */
        protected $result;
        /** @var array */
        protected $properties = [];
        /** @var int|null */
        protected $previous_step_number;
        /** @var int|null */
        protected $next_step_number;
        /**
         * @inheritDoc
         */
        public function serialize()
        {
        }
        /**
         * @inheritDoc
         */
        public function unserialize($serialized)
        {
        }
        /**
         * @inheritDoc
         */
        public function set_previous_step($step_identifier, $step_number)
        {
        }
        public function get_next_step()
        {
        }
        /**
         * @inheritDoc
         */
        public function set_progress($progress_value)
        {
        }
        /**
         * @inheritDoc
         * @return int|null
         */
        public function get_progress()
        {
        }
        /**
         * @inheritDoc
         */
        public function set_next_step($step_identifier, $step_number)
        {
        }
        /**
         * @inheritDoc
         */
        public function set_result(\OTGS\Toolset\Common\Result\ResultInterface $result)
        {
        }
        /**
         * @inheritDoc
         */
        public function get_result()
        {
        }
        /**
         * @inheritDoc
         */
        public function can_continue()
        {
        }
        /**
         * @inheritDoc
         */
        public function set_property($key, $value)
        {
        }
        /**
         * @inheritDoc
         */
        public function get_property($key)
        {
        }
        /**
         * @inheritDoc
         */
        public function get_previous_step_number()
        {
        }
        /**
         * @inheritDoc
         */
        public function get_next_step_number()
        {
        }
        /**
         * @inheritDoc
         */
        public function get_substep_count()
        {
        }
        /**
         * @inheritDoc
         */
        public function get_current_substep()
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration {
    /**
     * Represents a migration state after an error.
     *
     * @since 4.0
     */
    class ErrorState extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Migration\AbstractMigrationState
    {
        const ROLLBACK_STEP = \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration\RollbackStep::class;
        /**
         * ErrorState constructor.
         *
         * @param string $message Error message.
         * @param bool $do_rollback Whether the next step should be a rollback (or nothing). True by default.
         */
        public function __construct($message, $do_rollback = true)
        {
        }
    }
    /**
     * Turn off the maintenance mode, which concludes the migration.
     *
     * @since 4.0
     */
    class Step08MaintenanceModeOff extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration\MigrationStep
    {
        const STEP_NUMBER = 8;
        /**
         * Step01MaintenanceModeOn constructor.
         *
         * @param \OTGS\Toolset\Common\MaintenanceMode\Controller $maintenance_mode
         */
        public function __construct(\OTGS\Toolset\Common\MaintenanceMode\Controller $maintenance_mode)
        {
        }
        /**
         * @inheritDoc
         */
        public function run(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Migration\MigrationStateInterface $previous_state)
        {
        }
    }
    /**
     * Standard migration state for the \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration\MigrationController.
     *
     * @since 4.0
     */
    class MigrationState extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Migration\AbstractMigrationState
    {
        const SUBSTEP_COUNT_KEY = 'substep_count';
        const CURRENT_SUBSTEP_KEY = 'current_substep';
        /**
         * MigrationState constructor.
         *
         * @param string|null $next_step_identifier
         * @param int|null $progress
         * @param \OTGS\Toolset\Common\Result\SingleResult|null $result
         * @param string|null $previous_step_identifier
         * @param int|null $previous_step_number
         * @param int|null $next_step_number
         */
        public function __construct($next_step_identifier = null, $progress = null, $result = null, $previous_step_identifier = null, $previous_step_number = null, $next_step_number = null)
        {
        }
        /**
         * @inheritDoc
         */
        public function get_current_substep()
        {
        }
        /**
         * @inheritDoc
         */
        public function get_substep_count()
        {
        }
        /**
         * Set the current substep and total substep count.
         *
         * @param int $current_substep
         * @param int $substep_count
         */
        public function set_substep_info($current_substep, $substep_count)
        {
        }
    }
    class NothingToDoState extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration\MigrationState
    {
        public function __construct(\OTGS\Toolset\Common\Result\ResultInterface $result, $last_step_identifier = null)
        {
        }
    }
    /**
     * Migration step if something doesn't work out - drop the new version of the association table
     * and replace it by the old, backed-up one. If the backup doesn't exist, don't do anything.
     *
     * @since 4.0
     */
    class RollbackStep extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration\MigrationStep
    {
        /**
         * RollbackStep constructor.
         *
         * @param \wpdb $wpdb
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerMode $database_layer_mode
         * @param \OTGS\Toolset\Common\MaintenanceMode\Controller $maintenance_mode
         */
        public function __construct(\wpdb $wpdb, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names, \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerMode $database_layer_mode, \OTGS\Toolset\Common\MaintenanceMode\Controller $maintenance_mode)
        {
        }
        /**
         * @inheritDoc
         */
        public function run(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Migration\MigrationStateInterface $previous_state)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Migration {
    /**
     * Represents a migration controller that can handle the switch between two different versions
     * of a database layer.
     *
     * @since 4.0
     */
    interface MigrationControllerInterface
    {
        /**
         * Identifiers of database layer modes which allow for this migration to run.
         *
         * @return string[]
         */
        public function get_required_database_layer_modes();
        /**
         * Identifier of the database layer mode that will be active after this migration.
         *
         * @return string
         */
        public function get_target_database_layer_mode();
        /**
         * True if migration can be started under current circumstances.
         *
         * @return bool
         */
        public function can_migrate();
        /**
         * Return the initial state that can be used in do_next_step() to begin the migration.
         *
         * @return MigrationStateInterface
         */
        public function get_initial_state();
        /**
         * Perform the next step of the migration, based on the current state, and
         * return the updated state.
         *
         * May throw all sorts of exceptions when things go wrong.
         *
         * @param MigrationStateInterface $current_state
         *
         * @return MigrationStateInterface
         * @throws \Exception
         */
        public function do_next_step(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Migration\MigrationStateInterface $current_state);
        /**
         * Produce a correct migration state from the serialized string.
         *
         * May throw all sorts of exceptions when things go wrong.
         *
         * @param string $serialized
         *
         * @return MigrationStateInterface
         * @throws \Exception
         */
        public function unserialize_migration_state($serialized);
        /**
         * Determine if there are some data left after the migration that can be cleaned up.
         *
         * @return bool
         */
        public function can_do_cleanup();
        /**
         * Perform the after-migration cleanup.
         *
         * @return \OTGS\Toolset\Common\Result\ResultInterface
         */
        public function do_cleanup();
        /**
         * Determine if it's possible to revert the database to the pre-migration state.
         *
         * @return bool
         */
        public function can_do_rollback();
        /**
         * Perform the rollback to the pre-migration state (assuming nothing relevant had changed on the site
         * in the meantime).
         *
         * @return \OTGS\Toolset\Common\Result\ResultInterface
         */
        public function do_rollback();
        /**
         * Check whether the whole migration can be performed during a single request.
         *
         * This will probably include some estimation based on the size of the data to process.
         *
         * @return bool
         */
        public function can_migrate_in_one_shot();
        /**
         * Perform the whole migration in one request (if possible).
         *
         * @return \OTGS\Toolset\Common\Result\ResultInterface
         */
        public function migrate_in_one_shot();
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration {
    /**
     * Controller for the migration between the first and second version of the database layer.
     *
     * @since 4.0
     */
    class MigrationController implements \OTGS\Toolset\Common\Relationships\DatabaseLayer\Migration\MigrationControllerInterface
    {
        const TEMPORARY_OLD_ASSOCIATION_TABLE_NAME = 'toolset_associations_old';
        const TEMPORARY_NEW_ASSOCIATION_TABLE_NAME = 'toolset_associations_new';
        /**
         * MigrationController constructor.
         *
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerMode $database_layer_mode
         * @param \wpdb $wpdb
         * @param BatchSizeHelper $batch_size_helper
         * @param IsMigrationUnderwayOption $is_migration_underway_option
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerMode $database_layer_mode, \wpdb $wpdb, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration\BatchSizeHelper $batch_size_helper, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration\IsMigrationUnderwayOption $is_migration_underway_option)
        {
        }
        /**
         * @inheritDoc
         * @return string[]
         */
        public function get_required_database_layer_modes()
        {
        }
        /**
         * @inheritDoc
         * @return string
         */
        public function get_target_database_layer_mode()
        {
        }
        /**
         * @inheritDoc
         * @return bool|\OTGS\Toolset\Common\Result\SingleResult
         */
        public function can_migrate()
        {
        }
        /**
         * @inheritDoc
         */
        public function get_initial_state()
        {
        }
        /**
         * @inheritDoc
         */
        public function do_next_step(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Migration\MigrationStateInterface $current_state)
        {
        }
        /**
         * @param string $serialized
         *
         * @return MigrationState
         */
        public function unserialize_migration_state($serialized)
        {
        }
        /**
         * @inheritDoc
         */
        public function can_do_cleanup()
        {
        }
        /**
         * @inheritDoc
         */
        public function do_cleanup()
        {
        }
        /**
         * @inheritDoc
         */
        public function can_do_rollback()
        {
        }
        /**
         * @inheritDoc
         */
        public function do_rollback()
        {
        }
        /**
         * @inheritDoc
         */
        public function can_migrate_in_one_shot()
        {
        }
        /**
         * @inheritDoc
         */
        public function migrate_in_one_shot()
        {
        }
    }
    /**
     * Create the temporary association table with new structure and the table for connected elements.
     *
     * @since 4.0
     */
    class Step03CreateNewTables extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration\MigrationStep
    {
        const STEP_NUMBER = 3;
        const NEXT_STEP = \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration\Step04MigrateAssociations::class;
        /**
         * Step03CreateNewTables constructor.
         *
         * @param \wpdb $wpdb
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\DatabaseStructure $database_structure
         */
        public function __construct(\wpdb $wpdb, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\DatabaseStructure $database_structure)
        {
        }
        /**
         * @inheritDoc
         */
        public function run(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Migration\MigrationStateInterface $previous_state)
        {
        }
    }
    /**
     * Second step: Drop temporary tables that might have been created during a previous migration attempt.
     *
     * Note that the association table with original data is never deleted during the migration process,
     * so it should be always safe to revert, unless someone messes with the database directly.
     *
     * We handle the toolset_connected_elements table as a temporary, too, because the only way it could
     * have been created until now was during a previous migration attempt.
     *
     * @since 4.0
     */
    class Step02DropTemporaryTables extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration\MigrationStep
    {
        const STEP_NUMBER = 2;
        const NEXT_STATE = \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration\Step03CreateNewTables::class;
        /**
         * Step02DropTemporaryTables constructor.
         *
         * @param \wpdb $wpdb
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names
         */
        public function __construct(\wpdb $wpdb, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names)
        {
        }
        /**
         * @inheritDoc
         */
        public function run(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Migration\MigrationStateInterface $previous_state)
        {
        }
    }
    /**
     * Turn on the maintenance mode before doing anything that may affect the site operation.
     *
     * @since 4.0
     */
    class Step05MaintenanceModeOn extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration\MigrationStep
    {
        const STEP_NUMBER = 5;
        const NEXT_STEP = \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration\Step06RenameTables::class;
        /**
         * Step01MaintenanceModeOn constructor.
         *
         * @param \OTGS\Toolset\Common\MaintenanceMode\Controller $maintenance_mode
         */
        public function __construct(\OTGS\Toolset\Common\MaintenanceMode\Controller $maintenance_mode)
        {
        }
        /**
         * @inheritDoc
         */
        public function run(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Migration\MigrationStateInterface $previous_state)
        {
        }
    }
    /**
     * Functionality related to the migration batch size.
     *
     * @since 4.0
     */
    class BatchSizeHelper
    {
        /** @var int How many posts per batch do we want to have by default. */
        const DEFAULT_BATCH_SIZE = 250;
        /**
         * BatchSizeHelper constructor.
         *
         * @param \wpdb $wpdb
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names
         */
        public function __construct(\wpdb $wpdb, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names)
        {
        }
        /**
         * Obtain the expected size of the migration batch.
         *
         * @return int
         */
        public static function get_batch_size()
        {
        }
        /**
         * Determine how many associations to migrate are there.
         *
         * @return int
         */
        public function count_old_associations($relationship_constraints = [])
        {
        }
    }
    /**
     * Start using the new table.
     *
     * The current association table will be backed up with an '_old' prefix and the temporary '_new' table
     * takes its place (the '_new' prefix will be removed).
     *
     * @since 4.0
     */
    class Step06RenameTables extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration\MigrationStep
    {
        const STEP_NUMBER = 6;
        const NEXT_STEP = \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration\Step07UpdateDbLayerMode::class;
        /**
         * Step06RenameTables constructor.
         *
         * @param \wpdb $wpdb
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names
         */
        public function __construct(\wpdb $wpdb, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names)
        {
        }
        /**
         * @inheritDoc
         */
        public function run(\OTGS\Toolset\Common\Relationships\DatabaseLayer\Migration\MigrationStateInterface $previous_state)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2 {
    /**
     * Various database operations with associations.
     *
     * Intended for highly specific edge cases, cleanup routines, etc.
     *
     * Note: Please try to not make this class grow any further.
     */
    class AssociationDatabaseOperations implements \OTGS\Toolset\Common\Relationships\API\AssociationDatabaseOperations
    {
        /**
         * AssociationDatabaseOperations constructor.
         *
         * @param \Toolset_Relationship_Definition_Repository $definition_repository
         * @param \wpdb $wpdb
         * @param TableNames $table_names
         * @param \Toolset_Element_Factory $element_factory
         * @param \OTGS\Toolset\Common\WPML\WpmlService $wpml_service
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Persistence\ConnectedElementPersistence $connected_element_persistence
         */
        public function __construct(\Toolset_Relationship_Definition_Repository $definition_repository, \wpdb $wpdb, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\TableNames $table_names, \Toolset_Element_Factory $element_factory, \OTGS\Toolset\Common\WPML\WpmlService $wpml_service, \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Persistence\ConnectedElementPersistence $connected_element_persistence)
        {
        }
        /**
         * @inheritDoc
         */
        public function create_association($relationship_definition_source, $parent_source, $child_source, $intermediary_id, $instantiate = true)
        {
        }
        /**
         * @inheritDoc
         */
        public function delete_associations_by_element($relationship, $element_role_name, $element_id)
        {
        }
        /**
         * @inheritDoc
         */
        public function delete_association_by_element_in_any_role(\IToolset_Element $element)
        {
        }
        /**
         * @inheritDoc
         */
        public function delete_associations_by_relationship($relationship_row_id)
        {
        }
        /**
         * @inheritDoc
         */
        public function delete_association(\IToolset_Association $association)
        {
        }
        /**
         * @inheritDoc
         */
        public function delete_intermediary_posts_by_element($relationship, $element_role_name, $element_id)
        {
        }
        /**
         * @inheritDoc
         */
        public function update_associations_on_definition_renaming(\IToolset_Relationship_Definition $old_definition, \IToolset_Relationship_Definition $new_definition)
        {
        }
        /**
         * @inheritDoc
         * @throws \Toolset_Element_Exception_Element_Doesnt_Exist
         */
        public function update_association_intermediary_id($association_id, $intermediary_id)
        {
        }
        /**
         * @inheritDoc
         */
        public function count_max_associations($relationship_id, $role_name)
        {
        }
        /**
         * @inheritDoc
         */
        public function get_dangling_intermediary_posts(array $intermediary_post_types, array $post_types_to_delete_by)
        {
        }
        /**
         * @inheritDoc
         */
        public function requires_default_language_post()
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships\DatabaseLayer {
    /**
     * Factory for the database layer of the Relationships module.
     *
     * Chooses the correct instance according to the current database layer version.
     *
     * Note: It is rather important that this class is treated as a singleton, it has impact on various caches
     * in classes it provides.
     *
     * @since 4.0
     * @codeCoverageIgnore
     */
    class DatabaseLayerFactory
    {
        /**
         * DatabaseLayerFactory constructor.
         *
         * @param DatabaseLayerMode $database_layer_mode
         * @param \wpdb $wpdb
         * @param \OTGS\Toolset\Common\WPML\WpmlService $wpml_service
         * @param \Toolset_Element_Factory $element_factory
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerMode $database_layer_mode, \wpdb $wpdb, \OTGS\Toolset\Common\WPML\WpmlService $wpml_service, \Toolset_Element_Factory $element_factory)
        {
        }
        /**
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQuery
         */
        public function association_query()
        {
        }
        /**
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipQuery
         */
        public function relationship_query()
        {
        }
        /**
         * @param \IToolset_Relationship_Definition $for_relationship
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role
         * @param \IToolset_Element $for_element
         * @param array $args
         *
         * @return \OTGS\Toolset\Common\Relationships\API\PotentialAssociationQuery
         */
        public function potential_association_query(\IToolset_Relationship_Definition $for_relationship, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role, \IToolset_Element $for_element, $args = array())
        {
        }
        /**
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationDatabaseOperations
         */
        public function association_database_operations()
        {
        }
        /**
         * @return RelationshipDatabaseOperations
         */
        public function relationship_database_operations()
        {
        }
        /**
         * @param \IToolset_Relationship_Definition $relationship
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $target_role
         * @param \IToolset_Element $for_element
         *
         * @return PotentialAssociation\JoinManager
         */
        public function potential_association_table_join_manager(\IToolset_Relationship_Definition $relationship, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $target_role, \IToolset_Element $for_element)
        {
        }
        /**
         * @param \IToolset_Relationship_Definition $relationship
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $target_role Target role of the relationships (future role of
         *     the posts that are being queried)
         * @param \IToolset_Element $for_element ID of the element to check against.
         * @param PotentialAssociation\JoinManager $join_manager
         *
         * @return PotentialAssociation\WpQueryAdjustment
         */
        public function distinct_relationship_posts(\IToolset_Relationship_Definition $relationship, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $target_role, \IToolset_Element $for_element, \OTGS\Toolset\Common\Relationships\DatabaseLayer\PotentialAssociation\JoinManager $join_manager)
        {
        }
        /**
         * @param \IToolset_Relationship_Definition $relationship
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $target_role
         * @param \IToolset_Element $for_element
         * @param PotentialAssociation\JoinManager $join_manager
         *
         * @return PotentialAssociation\WpQueryAdjustment
         */
        public function cardinality_query_posts(\IToolset_Relationship_Definition $relationship, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $target_role, \IToolset_Element $for_element, \OTGS\Toolset\Common\Relationships\DatabaseLayer\PotentialAssociation\JoinManager $join_manager)
        {
        }
        /**
         * @param \IToolset_Relationship_Definition $relationship
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $target_role
         * @param \IToolset_Element $for_element
         * @param PotentialAssociation\JoinManager $join_manager
         *
         * @return PotentialAssociation\PostResultOrder
         */
        public function post_result_order_adjustments(\IToolset_Relationship_Definition $relationship, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $target_role, \IToolset_Element $for_element, \OTGS\Toolset\Common\Relationships\DatabaseLayer\PotentialAssociation\JoinManager $join_manager)
        {
        }
        /**
         * @param array $args Query arguments.
         *
         * @return \WP_Query
         */
        public function wp_query($args)
        {
        }
        /**
         * @return AssociationPersistence
         */
        public function association_persistence()
        {
        }
        /**
         * @return \OTGS\Toolset\Common\WpQueryExtension\AbstractRelationshipsExtension
         */
        public function wp_query_extension()
        {
        }
        /**
         * @return Version1\Toolset_Wp_Query_Adjustments_Table_Join_Manager|Version2\WpQueryExtension\JoinManager
         */
        public function join_manager_for_wp_query_extension()
        {
        }
        /**
         * Get an instance of a relevant migration controller.
         *
         * @param string|null $from_layer The database layer from which to migrate. By default, the current
         *     layer will be used. Be careful that during the migration process, the database layer mode will
         *     likely change and obtaining the controller by using the default will stop working.
         *
         * @return \OTGS\Toolset\Common\Relationships\DatabaseLayer\Migration\MigrationControllerInterface
         * @throws \RuntimeException When no migration controller can be provided.
         */
        public function migration_controller($from_layer = null)
        {
        }
        /**
         * @return DatabaseLayerMode
         */
        public function database_layer_mode()
        {
        }
        /**
         * Note that this is available only in the second version of the database layer.
         *
         * @return \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Persistence\ConnectedElementPersistence
         * @throws \RuntimeException
         */
        public function connected_element_persistence()
        {
        }
        /**
         * @param \Toolset_Association_Cleanup_Factory|null $cleanup_factory
         *
         * @return \OTGS\Toolset\Common\Relationships\DatabaseLayer\Cleanup\PostCleanupInterface
         */
        public function post_cleanup(\Toolset_Association_Cleanup_Factory $cleanup_factory = null)
        {
        }
        /**
         * Only valid for the second version of the database layer.
         *
         * @return \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Persistence\WpmlTranslationUpdate\WpmlTranslationUpdateHandler
         */
        public function wpml_translation_update_handler()
        {
        }
        /**
         * @param \IToolset_Relationship_Definition|null $relationship_definition
         *
         * @return \OTGS\Toolset\Common\Relationships\API\IntermediaryPostPersistence
         */
        public function intermediary_post_persistence(\IToolset_Relationship_Definition $relationship_definition = null)
        {
        }
        /**
         * @return TableExistenceCheck
         */
        public function table_existence_check()
        {
        }
        /**
         * @return \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version2\Migration\DuringMigrationIntegrity
         * @since 4.0.10
         */
        public function during_migration_compatibility()
        {
        }
    }
}
namespace {
    /**
     * Skeleton of cache for relationship queries.
     *
     * So far it does nothing but it's already being called by Toolset_Relationship_Query.
     *
     * @since m2m
     */
    class Toolset_Relationship_Query_Cache
    {
        public static function get_instance()
        {
        }
        /**
         * Get a cached value.
         *
         * @param array $query Query arguments for Toolset_Relationship_Query
         * @param string $subject Name of the subject (what cache to access).
         *
         * @return array|false An array of cached results for given arguments or false if not available.
         */
        public function get($query, $subject)
        {
        }
        /**
         * Add a value to the cache.
         *
         * @param array $query Query arguments for Toolset_Relationship_Query.
         * @param string $subject Name of the subject (what cache to access).
         * @param array $results Results to be cached.
         */
        public function set($query, $subject, $results)
        {
        }
    }
    /**
     * Interface IToolset_Relationship_Origin
     *
     * @since m2m
     */
    interface IToolset_Relationship_Origin
    {
        /**
         * Returns the origin keyword (which will also be stored in the database)
         * @return string
         */
        public function get_origin_keyword();
        /**
         * Should the relationship be shown on the relationships overview page (Toolset->Relationships)
         * @return bool
         */
        public function show_on_page_relationships();
        /**
         * Should the relationship be shown on post edit screens
         * @return bool
         */
        public function show_on_post_edit_screen();
    }
    /**
     * Class Toolset_Relationship_Origin_Repeatable_Group
     *
     * Relationship, which was created by adding a new repeatable group
     *
     * @since m2m
     */
    class Toolset_Relationship_Origin_Repeatable_Group implements \IToolset_Relationship_Origin
    {
        const ORIGIN_KEYWORD = 'repeatable_group';
        /**
         * Returns the origin keyword (which will also be stored in the database)
         * @return string
         */
        public function get_origin_keyword()
        {
        }
        /**
         * @return bool
         */
        public function show_on_page_relationships()
        {
        }
        public function show_on_post_edit_screen()
        {
        }
    }
    /**
     * Class Toolset_Relationship_Origin_Post_Reference_Field
     *
     * Relationship, which was created by adding a post reference field
     * (So far same rules as Toolset_Relationship_Origin_Repeatable_Group)
     *
     * @since m2m
     */
    class Toolset_Relationship_Origin_Post_Reference_Field extends \Toolset_Relationship_Origin_Repeatable_Group
    {
        const ORIGIN_KEYWORD = 'post_reference_field';
        /**
         * Returns the origin keyword (which will also be stored in the database)
         * @return string
         */
        public function get_origin_keyword()
        {
        }
    }
    /**
     * Class Toolset_Relationship_Origin_Wizard
     *
     * Relationship, which was created by using the relationship wizard
     *
     * @since m2m
     */
    class Toolset_Relationship_Origin_Wizard implements \IToolset_Relationship_Origin
    {
        const ORIGIN_KEYWORD = 'wizard';
        /**
         * Returns the origin keyword (which will also be stored in the database)
         * @return string
         */
        public function get_origin_keyword()
        {
        }
        /**
         * @return bool
         */
        public function show_on_page_relationships()
        {
        }
        public function show_on_post_edit_screen()
        {
        }
    }
}
namespace OTGS\Toolset\Common\Relationships {
    /**
     * Main controller class for object relationships in Toolset.
     *
     * initialize() needs to be called during init on every request, and no relationship functionality can be
     * used before then.
     *
     * Always use this as a singleton in the production code.
     *
     * @since m2m
     */
    class MainController
    {
        const IS_M2M_ENABLED_OPTION = 'toolset_is_m2m_enabled';
        const IS_M2M_ENABLED_YES_VALUE = 'yes';
        // This is not a typo. Initially, we had 'no', but then we changed the algorithm to determine the initial
        // m2m state, and we have force re-checking.
        const IS_M2M_ENABLED_NO_VALUE = 'noo';
        /**
         * We need WPML to fire certain actions when it updates its icl_translations table.
         */
        const MINIMAL_WPML_VERSION = '3.9.3';
        /**
         * @return MainController
         */
        public static function get_instance()
        {
        }
        /**
         * Toolset_Relationship_Controller constructor.
         *
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory|null $database_layer_factory_di
         * @param \Toolset_Association_Cleanup_Factory|null $cleanup_factory_di
         */
        public function __construct(\OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory $database_layer_factory_di = null, \Toolset_Association_Cleanup_Factory $cleanup_factory_di = null)
        {
        }
        /**
         * Returns the value of the m2m feature toggle.
         *
         * Default value depends on the presence of legacy post relationships on the site.
         *
         * The result is cached.
         *
         * @return bool
         */
        public function is_m2m_enabled()
        {
        }
        public function reset()
        {
        }
        /**
         * Full initialization that is needed before any relationships-related action takes place.
         *
         * @since m2m
         */
        public function initialize()
        {
        }
        /**
         * Backward compatibility measure. This method is no longer necessary to call.
         *
         * @deprecated
         * @since 4.0
         */
        public function initialize_full()
        {
        }
        public function is_fully_initialized()
        {
        }
        /**
         * Force the autoloader classmap registration when usage of m2m API classes is necessary even
         * with m2m not enabled.
         *
         * @since m2m
         */
        public function force_autoloader_initialization()
        {
        }
        /**
         * On change of cpt slug.
         * Method to prevent any misconfiguration/duplicated actions by callers.
         *
         * @since 3.0.7 (only the function to add the action, the action itself is added since 2.5.6)
         */
        public function add_action_to_wpcf_post_type_renamed()
        {
        }
        /**
         * Counter part of add_action_to_wpcf_post_type_renamed()
         *
         * @since 3.0.7
         */
        public function remove_action_of_wpcf_post_type_renamed()
        {
        }
        /**
         * Hooked into the wpcf_post_type_renamed action.
         * To update the slug in the relationship definition when the cpt slug is changed on the cpt edit page.
         *
         * @param $new_slug
         * @param $old_slug
         *
         * @since 2.5.6
         */
        public function on_types_cpt_rename_slug($new_slug, $old_slug)
        {
        }
    }
}
namespace {
    /**
     * Factory for instantiating query classes.
     *
     * Should be extendended for association query and all others within the m2m project.
     *
     * @since m2m
     * @deprecated use \OTGS\Toolset\Common\Relationships\API\Factory instead.
     */
    class Toolset_Relationship_Query_Factory
    {
        /**
         * @param $args
         *
         * @return Toolset_Relationship_Query
         * @deprecated Use Toolset_Relationship_Query_V2 instead.
         */
        public function relationships($args)
        {
        }
        /**
         * @return \OTGS\Toolset\Common\Relationships\API\RelationshipQuery
         */
        public function relationships_v2()
        {
        }
        /**
         * @param array $args Query arguments.
         *
         * @return WP_Query
         */
        public function wp_query($args)
        {
        }
        /**
         * @param $args
         *
         * @return Toolset_Association_Query
         * @deprecated Use associations_v2() instead.
         */
        public function associations($args)
        {
        }
        /**
         * @return \OTGS\Toolset\Common\Relationships\API\AssociationQuery
         */
        public function associations_v2()
        {
        }
    }
    /**
     * Factory for IToolset_Potentional_Association_Query.
     *
     * Detects the target domain and returns the proper factory instance.
     *
     * @since m2m
     * @deprecated Use \OTGS\Toolset\Common\Relationships\API\Factory::potential_association_query() instead.
     */
    class Toolset_Potential_Association_Query_Factory
    {
        public function __construct()
        {
        }
        /**
         * @param IToolset_Relationship_Definition $for_relationship
         * @param \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role
         * @param IToolset_Element $for_element
         * @param array $args
         *
         * @return \OTGS\Toolset\Common\Relationships\API\PotentialAssociationQuery
         * @throws RuntimeException
         */
        public function create(\IToolset_Relationship_Definition $for_relationship, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role, \IToolset_Element $for_element, $args = array())
        {
        }
    }
    /**
     * Handles the persistence of intermediary posts.
     *
     * @since m2m
     * @deprecated Use \OTGS\Toolset\Common\Relationships\API\Factory::low_level_gateway() and then
     *     the intermediary_post_persistence() to instantiate this class (or DatabaseLayerFactory if within the
     *     \OTGS\Toolset\Common\Relationships\DatabaseLayer\ namespace.
     */
    class Toolset_Association_Intermediary_Post_Persistence implements \OTGS\Toolset\Common\Relationships\API\IntermediaryPostPersistence
    {
        /**
         * Toolset_Association_Intermediary_Post_Persistence constructor.
         *
         * @param IToolset_Relationship_Definition|null $relationship_definition
         * @param \OTGS\Toolset\Common\WPML\WpmlService|null $wpml_service_di
         * @param \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory|null $database_layer_factory
         * @noinspection PhpUnusedParameterInspection
         */
        public function __construct(\IToolset_Relationship_Definition $relationship_definition = \null, \OTGS\Toolset\Common\WPML\WpmlService $wpml_service_di = \null, \OTGS\Toolset\Common\Relationships\DatabaseLayer\DatabaseLayerFactory $database_layer_factory = \null)
        {
        }
        const DEFAULT_LIMIT = 50;
        public function create_intermediary_post($parent_id, $child_id)
        {
        }
        public function create_empty_associations_intermediary_posts($limit = 0)
        {
        }
        public function remove_associations_intermediary_posts($limit = 0)
        {
        }
        public function create_empty_association_intermediary_post($association)
        {
        }
        public function maybe_delete_intermediary_post(\IToolset_Association $association)
        {
        }
        public function delete_intermediary_post($post_id)
        {
        }
    }
    /**
     * Condition to query associations that don't belong to a relationship.
     *
     * @since 2.5.8
     * @deprecated This MUST NOT be used any further.
     */
    class Toolset_Association_Query_Condition_Exclude_Relationship extends \OTGS\Toolset\Common\Relationships\DatabaseLayer\Version1\Toolset_Association_Query_Condition_Relationship_Id
    {
        /**
         * Returns condition operator
         *
         * @return string
         * @since m2m
         */
        protected function get_operator()
        {
        }
    }
    /**
     * Base for custom query classes.
     *
     * Contains shared methods, mainly related to query argument processing.
     *
     * @since m2m
     * @deprecated Needed only for old association and relationship query classes. Remove when those are removed.
     */
    abstract class Toolset_Relationship_Query_Base
    {
        /** @var array Query arguments. */
        protected $query;
        /** @var array Parsed and sanitized query vars (QUERY_* keys). */
        protected $query_vars;
        /** @var bool Use cached results if they're available?  */
        protected $use_cached_results;
        /** @var bool Update the cache with query results? */
        protected $cache_results;
        /**
         * If SELECT FOUND_ROWS() must be run or not
         *
         * @var boolean
         * @since m2m
         */
        protected $dont_count_found_rows;
        /**
         * Rows found
         *
         * @var int
         * @since m2m
         */
        protected $rows_found;
        /**
         * Toolset_Relationship_Query constructor.
         *
         * @param array $query Query arguments.
         */
        public function __construct($query)
        {
        }
        abstract protected function parse_query($query);
        /**
         * Parse a single query argument.
         *
         * @param array $query Query arguments.
         * @param string $var_name Name of the selected argument.
         * @param null|callable $sanitize If a callable is provided, it will be used as a sanitizing function. It needs to
         *     accept one parameter and return the sanitized value.
         * @param null|mixed $default_value Value to be used if the argument is not set at all.
         * @param null|array $allowed_list List of allowed values of the argument or null for no limitation. If $default_value
         *     is used, this parameter is ignored.
         */
        protected function parse_query_arg($query, $var_name, $sanitize = \null, $default_value = \null, $allowed_list = \null)
        {
        }
        /**
         * Check if a query variable is set.
         *
         * @param string $var_name
         * @return bool
         */
        protected function has_query_var($var_name)
        {
        }
        /**
         * Get a query variable.
         *
         * @param string $var_name
         * @return mixed|null Variable value or null if it's not set.
         */
        protected function get_query_var($var_name)
        {
        }
        abstract protected function get_subject_name_for_cache();
        /**
         * Perform the query and get results.
         *
         * Depending on query arguments, the results may be cached.
         *
         * @return int[]|Toolset_Element[]|Toolset_Association_Base[] Array of results, depending on query arguments.
         */
        public function get_results()
        {
        }
        /**
         * Second argument for $wpdb->get_results().
         *
         * @return string
         */
        protected function get_results_type()
        {
        }
        /**
         * Build the MySQL statement for querying the data, depending on query variables.
         *
         * @return string MySQL query statement.
         * @since m2m
         */
        abstract protected function build_sql_statement();
        /**
         * Process raw output from $wpdb.
         *
         * @param $rows
         * @return array
         */
        abstract protected function postprocess_results($rows);
        /**
         * Gets the number of rows found
         *
         * @return int
         * @since m2m
         */
        public function get_rows_found()
        {
        }
    }
    /**
     * A class for querying associations and associated elements.
     *
     * Usage:
     *
     *     $query = new Toolset_Association_Query( $args );
     *     $results = $query->get_results();
     *
     * Notes:
     *
     *   - For now, it supports only the native associations (they're the only ones we have).
     *   - If you need to query by some parameters that are not supported, either create a feature request about it or
     *     submit a merge request rather than going around the query and touching the database directly.
     *
     * WARNING: This got deprecated and was turned into a complatibility layer for Toolset_Association_Query_V2.
     *
     * @since m2m
     * @deprecated Since 2.5.8. Use AssociationQuery instead.
     */
    class Toolset_Association_Query extends \Toolset_Relationship_Query_Base
    {
        /** @var bool */
        protected $dont_count_found_rows;
        const OPTION_USE_CACHED_RESULTS = 'use_cached_results';
        const OPTION_CACHE_RESULTS = 'cache_results';
        const OPTION_RETURN = 'return';
        const OPTION_DONT_COUNT_FOUND_ROWS = 'no_found_rows';
        const QUERY_OFFSET = 'offset';
        const QUERY_LIMIT = 'limit';
        const QUERY_SELECT_FIELDS = 'select_fields';
        const QUERY_RELATIONSHIP_SLUG = 'relationship_slug';
        const QUERY_INTERMEDIARY_ID = 'intermediary_id';
        const QUERY_RELATIONSHIP_ID = 'relationship_id';
        const QUERY_PARENT_ID = 'parent_id';
        const QUERY_CHILD_ID = 'child_id';
        const QUERY_PARENT_DOMAIN = 'parent_domain';
        const QUERY_PARENT_QUERY = 'parent_query';
        const QUERY_CHILD_DOMAIN = 'child_domain';
        const QUERY_CHILD_QUERY = 'child_query';
        const QUERY_LANGUAGE = 'language';
        const QUERY_HAS_TRASHED_POSTS = 'has_trashed_posts';
        const RETURN_ASSOCIATION_IDS = 'association_ids';
        const RETURN_ASSOCIATIONS = 'associations';
        const RETURN_PARENT_IDS = 'parent_ids';
        const RETURN_CHILD_IDS = 'child_ids';
        const RETURN_PARENTS = 'parents';
        const RETURN_CHILDREN = 'children';
        const LANGUAGE_ALL = 'all';
        const GROUP_CONCAT_SEPARATOR = ',';
        /**
         * Parse query arguments, store them sanitized as options or in the $query_vars array.
         *
         * @param array $query
         */
        protected function parse_query($query)
        {
        }
        /**
         * Perform the query and get results.
         *
         * Depending on query arguments, the results may be cached.
         *
         * @return int[]|IToolset_Element[]|IToolset_Association[] Array of results, depending on query arguments.
         */
        public function get_results()
        {
        }
        protected function get_subject_name_for_cache()
        {
        }
        /**
         * Build the MySQL statement for querying the data, depending on query variables.
         *
         * @return string MySQL query statement.
         * @since m2m
         */
        protected function build_sql_statement()
        {
        }
        /**
         * @inheritdoc
         * @return string
         */
        protected function get_results_type()
        {
        }
        /**
         * Process raw output from $wpdb.
         *
         * @param array $rows
         *
         * @return array
         */
        protected function postprocess_results($rows)
        {
        }
    }
    /**
     * A class for querying relationship definitions.
     *
     * Arguments:
     *     todo document
     *
     * Usage:
     *
     *     $query = new Toolset_Relationship_Query( $args );
     *     $results = $query->get_results();
     *
     * Notes:
     *
     *   - For now, it doesn't query the database because all relationship definitions are loaded at once by the factory.
     *     That may change in the future but it should not influence the interface.
     *   - If you need to query by some parameters that are not supported, either create a feature request about it or
     *     submit a merge request rather than going around the query and touching the database directly.
     *
     * @since m2m
     *
     * @deprecated Use RelationshipQuery instead.
     */
    class Toolset_Relationship_Query extends \Toolset_Relationship_Query_Base
    {
        /**
         * Filter by affected domain.
         *
         * Selectd only relationship definitions that have the specified domain on the side of a child, parent or both.
         */
        const QUERY_HAS_DOMAIN = 'has_domain';
        const QUERY_HAS_TYPE = 'has_type';
        const QUERY_HAS_OWNER_TYPE = 'has_owner_type';
        const QUERY_IS_TRANSLATABLE = 'is_translatable';
        const QUERY_IS_LEGACY = 'is_legacy';
        const QUERY_IS_ACTIVE = 'is_active';
        /**
         * @inheritdoc
         * @param array $query
         */
        protected function parse_query($query)
        {
        }
        /**
         * @inheritdoc
         * @return string
         */
        protected function get_subject_name_for_cache()
        {
        }
        // This method should be never called in this class.
        protected function build_sql_statement()
        {
        }
        // This method should be never called in this class.
        protected function postprocess_results($rows)
        {
        }
        /**
         * @inheritdoc
         * @return Toolset_Relationship_Definition[]
         * @deprecated Use Toolset_Relationship_Query_V2 instead.
         */
        public function get_results()
        {
        }
    }
    /**
     * Facade to keep everything working with a direct instantiation of Toolset_Association_Query_V2 all over the
     * Toolset codebase while we've introduced an interface and a factory that should be used instead.
     *
     * @deprecated Use OTGS\Toolset\Common\Relationships\API\Factory::association_query().
     */
    class Toolset_Association_Query_V2 implements \OTGS\Toolset\Common\Relationships\API\AssociationQuery
    {
        /**
         * @inheritDoc
         */
        public function add(\OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition $condition)
        {
        }
        /**
         * @inheritDoc
         */
        public function do_not_add_default_conditions()
        {
        }
        /**
         * @inheritDoc
         */
        public function get_results()
        {
        }
        /**
         * @inheritDoc
         */
        public function do_or(...$conditions)
        {
        }
        /**
         * @inheritDoc
         */
        public function do_and(...$conditions)
        {
        }
        /**
         * @inheritDoc
         */
        public function do_if($statement, \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition $if_branch, \OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition $else_branch = \null)
        {
        }
        public function not(\OTGS\Toolset\Common\Relationships\API\AssociationQueryCondition $condition)
        {
        }
        /**
         * @inheritDoc
         */
        public function relationship_id($relationship_id)
        {
        }
        /**
         * @inheritDoc
         */
        public function intermediary_id($relationship_id)
        {
        }
        /**
         * @inheritDoc
         */
        public function relationship(\IToolset_Relationship_Definition $relationship_definition)
        {
        }
        /**
         * @inheritDoc
         */
        public function relationship_slug($slug)
        {
        }
        /**
         * @inheritDoc
         */
        public function element_id($element_id, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $need_wpml_unaware_query = \true)
        {
        }
        /**
         * @inheritDoc
         */
        public function element_id_and_domain($element_id, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $query_original_element = \false, $translate_provided_id = \true, $set_its_translation_language = \true, $element_identification_to_query_by = \null)
        {
        }
        /**
         * @inheritDoc
         */
        public function multiple_elements($element_ids, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $query_original_element = \false, $translate_provided_ids = \true)
        {
        }
        /**
         * @inheritDoc
         */
        public function element(\IToolset_Element $element, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role = \null, $query_original_element = \false, $translate_provided_id = \true, $set_its_translation_language = \true)
        {
        }
        /**
         * @inheritDoc
         */
        public function exclude_element(\IToolset_Element $element, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $query_original_element = \false, $translate_provided_id = \true)
        {
        }
        /**
         * @inheritDoc
         */
        public function parent(\IToolset_Element $element_source)
        {
        }
        /**
         * @inheritDoc
         */
        public function parent_id($parent_id, $domain = \Toolset_Element_Domain::POSTS)
        {
        }
        /**
         * @inheritDoc
         */
        public function child(\IToolset_Element $element)
        {
        }
        /**
         * @inheritDoc
         */
        public function child_id($child_id, $domain = \Toolset_Element_Domain::POSTS)
        {
        }
        /**
         * @inheritDoc
         */
        public function element_status($statuses, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role = \null)
        {
        }
        /**
         * @inheritDoc
         */
        public function has_available_elements()
        {
        }
        /**
         * @inheritDoc
         */
        public function has_active_relationship($is_active = \true)
        {
        }
        /**
         * @inheritDoc
         */
        public function has_legacy_relationship($needs_legacy_support = \true)
        {
        }
        /**
         * @inheritDoc
         */
        public function has_domain($domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role)
        {
        }
        /**
         * @inheritDoc
         */
        public function has_type($type, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role)
        {
        }
        /**
         * @inheritDoc
         */
        public function has_domain_and_type($domain, $type, \OTGS\Toolset\Common\Relationships\API\RelationshipRoleParentChild $for_role)
        {
        }
        /**
         * @inheritDoc
         */
        public function has_origin($origin)
        {
        }
        /**
         * @inheritDoc
         */
        public function has_intermediary_id()
        {
        }
        /**
         * @inheritDoc
         */
        public function wp_query(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $query_args, $confirmation = \null)
        {
        }
        /**
         * @inheritDoc
         */
        public function search($search_string, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $is_exact = \false)
        {
        }
        /**
         * @inheritDoc
         */
        public function association_id($association_id)
        {
        }
        public function meta($meta_key, $meta_value, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role = \null, $comparison = \Toolset_Query_Comparison_Operator::EQUALS)
        {
        }
        /**
         * @inheritDoc
         */
        public function has_autodeletable_intermediary_post($expected_value = \true)
        {
        }
        /**
         * @inheritDoc
         */
        public function return_association_instances()
        {
        }
        /**
         * @inheritDoc
         */
        public function return_association_uids()
        {
        }
        /**
         * @inheritDoc
         */
        public function return_element_ids(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role)
        {
        }
        /**
         * @inheritDoc
         */
        public function return_element_instances(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role)
        {
        }
        /**
         * @inheritDoc
         */
        public function return_per_role()
        {
        }
        /**
         * @inheritDoc
         */
        public function offset($value)
        {
        }
        /**
         * @inheritDoc
         */
        public function limit($value)
        {
        }
        /**
         * @inheritDoc
         */
        public function order($value)
        {
        }
        /**
         * @inheritDoc
         */
        public function need_found_rows($is_needed = \true)
        {
        }
        /**
         * @inheritDoc
         */
        public function get_found_rows()
        {
        }
        /**
         * @inheritDoc
         */
        public function dont_order()
        {
        }
        /**
         * @inheritDoc
         */
        public function order_by_title(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role)
        {
        }
        /**
         * @inheritDoc
         */
        public function order_by_field_value(\Toolset_Field_Definition $field_definition, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role)
        {
        }
        /**
         * @inheritDoc
         */
        public function order_by_meta($meta_key, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $is_numeric = \false)
        {
        }
        /**
         * @inheritDoc
         */
        public function dont_translate_results()
        {
        }
        /**
         * @inheritDoc
         */
        public function set_translation_language($lang_code)
        {
        }
        /**
         * @inheritDoc
         */
        public function force_language_per_role(\OTGS\Toolset\Common\Relationships\API\RelationshipRole $role, $lang_code)
        {
        }
        /**
         * @inheritDoc
         */
        public function set_translation_language_by_element_id_and_domain($element_id, $domain)
        {
        }
        /**
         * @inheritDoc
         */
        public function get_found_rows_directly()
        {
        }
        public function use_cache($use_cache = \true)
        {
        }
        public function build_cache_key($query_string)
        {
        }
        public function include_original_language($include = \true)
        {
        }
        public function force_display_as_translated_mode($do_force = \true)
        {
        }
        public function element_trid_or_id_and_domain($trid, $element_id, $domain, \OTGS\Toolset\Common\Relationships\API\RelationshipRole $for_role, $translate_provided_id = \true, $set_its_translation_language = \true, $element_identification_to_query_by = \OTGS\Toolset\Common\Relationships\API\ElementIdentification::CURRENT_LANGUAGE_IF_POSSIBLE)
        {
        }
    }
}
namespace OTGS\Toolset\Common\M2M\Association {
    /**
     * Class Repository
     *
     * This is useful when you know you will have to do a lot of association request.
     * Instead of doing a lot of small database queries you load them to this repository by doing bigger queries
     * and use this for the smaller afterwards request instead of asking the database.
     *
     * Example of usage: Types_Import_Export::wp_export_before()
     *
     * It can also be (ab)used as a container of Assocation/Relationship_Query and Roles
     * (to make sure you can still do very specific requests, without the need of additional injection).
     * Always consider to extend this Repository class instead of abusing it as a container.
     *
     * @deprecated Do not use in new code, some parts don't really scale well.
     */
    class Repository
    {
        /**
         * Repository constructor.
         *
         * @param \Toolset_Relationship_Query_Factory $query_factory
         * @param \Toolset_Relationship_Role_Parent $role_parent
         * @param \Toolset_Relationship_Role_Child $role_child
         * @param \Toolset_Relationship_Role_Intermediary $role_Intermediary
         * @param \Toolset_Element_Domain $element_domain
         */
        public function __construct(\Toolset_Relationship_Query_Factory $query_factory, \Toolset_Relationship_Role_Parent $role_parent, \Toolset_Relationship_Role_Child $role_child, \Toolset_Relationship_Role_Intermediary $role_Intermediary, \Toolset_Element_Domain $element_domain)
        {
        }
        /**
         * @param \IToolset_Post $toolset_post
         *
         * @return \Toolset_Association[]
         * @throws \Toolset_Element_Exception_Element_Doesnt_Exist
         */
        public function getAssociationsByChildPost(\IToolset_Post $toolset_post)
        {
        }
        /**
         * @param \IToolset_Association $association
         */
        public function addAssociation(\IToolset_Association $association)
        {
        }
        public function addAssociationsByChild(\IToolset_Element $child)
        {
        }
        /**
         * Load associations by given post type
         * This methods also tracks what's already loaded, means you don't need to care about loading associations
         * of a post type more than once.
         *
         * @param \IToolset_Post_Type $post_type
         */
        public function addAssociationsByPostType(\IToolset_Post_Type $post_type)
        {
        }
        /**
         * @param int|null $limit Optional (the best feature of Toolset_Association_Query is back)
         *
         * @return \Toolset_Association_Query_V2
         */
        public function getAssociationQuery($limit = null)
        {
        }
        /**
         * @return \Toolset_Relationship_Query_V2
         */
        public function getRelationshipQuery()
        {
        }
        /**
         * @return \Toolset_Relationship_Role_Parent
         */
        public function getRoleParent()
        {
        }
        /**
         * @return \Toolset_Relationship_Role_Child
         */
        public function getRoleChild()
        {
        }
        /**
         * @return \Toolset_Relationship_Role_Intermediary
         */
        public function getRoleIntermediary()
        {
        }
    }
}
namespace {
    /**
     * Abstract factory for field group classes.
     *
     * It ensures that each field group is instantiated only once and it keeps returning that one instance.
     *
     * Note: Cache is indexed by slugs, so if a field group can change it's slug, it is necessary to do
     * an 'wpcf_field_group_renamed' action immediately after renaming.
     *
     * @since 1.9
     */
    abstract class Toolset_Field_Group_Factory
    {
        /**
         * Cache key:
         *
         * @type array Array of field group instances for this post type, indexed by names (post slugs).
         *
         * Note that this needs to be cached due to multisite environments.
         */
        const FIELD_GROUP_MODELS_CACHE_KEY = 'field_group_models';
        /**
         * Cache key:
         *
         * @type WP_Post[][] WP_Post objects representing field groups.
         * @since Types 3.3
         *
         * Note that this needs to be cached due to multisite environments.
         */
        const GROUP_QUERIES_CACHE_KEY = 'group_queries';
        /** @var \OTGS\Toolset\Common\WpQueryFactory  */
        protected $wp_query_factory;
        /** @var \OTGS\Toolset\Common\WpPostFactory  */
        protected $wp_post_factory;
        /** @var \OTGS\Toolset\Common\Utils\InMemoryCache */
        protected $cache;
        /**
         * Singleton parent.
         *
         * @link http://stackoverflow.com/questions/3126130/extending-singletons-in-php
         * @return Toolset_Field_Group_Factory Instance of calling class.
         */
        public static function get_instance()
        {
        }
        protected function __construct(\OTGS\Toolset\Common\WpQueryFactory $wp_query_factory = \null, \OTGS\Toolset\Common\WpPostFactory $wp_post_factory = \null, \OTGS\Toolset\Common\Utils\InMemoryCache $cache = \null)
        {
        }
        /**
         * For a given field domain, return the appropriate field group factory instance.
         *
         * @param string $domain Valid field domain
         *
         * @return Toolset_Field_Group_Factory
         * @since 2.1
         */
        public static function get_factory_by_domain($domain)
        {
        }
        /**
         * @return string Post type that holds information about this field group type.
         */
        abstract public function get_post_type();
        /**
         * Get the name of the domain for which this factory is intended.
         *
         * @return string
         */
        abstract public function get_domain();
        /**
         * @return string Name of the class that represents this field group type (and that will be instantiated). It must
         * be a child of Toolset_Field_Group.
         */
        abstract protected function get_field_group_class_name();
        /**
         * Get a post object that represents a field group.
         *
         * @param int|string|WP_Post $field_group Numeric ID of the post, post slug or a post object.
         *
         * @param bool $force_query_by_name Useful to query field groups with numbers only title
         *
         * @return null|WP_Post Requested post object when the post exists and has correct post type. Null otherwise.
         */
        final protected function get_post($field_group, $force_query_by_name = \false)
        {
        }
        /**
         * Load a field group instance.
         *
         * @param int|string|WP_Post $field_group_source Post ID of the field group, it's name or a WP_Post object.
         *
         * @param bool $force_query_by_name
         *
         * @return null|Toolset_Field_Group Field group or null if it can't be loaded.
         */
        final public function load_field_group($field_group_source, $force_query_by_name = \false)
        {
        }
        /**
         * Update cache after a field group is renamed.
         *
         * @param string $original_name The old name of the field group.
         * @param Toolset_Field_Group $field_group The field group involved, with already updated name.
         */
        public function field_group_renamed($original_name, $field_group)
        {
        }
        /**
         * Create new field group.
         *
         * @param string $name Sanitized field group name. Note that the final name may change when new post is inserted.
         * @param string $title Field group title.
         * @param string $status Only 'draft'|'publish' are expected. Groups with the 'draft' status will appear as deactivated.
         * @param string|null $purpose Field group purpose. Defaults to PURPOSE_GENERIC. Accepted values depend on the type of the field group.
         *
         * @return null|Toolset_Field_Group The new field group or null on error.
         *
         * @since 1.9
         * @since m2m Added the 'purpose' argument.
         *
         * @refactoring ! Make this testable.
         */
        final public function create_field_group($name, $title = '', $status = 'draft', $purpose = \null)
        {
        }
        /**
         * Get field groups based on query arguments.
         *
         * @param array $query_args Optional arguments for the WP_Query that will be applied on the underlying posts.
         *     Post type query is added automatically.
         *     Additional arguments are allowed.
         *     - 'types_search': String for extended search. See WPCF_Field_Group::is_match() for details.
         *     - 'is_active' bool: If defined, only active/inactive field groups will be returned.
         *     - 'purpose' string: See Toolset_Field_Group::get_purpose(). Default is Toolset_Field_Group::PURPOSE_GENERIC.
         *        Special value '*' will return groups of all purposes.
         *     - 'assigned_to_post_type' string: For post field groups only, filter results by being assinged to a particular post type.
         *
         * @return Toolset_Field_Group[]
         * @since 1.9
         * @since m2m Added the 'purpose' argument.
         * @refactoring ! Make the code testable.
         */
        public function query_groups($query_args = array())
        {
        }
        /**
         * Get a map of all field group slugs to their display names.
         *
         * @return string[]
         * @since 2.0
         */
        public function get_group_slug_to_displayname_map()
        {
        }
        /**
         * Retrieve groups that should be displayed with a certain element, taking all possible conditions into account.
         *
         * @param IToolset_Element $element Element of the domain matching the field group.
         * @return Toolset_Field_Group[]
         * @throws InvalidArgumentException On invalid input (e.g. if the element's domain doesn't match the factory domain).
         * @since Types 3.3
         */
        abstract public function get_groups_for_element(\IToolset_Element $element);
        /**
         * Clear the query cache. This must be called after every field group change that isn't immediately followed by a
         * page reload.
         *
         * @since Types 3.3
         */
        public function reset_query_cache()
        {
        }
    }
    /**
     * Factory for the Toolset_Field_Group_Term class.
     *
     * @since 1.9
     */
    class Toolset_Field_Group_Term_Factory extends \Toolset_Field_Group_Factory
    {
        /**
         * @return Toolset_Field_Group_Term_Factory
         */
        public static function get_instance()
        {
        }
        protected function __construct()
        {
        }
        /**
         * Load a field group instance.
         *
         * @param int|string|WP_Post $field_group Post ID of the field group, it's name or a WP_Post object.
         *
         * @return null|Toolset_Field_Group_Term Field group or null if it can't be loaded.
         */
        public static function load($field_group)
        {
        }
        /**
         * Create new field group.
         *
         * @param string $name Sanitized field group name. Note that the final name may change when new post is inserted.
         * @param string $title Field group title.
         *
         * @return null|Toolset_Field_Group The new field group or null on error.
         */
        public static function create($name, $title = '')
        {
        }
        public function get_post_type()
        {
        }
        protected function get_field_group_class_name()
        {
        }
        /**
         * Produce a list of all taxonomies with groups that belong to them.
         *
         * @return Toolset_Field_Group_Term[][] Associative array where keys are taxonomy slugs and values are arrays of field
         *     groups that are associated with those taxonomies.
         */
        public function get_groups_by_taxonomies()
        {
        }
        /**
         * Get array of groups that are associated with given taxonomy.
         *
         * @param string $taxonomy_slug Slug of the taxonomy
         *
         * @return Toolset_Field_Group_Term[] Associated term field groups.
         */
        public function get_groups_by_taxonomy($taxonomy_slug)
        {
        }
        /**
         * This needs to be executed whenever a term group is updated.
         *
         * Hooked into the wpcf_group_updated action.
         * Erases cache for the get_groups_by_taxonomies() method.
         *
         * @param int $group_id Ignored
         * @param Toolset_Field_Group $group Field group that has been just updated.
         */
        public function on_group_updated(
            /** @noinspection PhpUnusedParameterInspection */
            $group_id,
            $group
        )
        {
        }
        /**
         * Clears the cache for taxonomy assignemnts.
         *
         * @since 2.2
         * @deprecated It is only used for testing purposes.
         */
        public function clear_taxonomy_assignment_cache()
        {
        }
        /**
         * Retrieve groups that should be displayed with a certain element, taking all possible conditions into account.
         *
         * @param IToolset_Element $element Element of the domain matching the field group.
         *
         * @throws RuntimeException Until the method is implemented for this domain.
         */
        public function get_groups_for_element(\IToolset_Element $element)
        {
        }
        /**
         * @inheritdoc
         * @return string
         * @since 3.4
         */
        public function get_domain()
        {
        }
    }
    /**
     * Post field group.
     *
     * @since 2.0
     */
    class Toolset_Field_Group_Post extends \Toolset_Field_Group
    {
        const POST_TYPE = 'wp-types-group';
        /**
         * Postmeta that contains a comma-separated list of post type slugs where this field group is assigned.
         *
         * Note: There might be empty items in the list: ",,,post-type-slug,," Make sure to avoid those.
         *
         * Note: Empty value means "all groups". There also may be legacy value "all" with the same meaning.
         *
         * @since unknown
         */
        const POSTMETA_POST_TYPE_LIST = '_wp_types_group_post_types';
        /**
         * @var string Key of postmeta that contains a comma-separated list of taxonomy_term IDs where the field group
         *    is assigned. Same warnings as for POSTMETA_POST_TYPE_LIST apply.
         */
        const POSTMETA_TERM_LIST = '_wp_types_group_terms';
        /**
         * @var string Key of postmeta that contains a comma-separated list of templates where the field group
         *     is assigned. A template can be a native WP page template or a Content Template ID or a Content Template slug.
         *     Same warnings as for POSTMETA_POST_TYPE_LIST apply.
         */
        const POSTMETA_TEMPLATE_LIST = '_wp_types_group_templates';
        /**
         * @var string Key of postmeta that contains the operator for evaluating the filters that determine where the
         *     field group is displayed. Accepted values are 'any' and 'all', the former being the default one.
         */
        const POSTMETA_FILTER_OPERATOR = '_wp_types_group_filters_association';
        // Field group purposes specific to post groups.
        /** Group is attached (only) to the indermediary post type of a relationship */
        const PURPOSE_FOR_INTERMEDIARY_POSTS = 'for_intermediary_posts';
        /** Group is attached to a post type that acts as a repeating field group */
        const PURPOSE_FOR_REPEATING_FIELD_GROUP = 'for_repeating_field_group';
        /**
         * @param WP_Post $field_group_post Post object representing a post field group.
         *
         * @param \OTGS\Toolset\Common\Field\Group\TemplateFilter\TemplateFilterFactory|null $template_filter_factory_di
         */
        public function __construct($field_group_post, \OTGS\Toolset\Common\Field\Group\TemplateFilter\TemplateFilterFactory $template_filter_factory_di = \null)
        {
        }
        /**
         * @return Toolset_Field_Definition_Factory Field definition factory of the correct type.
         */
        protected function get_field_definition_factory()
        {
        }
        /**
         * Assign a post type to the group
         *
         * @param $post_type
         */
        public function assign_post_type($post_type)
        {
        }
        /**
         * Stores an array of post types as list in database
         *
         * @param array $post_types
         *
         * @since m2m Allows to set a post type even though it's not currently registered
         *     (needed for working with just created post type).
         */
        protected function store_post_types($post_types)
        {
        }
        /**
         * Retrieve term_taxonomy IDs of terms where this field group should be displayed (if the post has the particular term).
         *
         * @return int[]
         * @since Types 3.3
         */
        public function get_assigned_to_terms()
        {
        }
        /**
         * Retrieve filter objects describing where the field group should be displayed based on its template.
         *
         * See TemplateFilterInterface for more details.
         *
         * @return \OTGS\Toolset\Common\Field\Group\TemplateFilter\TemplateFilterInterface[]
         * @since Types 3.3
         */
        public function get_assigned_to_templates()
        {
        }
        /**
         * @inheritdoc
         *
         * @return array
         * @since 2.1
         */
        protected function fetch_assigned_to_types()
        {
        }
        /**
         * @inheritdoc
         * @return WP_Post[] Individual posts using this group.
         * @since 2.1
         */
        protected function fetch_assigned_to_items()
        {
        }
        /**
         * Determine if the group is associated with a post type.
         *
         * @param string $post_type_slug
         *
         * @return bool
         * @since m2m
         * @deprecated Use is_assigned_to_type() instead.
         */
        public function has_associated_post_type($post_type_slug)
        {
        }
        /**
         * Get the backend edit link.
         *
         * @refactoring ! This doesn't belong to a model; separation of concerns!!
         *
         * @return string
         * @since 2.1
         */
        public function get_edit_link()
        {
        }
        /**
         * @inheritdoc
         *
         * @return string[]
         * @since m2m
         */
        protected function get_allowed_group_purposes()
        {
        }
        /**
         * Return the value from self::POSTMETA_FILTER_OPERATOR.
         *
         * @return string 'any'|'all'
         * @since Types 3.3
         */
        public function get_filter_operator()
        {
        }
    }
    /**
     * Term field group.
     *
     * @since 1.9
     */
    class Toolset_Field_Group_Term extends \Toolset_Field_Group
    {
        const POST_TYPE = 'wp-types-term-group';
        /**
         * Key for postmeta that holds slugs of taxonomies associated with this group. This is a "plural" postmeta,
         * each record contains one slug.
         */
        const POSTMETA_ASSOCIATED_TAXONOMY = '_wp_types_associated_taxonomy';
        /**
         * Toolset_Field_Group_Term constructor.
         *
         * @param WP_Post $field_group_post Post object representing a term field group.
         * @throws InvalidArgumentException
         */
        public function __construct($field_group_post)
        {
        }
        /**
         * @return Toolset_Field_Definition_Factory Field definition factory of the correct type.
         */
        protected function get_field_definition_factory()
        {
        }
        /**
         * Get taxonomies that are associated with this field group.
         *
         * @return string[] Taxonomy slugs. Empty array means that this group should be displayed with all taxonomies.
         */
        public function get_associated_taxonomies()
        {
        }
        /**
         * Quickly determine whether given taxonomy is associated with this group.
         *
         * @param string $taxonomy_slug
         * @return bool
         */
        public function has_associated_taxonomy($taxonomy_slug)
        {
        }
        /**
         * Update the set of taxonomies associated with this field group.
         *
         * @param string[] $taxonomy_slugs Array of (sanitized) taxonomy slugs.
         */
        public function update_associated_taxonomies($taxonomy_slugs)
        {
        }
        /** Element name for a single associated taxonomy. */
        const XML_ASSOCIATED_TAXONOMY = 'taxonomy';
        /**
         * @inheritdoc
         *
         * Add term field group-specific information to the export object.
         *
         * @return array
         * @since 2.1
         */
        protected function get_export_fields()
        {
        }
    }
    /**
     * User field group.
     *
     * @since 2.0
     */
    class Toolset_Field_Group_User extends \Toolset_Field_Group
    {
        const POST_TYPE = 'wp-types-user-group';
        /**
         * Postmeta that contains a comma-separated list of role slugs where this field group is assigned.
         *
         * Note: There might be empty items in the list: ",,,role-slug,," Make sure to avoid those.
         *
         * Note: Empty value means "all groups". There also may be legacy value "all" with the same meaning.
         *
         * @since unknown
         */
        const USERMETA_USER_ROLE_LIST = '_wp_types_group_showfor';
        /**
         * @param WP_Post $field_group_post Post object representing a user field group.
         * @throws InvalidArgumentException
         */
        public function __construct($field_group_post)
        {
        }
        /**
         * @return Toolset_Field_Definition_Factory Field definition factory of the correct type.
         */
        protected function get_field_definition_factory()
        {
        }
        /**
         * Get roles that are associated with this field group.
         *
         * @return string[] Role slugs. Empty array means that this group should be displayed with all roles.
         * @since 3.1
         */
        public function get_associated_roles()
        {
        }
        /**
         * Quickly determine whether given role is associated with this group.
         *
         * @param string $role
         * @return bool
         * @since 3.1
         */
        public function has_associated_role($role)
        {
        }
    }
    /**
     * Factory for the Toolset_Field_Group_Post class.
     *
     * @since 2.0
     */
    class Toolset_Field_Group_Post_Factory extends \Toolset_Field_Group_Factory
    {
        const POST_TYPE_ASSIGNMENTS_CACHE_KEY = 'post_type_assignments';
        /**
         * @return Toolset_Field_Group_Post_Factory
         * @noinspection SenselessProxyMethodInspection
         */
        public static function get_instance()
        {
        }
        protected function __construct()
        {
        }
        /**
         * Load a field group instance.
         *
         * @param int|string|WP_Post $field_group Post ID of the field group, it's name or a WP_Post object.
         *
         * @param bool $force_query_by_name
         *
         * @return null|Toolset_Field_Group_Post Field group or null if it can't be loaded.
         */
        public static function load($field_group, $force_query_by_name = \false)
        {
        }
        /**
         * Create new field group.
         *
         * @param string $name Sanitized field group name. Note that the final name may change when new post is inserted.
         * @param string $title Field group title.
         * @param String $status Post status
         * @param String $purpose Purpose.
         *
         * @return null|Toolset_Field_Group The new field group or null on error.
         */
        public static function create($name, $title = '', $status = 'draft', $purpose = \Toolset_Field_Group_Post::PURPOSE_GENERIC)
        {
        }
        public function get_post_type()
        {
        }
        protected function get_field_group_class_name()
        {
        }
        /**
         * Get all field groups sorted by their association with post types.
         *
         * @return Toolset_Field_Group_Post[][] For each (registered) post type, there will be an array element, which is
         *     an array of post field groups associated to it.
         * @since m2m
         */
        public function get_groups_by_post_types()
        {
        }
        /**
         * @param $post_type_slug
         *
         * @return Toolset_Field_Group_Post[]
         */
        public function get_groups_for_new_post($post_type_slug)
        {
        }
        /**
         * Get array of groups that are associated with given post type.
         *
         * @param string $post_type_slug Slug of the post type.
         *
         * @return Toolset_Field_Group_Post[] Associated post field groups.
         */
        public function get_groups_by_post_type($post_type_slug)
        {
        }
        /**
         * This needs to be executed whenever a post field group is updated.
         *
         * Hooked into the wpcf_group_updated action.
         * Erases cache for the get_groups_by_post_types() method.
         *
         * @param int $group_id Ignored
         * @param Toolset_Field_Group $group Field group that has been just updated.
         */
        public function on_group_updated(
            /** @noinspection PhpUnusedParameterInspection */
            $group_id = \null,
            $group = \null
        )
        {
        }
        /**
         * @inheritdoc
         * @return string
         * @since 3.4
         */
        public function get_domain()
        {
        }
        /**
         * Retrieve groups that should be displayed with a certain element, taking all possible conditions into account.
         *
         * @param IToolset_Element $element Element of the domain matching the field group.
         * @param bool $return_group_display_results Set this to true to get an array of group display results instead of
         *     the field groups themselves.
         *
         * @return Toolset_Field_Group_Post[]|\OTGS\Toolset\Common\Field\Group\GroupDisplayResult[]
         */
        public function get_groups_for_element(\IToolset_Element $element, $return_group_display_results = \false)
        {
        }
    }
}
namespace OTGS\Toolset\Common\Field\Group {
    /**
     * Holds results of display filters for a single post field group.
     *
     * Intended only for storing groups whose filters pass (since this logic cannot be evaluated here).
     *
     * @since Types 3.3
     */
    class GroupDisplayResult
    {
        /**
         * GroupDisplayResult constructor.
         *
         * @param \Toolset_Field_Group $group
         */
        public function __construct(\Toolset_Field_Group $group)
        {
        }
        /**
         * @return \Toolset_Field_Group
         */
        public function get_group()
        {
        }
        /**
         * Add another filter result for this group.
         *
         * @param FilterDisplayResult $filter_result
         */
        public function add_filter_result(\OTGS\Toolset\Common\Field\Group\FilterDisplayResult $filter_result)
        {
        }
        /**
         * Determine if any of the filters applied on this group requires browser evaluation.
         *
         * @return bool
         */
        public function requires_browser_evaluation()
        {
        }
        /**
         * Indicates whether any of the filters applied on this group require a page refresh after saving the post
         * in order to re-evaluate the group visibility.
         *
         * @return bool
         */
        public function requires_page_refresh_after_saving()
        {
        }
        /**
         * A flag that indicates whether the field group is actually selected to be rendered on the front-end.
         *
         * @param null|bool $new_value
         *
         * @return null|bool
         */
        public function is_selected($new_value = null)
        {
        }
    }
    /**
     * Holds a result of a field group display filter.
     *
     * @see Toolset_Field_Group_Post_Factory::get_groups_for_element()
     * @since Types 3.3
     */
    class FilterDisplayResult
    {
        // Possible output values of field group filters,
        const MATCH = 'match';
        const FAIL = 'fail';
        const INDIFFERENT = 'indifferent';
        /**
         * FilterDisplayResult constructor.
         *
         * @param string $value Filter result.
         * @param bool $requires_browser_evaluation Indicates whether this filter needs to be further evaluated in the
         *     browser.
         * @param bool $requires_page_refresh_after_saving Indicates whether this filter requires a page refresh after
         *        saving the post in order to re-evaluate the field group visibility.
         *
         * @throws \InvalidArgumentException
         */
        public function __construct($value, $requires_browser_evaluation, $requires_page_refresh_after_saving)
        {
        }
        /**
         * Filter result: MATCH, FAIL or INDIFFERENT.
         *
         * @return string
         */
        public function get_value()
        {
        }
        /**
         * Indicates whether this filter needs to be further evaluated in the browser.
         *
         * @return bool
         */
        public function requires_browser_evaluation()
        {
        }
        /**
         * Indicates whether this filter requires a page refresh after saving the post in order to re-evaluate
         * the field group visibility.
         *
         * @return bool
         */
        public function requires_page_refresh_after_saving()
        {
        }
    }
}
namespace {
    /**
     * Factory for the Toolset_Field_Group_User class.
     *
     * @since 2.0
     */
    class Toolset_Field_Group_User_Factory extends \Toolset_Field_Group_Factory
    {
        /**
         * @return Toolset_Field_Group_User_Factory
         * @noinspection SenselessProxyMethodInspection
         */
        public static function get_instance()
        {
        }
        /**
         * Toolset_Field_Group_User_Factory constructor.
         *
         * @param \OTGS\Toolset\Common\WpQueryFactory|null $wp_query_factory
         * @param \OTGS\Toolset\Common\WpPostFactory|null $wp_post_factory
         * @param \OTGS\Toolset\Common\Utils\InMemoryCache|null $cache
         */
        public function __construct(\OTGS\Toolset\Common\WpQueryFactory $wp_query_factory = \null, \OTGS\Toolset\Common\WpPostFactory $wp_post_factory = \null, \OTGS\Toolset\Common\Utils\InMemoryCache $cache = \null)
        {
        }
        /**
         * Load a field group instance.
         *
         * @param int|string|WP_Post $field_group Post ID of the field group, it's name or a WP_Post object.
         *
         * @return null|Toolset_Field_Group_User Field group or null if it can't be loaded.
         */
        public static function load($field_group)
        {
        }
        /**
         * Create new field group.
         *
         * @param string $name Sanitized field group name. Note that the final name may change when new post is inserted.
         * @param string $title Field group title.
         *
         * @return null|Toolset_Field_Group The new field group or null on error.
         */
        public static function create($name, $title = '')
        {
        }
        public function get_post_type()
        {
        }
        protected function get_field_group_class_name()
        {
        }
        /**
         * Get all field groups sorted by their association with roles.
         *
         * @return Toolset_Field_Group_User[][] For each role, there will be an array element, which is
         *     an array of user field groups associated to it.
         * @since 3.1
         */
        public function get_groups_by_roles()
        {
        }
        /**
         * Get array of groups that are associated with given role.
         *
         * @param string $role Slug of the role.
         * @return Toolset_Field_Group_User[] Associated user field groups.
         * @since 3.1
         */
        public function get_groups_by_role($role)
        {
        }
        /**
         * This needs to be executed whenever an usermeta field group is updated.
         *
         * Hooked into the wpcf_group_updated action.
         * Erases cache for the get_groups_by_roles() method.
         *
         * @param int $group_id Ignored
         * @param Toolset_Field_Group $group Field group that has been just updated.
         * @since 3.1
         */
        public function on_group_updated(
            /** @noinspection PhpUnusedParameterInspection */
            $group_id = \null,
            $group = \null
        )
        {
        }
        /**
         * Retrieve groups that should be displayed with a certain element, taking all possible conditions into account.
         *
         * @param IToolset_Element $element Element of the domain matching the field group.
         *
         * @throws RuntimeException Until the method is implemented for this domain.
         */
        public function get_groups_for_element(\IToolset_Element $element)
        {
        }
        /**
         * @inheritdoc
         * @return string
         * @since 3.4
         */
        public function get_domain()
        {
        }
    }
}
namespace OTGS\Toolset\Common\Field\Group\TemplateFilter {
    /**
     * Represents an object used for deciding whether a post has a certain template assigned to it.
     * Multiple types of templates can be supported (initial implementation covers native page templates and
     * Content Templates).
     *
     * Specifically, this is being used when determining what field groups should be displayed for a particular post
     * in Toolset_Field_Group_Post::get_groups_for_element().
     *
     * @since Types 3.3
     */
    interface TemplateFilterInterface
    {
        /**
         * @param \IToolset_Post $post
         *
         * @return bool True if the template matches given post.
         */
        public function is_match_for_post(\IToolset_Post $post);
        /**
         * Determine if the template is default for a given post type.
         *
         * This can be difficult to determine, so false negatives are allowed.
         * But if a positive result is returned, it must be certain.
         *
         * @param string $post_type_slug
         * @return bool
         * @since Types 3.3.4
         */
        public function is_default_for_post_type($post_type_slug);
    }
    /**
     * Represents a filter by a Content Template from Toolset.
     *
     * @since Types 3.3
     */
    class ContentTemplate implements \OTGS\Toolset\Common\Field\Group\TemplateFilter\TemplateFilterInterface
    {
        /**
         * ContentTemplate constructor.
         *
         * @param \WP_Post $template_post Post holding the content template.
         */
        public function __construct(\WP_Post $template_post)
        {
        }
        /**
         * @param \IToolset_Post $post
         *
         * @return bool True if the template matches given post.
         */
        public function is_match_for_post(\IToolset_Post $post)
        {
        }
        /**
         * @inheritDoc
         *
         * @param string $post_type_slug
         *
         * @return bool
         * @since Types 3.3.4
         */
        public function is_default_for_post_type($post_type_slug)
        {
        }
    }
    /**
     * Factory for creating an TemplateFilterInterface object based on the type of the template.
     *
     * @package OTGS\Toolset\Common\Field\Group\TemplateFilter
     */
    class TemplateFilterFactory
    {
        /**
         * @param string|int $template_name Post ID, native template name, content template slug...
         *
         * @return null|TemplateFilterInterface
         */
        public function build_from_name($template_name)
        {
        }
    }
    /**
     * Filter by a native WordPress template.
     *
     * @since Types 3.3
     */
    class NativePageTemplate implements \OTGS\Toolset\Common\Field\Group\TemplateFilter\TemplateFilterInterface
    {
        /**
         * NativePageTemplate constructor.
         *
         * @param string $template_name Name of the template file.
         * @throws \InvalidArgumentException If an obviously invalid $template_name is provided.
         */
        public function __construct($template_name)
        {
        }
        /**
         * @param \IToolset_Post $post
         *
         * @return bool True if the template matches given post.
         */
        public function is_match_for_post(\IToolset_Post $post)
        {
        }
        /**
         * @inheritDoc
         * @param string $post_type_slug
         * @return bool
         * @since Types 3.3.4
         */
        public function is_default_for_post_type($post_type_slug)
        {
        }
    }
}