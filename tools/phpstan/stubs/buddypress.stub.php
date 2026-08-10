<?php

/**
 * Class to help set up XProfile fields.
 *
 * @since 1.0.0
 */
#[\AllowDynamicProperties]
class BP_XProfile_Field
{
    /**
     * Field ID.
     *
     * @since 1.0.0
     * @var int
     */
    public $id;
    /**
     * Field group ID.
     *
     * @since 1.0.0
     * @var int
     */
    public $group_id;
    /**
     * Field parent ID.
     *
     * @since 1.0.0
     * @var int
     */
    public $parent_id;
    /**
     * Field type.
     *
     * @since 1.0.0
     * @var string
     */
    public $type = '';
    /**
     * Field name.
     *
     * @since 1.0.0
     * @var string
     */
    public $name = '';
    /**
     * Field description.
     *
     * @since 1.0.0
     * @var string
     */
    public $description = '';
    /**
     * Required field?
     *
     * @since 1.0.0
     * @var bool
     */
    public $is_required;
    /**
     * Deletable field?
     *
     * @since 1.0.0
     * @var int
     */
    public $can_delete = '1';
    /**
     * Field position.
     *
     * @since 1.0.0
     * @var int
     */
    public $field_order;
    /**
     * Option order.
     *
     * @since 1.0.0
     * @var int
     */
    public $option_order;
    /**
     * Order child fields.
     *
     * @since 1.0.0
     * @var string
     */
    public $order_by = '';
    /**
     * Is this the default option?
     *
     * @since 1.0.0
     * @var bool
     */
    public $is_default_option;
    /**
     * Field data visibility.
     *
     * @since 1.0.0
     * @var string
     */
    public $visibility;
    /**
     * Field data visibility.
     *
     * @since 1.9.0
     * @since 2.4.0 Property marked protected. Now accessible by magic method or by `get_default_visibility()`.
     * @var string
     */
    protected $default_visibility;
    /**
     * Is the visibility able to be modified?
     *
     * @since 2.3.0
     * @since 2.4.0 Property marked protected. Now accessible by magic method or by `get_allow_custom_visibility()`.
     * @var string
     */
    protected $allow_custom_visibility;
    /**
     * Whether values from this field are autolinked to directory searches.
     *
     * @since 2.5.0
     * @var bool
     */
    public $do_autolink;
    /**
     * The signup position of the field into the signups form.
     *
     * @since 8.0.0
     * @var int
     */
    public $signup_position;
    /**
     * Field type option.
     *
     * @since 2.0.0
     * @var BP_XProfile_Field_Type Field type object used for validation.
     */
    public $type_obj = \null;
    /**
     * Field data for user ID.
     *
     * @since 2.0.0
     * @var BP_XProfile_ProfileData Field data for user ID.
     */
    public $data;
    /**
     * Member types to which the profile field should be applied.
     *
     * @since 2.4.0
     * @var array Array of member types.
     */
    protected $member_types;
    /**
     * Initialize and/or populate profile field.
     *
     * @since 1.1.0
     *
     * @param int|null $id Field ID.
     * @param int|null $user_id User ID.
     * @param bool     $get_data Get data.
     */
    public function __construct($id = \null, $user_id = \null, $get_data = \true)
    {
    }
    /**
     * Populate a profile field object.
     *
     * @since 1.1.0
     *
     * @global wpdb $wpdb WordPress database object.
     * @global object $userdata
     *
     * @param int      $id Field ID.
     * @param int|null $user_id User ID.
     * @param bool     $get_data Get data.
     */
    public function populate($id, $user_id = \null, $get_data = \true)
    {
    }
    /**
     * Retrieve a `BP_XProfile_Field` instance.
     *
     * @since 2.4.0
     * @since 2.8.0 Added `$user_id` and `$get_data` parameters.
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @static
     *
     * @param int      $field_id ID of the field.
     * @param int|null $user_id  Optional. ID of the user associated with the field.
     *                           Ignored if `$get_data` is false. If `$get_data` is
     *                           true, but no `$user_id` is provided, defaults to
     *                           logged-in user ID.
     * @param bool     $get_data Whether to fetch data for the specified `$user_id`.
     * @return BP_XProfile_Field|false Field object if found, otherwise false.
     */
    public static function get_instance($field_id, $user_id = \null, $get_data = \true)
    {
    }
    /**
     * Fill object vars based on data passed to the method.
     *
     * @since 2.4.0
     *
     * @param array|object $args Array or object representing the `BP_XProfile_Field` properties.
     *                           Generally, this is a row from the fields database table.
     */
    public function fill_data($args)
    {
    }
    /**
     * Magic getter.
     *
     * @since 2.4.0
     *
     * @param string $key Property name.
     * @return string|null
     */
    public function __get($key)
    {
    }
    /**
     * Magic issetter.
     *
     * @since 2.4.0
     *
     * @param string $key Property name.
     * @return bool
     */
    public function __isset($key)
    {
    }
    /**
     * Delete a profile field.
     *
     * @since 1.1.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param boolean $delete_data Whether or not to delete data.
     * @return bool
     */
    public function delete($delete_data = \false)
    {
    }
    /**
     * Save a profile field.
     *
     * @since 1.1.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @return bool
     */
    public function save()
    {
    }
    /**
     * Get field data for a user ID.
     *
     * @since 1.2.0
     *
     * @param int $user_id ID of the user to get field data for.
     * @return BP_XProfile_ProfileData
     */
    public function get_field_data($user_id = 0)
    {
    }
    /**
     * Get all child fields for this field ID.
     *
     * @since 1.2.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param bool $for_editing Whether or not the field is for editing. Default to false.
     * @return array
     */
    public function get_children($for_editing = \false)
    {
    }
    /**
     * Delete all field children for this field.
     *
     * @since 1.2.0
     *
     * @global wpdb $wpdb WordPress database object.
     */
    public function delete_children()
    {
    }
    /**
     * Gets the member types to which this field should be available.
     *
     * Will not return inactive member types, even if associated metadata is found.
     *
     * 'null' is a special pseudo-type, which represents users that do not have a member type.
     *
     * @since 2.4.0
     *
     * @return array Array of member type names.
     */
    public function get_member_types()
    {
    }
    /**
     * Sets the member types for this field.
     *
     * @since 2.4.0
     *
     * @param array $member_types Array of member types. Can include 'null' (users with no type) in addition to any
     *                            registered types.
     * @param bool  $append       Whether to append to existing member types. If false, all existing member type
     *                            associations will be deleted before adding your `$member_types`. Default false.
     * @return array Member types for the current field, after being saved.
     */
    public function set_member_types($member_types, $append = \false)
    {
    }
    /**
     * Gets a label representing the field's member types.
     *
     * This label is displayed alongside the field's name on the Profile Fields Dashboard panel.
     *
     * @since 2.4.0
     *
     * @return string
     */
    public function get_member_type_label()
    {
    }
    /**
     * Get the field's default visibility setting.
     *
     * Lazy-loaded to reduce overhead.
     *
     * Defaults to 'public' if no visibility setting is found in the database.
     *
     * @since 2.4.0
     *
     * @return string
     */
    public function get_default_visibility()
    {
    }
    /**
     * Get whether the field's default visibility can be overridden by users.
     *
     * Lazy-loaded to reduce overhead.
     *
     * Defaults to 'allowed'.
     *
     * @since 4.4.0
     *
     * @return string 'disabled' or 'allowed'.
     */
    public function get_allow_custom_visibility()
    {
    }
    /**
     * Get the field's signup position.
     *
     * @since 8.0.0
     *
     * @return int the field's signup position.
     *             0 if the field has not been added to the signup form.
     */
    public function get_signup_position()
    {
    }
    /**
     * Get whether the field values should be auto-linked to a directory search.
     *
     * Lazy-loaded to reduce overhead.
     *
     * Defaults to true for multi and default fields, false for single fields.
     *
     * @since 2.5.0
     *
     * @return bool
     */
    public function get_do_autolink()
    {
    }
    /* Static Methods ********************************************************/
    /**
     * Get the type for provided field ID.
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param int $field_id Field ID to get type of.
     * @return bool|null|string
     */
    public static function get_type($field_id = 0)
    {
    }
    /**
     * Delete all fields in a field group.
     *
     * @since 1.2.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param int $group_id ID of the field group to delete fields from.
     * @return bool
     */
    public static function delete_for_group($group_id = 0)
    {
    }
    /**
     * Get field ID from field name.
     *
     * @since 1.5.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param string $field_name Name of the field to query the ID for.
     * @return int|null Field ID on success; null on failure.
     */
    public static function get_id_from_name($field_name = '')
    {
    }
    /**
     * Update field position and/or field group when relocating.
     *
     * @since 1.5.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param int      $field_id       ID of the field to update.
     * @param int|null $position       Field position to update.
     * @param int|null $field_group_id ID of the field group.
     * @return bool
     */
    public static function update_position($field_id, $position = \null, $field_group_id = \null)
    {
    }
    /**
     * Gets the IDs of fields applicable for a given member type or array of member types.
     *
     * @since 2.4.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param string|array $member_types Member type or array of member types. Use 'any' to return unrestricted
     *                                   fields (those available for anyone, regardless of member type).
     * @return array Multi-dimensional array, with field IDs as top-level keys, and arrays of member types
     *               associated with each field as values.
     */
    public static function get_fields_for_member_type($member_types)
    {
    }
    /**
     * Validate form field data on submission.
     *
     * @since 2.2.0
     *
     * @global string $message The feedback message to show.
     *
     * @return bool
     */
    public static function admin_validate()
    {
    }
    /**
     * Save miscellaneous settings for this field.
     *
     * Some field types have type-specific settings, which are saved here.
     *
     * @since 2.7.0
     *
     * @param array $settings Array of settings.
     */
    public function admin_save_settings($settings)
    {
    }
    /**
     * Populates the items for radio buttons, checkboxes, and dropdown boxes.
     */
    public function render_admin_form_children()
    {
    }
    /**
     * Oupput the admin form for this field.
     *
     * @since 1.9.0
     *
     * @param string $message Message to display.
     */
    public function render_admin_form($message = '')
    {
    }
    /**
     * Gets field type supports.
     *
     * @since 8.0.0
     *
     * @return bool[] Supported features.
     */
    public function get_field_type_supports()
    {
    }
    /**
     * Checks whether the field type supports the requested feature.
     *
     * @since 8.0.0
     *
     * @param string $support The name of the feature.
     * @return bool True if the field type supports the feature. False otherwise.
     */
    public function field_type_supports($support = '')
    {
    }
}
/**
 * Represents a type of XProfile field and holds meta information about the type of value that it accepts.
 *
 * @since 2.0.0
 */
abstract class BP_XProfile_Field_Type
{
    /**
     * Validation regex rules for field type.
     *
     * @since 2.0.0
     * @var array Field type validation regexes.
     */
    protected $validation_regex = array();
    /**
     * Allowed values for field type.
     *
     * @since 2.0.0
     * @var array Field type allowed values.
     */
    protected $validation_allowed_values = array();
    /**
     * Name for field type.
     *
     * @since 2.0.0
     * @var string The name of this field type.
     */
    public $name = '';
    /**
     * The name of the category that this field type should be grouped with. Used on the [Users > Profile Fields] screen in wp-admin.
     *
     * @since 2.0.0
     * @var string
     */
    public $category = '';
    /**
     * If allowed to store null/empty values.
     *
     * @since 2.0.0
     * @var bool If this is set, allow BP to store null/empty values for this field type.
     */
    public $accepts_null_value = \false;
    /**
     * If this is set, BP will set this field type's list of allowed values from the field's options (e.g checkbox, selectbox).
     *
     * @since 2.0.0
     * @var bool Does this field support options? e.g. selectbox, radio buttons, etc.
     */
    public $supports_options = \false;
    /**
     * If allowed to support multiple options as default.
     *
     * @since 2.0.0
     * @var bool Does this field type support multiple options being set as default values? e.g. multiselectbox, checkbox.
     */
    public $supports_multiple_defaults = \false;
    /**
     * If the field type supports rich text by default.
     *
     * @since 2.4.0
     * @var bool
     */
    public $supports_richtext = \false;
    /**
     * If the field type has a type-specific settings section on the Edit Field panel.
     *
     * @since 2.7.0
     * @var bool|null Boolean if set explicitly by the type object, otherwise null.
     */
    protected $do_settings_section = \null;
    /**
     * If object is created by an BP_XProfile_Field object.
     *
     * @since 2.0.0
     * @var BP_XProfile_Field If this object is created by instantiating a {@link BP_XProfile_Field},
     *                        this is a reference back to that object.
     */
    public $field_obj = \null;
    /**
     * Field data visibility.
     *
     * @since 2.0.0
     * @var string
     */
    public $visibility;
    /**
     * Constructor.
     *
     * @since 2.0.0
     */
    public function __construct()
    {
    }
    /**
     * Set a regex that profile data will be asserted against.
     *
     * You can call this method multiple times to set multiple formats. When validation is performed,
     * it's successful as long as the new value matches any one of the registered formats.
     *
     * @since 2.0.0
     *
     * @param string $format         Regex string.
     * @param string $replace_format Optional; if 'replace', replaces the format instead of adding to it.
     *                               Defaults to 'add'.
     * @return BP_XProfile_Field_Type
     */
    public function set_format($format, $replace_format = 'add')
    {
    }
    /**
     * Add a value to this type's list of allowed values that profile data will be asserted against.
     *
     * @since 2.0.0
     * @deprecated 7.0.0 Use set_allowed_values() instead.
     *
     * @param string|array $values Whitelisted values.
     * @return BP_XProfile_Field_Type
     */
    public function set_whitelist_values($values)
    {
    }
    /**
     * Add a value to this type's list of allowed values that profile data will be asserted against.
     *
     * You can call this method multiple times to set multiple formats. When validation is performed,
     * it's successful as long as the new value matches any one of the registered formats.
     *
     * @since 7.0.0
     *
     * @param string|array $values Allowed values.
     * @return BP_XProfile_Field_Type
     */
    public function set_allowed_values($values)
    {
    }
    /**
     * Check the given string against the registered formats for this field type.
     *
     * This method doesn't support chaining.
     *
     * @since 2.0.0
     *
     * @param string|array $values Value to check against the registered formats.
     * @return bool True if the value validates
     */
    public function is_valid($values)
    {
    }
    /**
     * Check whether the current field type should have a settings ("options") section on the Edit Field panel.
     *
     * Falls back on `supports_options` if no value is set by the field type.
     *
     * @since 2.7.0
     *
     * @return bool
     */
    public function do_settings_section()
    {
    }
    /**
     * Output the edit field HTML for this field type.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 2.0.0
     *
     * @param array $raw_properties Optional key/value array of permitted attributes that you want to add.
     */
    abstract public function edit_field_html(array $raw_properties = array());
    /**
     * Output HTML for this field type on the wp-admin Profile Fields screen.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 2.0.0
     *
     * @param array $raw_properties Optional key/value array of permitted attributes that you want to add.
     */
    abstract public function admin_field_html(array $raw_properties = array());
    /**
     * Output the edit field options HTML for this field type.
     *
     * BuddyPress considers a field's "options" to be, for example, the items in a selectbox.
     * These are stored separately in the database, and their templating is handled separately.
     * Populate this method in a child class if it's required. Otherwise, you can leave it out.
     *
     * This templating is separate from {@link BP_XProfile_Field_Type::edit_field_html()} because
     * it's also used in the wp-admin screens when creating new fields, and for backwards compatibility.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 2.0.0
     *
     * @param array $args Optional. The arguments passed to {@link bp_the_profile_field_options()}.
     */
    public function edit_field_options_html(array $args = array())
    {
    }
    /**
     * Output HTML for this field type's children options on the wp-admin Profile Fields "Add Field" and "Edit Field" screens.
     *
     * You don't need to implement this method for all field types. It's used in core by the
     * selectbox, multi selectbox, checkbox, and radio button fields, to allow the admin to
     * enter the child option values (e.g. the choices in a select box).
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 2.0.0
     *
     * @param BP_XProfile_Field $current_field The current profile field on the add/edit screen.
     * @param string            $control_type  Optional. HTML input type used to render the current
     *                          field's child options.
     */
    public function admin_new_field_html(\BP_XProfile_Field $current_field, $control_type = '')
    {
    }
    /**
     * Allow field types to modify submitted values before they are validated.
     *
     * In some cases, it may be appropriate for a field type to catch
     * submitted values and modify them before they are passed to the
     * is_valid() method. For example, URL validation requires the
     * 'http://' scheme (so that the value saved in the database is always
     * a fully-formed URL), but in order to allow users to enter a URL
     * without this scheme, BP_XProfile_Field_Type_URL prepends 'http://'
     * when it's not present.
     *
     * By default, this is a pass-through method that does nothing. Only
     * override in your own field type if you need this kind of pre-
     * validation filtering.
     *
     * @since 2.1.0
     * @since 2.4.0 Added the `$field_id` parameter.
     *
     * @param mixed      $field_value Submitted field value.
     * @param string|int $field_id    Optional. ID of the field.
     * @return mixed
     */
    public static function pre_validate_filter($field_value, $field_id = '')
    {
    }
    /**
     * Allow field types to modify the appearance of their values.
     *
     * By default, this is a pass-through method that does nothing. Only
     * override in your own field type if you need to provide custom
     * filtering for output values.
     *
     * @since 2.1.0
     * @since 2.4.0 Added `$field_id` parameter.
     *
     * @param mixed      $field_value Field value.
     * @param string|int $field_id    ID of the field.
     * @return mixed
     */
    public static function display_filter($field_value, $field_id = '')
    {
    }
    /**
     * Save miscellaneous settings related to this field type.
     *
     * Override in a specific field type if it requires an admin save routine.
     *
     * @since 2.7.0
     *
     * @param int   $field_id Field ID.
     * @param array $settings Array of settings.
     */
    public function admin_save_settings($field_id, $settings)
    {
    }
    /** Protected *************************************************************/
    /**
     * Get a sanitized and escaped string of the edit field's HTML elements and attributes.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     * This method was intended to be static but couldn't be because php.net/lsb/ requires PHP >= 5.3.
     *
     * @since 2.0.0
     *
     * @param array $properties Optional key/value array of attributes for this edit field.
     * @return string
     */
    protected function get_edit_field_html_elements(array $properties = array())
    {
    }
    /**
     * Output a sanitized and escaped string of the edit field's HTML elements and attributes.
     *
     * @since 12.4.1
     *
     * @param array $properties Optional key/value array of attributes for this edit field.
     */
    protected function output_edit_field_html_elements(array $properties = array())
    {
    }
}
/**
 * Checkbox xprofile field type.
 *
 * @since 2.0.0
 */
class BP_XProfile_Field_Type_Checkbox extends \BP_XProfile_Field_Type
{
    /**
     * Constructor for the checkbox field type.
     *
     * @since 2.0.0
     */
    public function __construct()
    {
    }
    /**
     * Output the edit field HTML for this field type.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 2.0.0
     *
     * @param array $raw_properties Optional key/value array of
     *                              {@link http://dev.w3.org/html5/markup/input.checkbox.html permitted attributes}
     *                              that you want to add.
     */
    public function edit_field_html(array $raw_properties = array())
    {
    }
    /**
     * Output the edit field options HTML for this field type.
     *
     * BuddyPress considers a field's "options" to be, for example, the items in a selectbox.
     * These are stored separately in the database, and their templating is handled separately.
     *
     * This templating is separate from {@link BP_XProfile_Field_Type::edit_field_html()} because
     * it's also used in the wp-admin screens when creating new fields, and for backwards compatibility.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 2.0.0
     *
     * @param array $args Optional. The arguments passed to {@link bp_the_profile_field_options()}.
     */
    public function edit_field_options_html(array $args = array())
    {
    }
    /**
     * Output HTML for this field type on the wp-admin Profile Fields screen.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 2.0.0
     *
     * @param array $raw_properties Optional key/value array of permitted attributes that you want to add.
     */
    public function admin_field_html(array $raw_properties = array())
    {
    }
    /**
     * Output HTML for this field type's children options on the wp-admin Profile Fields "Add Field" and "Edit Field" screens.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 2.0.0
     *
     * @param BP_XProfile_Field $current_field The current profile field on the add/edit screen.
     * @param string            $control_type  Optional. HTML input type used to render the current
     *                                         field's child options.
     */
    public function admin_new_field_html(\BP_XProfile_Field $current_field, $control_type = '')
    {
    }
}
/**
 * XProfile Field Groups Endpoints.
 *
 * Use /xprofile/groups
 * Use /xprofile/groups/{id}
 *
 * @since 5.0.0
 */
class BP_REST_XProfile_Field_Groups_Endpoint extends \WP_REST_Controller
{
    /**
     * XProfile Fields Class.
     *
     * @since 5.0.0
     *
     * @var BP_REST_XProfile_Fields_Endpoint
     */
    protected $fields_endpoint;
    /**
     * Constructor.
     *
     * @since 5.0.0
     */
    public function __construct()
    {
    }
    /**
     * Register the component routes.
     *
     * @since 5.0.0
     */
    public function register_routes()
    {
    }
    /**
     * Edit some properties for the CREATABLE & EDITABLE methods.
     *
     * @since 5.0.0
     *
     * @param string $method Optional. HTTP method of the request.
     * @return array Endpoint arguments.
     */
    public function get_endpoint_args_for_item_schema($method = \WP_REST_Server::CREATABLE)
    {
    }
    /**
     * Retrieve XProfile groups.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_REST_Response
     */
    public function get_items($request)
    {
    }
    /**
     * Check if a given request has access to XProfile field groups items.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return true|WP_Error
     */
    public function get_items_permissions_check($request)
    {
    }
    /**
     * Retrieve single XProfile field group.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_item($request)
    {
    }
    /**
     * Check if a given request has access to get information about a specific field group.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return true|WP_Error
     */
    public function get_item_permissions_check($request)
    {
    }
    /**
     * Create a XProfile field group.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function create_item($request)
    {
    }
    /**
     * Check if a given request has access to create a XProfile field group.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return true|WP_Error
     */
    public function create_item_permissions_check($request)
    {
    }
    /**
     * Update a XProfile field group.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function update_item($request)
    {
    }
    /**
     * Check if a given request has access to create a XProfile field group.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return true|WP_Error
     */
    public function update_item_permissions_check($request)
    {
    }
    /**
     * Delete a XProfile field group.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function delete_item($request)
    {
    }
    /**
     * Check if a given request has access to delete a field group.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return true|WP_Error
     */
    public function delete_item_permissions_check($request)
    {
    }
    /**
     * Prepares single XProfile field group data for return as an object.
     *
     * @since 5.0.0
     *
     * @param BP_XProfile_Group $group   XProfile field group data.
     * @param WP_REST_Request   $request Full data about the request.
     * @return WP_REST_Response
     */
    public function prepare_item_for_response($group, $request)
    {
    }
    /**
     * Prepare links for the request.
     *
     * @since 5.0.0
     *
     * @param BP_XProfile_Group $group XProfile field group.
     * @return array
     */
    protected function prepare_links($group)
    {
    }
    /**
     * Get XProfile field group object.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return BP_XProfile_Group|string XProfile field group object.
     */
    public function get_xprofile_field_group_object($request)
    {
    }
    /**
     * Get the XProfile field group schema, conforming to JSON Schema.
     *
     * @since 5.0.0
     *
     * @return array
     */
    public function get_item_schema()
    {
    }
    /**
     * Get the query params for XProfile field groups.
     *
     * @since 5.0.0
     *
     * @return array
     */
    public function get_collection_params()
    {
    }
}
/**
 * Telephone number xprofile field type.
 *
 * @since 3.0.0
 */
class BP_XProfile_Field_Type_Telephone extends \BP_XProfile_Field_Type
{
    /**
     * Constructor for the telephone number field type.
     *
     * @since 3.0.0
     */
    public function __construct()
    {
    }
    /**
     * Output the edit field HTML for this field type.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 3.0.0
     *
     * @param array $raw_properties Optional key/value array of
     *                              {@link http://dev.w3.org/html5/markup/input.text.html permitted attributes}
     *                              that you want to add.
     */
    public function edit_field_html(array $raw_properties = array())
    {
    }
    /**
     * Output HTML for this field type on the wp-admin Profile Fields screen.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 3.0.0
     *
     * @param array $raw_properties Optional key/value array of permitted attributes that you want to add.
     */
    public function admin_field_html(array $raw_properties = array())
    {
    }
    /**
     * This method usually outputs HTML for this field type's children options on the wp-admin Profile Fields
     * "Add Field" and "Edit Field" screens, but for this field type, we don't want it, so it's stubbed out.
     *
     * @since 3.0.0
     *
     * @param BP_XProfile_Field $current_field The current profile field on the add/edit screen.
     * @param string            $control_type  Optional. HTML input type used to render the
     *                                         current field's child options.
     */
    public function admin_new_field_html(\BP_XProfile_Field $current_field, $control_type = '')
    {
    }
    /**
     * Format URL values for display.
     *
     * @since 3.0.0
     *
     * @param string     $field_value The URL value, as saved in the database.
     * @param string|int $field_id    Optional. ID of the field.
     *
     * @return string URL converted to a link.
     */
    public static function display_filter($field_value, $field_id = '')
    {
    }
}
/**
 * Textarea xprofile field type.
 *
 * @since 2.0.0
 */
class BP_XProfile_Field_Type_Textarea extends \BP_XProfile_Field_Type
{
    /**
     * Constructor for the textarea field type.
     *
     * @since 2.0.0
     */
    public function __construct()
    {
    }
    /**
     * Output the edit field HTML for this field type.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 2.0.0
     *
     * @param array $raw_properties Optional key/value array of
     *                              {@link http://dev.w3.org/html5/markup/textarea.html permitted attributes}
     *                              that you want to add.
     */
    public function edit_field_html(array $raw_properties = array())
    {
    }
    /**
     * Output HTML for this field type on the wp-admin Profile Fields screen.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 2.0.0
     *
     * @param array $raw_properties Optional key/value array of permitted attributes that you want to add.
     */
    public function admin_field_html(array $raw_properties = array())
    {
    }
    /**
     * This method usually outputs HTML for this field type's children options on the wp-admin Profile Fields
     * "Add Field" and "Edit Field" screens, but for this field type, we don't want it, so it's stubbed out.
     *
     * @since 2.0.0
     *
     * @param BP_XProfile_Field $current_field The current profile field on the add/edit screen.
     * @param string            $control_type  Optional. HTML input type used to render the current
     *                                         field's child options.
     */
    public function admin_new_field_html(\BP_XProfile_Field $current_field, $control_type = '')
    {
    }
}
/**
 * Textbox xprofile field type.
 *
 * @since 2.0.0
 */
class BP_XProfile_Field_Type_Textbox extends \BP_XProfile_Field_Type
{
    /**
     * Constructor for the textbox field type.
     *
     * @since 2.0.0
     */
    public function __construct()
    {
    }
    /**
     * Output the edit field HTML for this field type.
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 2.0.0
     *
     * @param array $raw_properties Optional key/value array of
     *                              {@link http://dev.w3.org/html5/markup/input.text.html permitted attributes}
     *                              that you want to add.
     */
    public function edit_field_html(array $raw_properties = array())
    {
    }
    /**
     * Output HTML for this field type on the wp-admin Profile Fields screen.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 2.0.0
     *
     * @param array $raw_properties Optional key/value array of permitted attributes that you want to add.
     */
    public function admin_field_html(array $raw_properties = array())
    {
    }
    /**
     * This method usually outputs HTML for this field type's children options on the wp-admin Profile Fields
     * "Add Field" and "Edit Field" screens, but for this field type, we don't want it, so it's stubbed out.
     *
     * @since 2.0.0
     *
     * @param BP_XProfile_Field $current_field The current profile field on the add/edit screen.
     * @param string            $control_type  Optional. HTML input type used to render the
     *                                         current field's child options.
     */
    public function admin_new_field_html(\BP_XProfile_Field $current_field, $control_type = '')
    {
    }
}
/**
 * URL xprofile field type.
 *
 * @since 2.1.0
 */
class BP_XProfile_Field_Type_URL extends \BP_XProfile_Field_Type
{
    /**
     * Supported features for the URL field type.
     *
     * @since 8.0.0
     * @var bool[] The URL field type supported features.
     */
    public static $supported_features = array('do_autolink' => \false);
    /**
     * Constructor for the URL field type
     *
     * @since 2.1.0
     */
    public function __construct()
    {
    }
    /**
     * Output the edit field HTML for this field type.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 2.1.0
     *
     * @param array $raw_properties Optional key/value array of
     *                              {@link http://dev.w3.org/html5/markup/input.number.html permitted attributes}
     *                              that you want to add.
     */
    public function edit_field_html(array $raw_properties = array())
    {
    }
    /**
     * Output HTML for this field type on the wp-admin Profile Fields screen.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 2.1.0
     *
     * @param array $raw_properties Optional key/value array of permitted
     *                              attributes that you want to add.
     */
    public function admin_field_html(array $raw_properties = array())
    {
    }
    /**
     * This method usually outputs HTML for this field type's children options
     * on the wp-admin Profile Fields "Add Field" and "Edit Field" screens, but
     * for this field type, we don't want it, so it's stubbed out.
     *
     * @since 2.1.0
     *
     * @param BP_XProfile_Field $current_field The current profile field on the add/edit screen.
     * @param string            $control_type  Optional. HTML input type used to render the current
     *                                         field's child options.
     */
    public function admin_new_field_html(\BP_XProfile_Field $current_field, $control_type = '')
    {
    }
    /**
     * Modify submitted URL values before validation.
     *
     * The URL validation regex requires a http(s) protocol, so that all
     * values saved in the database are fully-formed URLs. However, we
     * still want to allow users to enter URLs without a protocol, for a
     * better user experience. So we catch submitted URL values, and if
     * the protocol is missing, we prepend 'http://' before passing to
     * is_valid().
     *
     * @since 2.1.0
     * @since 2.4.0 Added the `$field_id` parameter.
     *
     * @param string     $submitted_value Raw value submitted by the user.
     * @param string|int $field_id        Optional. ID of the field.
     * @return string
     */
    public static function pre_validate_filter($submitted_value = '', $field_id = '')
    {
    }
    /**
     * Format URL values for display.
     *
     * @since 2.1.0
     * @since 2.4.0 Added the `$field_id` parameter.
     *
     * @param string     $field_value The URL value, as saved in the database.
     * @param string|int $field_id    Optional. ID of the field.
     * @return string URL converted to a link.
     */
    public static function display_filter($field_value, $field_id = '')
    {
    }
}
/**
 * XProfile Fields endpoints.
 *
 * Use /xprofile/fields
 * Use /xprofile/fields/{id}
 *
 * @since 5.0.0
 */
