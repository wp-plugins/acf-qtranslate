=== ACF qTranslate ===
Contributors: funkjedi
Tags: acf, advanced custom fields, qtranslate, add-on, admin
Requires at least: 3.5.0
Tested up to: 4.1.1
Version: 1.7.1
Stable tag: 1.7.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds qTranslate compatible field types for Text, Text Area, Wysiwyg Editor and Image.


== Description ==

This plugin adds a new Field Type category called qTranslate. This contains qTranslate compatible fields for Text, Text Area, WYSIWYG, Image and File.

= Field Types =
* qTranslate Text (type text, api returns text)
* qTranslate Text Area (type text, api returns text)
* qTranslate WYSIWYG (a wordpress wysiwyg editor, api returns html)
* qTranslate Image (upload an image, api returns the url)
* qTranslate File (upload a file, api returns the url)

= qTranslate-X =
If using qTranslate-X the standard Text, Text Area and WYSIWYG field types all automatically support translation out of the box.

= Bug Submission =
https://github.com/funkjedi/acf-qtranslate/issues/


== Installation ==

**This plugins requires a qTranslate-based plugin to be installed:**

* [qTranslate](http://wordpress.org/extend/plugins/qtranslate/)
* [qTranslate-X](https://wordpress.org/plugins/qtranslate-x/)
* [qTranslate Plus](https://wordpress.org/plugins/qtranslate-xp/)
* [mqTranslate](https://wordpress.org/plugins/mqtranslate/)
* [zTranslate](http://wordpress.org/extend/plugins/ztranslate/)

1. Upload `acf-qtranslate` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress


== Frequently Asked Questions ==

= What's the history behind this plugin? =
The plugin is based on code samples posted to the ACF support forums by taeo back in 2013.


== Screenshots ==

1. Shows the qTranslate Text and Image fields.


== Changelog ==

= 1.7.1 =
* Core: Added back ACFv5 support for WYSIWYG
* Core: Added qTranslate-X support for the standard WYSIWYG field type
* Core: Bumped version requirement to match ACF
* Bug Fix: qTranslate-X switcher showing up on every admin page

= 1.7 =
* Core: Refactor of codebase
* Core: Support for qTranslate-X language switchers

= 1.6 =
* Added ACFv4 support for qTranslate-X

= 1.5 =
* Core: Added compatibility for qTranslate-X
* Bug Fix: Remove the broken ACFv5 WYSIWYG implementation

= 1.4 =
* Core: Added support for ACFv5
* Core: Tested compatibility with mqTranslate

= 1.3 =
* Core: Updated styles for Wordpress 3.8
* Bug Fix: qTranslate bug with multiple WYSIWYG editors

= 1.2 =
* Bug Fix: qTranslate bug with multiple WYSIWYG editors

= 1.1 =
* Core: Added support for Image Fields. Thanks to bookwyrm for the contribution.

= 1.0 =
* Initial Release. Thanks to taeo for the code samples this plugin was based on.


== Upgrade Notice ==

= 1.7.1 =
Added qTranslate-X support for the standard WYSIWYG field type
