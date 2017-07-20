=== Plugin Name ===
Contributors: ovann86
Donate link: http://www.itsupportguides.com/
Tags: comments, disqus, latest, shortcode, recent
Requires at least: 4.0
Tested up to: 4.7.1
Stable tag: 1.7.0
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

List your websites latest Disqus comments in a page, post or widget

== Description ==

This plugin will allow you to list your websites latest Disqus comments in a page, post or widget.

Simply install and activate the plugin, browse to 'Comments' -> 'Disqus Latest Comments' menu, add in your Disqus shortname, then include the [disqus-latest] shortcode in the page, post or widget of your choosing.

The configuration and options can be found in the WordPress administration. Browse to 'Comments' -> 'Disqus Latest Comments'.

**Options are available for:**

* Number of comments listed
* To show avatars
* Size of avatar images
* Excerpt length (length of comment shown in widget)
* A choice of three pre-configured styles - grey, blue and green or the ability to use your own custom CSS
* Ability to bypass caching (for debugging or attempting to bypass any caching plugins you may have installed)
* Ability to translate Disqus time terms (for example 'days ago')
* Ability to make linked Disqus usernames open in a new window (target='_blank')

You can see a working example of the plugin at [www.itsupportguides.com](http://www.itsupportguides.com/latest-comments/ "www.itsupportguides.com latest comments").

== Installation ==

1. Install plugin from WordPress administration or upload folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in the WordPress administration
1. In the WordPress administration, browse to 'Comments' -> 'Disqus Latest Comments'
1. Enter your Disqus shortname and any other settings as desired and save the changes
1. Open or create the post or page you want the comments to be included in
1. Enter the shortcode [disqus-latest] into the content area and save the changes
1. The comments will now be displayed on the post of page where the shortcode has been used

== Frequently Asked Questions ==

= How does this work? =

This plugin uses the Disqus API to retieve the latest comments for the given Disqus account.

= What are the settings =

On the 'Comments' -> 'Disqus Latest Comments' page you will find several settings.

'Disqus Shortname' - This is where you need to add the shortname for the websites Disqus account.

'Number of comments' - This controls how many latest comments are displayed.

'Hide avatars' - This disables the avatars (images) displayed with each comment.

'Avatar size' - This controls the size of the avatar (image) displayed with each comment.

'Except length'- This controls the length of the comment displayed on the page.

'Style' - This allows you to select three different styles for the comments - grey, green and blue. You may also choose none or custom.

'Bypass Cache' - This option can be used if a website is caching the page or post the comments are being displayed on, or the website is caching the Disqus API request. 

= What are the default values =

'Disqus Shortname' is required - there is no default. A warning message will be displayed instead of the comments scren if you do not specify a shortname.

'Number of comments' - 5

'Hide avatars' - Off - avatars will be displayed

'Avatar size' - 35 pixels

'Except length' - 200 characters 

'Style' - None

'Bypass Cache' - No

= Example custom CSS style =

The below can be used as a template when creating your own custom style. This can be pasted into the 'Custom CSS' option when the custom style is selected.

`/* the entire list */

.dsq-widget-list {
display: block;
}

/* each comment item */

.dsq-widget-item {
position: relative;
}

/* hover over style for each comment item */

.dsq-widget-item:hover {
background: #f6f6f6;
}

/* the avatar image in each comment item */

.dsq-widget-avatar {
display:block;
}

/* the Disqus user name */

.dsq-widget-user {
display:block;
}

/* the comment */

.dsq-widget-comment {
display: block;
}

/* paragraph that contains the link to the post and day */

.dsq-widget-meta {
display:block;
}

/* make the post title bold */

.dsq-widget-meta a:nth-child(1) {
font-weight:800;
}`

== Screenshots ==

1. This screenshot shows the comments that are displayed on the front end with the 'Grey' style applied.
2. This screenshot shows the options for configuring the plugin.
3. This screenshot shows the options for translating the Disqus time terms.

== Changelog ==

= 1.7.0 = 
* Maintenance: update instructions on how to find Disqus shortname on Disqus website
* Maintenance: improve internationalisation (i18n) for translating plugin to other languages
* Maintenance: move JavaScript to external file
* Maintenance: move CSS to external files
* Maintenance: add plugin 'Settings' link in WordPress administration plugins page
* Maintenance: improve input and output sanitisation

= 1.6 = 
* Feature: Improve support for HTTPS websites.

= 1.5 =
* Feature: Add 'Custom' option for styles. Allows you to enter your own CSS styles from the configuration screen.
* Maintenance: Moved CSS load in page footer using wp_footer action.
* Maintenance: General formatting of plugin code to make it more readable. 

= 1.4.1 =
* Fix: Resolve issue introduced in version 1.4.1.

= 1.4 =
* Feature: Added ability to make linked Disqus usernames open in a new window (target='_blank'). Enabled from options by ticking 'Open Disqus usernames in new window'.

= 1.3 =
* Feature: Added translation options to translate Disqus time terms (for example 'days ago').

= 1.2 =
* Fix: Resolved issue with using shortcode in widget areas - where comments would appear before the widget title.
* Maintenance: Tidy plugin code.

= 1.1 =
* Feature: Added 'Bypass Cache' option in case website caches Disqus API requests.
* Fix: Resolved issue with Avatar Size and Excerpt Length not working.
* Fix: Changed Avatar Size to use only the three supported Disqus avatar sizes - 35px, 34px and 92px.

= 1.0 =
* First public release.