class BP_REST_XProfile_Fields_Endpoint extends \WP_REST_Controller
{
    /**
     * Constructor.
     *
     * @since 5.0.0
     */
    public function __construct()
    {
    }
    /**
     * Register the component routes.
     *
     * @since 5.0.0
     */
    public function register_routes()
    {
    }
    /**
     * Retrieve XProfile fields.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_REST_Response
     */
    public function get_items($request)
    {
    }
    /**
     * Check if a given request has access to XProfile fields.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return true|WP_Error
     */
    public function get_items_permissions_check($request)
    {
    }
    /**
     * Retrieve single XProfile field.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_item($request)
    {
    }
    /**
     * Check if a given request has access to get information about a specific XProfile field.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return true|WP_Error
     */
    public function get_item_permissions_check($request)
    {
    }
    /**
     * Set additional field properties.
     *
     * @since 5.0.0
     *
     * @param integer         $field_id The profile field object ID.
     * @param WP_REST_Request $request  The request sent to the API.
     */
    public function set_additional_field_properties($field_id, \WP_REST_Request $request)
    {
    }
    /**
     * Create a XProfile field.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function create_item($request)
    {
    }
    /**
     * Check if a given request has access to create a XProfile field.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return true|WP_Error
     */
    public function create_item_permissions_check($request)
    {
    }
    /**
     * Update a XProfile field.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function update_item($request)
    {
    }
    /**
     * Check if a given request has access to update a XProfile field.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return true|WP_Error
     */
    public function update_item_permissions_check($request)
    {
    }
    /**
     * Delete a XProfile field.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function delete_item($request)
    {
    }
    /**
     * Check if a given request has access to delete a XProfile field.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return true|WP_Error
     */
    public function delete_item_permissions_check($request)
    {
    }
    /**
     * Prepares single XProfile field data to return as an object.
     *
     * @since 5.0.0
     *
     * @param BP_XProfile_Field $field   XProfile field object.
     * @param WP_REST_Request   $request Full data about the request.
     * @return WP_REST_Response
     */
    public function prepare_item_for_response($field, $request)
    {
    }
    /**
     * Assembles single XProfile field data to return as an object.
     *
     * @since 5.0.0
     *
     * @param BP_XProfile_Field $field   XProfile field object.
     * @param WP_REST_Request   $request Full data about the request.
     * @return array
     */
    public function assemble_response_data($field, $request)
    {
    }
    /**
     * Prepare links for the request.
     *
     * @since 5.0.0
     *
     * @param BP_XProfile_Field $field XProfile field object.
     * @return array
     */
    protected function prepare_links($field)
    {
    }
    /**
     * Get XProfile field object.
     *
     * @since 5.0.0
     *
     * @param  WP_REST_Request|int $request Request info or integer.
     * @return BP_XProfile_Field|string
     */
    public function get_xprofile_field_object($request)
    {
    }
    /**
     * Retrieve the rendered value of a profile field.
     *
     * @since 5.0.0
     *
     * @param  string                    $value         The raw value of the field.
     * @param  integer|BP_XProfile_Field $profile_field The ID or the full object for the field.
     * @return string                                   The field value for the display context.
     */
    public function get_profile_field_rendered_value($value = '', $profile_field = \null)
    {
    }
    /**
     * Retrieve the unserialized value of a profile field.
     *
     * @since 5.0.0
     *
     * @param  string $value The raw value of the field.
     * @return array The unserialized field value.
     */
    public function get_profile_field_unserialized_value($value = '')
    {
    }
    /**
     * Edit some properties for the CREATABLE & EDITABLE methods.
     *
     * @since 5.0.0
     *
     * @param string $method Optional. HTTP method of the request.
     * @return array Endpoint arguments.
     */
    public function get_endpoint_args_for_item_schema($method = \WP_REST_Server::CREATABLE)
    {
    }
    /**
     * Get the XProfile field schema, conforming to JSON Schema.
     *
     * @since 5.0.0
     *
     * @return array
     */
    public function get_item_schema()
    {
    }
    /**
     * Get the query params for the XProfile fields.
     *
     * @since 5.0.0
     *
     * @return array
     */
    public function get_collection_params()
    {
    }
}
/**
 * Datebox xprofile field type.
 *
 * @since 2.0.0
 */
class BP_XProfile_Field_Type_Datebox extends \BP_XProfile_Field_Type
{
    /**
     * Constructor for the datebox field type.
     *
     * @since 2.0.0
     */
    public function __construct()
    {
    }
    /**
     * Output the edit field HTML for this field type.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 2.0.0
     *
     * @param array $raw_properties Optional key/value array of
     *                              {@link http://dev.w3.org/html5/markup/input.html permitted attributes}
     *                              that you want to add.
     */
    public function edit_field_html(array $raw_properties = array())
    {
    }
    /**
     * Output the edit field options HTML for this field type.
     *
     * BuddyPress considers a field's "options" to be, for example, the items in a selectbox.
     * These are stored separately in the database, and their templating is handled separately.
     *
     * This templating is separate from {@link BP_XProfile_Field_Type::edit_field_html()} because
     * it's also used in the wp-admin screens when creating new fields, and for backwards compatibility.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 2.0.0
     *
     * @param array $args Optional. The arguments passed to {@link bp_the_profile_field_options()}.
     */
    public function edit_field_options_html(array $args = array())
    {
    }
    /**
     * Output HTML for this field type on the wp-admin Profile Fields screen.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 2.0.0
     *
     * @param array $raw_properties Optional key/value array of permitted attributes that you want to add.
     */
    public function admin_field_html(array $raw_properties = array())
    {
    }
    /**
     * Get settings for a given date field.
     *
     * @since 2.7.0
     *
     * @param int $field_id ID of the field.
     * @return array
     */
    public static function get_field_settings($field_id)
    {
    }
    /**
     * Validate date field settings.
     *
     * @since 2.7.0
     *
     * @param array $settings Raw settings.
     * @return array Validated settings.
     */
    public static function validate_settings($settings)
    {
    }
    /**
     * Save settings from the field edit screen in the Dashboard.
     *
     * @param int   $field_id ID of the field.
     * @param array $settings Array of settings.
     * @return bool
     */
    public function admin_save_settings($field_id, $settings)
    {
    }
    /**
     * Generate the settings markup for Date fields.
     *
     * @since 2.7.0
     *
     * @param BP_XProfile_Field $current_field The current profile field on the add/edit screen.
     * @param string            $control_type  Optional. HTML input type used to render the current
     *                                         field's child options.
     */
    public function admin_new_field_html(\BP_XProfile_Field $current_field, $control_type = '')
    {
    }
    /**
     * Format Date values for display.
     *
     * @since 2.1.0
     * @since 2.4.0 Added the `$field_id` parameter.
     *
     * @param string     $field_value The date value, as saved in the database. Typically, this is a MySQL-formatted
     *                                date string (Y-m-d H:i:s).
     * @param string|int $field_id    Optional. ID of the field.
     * @return string Date formatted by bp_format_time().
     */
    public static function display_filter($field_value, $field_id = '')
    {
    }
    /**
     * Gets the default date formats available when configuring a Date field.
     *
     * @since 2.7.0
     *
     * @return array
     */
    public function get_date_formats()
    {
    }
}
/**
 * Base class for xprofile field types that set/get WordPress profile data from usermeta.
 *
 * @since 8.0.0
 */
abstract class BP_XProfile_Field_Type_WordPress extends \BP_XProfile_Field_Type
{
    /**
     * The usermeta key for the WordPress field.
     *
     * @since 8.0.0
     * @var string The meta key name of this WordPress field.
     */
    public $wp_user_key = '';
    /**
     * The WordPress supported user keys.
     *
     * @since 8.0.0
     * @var string[] The WordPress supported user keys.
     */
    public $supported_keys = array();
    /**
     * WordPress field's visibility setting.
     *
     * Defaults to 'public'. This property enforces Field's default visibility.
     *
     * @since 8.0.0
     *
     * @return string The WordPress field's visibility setting.
     */
    public $visibility = 'public';
    /**
     * Supported features for the WordPress field type.
     *
     * @since 8.0.0
     * @var bool[] The WordPress field supported features.
     */
    public static $supported_features = array('switch_fieldtype' => \false, 'required' => \false, 'do_autolink' => \false, 'allow_custom_visibility' => \false, 'member_types' => \false, 'signup_position' => \true);
    /**
     * Constructor for the WordPress field type.
     *
     * @since 8.0.0
     */
    public function __construct()
    {
    }
    /**
     * Sanitize the user field before inserting it into db.
     *
     * @since 8.0.0
     *
     * @param string $value The user field value.
     */
    abstract public function sanitize_for_db($value);
    /**
     * Sanitize the user field before displaying it as an attribute.
     *
     * @since 8.0.0
     *
     * @param string $value The user field value.
     * @param integer $user_id The user ID.
     */
    abstract public function sanitize_for_output($value, $user_id = 0);
    /**
     * Sets the WordPress field value.
     *
     * @since 8.0.0
     *
     * @param boolean $retval Whether to shortcircuit the $bp->profile->table_name_data table.
     *                        Default `false`.
     * @param array $field_args {
     *     An array of arguments.
     *
     *     @type object            $field_type_obj Field type object.
     *     @type BP_XProfile_Field $field          Field object.
     *     @type integer           $user_id        The user ID.
     *     @type mixed             $value          Value passed to xprofile_set_field_data().
     *     @type boolean           $is_required    Whether or not the field is required.
     * }
     * @return bool Whether to shortcircuit the $bp->profile->table_name_data table.
     */
    public function set_field_value($retval = \false, $field_args = array())
    {
    }
    /**
     * Gets the WordPress field value during an xProfile fields loop.
     *
     * This function is used inside `BP_XProfile_ProfileData::get_data_for_user()`
     * to include the WordPress field value into the xProfile fields loop.
     *
     * @since 8.0.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param integer $user_id The user ID.
     * @param integer $field_id The xProfile field ID.
     * @return array An array containing the metadata `id`, `value` and `table_name`.
     */
    public function get_field_value($user_id, $field_id = 0)
    {
    }
}
/**
 * Class to help set up XProfile Groups.
 *
 * @since 1.0.0
 */
#[\AllowDynamicProperties]
class BP_XProfile_Group
{
    /**
     * Field group ID.
     *
     * @since 1.1.0
     * @var int ID of field group.
     */
    public $id = \null;
    /**
     * Field group name.
     *
     * @since 1.1.0
     * @var string Name of field group.
     */
    public $name;
    /**
     * Field group Description.
     *
     * @since 1.1.0
     * @var string Description of field group.
     */
    public $description;
    /**
     * Group deletion boolean.
     *
     * @since 1.1.0
     * @var bool Can this group be deleted?
     */
    public $can_delete;
    /**
     * Group order.
     *
     * @since 1.1.0
     * @var int Group order relative to other groups.
     */
    public $group_order;
    /**
     * Group fields.
     *
     * @since 1.1.0
     * @var array Fields of group.
     */
    public $fields;
    /**
     * Initialize and/or populate profile field group.
     *
     * @since 1.1.0
     *
     * @param int|null $id Field group ID.
     */
    public function __construct($id = \null)
    {
    }
    /**
     * Populate a profile field group.
     *
     * @since 1.0.0
     *
     * @param int $id Field group ID.
     * @return bool
     */
    public function populate($id)
    {
    }
    /**
     * Save a profile field group.
     *
     * @since 1.1.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @return bool
     */
    public function save()
    {
    }
    /**
     * Delete a profile field group
     *
     * @since 1.1.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @return bool
     */
    public function delete()
    {
    }
    /** Static Methods ********************************************************/
    /**
     * Populates the BP_XProfile_Group object with profile field groups, fields,
     * and field data.
     *
     * @since 1.2.0
     * @since 2.4.0  Introduced `$member_type` argument.
     * @since 8.0.0  Introduced `$hide_field_types` & `$signup_fields_only` arguments.
     * @since 11.0.0 `$profile_group_id` accepts an array of profile group ids.
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param array $args {
     *      Array of optional arguments.
     *
     *      @type int|int[]|bool $profile_group_id   Limit results to a single profile group or a comma-separated list or array of
     *                                               profile group ids. Default: false.
     *      @type int            $user_id            Required if you want to load a specific user's data.
     *                                               Default: displayed user's ID.
     *      @type array|string   $member_type        Limit fields by those restricted to a given member type, or array of
     *                                               member types. If `$user_id` is provided, the value of `$member_type`
     *                                               will be overridden by the member types of the provided user. The
     *                                               special value of 'any' will return only those fields that are
     *                                               unrestricted by member type - i.e., those applicable to any type.
     *      @type bool           $hide_empty_groups  True to hide groups that don't have any fields. Default: false.
     *      @type bool           $hide_empty_fields  True to hide fields where the user has not provided data.
     *                                               Default: false.
     *      @type bool           $fetch_fields       Whether to fetch each group's fields. Default: false.
     *      @type bool           $fetch_field_data   Whether to fetch data for each field. Requires a $user_id.
     *                                               Default: false.
     *      @type int[]|bool     $exclude_groups     Comma-separated list or array of group IDs to exclude.
     *      @type int[]|bool     $exclude_fields     Comma-separated list or array of field IDs to exclude.
     *      @type string[]       $hide_field_types   List of field types to hide form loop. Default: empty array.
     *      @type bool           $signup_fields_only Whether to only return signup fields. Default: false.
     *      @type bool           $update_meta_cache  Whether to pre-fetch xprofilemeta for all retrieved groups, fields,
     *                                               and data. Default: true.
     * }
     * @return array
     */
    public static function get($args = array())
    {
    }
    /**
     * Gets group IDs, based on passed parameters.
     *
     * @since 5.0.0
     * @since 11.0.0 `$profile_group_id` accepts an array of profile group ids.
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param array $args {
     *    Array of optional arguments.
     *
     *    @type int|int[]|bool $profile_group_id  Limit results to a single profile group or a comma-separated list or array of
     *                                       profile group ids. Default: false.
     *    @type int[]          $exclude_groups    Comma-separated list or array of group IDs to exclude. Default: false.
     *    @type bool           $hide_empty_groups True to hide groups that don't have any fields. Default: false.
     * }
     * @return array
     */
    public static function get_group_ids($args = array())
    {
    }
    /**
     * Gets group field IDs, based on passed parameters.
     *
     * @since 5.0.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param array $group_ids Array of group IDs.
     * @param array $args {
     *    Array of optional arguments:
     *      @type array        $exclude_fields    Comma-separated list or array of field IDs to exclude.
     *                                            Default empty.
     *      @type int          $user_id           Limit results to fields associated with a given user's
     *                                            member type. Default empty.
     *      @type array|string $member_type       Limit fields by those restricted to a given member type, or array of
     *                                            member types. If `$user_id` is provided, the value of `$member_type`
     *                                            is honored.
     * }
     * @return array
     */
    public static function get_group_field_ids($group_ids, $args = array())
    {
    }
    /**
     * Get data about a set of groups, based on IDs.
     *
     * @since 2.0.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param array $group_ids Array of IDs.
     * @return array
     */
    protected static function get_group_data($group_ids)
    {
    }
    /**
     * Validate field group when form submitted.
     *
     * @since 1.0.0
     *
     * @global string $message The feedback message to show.
     *
     * @return bool
     */
    public static function admin_validate()
    {
    }
    /**
     * Update field group position.
     *
     * @since 1.5.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param  int $field_group_id ID of the group the field belongs to.
     * @param  int $position       Field group position.
     * @return bool
     */
    public static function update_position($field_group_id, $position)
    {
    }
    /**
     * Fetch the field visibility level for the fields returned by the query.
     *
     * @since 1.6.0
     *
     * @param int   $user_id The profile owner's user_id.
     * @param array $fields  The database results returned by the get() query.
     * @return array $fields The database results, with field_visibility added
     */
    public static function fetch_visibility_level($user_id = 0, $fields = array())
    {
    }
    /**
     * Fetch the admin-set preferences for all fields.
     *
     * @since 1.6.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @return array $default_visibility_levels An array, keyed by field_id, of default
     *                                          visibility level + allow_custom
     *                                          (whether the admin allows this
     *                                          field to be set by user)
     */
    public static function fetch_default_visibility_levels()
    {
    }
    /** Admin Output **********************************************************/
    /**
     * Output the admin area field group form.
     *
     * @since 1.0.0
     *
     * @global string $message The feedback message to show.
     */
    public function render_admin_form()
    {
    }
}
/**
 * Multi-selectbox xprofile field type.
 *
 * @since 2.0.0
 */
class BP_XProfile_Field_Type_Multiselectbox extends \BP_XProfile_Field_Type
{
    /**
     * Constructor for the multi-selectbox field type.
     *
     * @since 2.0.0
     */
    public function __construct()
    {
    }
    /**
     * Output the edit field HTML for this field type.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 2.0.0
     *
     * @param array $raw_properties Optional key/value array of
     *                              {@link http://dev.w3.org/html5/markup/select.html permitted attributes}
     *                              that you want to add.
     */
    public function edit_field_html(array $raw_properties = array())
    {
    }
    /**
     * Output the edit field options HTML for this field type.
     *
     * BuddyPress considers a field's "options" to be, for example, the items in a selectbox.
     * These are stored separately in the database, and their templating is handled separately.
     *
     * This templating is separate from {@link BP_XProfile_Field_Type::edit_field_html()} because
     * it's also used in the wp-admin screens when creating new fields, and for backwards compatibility.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 2.0.0
     *
     * @param array $args Optional. The arguments passed to {@link bp_the_profile_field_options()}.
     */
    public function edit_field_options_html(array $args = array())
    {
    }
    /**
     * Output HTML for this field type on the wp-admin Profile Fields screen.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 2.0.0
     *
     * @param array $raw_properties Optional key/value array of permitted attributes that you want to add.
     */
    public function admin_field_html(array $raw_properties = array())
    {
    }
    /**
     * Output HTML for this field type's children options on the wp-admin Profile Fields,
     * "Add Field" and "Edit Field" screens.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 2.0.0
     *
     * @param BP_XProfile_Field $current_field The current profile field on the add/edit screen.
     * @param string            $control_type  Optional. HTML input type used to render the current
     *                                         field's child options.
     */
    public function admin_new_field_html(\BP_XProfile_Field $current_field, $control_type = '')
    {
    }
}
/**
 * WordPress xProfile regular field type.
 *
 * @since 8.0.0
 */
class BP_XProfile_Field_Type_WordPress_Textbox extends \BP_XProfile_Field_Type_WordPress
{
    /**
     * Constructor for the WordPress regular field type.
     *
     * @since 8.0.0
     */
    public function __construct()
    {
    }
    /**
     * Sets the WordPress field wp_user_key property before saving the xProfile field.
     *
     * @since 8.0.0
     *
     * @param BP_XProfile_Field $field Field object.
     */
    public function set_wp_user_key($field)
    {
    }
    /**
     * Gets the WordPress field value during an xProfile fields loop.
     *
     * This function is used inside `BP_XProfile_ProfileData::get_data_for_user()`
     * to include the WordPress field value into the xProfile fields loop.
     *
     * @since 8.0.0
     *
     * @param integer $user_id The user ID.
     * @param integer $field_id The xProfile field ID.
     * @return array An array containing the metadata `id`, `value` and `table_name`.
     */
    public function get_field_value($user_id, $field_id = 0)
    {
    }
    /**
     * Sanitize the user field before saving it to db.
     *
     * @since 8.0.0
     *
     * @param string $value The user field value.
     * @return string The sanitized field value.
     */
    public function sanitize_for_db($value)
    {
    }
    /**
     * Sanitize the user field before displaying it as an attribute.
     *
     * @since 8.0.0
     *
     * @param string $value The user field value.
     * @return string The sanitized field value.
     */
    public function sanitize_for_output($value, $user_id = 0)
    {
    }
    /**
     * Output the edit field HTML for this field type.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 8.0.0
     *
     * @param array $raw_properties Optional key/value array of
     *                              {@link http://dev.w3.org/html5/markup/textarea.html permitted attributes}
     *                              that you want to add.
     */
    public function edit_field_html(array $raw_properties = array())
    {
    }
    /**
     * Output HTML for this field type on the wp-admin Profile Fields screen.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 8.0.0
     *
     * @param array $raw_properties Optional key/value array of permitted attributes that you want to add.
     */
    public function admin_field_html(array $raw_properties = array())
    {
    }
    /**
     * Get settings for a given WordPress field.
     *
     * @since 8.0.0
     *
     * @param int $field_id ID of the field.
     * @return string The meta_key used for this field.
     */
    public static function get_field_settings($field_id)
    {
    }
    /**
     * Save settings from the field edit screen in the Dashboard.
     *
     * @since 8.0.0
     *
     * @param int   $field_id ID of the field.
     * @param array $settings Array of settings.
     * @return bool
     */
    public function admin_save_settings($field_id, $settings)
    {
    }
    /**
     * This method usually outputs HTML for this field type's children options on the wp-admin Profile Fields
     * "Add Field" and "Edit Field" screens, but for this field type, we don't want it, so it's stubbed out.
     *
     * @since 8.0.0
     *
     * @param BP_XProfile_Field $current_field The current profile field on the add/edit screen.
     * @param string            $control_type  Optional. HTML input type used to render the
     *                                         current field's child options.
     */
    public function admin_new_field_html(\BP_XProfile_Field $current_field, $control_type = '')
    {
    }
    /**
     * Format WordPress field values for display.
     *
     * @since 8.0.0
     *
     * @param string     $field_value The field value, as saved in the database.
     * @param string|int $field_id    Optional. ID of the field.
     * @return string The sanitized WordPress field.
     */
    public static function display_filter($field_value, $field_id = '')
    {
    }
}
/**
 * Selectbox xprofile field type.
 *
 * @since 2.0.0
 */
class BP_XProfile_Field_Type_Selectbox extends \BP_XProfile_Field_Type
{
    /**
     * Constructor for the selectbox field type.
     *
     * @since 2.0.0
     */
    public function __construct()
    {
    }
    /**
     * Output the edit field HTML for this field type.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 2.0.0
     *
     * @param array $raw_properties Optional key/value array of
     *                              {@link http://dev.w3.org/html5/markup/select.html permitted attributes}
     *                              that you want to add.
     */
    public function edit_field_html(array $raw_properties = array())
    {
    }
    /**
     * Output the edit field options HTML for this field type.
     *
     * BuddyPress considers a field's "options" to be, for example, the items in a selectbox.
     * These are stored separately in the database, and their templating is handled separately.
     *
     * This templating is separate from {@link BP_XProfile_Field_Type::edit_field_html()} because
     * it's also used in the wp-admin screens when creating new fields, and for backwards compatibility.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 2.0.0
     *
     * @param array $args Optional. The arguments passed to {@link bp_the_profile_field_options()}.
     */
    public function edit_field_options_html(array $args = array())
    {
    }
    /**
     * Output HTML for this field type on the wp-admin Profile Fields screen.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 2.0.0
     *
     * @param array $raw_properties Optional key/value array of permitted attributes that you want to add.
     */
    public function admin_field_html(array $raw_properties = array())
    {
    }
    /**
     * Output HTML for this field type's children options on the wp-admin Profile Fields "Add Field" and "Edit Field" screens.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 2.0.0
     *
     * @param BP_XProfile_Field $current_field The current profile field on the add/edit screen.
     * @param string            $control_type  Optional. HTML input type used to render the current
     *                                         field's child options.
     */
    public function admin_new_field_html(\BP_XProfile_Field $current_field, $control_type = '')
    {
    }
}
/**
 * Load xProfile Profile admin area.
 *
 * @since 2.0.0
 */
class BP_XProfile_User_Admin
{
    /**
     * Setup xProfile User Admin.
     *
     * @since 2.0.0
     *
     * @return BP_XProfile_User_Admin
     */
    public static function register_xprofile_user_admin()
    {
    }
    /**
     * Constructor method.
     *
     * @since 2.0.0
     */
    public function __construct()
    {
    }
    /**
     * Register the xProfile metabox on Community Profile admin page.
     *
     * @since 2.0.0
     *
     * @param int         $user_id       ID of the user being edited.
     * @param string      $screen_id     Screen ID to load the metabox in.
     * @param object|null $stats_metabox Context and priority for the stats metabox.
     */
    public function register_metaboxes($user_id = 0, $screen_id = '', $stats_metabox = \null)
    {
    }
    /**
     * Save the profile fields in Members community profile page.
     *
     * Loaded before the page is rendered, this function is processing form
     * requests.
     *
     * @since 2.0.0
     * @since 6.0.0 The `delete_avatar` action is now managed into BP_Members_Admin::user_admin_load().
     *
     * @param string $doaction    Action being run.
     * @param int    $user_id     ID for the user whose profile is being saved.
     * @param array  $request     Request being made.
     * @param string $redirect_to Where to redirect user to.
     */
    public function user_admin_load($doaction = '', $user_id = 0, $request = array(), $redirect_to = '')
    {
    }
    /**
     * Render the xprofile metabox for Community Profile screen.
     *
     * @since 2.0.0
     *
     * @param WP_User|null $user The WP_User object for the user being edited.
     * @param array        $args Array of arguments for metaboxes.
     */
    public function user_admin_profile_metaboxes($user = \null, $args = array())
    {
    }
    /**
     * Render the fallback metabox in case a user has been marked as a spammer.
     *
     * @since 2.0.0
     *
     * @param WP_User|null $user The WP_User object for the user being edited.
     */
    public function user_admin_spammer_metabox($user = \null)
    {
    }
}
/**
 * WordPress Biography xProfile field type.
 *
 * @since 8.0.0
 */
class BP_XProfile_Field_Type_WordPress_Biography extends \BP_XProfile_Field_Type_WordPress
{
    /**
     * Constructor for the WordPress biography field type.
     *
     * @since 8.0.0
     */
    public function __construct()
    {
    }
    /**
     * Sanitize the user field before saving it to db.
     *
     * @since 8.0.0
     *
     * @param string $value The user field value.
     * @return string The sanitized field value.
     */
    public function sanitize_for_db($value)
    {
    }
    /**
     * Sanitize the user field before displaying it as an attribute.
     *
     * @since 8.0.0
     *
     * @param string $value The user field value.
     * @param integer $user_id The user ID.
     * @return string The sanitized field value.
     */
    public function sanitize_for_output($value, $user_id = 0)
    {
    }
    /**
     * Output the edit field HTML for this field type.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 8.0.0
     *
     * @param array $raw_properties Optional key/value array of
     *                              {@link http://dev.w3.org/html5/markup/textarea.html permitted attributes}
     *                              that you want to add.
     */
    public function edit_field_html(array $raw_properties = array())
    {
    }
    /**
     * Output HTML for this field type on the wp-admin Profile Fields screen.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 8.0.0
     *
     * @param array $raw_properties Optional key/value array of permitted attributes that you want to add.
     */
    public function admin_field_html(array $raw_properties = array())
    {
    }
    /**
     * This method usually outputs HTML for this field type's children options on the wp-admin Profile Fields
     * "Add Field" and "Edit Field" screens, but for this field type, we don't want it, so it's stubbed out.
     *
     * @since 8.0.0
     *
     * @param BP_XProfile_Field $current_field The current profile field on the add/edit screen.
     * @param string            $control_type  Optional. HTML input type used to render the
     *                                         current field's child options.
     */
    public function admin_new_field_html(\BP_XProfile_Field $current_field, $control_type = '')
    {
    }
    /**
     * Format WordPress Biography for display.
     *
     * @since 8.0.0
     *
     * @param string     $field_value The field value, as saved in the database.
     * @param string|int $field_id    Optional. ID of the field.
     * @return string The sanitized WordPress field.
     */
    public static function display_filter($field_value, $field_id = '')
    {
    }
}
/**
 * Class for generating SQL clauses to filter a user query by xprofile data.
 *
 * @since 2.2.0
 */
class BP_XProfile_Query
{
    /**
     * Array of xprofile queries.
     *
     * See {@see WP_XProfile_Query::__construct()} for information on parameters.
     *
     * @since 2.2.0
     * @var array
     */
    public $queries = array();
    /**
     * Database table that where the metadata's objects are stored (eg $wpdb->users).
     *
     * @since 2.2.0
     * @var string
     */
    public $primary_table;
    /**
     * Column in primary_table that represents the ID of the object.
     *
     * @since 2.2.0
     * @var string
     */
    public $primary_id_column;
    /**
     * A flat list of table aliases used in JOIN clauses.
     *
     * @since 2.2.0
     * @var array
     */
    protected $table_aliases = array();
    /**
     * Constructor.
     *
     * @since 2.2.0
     *
     * @param array $xprofile_query {
     *     Array of xprofile query clauses.
     *
     *     @type string $relation Optional. The MySQL keyword used to join the clauses of the query.
     *                            Accepts 'AND', or 'OR'. Default 'AND'.
     *     @type array {
     *         Optional. An array of first-order clause parameters, or another fully-formed xprofile query.
     *
     *         @type string|int $field   XProfile field to filter by. Accepts a field name or ID.
     *         @type string     $value   XProfile value to filter by.
     *         @type string     $compare MySQL operator used for comparing the $value. Accepts '=', '!=', '>',
     *                                   '>=', '<', '<=', 'LIKE', 'NOT LIKE', 'IN', 'NOT IN', 'BETWEEN',
     *                                   'NOT BETWEEN', 'REGEXP', 'NOT REGEXP', or 'RLIKE'. Default is 'IN'
     *                                   when `$value` is an array, '=' otherwise.
     *         @type string     $type    MySQL data type that the `value` column will be CAST to for comparisons.
     *                                   Accepts 'NUMERIC', 'BINARY', 'CHAR', 'DATE', 'DATETIME', 'DECIMAL',
     *                                   'SIGNED', 'TIME', or 'UNSIGNED'. Default is 'CHAR'.
     *     }
     * }
     */
    public function __construct($xprofile_query)
    {
    }
    /**
     * Ensure the `xprofile_query` argument passed to the class constructor is well-formed.
     *
     * Eliminates empty items and ensures that a 'relation' is set.
     *
     * @since 2.2.0
     *
     * @param array $queries Array of query clauses.
     * @return array Sanitized array of query clauses.
     */
    public function sanitize_query($queries)
    {
    }
    /**
     * Determine whether a query clause is first-order.
     *
     * A first-order query clause is one that has either a 'key' or a 'value' array key.
     *
     * @since 2.2.0
     *
     * @param  array $query XProfile query arguments.
     * @return bool  Whether the query clause is a first-order clause.
     */
    protected function is_first_order_clause($query)
    {
    }
    /**
     * Return the appropriate alias for the given field type if applicable.
     *
     * @since 2.2.0
     *
     * @param string $type MySQL type to cast `value`.
     * @return string MySQL type.
     */
    public function get_cast_for_type($type = '')
    {
    }
    /**
     * Generate SQL clauses to be appended to a main query.
     *
     * Called by the public {@see BP_XProfile_Query::get_sql()}, this method is abstracted out to maintain parity
     * with WP's Query classes.
     *
     * @since 2.2.0
     *
     * @return array {
     *     Array containing JOIN and WHERE SQL clauses to append to the main query.
     *
     *     @type string $join  SQL fragment to append to the main JOIN clause.
     *     @type string $where SQL fragment to append to the main WHERE clause.
     * }
     */
    protected function get_sql_clauses()
    {
    }
    /**
     * Generate SQL clauses for a single query array.
     *
     * If nested subqueries are found, this method recurses the tree to produce the properly nested SQL.
     *
     * @since 2.2.0
     *
     * @param  array $query Query to parse. Passed by reference.
     * @param  int   $depth Optional. Number of tree levels deep we currently are. Used to calculate indentation.
     * @return array {
     *     Array containing JOIN and WHERE SQL clauses to append to a single query array.
     *
     *     @type string $join  SQL fragment to append to the main JOIN clause.
     *     @type string $where SQL fragment to append to the main WHERE clause.
     * }
     */
    protected function get_sql_for_query(&$query, $depth = 0)
    {
    }
    /**
     * Generates SQL clauses to be appended to a main query.
     *
     * @since 2.2.0
     *
     * @param string $primary_table     Database table where the object being filtered is stored (eg wp_users).
     * @param string $primary_id_column ID column for the filtered object in $primary_table.
     * @return array {
     *     Array containing JOIN and WHERE SQL clauses to append to the main query.
     *
     *     @type string $join  SQL fragment to append to the main JOIN clause.
     *     @type string $where SQL fragment to append to the main WHERE clause.
     * }
     */
    public function get_sql($primary_table, $primary_id_column)
    {
    }
    /**
     * Generate SQL JOIN and WHERE clauses for a first-order query clause.
     *
     * "First-order" means that it's an array with a 'field' or 'value'.
     *
     * @since 2.2.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param array $clause       Query clause.
     * @param array $parent_query Parent query array.
     * @return array {
     *     Array containing JOIN and WHERE SQL clauses to append to a first-order query.
     *
     *     @type string $join  SQL fragment to append to the main JOIN clause.
     *     @type string $where SQL fragment to append to the main WHERE clause.
     * }
     */
    public function get_sql_for_clause(&$clause, $parent_query)
    {
    }
    /**
     * Identify an existing table alias that is compatible with the current query clause.
     *
     * We avoid unnecessary table joins by allowing each clause to look for an existing table alias that is
     * compatible with the query that it needs to perform. An existing alias is compatible if (a) it is a
     * sibling of $clause (ie, it's under the scope of the same relation), and (b) the combination of
     * operator and relation between the clauses allows for a shared table join. In the case of BP_XProfile_Query,
     * this * only applies to IN clauses that are connected by the relation OR.
     *
     * @since 2.2.0
     *
     * @param array $clause       Query clause.
     * @param array $parent_query Parent query of $clause.
     * @return string|bool Table alias if found, otherwise false.
     */
    protected function find_compatible_table_alias($clause, $parent_query)
    {
    }
}
/**
 * Creates our XProfile component.
 *
 * @since 1.5.0
 */
