<?php global $icon_list;
$display_list = array(); ?>
<h2 id="sf_step_one">1.) Which icons <span>do you want to show?</span></h2>

<div id="sf_list_extras">
<p>
	Add common icons:

	<?php foreach( $icon_list as $key => $icon )
	{
		$display_list[] = "<a href='" . admin_url('admin-ajax.php?action=sf_add_common&to_add='.$key) . "'>{$icon['title']}</a>";
	} 
	echo implode( ', ', $display_list );
	?>
	<span class="spinner" id="add_common_spinner"></span>
</p>
<p><a href="<?php echo admin_url('post-new.php?post_type=sf_social_icons') ?>">Upload a new icon</a></p>
<p>Change the order of the icons with drag & drop</p>

<h2 class="area-title">2.) What design <span>do you want to give them?</span></h2>

<?php 
$icons_dir = dirname( __FILE__ ) . '/assets/icons/*';
$icons = array();

foreach( glob( $icons_dir ) as $dir )
{
	echo '<div class="chose-icons-list">';
	echo '<span><input type="radio" name="choose-default" value="'.basename( $dir ).'" />&nbsp;' . ucwords( str_replace('_', ' ', basename( $dir ) ) ) . '</span>';
	foreach( glob( $dir . '/*' ) as $image )
	{
		$base = substr( basename( $image ), 0, -4 );

		foreach( $titles as $title )
		{
			$title = trim( str_replace( '+', '', $title ) );
			
			if( stristr( $base, $title ) || stristr( $title, $base ) )
			{
				echo '<img src="' . plugins_url( str_replace( dirname( __FILE__ ), '', $image ), dirname( __FILE__ ).'/free-social-media-icons' ) . '" />&nbsp;&nbsp;';
			}
		}
	}
	echo '</div>';

}
?>

<p><input type="submit" value="Update icon designs" class="button-primary set-icons" /></p>


<h2 class="area-title">3.) Go to <a href="<?php echo admin_url('widgets.php') ?>">Widgets</a> and select where you want to show the icons on the site</h2>
<p>If you also want to display them on every post, do that under <a href="<?php echo admin_url('edit.php?post_type=sf_social_icons&page=specific-feeds-admin-options.php') ?>">Options</a> (where you can also do some more selections)</p>

<h2 class="area-title">4.) Say &laquo; Thank You &raquo; :)</h2>
<p>This plugin is FREE &mdash; the only thing we ask from you is that you <a href="mailto:feedback@specificfeeds.com">give us Feedback</a>. When you're not happy, please first <a href="mailto:feedback@specificfeeds.com">get in touch with us</a>, we'll sort it!</p>
</div>