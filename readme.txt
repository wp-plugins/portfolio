=== Portfolio ===
Contributors: bestwebsoft
Donate link: https://www.2checkout.com/checkout/purchase?sid=1430388&quantity=1&product_id=13
Tags: portfolio, images gallery, custom fields, categories, clients, custom, image, images, jpeg, jpg, page, pages, photos, picture, pictures, portolio, post, posts, showcase, tags
Requires at least: 3.1
Tested up to: 3.5.1
Stable tag: 2.13
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Portfolio plugin allows you to create a page containing the information about your past projects.

== Description ==

With the Portfolio plugin you can create a unique page for displaying portfolio items consisting of screenshots and additional information such as description, short description, URL, date of completion, etc.
Moreover you can add not just one, but many screenshots to one portfolio item for better visual guidance. 

<a href="http://wordpress.org/extend/plugins/portfolio/faq/" target="_blank">FAQ</a>
<a href="http://support.bestwebsoft.com" target="_blank">Support</a>

= Features =

* Actions: Create a template with page navigation to display all portfolio items. 
* Actions: Regenerate thumbnails after changing their size. 
* Options: Change image size and a number of images displayed in the row. 
* Actions: Edit labels of additional fields.
* Display: Enable/disable the option of additional fields displaying. 
* Actions: Display the latest portfolio items on your page or post using the shortcode [latest_portfolio_items count=3].

= Translation =

* Brazilian Portuguese (pt_BR) (thanks to DJIO, www.djio.com.br)
* Dutch (nl_NL) (thanks to <a href="mailto:ronald@hostingu.nl">HostingU, Ronald Verheul</a>)
* French (fr_FR) (thanks to <a href="mailto:paillat.jeff@gmail.com">Jeff</a>)
* German (de_DE) (thanks to Felix Griewald, www.felix-griewald.de)
* Hebrew (he_IL) (thanks to Sagive SEO)
* Hindi (hi_IN) (thanks to <a href="mailto:ash.pr@outshinesolutions.com">Outshine Solutions</a>, www.outshinesolutions.com )
* Italian (it_IT)
* Persian (fa_IR) (thanks to <a href="mailto:AmirMaskani@gmail.com">Amir Maskani</a>, www.emir.ir)
* Russian (ru_RU)
* Spain (es_ES) (thanks to Grupo Gomariz, S.L. www.grupogomariz.com)
* Ukrainian (uk) (thanks to Cmd Soft, www.cmd-soft.com)

If you would like to create your own language pack or update the existing one, you can send <a href="http://codex.wordpress.org/Translating_WordPress" target="_blank">the text of PO and MO files</a> for <a href="http://support.bestwebsoft.com" target="_blank">BWS</a> and we'll add it to the plugin. You can download the latest version of the program for work with PO and MO files  <a href="http://www.poedit.net/download.php" target="_blank">Poedit</a>.

= Technical support =

Dear users, our plugins are available for free download. If you have any questions or recommendations regarding the functionality of our plugins (existing options, new options, current issues), please feel free to contact us. Please note that we accept requests in English only. All messages in another languages won't be accepted.

If you notice any bugs in the plugins, you can notify us about it and we'll investigate and fix the issue then. Your request should contain URL of the website, issues description and WordPress admin panel credentials.
Moreover we can customize the plugin according to your requirements. It's a paid service (as a rule it costs $40, but the price can vary depending on the amount of the necessary changes and their complexity). Please note that we could also include this or that feature (developed for you) in the next release and share with the other users then. 
We can fix some things for free for the users who provide translation of our plugin into their native language (this should be a new translation of a certain plugin, you can check available translations on the official plugin page).

== Installation ==

1. Upload the folder `portfolio` to the directory `/wp-content/plugins/` 
2. Activate the plugin via the 'Plugins' menu in WordPress
3. Please check if you have the template files 'portfolio.php' and 'portfolio-post.php' in your templates directory. If you can't find these files, then just copy them from the directory '/wp-content/plugins/portfolio/template/' to your templates directory.
4. Create a page and select a template in the Page Attributes block.
5. Create (if necessary) Technologies and Executor profiles.
6. Create portfolio item, add title, description, short description, and upload the images one of which should be set as featured. Publish portfolio then.

== Frequently Asked Questions ==

= I don't see my Portfolio page =

1. First of all, you should create your first Portfolio page and select 'Portfolio Template' in the list of available templates.
2. If you cannot find 'Portfolio Template' in the list of available templates, then just copy it from the directory '/wp-content/plugins/portfolio/template/' to your templates directory.

= How to use a plugin? =

1. Add necessary technologies using this page http://example.com/wp-admin/edit-tags.php?taxonomy=portfolio_technologies&post_type=portfolio
2. This is optional. Fill this page http://example.com/wp-admin/edit-tags.php?taxonomy=portfolio_executor_profile&post_type=portfolio - create an executor profile. Fill out the fields 'Name' and 'Description'. The 'Description' field contains a link to the executor page.
3. Click 'Add New' in the 'Portfolio' menu and fill out your page. Set the necessary values for the Technologies and Executors Profile widgets.

= How to add an image? =

Use Wordpress meta box to upload images from URL or your local storage. Please note that one of the images should be set as 'Featured' - it will be the main image of your portfolio item.

= I updated the plugin, the template changed, but I would like to revert it back as it was before? What should I do? =