#[\AllowDynamicProperties]
class BP_XProfile_Component extends \BP_Component
{
    /**
     * Profile field types.
     *
     * @since 1.5.0
     * @var array
     */
    public $field_types;
    /**
     * The acceptable visibility levels for xprofile fields.
     *
     * @see bp_xprofile_get_visibility_levels()
     *
     * @since 1.6.0
     * @var array
     */
    public $visibility_levels = array();
    /**
     * Start the xprofile component creation process.
     *
     * @since 1.5.0
     */
    public function __construct()
    {
    }
    /**
     * Include files.
     *
     * @since 1.5.0
     *
     * @param array $includes Array of files to include.
     */
    public function includes($includes = array())
    {
    }
    /**
     * Late includes method.
     *
     * Only load up certain code when on specific pages.
     *
     * @since 3.0.0
     */
    public function late_includes()
    {
    }
    /**
     * Setup globals.
     *
     * The BP_XPROFILE_SLUG constant is deprecated.
     *
     * @since 1.5.0
     *
     * @param array $args Array of globals to set up.
     */
    public function setup_globals($args = array())
    {
    }
    /**
     * Register component navigation.
     *
     * @since 12.0.0
     *
     * @param array $main_nav See `BP_Component::register_nav()` for details.
     * @param array $sub_nav  See `BP_Component::register_nav()` for details.
     */
    public function register_nav($main_nav = array(), $sub_nav = array())
    {
    }
    /**
     * Set up the Settings > Profile nav item.
     *
     * Loaded in a separate method because the Settings component may not
     * be loaded in time for BP_XProfile_Component::setup_nav().
     *
     * @since 2.1.0
     * @deprecated 12.0.0
     */
    public function setup_settings_nav()
    {
    }
    /**
     * Set up the Admin Bar.
     *
     * @since 1.5.0
     *
     * @param array $wp_admin_nav Admin Bar items.
     */
    public function setup_admin_bar($wp_admin_nav = array())
    {
    }
    /**
     * Add custom hooks.
     *
     * @since 2.0.0
     */
    public function setup_hooks()
    {
    }
    /**
     * Sets up the title for pages and <title>.
     *
     * @since 1.5.0
     */
    public function setup_title()
    {
    }
    /**
     * Setup cache groups.
     *
     * @since 2.2.0
     */
    public function setup_cache_groups()
    {
    }
    /**
     * Adds "Settings > Profile" subnav item under the "Settings" adminbar menu.
     *
     * @since 2.0.0
     *
     * @param array $wp_admin_nav The settings adminbar nav array.
     * @return array
     */
    public function setup_settings_admin_nav($wp_admin_nav)
    {
    }
    /**
     * Init the BP REST API.
     *
     * @since 5.0.0
     *
     * @param array $controllers Optional. See BP_Component::rest_api_init() for
     *                           description.
     */
    public function rest_api_init($controllers = array())
    {
    }
    /**
     * Register the BP xProfile Blocks.
     *
     * @since 9.0.0
     *
     * @param array $blocks Optional. See BP_Component::blocks_init() for
     *                      description.
     */
    public function blocks_init($blocks = array())
    {
    }
}
/**
 * XProfile Data endpoints.
 *
 * Use /xprofile/{field_id}/data/{user_id}
 *
 * @since 5.0.0
 */
class BP_REST_XProfile_Data_Endpoint extends \WP_REST_Controller
{
    /**
     * XProfile Fields Class.
     *
     * @since 5.0.0
     *
     * @var BP_REST_XProfile_Fields_Endpoint
     */
    protected $fields_endpoint;
    /**
     * Constructor.
     *
     * @since 5.0.0
     */
    public function __construct()
    {
    }
    /**
     * Register the component routes.
     *
     * @since 5.0.0
     */
    public function register_routes()
    {
    }
    /**
     * Retrieve single XProfile field data.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_REST_Response
     */
    public function get_item($request)
    {
    }
    /**
     * Check if a given request has access to get users's data.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return true|WP_Error
     */
    public function get_item_permissions_check($request)
    {
    }
    /**
     * Save XProfile data.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function update_item($request)
    {
    }
    /**
     * Check if a given request has access to save XProfile field data.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return true|WP_Error
     */
    public function update_item_permissions_check($request)
    {
    }
    /**
     * Delete user's XProfile data.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function delete_item($request)
    {
    }
    /**
     * Check if a given request has access to delete users's data.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return true|WP_Error
     */
    public function delete_item_permissions_check($request)
    {
    }
    /**
     * Prepares XProfile data to return as an object.
     *
     * @since 5.0.0
     *
     * @param  BP_XProfile_ProfileData $field_data XProfile field data object.
     * @param  WP_REST_Request         $request    Full data about the request.
     * @return WP_REST_Response
     */
    public function prepare_item_for_response($field_data, $request)
    {
    }
    /**
     * Prepare links for the request.
     *
     * @since 5.0.0
     *
     * @param BP_XProfile_ProfileData $field_data XProfile field data object.
     * @return array
     */
    protected function prepare_links($field_data)
    {
    }
    /**
     * Get XProfile field object.
     *
     * @since 5.0.0
     *
     * @param int $field_id Field id.
     * @return BP_XProfile_Field
     */
    public function get_xprofile_field_object($field_id)
    {
    }
    /**
     * Get XProfile field data object.
     *
     * @since 5.0.0
     *
     * @param int $field_id Field id.
     * @param int $user_id User id.
     * @return BP_XProfile_ProfileData
     */
    public function get_xprofile_field_data_object($field_id, $user_id)
    {
    }
    /**
     * Can this user see the XProfile data?
     *
     * @since 5.0.0
     *
     * @param int $field_user_id User ID of the field.
     * @return bool
     */
    protected function can_see($field_user_id)
    {
    }
    /**
     * Get the XProfile data schema, conforming to JSON Schema.
     *
     * @since 5.0.0
     *
     * @return array
     */
    public function get_item_schema()
    {
    }
}
/**
 * Class for XProfile Profile Data setup.
 *
 * @since 1.6.0
 */
#[\AllowDynamicProperties]
class BP_XProfile_ProfileData
{
    /**
     * XProfile ID.
     *
     * @since 1.6.0
     * @var int $id
     */
    public $id;
    /**
     * User ID.
     *
     * @since 1.6.0
     * @var int $user_id
     */
    public $user_id;
    /**
     * XProfile field ID.
     *
     * @since 1.6.0
     * @var int $field_id
     */
    public $field_id;
    /**
     * XProfile field value.
     *
     * @since 1.6.0
     * @var string $value
     */
    public $value;
    /**
     * XProfile field last updated time.
     *
     * @since 1.6.0
     * @var string $last_updated
     */
    public $last_updated;
    /**
     * BP_XProfile_ProfileData constructor.
     *
     * @since 1.5.0
     *
     * @param int|null $field_id Field ID to instantiate.
     * @param int|null $user_id  User ID to instantiate for.
     */
    public function __construct($field_id = \null, $user_id = \null)
    {
    }
    /**
     * Populates the XProfile profile data.
     *
     * @since 1.0.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param int $field_id Field ID to populate.
     * @param int $user_id  User ID to populate for.
     */
    public function populate($field_id, $user_id)
    {
    }
    /**
     * Check if there is data already for the user.
     *
     * @since 1.0.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @return bool
     */
    public function exists()
    {
    }
    /**
     * Check if this data is for a valid field.
     *
     * @since 1.0.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @return bool
     */
    public function is_valid_field()
    {
    }
    /**
     * Save the data for the XProfile field.
     *
     * @since 1.0.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @return bool
     */
    public function save()
    {
    }
    /**
     * Delete specific XProfile field data.
     *
     * @since 1.0.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @return bool
     */
    public function delete()
    {
    }
    /** Static Methods ********************************************************/
    /**
     * Get a user's profile data for a set of fields.
     *
     * @since 2.0.0
     * @since 8.0.0 Checks if a null field data is an xProfile WP Field.
     *              Adds a new parameter `$field_type_objects` to pass the list of field type objects.
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param int   $user_id            ID of user whose data is being queried.
     * @param array $field_ids          Array of field IDs to query for.
     * @param array $field_type_objects Array of field type objects keyed by the queried filed IDs.
     * @return array
     */
    public static function get_data_for_user($user_id, $field_ids, $field_type_objects = array())
    {
    }
    /**
     * Get all of the profile information for a specific user.
     *
     * @since 1.2.0
     * @since 8.0.0 Checks if a null field data is an xProfile WP Field.
     *
     * @param int $user_id ID of the user.
     * @return array
     */
    public static function get_all_for_user($user_id)
    {
    }
    /**
     * Get the user's field data id by the id of the xprofile field.
     *
     * @since 1.6.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param int $field_id Field ID being queried for.
     * @param int $user_id  User ID associated with field.
     * @return int $fielddata_id
     */
    public static function get_fielddataid_byid($field_id, $user_id)
    {
    }
    /**
     * Get profile field values by field ID and user IDs.
     *
     * Supports multiple user IDs.
     *
     * @since 1.0.0
     * @since 8.0.0 Checks if a null field data is an xProfile WP Field.
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param int            $field_id ID of the field.
     * @param int|array|null $user_ids ID or IDs of user(s).
     * @return string|array Single value if a single user is queried,
     *                      otherwise an array of results.
     */
    public static function get_value_byid($field_id, $user_ids = \null)
    {
    }
    /**
     * Get profile field values by field name and user ID.
     *
     * @since 1.0.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @deprecated 8.0.0 This function is not used anymore.
     *
     * @param array|string $fields  Field(s) to get.
     * @param int|null     $user_id User ID to get field data for.
     * @return array|bool
     */
    public static function get_value_byfieldname($fields, $user_id = \null)
    {
    }
    /**
     * Delete field.
     *
     * @since 1.0.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param int $field_id ID of the field to delete.
     * @return bool
     */
    public static function delete_for_field($field_id)
    {
    }
    /**
     * Get time for last XProfile field data update by user.
     *
     * @since 1.0.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param int $user_id User ID to get time for.
     * @return null|string
     */
    public static function get_last_updated($user_id)
    {
    }
    /**
     * Delete all data for provided user ID.
     *
     * @since 1.0.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param int $user_id User ID to remove data for.
     * @return false|int
     */
    public static function delete_data_for_user($user_id)
    {
    }
    /**
     * Get random field type by user ID.
     *
     * @since 1.0.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param int    $user_id          User ID to query for.
     * @param string $exclude_fullname SQL portion used to exclude by field ID.
     * @return array|null|object
     */
    public static function get_random($user_id, $exclude_fullname)
    {
    }
    /**
     * Get fullname for provided user ID.
     *
     * @since 1.0.0
     *
     * @param int $user_id ID of the user to query.
     * @return mixed
     */
    public static function get_fullname($user_id = 0)
    {
    }
}
/**
 * Radio button xprofile field type.
 *
 * @since 2.0.0
 */
class BP_XProfile_Field_Type_Radiobutton extends \BP_XProfile_Field_Type
{
    /**
     * Constructor for the radio button field type.
     *
     * @since 2.0.0
     */
    public function __construct()
    {
    }
    /**
     * Output the edit field HTML for this field type.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 2.0.0
     *
     * @param array $raw_properties Optional key/value array of
     *                              {@link http://dev.w3.org/html5/markup/input.radio.html permitted attributes}
     *                              that you want to add.
     */
    public function edit_field_html(array $raw_properties = array())
    {
    }
    /**
     * Output the edit field options HTML for this field type.
     *
     * BuddyPress considers a field's "options" to be, for example, the items in a selectbox.
     * These are stored separately in the database, and their templating is handled separately.
     *
     * This templating is separate from {@link BP_XProfile_Field_Type::edit_field_html()} because
     * it's also used in the wp-admin screens when creating new fields, and for backwards compatibility.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 2.0.0
     *
     * @param array $args Optional. The arguments passed to {@link bp_the_profile_field_options()}.
     */
    public function edit_field_options_html(array $args = array())
    {
    }
    /**
     * Output HTML for this field type on the wp-admin Profile Fields screen.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 2.0.0
     *
     * @param array $raw_properties Optional key/value array of permitted attributes that you want to add.
     */
    public function admin_field_html(array $raw_properties = array())
    {
    }
    /**
     * Output HTML for this field type's children options on the wp-admin Profile Fields "Add Field" and "Edit Field" screens.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 2.0.0
     *
     * @param BP_XProfile_Field $current_field The current profile field on the add/edit screen.
     * @param string            $control_type  Optional. HTML input type used to render the current
     *                                         field's child options.
     */
    public function admin_new_field_html(\BP_XProfile_Field $current_field, $control_type = '')
    {
    }
}
/**
 * Number xprofile field type.
 *
 * @since 2.0.0
 */
class BP_XProfile_Field_Type_Number extends \BP_XProfile_Field_Type
{
    /**
     * Constructor for the number field type.
     *
     * @since 2.0.0
     */
    public function __construct()
    {
    }
    /**
     * Output the edit field HTML for this field type.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 2.0.0
     *
     * @param array $raw_properties Optional key/value array of
     *                              {@link http://dev.w3.org/html5/markup/input.number.html permitted attributes}
     *                              that you want to add.
     */
    public function edit_field_html(array $raw_properties = array())
    {
    }
    /**
     * Output HTML for this field type on the wp-admin Profile Fields screen.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 2.0.0
     *
     * @param array $raw_properties Optional key/value array of permitted attributes that you want to add.
     */
    public function admin_field_html(array $raw_properties = array())
    {
    }
    /**
     * This method usually outputs HTML for this field type's children options on the wp-admin Profile Fields
     * "Add Field" and "Edit Field" screens, but for this field type, we don't want it, so it's stubbed out.
     *
     * @since 2.0.0
     *
     * @param BP_XProfile_Field $current_field The current profile field on the add/edit screen.
     * @param string            $control_type  Optional. HTML input type used to render the current
     *                                         field's child options.
     */
    public function admin_new_field_html(\BP_XProfile_Field $current_field, $control_type = '')
    {
    }
}
/**
 * Checkbox Acceptance xProfile field type.
 *
 * @since 8.0.0
 */
class BP_XProfile_Field_Type_Checkbox_Acceptance extends \BP_XProfile_Field_Type
{
    /**
     * Checkbox Acceptance field's visibility setting.
     *
     * Defaults to 'adminsonly'. This property enforces Field's default visibility.
     *
     * @since 8.0.0
     *
     * @return string The Checkbox Acceptance field's visibility setting.
     */
    public $visibility = 'adminsonly';
    /**
     * Supported features for the Checkbox Acceptance field type.
     *
     * @since 8.0.0
     * @var bool[] The WordPress field supported features.
     */
    public static $supported_features = array('switch_fieldtype' => \false, 'required' => \true, 'do_autolink' => \false, 'allow_custom_visibility' => \false, 'member_types' => \false);
    /**
     * Constructor for the Checkbox Acceptance field type.
     *
     * @since 8.0.0
     */
    public function __construct()
    {
    }
    /**
     * Output the edit field HTML for this field type.
     *
     * Must be used inside the {@link bp_profile_fields()} template loop.
     *
     * @since 8.0.0
     *
     * @param array $raw_properties Optional key/value array of
     * {@link http://dev.w3.org/html5/markup/textarea.html permitted attributes}
     *  that you want to add.
     */
    public function edit_field_html(array $raw_properties = array())
    {
    }
    /**
     * Field html for Admin-> User->Profile Fields screen.
     *
     * @since 8.0.0
     *
     * @param array $raw_properties properties.
     */
    public function admin_field_html(array $raw_properties = array())
    {
    }
    /**
     * Admin new field screen.
     *
     * @since 8.0.0
     *
     * @param BP_XProfile_Field $current_field Profile field object.
     * @param string            $control_type  Control type.
     */
    public function admin_new_field_html(\BP_XProfile_Field $current_field, $control_type = '')
    {
    }
    /**
     * Save settings from the field edit screen in the Dashboard.
     *
     * @since 8.0.0
     *
     * @param int   $field_id ID of the field.
     * @param array $settings Array of settings.
     * @return bool
     */
    public function admin_save_settings($field_id, $settings)
    {
    }
    /**
     * Profile edit/register options html.
     *
     * @since 8.0.0
     *
     * @param array $args args.
     */
    public function edit_field_options_html(array $args = array())
    {
    }
    /**
     * Enforces the field value if it has been already accepted.
     *
     * As it's always possible to edit HTML source and remove the `readonly="readonly"` attribute
     * of the checkbox, we may need to enforce the field value.
     *
     * @since 8.0.0
     *
     * @param mixed             $value Value passed to xprofile_set_field_data().
     * @param BP_XProfile_Field $field Field object.
     * @return mixed The field value.
     */
    public function enforce_field_value($value, \BP_XProfile_Field $field)
    {
    }
    /**
     * Check if field is valid?
     *
     * @since 8.0.0
     *
     * @param string|int $values value.
     * @return bool
     */
    public function is_valid($value)
    {
    }
    /**
     * Modify the appearance of value.
     *
     * @since 8.0.0
     *
     * @param string $field_value Original value of field.
     * @param int    $field_id field id.
     *
     * @return string   Value formatted
     */
    public static function display_filter($field_value, $field_id = 0)
    {
    }
}
/**
 * The main profile template loop class.
 *
 * This is responsible for loading profile field, group, and data and displaying it.
 *
 * @since 1.0.0
 */
class BP_XProfile_Data_Template
{
    /**
     * The loop iterator.
     *
     * @since 1.5.0
     * @var int
     */
    public $current_group = -1;
    /**
     * The number of groups returned by the paged query.
     *
     * @since 1.5.0
     * @var int
     */
    public $group_count;
    /**
     * Array of groups located by the query.
     *
     * @since 1.5.0
     * @var array
     */
    public $groups;
    /**
     * The group object currently being iterated on.
     *
     * @since 1.5.0
     * @var object
     */
    public $group;
    /**
     * The current field.
     *
     * @since 1.5.0
     * @var int
     */
    public $current_field = -1;
    /**
     * The field count.
     *
     * @since 1.5.0
     * @var int
     */
    public $field_count;
    /**
     * Field has data.
     *
     * @since 1.5.0
     * @var bool
     */
    public $field_has_data;
    /**
     * The field.
     *
     * @since 1.5.0
     * @var int
     */
    public $field;
    /**
     * A flag for whether the loop is currently being iterated.
     *
     * @since 1.5.0
     * @var bool
     */
    public $in_the_loop;
    /**
     * The user ID.
     *
     * @since 1.5.0
     * @var int
     */
    public $user_id;
    /**
     * Get activity items, as specified by parameters.
     *
     * @see BP_XProfile_Group::get() for more details about parameters.
     *
     * @since 1.5.0
     * @since 2.4.0  Introduced `$member_type` argument.
     * @since 8.0.0  Introduced `$hide_field_types` & `$signup_fields_only` arguments.
     * @since 11.0.0 `$profile_group_id` accepts an array of profile group ids.
     *
     * @param array|string $args {
     *     An array of arguments. All items are optional.
     *
     *     @type int            $user_id                 Fetch field data for this user ID.
     *     @type string|array   $member_type             Limit results to those matching member type(s).
     *     @type int|int[]|bool $profile_group_id        Field group(s) to fetch fields & data for. Default: false.
     *     @type int|bool       $hide_empty_groups       Should empty field groups be skipped.
     *     @type int|bool       $fetch_fields            Fetch fields for field group.
     *     @type int|bool       $fetch_field_data        Fetch field data for fields in group.
     *     @type array          $exclude_groups          Exclude these field groups.
     *     @type array          $exclude_fields          Exclude these fields.
     *     @type int|bool       $hide_empty_fields       Should empty fields be skipped.
     *     @type int|bool       $fetch_visibility_level  Fetch visibility levels.
     *     @type string[]       $hide_field_types        List of field types to hide form loop. Default: empty array.
     *     @type bool           $signup_fields_only      Whether to only return signup fields. Default: false.
     *     @type int|bool       $update_meta_cache       Should metadata cache be updated.
     * }
     */
    public function __construct($args = '')
    {
    }
    /**
     * Whether or not the loop has field groups.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function has_groups()
    {
    }
    /**
     * Increments to the next group of fields.
     *
     * @since 1.0.0
     *
     * @return object
     */
    public function next_group()
    {
    }
    /**
     * Rewinds to the start of the groups list.
     *
     * @since 1.0.0
     */
    public function rewind_groups()
    {
    }
    /**
     * Kicks off the profile groups.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function profile_groups()
    {
    }
    /**
     * Sets up the profile group.
     *
     * @since 1.0.0
     *
     * @global object $group Current group of profile fields.
     *
     */
    public function the_profile_group()
    {
    }
    /** Fields ****************************************************************/
    /**
     * Increments to the next field.
     *
     * @since 1.0.0
     *
     * @return int
     */
    public function next_field()
    {
    }
    /**
     * Rewinds to the start of the fields.
     *
     * @since 1.0.0
     */
    public function rewind_fields()
    {
    }
    /**
     * Whether or not the loop has fields.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function has_fields()
    {
    }
    /**
     * Kick off the profile fields.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function profile_fields()
    {
    }
    /**
     * Set up the profile fields.
     *
     * @since 1.0.0
     *
     * @global object $field Current profile field.
     *
     */
    public function the_profile_field()
    {
    }
}
/**
 * A placeholder xprofile field type. Doesn't do anything.
 *
 * Used if an existing field has an unknown type (e.g. one provided by a missing third-party plugin).
 *
 * @since 2.0.1
 */
class BP_XProfile_Field_Type_Placeholder extends \BP_XProfile_Field_Type
{
    /**
     * Constructor for the placeholder field type.
     *
     * @since 2.0.1
     */
    public function __construct()
    {
    }
    /**
     * Prevent any HTML being output for this field type.
     *
     * @since 2.0.1
     *
     * @param array $raw_properties Optional key/value array of
     *                              {@link http://dev.w3.org/html5/markup/input.text.html permitted attributes}
     *                              that you want to add.
     */
    public function edit_field_html(array $raw_properties = array())
    {
    }
    /**
     * Prevent any HTML being output for this field type.
     *
     * @since 2.0.1
     *
     * @param array $raw_properties Optional key/value array of permitted attributes that you want to add.
     */
    public function admin_field_html(array $raw_properties = array())
    {
    }
    /**
     * Prevent any HTML being output for this field type.
     *
     * @since 2.0.1
     *
     * @param BP_XProfile_Field $current_field The current profile field on the add/edit screen.
     * @param string            $control_type  Optional. HTML input type used to render the current
     *                                         field's child options.
     */
    public function admin_new_field_html(\BP_XProfile_Field $current_field, $control_type = '')
    {
    }
}
/**
 * Class for generating SQL clauses that filter a primary query according to
 * XProfile metadata keys and values.
 *
 * `BP_XProfile_Meta_Query` is a helper that allows primary query classes, such
 * as {@see WP_Query} and {@see WP_User_Query}, to filter their results by object
 * metadata, by generating `JOIN` and `WHERE` subclauses to be attached
 * to the primary SQL query string.
 *
 * @since 2.3.0
 */
class BP_XProfile_Meta_Query extends \WP_Meta_Query
{
    /**
     * Determine whether a query clause is first-order.
     *
     * A first-order meta query clause is one that has either a 'key', 'value',
     * or 'object' array key.
     *
     * @since 2.3.0
     *
     * @param array $query Meta query arguments.
     * @return bool Whether the query clause is a first-order clause.
     */
    protected function is_first_order_clause($query)
    {
    }
    /**
     * Constructs a meta query based on 'meta_*' query vars.
     *
     * @since 2.3.0
     *
     * @param array $qv The query variables.
     */
    public function parse_query_vars($qv)
    {
    }
    /**
     * Generates SQL clauses to be appended to a main query.
     *
     * @since 2.3.0
     *
     * @param string      $type              Type of meta, eg 'user', 'post'.
     * @param string      $primary_table     Database table where the object being filtered is stored (eg wp_users).
     * @param string      $primary_id_column ID column for the filtered object in $primary_table.
     * @param object|null $context           Optional. The main query object.
     * @return array {
     *     Array containing JOIN and WHERE SQL clauses to append to the main query.
     *
     *     @type string $join  SQL fragment to append to the main JOIN clause.
     *     @type string $where SQL fragment to append to the main WHERE clause.
     * }
     */
    public function get_sql($type, $primary_table, $primary_id_column, $context = \null)
    {
    }
    /**
     * Generate SQL JOIN and WHERE clauses for a first-order query clause.
     *
     * "First-order" means that it's an array with a 'key' or 'value'.
     *
     * @since 2.3.0
     * 
     * @global wpdb $wpdb WordPress database object.
     *
     * @param array  $clause       Query clause, passed by reference.
     * @param array  $parent_query Parent query array.
     * @param string $clause_key   Optional. The array key used to name the clause in the original `$meta_query`
     *                             parameters. If not provided, a key will be generated automatically.
     * @return array {
     *     Array containing JOIN and WHERE SQL clauses to append to a first-order query.
     *
     *     @type string $join  SQL fragment to append to the main JOIN clause.
     *     @type string $where SQL fragment to append to the main WHERE clause.
     * }
     */
    public function get_sql_for_clause(&$clause, $parent_query, $clause_key = '')
    {
    }
}
/**
 * Query for the members of a group.
 *
 * Special notes about the group members data schema:
 * - *Members* are entries with is_confirmed = 1.
 * - *Pending requests* are entries with is_confirmed = 0 and inviter_id = 0.
 * - *Pending and sent invitations* are entries with is_confirmed = 0 and
 *   inviter_id != 0 and invite_sent = 1.
 * - *Pending and unsent invitations* are entries with is_confirmed = 0 and
 *   inviter_id != 0 and invite_sent = 0.
 * - *Membership requests* are entries with is_confirmed = 0 and
 *   inviter_id = 0 (and invite_sent = 0).
 *
 * @since 1.8.0
 * @since 3.0.0 $group_id now supports multiple values.
 *
 * @param array $args  {
 *     Array of arguments. Accepts all arguments from
 *     {@link BP_User_Query}, with the following additions:
 *
 *     @type int|array|string $group_id     ID of the group to limit results to. Also accepts multiple values
 *                                          either as an array or as a comma-delimited string.
 *     @type array            $group_role   Array of group roles to match ('member', 'mod', 'admin', 'banned').
 *                                          Default: array( 'member' ).
 *     @type bool             $is_confirmed Whether to limit to confirmed members. Default: true.
 *     @type string           $type         Sort order. Accepts any value supported by {@link BP_User_Query}, in
 *                                          addition to 'last_joined' and 'first_joined'. Default: 'last_joined'.
 * }
 */
class BP_Group_Member_Query extends \BP_User_Query
{
    /**
     * Array of group member ids, cached to prevent redundant lookups.
     *
     * @since 1.8.1
     * @var null|array Null if not yet defined, otherwise an array of ints.
     */
    protected $group_member_ids;
    /**
     * Constructor.
     *
     * @since 10.3.0
     *
     * @param string|array|null $query See {@link BP_User_Query}.
     */
    public function __construct($query = \null)
    {
    }
    /**
     * Set up action hooks.
     *
     * @since 1.8.0
     */
    public function setup_hooks()
    {
    }
    /**
     * Use WP_User_Query() to pull data for the user IDs retrieved in the main query.
     *
     * If a `count` query is performed, the function is used to validate existing users.
     *
     * @since 10.3.0
     */
    public function do_wp_user_query()
    {
    }
    /**
     * Get a list of user_ids to include in the IN clause of the main query.
     *
     * Overrides BP_User_Query::get_include_ids(), adding our additional
     * group-member logic.
     *
     * @since 1.8.0
     *
     * @param array $include Existing group IDs in the $include parameter,
     *                       as calculated in BP_User_Query.
     * @return array
     */
    public function get_include_ids($include = array())
    {
    }
    /**
     * Get the members of the queried group.
     *
     * @since 1.8.0
     *
     * @return array $ids User IDs of relevant group member ids.
     */
    protected function get_group_member_ids()
    {
    }
    /**
     * Tell BP_User_Query to order by the order of our query results.
     *
     * We only override BP_User_Query's native ordering in case of the
     * 'last_joined' and 'first_joined' $type parameters.
     *
     * @since 1.8.1
     *
     * @param BP_User_Query $query BP_User_Query object.
     */
    public function set_orderby($query)
    {
    }
    /**
     * Fetch additional data required in bp_group_has_members() loops.
     *
     * Additional data fetched:
     *      - is_banned
     *      - date_modified
     *
     * @since 1.8.0
     *
     * @param BP_User_Query $query        BP_User_Query object. Because we're
     *                                    filtering the current object, we use
     *                                    $this inside of the method instead.
     * @param string        $user_ids_sql Sanitized, comma-separated string of
     *                                    the user ids returned by the main query.
     */
    public function populate_group_member_extras($query, $user_ids_sql)
    {
    }
    /**
     * Sort user IDs by how recently they have generated activity within a given group.
     *
     * @since 2.1.0
     *
     * @param BP_User_Query $query  BP_User_Query object.
     * @param array         $gm_ids array of group member ids.
     * @return array
     */
    public function get_gm_ids_ordered_by_activity($query, $gm_ids = array())
    {
    }
    /**
     * Perform a database query to populate any extra metadata we might need.
     *
     * If a `count` query is performed, the function is used to validate active users.
     *
     * @since 10.3.0
     * @since 11.0.0 Include inactive users added by a community administrators to the group members count.
     */
    public function populate_extras()
    {
    }
}
/**
 * Group membership endpoints.
 *
 * Use /groups/{group_id}/members
 * Use /groups/{group_id}/members/{user_id}
 *
 * @since 5.0.0
 */
class BP_REST_Group_Membership_Endpoint extends \WP_REST_Controller
{
    /**
     * Reuse some parts of the BP_REST_Groups_Endpoint class.
     *
     * @since 5.0.0
     *
     * @var BP_REST_Groups_Endpoint
     */
    protected $groups_endpoint;
    /**
     * Reuse some parts of the BP_REST_Members_Endpoint class.
     *
     * @since 5.0.0
     *
     * @var BP_REST_Members_Endpoint
     */
    protected $members_endpoint;
    /**
     * Constructor.
     *
     * @since 5.0.0
     */
    public function __construct()
    {
    }
    /**
     * Register the component routes.
     *
     * @since 5.0.0
     */
    public function register_routes()
    {
    }
    /**
     * Retrieve group members.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response
     */
    public function get_items($request)
    {
    }
    /**
     * Check if a given request has access to group members.
     *
     * We are using the same permissions check done on group access.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return true|WP_Error
     */
    public function get_items_permissions_check($request)
    {
    }
    /**
     * Add member to a group.
     *
     * @since 5.0.0
     *
     * @param  WP_REST_Request $request Full data about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function create_item($request)
    {
    }
    /**
     * Checks if a given request has access to join a group.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return true|WP_Error
     */
    public function create_item_permissions_check($request)
    {
    }
    /**
     * Update user status on a group (add, remove, promote, demote or ban).
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function update_item($request)
    {
    }
    /**
     * Check if a given request has access to update a group member.
     *
     * @since 5.0.0
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return true|WP_Error
     */
    public function update_item_permissions_check($request)
    {
    }
    /**
     * Delete a group membership.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function delete_item($request)
    {
    }
    /**
     * Check if a given request has access to delete a group member.
     *
     * @since 5.0.0
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return true|WP_Error
     */
    public function delete_item_permissions_check($request)
    {
    }
    /**
     * Prepares group member data for return as an object.
     *
     * @since 5.0.0
     *
     * @param BP_Groups_Member $group_member Group member object.
     * @param WP_REST_Request  $request      Full details about the request.
     * @return WP_REST_Response
     */
    public function prepare_item_for_response($group_member, $request)
    {
    }
    /**
     * Prepare links for the request.
     *
     * @since 5.0.0
     *
     * @param BP_Groups_Member $group_member Group member object.
     * @return array
     */
    protected function prepare_links($group_member)
    {
    }
    /**
     * GET arguments for the endpoint's CREATABLE, EDITABLE & DELETABLE methods.
     *
     * @since 5.0.0
     *
     * @param string $method Optional. HTTP method of the request.
     * @return array Endpoint arguments.
     */
    public function get_endpoint_args_for_method($method = \WP_REST_Server::CREATABLE)
    {
    }
    /**
     * Get the group member schema, conforming to JSON Schema.
     *
     * @since 5.0.0
     *
     * @return array
     */
    public function get_item_schema()
    {
    }
    /**
     * Get the query params for collections of group memberships.
     *
     * @since 5.0.0
     *
     * @return array
     */
    public function get_collection_params()
    {
    }
}
/**
 * Group Membership Request Endpoint.
 *
 * Use /groups/{group_id}/membership-request
 * Use /groups/membership-request/{request_id}
 *
 * @since 5.0.0
 */
