=== Admin Menu Editor Pro ===
Contributors: whiteshadow
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=A6P9S6CE3SRSW
Tags: admin, dashboard, menu, security, wpmu
Requires at least: 3.0
Tested up to: 3.3.1
Stable tag: 1.16

Lets you directly edit the WordPress admin menu. You can re-order, hide or rename existing menus, add custom menus and more.

== Description ==
Pro version of the Admin Menu Editor plugin. Lets you manually edit the Dashboard menu. You can reorder the menus, show/hide specific items, change access rights, and more. 

[Get the latest version here.](http://w-shadow.com/admin-menu-editor-pro/updates/)

**Pro Version Features**

- Import/export custom menus.
- Set menu items to open in a new window or IFrame.
- Use shortcodes in menu fields.
- Create menus accessible only to a specific user.

**Other Features**

- Change menu title, access rights, URL and more. 
- Create custom menus.
- Sort items using a simple drag & drop interface.
- Move items to a different submenus.
- Hide any menu or submenu item.
- Supports WordPress MultiSite/WPMU.

[Suggest new features and improvements here](http://feedback.w-shadow.com/forums/58572-admin-menu-editor)

**Requirements**

- WordPress 3.0 or later
- PHP 5 (PHP 5.2+ recommended)

_For maximum compatibility and security, using a modern web browser such as Firefox, Opera, Chrome or Safari is recommended. Certain advanced features (e.g. menu import) may not work reliably or at all in Internet Explorer and other outdated browsers._

== Installation ==

_You can install Admin Menu Editor Pro on as many of your sites as you like. If you already have the free version of Admin Menu Editor installed, deactivate it before installing the Pro version._

**Normal installation**

1. Download the admin-menu-editor-pro.zip file to your computer.
1. Unzip the file.
1. Upload the `admin-menu-editor-pro` directory to your `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.

That's it. You can access the the menu editor by going to _Settings -> Menu Editor Pro_. The plugin will automatically load your current menu configuration the first time you run it.

**WP MultiSite installation**

If you have WordPress set up in multisite ("Network") mode, you can also install Admin Menu Editor as a global plugin. This will enable you to edit the Dashboard menu for all sites and users at once.

1. Download the admin-menu-editor-pro.zip file to your computer.
1. Unzip the file.
1. Create a new directory named `mu-plugins` in your site's `wp-content` directory (unless it already exists).
1. Upload the `admin-menu-editor-pro` directory to `/wp-content/mu-plugins/`.
1. Move `admin-menu-editor-mu.php` from `admin-menu-editor-pro/includes` to `/wp-content/mu-plugins/`.

Plugins installed in the `mu-plugins` directory are treated as "always on", so you don't need to explicitly activate the menu editor. Just go to _Settings -> Menu Editor_ and start customizing your admin menu :)

_Notes_

- Instead of installing Admin Menu Editor in `mu-plugins`, you can also install it normally and then activate it globally via "Network Activate". However, this will make the plugin visible to normal users when it is inactive (e.g. during upgrades).
- When Admin Menu Editor is installed in `mu-plugins` or activated via "Network Activate", only the "super admin" user can access the menu editor page. Other users will see the customized Dashboard menu, but be unable to edit it.
- It is currently not possible to install Admin Menu Editor as both a normal and global plugin on the same site.

== Notes ==
Here are some usage tips and other things that can be good to know when using the menu editor : 

- WordPress uses the concept of [roles and capabilities](http://codex.wordpress.org/Roles_and_Capabilities "An overview of roles and capabilities") to manage access rights. Each Dashboard menu item has an associated capability setting, and only the users that possess that capability can see and use that menu item.

- "Hidden" menus are invisible to everyone - including the administrator - but they can still be accessible via the direct page URL. To make a menu inaccessible to certain users, raise its access rights by assigning it a different "required capability".

- If you delete any of the default menus they will reappear after saving. This is by design. To get rid of a menu for good, either hide it or set it's access rights to a higher level.

- Custom menu items will only show up in the final menu if the "Custom" box is checked (the plugin will check it for you when you create a new menu). If some of your menu items are only visible in the editor but not in the Dashboard menu itself, this is probably the reason.


== Changelog ==

[Get the latest version here.](http://w-shadow.com/admin-menu-editor-pro/updates/)

= 1.16 =
* Removed the "Feedback" button. You can still provide feedback via email or support forums, of course.

= 1.15 =
* Fixed a PHP warning when no custom menu exists (yet).

= 1.14 =
* Fixed menu export not working when WP_DEBUG is set to True.
* Fixed the updater's cron hook not being removed when the plugin is deactivated.
* Fixed updates not showing up in some situations.
* Fixed the "Feedback" button not responding to mouse clicks in some browsers.
* Enforce the custom menu order by using the 'menu_order' filter. Fixes Jetpack menu not staying put.
* Fixed "Feedback" button style to be consistent with other WP screen meta buttons.
* You can now copy/paste as many menu separators as you like without worrying about some of them mysteriously disappearing on save.
* Fixed a long-standing copying related bug where copied menus would all still refer to the same JS object instance.
* Added ALT attributes to the toolbar icon images.
* Removed the "Custom" checkbox. In retrospect, all it did was confuse people.
* Made it impossible to edit separator properties.
* Removed the deprecated "level_X" capabilities from the "Required capability" dropdown. You can still type them in manually if you want.

= 1.13 =
* Tested for WordPress 3.2 compatibility.

= 1.12 =
* Fixed a "failed to decode input" error that would show up when saving the menu.

= 1.11 = 
* WordPress 3.1.3 compatibility. Should also be compatible with the upcoming 3.2.
* Fixed spurious slashes sometimes showing up in menus.
* Fixed a fatal error concerning "Services_JSON".

= 1.1 = 
* WordPress 3.1 compatibility.
* Added the ability to drag & drop a menu item to a different menu.
* You can now set the "Required capability" to a username. Syntax: "user:john_smith".
* Added a drop-down list of Dashboard pages to the "File" box.
* When the menu editor is opened, the first top-level menu is now automatically selected and it's submenu displayed. Hopefully, this will make the UI slightly easier to understand for first-time users.
* All corners rounded on the "expand" link when not expanded.
* By popular request, the "Menu Editor" menu entry can be hidden again.
