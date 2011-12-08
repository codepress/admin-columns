=== Codepress Admin Columns ===
Contributors: codepress, tschutter
Tags: plugins, wordpress, admin, column, columns, custom columns, custom fields, image, dashboard, sortable, filters, wp-admin
Requires at least: 3.1
Tested up to: 3.3-RC1
Stable tag: 1.2

== Description ==

Completely customise your Custom Columns with a nice drag and drop interface. 

By default, WordPress only shows a few built-in columns. This plugin will give you many additional columns and you will have full control over all custom columns for pages, posts, posttypes and users. You can add or remove columns, change their label and reorder them.

= Custom Columns  =

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
* Custom Fields

= Custom Fields =

With the custom field column you can display any custom field values. It can show its default value but also handle it as an image or icon. Thsese types are added:

* Image thumbnails
* Icons for Media Library items
* Excerpt
* Multiple Values
* Numeric value ( this also works for sorting by meta_value_num )

= Sortable Custom Columns =

A nice feature is that it will make some of the new columns support sorting. By default WordPress let's you sort by Title, Date, Comments and Author. This will make you be able to sort by:

* ID
* page order
* slug 
* page template
* custom fields ( both meta_value and meta_value_num are supported )

= User Columns =

You can also change the User Columns. The following user columns are added:

* User ID
* First name
* Last name
* Url
* Register date
* Biographical Info ( description )
* User Custom Fields

Some of the user custom fields that are included: user level, capabilities, admin color, nickname... many more.


= Third party plugin support =

It will work nice with other plugins and support their additional custom columns. A few examples of plugins that are supported: WordPress SEO by Yoast (Robots Meta), Post Admin Shortcuts (Pin), WP Show IDs (ID) and User Access Manager (Access).

**Feedback**

You can leave any <a href='http://www.codepress.nl/plugins/codepress-admin-columns#feedback'>requests or feedback</a>.

**Related Links:**

* http://www.codepress.nl/plugins/codepress-admin-columns/

== Installation ==

1. Upload codepress-admin-columns to the /wp-content/plugins/ directory
2. Activate Codepress Admin Columns through the 'Plugins' menu in WordPress
3. Configure the plugin by going to the Admin Column settings that appears under the Settings menu.


== Frequently Asked Questions ==

= I have an idea for a great way to improve this plugin =

Great! I'd love to hear from you.
Leave your feedback at http://www.codepress.nl/plugins/codepress-admin-columns#feedback.

== Screenshots ==

1. Posts Screen with custom columns.
2. Settings page of the Codepress Admin columns plugin.
3. Options for the Custom Field Column.

== Changelog ==

= 1.2.1 =

* added wordcount sorting
* removed attachment sorting

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