class BP_REST_Group_Membership_Request_Endpoint extends \WP_REST_Controller
{
    /**
     * Reuse some parts of the BP_REST_Groups_Endpoint class.
     *
     * @since 5.0.0
     *
     * @var BP_REST_Groups_Endpoint
     */
    protected $groups_endpoint;
    /**
     * Reuse some parts of the BP_REST_Group_Invites_Endpoint class.
     *
     * @since 5.0.0
     *
     * @var BP_REST_Group_Invites_Endpoint
     */
    protected $invites_endpoint;
    /**
     * Constructor.
     *
     * @since 5.0.0
     */
    public function __construct()
    {
    }
    /**
     * Register the component routes.
     *
     * @since 5.0.0
     */
    public function register_routes()
    {
    }
    /**
     * Fetch pending group membership requests.
     *
     * @since 5.0.0
     *
     * @param  WP_REST_Request $request Full data about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_items($request)
    {
    }
    /**
     * Check if a given request has access to fetch group membership requests.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return true|WP_Error
     */
    public function get_items_permissions_check($request)
    {
    }
    /**
     * Fetch a sepcific pending group membership request by ID.
     *
     * @since 5.0.0
     *
     * @param  WP_REST_Request $request Full data about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_item($request)
    {
    }
    /**
     * Check if a given request has access to fetch group membership request.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return true|WP_Error
     */
    public function get_item_permissions_check($request)
    {
    }
    /**
     * Request membership to a group.
     *
     * @since 5.0.0
     *
     * @param  WP_REST_Request $request Full data about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function create_item($request)
    {
    }
    /**
     * Checks if a given request has access to make a group membership request.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return true|WP_Error
     */
    public function create_item_permissions_check($request)
    {
    }
    /**
     * Accept or reject a pending group membership request.
     *
     * @since 5.0.0
     *
     * @param  WP_REST_Request $request Full data about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function update_item($request)
    {
    }
    /**
     * Checks if a given request has access to accept a group membership request.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return true|WP_Error
     */
    public function update_item_permissions_check($request)
    {
    }
    /**
     * Reject a pending group membership request.
     *
     * @since 5.0.0
     *
     * @param  WP_REST_Request $request Full data about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function delete_item($request)
    {
    }
    /**
     * Checks if a given request has access to reject a group membership request.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return true|WP_Error
     */
    public function delete_item_permissions_check($request)
    {
    }
    /**
     * Prepares group invitation data to return as an object.
     *
     * @since 5.0.0
     *
     * @param BP_Invitation   $invite  Invite object.
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response
     */
    public function prepare_item_for_response($invite, $request)
    {
    }
    /**
     * Prepare links for the request.
     *
     * @since 5.0.0
     *
     * @param BP_Invitation $invite Invite object.
     * @return array
     */
    protected function prepare_links($invite)
    {
    }
    /**
     * Helper function to fetch a single group invite.
     *
     * @since 5.0.0
     *
     * @param int $request_id The ID of the request you wish to fetch.
     * @return BP_Invitation|bool $group_request Membership request if found, false otherwise.
     */
    public function fetch_single_membership_request($request_id = 0)
    {
    }
    /**
     * Endpoint args.
     *
     * @since 5.0.0
     *
     * @param string $method Optional. HTTP method of the request.
     * @return array Endpoint arguments.
     */
    public function get_endpoint_args_for_item_schema($method = \WP_REST_Server::CREATABLE)
    {
    }
    /**
     * Get the group membership request schema, conforming to JSON Schema.
     *
     * @since 5.0.0
     *
     * @return array
     */
    public function get_item_schema()
    {
    }
    /**
     * Get the query params for collections of group invites.
     *
     * @since 5.0.0
     *
     * @return array
     */
    public function get_collection_params()
    {
    }
}
/**
 * API for creating group extensions without having to hardcode the content into
 * the theme.
 *
 * To implement, extend this class. In your constructor, pass an optional array
 * of arguments to parent::init() to configure your widget. The config array
 * supports the following values:
 *   - 'slug' A unique identifier for your extension. This value will be used
 *     to build URLs, so make it URL-safe.
 *   - 'name' A translatable name for your extension. This value is used to
 *     populate the navigation tab, as well as the default titles for admin/
 *     edit/create tabs.
 *   - 'visibility' Set to 'public' (default) for your extension (the main tab
 *     as well as the widget) to be available to anyone who can access the
 *     group, 'private' otherwise.
 *   - 'nav_item_position' An integer explaining where the nav item should
 *     appear in the tab list.
 *   - 'enable_nav_item' Set to true for your extension's main tab to be
 *     available to anyone who can access the group.
 *   - 'nav_item_name' The translatable text you want to appear in the nav tab.
 *     Defaults to the value of 'name'.
 *   - 'display_hook' The WordPress action that the widget_display() method is
 *     hooked to.
 *   - 'template_file' The template file that will be used to load the content
 *     of your main extension tab. Defaults to 'groups/single/plugins.php'.
 *   - 'screens' A multi-dimensional array, described below.
 *   - 'access' Which users can visit the plugin's tab.
 *   - 'show_tab' Which users can see the plugin's navigation tab.
 *
 * BP_Group_Extension uses the concept of "settings screens". There are three
 * contexts for settings screens:
 *   - 'create', which inserts a new step into the group creation process
 *   - 'edit', which adds a tab for your extension into the Admin section of
 *     a group
 *   - 'admin', which adds a metabox to the Groups administration panel in the
 *     WordPress Dashboard
 * Each of these settings screens is populated by a pair of methods: one that
 * creates the markup for the screen, and one that processes form data
 * submitted from the screen. If your plugin needs screens in all three
 * contexts, and if the markup and form processing logic will be the same in
 * each case, you can define two methods to handle all of the screens:
 *   function settings_screen() {}
 *   function settings_screen_save() {}
 * If one or more of your settings screen needs separate logic, you may define
 * context-specific methods, for example:
 *   function edit_screen() {}
 *   function edit_screen_save() {}
 * BP_Group_Extension will use the more specific methods if they are available.
 *
 * You can further customize the settings screens (tab names, etc) by passing
 * an optional 'screens' parameter to the init array. The format is as follows:
 *   'screens' => array(
 *       'create' => array(
 *       'slug' => 'foo',
 *       'name' => 'Foo',
 *       'position' => 55,
 *       'screen_callback' => 'my_create_screen_callback',
 *       'screen_save_callback' => 'my_create_screen_save_callback',
 *   ),
 *   'edit' => array( // ...
 *   ),
 * Only provide those arguments that you actually want to change from the
 * default configuration. BP_Group_Extension will do the rest.
 *
 * Note that the 'edit' screen accepts an additional parameter: 'submit_text',
 * which defines the text of the Submit button automatically added to the Edit
 * screen of the extension (defaults to 'Save Changes'). Also, the 'admin'
 * screen accepts two additional parameters: 'metabox_priority' and
 * 'metabox_context'. See the docs for add_meta_box() for more details on these
 * arguments.
 *
 * Prior to BuddyPress 1.7, group extension configurations were set slightly
 * differently. The legacy method is still supported, though deprecated.
 *
 * @since 1.1.0
 */
class BP_Group_Extension
{
    /** Public ************************************************************/
    /**
     * Information about this extension's screens.
     *
     * @since 1.8.0
     * @var array
     */
    public $screens = array();
    /**
     * The name of the extending class.
     *
     * @since 1.8.0
     * @var string
     */
    public $class_name = '';
    /**
     * A ReflectionClass object of the current extension.
     *
     * @since 1.8.0
     * @var ReflectionClass
     */
    public $class_reflection = \null;
    /**
     * Parsed configuration parameters for the extension.
     *
     * @since 1.8.0
     * @var array
     */
    public $params = array();
    /**
     * Raw config params, as passed by the extending class.
     *
     * @since 2.1.0
     * @var array
     */
    public $params_raw = array();
    /**
     * The ID of the current group.
     *
     * @since 1.8.0
     * @var int
     */
    public $group_id = 0;
    /**
     * The slug of the current extension.
     *
     * @since 1.1.0
     * @var string
     */
    public $slug = '';
    /**
     * The translatable name of the current extension.
     *
     * @since 1.1.0
     * @var string
     */
    public $name = '';
    /**
     * The visibility of the extension tab. 'public' or 'private'.
     *
     * @since 1.1.0
     * @var string
     */
    public $visibility = 'public';
    /**
     * The numeric position of the main nav item.
     *
     * @since 1.1.0
     * @var int
     */
    public $nav_item_position = 81;
    /**
     * Whether to show the nav item.
     *
     * @since 1.1.0
     * @var bool
     */
    public $enable_nav_item = \true;
    /**
     * Whether the current user should see the navigation item.
     *
     * @since 2.1.0
     * @var bool
     */
    public $user_can_see_nav_item;
    /**
     * The Callback function to use before showing the navigation item.
     *
     * @since 12.0.0
     * @var string
     */
    public $show_tab_callback = '';
    /**
     * Whether the current user can visit the tab.
     *
     * @since 2.1.0
     * @var bool
     */
    public $user_can_visit;
    /**
     * The text of the nav item. Defaults to self::name.
     *
     * @since 1.1.0
     * @var string
     */
    public $nav_item_name = '';
    /**
     * The WP action that self::widget_display() is attached to.
     *
     * Default: 'groups_custom_group_boxes'.
     *
     * @since 1.1.0
     * @var string
     */
    public $display_hook = 'groups_custom_group_boxes';
    /**
     * The template file used to load the plugin content.
     *
     * Default: 'groups/single/plugins'.
     *
     * @since 1.1.0
     * @var string
     */
    public $template_file = 'groups/single/plugins';
    /**
     * The template file.
     *
     * @since 1.1.0
     * @var string
     */
    public $edit_screen_template;
    /** Protected *********************************************************/
    /**
     * Has the extension been initialized?
     *
     * @since 1.8.0
     * @var bool
     */
    protected $initialized = \false;
    /**
     * Extension properties as set by legacy extensions.
     *
     * @since 1.8.0
     * @var array
     */
    protected $legacy_properties = array();
    /**
     * Converted legacy parameters.
     *
     * These are the extension properties as set by legacy extensions, but
     * then converted to match the new format for params.
     *
     * @since 1.8.0
     * @var array
     */
    protected $legacy_properties_converted = array();
    /**
     * Redirect location as defined by post-edit save callback.
     *
     * @since 2.1.0
     * @var string
     */
    protected $post_save_redirect;
    /**
     * Miscellaneous data as set by the __set() magic method.
     *
     * @since 1.8.0
     * @var array
     */
    protected $data = array();
    /** Screen Overrides **************************************************/
    /*
     * Screen override methods are how your extension will display content
     * and handle form submits. Your extension should only override those
     * methods that it needs for its purposes.
     */
    /**
     * The content of the group tab.
     *
     * @since 1.1.0
     *
     * @param int|null $group_id ID of the group to display.
     */
    public function display($group_id = \null)
    {
    }
    /**
     * Content displayed in a widget sidebar, if applicable.
     *
     * @since 1.1.0
     */
    public function widget_display()
    {
    }
    /*
     * *_screen() displays the settings form for the given context
     * *_screen_save() processes data submitted via the settings form
     * The settings_* methods are generic fallbacks, which can optionally
     * be overridden by the more specific edit_*, create_*, and admin_*
     * versions.
     */
    /**
     * Provide the fallback markup for Group's Create/Admin/Edit screens.
     *
     * @since 1.8.0
     *
     * @param int|null $group_id ID of the group to display.
     */
    public function settings_screen($group_id = \null)
    {
    }
    /**
     * Group's Fallback handler for the Create/Admin/Edit screens.
     *
     * @since 1.8.0
     *
     * @param int|null $group_id ID of the group to display.
     */
    public function settings_screen_save($group_id = \null)
    {
    }
    /**
     * The content of the Manage sub tab.
     *
     * @since 1.1.0
     *
     * @param int|null $group_id ID of the group to display.
     */
    public function edit_screen($group_id = \null)
    {
    }
    /**
     * Group Manage sub tab handler.
     *
     * @since 1.1.0
     *
     * @param int|null $group_id ID of the group to display.
     */
    public function edit_screen_save($group_id = \null)
    {
    }
    /**
     * The content of the group create step tab.
     *
     * @since 1.1.0
     *
     * @param int|null $group_id ID of the group to display.
     */
    public function create_screen($group_id = \null)
    {
    }
    /**
     * Group create step tab handler.
     *
     * @since 1.1.0
     *
     * @param int|null $group_id ID of the group to display.
     */
    public function create_screen_save($group_id = \null)
    {
    }
    /**
     * The content of Group's WP Administration screen metabox.
     *
     * @since 1.8.0
     *
     * @param int|null $group_id ID of the group to display.
     */
    public function admin_screen($group_id = \null)
    {
    }
    /**
     * Group's WP Administration screen handler.
     *
     * @since 1.8.0
     *
     * @param int|null $group_id ID of the group to display.
     */
    public function admin_screen_save($group_id = \null)
    {
    }
    /** Setup *************************************************************/
    /**
     * Initialize the extension, using your config settings.
     *
     * Your plugin should call this method at the very end of its
     * constructor, like so:
     *
     *   public function __construct() {
     *       $args = array(
     *           'slug' => 'my-group-extension',
     *           'name' => 'My Group Extension',
     *           // ...
     *       );
     *
     *       parent::init( $args );
     *   }
     *
     * @since 1.8.0
     * @since 2.1.0 Added 'access' and 'show_tab' arguments to `$args`.
     * @since 12.0.0 Set the Group Extension screens.
     *
     * @param array $args {
     *     Array of initialization arguments.
     *     @type string       $slug              Unique, URL-safe identifier for your extension.
     *     @type string       $name              Translatable name for your extension. Used to populate
     *                                           navigation items.
     *     @type string       $visibility        Optional. Set to 'public' for your extension (the main tab as well
     *                                           as the widget) to be available to anyone who can access the group;
     *                                           set to 'private' otherwise. Default: 'public'.
     *     @type int          $nav_item_position Optional. Location of the nav item in the tab list.
     *                                           Default: 81.
     *     @type bool         $enable_nav_item   Optional. Whether the extension's tab should be accessible to
     *                                           anyone who can view the group. Default: true.
     *     @type string       $nav_item_name     Optional. The translatable text you want to appear in the nav tab.
     *                                           Default: the value of `$name`.
     *     @type string       $display_hook      Optional. The WordPress action that the widget_display() method is
     *                                           hooked to. Default: 'groups_custom_group_boxes'.
     *     @type string       $template_file     Optional. Theme-relative path to the template file BP should use
     *                                           to load the content of your main extension tab.
     *                                           Default: 'groups/single/plugins.php'.
     *     @type array        $screens           A multi-dimensional array of configuration information for the
     *                                           extension screens. See docblock of {@link BP_Group_Extension}
     *                                           for more details.
     *     @type string|array $access            Which users can visit the plugin's tab. Possible values: 'anyone',
     *                                           'loggedin', 'member', 'mod', 'admin' or 'noone'. ('member', 'mod',
     *                                           'admin' refer to user's role in group.) Note that 'mod' targets
     *                                           only group moderators. If you want to allow access to group moderators
     *                                           and admins, specify `array( 'mod', 'admin' )`. Defaults to 'anyone'
     *                                           for public groups and 'member' for private groups.
     *     @type string|array $show_tab          Which users can see the plugin's navigation tab. Possible values:
     *                                           'anyone', 'loggedin', 'member', 'mod', 'admin' or 'noone'.
     *                                           ('member', 'mod', 'admin' refer to user's role in group.) Note
     *                                           that 'mod' targets only group moderators. If you want to show the
     *                                           tab to group moderators and admins, specify
     *                                           `array( 'mod', 'admin' )`. Defaults to 'anyone' for public groups
     *                                           and 'member' for private groups.
     *    @type string|array  $show_tab_callback The function to execute to set the $show_tab argument.
     * }
     */
    public function init($args = array())
    {
    }
    /**
     * The main setup routine for the extension.
     *
     * This method contains the primary logic for setting up an extension's
     * configuration, setting up backward compatibility for legacy plugins,
     * and hooking the extension's screen functions into WP and BP.
     *
     * Marked 'public' because it must be accessible to add_action().
     * However, you should never need to invoke this method yourself - it
     * is called automatically at the right point in the load order by
     * bp_register_group_extension().
     *
     * @since 1.1.0
     */
    public function _register()
    {
    }
    /**
     * Set up some basic info about the Extension.
     *
     * Here we collect the name of the extending class, as well as a
     * ReflectionClass that is used in get_screen_callback() to determine
     * whether your extension overrides certain callback methods.
     *
     * @since 1.8.0
     */
    protected function setup_class_info()
    {
    }
    /**
     * Get the current group ID.
     *
     * Check for:
     *   - current group
     *   - new group
     *   - group admin
     *
     * @since 1.8.0
     *
     * @return int
     */
    public static function get_group_id()
    {
    }
    /**
     * Gather configuration data about your screens.
     *
     * @since 1.8.0
     *
     * @return array
     */
    protected function get_default_screens()
    {
    }
    /**
     * Set up screens array based on params.
     *
     * @since 1.8.0
     */
    protected function setup_screens()
    {
    }
    /**
     * Set up access-related settings for this extension.
     *
     * @since 2.1.0
     */
    protected function setup_access_settings()
    {
    }
    /**
     * Check whether the current user meets an access condition.
     *
     * @since 2.1.0
     *
     * @param string $access_condition 'anyone', 'loggedin', 'member',
     *                                 'mod', 'admin' or 'noone'.
     * @return bool
     */
    protected function user_meets_access_condition($access_condition)
    {
    }
    /** Display ***********************************************************/
    /**
     * Returns the Rewrite ID of the Group Extension Item according to the context.
     *
     * @since 12.0.0
     *
     * @param string $context One of these contexts: 'create', 'manage', 'read'.
     * @return string         The found Rewrite ID, an empty string otherwise.
     */
    protected function get_rewrite_id_for($context = '')
    {
    }
    /**
     * Hook this extension's group tab into BuddyPress, if necessary.
     *
     * @since 1.8.0
     */
    protected function setup_display_hooks()
    {
    }
    /**
     * Hook the main display method, and loads the template file.
     *
     * @since 1.1.0
     */
    public function _display_hook()
    {
    }
    /**
     * Call the display() method.
     *
     * We use this wrapper so that we can pass the group_id to the
     * display() callback.
     *
     * @since 2.1.1
     */
    public function call_display()
    {
    }
    /**
     * Determine whether the current user should see this nav tab.
     *
     * Note that this controls only the display of the navigation item.
     * Access to the tab is controlled by the user_can_visit() check.
     *
     * @since 2.1.0
     *
     * @param bool $user_can_see_nav_item Whether or not the user can see the nav item.
     * @return bool
     */
    public function user_can_see_nav_item($user_can_see_nav_item = \false)
    {
    }
    /**
     * Determine whether the current user has access to visit this tab.
     *
     * Note that this controls the ability of a user to access a tab.
     * Display of the navigation item is controlled by user_can_see_nav_item().
     *
     * @since 2.1.0
     *
     * @param bool $user_can_visit Whether or not the user can visit the tab.
     * @return bool
     */
    public function user_can_visit($user_can_visit = \false)
    {
    }
    /**
     * Filter the access check in bp_groups_group_access_protection() for this extension.
     *
     * Note that $no_access_args is passed by reference, as there are some
     * circumstances where the bp_core_no_access() arguments need to be
     * modified before the redirect takes place.
     *
     * @since 2.1.0
     *
     * @param bool  $user_can_visit Whether or not the user can visit the tab.
     * @param array $no_access_args Array of args to help determine access.
     * @return bool
     */
    public function group_access_protection($user_can_visit, &$no_access_args)
    {
    }
    /** Create ************************************************************/
    /**
     * Hook this extension's Create step into BuddyPress, if necessary.
     *
     * @since 1.8.0
     */
    protected function setup_create_hooks()
    {
    }
    /**
     * Call the create_screen() method, if we're on the right page.
     *
     * @since 1.8.0
     */
    public function maybe_create_screen()
    {
    }
    /**
     * Call the create_screen_save() method, if we're on the right page.
     *
     * @since 1.8.0
     */
    public function maybe_create_screen_save()
    {
    }
    /** Edit **************************************************************/
    /**
     * Hook this extension's Edit panel into BuddyPress, if necessary.
     *
     * @since 1.8.0
     */
    protected function setup_edit_hooks()
    {
    }
    /**
     * Call the edit_screen() method.
     *
     * Previous versions of BP_Group_Extension required plugins to provide
     * their own Submit button and nonce fields when building markup. In
     * BP 1.8, this requirement was lifted - BP_Group_Extension now handles
     * all required submit buttons and nonces.
     *
     * We put the edit screen markup into an output buffer before echoing.
     * This is so that we can check for the presence of a hardcoded submit
     * button, as would be present in legacy plugins; if one is found, we
     * do not auto-add our own button.
     *
     * @since 1.8.0
     */
    public function call_edit_screen()
    {
    }
    /**
     * Check the nonce, and call the edit_screen_save() method.
     *
     * @since 1.8.0
     */
    public function call_edit_screen_save()
    {
    }
    /**
     * Load the template that houses the Edit screen.
     *
     * Separated out into a callback so that it can run after all other
     * Group Extensions have had a chance to register their navigation, to
     * avoid missing tabs.
     *
     * Hooked to 'bp_screens'.
     *
     * @since 1.8.0
     *
     * @see BP_Group_Extension::setup_edit_hooks()
     */
    public function call_edit_screen_template_loader()
    {
    }
    /**
     * Add a submit button to the edit form, if it needs one.
     *
     * There's an inconsistency in the way that the group Edit and Create
     * screens are rendered: the Create screen has a submit button built
     * in, but the Edit screen does not. This function allows plugin
     * authors to write markup that does not contain the submit button for
     * use on both the Create and Edit screens - BP will provide the button
     * if one is not found.
     *
     * @since 1.8.0
     *
     * @param string $screen The screen markup, captured in the output
     *                       buffer.
     * @return string $screen The same markup, with a submit button added.
     */
    protected function maybe_add_submit_button($screen = '')
    {
    }
    /**
     * Does the given markup have a submit button?
     *
     * @since 1.8.0
     *
     * @param string $screen The markup to check.
     * @return bool True if a Submit button is found, otherwise false.
     */
    public static function has_submit_button($screen = '')
    {
    }
    /**
     * Detect redirects hardcoded into edit_screen_save() callbacks.
     *
     * @since 2.1.0
     *
     * @param string $redirect Redirect string.
     * @return string
     */
    public function detect_post_save_redirect($redirect = '')
    {
    }
    /** Admin *************************************************************/
    /**
     * Hook this extension's Admin metabox into BuddyPress, if necessary.
     *
     * @since 1.8.0
     */
    protected function setup_admin_hooks()
    {
    }
    /**
     * Call the admin_screen() method, and add a nonce field.
     *
     * @since 1.8.0
     */
    public function call_admin_screen()
    {
    }
    /**
     * Check the nonce, and call the admin_screen_save() method.
     *
     * @since 1.8.0
     */
    public function call_admin_screen_save()
    {
    }
    /**
     * Create the Dashboard meta box for this extension.
     *
     * @since 1.7.0
     */
    public function _meta_box_display_callback()
    {
    }
    /** Utilities *********************************************************/
    /**
     * Generate the nonce fields for a settings form.
     *
     * The nonce field name (the second param passed to wp_nonce_field)
     * contains this extension's slug and is thus unique to this extension.
     * This is necessary because in some cases (namely, the Dashboard),
     * more than one extension may generate nonces on the same page, and we
     * must avoid name clashes.
     *
     * @since 1.8.0
     *
     * @param string $context Screen context. 'create', 'edit', or 'admin'.
     */
    public function nonce_field($context = '')
    {
    }
    /**
     * Check the nonce on a submitted settings form.
     *
     * @since 1.8.0
     *
     * @param string $context Screen context. 'create', 'edit', or 'admin'.
     */
    public function check_nonce($context = '')
    {
    }
    /**
     * Is the specified screen enabled?
     *
     * To be enabled, a screen must both have the 'enabled' key set to true
     * (legacy: $this->enable_create_step, etc), and its screen_callback
     * must also exist and be callable.
     *
     * @since 1.8.0
     *
     * @param string $context Screen context. 'create', 'edit', or 'admin'.
     * @return bool True if the screen is enabled, otherwise false.
     */
    public function is_screen_enabled($context = '')
    {
    }
    /**
     * Get the appropriate screen callback for the specified context/type.
     *
     * BP Group Extensions have three special "screen contexts": create,
     * admin, and edit. Each of these contexts has a corresponding
     * _screen() and _screen_save() method, which allow group extension
     * plugins to define different markup and logic for each context.
     *
     * BP also supports fallback settings_screen() and
     * settings_screen_save() methods, which can be used to define markup
     * and logic that is shared between context. For each context, you may
     * either provide context-specific methods, or you can let BP fall back
     * on the shared settings_* callbacks.
     *
     * For example, consider a BP_Group_Extension implementation that looks
     * like this:
     *
     *   // ...
     *   function create_screen( $group_id ) { ... }
     *   function create_screen_save( $group_id ) { ... }
     *   function settings_screen( $group_id ) { ... }
     *   function settings_screen_save( $group_id ) { ... }
     *   // ...
     *
     * BP_Group_Extension will use your create_* methods for the Create
     * steps, and will use your generic settings_* methods for the Edit
     * and Admin contexts. This schema allows plugin authors maximum
     * flexibility without having to repeat themselves.
     *
     * The get_screen_callback() method uses a ReflectionClass object to
     * determine whether your extension has provided a given callback.
     *
     * @since 1.8.0
     *
     * @param string $context Screen context. 'create', 'edit', or 'admin'.
     * @param string $type    Screen type. 'screen' or 'screen_save'. Default:
     *                        'screen'.
     * @return callable A callable function handle.
     */
    public function get_screen_callback($context = '', $type = 'screen')
    {
    }
    /**
     * Recursive argument parsing.
     *
     * This acts like a multi-dimensional version of wp_parse_args() (minus
     * the querystring parsing - you must pass arrays).
     *
     * Values from $a override those from $b; keys in $b that don't exist
     * in $a are passed through.
     *
     * This is different from array_merge_recursive(), both because of the
     * order of preference ($a overrides $b) and because of the fact that
     * array_merge_recursive() combines arrays deep in the tree, rather
     * than overwriting the b array with the a array.
     *
     * The implementation of this function is specific to the needs of
     * BP_Group_Extension, where we know that arrays will always be
     * associative, and that an argument under a given key in one array
     * will be matched by a value of identical depth in the other one. The
     * function is NOT designed for general use, and will probably result
     * in unexpected results when used with data in the wild. See, eg,
     * https://core.trac.wordpress.org/ticket/19888
     *
     * @since 1.8.0
     *
     * @param array $a First set of arguments.
     * @param array $b Second set of arguments.
     * @return array Parsed arguments.
     */
    public static function parse_args_r(&$a, $b)
    {
    }
    /** Legacy Support ********************************************************/
    /*
     * In BuddyPress 1.8, the recommended technique for configuring
     * extensions changed from directly setting various object properties
     * in the class constructor, to passing a configuration array to
     * parent::init(). The following methods ensure that extensions created
     * in the old way continue to work, by converting legacy configuration
     * data to the new format.
     */
    /**
     * Provide access to otherwise unavailable object properties.
     *
     * This magic method is here for backward compatibility with plugins
     * that refer to config properties that have moved to a different
     * location (such as enable_create_step, which is now at
     * $this->screens['create']['enabled']
     *
     * The legacy_properties array is set up in
     * self::setup_legacy_properties().
     *
     * @since 1.8.0
     *
     * @param string $key Property name.
     * @return mixed The value if found, otherwise null.
     */
    public function __get($key)
    {
    }
    /**
     * Provide a fallback for isset( $this->foo ) when foo is unavailable.
     *
     * This magic method is here for backward compatibility with plugins
     * that have set their class config options directly in the class
     * constructor. The parse_legacy_properties() method of the current
     * class needs to check whether any legacy keys have been put into the
     * $this->data array.
     *
     * @since 1.8.0
     *
     * @param string $key Property name.
     * @return bool True if the value is set, otherwise false.
     */
    public function __isset($key)
    {
    }
    /**
     * Allow plugins to set otherwise unavailable object properties.
     *
     * This magic method is here for backward compatibility with plugins
     * that may attempt to modify the group extension by manually assigning
     * a value to an object property that no longer exists, such as
     * $this->enable_create_step.
     *
     * @since 1.8.0
     *
     * @param string $key Property name.
     * @param mixed  $value Property value.
     */
    public function __set($key, $value)
    {
    }
    /**
     * Return a list of legacy properties.
     *
     * The legacy implementation of BP_Group_Extension used all of these
     * object properties for configuration. Some have been moved.
     *
     * @since 1.8.0
     *
     * @return array List of legacy property keys.
     */
    protected function get_legacy_property_list()
    {
    }
    /**
     * Parse legacy properties.
     *
     * The old standard for BP_Group_Extension was for plugins to register
     * their settings as properties in their constructor. The new method is
     * to pass a config array to the init() method. In order to support
     * legacy plugins, we slurp up legacy properties, and later on we'll
     * parse them into the new init() array.
     *
     * @since 1.8.0
     */
    protected function parse_legacy_properties()
    {
    }
    /**
     * Set up legacy properties.
     *
     * This method is responsible for ensuring that all legacy config
     * properties are stored in an array $this->legacy_properties, so that
     * they remain available to plugins that reference the variables at
     * their old locations.
     *
     * @since 1.8.0
     *
     * @see BP_Group_Extension::__get()
     */
    protected function setup_legacy_properties()
    {
    }
}
/**
 * Adds support for user at-mentions (for users in a specific Group) to the Suggestions API.
 *
 * @since 2.1.0
 */
class BP_Groups_Member_Suggestions extends \BP_Members_Suggestions
{
    /**
     * Default arguments for this suggestions service.
     *
     * @since 2.1.0
     * @var array $args {
     *     @type int    $group_id     Positive integers will restrict the search to members in that group.
     *                                Negative integers will restrict the search to members in every other group.
     *     @type int    $limit        Maximum number of results to display. Default: 16.
     *     @type bool   $only_friends If true, only match the current user's friends. Default: false.
     *     @type string $term         The suggestion service will try to find results that contain this string.
     *                                Mandatory.
     * }
     */
    protected $default_args = array('group_id' => 0, 'limit' => 16, 'only_friends' => \false, 'term' => '', 'type' => '');
    /**
     * Validate and sanitise the parameters for the suggestion service query.
     *
     * @since 2.1.0
     *
     * @return true|WP_Error If validation fails, return a WP_Error object. On success, return true (bool).
     */
    public function validate()
    {
    }
    /**
     * Find and return a list of username suggestions that match the query.
     *
     * @since 2.1.0
     *
     * @return array|WP_Error Array of results. If there were problems, returns a WP_Error object.
     */
    public function get_suggestions()
    {
    }
}
/**
 * BuddyPress Group Membership object.
 */