Sometimes during the plugin update the plugin template in your theme is also updated. Meanwhile a backup of the previous template verion is created and it contains the files `portfolio-post.php.bak` and `portfolio.php.bak`. You should compare the old files with the new ones and apply the necessary changes to the new files.

= I was wondering what determines the order of portfolio posts on the Portfolio page =
'orderby'                        => 'menu_order',  menu_order  

orderby (string) - Sort retrieved posts by parameter. Defaults to 'date'.
	'none' - No order (available with Version 2.8).
	'ID' - Order by post id. Note the captialization.
	'author' - Order by author.
	'title' - Order by title.
	'date' - Order by date.
	'modified' - Order by last modified date.
	'parent' - Order by post/page parent id.
	'rand' - Random order.
	'comment_count' - Order by number of comments (available with Version 2.9).
	'menu_order' - Order by Page Order. Used most often for Pages (Order field in the Edit Page Attributes box) and for Attachments (the integer fields in the Insert / Upload Media Gallery dialog), but could be used for any post type with distinct 'menu_order' values (they all default to 0).
	'meta_value' - Note that a 'meta_key=keyname' must also be present in the query. Note also that the sorting will be alphabetical which is fine for strings (i.e. words), but can be unexpected for numbers (e.g. 1, 3, 34, 4, 56, 6, etc, rather than 1, 3, 4, 6, 34, 56 as you might naturally expect).
	'meta_value_num' - Order by numeric meta value (available with Version 2.8). Also note that a 'meta_key=keyname' must also be present in the query. This value allows for numerical sorting as noted above in 'meta_value'. 
'order'=>'ASC',  'order'=>'DESC', - 
    'ASC' - ascending order from lowest to highest values (1, 2, 3; a, b, c).
    'DESC' - descending order from highest to lowest values (3, 2, 1; c, b, a). 

= I'm getting the following error: Fatal error: Call to undefined function get_post_thumbnail_id() =

This error means that your theme doesn't support thumbnails, in order to add this option please find the file 'functions.php' in your theme and add the following strings to this file:
add_action( 'after_setup_theme', 'theme_setup' );

function theme_setup() {
    add_theme_support( 'post-thumbnails' );
}

After that your theme will support thumbnails and the error will disappear.

== Screenshots ==

1. Portfolio Settings page.
2. Add technologies page.
3. Add executors profile page.
4. Add New Portfolio items.
5. Portfolio frontend page (for all portfolios).
6. Portfolio single frontend page.
7. Portfolio frontend page (for all portfolios) without label for additional fields.

== Changelog ==

= V2.13 - 27.05.2013 =
* Bugfix : The error related to changing the name of the field '_prtfl_descr' to the field '_prtfl_short_descr' is fixed. 
* Update : BWS plugins section is updated.

= V2.12 - 12.04.2013 =
* NEW : English language file is updated in the plugin.

= V2.11 - 05.03.2013 =
* NEW : Ukrainian language file is added to the plugin.

= V2.10 - 30.01.2013 =
* NEW: French language file is added to the plugin.

= V2.09 - 29.01.2013 =
* NEW : Add possibility to display Latest Portfolio Items on your page or post with shortcode [latest_portfolio_items count=3].
* Update : We updated all functionality for wordpress 3.5.1.

= V2.08 - 09.10.2012 =
* NEW : The ordering of Portfolio Items was added to Settings page.

= V2.07 - 13.08.2012 =
* Bugfix : German language file is added to the plugin.

= V2.06 - 24.07.2012 =
* Bugfix : Cross Site Request Forgery bug was fixed. 

= V2.05 - 10.07.2012 =
* NEW : Brazilian Portuguese, Hebrew, Hindi, Italian, Persian, Spain language files are added to the plugin.
* NEW : Add possibility to change caption to additional fields.
* NEW : Add possibility to select additional fields to display them on main and single portfolio pages.
* Changed : Template to display portfolio is changed. Changes were done to both main page and single portfolio page.
* Update : We updated all functionality for wordpress 3.4.1.

= V2.04 - 13.04.2012 =
* Change: Replace prettyPhoto library to fancybox library.
* Change: Code that is used to display a lightbox for images in `portfolio.php` and `portfolio-post.php` template files is changed.

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

= V2.13 =
The error related to changing the name of the field '_prtfl_descr' to the field '_prtfl_short_descr' is fixed. BWS plugins section is updated.

= V2.12 =
English language file is updated in the plugin.

= V2.11 =
Ukrainian language file was added to the plugin.

= V2.10 =
French language file was added to the plugin.

= V2.09 =
Add possibility to display Latest Portfolio Items on your page or post with shortcode [latest_portfolio_items count=3]. We updated all functionality for wordpress 3.5.1.

= V2.08 =
The ordering of Portfolio Items was added to Settings page.

= V2.07 =
German language file is added to the plugin.

= V2.06 =
Cross Site Request Forgery bug was fixed. 

= V2.05 =
Brazilian Portuguese, Hebrew, Hindi, Italian, Persian, Spain language files are added to the plugin. Add possibility to change caption to additional fields. Add possibility to select additional fields to display them on main and single portfolio pages. Template to display portfolio is changed. Changes were done to both main page and single portfolio page. We updated all functionality for wordpress 3.4.1.

= V2.04 =
Replace prettyPhoto library to fancybox library. Code that is used to display a lightbox for images in `portfolio.php` and `portfolio-post.php` template files is changed.

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
