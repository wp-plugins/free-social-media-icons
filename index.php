<?php 
/**
Plugin Name: Free Social Media Icons
Description: Free Social Media Icons & Follow/Newsletter Buttons (Facebook, Twitter, Google Plus etc.)
Author: Specific Feeds
Author URI: http://www.specificfeeds.com/
Version: 1.0
 */

global $specificFeedsSocialIcons, $specificFeedsAdminOptions, $icon_list, $default_icon_choice;
$default_add = array( 'follow','facebook','twitter','youtube','pinterest','linkedin' );
$default_icon_choice = get_option( 'default_icon_choice', 'default set' );

$icon_list = array(
	'follow' => array(
		'title' => 'Follow (by Email & RSS)',
		'url' => 'http://www.specificfeeds.com/follow',
		'target' => 'parent',
		'custom_icon_url' => plugins_url('free-social-media-icons').'/assets/icons/' . $default_icon_choice . '/mail.png',
		'default' => true
		),
	'facebook' => array(
		'title' => 'Facebook',
		'url' => 'http://facebook.com/your-fan-page',
		'target' => 'parent',
		'custom_icon_url' => plugins_url('free-social-media-icons').'/assets/icons/' . $default_icon_choice . '/facebook.png',
		'default' => true
		),
	'twitter' => array(
		'title' => 'Twitter',
		'url' => 'http://twitter/your-username',
		'target' => 'parent',
		'custom_icon_url' => plugins_url('free-social-media-icons').'/assets/icons/' . $default_icon_choice . '/twitter.png',
		'default' => true
		),
	'google' => array(
		'title' => 'Google +',
		'url' => 'http://google/your-username',
		'target' => 'parent',
		'custom_icon_url' => plugins_url('free-social-media-icons').'/assets/icons/' . $default_icon_choice . '/google.png',
		'default' => true
		),
	'youtube' => array(
		'title' => 'Youtube',
		'url' => 'http://youtube.com/user/your-username',
		'target' => 'parent',
		'custom_icon_url' => plugins_url('free-social-media-icons').'/assets/icons/' . $default_icon_choice . '/youtube.png',
		'default' => false
		),
	'pinterest' => array(
		'title' => 'Pinterest',
		'url' => 'http//www.pinterest.com/your-username',
		'target' => 'parent',
		'custom_icon_url' => plugins_url('free-social-media-icons').'/assets/icons/' . $default_icon_choice . '/pinterest.png',
		'default' => false
		),
	'linkedin' => array(
		'title' => 'Linkedin',
		'url' => 'http:/www.linkedin/in/your-username',
		'target' => 'parent',
		'custom_icon_url' => plugins_url('free-social-media-icons').'/assets/icons/' . $default_icon_choice . '/linkedin.png',
		'default' => false
		),
	'rss' => array(
		'title' => 'RSS',
		'url' => 'http://www.specificfeeds.com/follow',
		'target' => 'parent',
		'custom_icon_url' => plugins_url('free-social-media-icons').'/assets/icons/' . $default_icon_choice . '/rss.png',
		'default' => false
		),
	'flickr' => array(
		'title' => 'Flickr',
		'url' => 'http:/www.flickr/photos/username',
		'target' => 'parent',
		'custom_icon_url' => plugins_url('free-social-media-icons').'/assets/icons/' . $default_icon_choice . '/flickr.png',
		'default' => false
		),
	'blogger' => array(
		'title' => 'Blogger',
		'url' => '',
		'target' => 'parent',
		'custom_icon_url' => plugins_url('free-social-media-icons').'/assets/icons/' . $default_icon_choice . '/blogger.png',
		'default' => false
		),
	'reddit' => array(
		'title' => 'Reddit',
		'url' => '',
		'target' => 'parent',
		'custom_icon_url' => plugins_url('free-social-media-icons').'/assets/icons/' . $default_icon_choice . '/reddit.png',
		'default' => false
		),
	'delicious' => array(
		'title' => 'Delicious',
		'url' => '',
		'target' => 'parent',
		'custom_icon_url' => plugins_url('free-social-media-icons').'/assets/icons/' . $default_icon_choice . '/delicious.png',
		'default' => false
		),
	'stumbleupon' => array(
		'title' => 'StumbleUpon',
		'url' => '',
		'target' => 'parent',
		'custom_icon_url' => plugins_url('free-social-media-icons').'/assets/icons/' . $default_icon_choice . '/stumbleupon.png',
		'default' => false
		)
);

require_once('specific-feeds-social-icons.php');
require_once('specific-feeds-admin-options.php');

$specificFeedsSocialIcons = new specificFeedsSocialIcons();
$specificFeedsMetaBoxes = new specificFeedsMetaBoxes;
$specificFeedsAdminOptions = new specificFeedsAdminOptions();

/* 
	Initialize the theme's widgets. 
*/
add_action( 'widgets_init','specificFeedsRegWidget' );
function specificFeedsRegWidget() 
{
	require_once( dirname(__FILE__) .'/specific-feeds-widget.php' );

	register_widget( 'specificFeedsWidget' );
}

register_activation_hook( __FILE__, array( 'specificFeedsSocialIcons', 'specificFeedsInstall' ) );
register_deactivation_hook( __FILE__, array( 'specificFeedsSocialIcons', 'specificFeedsUninstall' ) );