class BP_Groups_Member
{
    /**
     * ID of the membership.
     *
     * @since 1.6.0
     * @var int
     */
    var $id;
    /**
     * ID of the group associated with the membership.
     *
     * @since 1.6.0
     * @var int
     */
    var $group_id;
    /**
     * ID of the user associated with the membership.
     *
     * @since 1.6.0
     * @var int
     */
    var $user_id;
    /**
     * ID of the user whose invitation initiated the membership.
     *
     * @since 1.6.0
     * @var int
     */
    var $inviter_id;
    /**
     * Whether the member is an admin of the group.
     *
     * @since 1.6.0
     * @var int
     */
    var $is_admin;
    /**
     * Whether the member is a mod of the group.
     *
     * @since 1.6.0
     * @var int
     */
    var $is_mod;
    /**
     * Whether the member is banned from the group.
     *
     * @since 1.6.0
     * @var int
     */
    var $is_banned;
    /**
     * Title used to describe the group member's role in the group.
     *
     * Eg, 'Group Admin'.
     *
     * @since 1.6.0
     * @var int
     */
    var $user_title;
    /**
     * Last modified date of the membership.
     *
     * This value is updated when, eg, invitations are accepted.
     *
     * @since 1.6.0
     * @var string
     */
    var $date_modified;
    /**
     * Whether the membership has been confirmed.
     *
     * @since 1.6.0
     * @var int
     */
    var $is_confirmed;
    /**
     * Comments associated with the membership.
     *
     * In BP core, these are limited to the optional message users can
     * include when requesting membership to a private group.
     *
     * @since 1.6.0
     * @var string
     */
    var $comments;
    /**
     * Whether an invitation has been sent for this membership.
     *
     * The purpose of this flag is to mark when an invitation has been
     * "drafted" (the user has been added via the interface at Send
     * Invites), but the Send button has not been pressed, so the
     * invitee has not yet been notified.
     *
     * @since 1.6.0
     * @var int
     */
    var $invite_sent;
    /**
     * WP_User object representing the membership's user.
     *
     * @since 1.6.0
     * @var WP_User
     */
    protected $user;
    /**
     * Constructor method.
     *
     * @since 1.6.0
     *
     * @param int      $user_id  Optional. Along with $group_id, can be used to
     *                           look up a membership.
     * @param int      $group_id Optional. Along with $user_id, can be used to
     *                           look up a membership.
     * @param int|bool $id       Optional. The unique ID of the membership object.
     * @param bool     $populate Whether to populate the properties of the
     *                           located membership. Default: true.
     */
    public function __construct($user_id = 0, $group_id = 0, $id = \false, $populate = \true)
    {
    }
    /**
     * Populate the object's properties.
     *
     * @since 1.6.0
     */
    public function populate()
    {
    }
    /**
     * Magic getter.
     *
     * @since 2.8.0
     *
     * @param string $key Key.
     * @return BP_Core_User|null
     */
    public function __get($key)
    {
    }
    /**
     * Magic issetter.
     *
     * @since 2.8.0
     *
     * @param string $key Key.
     * @return bool
     */
    public function __isset($key)
    {
    }
    /**
     * Get the user object corresponding to this membership.
     *
     * Used for lazyloading the protected `user` property.
     *
     * @since 2.8.0
     *
     * @return BP_Core_User
     */
    protected function get_user_object()
    {
    }
    /**
     * Save the membership data to the database.
     *
     * @since 1.6.0
     *
     * @return bool
     */
    public function save()
    {
    }
    /**
     * Promote a member to a new status.
     *
     * @since 1.6.0
     *
     * @param string $status The new status. 'mod' or 'admin'.
     * @return bool
     */
    public function promote($status = 'mod')
    {
    }
    /**
     * Demote membership to Member status (non-admin, non-mod).
     *
     * @since 1.6.0
     *
     * @return bool
     */
    public function demote()
    {
    }
    /**
     * Ban the user from the group.
     *
     * @since 1.6.0
     *
     * @return bool
     */
    public function ban()
    {
    }
    /**
     * Unban the user from the group.
     *
     * @since 1.6.0
     *
     * @return bool
     */
    public function unban()
    {
    }
    /**
     * Mark a pending invitation as accepted.
     *
     * @since 1.6.0
     */
    public function accept_invite()
    {
    }
    /**
     * Confirm a membership request.
     *
     * @since 1.6.0
     */
    public function accept_request()
    {
    }
    /**
     * Remove the current membership.
     *
     * @since 1.6.0
     *
     * @return bool
     */
    public function remove()
    {
    }
    /** Static Methods ****************************************************/
    /**
     * Refresh the `total_group_count` for a user.
     *
     * @since 1.8.0
     *
     * @param int $user_id ID of the user.
     */
    public static function refresh_total_group_count_for_user($user_id)
    {
    }
    /**
     * Refresh the `total_member_count` for a group.
     *
     * The request skip the current cache so that we always grab the lastest total count.
     *
     * @since 1.8.0
     * @since 10.0.0 Updated to use `BP_Groups_Group::get_total_member_count`
     *
     * @param int $group_id ID of the group.
     */
    public static function refresh_total_member_count_for_group($group_id)
    {
    }
    /**
     * Delete a membership, based on user + group IDs.
     *
     * @since 1.6.0
     *
     * @param int $user_id  ID of the user.
     * @param int $group_id ID of the group.
     * @return bool
     */
    public static function delete($user_id, $group_id)
    {
    }
    /**
     * Get the IDs of the groups of which a specified user is a member.
     *
     * @since 1.6.0
     *
     * @param int      $user_id ID of the user.
     * @param int|bool $limit   Optional. Max number of results to return.
     *                          Default: false (no limit).
     * @param int|bool $page    Optional. Page offset of results to return.
     *                          Default: false (no limit).
     * @return array {
     *     @type array $groups Array of groups returned by paginated query.
     *     @type int   $total  Count of groups matching query.
     * }
     */
    public static function get_group_ids($user_id, $limit = \false, $page = \false)
    {
    }
    /**
     * Get the IDs of the groups of which a specified user is a member, sorted by the date joined.
     *
     * @since 1.6.0
     *
     * @param int         $user_id ID of the user.
     * @param int|bool    $limit   Optional. Max number of results to return.
     *                             Default: false (no limit).
     * @param int|bool    $page    Optional. Page offset of results to return.
     *                             Default: false (no limit).
     * @param string|bool $filter  Optional. Limit results to groups whose name or
     *                             description field matches search terms.
     * @return array {
     *     @type array $groups Array of groups returned by paginated query.
     *     @type int   $total  Count of groups matching query.
     * }
     */
    public static function get_recently_joined($user_id, $limit = \false, $page = \false, $filter = \false)
    {
    }
    /**
     * Get the IDs of the groups of which a specified user is an admin.
     *
     * @since 1.6.0
     *
     * @param int         $user_id ID of the user.
     * @param int|bool    $limit   Optional. Max number of results to return.
     *                             Default: false (no limit).
     * @param int|bool    $page    Optional. Page offset of results to return.
     *                             Default: false (no limit).
     * @param string|bool $filter  Optional. Limit results to groups whose name or
     *                             description field matches search terms.
     * @return array {
     *     @type array $groups Array of groups returned by paginated query.
     *     @type int   $total  Count of groups matching query.
     * }
     */
    public static function get_is_admin_of($user_id, $limit = \false, $page = \false, $filter = \false)
    {
    }
    /**
     * Get the IDs of the groups of which a specified user is a moderator.
     *
     * @since 1.6.0
     *
     * @param int         $user_id ID of the user.
     * @param int|bool    $limit   Optional. Max number of results to return.
     *                             Default: false (no limit).
     * @param int|bool    $page    Optional. Page offset of results to return.
     *                             Default: false (no limit).
     * @param string|bool $filter  Optional. Limit results to groups whose name or
     *                             description field matches search terms.
     * @return array {
     *     @type array $groups Array of groups returned by paginated query.
     *     @type int   $total  Count of groups matching query.
     * }
     */
    public static function get_is_mod_of($user_id, $limit = \false, $page = \false, $filter = \false)
    {
    }
    /**
     * Get the groups of which a specified user is banned from.
     *
     * @since 2.4.0
     *
     * @param int         $user_id ID of the user.
     * @param int|bool    $limit   Optional. Max number of results to return.
     *                             Default: false (no limit).
     * @param int|bool    $page    Optional. Page offset of results to return.
     *                             Default: false (no limit).
     * @param string|bool $filter  Optional. Limit results to groups whose name or
     *                             description field matches search terms.
     * @return array {
     *     @type array $groups Array of groups returned by paginated query.
     *     @type int   $total  Count of groups matching query.
     * }
     */
    public static function get_is_banned_of($user_id, $limit = \false, $page = \false, $filter = \false)
    {
    }
    /**
     * Get the count of groups of which the specified user is a member.
     *
     * @since 1.6.0
     *
     * @param int $user_id Optional. Default: ID of the displayed user.
     * @return int Group count.
     */
    public static function total_group_count($user_id = 0)
    {
    }
    /**
     * Get group objects for groups that a user is currently invited to.
     *
     * @since 1.6.0
     *
     * @param int               $user_id ID of the invitee.
     * @param int|bool          $limit   Optional. Max number of results to return.
     *                                   Default: false (no limit).
     * @param int|bool          $page    Optional. Page offset of results to return.
     *                                   Default: false (no limit).
     * @param string|array|bool $exclude Optional. Array or comma-separated list
     *                                   of group IDs to exclude from results.
     * @return array {
     *     @type array $groups Array of groups returned by paginated query.
     *     @type int   $total  Count of groups matching query.
     * }
     */
    public static function get_invites($user_id, $limit = \false, $page = \false, $exclude = \false)
    {
    }
    /**
     * Gets the total group invite count for a user.
     *
     * @since 2.0.0
     *
     * @param int $user_id The user ID.
     * @return int
     */
    public static function get_invite_count_for_user($user_id = 0)
    {
    }
    /**
     * Gets memberships of a user for purposes of a personal data export.
     *
     * @since 4.0.0
     *
     * @param int $user_id ID of the user.
     * @param array $args {
     *    Array of optional arguments.
     *    @type int    $page     Page of memberships being requested. Default 1.
     *    @type int    $per_page Memberships to return per page. Default 20.
     *    @type string $type     Membership type being requested. Accepts 'membership',
     *                           'pending_request', 'pending_received_invitation',
     *                           'pending_sent_invitation'. Default 'membership'.
     * }
     *
     * @return array
     */
    public static function get_user_memberships($user_id, $args = array())
    {
    }
    /**
     * Check whether a user has an outstanding invitation to a given group.
     *
     * @since 1.6.0
     *
     * @param int    $user_id  ID of the potential invitee.
     * @param int    $group_id ID of the group.
     * @param string $type     If 'sent', results are limited to those invitations
     *                         that have actually been sent (non-draft). Default: 'sent'.
     * @return int|null The ID of the invitation if found; null if not found.
     */
    public static function check_has_invite($user_id, $group_id, $type = 'sent')
    {
    }
    /**
     * Delete an invitation, by specifying user ID and group ID.
     *
     * @since 1.6.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param  int $user_id    ID of the user.
     * @param  int $group_id   ID of the group.
     * @param  int $inviter_id ID of the inviter. Specify if you want to delete
     *                         a specific invite. Leave false if you want to
     *                         delete all invites to this group.
     * @return int Number of records deleted.
     */
    public static function delete_invite($user_id, $group_id, $inviter_id = \false)
    {
    }
    /**
     * Delete an unconfirmed membership request, by user ID and group ID.
     *
     * @since 1.6.0
     *
     * @param int $user_id  ID of the user.
     * @param int $group_id ID of the group.
     * @return int Number of records deleted.
     */
    public static function delete_request($user_id, $group_id)
    {
    }
    /**
     * Check whether a user is an admin of a given group.
     *
     * @since 1.6.0
     *
     * @param int $user_id  ID of the user.
     * @param int $group_id ID of the group.
     * @return mixed
     */
    public static function check_is_admin($user_id, $group_id)
    {
    }
    /**
     * Check whether a user is a mod of a given group.
     *
     * @since 1.6.0
     *
     * @param int $user_id  ID of the user.
     * @param int $group_id ID of the group.
     * @return mixed
     */
    public static function check_is_mod($user_id, $group_id)
    {
    }
    /**
     * Check whether a user is a member of a given group.
     *
     * @since 1.6.0
     *
     * @param int $user_id  ID of the user.
     * @param int $group_id ID of the group.
     * @return mixed
     */
    public static function check_is_member($user_id, $group_id)
    {
    }
    /**
     * Check whether a user is banned from a given group.
     *
     * @since 1.6.0
     *
     * @param int $user_id  ID of the user.
     * @param int $group_id ID of the group.
     * @return int|null int 1 if user is banned; int 0 if user is not banned;
     *                  null if user is not part of the group or if group doesn't exist.
     */
    public static function check_is_banned($user_id, $group_id)
    {
    }
    /**
     * Is the specified user the creator of the group?
     *
     * @since 1.2.6
     *
     * @param int $user_id  ID of the user.
     * @param int $group_id ID of the group.
     * @return int|null int of group ID if user is the creator; null on failure.
     */
    public static function check_is_creator($user_id, $group_id)
    {
    }
    /**
     * Check whether a user has an outstanding membership request for a given group.
     *
     * @since 1.6.0
     *
     * @param int $user_id  ID of the user.
     * @param int $group_id ID of the group.
     * @return int Database ID of the membership if found; int 0 on failure.
     */
    public static function check_for_membership_request($user_id, $group_id)
    {
    }
    /**
     * Get a list of randomly selected IDs of groups that the member belongs to.
     *
     * @since 1.6.0
     *
     * @param int $user_id      ID of the user.
     * @param int $total_groups Max number of group IDs to return. Default: 5.
     * @return array Group IDs.
     */
    public static function get_random_groups($user_id = 0, $total_groups = 5)
    {
    }
    /**
     * Get the IDs of all a given group's members.
     *
     * @since 1.6.0
     *
     * @param int $group_id ID of the group.
     * @return array IDs of all group members.
     */
    public static function get_group_member_ids($group_id)
    {
    }
    /**
     * Get a list of all a given group's admins.
     *
     * @since 1.6.0
     *
     * @param  int   $group_id ID of the group.
     * @return array           Info about group admins (user_id + date_modified).
     */
    public static function get_group_administrator_ids($group_id)
    {
    }
    /**
     * Prime the bp_group_admins cache for one or more groups.
     *
     * @since 2.7.0
     *
     * @param array $group_ids IDs of the groups.
     * @return bool
     */
    public static function prime_group_admins_mods_cache($group_ids)
    {
    }
    /**
     * Get a list of all a given group's moderators.
     *
     * @since 1.6.0
     *
     * @param int $group_id ID of the group.
     * @return array Info about group mods (user_id + date_modified).
     */
    public static function get_group_moderator_ids($group_id)
    {
    }
    /**
     * Get group membership objects by ID (or an array of IDs).
     *
     * @since 2.6.0
     *
     * @param int|string|array $membership_ids Single membership ID or comma-separated/array list of membership IDs.
     * @return array
     */
    public static function get_memberships_by_id($membership_ids)
    {
    }
    /**
     * Get the IDs users with outstanding membership requests to the group.
     *
     * @since 1.6.0
     *
     * @param int $group_id ID of the group.
     * @return array IDs of users with outstanding membership requests.
     */
    public static function get_all_membership_request_user_ids($group_id)
    {
    }
    /**
     * Get members of a group.
     *
     * @deprecated 1.6.0
     *
     * @param int        $group_id            ID of the group being queried for.
     * @param bool|int   $limit               Max amount to return.
     * @param bool|int   $page                Pagination value.
     * @param bool       $exclude_admins_mods Whether or not to exclude admins and moderators.
     * @param bool       $exclude_banned      Whether or not to exclude banned members.
     * @param bool|array $exclude             Array of user IDs to exclude.
     * @return false|array
     */
    public static function get_all_for_group($group_id, $limit = \false, $page = \false, $exclude_admins_mods = \true, $exclude_banned = \true, $exclude = \false)
    {
    }
    /**
     * Get all membership IDs for a user.
     *
     * @since 2.6.0
     *
     * @param int $user_id ID of the user.
     * @return array
     */
    public static function get_membership_ids_for_user($user_id)
    {
    }
    /**
     * Delete all memberships for a given group.
     *
     * @since 1.6.0
     *
     * @param int $group_id ID of the group.
     * @return int Number of records deleted.
     */
    public static function delete_all($group_id)
    {
    }
    /**
     * Delete all group membership information for the specified user.
     *
     * In cases where the user is the sole member of a group, a site administrator is
     * assigned to be the group's administrator. Unhook `groups_remove_data_for_user()`
     * to modify this behavior.
     *
     * @since 1.0.0
     * @since 4.0.0 The method behavior was changed so that single-member groups are not deleted.
     *
     * @param int $user_id ID of the user.
     * @return bool
     */
    public static function delete_all_for_user($user_id)
    {
    }
}
/**
 * The main Groups template loop class.
 *
 * Responsible for loading a group of groups into a loop for display.
 *
 * @since 1.2.0
 */
class BP_Groups_Template
{
    /**
     * The loop iterator.
     *
     * @since 1.2.0
     * @var int
     */
    public $current_group = -1;
    /**
     * The number of groups returned by the paged query.
     *
     * @since 1.2.0
     * @var int
     */
    public $group_count;
    /**
     * Array of groups located by the query.
     *
     * @since 1.2.0
     * @var array
     */
    public $groups;
    /**
     * The group object currently being iterated on.
     *
     * @since 1.2.0
     * @var object
     */
    public $group;
    /**
     * A flag for whether the loop is currently being iterated.
     *
     * @since 1.2.0
     * @var bool
     */
    public $in_the_loop;
    /**
     * The page number being requested.
     *
     * @since 1.2.0
     * @var string
     */
    public $pag_page;
    /**
     * The number of items being requested per page.
     *
     * @since 1.2.0
     * @var string
     */
    public $pag_num;
    /**
     * URL argument used for the pagination param.
     *
     * @since 1.2.0
     * @var string
     */
    public $pag_arg;
    /**
     * An HTML string containing pagination links.
     *
     * @since 1.2.0
     * @var string
     */
    public $pag_links;
    /**
     * The total number of groups matching the query parameters.
     *
     * @since 1.2.0
     * @var int
     */
    public $total_group_count;
    /**
     * Whether the template loop is for a single group page.
     *
     * @since 1.2.0
     * @var bool
     */
    public $single_group = \false;
    /**
     * Field to sort by.
     *
     * @since 1.2.0
     * @var string
     */
    public $sort_by;
    /**
     * Sort order.
     *
     * @since 1.2.0
     * @var string
     */
    public $order;
    /**
     * Constructor method.
     *
     * @see BP_Groups_Group::get() for an in-depth description of arguments.
     *
     * @param array $args {
     *     Array of arguments. Accepts all arguments accepted by
     *     {@link BP_Groups_Group::get()}. In cases where the default
     *     values of the params differ, they have been discussed below.
     *     @type int $per_page Default: 20.
     *     @type int $page Default: 1.
     * }
     */
    function __construct(...$args)
    {
    }
    /**
     * Whether there are groups available in the loop.
     *
     * @since 1.2.0
     *
     * @see bp_has_groups()
     *
     * @return bool True if there are items in the loop, otherwise false.
     */
    function has_groups()
    {
    }
    /**
     * Set up the next group and iterate index.
     *
     * @since 1.2.0
     *
     * @return object The next group to iterate over.
     */
    function next_group()
    {
    }
    /**
     * Rewind the groups and reset member index.
     *
     * @since 1.2.0
     */
    function rewind_groups()
    {
    }
    /**
     * Whether there are groups left in the loop to iterate over.
     *
     * This method is used by {@link bp_groups()} as part of the while loop
     * that controls iteration inside the groups loop, eg:
     *     while ( bp_groups() ) { ...
     *
     * @since 1.2.0
     *
     * @see bp_groups()
     *
     * @return bool True if there are more groups to show, otherwise false.
     */
    function groups()
    {
    }
    /**
     * Set up the current group inside the loop.
     *
     * Used by {@link bp_the_group()} to set up the current group data
     * while looping, so that template tags used during that iteration make
     * reference to the current member.
     *
     * @since 1.2.0
     *
     * @see bp_the_group()
     */
    function the_group()
    {
    }
}
/**
 * Creates our Groups component.
 *
 * @since 1.5.0
 */
#[\AllowDynamicProperties]
class BP_Groups_Component extends \BP_Component
{
    /**
     * Auto-join group when non group member performs group activity.
     *
     * @since 1.5.0
     * @var bool
     */
    public $auto_join;
    /**
     * The group being currently accessed.
     *
     * @since 1.5.0
     * @var BP_Groups_Group
     */
    public $current_group;
    /**
     * Default group extension.
     *
     * @since 1.6.0
     * @var string
     */
    public $default_extension;
    /**
     * Illegal group names/slugs.
     *
     * @since 1.5.0
     * @var array
     */
    public $forbidden_names;
    /**
     * Group creation/edit steps (e.g. Details, Settings, Avatar, Invites).
     *
     * @since 1.5.0
     * @var array
     */
    public $group_creation_steps;
    /**
     * Types of group statuses (Public, Private, Hidden).
     *
     * @since 1.5.0
     * @var array
     */
    public $valid_status;
    /**
     * Group types.
     *
     * @see bp_groups_register_group_type()
     *
     * @since 2.6.0
     * @var array
     */
    public $types = array();
    /**
     * Nav for the Group component.
     *
     * @since 2.6.0
     * @var BP_Core_Nav
     */
    public $nav;
    /**
     * Current directory group type.
     *
     * @see groups_directory_groups_setup()
     *
     * @since 2.7.0
     * @var string
     */
    public $current_directory_type = '';
    /**
     * List of registered Group extensions.
     *
     * @see bp_register_group_extension()
     *
     * @since 10.0.0
     * @var array
     */
    public $group_extensions = array();
    /**
     * Start the groups component creation process.
     *
     * @since 1.5.0
     */
    public function __construct()
    {
    }
    /**
     * Include Groups component files.
     *
     * @since 1.5.0
     *
     * @see BP_Component::includes() for a description of arguments.
     *
     * @param array $includes See BP_Component::includes() for a description.
     */
    public function includes($includes = array())
    {
    }
    /**
     * Late includes method.
     *
     * Only load up certain code when on specific pages.
     *
     * @since 3.0.0
     */
    public function late_includes()
    {
    }
    /**
     * Sets up the current (displayed) group it it exists.
     *
     * @since 12.0.0
     *
     * @param string $group_slug The current action which is possibly a group slug.
     * @return BP_Groups_Group|Object|integer A group's object or 0 if no groups were found.
     */
    public function setup_current_group($group_slug = '')
    {
    }
    /**
     * Set up the component actions.
     *
     * @since 12.0.0
     */
    public function setup_actions()
    {
    }
    /**
     * Set up additional globals for the component.
     *
     * @since 10.0.0
     */
    public function setup_additional_globals()
    {
    }
    /**
     * Set up component global data.
     *
     * The BP_GROUPS_SLUG constant is deprecated.
     *
     * @since 1.5.0
     *
     * @see BP_Component::setup_globals() for a description of arguments.
     *
     * @param array $args See BP_Component::setup_globals() for a description.
     */
    public function setup_globals($args = array())
    {
    }
    /**
     * Set up canonical stack for this component.
     *
     * @since 2.1.0
     */
    public function setup_canonical_stack()
    {
    }
    /**
     * Register component navigation.
     *
     * @since 12.0.0
     *
     * @see `BP_Component::register_nav()` for a description of arguments.
     *
     * @param array $main_nav Optional. See `BP_Component::register_nav()` for description.
     * @param array $sub_nav  Optional. See `BP_Component::register_nav()` for description.
     */
    public function register_nav($main_nav = array(), $sub_nav = array())
    {
    }
    /**
     * Set up component navigation.
     *
     * @since 1.5.0
     * @since 12.0.0 Used to customize the main navigation name and set
     *               a Groups single item navigation.
     *
     * @see `BP_Component::setup_nav()` for a description of arguments.
     *
     * @param array $main_nav Optional. See `BP_Component::setup_nav()` for
     *                        description.
     * @param array $sub_nav  Optional. See `BP_Component::setup_nav()` for
     *                        description.
     */
    public function setup_nav($main_nav = array(), $sub_nav = array())
    {
    }
    /**
     * Set up the component entries in the WordPress Admin Bar.
     *
     * @since 1.5.0
     *
     * @see BP_Component::setup_nav() for a description of the $wp_admin_nav
     *      parameter array.
     *
     * @param array $wp_admin_nav See BP_Component::setup_admin_bar() for a description.
     */
    public function setup_admin_bar($wp_admin_nav = array())
    {
    }
    /**
     * Set up the title for pages and <title>.
     *
     * @since 1.5.0
     */
    public function setup_title()
    {
    }
    /**
     * Setup cache groups
     *
     * @since 2.2.0
     */
    public function setup_cache_groups()
    {
    }
    /**
     * Set up taxonomies.
     *
     * @since 2.6.0
     * @since 7.0.0 The Group Type taxonomy is registered using the `bp_groups_register_group_type_taxonomy()` function.
     */
    public function register_taxonomies()
    {
    }
    /**
     * Adds the Groups directory type & Group create rewrite tags.
     *
     * @since 12.0.0
     *
     * @param array $rewrite_tags Optional. See BP_Component::add_rewrite_tags() for
     *                            description.
     */
    public function add_rewrite_tags($rewrite_tags = array())
    {
    }
    /**
     * Adds the Groups directory type & Group create rewrite rules.
     *
     * @since 12.0.0
     *
     * @param array $rewrite_rules Optional. See BP_Component::add_rewrite_rules() for
     *                             description.
     */
    public function add_rewrite_rules($rewrite_rules = array())
    {
    }
    /**
     * Parse the WP_Query and eventually display the component's directory or single item.
     *
     * @since 12.0.0
     *
     * @param WP_Query $query Required. See BP_Component::parse_query() for
     *                        description.
     */
    public function parse_query($query)
    {
    }
    /**
     * Check the parsed query is consistent with Group’s registered screens.
     *
     * @since 12.0.0
     */
    public function check_parsed_query()
    {
    }
    /**
     * Init the BP REST API.
     *
     * @since 5.0.0
     * @since 6.0.0 Adds the Group Cover REST endpoint.
     *
     * @param array $controllers Optional. See BP_Component::rest_api_init() for
     *                           description.
     */
    public function rest_api_init($controllers = array())
    {
    }
    /**
     * Register the BP Groups Blocks.
     *
     * @since 6.0.0
     * @since 12.0.0 Use the WP Blocks API v2.
     *
     * @param array $blocks Optional. See BP_Component::blocks_init() for
     *                      description.
     */
    public function blocks_init($blocks = array())
    {
    }
}
/**
 * Membership request template loop class.
 *
 * @since 1.0.0
 */
class BP_Groups_Membership_Requests_Template
{
    /**
     * @since 1.0.0
     * @var int
     */
    public $current_request = -1;
    /**
     * @since 1.0.0
     * @var int
     */
    public $request_count;
    /**
     * @since 1.0.0
     * @var array
     */
    public $requests;
    /**
     * @since 1.0.0
     * @var object
     */
    public $request;
    /**
     * @sine 1.0.0
     * @var bool
     */
    public $in_the_loop;
    /**
     * @since 1.0.0
     * @var int
     */
    public $pag_page;
    /**
     * @since 1.0.0
     * @var int
     */
    public $pag_num;
    /**
     * @since 1.0.0
     * @var array|string|null
     */
    public $pag_links;
    /**
     * URL argument used for the pagination param.
     *
     * @since 1.0.0
     * @var string
     */
    public $pag_arg;
    /**
     * @since 1.0.0
     * @var int
     */
    public $total_request_count;
    /**
     * Constructor method.
     *
     * @since 1.5.0
     *
     * @param array $args {
     *     @type int $group_id ID of the group whose membership requests
     *                         are being queried. Default: current group id.
     *     @type int $per_page Number of records to return per page of
     *                         results. Default: 10.
     *     @type int $page     Page of results to show. Default: 1.
     *     @type int $max      Max items to return. Default: false (show all)
     * }
     */
    public function __construct($args = array())
    {
    }
    /**
     * Whether or not there are requests to show.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function has_requests()
    {
    }
    /**
     * Moves up to the next request.
     *
     * @since 1.0.0
     *
     * @return object
     */
    public function next_request()
    {
    }
    /**
     * Rewinds the requests to the first in the list.
     *
     * @since 1.0.0
     */
    public function rewind_requests()
    {
    }
    /**
     * Finishes up the requests to display.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function requests()
    {
    }
    /**
     * Sets up the request to display.
     *
     * @since 1.0.0
     */
    public function the_request()
    {
    }
}
/**
 * Group Cover endpoints.
 *
 * /groups/<group_id>/cover
 *
 * @since 6.0.0
 */
class BP_REST_Attachments_Group_Cover_Endpoint extends \WP_REST_Controller
{
    use \BP_REST_Attachments;
    /**
     * BP_Attachment_Cover_Image Instance.
     *
     * @since 6.0.0
     *
     * @var BP_Attachment_Cover_Image
     */
    protected $attachment_instance;
    /**
     * Reuse some parts of the BP_REST_Groups_Endpoint class.
     *
     * @since 6.0.0
     *
     * @var BP_REST_Groups_Endpoint
     */
    protected $groups_endpoint;
    /**
     * Hold the group object.
     *
     * @since 6.0.0
     *
     * @var BP_Groups_Group
     */
    protected $group;
    /**
     * Group object type.
     *
     * @since 6.0.0
     *
     * @var string
     */
    protected $object = 'group';
    /**
     * Constructor.
     *
     * @since 6.0.0
     */
    public function __construct()
    {
    }
    /**
     * Register the component routes.
     *
     * @since 6.0.0
     */
    public function register_routes()
    {
    }
    /**
     * Fetch an existing group cover.
     *
     * @since 6.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_item($request)
    {
    }
    /**
     * Checks if a given request has access to get a group cover.
     *
     * @since 6.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return true|WP_Error
     */
    public function get_item_permissions_check($request)
    {
    }
    /**
     * Upload a group cover.
     *
     * @since 6.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function create_item($request)
    {
    }
    /**
     * Checks if a given request has access to upload a group cover.
     *
     * @since 6.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return true|WP_Error
     */
    public function create_item_permissions_check($request)
    {
    }
    /**
     * Delete an existing group cover.
     *
     * @since 6.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function delete_item($request)
    {
    }
    /**
     * Checks if a given request has access to delete a group cover.
     *
     * @since 6.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return true|WP_Error
     */
    public function delete_item_permissions_check($request)
    {
    }
    /**
     * Prepares group cover to return as an object.
     *
     * @since 6.0.0
     *
     * @param string          $cover_url Group cover url.
     * @param WP_REST_Request $request   Full details about the request.
     * @return WP_REST_Response
     */
    public function prepare_item_for_response($cover_url, $request)
    {
    }
    /**
     * Get the plugin schema, conforming to JSON Schema.
     *
     * @since 6.0.0
     *
     * @return array
     */
    public function get_item_schema()
    {
    }
}
/**
 * Group invitations class.
 *
 * An extension of the core Invitations class that adapts the
 * core logic to accommodate group invitation behavior.
 *
 * @since 5.0.0
 */
class BP_Groups_Invitation_Manager extends \BP_Invitation_Manager
{
    /**
     * Construct parameters.
     *
     * @since 5.0.0
     *
     * @param array|string $args.
     */
    public function __construct($args = '')
    {
    }
    /**
     * This is where custom actions are added to run when notifications of an
     * invitation or request need to be generated & sent.
     *
     * @since 5.0.0
     *
     * @param BP_Invitation $invitation The invitation to send.
     * @return bool
     */
    public function run_send_action(\BP_Invitation $invitation)
    {
    }
    /**
     * This is where custom actions are added to run when an invitation
     * or request is accepted.
     *
     * @since 5.0.0
     *
     * @param string $type Are we accepting an invitation or request?
     * @param array  $r    Parameters that describe the invitation being accepted.
     * @return bool
     */
    public function run_acceptance_action($type, $r)
    {
    }
    /**
     * With group invitations, we don't need to keep the old record, so we delete rather than
     * mark invitations as "accepted."
     *
     * @since 5.0.0
     *
     * @see BP_Invitation::mark_accepted_by_data()
     *      for a description of arguments.
     *
     * @param array $args.
     */
    public function mark_accepted($args)
    {
    }
    /**
     * Should this invitation be created?
     *
     * @since 5.0.0
     *
     * @param array $args Array of arguments.
     * @return bool
     */
    public function allow_invitation($args)
    {
    }
    /**
     * Should this request be created?
     *
     * @since 5.0.0
     *
     * @param array $args.
     * @return bool.
     */
    public function allow_request($args)
    {
    }
}
/**
 * Groups endpoints.
 *
 * @since 5.0.0
 */
