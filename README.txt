=== Plugin Name ===
Contributors: wearelittle
Donate link: https://wearelittle.agency/
Tags: social, twitter, facebook, instagram, linkedin
Requires at least: 4.0
Tested up to: 5.2
Stable tag: 5.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


== Description ==

Display posts from multiple social media channels and profiles in one combined feed.


== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `little-social-media-plugin` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure the plugin by going to `LITTLE Social` in the sidebar menu

== Frequently Asked Questions ==

Usage instructions can be viewed here: http://docs.little.build/

== Upgrade Notice ==

N/A

== Screenshots ==

N/A

== Changelog ==

1. New error section added for diagnosing API issues
2. Removed the 1min cron interval to prevent API softbans. Existing installations will revert to 5mins
3. Fixed a bug whereby multiple API calls were made on each page load
4. Improved error handling, and issue where process couldn't carry on if an error was encountered

