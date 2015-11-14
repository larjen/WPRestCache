=== WPRestCache ===
Contributors: larjen
Donate link: http://exenova.dk/
Tags: Toggle
Requires at least: 4.3.1
Tested up to: 4.3.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Wordpress plugin that provides an infinite cache in front of a REST API.

== Description ==

Wordpress plugin that provides an infinite cache in front of a REST API. This would be useful for sites that are static in nature and seldom updates, as polling the native REST API in WordPress can be somewhat slow.

From within the plugin, there is an option to deploy a cache in front of a REST API. When you deploy it, be warned that the folder "rest-api" will be created in the root of your webserver.

You can then request the REST-API like this:

    http://www.example.com/rest-api/v1/posts?page=1&per_page=12&search=query&tags=tag1+tag2
    http://www.example.com/rest-api/v1/tag/?_jsonp=angular.callbacks._1&tag_name=tag1

Since these requests are infinitely cached, you can schedule an optional wipe of the cache which will occur 5 minutes after last post alteration.

== Installation ==

1. Download to your Wordpress plugin folder and unzip.
1. Activate plugin.
1. Deploy the cache, and change the settings in the control panel to your liking.

== Frequently Asked Questions ==

= Do I use this at my own risk? =

Yes.

== Screenshots ==

== Changelog ==

= 1.0.0 =
* Uploaded plugin.

== Upgrade Notice ==