class BP_REST_Groups_Endpoint extends \WP_REST_Controller
{
    /**
     * Constructor.
     *
     * @since 5.0.0
     */
    public function __construct()
    {
    }
    /**
     * Register the component routes.
     *
     * @since 5.0.0
     */
    public function register_routes()
    {
    }
    /**
     * Retrieve groups.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Request List of groups object data.
     */
    public function get_items($request)
    {
    }
    /**
     * Check if a given request has access to group items.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return true|WP_Error
     */
    public function get_items_permissions_check($request)
    {
    }
    /**
     * Retrieve a group.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response
     */
    public function get_item($request)
    {
    }
    /**
     * Check if a given request has access to get information about a specific group.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return true|WP_Error
     */
    public function get_item_permissions_check($request)
    {
    }
    /**
     * Create a group.
     *
     * @since 5.0.0
     *
     * @param  WP_REST_Request $request Full data about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function create_item($request)
    {
    }
    /**
     * Checks if a given request has access to create a group.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return true|WP_Error
     */
    public function create_item_permissions_check($request)
    {
    }
    /**
     * Update a group.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function update_item($request)
    {
    }
    /**
     * Check if a given request has access to update a group.
     *
     * @since 5.0.0
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return true|WP_Error
     */
    public function update_item_permissions_check($request)
    {
    }
    /**
     * Delete a group.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function delete_item($request)
    {
    }
    /**
     * Check if a given request has access to delete a group.
     *
     * @since 5.0.0
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return true|WP_Error
     */
    public function delete_item_permissions_check($request)
    {
    }
    /**
     * Retrieves the current user groups.
     *
     * @since 7.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function get_current_user_groups($request)
    {
    }
    /**
     * Check if a given request has access to fetch the user's groups.
     *
     * @since 7.0.0
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return true|WP_Error
     */
    public function get_current_user_groups_permissions_check($request)
    {
    }
    /**
     * Prepares group data for return as an object.
     *
     * @since 5.0.0
     *
     * @param BP_Groups_Group $item     The group object.
     * @param WP_REST_Request $request  Full details about the request.
     * @return WP_REST_Response
     */
    public function prepare_item_for_response($item, $request)
    {
    }
    /**
     * Prepare a group for create or update.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return stdClass|WP_Error
     */
    protected function prepare_item_for_database($request)
    {
    }
    /**
     * Prepare links for the request.
     *
     * @since 5.0.0
     *
     * @param BP_Groups_Group $group Group object.
     * @return array
     */
    protected function prepare_links($group)
    {
    }
    /**
     * See if user can delete or update a group.
     *
     * @since 5.0.0
     *
     * @param  BP_Groups_Group $group Group item.
     * @return bool
     */
    protected function can_user_delete_or_update($group)
    {
    }
    /**
     * Can a user see a group?
     *
     * @since 5.0.0
     *
     * @param  BP_Groups_Group $group Group object.
     * @return bool
     */
    public function can_see($group)
    {
    }
    /**
     * Can this user see hidden groups?
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return bool
     */
    protected function can_see_hidden_groups($request)
    {
    }
    /**
     * Get group object.
     *
     * @since 5.0.0
     *
     * @param  int|WP_REST_Request $request Full details about the request or an group integer.
     * @return false|BP_Groups_Group
     */
    public function get_group_object($request)
    {
    }
    /**
     * Edit some arguments for the endpoint's CREATABLE and EDITABLE methods.
     *
     * @since 5.0.0
     *
     * @param string $method Optional. HTTP method of the request.
     * @return array Endpoint arguments.
     */
    public function get_endpoint_args_for_item_schema($method = \WP_REST_Server::CREATABLE)
    {
    }
    /**
     * Get the group schema, conforming to JSON Schema.
     *
     * @since 5.0.0
     *
     * @return array
     */
    public function get_item_schema()
    {
    }
    /**
     * Get the query params for collections of groups.
     *
     * @since 5.0.0
     *
     * @return array
     */
    public function get_collection_params()
    {
    }
}
/**
 * Group Invites endpoints.
 *
 * Use /groups/{group_id}/invites
 * Use /groups/{group_id}/invites/{invite_id}
 *
 * @since 5.0.0
 */
class BP_REST_Group_Invites_Endpoint extends \WP_REST_Controller
{
    /**
     * Reuse some parts of the BP_REST_Groups_Endpoint class.
     *
     * @since 5.0.0
     *
     * @var BP_REST_Groups_Endpoint
     */
    protected $groups_endpoint;
    /**
     * Constructor.
     *
     * @since 5.0.0
     */
    public function __construct()
    {
    }
    /**
     * Register the component routes.
     *
     * @since 5.0.0
     */
    public function register_routes()
    {
    }
    /**
     * Retrieve group invitations.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response
     */
    public function get_items($request)
    {
    }
    /**
     * Check if a given request has access to group invitations.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return bool|WP_Error
     */
    public function get_items_permissions_check($request)
    {
    }
    /**
     * Fetch a specific group invitation by ID.
     *
     * @since 5.0.0
     *
     * @param  WP_REST_Request $request Full data about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_item($request)
    {
    }
    /**
     * Check if a given request has access to fetch group invitation.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return true|WP_Error
     */
    public function get_item_permissions_check($request)
    {
    }
    /**
     * Invite a member to a group.
     *
     * @since 5.0.0
     *
     * @param  WP_REST_Request $request Full data about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function create_item($request)
    {
    }
    /**
     * Checks if a given request has access to invite a member to a group.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return true|WP_Error
     */
    public function create_item_permissions_check($request)
    {
    }
    /**
     * Accept a group invitation.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function update_item($request)
    {
    }
    /**
     * Check if a given request has access to accept a group invitation.
     *
     * @since 5.0.0
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return true|WP_Error
     */
    public function update_item_permissions_check($request)
    {
    }
    /**
     * Remove (reject/delete) a group invitation.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function delete_item($request)
    {
    }
    /**
     * Check if a given request has access to delete a group invitation.
     *
     * @since 5.0.0
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return bool|WP_Error
     */
    public function delete_item_permissions_check($request)
    {
    }
    /**
     * Prepares group invitation data to return as an object.
     *
     * @since 5.0.0
     *
     * @param BP_Invitation   $invite  The invitation object.
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response
     */
    public function prepare_item_for_response($invite, $request)
    {
    }
    /**
     * Prepare links for the request.
     *
     * @since 5.0.0
     *
     * @param BP_Invitation $invite Invite object.
     * @return array
     */
    protected function prepare_links($invite)
    {
    }
    /**
     * Check access.
     *
     * @param int $group_id Group ID.
     * @return bool
     */
    protected function can_see($group_id)
    {
    }
    /**
     * Helper function to fetch a single group invite.
     *
     * @since 5.0.0
     *
     * @param int $invite_id The ID of the invitation you wish to fetch.
     * @return BP_Invitation|bool $invite Invitation if found, false otherwise.
     */
    public function fetch_single_invite($invite_id = 0)
    {
    }
    /**
     * Edit the type of the some properties for the CREATABLE & EDITABLE methods.
     *
     * @since 5.0.0
     *
     * @param string $method Optional. HTTP method of the request.
     * @return array Endpoint arguments.
     */
    public function get_endpoint_args_for_item_schema($method = \WP_REST_Server::CREATABLE)
    {
    }
    /**
     * Get the group invite schema, conforming to JSON Schema.
     *
     * @since 5.0.0
     *
     * @return array
     */
    public function get_item_schema()
    {
    }
    /**
     * Get the query params for collections of group invites.
     *
     * @since 5.0.0
     *
     * @return array
     */
    public function get_collection_params()
    {
    }
}
/**
 * BuddyPress Group object.
 *
 * @since 1.6.0
 */
#[\AllowDynamicProperties]
class BP_Groups_Group
{
    /**
     * ID of the group.
     *
     * @since 1.6.0
     * @var int
     */
    public $id;
    /**
     * User ID of the group's creator.
     *
     * @since 1.6.0
     * @var int
     */
    public $creator_id;
    /**
     * Name of the group.
     *
     * @since 1.6.0
     * @var string
     */
    public $name;
    /**
     * Group slug.
     *
     * @since 1.6.0
     * @var string
     */
    public $slug;
    /**
     * Group description.
     *
     * @since 1.6.0
     * @var string
     */
    public $description;
    /**
     * Group status.
     *
     * Core statuses are 'public', 'private', and 'hidden'.
     *
     * @since 1.6.0
     * @var string
     */
    public $status;
    /**
     * Parent ID.
     *
     * ID of parent group, if applicable.
     *
     * @since 2.7.0
     * @var int
     */
    public $parent_id;
    /**
     * Controls whether the group has a forum enabled.
     *
     * @since 1.6.0
     * @since 3.0.0 Previously, this referred to Legacy Forums. It's still used by bbPress 2 for integration.
     *
     * @var int
     */
    public $enable_forum;
    /**
     * Date the group was created.
     *
     * @since 1.6.0
     * @var string
     */
    public $date_created;
    /**
     * Data about the group's admins.
     *
     * @since 1.6.0
     * @var array
     */
    protected $admins;
    /**
     * Data about the group's moderators.
     *
     * @since 1.6.0
     * @var array
     */
    protected $mods;
    /**
     * Total count of group members.
     *
     * @since 1.6.0
     * @var int
     */
    protected $total_member_count;
    /**
     * Is the current user a member of this group?
     *
     * @since 1.2.0
     * @var bool
     */
    protected $is_member;
    /**
     * Is the current user a member of this group?
     * Alias of $is_member for backward compatibility.
     *
     * @since 2.9.0
     * @var bool
     */
    protected $is_user_member;
    /**
     * Does the current user have an outstanding invitation to this group?
     *
     * @since 1.9.0
     * @var bool
     */
    protected $is_invited;
    /**
     * Does the current user have a pending membership request to this group?
     *
     * @since 1.9.0
     * @var bool
     */
    protected $is_pending;
    /**
     * Timestamp of the last activity that happened in this group.
     *
     * @since 1.2.0
     * @var string
     */
    protected $last_activity;
    /**
     * If this is a private or hidden group, does the current user have access?
     *
     * @since 1.6.0
     * @var bool
     */
    protected $user_has_access;
    /**
     * Can the current user know that this group exists?
     *
     * @since 2.9.0
     * @var bool
     */
    protected $is_visible;
    /**
     * Raw arguments passed to the constructor.
     *
     * Not currently used by BuddyPress.
     *
     * @since 2.0.0
     * @var array
     */
    public $args;
    /**
     * Constructor method.
     *
     * @since 1.6.0
     *
     * @param int|null $id   Optional. If the ID of an existing group is provided,
     *                       the object will be pre-populated with info about that group.
     * @param array    $args {
     *     Array of optional arguments.
     *     @type bool $populate_extras Deprecated.
     * }
     */
    public function __construct($id = \null, $args = array())
    {
    }
    /**
     * Set up data about the current group.
     *
     * @since 1.6.0
     */
    public function populate()
    {
    }
    /**
     * Save the current group to the database.
     *
     * @since 1.6.0
     *
     * @return bool
     */
    public function save()
    {
    }
    /**
     * Delete the current group.
     *
     * @since 1.6.0
     *
     * @return bool
     */
    public function delete()
    {
    }
    /**
     * Magic getter.
     *
     * @since 2.7.0
     *
     * @param string $key Property name.
     * @return mixed
     */
    public function __get($key)
    {
    }
    /**
     * Magic issetter.
     *
     * Used to maintain backward compatibility for properties that are now
     * accessible only via magic method.
     *
     * @since 2.7.0
     *
     * @param string $key Property name.
     * @return bool
     */
    public function __isset($key)
    {
    }
    /**
     * Magic setter.
     *
     * Used to maintain backward compatibility for properties that are now
     * accessible only via magic method.
     *
     * @since 2.7.0
     *
     * @param string $key   Property name.
     * @param mixed  $value Property value.
     * @return bool
     */
    public function __set($key, $value)
    {
    }
    /**
     * Get a list of the group's admins.
     *
     * Used to provide cache-friendly access to the 'admins' property of
     * the group object.
     *
     * @since 2.7.0
     *
     * @return array|null
     */
    protected function get_admins()
    {
    }
    /**
     * Get a list of the group's mods.
     *
     * Used to provide cache-friendly access to the 'mods' property of
     * the group object.
     *
     * @since 2.7.0
     *
     * @return array|null
     */
    protected function get_mods()
    {
    }
    /**
     * Set up admins and mods for the current group object.
     *
     * Called only when the 'admins' or 'mods' property is accessed.
     *
     * @since 2.7.0
     */
    protected function set_up_admins_and_mods()
    {
    }
    /**
     * Checks whether the logged-in user is a member of the group.
     *
     * @since 2.7.0
     *
     * @return bool|int
     */
    protected function get_is_member()
    {
    }
    /**
     * Checks whether the logged-in user has access to the group.
     *
     * @since 2.7.0
     *
     * @return bool
     */
    protected function get_user_has_access()
    {
    }
    /**
     * Checks whether the current user can know the group exists.
     *
     * @since 2.9.0
     *
     * @return bool
     */
    protected function is_visible()
    {
    }
    /** Static Methods ****************************************************/
    /**
     * Get whether a group exists for a given slug.
     *
     * @since 1.6.0
     * @since 10.0.0 Updated to add the deprecated notice.
     *
     * @param string      $slug       Slug to check.
     * @param string|bool $table_name Deprecated.
     * @return int|null|bool False if empty slug, group ID if found; `null` if not.
     */
    public static function group_exists($slug, $table_name = \false)
    {
    }
    /**
     * Get the ID of a group by the group's slug.
     *
     * Alias of {@link BP_Groups_Group::group_exists()}.
     *
     * @since 1.6.0
     *
     * @param string $slug See {@link BP_Groups_Group::group_exists()}.
     * @return int|null|bool See {@link BP_Groups_Group::group_exists()}.
     */
    public static function get_id_from_slug($slug)
    {
    }
    /**
     * Get whether a group exists for an old slug.
     *
     * @since 2.9.0
     *
     * @param  string         $slug Slug to check.
     * @return int|null|false       Group ID if found; null if not; false if missing parameters.
     */
    public static function get_id_by_previous_slug($slug)
    {
    }
    /**
     * Get IDs of users with outstanding invites to a given group from a specified user.
     *
     * @since 1.6.0
     * @since 2.9.0 Added $sent as a parameter.
     *
     * @param  int      $user_id  ID of the inviting user.
     * @param  int      $group_id ID of the group.
     * @param  int|null $sent     Query for a specific invite sent status. If 0, this will query for users
     *                            that haven't had an invite sent to them yet. If 1, this will query for
     *                            users that have had an invite sent to them. If null, no invite status will
     *                            queried. Default: null.
     * @return array    IDs of users who have been invited to the group by the user but have not
     *                  yet accepted.
     */
    public static function get_invites($user_id, $group_id, $sent = \null)
    {
    }
    /**
     * Get a list of a user's groups, filtered by a search string.
     *
     * @since 1.6.0
     *
     * @param string   $filter  Search term. Matches against 'name' and
     *                          'description' fields.
     * @param int      $user_id ID of the user whose groups are being searched.
     *                          Default: the displayed user.
     * @param mixed    $order   Not used.
     * @param int|null $limit   Optional. The max number of results to return.
     *                          Default: null (no limit).
     * @param int|null $page    Optional. The page offset of results to return.
     *                          Default: null (no limit).
     * @return false|array {
     *     @type array $groups Array of matched and paginated group IDs.
     *     @type int   $total  Total count of groups matching the query.
     * }
     */
    public static function filter_user_groups($filter, $user_id = 0, $order = \false, $limit = \null, $page = \null)
    {
    }
    /**
     * Get a list of groups, filtered by a search string.
     *
     * @since 1.6.0
     *
     * @param string      $filter  Search term. Matches against 'name' and
     *                             'description' fields.
     * @param int|null    $limit   Optional. The max number of results to return.
     *                             Default: null (no limit).
     * @param int|null    $page    Optional. The page offset of results to return.
     *                             Default: null (no limit).
     * @param string|bool $sort_by Column to sort by. Default: false (default
     *        sort).
     * @param string|bool $order   ASC or DESC. Default: false (default sort).
     * @return array {
     *     @type array $groups Array of matched and paginated group IDs.
     *     @type int   $total  Total count of groups matching the query.
     * }
     */
    public static function search_groups($filter, $limit = \null, $page = \null, $sort_by = \false, $order = \false)
    {
    }
    /**
     * Check for the existence of a slug.
     *
     * @since 1.6.0
     *
     * @param string $slug Slug to check.
     * @return string|null The slug, if found. Otherwise null.
     */
    public static function check_slug($slug)
    {
    }
    /**
     * Get the slug for a given group ID.
     *
     * @since 1.6.0
     *
     * @param int $group_id ID of the group.
     * @return string|null The slug, if found. Otherwise null.
     */
    public static function get_slug($group_id)
    {
    }
    /**
     * Check whether a given group has any members.
     *
     * @since 1.6.0
     *
     * @param int $group_id ID of the group.
     * @return bool True if the group has members, otherwise false.
     */
    public static function has_members($group_id)
    {
    }
    /**
     * Check whether a group has outstanding membership requests.
     *
     * @since 1.6.0
     *
     * @param int $group_id ID of the group.
     * @return int|null The number of outstanding requests, or null if
     *                  none are found.
     */
    public static function has_membership_requests($group_id)
    {
    }
    /**
     * Get outstanding membership requests for a group.
     *
     * @since 1.6.0
     *
     * @param int      $group_id ID of the group.
     * @param int|null $limit    Optional. Max number of results to return.
     *                           Default: null (no limit).
     * @param int|null $page     Optional. Page offset of results returned. Default:
     *                           null (no limit).
     * @return array {
     *     @type array $requests The requested page of located requests.
     *     @type int   $total    Total number of requests outstanding for the
     *                           group.
     * }
     */
    public static function get_membership_requests($group_id, $limit = \null, $page = \null)
    {
    }
    /**
     * Query for groups.
     *
     * @see WP_Meta_Query::queries for a description of the 'meta_query'
     *      parameter format.
     *
     * @since 1.6.0
     * @since 2.6.0 Added `$group_type`, `$group_type__in`, and `$group_type__not_in` parameters.
     * @since 2.7.0 Added `$update_admin_cache` and `$parent_id` parameters.
     * @since 2.8.0 Changed `$search_terms` parameter handling and added `$search_columns` parameter.
     * @since 2.9.0 Added `$slug` parameter.
     * @since 10.0.0 Added `$date_query` parameter.
     *
     * @param array $args {
     *     Array of parameters. All items are optional.
     *     @type string       $type               Optional. Shorthand for certain orderby/order combinations.
     *                                            'newest', 'active', 'popular', 'alphabetical', 'random'.
     *                                            When present, will override orderby and order params.
     *                                            Default: null.
     *     @type string       $orderby            Optional. Property to sort by. 'date_created', 'last_activity',
     *                                            'total_member_count', 'name', 'random', 'meta_id'.
     *                                            Default: 'date_created'.
     *     @type string       $order              Optional. Sort order. 'ASC' or 'DESC'. Default: 'DESC'.
     *     @type int          $per_page           Optional. Number of items to return per page of results.
     *                                            Default: null (no limit).
     *     @type int          $page               Optional. Page offset of results to return.
     *                                            Default: null (no limit).
     *     @type int          $user_id            Optional. If provided, results will be limited to groups
     *                                            of which the specified user is a member. Default: null.
     *     @type array|string $slug               Optional. Array or comma-separated list of group slugs to limit
     *                                            results to.
     *                                            Default: false.
     *     @type string       $search_terms       Optional. If provided, only groups whose names or descriptions
     *                                            match the search terms will be returned. Allows specifying the
     *                                            wildcard position using a '*' character before or after the
     *                                            string or both. Works in concert with $search_columns.
     *                                            Default: false.
     *     @type string       $search_columns     Optional. If provided, only apply the search terms to the
     *                                            specified columns. Works in concert with $search_terms.
     *                                            Default: empty array.
     *     @type array|string $group_type         Array or comma-separated list of group types to limit results to.
     *     @type array|string $group_type__in     Array or comma-separated list of group types to limit results to.
     *     @type array|string $group_type__not_in Array or comma-separated list of group types that will be
     *                                            excluded from results.
     *     @type array        $meta_query         Optional. An array of meta_query conditions.
     *                                            See {@link WP_Meta_Query::queries} for description.
     *     @type array        $date_query         Optional. Filter results by group last activity date. See first
     *                                            paramter of {@link WP_Date_Query::__construct()} for syntax. Only
     *                                            applicable if $type is either 'newest' or 'active'.
     *     @type array|string $value              Optional. Array or comma-separated list of group IDs. Results
     *                                            will be limited to groups within the list. Default: false.
     *     @type array|string $parent_id          Optional. Array or comma-separated list of group IDs. Results
     *                                            will be limited to children of the specified groups. Default: null.
     *     @type array|string $exclude            Optional. Array or comma-separated list of group IDs.
     *                                            Results will exclude the listed groups. Default: false.
     *     @type bool         $update_meta_cache  Whether to pre-fetch groupmeta for the returned groups.
     *                                            Default: true.
     *     @type bool         $update_admin_cache Whether to pre-fetch administrator IDs for the returned
     *                                            groups. Default: false.
     *     @type bool         $show_hidden        Whether to include hidden groups in results. Default: false.
     *     @type array|string $status             Optional. Array or comma-separated list of group statuses to limit
     *                                            results to. If specified, $show_hidden is ignored.
     *                                            Default: empty array.
     *     @type string       $fields             Which fields to return. Specify 'ids' to fetch a list of IDs.
     *                                            Default: 'all' (return BP_Groups_Group objects).
     *                                            If set, meta and admin caches will not be prefetched.
     * }
     * @return array {
     *     @type array $groups Array of group objects returned by the
     *                         paginated query. (IDs only if `fields` is set to `ids`.)
     *     @type int   $total  Total count of all groups matching non-
     *                         paginated query params.
     * }
     */
    public static function get($args = array())
    {
    }
    /**
     * Get the SQL for the 'meta_query' param in BP_Groups_Group::get()
     *
     * We use WP_Meta_Query to do the heavy lifting of parsing the
     * meta_query array and creating the necessary SQL clauses.
     *
     * @since 1.8.0
     *
     * @param array $meta_query An array of meta_query filters. See the
     *                          documentation for {@link WP_Meta_Query} for details.
     * @return array $sql_array 'join' and 'where' clauses.
     */
    protected static function get_meta_query_sql($meta_query = array())
    {
    }
    /**
     * Convert the 'type' parameter to 'order' and 'orderby'.
     *
     * @since 1.8.0
     *
     * @param string $type The 'type' shorthand param.
     *
     * @return array {
     *     @type string $order   SQL-friendly order string.
     *     @type string $orderby SQL-friendly orderby column name.
     * }
     */
    protected static function convert_type_to_order_orderby($type = '')
    {
    }
    /**
     * Convert the 'orderby' param into a proper SQL term/column.
     *
     * @since 1.8.0
     *
     * @param string $orderby Orderby term as passed to get().
     * @return string $order_by_term SQL-friendly orderby term.
     */
    protected static function convert_orderby_to_order_by_term($orderby)
    {
    }
    /**
     * Get a list of groups whose names start with a given letter.
     *
     * @since 1.6.0
     *
     * @param string            $letter          The letter.
     * @param int|null          $limit           Optional. The max number of results to return.
     *                                           Default: null (no limit).
     * @param int|null          $page            Optional. The page offset of results to return.
     *                                           Default: null (no limit).
     * @param bool              $populate_extras Deprecated.
     * @param string|array|bool $exclude         Optional. Array or comma-separated list of group
     *                                           IDs to exclude from results.
     * @return false|array {
     *     @type array $groups Array of group objects returned by the
     *                         paginated query.
     *     @type int   $total  Total count of all groups matching non-
     *                         paginated query params.
     * }
     */
    public static function get_by_letter($letter, $limit = \null, $page = \null, $populate_extras = \true, $exclude = \false)
    {
    }
    /**
     * Get a list of random groups.
     *
     * Use BP_Groups_Group::get() with 'type' = 'random' instead.
     *
     * @since 1.6.0
     * @since 10.0.0 Deprecate the `$populate_extras` arg.
     *
     * @param int|null          $limit           Optional. The max number of results to return.
     *                                           Default: null (no limit).
     * @param int|null          $page            Optional. The page offset of results to return.
     *                                           Default: null (no limit).
     * @param int               $user_id         Optional. If present, groups will be limited to
     *                                           those of which the specified user is a member.
     * @param string|bool       $search_terms    Optional. Limit groups to those whose name
     *                                           or description field contain the search string.
     * @param bool              $populate_extras Deprecated.
     * @param string|array|bool $exclude         Optional. Array or comma-separated list of group
     *                                           IDs to exclude from results.
     * @return array {
     *     @type array $groups Array of group objects returned by the
     *                         paginated query.
     *     @type int   $total  Total count of all groups matching non-
     *                         paginated query params.
     * }
     */
    public static function get_random($limit = \null, $page = \null, $user_id = 0, $search_terms = \false, $populate_extras = \true, $exclude = \false)
    {
    }
    /**
     * Fetch extra data for a list of groups.
     *
     * This method is used throughout the class, by methods that take a
     * $populate_extras parameter.
     *
     * Data fetched:
     *     - Logged-in user's status within each group (is_member,
     *       is_confirmed, is_pending, is_banned)
     *
     * @since 1.6.0
     *
     * @param array        $paged_groups Array of groups.
     * @param string|array $group_ids    Array or comma-separated list of IDs matching
     *                                   $paged_groups.
     * @param string|bool  $type         Not used.
     * @return array $paged_groups
     */
    public static function get_group_extras(&$paged_groups, &$group_ids, $type = \false)
    {
    }
    /**
     * Delete all invitations to a given group.
     *
     * @since 1.6.0
     *
     * @param int $group_id ID of the group whose invitations are being deleted.
     * @return int|null Number of rows records deleted on success, null on
     *                  failure.
     */
    public static function delete_all_invites($group_id)
    {
    }
    /**
     * Get a total group count for the site.
     *
     * Will include hidden groups in the count only if
     * bp_current_user_can( 'bp_moderate' ).
     *
     * @since 1.6.0
     * @since 10.0.0 Added the `$skip_cache` parameter.
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param bool $skip_cache Optional. Skip getting count from cache.
     *                         Defaults to false.
     * @return int
     */
    public static function get_total_group_count($skip_cache = \false)
    {
    }
    /**
     * Get the member count for a group.
     *
     * @since 1.6.0
     * @since 10.0.0 Updated to use the `groups_get_group_members`.
     *
     * @param int  $group_id   Group ID.
     * @param bool $skip_cache Optional. Skip getting count from cache. Defaults to false.
     * @return int Count of confirmed members for the group.
     */
    public static function get_total_member_count($group_id, $skip_cache = \false)
    {
    }
    /**
     * Get an array containing ids for each group type.
     *
     * A bit of a kludge workaround for some issues
     * with bp_has_groups().
     *
     * @since 1.7.0
     *
     * @return array
     */
    public static function get_group_type_ids()
    {
    }
    /**
     * Get SQL clause for group type(s).
     *
     * @since 2.6.0
     *
     * @param  string|array $group_types Group type(s).
     * @param  string       $operator    'IN' or 'NOT IN'.
     * @return string       $clause      SQL clause.
     */
    protected static function get_sql_clause_for_group_types($group_types, $operator)
    {
    }
    /**
     * Strips the leading AND and any surrounding whitespace from a string.
     *
     * Used here to normalize SQL fragments generated by `WP_Meta_Query` and
     * other utility classes.
     *
     * @since 2.7.0
     *
     * @param string $s String.
     * @return string
     */
    protected static function strip_leading_and($s)
    {
    }
}
/**
 * Group Members Loop template class.
 *
 * @since 1.0.0
 */
class BP_Groups_Group_Members_Template
{
    /**
     * @since 1.0.0
     * @var int
     */
    public $current_member = -1;
    /**
     * @since 1.0.0
     * @var int
     */
    public $member_count;
    /**
     * @since 1.0.0
     * @var array
     */
    public $members;
    /**
     * @since 1.0.0
     * @var object
     */
    public $member;
    /**
     * @since 1.0.0
     * @var bool
     */
    public $in_the_loop;
    /**
     * @since 1.0.0
     * @var int
     */
    public $pag_page;
    /**
     * @since 1.0.0
     * @var int
     */
    public $pag_num;
    /**
     * @since 1.0.0
     * @var array|string|null
     */
    public $pag_links;
    /**
     * URL argument used for the pagination param.
     *
     * @since 1.0.0
     * @var string
     */
    public $pag_arg;
    /**
     * The type of member being requested. Used for ordering results.
     *
     * @since 2.3.0
     * @var string
     */
    public $type = '';
    /**
     * The total number of members.
     *
     * @var int
     */
    public $total_member_count;
    /**
     * @since 1.0.0
     * @var int
     */
    public $total_group_count;
    /**
     * Constructor.
     *
     * @since 1.5.0
     *
     * @param array $args {
     *     An array of optional arguments.
     *     @type int      $group_id           ID of the group whose members are being
     *                                        queried. Default: current group ID.
     *     @type int      $page               Page of results to be queried. Default: 1.
     *     @type int      $per_page           Number of items to return per page of
     *                                        results. Default: 20.
     *     @type int      $max                Optional. Max number of items to return.
     *     @type array    $exclude            Optional. Array of user IDs to exclude.
     *     @type bool|int $exclude_admin_mods True (or 1) to exclude admins and mods from
     *                                        results. Default: 1.
     *     @type bool|int $exclude_banned     True (or 1) to exclude banned users from results.
     *                                        Default: 1.
     *     @type array    $group_role         Optional. Array of group roles to include.
     *     @type string   $search_terms       Optional. Search terms to match.
     * }
     */
    public function __construct($args = array())
    {
    }
    /**
     * Whether or not there are members to display.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function has_members()
    {
    }
    /**
     * Increments to the next member to display.
     *
     * @since 1.0.0
     *
     * @return object
     */
    public function next_member()
    {
    }
    /**
     * Rewinds to the first member to display.
     *
     * @since 1.0.0
     */
    public function rewind_members()
    {
    }
    /**
     * Finishes up the members for display.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function members()
    {
    }
    /**
     * Sets up the member to display.
     *
     * @since 1.0.0
     */
    public function the_member()
    {
    }
}
/**
 * The main theme compat class for BuddyPress Groups.
 *
 * This class sets up the necessary theme compatibility actions to safely output
 * group template parts to the_title and the_content areas of a theme.
 *
 * @since 1.7.0
 */
class BP_Groups_Theme_Compat
{
    /**
     * Set up theme compatibility for the Groups component.
     *
     * @since 1.7.0
     */
    public function __construct()
    {
    }
    /**
     * Are we looking at something that needs group theme compatibility?
     *
     * @since 1.7.0
     */
    public function is_group()
    {
    }
    /** Directory *********************************************************/
    /**
     * Add template hierarchy to theme compat for the group directory page.
     *
     * This is to mirror how WordPress has
     * {@link https://codex.wordpress.org/Template_Hierarchy template hierarchy}.
     *
     * @since 1.8.0
     *
     * @param string $templates The templates from bp_get_theme_compat_templates().
     * @return array $templates Array of custom templates to look for.
     */
    public function directory_template_hierarchy($templates)
    {
    }
    /**
     * Update the global $post with directory data.
     *
     * @since 1.7.0
     */
    public function directory_dummy_post()
    {
    }
    /**
     * Filter the_content with the groups index template part.
     *
     * @since 1.7.0
     */
    public function directory_content()
    {
    }
    /** Create ************************************************************/
    /**
     * Add custom template hierarchy to theme compat for the group create page.
     *
     * This is to mirror how WordPress has
     * {@link https://codex.wordpress.org/Template_Hierarchy template hierarchy}.
     *
     * @since 1.8.0
     *
     * @param string $templates The templates from bp_get_theme_compat_templates().
     * @return array $templates Array of custom templates to look for.
     */
    public function create_template_hierarchy($templates)
    {
    }
    /**
     * Update the global $post with create screen data.
     *
     * @since 1.7.0
     */
    public function create_dummy_post()
    {
    }
    /**
     * Filter the_content with the create screen template part.
     *
     * @since 1.7.0
     */
    public function create_content()
    {
    }
    /** Single ************************************************************/
    /**
     * Add custom template hierarchy to theme compat for group pages.
     *
     * This is to mirror how WordPress has
     * {@link https://codex.wordpress.org/Template_Hierarchy template hierarchy}.
     *
     * @since 1.8.0
     *
     * @param string $templates The templates from bp_get_theme_compat_templates().
     * @return array $templates Array of custom templates to look for.
     */
    public function single_template_hierarchy($templates)
    {
    }
    /**
     * Update the global $post with single group data.
     *
     * @since 1.7.0
     */
    public function single_dummy_post()
    {
    }
    /**
     * Filter the_content with the single group template part.
     *
     * @since 1.7.0
     */
    public function single_content()
    {
    }
}
/**
 * Group Avatar endpoints.
 *
 * @since 5.0.0
 */
class BP_REST_Attachments_Group_Avatar_Endpoint extends \WP_REST_Controller
{
    use \BP_REST_Attachments;
    /**
     * Reuse some parts of the BP_REST_Groups_Endpoint class.
     *
     * @since 5.0.0
     *
     * @var BP_REST_Groups_Endpoint
     */
    protected $groups_endpoint;
    /**
     * BP_Attachment_Avatar Instance.
     *
     * @since 5.0.0
     *
     * @var BP_Attachment_Avatar
     */
    protected $avatar_instance;
    /**
     * Hold the group object.
     *
     * @since 5.0.0
     *
     * @var BP_Groups_Group
     */
    protected $group;
    /**
     * Group object type.
     *
     * @since 5.0.0
     *
     * @var string
     */
    protected $object = 'group';
    /**
     * Constructor.
     *
     * @since 5.0.0
     */
    public function __construct()
    {
    }
    /**
     * Register the component routes.
     *
     * @since 5.0.0
     */
    public function register_routes()
    {
    }
    /**
     * Fetch an existing group avatar.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_item($request)
    {
    }
    /**
     * Checks if a given request has access to get a group avatar.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return true|WP_Error
     */
    public function get_item_permissions_check($request)
    {
    }
    /**
     * Upload a group avatar.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function create_item($request)
    {
    }
    /**
     * Checks if a given request has access to upload a group avatar.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return true|WP_Error
     */
    public function create_item_permissions_check($request)
    {
    }
    /**
     * Delete an existing group avatar.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function delete_item($request)
    {
    }
    /**
     * Checks if a given request has access to delete a group avatar.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return true|WP_Error
     */
    public function delete_item_permissions_check($request)
    {
    }
    /**
     * Prepares avatar data to return as an object.
     *
     * @since 5.0.0
     *
     * @param stdClass|string $avatar  Avatar object or string with url or image with html.
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response
     */
    public function prepare_item_for_response($avatar, $request)
    {
    }
    /**
     * Get the plugin schema, conforming to JSON Schema.
     *
     * @since 5.0.0
     *
     * @return array
     */
    public function get_item_schema()
    {
    }
    /**
     * Get the query params for the `get_item`.
     *
     * @since 5.0.0
     *
     * @return array
     */
    public function get_item_collection_params()
    {
    }
}
/**
 * Groups widget.
 *
 * @since 1.0.3
 * @deprecated 12.0.0
 */
