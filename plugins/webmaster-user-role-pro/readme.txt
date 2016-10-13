=== Webmaster User Role: Pro ===
Contributors: tylerdigital, croixhaug
Tags: client, clients, restrict access, access, admin, users, webmaster, capabilities, administrator, editor, permissions, roles, user roles
Requires at least: 3.5
Tested up to: 4.2.2
Stable tag: 1.6

Adds a new "Admin" user role between Administrator and Editor. Perfect for clients and those who know just enough to be dangerous.

== Description ==
This plugin creates a new role named "Admin" that is the same as "Administrator" with the following changes:

= In WP-Admin =
* Hide / Remove Settings menu
* Hide / Remove Plugins menu
* Hide / Remove Tools menu
* Hide / Remove Users menu
* Disable theme installation
* Disable theme switching
* Hide / Remove Appearance > Editor
* Disable WP core updates
* Hide non-essential dashboard items

= 3rd party plugin compatibility =

* Advanced Custom Fields (Elliot Condon) - hide ACF menu, only admins/developers should be modifying ACF definitions/rules/fields
* Contact Form 7 - user can only read contact form submissions
* Gravity Forms (RocketGenius) - user can view form entries but not edit forms or create new ones
* iThemes Security - hide iThemes security menus
* Sucuri Scanner (Sucuri) - hide security scan information
* TablePress - Show/Edit/Import/Export TablePress content (all tabs except plugin options)
* Ultimate Branding (WPMU Dev) - hide branding menu
* Yoast SEO - hide Yoast SEO settings menu (also hide the SEO metabox when editing pages/posts by upgrading to Webmaster User Role Pro)
* * iThemes Exchange - revoke all access to Exchange, or restrict user's ability to manage purchases & payments, manage sales, and/or manage add-ons
* Easy Digital Downloads - allow user to manage products, manage customer payments, manage reports & sales data, and/or manage settings
* WooCommerce - hide the settings, allow user to edit & view products, coupons, or orders, allow user to view reports
* Events Calendar - restrict the user's ability to manage and delete events, venues, and organizers
* WP All Import - user cannot use the WP All Import Settings Menu
* SiteGround SuperCacher - user cannot use SuperCacher menu
* Event Espresso - decide whether user can access Events, Registrations, Transations, Messages, Pricing, Registration Form, Venues, or Payment Methods

== Screenshots ==

1. Adds a role to fit nicely between Administrator and Editor
2. Easily remove administrative menu items, while leaving content-related items like Menus and Wigets
3. Gravity Forms Settings (Pro version only)
4. Yoast SEO Settings (Pro version only)
5. Appearance/Themes Settings (Pro version only)

== Installation ==
Install and activate, there are no settings in the free version of this plugin. Webmaster User Role is built with a carefully chosen set of permissions intended for the majority of clients. There are many free options available for further tweaking user permissions or editing the admin menu. We also have a [Pro version](http://tylerdigital.com/products/webmaster-user-role/) that makes it easy to customize for your clients.

== Changelog ==

= 1.6 =

* Added: Ninja Forms Plugin Support
* Added: Envato Toolkit Plugin Support
* Added: CPTUI Plugin Support
* Added: WordFence Plugin Support
* Added: Google Analytics Plugin by Yoast Support
* Added: NativeChurch Theme Support
* Update: Improved Portuguese Language Support

= 1.5 =

* New: Added Settings link in plugins list
* New: Added Easy Digital Downloads Module
* New: Added iThemes Exchange Module
* New: Added WooCommerce Module
* New: Added Event Espresso Module
* New: Added Events Calendar Module
* New: Added WP All Import Module
* New: Added Siteground SuperCacher Module
* New: Theme support for Avian theme
* New: Theme support for Cardinal theme
* New: Theme support for Ken theme
* New: Theme support for Total theme
* Update: Improved Gravity Forms Module
* Update: Improved i18n
* New: Serbian Language Support
* New: Portuguese Language Support (PT & BR)
* Fix: multisite activation bug with _blogs() function
* Fix: Improve Yoast SEO module
* Fix: Fatal error with CF7 module
* Fix: config array error on Multisite
* Fix: removed Administrator from editable roles

= 1.3.1 =
* Improved: Show Yoast metabox when editing pages (still hide settings) by default

= 1.3 =
* New: Hide Users menu (often requested, and really the webmaster user couldn't do much in this screen anyway)
* New: Add support for ACF5 (hide Custom Fields Menu compatible with new version)
* New: Add support for Contact Form 7
* New: Add support for iThemes Security

= 1.21 =
* New: Add support for TablePress [http://wordpress.org/plugins/tablepress/]

= 1.2 =
* Multisite bugfix: Prevent webmaster from removing users from individual sites
* Multisite bugfix: Stop removing Settings & Tools from network administrator

= 1.1.1 =
* New: Now hides non-essential dashboard items
* Fixed: Fixes a conflict with Mizzo theme (thanks djesch)

= 1.1 =
* New: Add support for Sucuri Scanner [http://wordpress.org/plugins/sucuri-scanner/]
* New: Add support for Advanced Custom Fields [http://wordpress.org/plugins/advanced-custom-fields/]
* New: Remove tools menu – so webmaster users can't import/export/migrate/find&replace

= 1.0.9 =
* New: Add support for Ultimate Branding [http://premium.wpmudev.org/project/ultimate-branding/]

= 1.0.8 =
* Improved: Add Gravity Forms edit_forms capability as an option (only allows entry viewing by default) via filter:
add_filter( 'td-webmaster-user-roleoption_cap_gravityforms_edit_forms', __return_true );

= 1.0.7 =
* New: Remove settings menu from wp-admin

= 1.0.5 =
* New: Remove capability to delete users

= 1.0.4 =
* New: Add "editor" cap for role so plugins checking for "editor" explicitly work

= 1.0.3 =
* New: Remove capabiilty to add, edit, promote users
* New: Remove capability to update core

= 1.0.2 =
* Initial Release