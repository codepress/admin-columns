=== Codepress Admin Columns ===
Contributors: codepress, tschutter, davidmosterd
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZDZRSYLQ4Z76J
Tags: plugins, wordpress, admin, column, columns, custom columns, custom fields, image, dashboard, sortable, filters, posts, media, users, pages, posttypes, manage columns, wp-admin
Requires at least: 3.1
Tested up to: 3.5
Stable tag: 1.4.9

Customise columns on the administration screens for post(types), pages, media, comments, links and users with an easy to use drag-and-drop interface.

== Description ==

Completely customise the columns on the administration screens with a nice drag and drop interface.

By default, WordPress only shows a few built-in columns. This plugin will give you many additional columns. You will have full control over all columns for pages, posts, posttypes, media, links, comments and users.

Add or remove columns, change their label, change their width and reorder them.

= Post Types Columns  =

The following custom columns are added:

* Featured Image
* Excerpt
* Post Attachments
* Page Order
* Page Templates
* Post Formats
* Taxonomies
* ID
* Slug
* Sticky
* Word count
* Roles
* Status
* Number of Attachments
* Last Modified
* Comment count
* Before More Tag Content
* Custom Fields

= User Columns =

You can also change the User Columns. The following user columns are added:

* User ID
* First name
* Last name
* Url
* Register date
* Biographical Info ( description )
* Number of Posts Types
* User Custom Fields

Some of the user custom fields that are included: user level, capabilities, admin color, nickname... many more.

= Media Columns =

Customise the Media Library Columns. The following media columns are added:

* Media ID
* File name
* Height
* Width
* Dimensions ( width x height )
* EXIF and IPTC image data
* Description, Caption and Alternate tekst
* Mime-Type
* Media Custom Fields
* Filesize

= Comment Columns =

A lot more comment colums are added, here are a few examples:

* Avatar
* Author IP
* Agent
* ID
* Comment excerpt
* Comment Meta data

= Link Columns =

A few examples of added Link columns:

* ID
* Target
* Description
* Notes
* Owner

= Custom Fields =

With the custom field column you can display any custom field values. It can show its default value but also handle it as an image or icon. Thsese types are added:

* Image thumbnails
* Icons for Media Library items
* Excerpt
* Multiple Values
* Numeric value ( this also works for sorting by meta_value_num )
* Post Titles
* Usernames
* Checkmark Image ( for true or false values )

= Sortable Custom Columns for all Screens =

All of the new columns will have support for sorting with the <a href="http://www.codepress.nl/plugins/codepress-admin-columns/sortorder-addon/">sorting addon</a>.

By default WordPress let's you only sort by Title, Date, Comments and Author. This will make you be able to <strong>sort by ALL columns of ANY type</strong>. (columns that are added by other plugins are not supported)

= Third party plugin support =

It will work nice with other plugins and support their additional custom columns. A few examples of plugins that are supported: WordPress SEO by Yoast (Robots Meta), Post Admin Shortcuts (Pin), WP Show IDs (ID) and User Access Manager (Access), Co-Authors Plus and Advanced Custom Fields.

= Translations =

If you like to contrinute a language, please send them to <a href="mailto:info@codepress.nl">info@codepress.nl</a>.

* Danish (da_DK) - Thanks for contributing the danish language goes to Morten Dalgaard Johansen
* German (de_DE) - Thanks for contributing the german language goes to Uli
* Polish (pl_PL) - Thanks for contributing the polish language goes to Bartosz
* French (fr_FR) - Thanks for contributing the french language goes to Alexandre Girard

= Upcoming releases =

* support for default sorting for users, links and comments

**Feedback**

You can leave any <a href='http://www.codepress.nl/plugins/codepress-admin-columns/feedback'>requests or feedback</a>.

**Related Links:**

* http://www.codepress.nl/plugins/codepress-admin-columns/

== Installation ==

1. Upload codepress-admin-columns to the /wp-content/plugins/ directory
2. Activate Codepress Admin Columns through the 'Plugins' menu in WordPress
3. Configure the plugin by going to the Admin Column settings that appears under the Settings menu.

== Frequently Asked Questions ==

= I have an idea for a great way to improve this plugin =

