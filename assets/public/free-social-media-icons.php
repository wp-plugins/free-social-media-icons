<?php

require_once('/../../specific-feeds-social-icons.php');
require_once('/../../../../../wp-blog-header.php');
require_once('/../../index.php');

global $sf_settings, $specificFeedsSocialIcons;
$sf_settings = $specificFeedsSocialIcons->getSettings();

$sizes = array(
	'tiny-icons' 	=> "width: 16px;\nheight: 16px\n",
	'mid-icons' 	=> "width: 32px;\nheight: 32px\n",
	'big-icons' 	=> "width: 64px;\nheight: 64px\n",
	'large-icons' 	=> "width: 128px;\nheight: 128px\n",
	'custom' 		=> "width: {$sf_settings['width']}px;\nheight: {$sf_settings['height']}px\n");

header("Content-type: text/css; charset: UTF-8");
?>
.sf-social-icon-list ul {
	list-style: none;
	margin: 0 !important;
	padding: 0 !important;
}
.sf-social-icon-list li {
	list-style: none;
	margin: <?php echo $sf_settings['margin']; ?>px;
	padding: 0;
}
.sf-social-icon-list ul.horizontal:after {
	content: '';
	display: table;
	clear: both;
}
.sf-social-icon-list ul.horizontal li {
	display: inline-block;
	float: left;
}
.sf-social-icon {
	<?php echo $sizes[$sf_settings['size']] ?>
}