class BP_Groups_Widget
{
    /**
     * Working as a group, we get things done better.
     *
     * @since 1.0.3
     * @since 9.0.0 Adds the `show_instance_in_rest` property to Widget options.
     * @deprecated 12.0.0
     */
    public function __construct()
    {
    }
    /**
     * Enqueue scripts.
     *
     * @since 2.6.0
     * @deprecated 12.0.0
     */
    public function enqueue_scripts()
    {
    }
    /**
     * Extends our front-end output method.
     *
     * @since 1.0.3
     * @deprecated 12.0.0
     *
     * @param array $args     Array of arguments for the widget.
     * @param array $instance Widget instance data.
     */
    public function widget($args, $instance)
    {
    }
    /**
     * Extends our update method.
     *
     * @since 1.0.3
     * @deprecated 12.0.0
     *
     * @param array $new_instance New instance data.
     * @param array $old_instance Original instance data.
     * @return array
     */
    public function update($new_instance, $old_instance)
    {
    }
    /**
     * Extends our form method.
     *
     * @since 1.0.3
     * @deprecated 12.0.0
     *
     * @param array $instance Current instance.
     * @return mixed
     */
    public function form($instance)
    {
    }
}
/**
 * Group invitation template loop class.
 *
 * @since 1.1.0
 */
class BP_Groups_Invite_Template
{
    /**
     * @since 1.1.0
     * @var int
     */
    public $current_invite = -1;
    /**
     * @since 1.1.0
     * @var int
     */
    public $invite_count;
    /**
     * @since 1.1.0
     * @var array
     */
    public $invites;
    /**
     * @since 1.1.0
     * @var object
     */
    public $invite;
    /**
     * List of invites found and their respective data.
     *
     * @since 1.1.0
     * @var array
     */
    public $invite_data = array();
    /**
     * @since 1.1.0
     * @var bool
     */
    public $in_the_loop;
    /**
     * @since 1.1.0
     * @var int
     */
    public $pag_page;
    /**
     * @since 1.1.0
     * @var int
     */
    public $pag_num;
    /**
     * @since 1.1.0
     * @var string
     */
    public $pag_links;
    /**
     * URL argument used for the pagination param.
     *
     * @since 1.1.0
     * @var string
     */
    public $pag_arg;
    /**
     * @since 1.1.0
     * @var int
     */
    public $total_invite_count;
    /**
     * BP_Groups_Invite_Template constructor.
     *
     * @since 1.5.0
     *
     * @param array $args
     */
    public function __construct($args = array())
    {
    }
    /**
     * Whether or not there are invites to show.
     *
     * @since 1.1.0
     *
     * @return bool
     */
    public function has_invites()
    {
    }
    /**
     * Increments up to the next invite to show.
     *
     * @since 1.1.0
     *
     * @return object
     */
    public function next_invite()
    {
    }
    /**
     * Rewinds to the first invite to show.
     *
     * @since 1.1.0
     */
    public function rewind_invites()
    {
    }
    /**
     * Finishes up the invites to show.
     *
     * @since 1.1.0
     *
     * @return bool
     */
    public function invites()
    {
    }
    /**
     * Sets up the invite to show.
     *
     * @since 1.1.0
     */
    public function the_invite()
    {
    }
}
/**
 * List table class for the Groups component admin page.
 *
 * @since 1.7.0
 */
class BP_Groups_List_Table extends \WP_List_Table
{
    /**
     * The type of view currently being displayed.
     *
     * E.g. "All", "Pending", "Approved", "Spam"...
     *
     * @since 1.7.0
     * @var string
     */
    public $view = 'all';
    /**
     * Group counts for each group type.
     *
     * @since 1.7.0
     * @var int
     */
    public $group_counts = 0;
    /**
     * Multidimensional array of group visibility (status) types and their groups.
     *
     * @link https://buddypress.trac.wordpress.org/ticket/6277
     * @var array
     */
    public $group_type_ids = array();
    /**
     * Constructor
     *
     * @since 1.7.0
     */
    public function __construct()
    {
    }
    /**
     * Set up items for display in the list table.
     *
     * Handles filtering of data, sorting, pagination, and any other data
     * manipulation required prior to rendering.
     *
     * @since 1.7.0
     */
    public function prepare_items()
    {
    }
    /**
     * Get an array of all the columns on the page.
     *
     * @since 1.7.0
     *
     * @return array Array of column headers.
     */
    public function get_column_info()
    {
    }
    /**
     * Get name of default primary column
     *
     * @since 2.3.3
     *
     * @return string
     */
    protected function get_default_primary_column_name()
    {
    }
    /**
     * Display a message on screen when no items are found ("No groups found").
     *
     * @since 1.7.0
     */
    public function no_items()
    {
    }
    /**
     * Output the Groups data table.
     *
     * @since 1.7.0
     */
    public function display()
    {
    }
    /**
     * Extra controls to be displayed between bulk actions and pagination
     *
     * @since 2.7.0
     * @access protected
     *
     * @param string $which
     */
    protected function extra_tablenav($which)
    {
    }
    /**
     * Generate content for a single row of the table.
     *
     * @since 1.7.0
     *
     * @param object|array $item The current group item in the loop.
     */
    public function single_row($item = array())
    {
    }
    /**
     * Get the list of views available on this table (e.g. "all", "public").
     *
     * @since 1.7.0
     */
    public function get_views()
    {
    }
    /**
     * Get bulk actions for single group row.
     *
     * @since 1.7.0
     *
     * @return array Key/value pairs for the bulk actions dropdown.
     */
    public function get_bulk_actions()
    {
    }
    /**
     * Get the table column titles.
     *
     * @since 1.7.0
     *
     * @see WP_List_Table::single_row_columns()
     *
     * @return array Array of column titles.
     */
    public function get_columns()
    {
    }
    /**
     * Get the column names for sortable columns.
     *
     * Note: It's not documented in WP, but the second item in the
     * nested arrays below is $desc_first. Normally, we would set
     * last_active to be desc_first (since you're generally interested in
     * the *most* recently active group, not the *least*). But because
     * the default sort for the Groups admin screen is DESC by last_active,
     * we want the first click on the Last Active column header to switch
     * the sort order - ie, to make it ASC. Thus last_active is set to
     * $desc_first = false.
     *
     * @since 1.7.0
     *
     * @return array Array of sortable column names.
     */
    public function get_sortable_columns()
    {
    }
    /**
     * Override WP_List_Table::row_actions().
     *
     * Basically a duplicate of the row_actions() method, but removes the
     * unnecessary <button> addition.
     *
     * @since 2.3.3
     * @since 2.3.4 Visibility set to public for compatibility with WP < 4.0.0.
     *
     * @param array $actions        The list of actions.
     * @param bool  $always_visible Whether the actions should be always visible.
     * @return string
     */
    public function row_actions($actions, $always_visible = \false)
    {
    }
    /**
     * Markup for the Checkbox column.
     *
     * @since 1.7.0
     *
     * @see WP_List_Table::single_row_columns()
     *
     * @param array $item A singular item (one full row).
     */
    public function column_cb($item = array())
    {
    }
    /**
     * Markup for the Group ID column.
     *
     * @since 1.7.0
     *
     * @see WP_List_Table::single_row_columns()
     *
     * @param array $item A singular item (one full row).
     */
    public function column_gid($item = array())
    {
    }
    /**
     * Name column, and "quick admin" rollover actions.
     *
     * Called "comment" in the CSS so we can re-use some WP core CSS.
     *
     * @since 1.7.0
     *
     * @see WP_List_Table::single_row_columns()
     *
     * @param array $item A singular item (one full row).
     */
    public function column_comment($item = array())
    {
    }
    /**
     * Markup for the Description column.
     *
     * @since 1.7.0
     *
     * @param array $item Information about the current row.
     */
    public function column_description($item = array())
    {
    }
    /**
     * Markup for the Status column.
     *
     * @since 1.7.0
     *
     * @param array $item Information about the current row.
     */
    public function column_status($item = array())
    {
    }
    /**
     * Markup for the Number of Members column.
     *
     * @since 1.7.0
     * @since 10.0.0 Updated to use `groups_get_total_member_count`.
     *
     * @param array $item Information about the current row.
     */
    public function column_members($item = array())
    {
    }
    /**
     * Markup for the Last Active column.
     *
     * @since 1.7.0
     *
     * @param array $item Information about the current row.
     */
    public function column_last_active($item = array())
    {
    }
    /**
     * Allow plugins to add their custom column.
     *
     * @since 2.0.0
     *
     * @param array  $item        Information about the current row.
     * @param string $column_name The column name.
     * @return string
     */
    public function column_default($item = array(), $column_name = '')
    {
    }
    // Group Types
    /**
     * Add group type column to the WordPress admin groups list table.
     *
     * @since 2.7.0
     *
     * @param array $columns Groups table columns.
     *
     * @return array $columns
     */
    public function add_type_column($columns = array())
    {
    }
    /**
     * Markup for the Group Type column.
     *
     * @since 2.7.0
     *
     * @param string $retval      Empty string.
     * @param string $column_name Name of the column being rendered.
     * @param array  $item        The current group item in the loop.
     * @return string
     */
    public function column_content_group_type($retval = '', $column_name = '', $item = array())
    {
    }
    /**
     * Filters the group type list permalink in the Group Type column.
     *
     * Changes the group type permalink to use the admin URL.
     *
     * @since 2.7.0
     *
     * @param  string $retval Current group type permalink.
     * @param  object $type   Group type object.
     * @return string
     */
    public function group_type_permalink_use_admin_filter($retval, $type)
    {
    }
    /**
     * Markup for the Group Type bulk change select.
     *
     * @since 2.7.0
     *
     * @param string $which The location of the extra table nav markup: 'top' or 'bottom'.
     */
    public function add_group_type_bulk_change_select($which)
    {
    }
}
/**
 * Class for generating the WHERE SQL clause for advanced activity fetching.
 *
 * This is notably used in {@link BP_Activity_Activity::get()} with the
 * 'filter_query' parameter.
 *
 * @since 2.2.0
 */
class BP_Activity_Query extends \BP_Recursive_Query
{
    /**
     * Array of activity queries.
     *
     * See {@see BP_Activity_Query::__construct()} for information on query arguments.
     *
     * @since 2.2.0
     * @var array
     */
    public $queries = array();
    /**
     * Table alias.
     *
     * @since 2.2.0
     * @var string
     */
    public $table_alias = '';
    /**
     * Supported DB columns.
     *
     * See the 'wp_bp_activity' DB table schema.
     *
     * @since 2.2.0
     * @var array
     */
    public $db_columns = array('id', 'user_id', 'component', 'type', 'action', 'content', 'primary_link', 'item_id', 'secondary_item_id', 'hide_sitewide', 'is_spam');
    /**
     * Constructor.
     *
     * @since 2.2.0
     *
     * @param array $query {
     *     Array of query clauses.
     *     @type array {
     *         @type string $column   Required. The column to query against. Basically, any DB column in the main
     *                                'wp_bp_activity' table.
     *         @type string $value    Required. Value to filter by.
     *         @type string $compare  Optional. The comparison operator. Default '='.
     *                                Accepts '=', '!=', '>', '>=', '<', '<=', 'IN', 'NOT IN', 'LIKE',
     *                                'NOT LIKE', BETWEEN', 'NOT BETWEEN', 'REGEXP', 'NOT REGEXP', 'RLIKE'.
     *         @type string $relation Optional. The boolean relationship between the activity queries.
     *                                Accepts 'OR', 'AND'. Default 'AND'.
     *         @type array {
     *             Optional. Another fully-formed activity query. See parameters above.
     *         }
     *     }
     * }
     */
    public function __construct($query = array())
    {
    }
    /**
     * Generates WHERE SQL clause to be appended to a main query.
     *
     * @since 2.2.0
     *
     * @param string $alias An existing table alias that is compatible with the current query clause.
     *                      Default: 'a'. BP_Activity_Activity::get() uses 'a', so we default to that.
     * @return string SQL fragment to append to the main WHERE clause.
     */
    public function get_sql($alias = 'a')
    {
    }
    /**
     * Generate WHERE clauses for a first-order clause.
     *
     * @since 2.2.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param  array $clause       Array of arguments belonging to the clause.
     * @param  array $parent_query Parent query to which the clause belongs.
     * @return array {
     *     @type array $where Array of subclauses for the WHERE statement.
     *     @type array $join  Empty array. Not used.
     * }
     */
    protected function get_sql_for_clause($clause, $parent_query)
    {
    }
    /**
     * Determine whether a clause is first-order.
     *
     * @since 2.2.0
     *
     * @param array $query Clause to check.
     * @return bool
     */
    protected function is_first_order_clause($query)
    {
    }
    /**
     * Validates a column name parameter.
     *
     * Column names are checked against a list of known tables.
     * See {@link BP_Activity_Query::db_tables}.
     *
     * @since 2.2.0
     *
     * @param string $column The user-supplied column name.
     * @return string A validated column name value.
     */
    public function validate_column($column = '')
    {
    }
}
/**
 * Create a RSS feed using the activity component.
 *
 * You should only construct a new feed when you've validated that you're on
 * the appropriate screen.
 *
 * @since 1.8.0
 *
 * See {@link bp_activity_action_sitewide_feed()} as an example.
 *
 * @param array $args {
 *   @type string $id               Required. Internal id for the feed; should be alphanumeric only.
 *   @type string $title            Optional. RSS feed title.
 *   @type string $link             Optional. Relevant link for the RSS feed.
 *   @type string $description      Optional. RSS feed description.
 *   @type string $ttl              Optional. Time-to-live. (see inline doc in constructor)
 *   @type string $update_period    Optional. Part of the syndication module.
 *                                            (see inline doc in constructor for more info)
 *   @type string $update_frequency Optional. Part of the syndication module.
 *                                            (see inline doc in constructor for more info)
 *   @type string $max              Optional. Number of feed items to display.
 *   @type array  $activity_args    Optional. Arguments passed to {@link bp_has_activities()}
 * }
 */
class BP_Activity_Feed
{
    /**
     * Holds our custom class properties.
     *
     * These variables are stored in a protected array that is magically
     * updated using PHP 5.2+ methods.
     *
     * @see BP_Feed::__construct() This is where $data is added.
     *
     * @since 1.8.0
     * @var array
     */
    protected $data;
    /**
     * Magic method for checking the existence of a certain data variable.
     *
     * @since 1.8.0
     *
     * @param string $key Property to check.
     * @return bool Whether or not data variable exists.
     */
    public function __isset($key)
    {
    }
    /**
     * Magic method for getting a certain data variable.
     *
     * @since 1.8.0
     *
     * @param string $key Property to get.
     * @return mixed Data in variable if available or null.
     */
    public function __get($key)
    {
    }
    /**
     * Magic method for setting a certain data variable.
     *
     * @since 2.4.0
     *
     * @param string $key   The property to set.
     * @param mixed  $value The value to set.
     */
    public function __set($key, $value)
    {
    }
    /**
     * Constructor.
     *
     * @since 1.8.0
     *
     * @global WP_Query $wp_query WordPress query object.
     *
     * @param array $args Optional.
     */
    public function __construct($args = array())
    {
    }
    /** SETUP ****************************************************************/
    /**
     * Setup and validate the class properties.
     *
     * @since 1.8.0
     */
    protected function setup_properties()
    {
    }
    /**
     * Setup some hooks that are used in the feed.
     *
     * Currently, these hooks are used to maintain backwards compatibility with
     * the RSS feeds previous to BP 1.8.
     *
     * @since 1.8.0
     */
    protected function setup_hooks()
    {
    }
    /** BACKPAT HOOKS ********************************************************/
    /**
     * Fire a hook to ensure backward compatibility for RSS attributes.
     *
     * @since 1.8.0
     */
    public function backpat_rss_attributes()
    {
    }
    /**
     * Fire a hook to ensure backward compatibility for channel elements.
     *
     * @since 1.8.0
     */
    public function backpat_channel_elements()
    {
    }
    /**
     * Fire a hook to ensure backward compatibility for item elements.
     *
     * @since 1.8.0
     */
    public function backpat_item_elements()
    {
    }
    /** HELPERS **************************************************************/
    /**
     * Output the feed's item content.
     *
     * @since 1.8.0
     */
    protected function feed_content()
    {
    }
    /**
     * Sets various HTTP headers related to Content-Type and browser caching.
     *
     * Most of this class method is derived from {@link WP::send_headers()}.
     *
     * @since 1.9.0
     *
     * @global WP_Query $wp_query WordPress query object.
     */
    protected function http_headers()
    {
    }
    /** OUTPUT ***************************************************************/
    /**
     * Output the RSS feed.
     *
     * @since 1.8.0
     */
    protected function output()
    {
    }
}
/**
 * Activity endpoints.
 *
 * @since 5.0.0
 */
class BP_REST_Activity_Endpoint extends \WP_REST_Controller
{
    /**
     * User favorites.
     *
     * @since 5.0.0
     *
     * @var array|null
     */
    protected $user_favorites = \null;
    /**
     * Constructor.
     *
     * @since 5.0.0
     */
    public function __construct()
    {
    }
    /**
     * Register the component routes.
     *
     * @since 5.0.0
     */
    public function register_routes()
    {
    }
    /**
     * Retrieve activities.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response List of activities response data.
     */
    public function get_items($request)
    {
    }
    /**
     * Check if a given request has access to activity items.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return true|WP_Error
     */
    public function get_items_permissions_check($request)
    {
    }
    /**
     * Retrieve an activity.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_item($request)
    {
    }
    /**
     * Check if a given request has access to get information about a specific activity.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return true|WP_Error
     */
    public function get_item_permissions_check($request)
    {
    }
    /**
     * Create an activity.
     *
     * @since 5.0.0
     *
     * @param  WP_REST_Request $request Full data about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function create_item($request)
    {
    }
    /**
     * Checks if a given request has access to create an activity.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return true|WP_Error
     */
    public function create_item_permissions_check($request)
    {
    }
    /**
     * Update an activity.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function update_item($request)
    {
    }
    /**
     * Check if a given request has access to update an activity.
     *
     * @since 5.0.0
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return true|WP_Error
     */
    public function update_item_permissions_check($request)
    {
    }
    /**
     * Delete activity.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function delete_item($request)
    {
    }
    /**
     * Check if a given request has access to delete an activity.
     *
     * @since 5.0.0
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return true|WP_Error
     */
    public function delete_item_permissions_check($request)
    {
    }
    /**
     * Gets the current user's favorites.
     *
     * @since 5.0.0
     *
     * @return array Array of activity IDs.
     */
    public function get_user_favorites()
    {
    }
    /**
     * Adds or removes the activity from the current user's favorites.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function update_favorite($request)
    {
    }
    /**
     * Check if a given request has access to update user favorites.
     *
     * @since 5.0.0
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return true|WP_Error
     */
    public function update_favorite_permissions_check($request)
    {
    }
    /**
     * Renders the content of an activity.
     *
     * @since 5.0.0
     *
     * @param BP_Activity_Activity $activity Activity data.
     * @return string The rendered activity content.
     */
    public function render_item($activity)
    {
    }
    /**
     * Prepares activity data for return as an object.
     *
     * @since 5.0.0
     *
     * @param BP_Activity_Activity $activity Activity object.
     * @param WP_REST_Request      $request  Full details about the request.
     * @return WP_REST_Response
     */
    public function prepare_item_for_response($activity, $request)
    {
    }
    /**
     * Prepare activity comments.
     *
     * @since 5.0.0
     *
     * @param  array           $comments Array of comments.
     * @param  WP_REST_Request $request  Full details about the request.
     * @return array           An array of activity comments.
     */
    protected function prepare_activity_comments($comments, $request)
    {
    }
    /**
     * Prepare an activity for create or update.
     *
     * @since 5.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return stdClass|WP_Error Object or WP_Error.
     */
    protected function prepare_item_for_database($request)
    {
    }
    /**
     * Prepare links for the request.
     *
     * @since 5.0.0
     *
     * @param BP_Activity_Activity $activity Activity object.
     * @return array
     */
    protected function prepare_links($activity)
    {
    }
    /**
     * Can this user see the activity?
     *
     * @since 5.0.0
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return boolean
     */
    protected function can_see($request)
    {
    }
    /**
     * Show hidden activity?
     *
     * @since 5.0.0
     *
     * @param  string $component The component the activity is from.
     * @param  int    $item_id   The activity item ID.
     * @return boolean
     */
    protected function show_hidden($component, $item_id)
    {
    }
    /**
     * Get activity object.
     *
     * @since 5.0.0
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return BP_Activity_Activity|string An activity object.
     */
    public function get_activity_object($request)
    {
    }
    /**
     * Edit the type of the some properties for the CREATABLE & EDITABLE methods.
     *
     * @since 5.0.0
     *
     * @param string $method Optional. HTTP method of the request.
     * @return array Endpoint arguments.
     */
    public function get_endpoint_args_for_item_schema($method = \WP_REST_Server::CREATABLE)
    {
    }
    /**
     * Get the plugin schema, conforming to JSON Schema.
     *
     * @since 5.0.0
     *
     * @return array
     */
    public function get_item_schema()
    {
    }
    /**
     * Get the query params for collections of plugins.
     *
     * @since 5.0.0
     *
     * @return array
     */
    public function get_collection_params()
    {
    }
}
/**
 * Akismet support for the Activity component.
 *
 * @since 1.6.0
 * @since 2.3.0 We only support Akismet 3+.
 */
class BP_Akismet
{
    /**
     * The activity last marked as spam.
     *
     * @since 1.6.0
     * @var BP_Activity_Activity
     */
    protected $last_activity = \null;
    /**
     * Constructor.
     *
     * @since 1.6.0
     */
    public function __construct()
    {
    }
    /**
     * Hook Akismet into the activity stream.
     *
     * @since 1.6.0
     */
    protected function setup_actions()
    {
    }
    /**
     * Add a history item to the hover links in an activity's row.
     *
     * This function lifted with love from the Akismet WordPress plugin's
     * akismet_comment_row_action() function. Thanks!
     *
     * @since 1.6.0
     *
     * @param array $actions  The hover links.
     * @param array $activity The activity for the current row being processed.
     * @return array The hover links.
     */
    function comment_row_action($actions, $activity)
    {
    }
    /**
     * Generate nonces for activity forms.
     *
     * These nonces appear in the member profile status form, as well as in
     * the reply form of each activity item. The nonces are, in turn, used
     * by Akismet to help detect spam activity.
     *
     * @since 1.6.0
     *
     * @see https://plugins.trac.wordpress.org/ticket/1232
     */
    public function add_activity_stream_nonce()
    {
    }
    /**
     * Clean up the bp_latest_update usermeta in case of spamming.
     *
     * Run just after an update is posted, this method check to see whether
     * the newly created update has been marked as spam by Akismet. If so,
     * the cached update is cleared from the user's 'bp_latest_update'
     * usermeta, ensuring that it won't appear in the member header and
     * elsewhere in the theme.
     *
     * This can't be done in BP_Akismet::check_activity() due to the
     * default AJAX implementation; see bp_dtheme_post_update().
     *
     * @since 1.6.0
     *
     * @see bp_dtheme_post_update()
     *
     * @param string $content     Activity update text.
     * @param int    $user_id     User ID.
     * @param int    $activity_id Activity ID.
     */
    public function check_member_activity_update($content, $user_id, $activity_id)
    {
    }
    /**
     * Adds a "mark as spam" button to each activity item for site admins.
     *
     * This function is intended to be used inside the activity stream loop.
     *
     * @since 1.6.0
     */
    public function add_activity_spam_button()
    {
    }
    /**
     * Adds a "mark as spam" button to each activity COMMENT item for site admins.
     *
     * This function is intended to be used inside the activity stream loop.
     *
     * @since 1.6.0
     */
    public function add_activity_comment_spam_button()
    {
    }
    /**
     * Get a filterable list of activity types that Akismet should automatically check for spam.
     *
     * @since 1.6.0
     *
     * @static
     *
     * @return array $value List of activity types.
     */
    public static function get_activity_types()
    {
    }
    /**
     * Mark activity item as spam.
     *
     * @since 1.6.0
     *
     * @param BP_Activity_Activity $activity Activity item being spammed.
     * @param string               $source   Either "by_a_person" (e.g. a person has
     *                                       manually marked the activity as spam) or
     *                                       "by_akismet" (automatically spammed).
     */
    public function mark_as_spam($activity, $source)
    {
    }
    /**
     * Mark activity item as ham.
     *
     * @since 1.6.0
     *
     * @param BP_Activity_Activity $activity Activity item being hammed.
     * @param string               $source   Either "by_a_person" (e.g. a person has
     *                                       manually marked the activity as ham) or
     *                                       "by_akismet" (automatically hammed).
     */
    public function mark_as_ham($activity, $source)
    {
    }
    /**
     * Build a data package for the Akismet service to inspect.
     *
     * @since 1.6.0
     *
     * @see http://akismet.com/development/api/#comment-check
     * @static
     *
     * @param BP_Activity_Activity $activity Activity item data.
     * @return array $activity_data
     */
    public static function build_akismet_data_package($activity)
    {
    }
    /**
     * Check if the activity item is spam or ham.
     *
     * @since 1.6.0
     *
     * @see http://akismet.com/development/api/
     * @todo Spam counter?
     * @todo Auto-delete old spam?
     *
     * @param BP_Activity_Activity $activity The activity item to check.
     */
    public function check_activity($activity)
    {
    }
    /**
     * Update activity meta after a manual spam change (user-initiated).
     *
     * @since 1.6.0
     *
     * @param BP_Activity_Activity $activity The activity to check.
     */
    public function update_activity_spam_meta($activity)
    {
    }
    /**
     * Update activity meta after a manual ham change (user-initiated).
     *
     * @since 1.6.0
     *
     * @param BP_Activity_Activity $activity The activity to check.
     */
    public function update_activity_ham_meta($activity)
    {
    }
    /**
     * Update activity meta after an automatic spam check (not user-initiated).
     *
     * @since 1.6.0
     *
     * @param BP_Activity_Activity $activity The activity to check.
     */
    public function update_activity_akismet_meta($activity)
    {
    }
    /**
     * Contact Akismet to check if this is spam or ham.
     *
     * Props to WordPress core Akismet plugin for a lot of this.
     *
     * @since 1.6.0
     *
     * @param array  $activity_data Packet of information to submit to Akismet.
     * @param string $check         "check" or "submit".
     * @param string $spam          "spam" or "ham".
     * @return array $activity_data Activity data, with Akismet data added.
     */
    public function send_akismet_request($activity_data, $check = 'check', $spam = 'spam')
    {
    }
    /**
     * Filters user agent when sending to Akismet to add BuddyPress info.
     *
     * @since 1.6.0
     *
     * @param string $user_agent User agent string, as generated by Akismet.
     * @return string $user_agent Modified user agent string.
     */
    public function buddypress_ua($user_agent)
    {
    }
    /**
     * Adds a "History" meta box to the activity edit screen.
     *
     * @since 1.6.0
     *
     * @param string $screen_action The type of screen that has been requested.
     */
    function add_history_metabox($screen_action)
    {
    }
    /**
     * History meta box for the Activity admin edit screen.
     *
     * @since 1.6.0
     *
     * @see https://buddypress.trac.wordpress.org/ticket/3907
     * @todo Update activity meta to allow >1 record with the same key (iterate through $history).
     *
     * @param object $item Activity item.
     */
    function history_metabox($item)
    {
    }
    /**
     * Update an activity item's Akismet history.
     *
     * @since 1.6.0
     *
     * @param int    $activity_id Activity item ID.
     * @param string $message     Human-readable description of what's changed.
     * @param string $event       The type of check we were carrying out.
     */
    public function update_activity_history($activity_id = 0, $message = '', $event = '')
    {
    }
    /**
     * Get an activity item's Akismet history.
     *
     * @since 1.6.0
     *
     * @param int $activity_id Activity item ID.
     * @return array The activity item's Akismet history.
     */
    public function get_activity_history($activity_id = 0)
    {
    }
}
/**
 * Database interaction class for the BuddyPress activity component.
 * Instance methods are available for creating/editing an activity,
 * static methods for querying activities.
 *
 * @since 1.0.0
 */