Great! I'd love to hear from you.
Leave your feedback at http://www.codepress.nl/plugins/codepress-admin-columns/feedback.

= How can I change the thumbnail size of images? =

You can use the build in filter to set your own thumbnail size. Just add this piece of code to your
theme's  functions.php.

To set a custom size use, for example 194 width by 63 height pixels:

`
<?php

// edit here: fill in your thumbnail height and width
$my_height = 63;
$my_width  = 194;
// stop editing

add_image_size( 'admin-columns', $my_width, $my_height, true );
add_filter('cpac_thumbnail_size', 'cb_cpac_thumbnail_size' );
function cb_cpac_thumbnail_size() {
	return 'admin-columns';
};
?>
`

**my columns thumbnails still have the wrong size**

If you want your already uploaded images to display the newly added size you will need to regenerate the thumbnail for them. Use this plugin to generate the newly added sized thumbnails: http://wordpress.org/extend/plugins/regenerate-thumbnails/.

= How can I enable the use of Hidden Custom Fields? =

I am currently working on settings page where you can enable this feature. In the meanwhile you can enable this by adding
this piece of code to your theme's functions.php:

`
<?php
add_filter('cpac_use_hidden_custom_fields', '__return_true'); // enables the use hidden custom fields
?>
`

Now you can select your HIDDEN custom fields in de dropdown menu under "Custom Field:".

= How can I add the dropdown menu for taxonomy filtering? =

This will also be included in the upcoming settings page, in the meanwhile you can enable this by adding
this piece of code to your theme's functions.php:

`
<?php
add_filter( 'cpac-remove-filtering-columns', '__return_false' ); // add dropdown taxonomy filtering to the overview pages
?>
`

= How can I display a custom value in the Custom Fields Column? =

With this filter 'cpac_get_column_value_custom_field' you can control what the value will be for any Custom Field Column.

Filter explained:

* **$value** is the original value which would otherwise be displayed
* **$internal_field_key** is only used internally to store the column
* **$custom_field** is the name of your custom field
* **$type** will return either the posttype or if it is any other type it will return wp-comments, wp-links, wp-users, wp-media.
* **$object_id** will return the ID of the object.

For example if you have a custom posttype 'Demo' with a custom_field that is called 'city' and the result would be an integer '33'. You can change that integer '33' to Amsterdam.

`
<?php
function my_custom_field_value( $value, $internal_field_key, $custom_field, $type, $object_id )
{
	$my_post_type  = 'demo';
	$my_field_name = 'city';

	// make sure we have the correct posttype and fieldname
	if ( $my_post_type == $type && $my_field_name == $custom_field ) {

		if ( '33' == $value )
			$value = 'Amsterdam';

		elseif ( '34' == $value )
			$value = 'New York';
	}
	return $value;
}
add_filter( 'cpac_get_column_value_custom_field', 'my_custom_field_value', 10, 5 );
?>
`

== Screenshots ==

1. Settings page for Post(type) columns.
2. Posts Screen with the customized sortable columns.
3. Settings page for the Media Library columns.
4. Media Screen with the customized sortable columns.
5. Settings page for Users columns.
6. Users Screen with the customized sortable columns.
7. Settings page showing the different displaying types for custom field.

== Changelog ==

= 1.4.9
* fixed bug: thirdparty columns that were previous loaded through load-edit.php will now use do_action( 'cpac-get-default-columns-{$type}' )

= 1.4.8 =
* fixed bug: removed acf posttype placed by Advaced Custom Fields from settings menu
* fixed bug: removed bbPress posttypes topic, forum and reply from admin columns settings menu
* fixed bug: license key could not activate properly

= 1.4.7 =
* ready for WP 3.5
* added support for custom fields for Media
* added color to the custom field types
* fixed default sorting for Post(types) and Media
* fixed problem with different date formats in custom fields. all dates will parsed by strtotime() now.
* fixed bug which could trigger a conflict when saving the setting on other plugins
* fixed bug when returning an admin class atrribute
* improved perfomance on post count on user overview screen

= 1.4.6.4 =
* Added 'before more tag' column, which will show the content which is placed before the more-tag
* bug fix: file images will now also be displayed when they can not be resized.
* bug fix: the checkbox disappeared when resetting columns and resaving them.

