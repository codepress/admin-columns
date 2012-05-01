=== Codepress Admin Columns ===
Contributors: codepress, tschutter
Tags: plugins, wordpress, admin, column, columns, custom columns, custom fields, image, dashboard, sortable, filters, posts, media, users, pages, posttypes, manage columns, wp-admin
Requires at least: 3.1
Tested up to: 3.3.1
Stable tag: 1.4.3.1

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
* Custom Fields

= User Columns =

You can also change the User Columns. The following user columns are added:

* User ID
* First name
* Last name
* Url
* Register date
* Biographical Info ( description )
* User Custom Fields
* Number of Posts Types

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

= Sortable Custom Columns for all Screens =

All of the new columns will have support for sorting with the <a href="http://www.codepress.nl/plugins/codepress-admin-columns/sortorder-addon/">sorting addon</a>.

By default WordPress let's you only sort by Title, Date, Comments and Author. This will make you be able to <strong>sort by ALL columns of ANY type</strong>.

= Third party plugin support =

It will work nice with other plugins and support their additional custom columns. A few examples of plugins that are supported: WordPress SEO by Yoast (Robots Meta), Post Admin Shortcuts (Pin), WP Show IDs (ID) and User Access Manager (Access), Co-Authors Plus and Advanced Custom Fields.

= Translations = 

If you like to contrinute a language, please send them to <a href="mailto:info@codepress.nl">info@codepress.nl</a>.

* Polish (pl_PL) - Thanks for contributing the polish language goes to Bartosz


= Upcoming releases =

The next major release (1.5) will have the option to set default sorting per screen.


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

== Screenshots ==

1. Settings page for Post(type) columns.
2. Posts Screen with the customized sortable columns.
3. Settings page for the Media Library columns.
4. Media Screen with the customized sortable columns.
5. Settings page for Users columns.
6. Users Screen with the customized sortable columns.
7. Settings page showing the different displaying types for custom field.

== Changelog ==

= 1.4.4 =
* added posts columns Last Modified and Comment count
* added media columns for EXIF and IPTC image data
* added custom fields columns to the Media Library
* given column values it's own class
* added bug fix for sorting bookmarks/links
* added fix for possible php warning

= 1.4.3 =
* removed taxonomy filtering ( this will return in next patch with an option to show/hide )

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