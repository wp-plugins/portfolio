=== Portfolio ===
Contributors: bestwebsoft
Donate link: https://www.2checkout.com/checkout/purchase?sid=1430388&quantity=1&product_id=13
Tags: portfolio, images gallery, custom fields, categories, clients, custom, image, images, jpeg, jpg, page, pages, photos, picture, pictures, portolio, post, posts, showcase, tags
Requires at least: 3.1
Tested up to: 3.3.1
Stable tag: 2.03

Portfolio plugin allows you to create a page with information about your past projects.

== Description ==

Portfolio plugin provides a possibility to create a unique page for displaying portfolio items consisting of screenshots and additional information such as description, short description, URL, date of completion, etc.
Also it allows adding of additional screenshots (multiple additional screenshots per 1 portfolio item).

<a href="http://wordpress.org/extend/plugins/portfolio/faq/" target="_blank">FAQ</a>
<a href="http://bestwebsoft.com/plugin/portfolio-plugin/" target="_blank">Support</a>

= Features =

* Actions: Create a template page to display all portfolio items with paging navigation. 
* Actions: Possibility to regenerate thumbnails for an image after changing of its size. 
* Options: Provides a possibility to adjust images size and a number of images displayed in a row. 
* Display: Includes a possibility to turn off displaying of additional fields. 

= Translation =

* Dutch (nl_NL) (thanks to <a href="ronald@hostingu.nl">HostingU, Ronald Verheul</a> )
* Russian (ru_RU)

If you create your own language pack or update an existing one, you can send <a href="http://codex.wordpress.org/Translating_WordPress" target="_blank">the text in PO and MO files</a> for <a href="http://bestwebsoft.com/" target="_blank">BWS</a> and we'll add it to the plugin. You can download the latest version of the program for work with PO and MO files <a href="http://www.poedit.net/download.php" target="_blank">Poedit</a>.

== Installation ==

1. Upload `portfolio` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Please check if you have the 'portfolio.php' and 'portfolio-post.php' template files in your templates directory. If you are not able to find these files, then just copy them from '/wp-content/plugins/portfolio/template/' directory to your templates directory.
4. Create a page and select Portfolio template for it in the Page Attributes block.
5. Create (if necessary) Technologies and Executor profiles.
6. Create portfolio, add title, description, short description, and upload images one of which must be marked as featured. Publish portfolio.

== Frequently Asked Questions ==

= I cannot view my Portfolio page =

1. First of all, you need to create your first Portfolio page and choose 'Portfolio Template' from a list of available templates (which will be used for displaying of our portfolio).
2. If you cannot find 'Portfolio Template' in a list of available templates, then just copy it from '/wp-content/plugins/portfolio/template/' directory to your templates directory.

= How to use a plugin? =

1. Create necessary technologies using this page http://example.com/wp-admin/edit-tags.php?taxonomy=post_tag&post_type=portfolio
2. This is optional. Fill in this page http://example.com/wp-admin/edit-tags.php?taxonomy=portfolio_executor_profile&post_type=portfolio - create a profile of executor. Fill in 'Name' and 'Description' fields. 'Description' field contains link to a personal executor's page.
3. Choose 'Add New' from the 'Portfolio' menu and fill out your page. Set necessary values for the ‘Technologies’ and 'Executors Profile' widgets.

= How to add an image? =

Use Wordpress meta box to upload images from URL or your local storage. Note that one image needs to be selected as 'Featured' - it will be main image of your Portfolio item.

= You have updated the plugin, the template was changed, but you want to get back everything as it was? What you should do?=

Sometimes when updating the plugin the templates of the the plugin are updated in the theme herewith the back ups for the old versions of the templates are performed. There will be the files `portfolio-post.php.bak` and `portfolio.php.bak`in the theme. It is necessary to compare the old files and the new files, implement the required changes in the new files form the old files.

== Screenshots ==

1. Portfolio Settings page.
2. Add technologies page.
3. Add executors profile page.
4. This screenshot for Add New Portfolio items.
5. Portfolio frontend page (for all portfolios).
6. Portfolio single frontend page.

== Changelog ==

= V2.03 - 07.03.2012 =
* NEW : Shortcode for displaying of latest portfolio items is added.
* Changed : BWS plugins section. 

= V2.02 - 24.02.2012 =
* NEW : Dutch language file is added to the plugin.
* New : Code for backup of portfolio template before a plugin is updated is added.
* Change : Code that is used to connect styles and scripts is added to the plugin for correct SSL verification.

= V2.01 - 31.01.2012 =
* NEW : Language files are added to the plugin.
* NEW : Settings page for Portfolio is added.
* Changed : Template to display portfolio is changed. Changes were done to both main page and single portfolio page.
* NEW : A possibility to create thumbnails multiple times for portfolio images is added.

= 1.07 =
* The bugs of description block displaying are fixed and jQuery noConflict is added to this version of the plugin.

= 1.06 =
* The bugs of featured images display and pagination are fixed in this version of the plugin.

= 1.05 =
* In this version image bug display is fixed.

= 1.04 =
* In this version image for portfolio is added to admin page.

= 1.03 =
* In this version an image uploaded by means of custom fields is substituted with Wordpress standard meta box for the media files uploading.

== Upgrade Notice ==

= V2.03 =
Shortcode for displaying of the latest portfolio items is added. BWS plugins section has been changed. 

= V2.02 =
Dutch language file is added to the plugin. Code to backup portfolio template before a plugin is updated is added. Code that is used to connect styles and scripts is added to the plugin for correct SSL verification.

= V2.01 =
Language files and Settings page are added to the plugin. Template for portfolio display is changed. Changes were done to both main page and single portfolio page. A possibility to create new thumbnails for portfolio images was added. Upgrade immediately.

= 1.07 =
The bug of description for the block is fixed and jQuery noConflict is added to this version of the plugin.

= 1.06 =
The bugs of featured images displaying and pagination are fixed in this version of the plugin. Upgrade immediately.

= 1.05 =
Display image bug is fixed in this version. Upgrade immediately.

= 1.03 =
Security related bug is fixed in this version. Upgrade immediately.