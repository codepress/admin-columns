=== Admin Columns ===
Contributors: codepress, tschutter, davidmosterd, engelen, dungengronovius
Tags: columns, admin columns, custom fields, column manager, sorting
Requires at least: 5.9
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 7.0.10
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Take control of your WordPress admin list tables. Add, remove, and reorder columns for posts, users, media, and more — no coding needed.

== Description ==

Admin Columns lets you customize and organize the columns displayed in the WordPress admin list tables for posts, pages, users, comments and media.

Instead of the limited default list table, you can create powerful dashboards that display the information you actually need - like custom fields, featured images, taxonomies, file data and more.

Trusted by 100,000+ WordPress sites worldwide.

With a simple drag-and-drop interface you can:

* Add custom columns to any admin list
* Reorder and resize columns
* Choose from 200+ available column types
* Display metadata such as custom fields, images or taxonomy terms
* Create clean overviews for complex websites

http://vimeo.com/96885841

Whether you're managing a blog, WooCommerce store or large content site, Admin Columns makes the WordPress admin faster and easier to use.

= Why Admin Columns? =

Default WordPress list tables are limited. They often show only the title, author and date.

Admin Columns turns them into powerful management screens where you can instantly see important information about your content.

Examples:

* See **featured images, custom fields and taxonomies** directly in the post overview
* Display **media file size, dimensions or EXIF data**
* View **user metadata and roles** in the users screen
* Organize large content libraries quickly

= Popular use cases =

Admin Columns is used by developers, agencies and site owners to manage content more efficiently.

Common workflows include:

* Managing **WooCommerce products** with price, SKU or stock columns
* Displaying **ACF custom fields** in post list tables
* Organizing **large media libraries**
* Reviewing **SEO metadata from Yoast**
* Managing **complex custom post types**

= Admin Columns Pro =

Upgrade to **Admin Columns Pro** to unlock powerful workflow features. Pro allows you to:

* **Sort columns** – quickly find the content you need
* **Filter content** – narrow down large content lists with our stackable smart filters
* **Inline edit data** – edit titles, custom fields, taxonomies and more directly from the list table
* **Bulk edit data** – update multiple items at once directly from the list table
* **Conditional formatting** – highlight rows and cells based on rules to spot important content at a glance
* **Export data to CSV**
* **Import/export column configurations**
* Save column configurations to **PHP** for developers

Admin Columns Pro also provides deep integrations with popular plugins:

* **Advanced Custom Fields** – display and edit all field types
* **WooCommerce** – product and order columns
* **Yoast SEO**
* **Toolset Types**
* **Pods**
* and many more

Learn more about the additional features of Admin Columns Pro on our website:

[Upgrade to Admin Columns Pro](https://www.admincolumns.com/admin-columns-pro/)

= Supported content types =

Admin Columns works with the following WordPress admin screens:

* Posts, Pages and Custom Post Types
* Users
* Media Library
* Comments
* Taxonomies (Pro)

= Custom field column =

Admin Columns allows you to display custom fields (post meta and user meta) directly in the admin list table.

Supported field formats include:

* Color
* Date
* Images
* Number
* Text
* URL
* True/False
* Relational: Posts, Users and Media

These field types ensure that your metadata is displayed in a clear and readable format.

= Docs & Support =

Is this your first time using Admin Columns? Check out our documentation and guides:

[Documentation](https://docs.admincolumns.com/)
[Getting Started](https://docs.admincolumns.com/category/16-getting-started)
[Developer documentation](https://docs.admincolumns.com/category/75-developer)

Need help? Please visit the Admin Columns [support forums](http://admincolumns.com/support).

= Translations =

Admin Columns is translated into many languages thanks to our community.

You can contribute translations on [WordPress.org](https://translate.wordpress.org/projects/wp-plugins/codepress-admin-columns) and Pro translations on [Transifex](https://www.transifex.com/projects/p/admin-columns/)

= Feedback & Feature Requests =

Have ideas or suggestions? Submit it to our [public roadmap](https://www.admincolumns.com/public-roadmap/)

== Frequently Asked Questions ==

= Is Admin Columns free? =

Yes. The core plugin is free and includes column management for posts, pages, users, comments and media.

Admin Columns Pro adds advanced features such as sorting, filtering, inline editing, bulk editing, conditional formatting and CSV export. [Learn more](https://www.admincolumns.com/admin-columns-pro/).

= Can I display custom fields? =

Yes. Admin Columns lets you display custom fields for posts and users directly in the list table. Supported types include images, numbers, URLs, dates and more.

Admin Columns Pro also provides deep integrations with plugins such as Advanced Custom Fields, which adds support for all ACF field types.

= Does it work with WooCommerce, ACF and other plugins? =

Yes. Admin Columns works with most WordPress plugins out of the box. Admin Columns Pro provides dedicated integrations for WooCommerce, Advanced Custom Fields, Yoast SEO, Toolset Types, Pods and more.

= Can I sort or filter columns? =

Sorting and filtering are available in **Admin Columns Pro**, allowing you to quickly find the content you need across large datasets.

= Can I edit content directly from the list table? =

Yes. **Admin Columns Pro** supports inline editing and bulk editing, so you can update titles, taxonomies, custom fields and more without leaving the list table.

= What filters and hooks can I use? =

Developers can extend Admin Columns using many actions and filters. You can find the full reference here:

https://www.admincolumns.com/documentation/#filter-reference

== Screenshots ==

1. Column settings page for post types — drag and drop to reorder, configure each column's display options.
2. Posts list screen with customized columns showing exactly the data you need.
3. Column settings page for the Media Library.
4. Media Library with custom columns for dimensions, file size, EXIF data, and more.
5. Column settings page for the Users screen.
6. Users list with custom columns for roles, post counts, and custom fields.
7. Custom field column configuration — choose from color, date, image, number, URL, and more display types.
8. Posts list showing multiple custom field columns with formatted values.

== Changelog ==

= 7.0.10 =
Release Date: February 16th, 2026

* [Improved] String limit setting added to Media Caption column
* [Improved] Deprecated the Admin Columns helper function 'ac_helper'.
* [Removed] Removed the hook ac/column/types/pro in favor of ac/column/types.

[See changelog for all versions](https://github.com/codepress/admin-columns/blob/main/changelog.txt).