= 1.4.6.3 =

* Added new custom field type: User by User ID
* Added values to filter 'cpac_get_column_value_custom_field' for better control of the output
* Added an example for above filter to FAQ section
* Added fix where trash posts did not show with the sorting addon activated

= 1.4.6.2 =

* bug fix with a static function which could cause an error in some cases
* added filter to enable taxonomy filtering. add this to your functions.php to enable taxonomy filtering: `add_filter( 'cpac-remove-filtering-columns', '__return_false' );`

= 1.4.6.1 =

* bug fix for possible warning when using Custompress ( props to scottsalisbury for the fix! )
* bug fix for sorting by postcount for users
* added 'Display Author As' column for post(types)
* added sorting support for 'Display Author As' column

= 1.4.6 =

* added german language ( thanks to Uli )
* added danish language ( thanks to Morten Dalgaard Johansen )
* added filter for setting thumbnail size ( see FAQ on how to use it )
* added support for hidden custom fields ( see FAQ on how to enable this )
* added fix for WordPress SEO by Yoast Columns

= 1.4.5.1 =

* removed taxonomy filtering ( will implement show/hide option )

= 1.4.5 =

* added french language ( thanks to Alexandre Girard )
* filtering by taxonomy ( only displays when column is used )
* added compatibility with woocommerce
* fix value media meta column ID
* fixed bug with sorting users by postcount
* added Actions column for Media (delete, view etc.)
* added Actions column for Link (delete, view etc.)
* added Actions column for Comments (delete, view etc.)
* added Wordcount column for Comments
* added Filesize column for Media ( supports sorting )
* added default sorting for posts ( remembers your last sorting, only with addon )
* added default sorting for media ( remembers your last sorting, only with addon )
* added filters to the result output

= 1.4.4 =

* added posts columns Last Modified and Comment count
* added media columns for EXIF and IPTC image data
* added custom fields columns to the Media Library
* given column values it's own class
* added bug fix for sorting bookmarks/links
* added fix for possible php warning

= 1.4.3 =

* removed taxonomy filtering

= 1.4.2 =

* added fix for unexpected output in the column value
* added fix for better 3rd party plugin support
* added column for Comment status
* added column for Ping/Trackback status
* added column for Posts Actions (delete, view etc.)
* added column for Users Actions (delete, view etc.)
* added sorting taxonomies ( only on first one )
* added bug fix for sorting
* added taxonomy filtering

= 1.4.1 =

* added polish translation, thanks to Bartosz.
* changed the license key validation proces
* removed non-breaking-space-character from column output

= 1.4 =

* added support for comment columns
* added support for link columns
* added links to taxonomies
* added sorting user custom fields
* added sorting to links columns
* added user columns so you can see how many articles an author has published of a certain post type
* added Textual help
* added the option to specify column width
* added role column to all posts screens
* added posts status column to all posts screens
* added image path to media library
* added added apply_filters('cpac-get-post-types', $post_types) to filter out certain post types
* added option to enter license key for activating sorting on ALL columns
* fixed a php5 warning
* fixed a conflict with the Co-Authors plugin

= 1.3 =

* added support for Media columns
* added Media columns: filename, width, height, dimensions, description, alt, caption and mime-type
* added date type to posts custom fields
* added title type to posts custom fields
* sorting has changed. when sorting; only results are shown which contain a value
* str_word_count is used for excerpts

= 1.2.1 =

* added word count sorting
* added attachment count sorting
* added template name sorting
* minor styling changes
* bug fix with sorting by slug
* bug fix with sorting by attachment

= 1.2 =

* added support for third party plugins
* added user custom fields
* added extra image check
* bug fix with javascript (jquery) enqueue

= 1.1.3 =

* added bug fix for WP3.3beta ( thanks to raonip and ronbme for pointing this out )

= 1.1.2 =

* added dutch translation

= 1.1.1 =

* Bug fix: path separator for require_once
* Added word count

= 1.1 =

* Added User Columns.
* Added before / after text for custom fields
* Added custom field type 'Numeric'.
* Added custom field sortables.
* Fixed domain path
* Fixed settings link

= 1.0 =

* Initial release.