#[\AllowDynamicProperties]
class BP_Activity_Activity
{
    /** Properties ************************************************************/
    /**
     * ID of the activity item.
     *
     * @since 1.0.0
     * @var int
     */
    var $id;
    /**
     * ID of the associated item.
     *
     * @since 1.0.0
     * @var int
     */
    var $item_id;
    /**
     * ID of the associated secondary item.
     *
     * @since 1.0.0
     * @var int
     */
    var $secondary_item_id;
    /**
     * ID of user associated with the activity item.
     *
     * @since 1.0.0
     * @var int
     */
    var $user_id;
    /**
     * The primary URL for the activity in RSS feeds.
     *
     * @since 1.0.0
     * @var string
     */
    var $primary_link = '';
    /**
     * BuddyPress component the activity item relates to.
     *
     * @since 1.2.0
     * @var string
     */
    var $component = '';
    /**
     * Activity type, eg 'new_blog_post'.
     *
     * @since 1.2.0
     * @var string
     */
    var $type = '';
    /**
     * Description of the activity, eg 'Alex updated his profile.'.
     *
     * @since 1.2.0
     * @var string
     */
    var $action = '';
    /**
     * The content of the activity item.
     *
     * @since 1.2.0
     * @var string
     */
    var $content = '';
    /**
     * The date the activity item was recorded, in 'Y-m-d h:i:s' format.
     *
     * @since 1.0.0
     * @var string
     */
    var $date_recorded = '';
    /**
     * Whether the item should be hidden in sitewide streams.
     *
     * @since 1.1.0
     * @var int
     */
    var $hide_sitewide = 0;
    /**
     * Node boundary start for activity or activity comment.
     *
     * @since 1.5.0
     * @var int
     */
    var $mptt_left;
    /**
     * Node boundary end for activity or activity comment.
     *
     * @since 1.5.0
     * @var int
     */
    var $mptt_right;
    /**
     * Whether this item is marked as spam.
     *
     * @since 1.6.0
     * @var int
     */
    var $is_spam;
    /**
     * Error holder.
     *
     * @since 2.6.0
     *
     * @var WP_Error
     */
    public $errors;
    /**
     * Error type to return. Either 'bool' or 'wp_error'.
     *
     * @since 2.6.0
     *
     * @var string
     */
    public $error_type = 'bool';
    /**
     * Constructor method.
     *
     * @since 1.5.0
     *
     * @param int|bool $id Optional. The ID of a specific activity item.
     */
    public function __construct($id = \false)
    {
    }
    /**
     * Populate the object with data about the specific activity item.
     *
     * @since 1.0.0
     *
     * @global wpdb $wpdb WordPress database object.
     */
    public function populate()
    {
    }
    /**
     * Save the activity item to the database.
     *
     * @since 1.0.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @return WP_Error|bool True on success.
     */
    public function save()
    {
    }
    /** Static Methods ***************************************************/
    /**
     * Get activity items, as specified by parameters.
     *
     * @since 1.2.0
     * @since 2.4.0 Introduced the `$fields` parameter.
     * @since 2.9.0 Introduced the `$order_by` parameter.
     * @since 10.0.0 Introduced the `$count_total_only` parameter.
     * @since 11.0.0 Introduced the `$user_id__in` and `$user_id__not_in` parameters.
     *
     * @see BP_Activity_Activity::get_filter_sql() for a description of the
     *      'filter' parameter.
     * @see WP_Meta_Query::queries for a description of the 'meta_query'
     *      parameter format.
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param array $args {
     *     An array of arguments. All items are optional.
     *     @type int          $page              Which page of results to fetch. Using page=1 without per_page will result
     *                                           in no pagination. Default: 1.
     *     @type int|bool     $per_page          Number of results per page. Default: 25.
     *     @type int|bool     $max               Maximum number of results to return. Default: false (unlimited).
     *     @type string       $fields            Activity fields to return. Pass 'ids' to get only the activity IDs.
     *                                           'all' returns full activity objects.
     *     @type string       $sort              ASC or DESC. Default: 'DESC'.
     *     @type string       $order_by          Column to order results by.
     *     @type array        $exclude           Array of activity IDs to exclude. Default: false.
     *     @type array        $in                Array of ids to limit query by (IN). Default: false.
     *     @type array        $meta_query        Array of meta_query conditions. See WP_Meta_Query::queries.
     *     @type array        $date_query        Array of date_query conditions. See first parameter of
     *                                           WP_Date_Query::__construct().
     *     @type array        $filter_query      Array of advanced query conditions. See BP_Activity_Query::__construct().
     *     @type string|array $scope             Pre-determined set of activity arguments.
     *     @type array        $filter            See BP_Activity_Activity::get_filter_sql().
     *     @type array        $user_id__in       An array of user ids to include. Activity posted by users matching one of these
     *                                           user ids will be included in results. Default empty array.
     *     @type array        $user_id__not_in   An array of user ids to exclude. Activity posted by users matching one of these
     *                                           user ids will not be included in results. Default empty array.
     *     @type string       $search_terms      Limit results by a search term. Default: false.
     *     @type bool         $display_comments  Whether to include activity comments. Default: false.
     *     @type bool         $show_hidden       Whether to show items marked hide_sitewide. Default: false.
     *     @type string       $spam              Spam status. Default: 'ham_only'.
     *     @type bool         $update_meta_cache Whether to pre-fetch metadata for queried activity items. Default: true.
     *     @type string|bool  $count_total       If true, an additional DB query is run to count the total activity items
     *                                           for the query. Default: false.
     *     @type bool         $count_total_only  If true, only the DB query to count the total activity items is run.
     *                                           Default: false.
     * }
     * @return array The array returned has two keys:
     *               - 'total' is the count of located activities
     *               - 'activities' is an array of the located activities
     */
    public static function get($args = array())
    {
    }
    /**
     * Convert activity IDs to activity objects, as expected in template loop.
     *
     * @since 2.0.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param array $activity_ids Array of activity IDs.
     * @return array
     */
    protected static function get_activity_data($activity_ids = array())
    {
    }
    /**
     * Append xProfile fullnames to an activity array.
     *
     * @since 2.0.0
     *
     * @param array $activities Activities array.
     * @return array
     */
    protected static function append_user_fullnames($activities)
    {
    }
    /**
     * Pre-fetch data for objects associated with activity items.
     *
     * Activity items are associated with users, and often with other
     * BuddyPress data objects. Here, we pre-fetch data about these
     * associated objects, so that inline lookups - done primarily when
     * building action strings - do not result in excess database queries.
     *
     * The only object data required for activity component activity types
     * (activity_update and activity_comment) is related to users, and that
     * info is fetched separately in BP_Activity_Activity::get_activity_data().
     * So this method contains nothing but a filter that allows other
     * components, such as bp-friends and bp-groups, to hook in and prime
     * their own caches at the beginning of an activity loop.
     *
     * @since 2.0.0
     *
     * @param array $activities Array of activities.
     * @return array $activities Array of activities.
     */
    protected static function prefetch_object_data($activities)
    {
    }
    /**
     * Generate action strings for the activities located in BP_Activity_Activity::get().
     *
     * If no string can be dynamically generated for a given item
     * (typically because the activity type has not been properly
     * registered), the static 'action' value pulled from the database will
     * be left in place.
     *
     * @since 2.0.0
     *
     * @param array $activities Array of activities.
     * @return array
     */
    protected static function generate_action_strings($activities)
    {
    }
    /**
     * Get the SQL for the 'meta_query' param in BP_Activity_Activity::get().
     *
     * We use WP_Meta_Query to do the heavy lifting of parsing the
     * meta_query array and creating the necessary SQL clauses. However,
     * since BP_Activity_Activity::get() builds its SQL differently than
     * WP_Query, we have to alter the return value (stripping the leading
     * AND keyword from the 'where' clause).
     *
     * @since 1.8.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param array $meta_query An array of meta_query filters. See the
     *                          documentation for WP_Meta_Query for details.
     * @return array $sql_array 'join' and 'where' clauses.
     */
    public static function get_meta_query_sql($meta_query = array())
    {
    }
    /**
     * Get the SQL for the 'date_query' param in BP_Activity_Activity::get().
     *
     * We use BP_Date_Query, which extends WP_Date_Query, to do the heavy lifting
     * of parsing the date_query array and creating the necessary SQL clauses.
     *
     * @since 2.1.0
     *
     * @param array $date_query An array of date_query parameters. See the
     *                          documentation for the first parameter of WP_Date_Query.
     * @return string
     */
    public static function get_date_query_sql($date_query = array())
    {
    }
    /**
     * Get the SQL for the 'scope' param in BP_Activity_Activity::get().
     *
     * A scope is a predetermined set of activity arguments.  This method is used
     * to grab these activity arguments and override any existing args if needed.
     *
     * Can handle multiple scopes.
     *
     * @since 2.2.0
     *
     * @param  mixed $scope  The activity scope. Accepts string or array of scopes.
     * @param  array $r      Current activity arguments. Same as those of BP_Activity_Activity::get(),
     *                       but merged with defaults.
     * @return false|array 'sql' WHERE SQL string and 'override' activity args.
     */
    public static function get_scope_query_sql($scope = \false, $r = array())
    {
    }
    /**
     * In BuddyPress 1.2.x, this was used to retrieve specific activity stream items (for example, on an activity's permalink page).
     *
     * As of 1.5.x, use BP_Activity_Activity::get() with an 'in' parameter instead.
     *
     * @since 1.2.0
     *
     * @deprecated 1.5
     * @deprecated Use BP_Activity_Activity::get() with an 'in' parameter instead.
     *
     * @param mixed    $activity_ids     Array or comma-separated string of activity IDs to retrieve.
     * @param int|bool $max              Maximum number of results to return. (Optional; default is no maximum).
     * @param int      $page             The set of results that the user is viewing. Used in pagination. (Optional; default is 1).
     * @param int      $per_page         Specifies how many results per page. Used in pagination. (Optional; default is 25).
     * @param string   $sort             MySQL column sort; ASC or DESC. (Optional; default is DESC).
     * @param bool     $display_comments Retrieve an activity item's associated comments or not. (Optional; default is false).
     * @return array
     */
    public static function get_specific($activity_ids, $max = \false, $page = 1, $per_page = 25, $sort = 'DESC', $display_comments = \false)
    {
    }
    /**
     * Get the first activity ID that matches a set of criteria.
     *
     * @since 1.2.0
     * @since 10.0.0 Parameters were made optional.
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param array $args {
     *     An array of arguments. All items are optional.
     *     @type int    $user_id           User ID to filter by.
     *     @type string $component         Component to filter by.
     *     @type string $type              Activity type to filter by.
     *     @type int    $item_id           Associated item to filter by.
     *     @type int    $secondary_item_id Secondary associated item to filter by.
     *     @type string $action            Action to filter by.
     *     @type string $content           Content to filter by.
     *     @type string $date_recorded     Date to filter by.
     * }
     * @return int|false Activity ID on success, false if none is found.
     */
    public static function get_id($args = array())
    {
    }
    /**
     * Delete activity items from the database.
     *
     * To delete a specific activity item, pass an 'id' parameter.
     * Otherwise use the filters.
     *
     * @since 1.2.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param array $args {
     *     @int    $id                Optional. The ID of a specific item to delete.
     *     @string $action            Optional. The action to filter by.
     *     @string $content           Optional. The content to filter by.
     *     @string $component         Optional. The component name to filter by.
     *     @string $type              Optional. The activity type to filter by.
     *     @string $primary_link      Optional. The primary URL to filter by.
     *     @int    $user_id           Optional. The user ID to filter by.
     *     @int    $item_id           Optional. The associated item ID to filter by.
     *     @int    $secondary_item_id Optional. The secondary associated item ID to filter by.
     *     @string $date_recorded     Optional. The date to filter by.
     *     @int    $hide_sitewide     Optional. Default: false.
     * }
     * @return array|bool An array of deleted activity IDs on success, false on failure.
     */
    public static function delete($args = array())
    {
    }
    /**
     * Delete the comments associated with a set of activity items.
     *
     * This method is no longer used by BuddyPress, and it is recommended not to
     * use it going forward, and use BP_Activity_Activity::delete() instead.
     *
     * @since 1.2.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @deprecated 2.3.0
     *
     * @param array $activity_ids Activity IDs whose comments should be deleted.
     * @param bool  $delete_meta  Should we delete the activity meta items for these comments.
     * @return bool
     */
    public static function delete_activity_item_comments($activity_ids = array(), $delete_meta = \true)
    {
    }
    /**
     * Delete the meta entries associated with a set of activity items.
     *
     * @since 1.2.0
     *
     * @param array $activity_ids Activity IDs whose meta should be deleted.
     * @return bool
     */
    public static function delete_activity_meta_entries($activity_ids = array())
    {
    }
    /**
     * Append activity comments to their associated activity items.
     *
     * @since 1.2.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param array  $activities Activities to fetch comments for.
     * @param string $spam       Optional. 'ham_only' (default), 'spam_only' or 'all'.
     * @return array The updated activities with nested comments.
     */
    public static function append_comments($activities, $spam = 'ham_only')
    {
    }
    /**
     * Get activity comments that are associated with a specific activity ID.
     *
     * @since 1.2.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param int    $activity_id         Activity ID to fetch comments for.
     * @param int    $left                Left-most node boundary.
     * @param int    $right               Right-most node boundary.
     * @param string $spam                Optional. 'ham_only' (default), 'spam_only' or 'all'.
     * @param int    $top_level_parent_id Optional. The id of the root-level parent activity item.
     * @return array The updated activities with nested comments.
     */
    public static function get_activity_comments($activity_id, $left, $right, $spam = 'ham_only', $top_level_parent_id = 0)
    {
    }
    /**
     * Rebuild nested comment tree under an activity or activity comment.
     *
     * @since 1.2.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param int $parent_id ID of an activity or activity comment.
     * @param int $left      Node boundary start for activity or activity comment.
     * @return int Right Node boundary of activity or activity comment.
     */
    public static function rebuild_activity_comment_tree($parent_id, $left = 1)
    {
    }
    /**
     * Get child comments of an activity or activity comment.
     *
     * @since 1.2.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param int $parent_id ID of an activity or activity comment.
     * @return object Numerically indexed array of child comments.
     */
    public static function get_child_comments($parent_id)
    {
    }
    /**
     * Get a list of components that have recorded activity associated with them.
     *
     * @since 1.2.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param bool $skip_last_activity If true, components will not be
     *                                 included if the only activity type associated with them is
     *                                 'last_activity'. (Since 2.0.0, 'last_activity' is stored in
     *                                 the activity table, but these items are not full-fledged
     *                                 activity items.) Default: true.
     * @return array List of component names.
     */
    public static function get_recorded_components($skip_last_activity = \true)
    {
    }
    /**
     * Get sitewide activity items for use in an RSS feed.
     *
     * @since 1.0.0
     *
     * @param int $limit Optional. Number of items to fetch. Default: 35.
     * @return array $activity_feed List of activity items, with RSS data added.
     */
    public static function get_sitewide_items_for_feed($limit = 35)
    {
    }
    /**
     * Create SQL IN clause for filter queries.
     *
     * @since 1.5.0
     *
     * @see BP_Activity_Activity::get_filter_sql()
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param string     $field The database field.
     * @param array|bool $items The values for the IN clause, or false when none are found.
     * @return string|false
     */
    public static function get_in_operator_sql($field, $items)
    {
    }
    /**
     * Create filter SQL clauses.
     *
     * @since 1.5.0
     *
     * @param array $filter_array {
     *     Fields and values to filter by.
     *
     *     @type array|string|int $user_id      User ID(s).
     *     @type array|string     $object       Corresponds to the 'component'
     *                                          column in the database.
     *     @type array|string     $action       Corresponds to the 'type' column
     *                                          in the database.
     *     @type array|string|int $primary_id   Corresponds to the 'item_id'
     *                                          column in the database.
     *     @type array|string|int $secondary_id Corresponds to the
     *                                          'secondary_item_id' column in the database.
     *     @type int              $offset       Return only those items with an ID greater
     *                                          than the offset value.
     *     @type int              $offset_lower Return only those items with an ID lower
     *                                          than the offset value.
     *     @type string           $since        Return only those items that have a
     *                                          date_recorded value greater than a
     *                                          given MySQL-formatted date.
     * }
     * @return string The filter clause, for use in a SQL query.
     */
    public static function get_filter_sql($filter_array)
    {
    }
    /**
     * Get the date/time of last recorded activity.
     *
     * @since 1.2.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @return string ISO timestamp.
     */
    public static function get_last_updated()
    {
    }
    /**
     * Get favorite count for a given user.
     *
     * @since 1.2.0
     *
     * @param int $user_id The ID of the user whose favorites you're counting.
     * @return int $value A count of the user's favorites.
     */
    public static function total_favorite_count($user_id)
    {
    }
    /**
     * Check whether an activity item exists with a given string content.
     *
     * @since 1.1.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param string $content The content to filter by.
     * @return int|false The ID of the first matching item if found, otherwise false.
     */
    public static function check_exists_by_content($content)
    {
    }
    /**
     * Hide all activity for a given user.
     *
     * @since 1.2.0
     *
     * @global wpdb $wpdb WordPress database object.
     *
     * @param int $user_id The ID of the user whose activity you want to mark hidden.
     * @return mixed
     */
    public static function hide_all_for_user($user_id)
    {
    }
}
/**
 * The main activity template loop class.
 *
 * This is responsible for loading a group of activity items and displaying them.
 *
 * @since 1.0.0
 */
class BP_Activity_Template
{
    /**
     * The loop iterator.
     *
     * @since 1.0.0
     * @var int
     */
    public $current_activity = -1;
    /**
     * The activity count.
     *
     * @since 1.0.0
     * @var int
     */
    public $activity_count;
    /**
     * The total activity count.
     *
     * @since 1.0.0
     * @var int
     */
    public $total_activity_count;
    /**
     * Array of activities located by the query.
     *
     * @since 1.0.0
     * @var array
     */
    public $activities;
    /**
     * The activity object currently being iterated on.
     *
     * @since 1.0.0
     * @var object
     */
    public $activity;
    /**
     * A flag for whether the loop is currently being iterated.
     *
     * @since 1.0.0
     * @var bool
     */
    public $in_the_loop;
    /**
     * URL parameter key for activity pagination. Default: 'acpage'.
     *
     * @since 2.1.0
     * @var string
     */
    public $pag_arg;
    /**
     * The page number being requested.
     *
     * @since 1.0.0
     * @var int
     */
    public $pag_page;
    /**
     * The number of items being requested per page.
     *
     * @since 1.0.0
     * @var int
     */
    public $pag_num;
    /**
     * An HTML string containing pagination links.
     *
     * @since 1.0.0
     * @var string
     */
    public $pag_links;
    /**
     * The displayed user's full name.
     *
     * @since 1.0.0
     * @var string
     */
    public $full_name;
    /**
     * Check if post/comment replies are disabled.
     *
     * @since 1.0.0
     * @var bool
     */
    public $disable_blogforum_replies;
    /**
     * If more items are available.
     *
     * @since 1.0.0
     * @var bool
     */
    public $has_more_items;
    /**
     * An array of the logged in user's favorite activities.
     *
     * @since 1.0.0
     * @var array
     */
    public $my_favs;
    /**
     * An array of parent activities.
     *
     * @since 1.0.0
     * @var array
     */
    public $activity_parents;
    /**
     * Constructor method.
     *
     * The arguments passed to this class constructor are of the same
     * format as {@link BP_Activity_Activity::get()}.
     *
     * @since 1.5.0
     *
     * @see BP_Activity_Activity::get() for a description of the argument
     *      structure, as well as default values.
     *
     * @param array $args {
     *     Array of arguments. Supports all arguments from
     *     BP_Activity_Activity::get(), as well as 'page_arg' and
     *     'include'. Default values for 'per_page' and 'display_comments'
     *     differ from the originating function, and are described below.
     *     @type string      $page_arg         The string used as a query parameter in
     *                                         pagination links. Default: 'acpage'.
     *     @type array|bool  $include          Pass an array of activity IDs to
     *                                         retrieve only those items, or false to noop the 'include'
     *                                         parameter. 'include' differs from 'in' in that 'in' forms
     *                                         an IN clause that works in conjunction with other filters
     *                                         passed to the function, while 'include' is interpreted as
     *                                         an exact list of items to retrieve, which skips all other
     *                                         filter-related parameters. Default: false.
     *     @type int|bool    $per_page         Default: 20.
     *     @type string|bool $display_comments Default: 'threaded'.
     * }
     */
    public function __construct($args)
    {
    }
    /**
     * Whether there are activity items available in the loop.
     *
     * @since 1.0.0
     *
     * @see bp_has_activities()
     *
     * @return bool True if there are items in the loop, otherwise false.
     */
    function has_activities()
    {
    }
    /**
     * Set up the next activity item and iterate index.
     *
     * @since 1.0.0
     *
     * @return object The next activity item to iterate over.
     */
    public function next_activity()
    {
    }
    /**
     * Rewind the posts and reset post index.
     *
     * @since 1.0.0
     */
    public function rewind_activities()
    {
    }
    /**
     * Whether there are activity items left in the loop to iterate over.
     *
     * This method is used by {@link bp_activities()} as part of the while loop
     * that controls iteration inside the activities loop, eg:
     *     while ( bp_activities() ) { ...
     *
     * @since 1.0.0
     *
     * @see bp_activities()
     *
     * @return bool True if there are more activity items to show,
     *              otherwise false.
     */
    public function user_activities()
    {
    }
    /**
     * Set up the current activity item inside the loop.
     *
     * Used by {@link bp_the_activity()} to set up the current activity item
     * data while looping, so that template tags used during that iteration
     * make reference to the current activity item.
     *
     * @since 1.0.0
     *
     * @see bp_the_activity()
     */
    public function the_activity()
    {
    }
}
/**
 * Main Activity Class.
 *
 * @since 1.5.0
 */
#[\AllowDynamicProperties]
class BP_Activity_Component extends \BP_Component
{
    /**
     * Start the activity component setup process.
     *
     * @since 1.5.0
     */
    public function __construct()
    {
    }
    /**
     * Include component files.
     *
     * @since 1.5.0
     *
     * @see BP_Component::includes() for a description of arguments.
     *
     * @param array $includes See BP_Component::includes() for a description.
     */
    public function includes($includes = array())
    {
    }
    /**
     * Late includes method.
     *
     * Only load up certain code when on specific pages.
     *
     * @since 3.0.0
     */
    public function late_includes()
    {
    }
    /**
     * Set up component global variables.
     *
     * The BP_ACTIVITY_SLUG constant is deprecated.
     *
     * @since 1.5.0
     *
     * @see BP_Component::setup_globals() for a description of arguments.
     *
     * @param array $args See BP_Component::setup_globals() for a description.
     */
    public function setup_globals($args = array())
    {
    }
    /**
     * Register component navigation.
     *
     * @since 12.0.0
     *
     * @see `BP_Component::register_nav()` for a description of arguments.
     *
     * @param array $main_nav Optional. See `BP_Component::register_nav()` for description.
     * @param array $sub_nav  Optional. See `BP_Component::register_nav()` for description.
     */
    public function register_nav($main_nav = array(), $sub_nav = array())
    {
    }
    /**
     * Set up the component entries in the WordPress Admin Bar.
     *
     * @since 1.5.0
     *
     * @see BP_Component::setup_nav() for a description of the $wp_admin_nav
     *      parameter array.
     *
     * @param array $wp_admin_nav See BP_Component::setup_admin_bar() for a
     *                            description.
     */
    public function setup_admin_bar($wp_admin_nav = array())
    {
    }
    /**
     * Set up the title for pages and <title>.
     *
     * @since 1.5.0
     *
     */
    public function setup_title()
    {
    }
    /**
     * Setup cache groups.
     *
     * @since 2.2.0
     */
    public function setup_cache_groups()
    {
    }
    /**
     * Parse the WP_Query and eventually display the component's directory or single item.
     *
     * @since 12.0.0
     *
     * @param WP_Query $query Required. See BP_Component::parse_query() for
     *                        description.
     */
    public function parse_query($query)
    {
    }
    /**
     * Init the BP REST API.
     *
     * @since 5.0.0
     *
     * @param array $controllers Optional. See BP_Component::rest_api_init() for
     *                           description.
     */
    public function rest_api_init($controllers = array())
    {
    }
    /**
     * Register the BP Activity Blocks.
     *
     * @since 7.0.0
     * @since 12.0.0 Use the WP Blocks API v2.
     *
     * @param array $blocks Optional. See BP_Component::blocks_init() for
     *                      description.
     */
    public function blocks_init($blocks = array())
    {
    }
}
/**
 * oEmbed handler to respond and render single activity items.
 *
 * @since 2.6.0
 */
class BP_Activity_oEmbed_Extension extends \BP_Core_oEmbed_Extension
{
    /**
     * Custom oEmbed slug endpoint.
     *
     * @since 2.6.0
     *
     * @var string
     */
    public $slug_endpoint = 'activity';
    /**
     * Custom hooks.
     *
     * @since 2.6.0
     */
    protected function custom_hooks()
    {
    }
    /**
     * Add custom endpoint arguments.
     *
     * Currently, includes 'hide_media'.
     *
     * @since 2.6.0
     *
     * @return array
     */
    protected function set_route_args()
    {
    }
    /**
     * Output our custom embed template part.
     *
     * @since 2.6.0
     */
    protected function content()
    {
    }
    /**
     * Check if we're on our single activity page.
     *
     * @since 2.6.0
     *
     * @return bool
     */
    protected function is_page()
    {
    }
    /**
     * Validates the URL to determine if the activity item is valid.
     *
     * @since 2.6.0
     *
     * @param  string   $url The URL to check.
     * @return int|bool Activity ID on success; boolean false on failure.
     */
    protected function validate_url_to_item_id($url)
    {
    }
    /**
     * Sets the oEmbed response data for our activity item.
     *
     * @since 2.6.0
     *
     * @param  int $item_id The activity ID.
     * @return array
     */
    protected function set_oembed_response_data($item_id)
    {
    }
    /**
     * Sets a custom <blockquote> for our oEmbed fallback HTML.
     *
     * @since 2.6.0
     *
     * @global BP_Activity_Template $activities_template The Activity template loop.
     *
     * @param  int $item_id The activity ID.
     * @return string
     */
    protected function set_fallback_html($item_id)
    {
    }
    /**
     * Sets a custom <iframe> title for our oEmbed item.
     *
     * @since 2.6.0
     *
     * @param  int $item_id The activity ID
     * @return string
     */
    protected function set_iframe_title($item_id)
    {
    }
    /**
     * Use our custom <iframe> sandbox attribute in our oEmbed response.
     *
     * WordPress sets the <iframe> sandbox attribute to 'allow-scripts' regardless
     * of whatever the oEmbed response is in {@link wp_filter_oembed_result()}. We
     * need to add back our custom sandbox value so links will work.
     *
     * @since 2.6.0
     *
     * @see BP_Activity_Component::modify_iframe() where our custom sandbox value is set.
     *
     * @param string $result The oEmbed HTML result.
     * @param object $data   A data object result from an oEmbed provider.
     * @param string $url    The URL of the content to be embedded.
     * @return string
     */
    public function use_custom_iframe_sandbox_attribute($result, $data, $url)
    {
    }
    /**
     * Modify various IFRAME-related items if embeds are allowed.
     *
     * HTML modified:
     *  - Add sandbox="allow-top-navigation" attribute. This allows links to work
     *    within the iframe sandbox attribute.
     *
     * JS modified:
     *  - Remove IFRAME height restriction of 1000px. Fixes long embed items being
     *    truncated.
     *
     * @since 2.6.0
     *
     * @param  string $retval Current embed HTML.
     * @return string
     */
    public function modify_iframe($retval)
    {
    }
    /**
     * Do stuff when our oEmbed activity header template part is loading.
     *
     * Currently, removes wpautop() from the bp_activity_action() function.
     *
     * @since 2.6.0
     *
     * @param string $slug Template part slug requested.
     * @param string $name Template part name requested.
     */
    public function on_activity_header($slug, $name)
    {
    }
    /**
     * Prints the markup for the activity embed comments button.
     *
     * Basically a copy of {@link print_embed_comments_button()}, but modified for
     * the BP activity component.
     *
     * @since 2.6.0
     */
    public function embed_comments_button()
    {
    }
}
/**
 * List table class for the Activity component admin page.
 *
 * @since 1.6.0
 */
class BP_Activity_List_Table extends \WP_List_Table
{
    /**
     * What type of view is being displayed?
     *
     * E.g. "all", "pending", "approved", "spam"...
     *
     * @since 1.6.0
     * @var string $view
     */
    public $view = 'all';
    /**
     * How many activity items have been marked as spam.
     *
     * @since 1.6.0
     * @var int $spam_count
     */
    public $spam_count = 0;
    /**
     * Total number of activities.
     *
     * @since 6.0.0
     * @var int $all_count
     */
    public $all_count = 0;
    /**
     * Store activity-to-user-ID mappings for use in the In Response To column.
     *
     * @since 1.6.0
     * @var array $activity_user_id
     */
    protected $activity_user_id = array();
    /**
     * If users can comment on post and comment activity items.
     *
     * @link https://buddypress.trac.wordpress.org/ticket/6277
     *
     * @since 2.2.2
     * @var bool $disable_blogforum_comments
     */
    public $disable_blogforum_comments = \false;
    /**
     * Constructor.
     *
     * @since 1.6.0
     */
    public function __construct()
    {
    }
    /**
     * Handle filtering of data, sorting, pagination, and any other data manipulation prior to rendering.
     *
     * @since 1.6.0
     */
    function prepare_items()
    {
    }
    /**
     * Get an array of all the columns on the page.
     *
     * @since 1.6.0
     *
     * @return array Column headers.
     */
    function get_column_info()
    {
    }
    /**
     * Get name of default primary column
     *
     * @since 2.3.3
     *
     * @return string
     */
    protected function get_default_primary_column_name()
    {
    }
    /**
     * Display a message on screen when no items are found (e.g. no search matches).
     *
     * @since 1.6.0
     */
    function no_items()
    {
    }
    /**
     * Output the Activity data table.
     *
     * @since 1.6.0
     */
    function display()
    {
    }
    /**
     * Generate content for a single row of the table.
     *
     * @since 1.6.0
     *
     * @param object $item The current item.
     */
    function single_row($item)
    {
    }
    /**
     * Get the list of views available on this table (e.g. "all", "spam").
     *
     * @since 1.6.0
     */
    function get_views()
    {
    }
    /**
     * Get bulk actions.
     *
     * @since 1.6.0
     *
     * @return array Key/value pairs for the bulk actions dropdown.
     */
    public function get_bulk_actions()
    {
    }
    /**
     * Get the table column titles.
     *
     * @since 1.6.0
     *
     * @see WP_List_Table::single_row_columns()
     *
     * @return array The columns to appear in the Activity list table.
     */
    function get_columns()
    {
    }
    /**
     * Get the column names for sortable columns.
     *
     * @since 1.6.0
     *
     * @return array The columns that can be sorted on the Activity screen.
     */
    public function get_sortable_columns()
    {
    }
    /**
     * Markup for the "filter" part of the form (i.e. which activity type to display).
     *
     * @since 1.6.0
     *
     * @param string $which 'top' or 'bottom'.
     */
    function extra_tablenav($which)
    {
    }
    /**
     * Override WP_List_Table::row_actions().
     *
     * Basically a duplicate of the row_actions() method, but removes the
     * unnecessary <button> addition.
     *
     * @since 2.3.3
     * @since 2.3.4 Visibility set to public for compatibility with WP < 4.0.0.
     *
     * @param array $actions The list of actions.
     * @param bool  $always_visible Whether the actions should be always visible.
     * @return string
     */
    public function row_actions($actions, $always_visible = \false)
    {
    }
    /**
     * Checkbox column markup.
     *
     * @since 1.6.0
     *
     * @see WP_List_Table::single_row_columns()
     *
     * @param array $item A singular item (one full row).
     */
    function column_cb($item)
    {
    }
    /**
     * Author column markup.
     *
     * @since 1.6.0
     *
     * @see WP_List_Table::single_row_columns()
     *
     * @param array $item A singular item (one full row).
     */
    function column_author($item)
    {
    }
    /**
     * Action column markup.
     *
     * @since 2.0.0
     *
     * @see WP_List_Table::single_row_columns()
     *
     * @param array $item A singular item (one full row).
     */
    function column_action($item)
    {
    }
    /**
     * Content column, and "quick admin" rollover actions.
     *
     * Called "comment" in the CSS so we can re-use some WP core CSS.
     *
     * @since 1.6.0
     *
     * @see WP_List_Table::single_row_columns()
     *
     * @param array $item A singular item (one full row).
     */
    function column_comment($item)
    {
    }
    /**
     * "In response to" column markup.
     *
     * @since 1.6.0
     *
     * @see WP_List_Table::single_row_columns()
     *
     * @param array $item A singular item (one full row).
     */
    function column_response($item)
    {
    }
    /**
     * Allow plugins to add their custom column.
     *
     * @since 2.4.0
     *
     * @param array  $item        Information about the current row.
     * @param string $column_name The column name.
     * @return string
     */
    public function column_default($item = array(), $column_name = '')
    {
    }
    /**
     * Get the user id associated with a given activity item.
     *
     * Wraps bp_activity_get_specific(), with some additional logic for
     * avoiding duplicate queries.
     *
     * @since 1.6.0
     *
     * @param int $activity_id Activity ID to retrieve User ID for.
     * @return int User ID of the activity item in question.
     */
    protected function get_activity_user_id($activity_id)
    {
    }
    /**
     * Checks if an activity item can be replied to.
     *
     * This method merges functionality from {@link bp_activity_can_comment()} and
     * {@link bp_blogs_disable_activity_commenting()}. This is done because the activity
     * list table doesn't use a BuddyPress activity loop, which prevents those
     * functions from working as intended.
     *
     * @since 2.0.0
     * @since 2.5.0 Include Post type activities types
     *
     * @param array $item An array version of the BP_Activity_Activity object.
     * @return bool $can_comment
     */
    protected function can_comment($item)
    {
    }
    /**
     * Flatten the activity array.
     *
     * In some cases, BuddyPress gives us a structured tree of activity
     * items plus their comments. This method converts it to a flat array.
     *
     * @since 1.6.0
     *
     * @param array $tree Source array.
     * @return array Flattened array.
     */
    public static function flatten_activity_array($tree)
    {
    }
}
/**
 * The main theme compat class for BuddyPress Activity.
 *
 * This class sets up the necessary theme compatibility actions to safely output
 * activity template parts to the_title and the_content areas of a theme.
 *
 * @since 1.7.0
 */
class BP_Activity_Theme_Compat
{
    /**
     * Set up the activity component theme compatibility.
     *
     * @since 1.7.0
     */
    public function __construct()
    {
    }
    /**
     * Set up the theme compatibility hooks, if we're looking at an activity page.
     *
     * @since 1.7.0
     */
    public function is_activity()
    {
    }
    /** Directory *************************************************************/
    /**
     * Add template hierarchy to theme compat for the activity directory page.
     *
     * This is to mirror how WordPress has {@link https://codex.wordpress.org/Template_Hierarchy template hierarchy}.
     *
     * @since 1.8.0
     *
     * @param string $templates The templates from bp_get_theme_compat_templates().
     * @return array $templates Array of custom templates to look for.
     */
    public function directory_template_hierarchy($templates)
    {
    }
    /**
     * Update the global $post with directory data.
     *
     * @since 1.7.0
     */
    public function directory_dummy_post()
    {
    }
    /**
     * Filter the_content with the groups index template part.
     *
     * @since 1.7.0
     */
    public function directory_content()
    {
    }
    /** Single ****************************************************************/
    /**
     * Add custom template hierarchy to theme compat for activity permalink pages.
     *
     * This is to mirror how WordPress has {@link https://codex.wordpress.org/Template_Hierarchy template hierarchy}.
     *
     * @since 1.8.0
     *
     * @param string $templates The templates from bp_get_theme_compat_templates().
     * @return array $templates Array of custom templates to look for.
     */
    public function single_template_hierarchy($templates)
    {
    }
    /**
     * Update the global $post with the displayed user's data.
     *
     * @since 1.7.0
     */
    public function single_dummy_post()
    {
    }
    /**
     * Filter the_content with the members' activity permalink template part.
     *
     * @since 1.7.0
     */
    public function single_dummy_content()
    {
    }
}