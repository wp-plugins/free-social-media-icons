<?php 
class specificFeedsAdminOptions
{
	function __construct()
	{
		add_action( 'admin_menu', array( $this, 'specificFeedsRegisterOptionsPage' ), 9 );

		if( isset($_POST['sf_settings'] ) )
		{
			add_action( 'init', array( $this, 'specificFeedsSaveSettings' ) );
		}
	}

	public function specificFeedsRegisterOptionsPage()
	{
		add_submenu_page( 'edit.php?post_type=sf_social_icons', __( 'options', 'specificfeeds'), __( 'Options', 'specificfeeds'), 'manage_options', basename(__FILE__), array( $this, 'specificFeedsAdminPage' ) );
	}

	public function specificFeedsAdminPage()
	{
		$sf_settings = maybe_unserialize( get_option( 'sf_settings' ) );
		?>
		<form method="post" action="">
			<table class="form-table">
			<tbody>
			<tr>
				<th><label for="icon_url">Icon Size:</label></th>
				<td>
					<select name="sf_settings[size]" id="sf_settings_size">
						<option <?php echo selected( $sf_settings['size'], 'tiny-icons') ?> value="tiny-icons">16 x 16</option>
						<option <?php echo selected( $sf_settings['size'], 'mid-icons') ?> value="mid-icons">32 x 32 (recommended)</option>
						<option <?php echo selected( $sf_settings['size'], 'big-icons') ?> value="big-icons">64 x 64 (recommended)</option>
						<option <?php echo selected( $sf_settings['size'], 'custom') ?> value="custom">Custom</option>
					</select>
				</td>
			</tr>
			<tr <?php echo ($sf_settings['size'] == 'custom') ? '' : 'class="custom-settings"' ?>>
				<th><label for="custom_size">Custom Size:</label></th>
				<td>
					Custom Width: <input type="text" name="sf_settings[width]" id="sf_settings_width" value="<?php echo $sf_settings['width'] ?>" size="3">px<br />
					Custom Height: <input type="text" name="sf_settings[height]" id="sf_settings_height" value="<?php echo $sf_settings['height'] ?>" size="3">px
				</td>
			</tr>
			<tr>
				<th><label for="sf_settings[margin]">Icon Margin:</label></th>
				<td>
					<input type="text" name="sf_settings[margin]" id="sf_settings_margin" value="<?php echo $sf_settings['margin'] ?>" size="3">px<br />
					<span class="description">Spacing between icons</span>
				</td>
			</tr>
			<tr>
				<th><label for="sf_settings[direction]">Direction:</label></th>
				<td>
					<input type="radio" name="sf_settings[direction]" class="sf_settings_direction" id="sf_settings_direction" value="horizontal" <?php echo checked( $sf_settings['direction'], 'horizontal' ) ?> /> Horizontally <br />
					<input type="radio" name="sf_settings[direction]" class="sf_settings_direction" id="sf_settings_direction" value="vertical" <?php echo checked( $sf_settings['direction'], 'vertical' ) ?> /> Vertically
				</td>
			</tr>
			<tr>
				<th><label for="sf_settings[rows]">Number of Columns:</label></th>
				<td>
					<input type="text" name="sf_settings[rows]" id="sf_settings_rows" value="<?php echo $sf_settings['rows'] ?>" <?php echo ( $sf_settings['direction'] == 'vertical' ) ? '' : 'disabled' ?> size="3">
				</td>
			</tr>
			<tr>
				<th><label for="sf_settings['content]">Add to Content:</label></th>
				<td>
					<select name="sf_settings[content]" id="sf_settings_content">
						<option <?php echo selected( $sf_settings['content'], 'none') ?> value="none">Do not add to blog posts</option>
						<option <?php echo selected( $sf_settings['content'], 'before') ?> value="before">Add before blog posts</option>
						<option <?php echo selected( $sf_settings['content'], 'after') ?> value="after">Add after blog posts</option>
						<option <?php echo selected( $sf_settings['content'], 'both') ?> value="both">Add before and after blog posts</option>
					</select>
				</td>
			</tr>
			</tbody>
			</table>
			<p><input type="submit" class="button-primary" name="save_sf_settings" value="Save Settings"></p>
		</form>
		<?php
	}

	public function specificFeedsSaveSettings()
	{
		update_option( 'sf_settings', serialize( $_POST['sf_settings'] ) );

		wp_redirect( admin_url( 'edit.php?post_type=sf_social_icons' ) );
	}
}