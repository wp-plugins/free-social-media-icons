<?php 

class specificFeedsMetaBoxes
{
	function __construct()
	{
		add_action( 'do_meta_boxes', array( $this, 'specificFeedsCustomImageBox' ), 0 );

		add_action( 'admin_head-post-new.php', array( $this, 'specificFeedsImageBoxText' ) );
		add_action( 'admin_head-post.php', array( $this, 'specificFeedsImageBoxText' ) );

		add_action( 'add_meta_boxes', array( $this, 'specificFeedsImageBox' ), 10, 0 );
		add_action( 'add_meta_boxes', array( $this, 'specificFeedsSettingsBox' ), 20, 0 );

		if( function_exists( 'add_image_size' ) )
		{
			add_image_size( 'tiny-icons', 16, 16, true );
			add_image_size( 'mid-icons', 32, 32, true );
			add_image_size( 'big-icons', 64, 64, true );
			add_image_size( 'large-icons', 128, 128, true );
		}
	}

	function specificFeedsCustomImageBox()
	{
		remove_meta_box( 'postimagediv', 'sf_social_icons', 'side' );
		remove_meta_box( 'submitdiv', 'sf_social_icons', 'side' );
	}

	function specificFeedsImageBoxText( $content )
	{
		if ( 'sf_social_icons' == $GLOBALS['post_type'] )
			add_filter( 'admin_post_thumbnail_html', array( $this, 'specificFeedsSetText' ) );
	}

	function specificFeedsSetText( $content )
	{
		return str_replace( 
			array(
				__('Set featured image'),
				__('Remove featured image')
			),
			array(
				__('Set custom icon'),
				__('Remove custom icon')
			),
			$content );
	}

	public function specificFeedsImageBox()
	{
		add_meta_box(
			'postimagediv', 
			__( 'Custom Icon Image', 'specificfeed' ), 
			'post_thumbnail_meta_box', 
			'sf_social_icons', 
			'normal', 
			'high'
		);

		add_meta_box(
			'sf_social_icons_logo_settings',
			__( 'Choose icon', 'specificfeed' ),
			array( $this, 'specificFeedsImageBoxSettings' ),
			'sf_social_icons',
			'normal',
			'high'
		);
	}

	public function specificFeedsImageBoxSettings()
	{
		global $post;
		$status = get_post_status( $post->ID );

		if( !in_array( $status, array( 'draft', 'publish' ) ) )
		{
			echo '<p>Either publish or save as draft to load images</p>';
		}
		else
		{
			echo '<p>Upload image above or select image below</p>';

			$title = strtolower( get_the_title( $post->ID ) );
			$title = trim( str_replace( ' ', '', $title) );

			if( strstr( $title, 'follow') )
				$title = 'mail';

			$icons_dir = dirname( __FILE__ ) . '/assets/icons/*';
			$icons = array();

			foreach( glob( $icons_dir ) as $dir )
			{
				foreach( glob( $dir . '/*' ) as $image )
				{
					$title = trim( str_replace( '+', '', $title ) );
					
					if( strstr( strtolower( $image ), $title ) )
					{
						$icons[] = plugins_url( str_replace( dirname( __FILE__ ), '', $image ), dirname( __FILE__ ).'/free-social-media-icons' );
					}
				}

			}
			
			$custom_icon_url = $this->_get_post_meta( $post->ID, 'custom_icon_url', $icons[0] );

			foreach( $icons as $icon )
			{
				if( $custom_icon_url == $icon )
				{
					$class = 'active';
				}
				else
				{
					$class = '';
				}

				echo '<img src="'.$icon.'" class="icon-image ' . $class . '" />';
			}

			echo '<input type="hidden" name="custom_icon_url" id="custom_icon_url" value="' . $custom_icon_url . '" />';
		}
	}

	public function specificFeedsSettingsBox()
	{
		add_meta_box(
			'sf_social_icons_settings',
			__( 'Social site settings', 'specificfeed' ),
			array( $this, 'specificFeedsSocialSettings' ),
			'sf_social_icons',
			'normal',
			'core'
		);
	}

	public function specificFeedsSocialSettings()
	{
		global $post;
		$url = $this->_get_post_meta( $post->ID, 'icon_url', '' );
		$target = $this->_get_post_meta( $post->ID, 'target', 'parent' );
		?>
		
		<table class="form-table">
		<tbody>
		<tr>
			<th><label for="icon_url">URL:</label></th>
			<td>
				<input type="text" name="icon_url" id="icon_url" value="<?php echo $url ?>" placeholder="Enter your URL" class="regular-text" tabindex="10">
				<span class="description">(e.g. Please include the http://)</span>
			</td>
		</tr>
		<tr>
			<th><label for="menu_order">Sort Order:</label></th>
			<td><input type="text" name="menu_order" id="menu_order" value="<?php echo $post->menu_order ?>" size="3"></td>
		</tr>
		<tr>
			<th><label for="target">Target:</label></th>
			<td>
				<input type="radio" name="target" id="target" value="parent" <?php echo checked( $target, 'parent' ) ?> /> Open in a new window <br />
				<input type="radio" name="target" id="target" value="self" <?php echo checked( $target, 'self' ) ?> /> Open in the same window
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="hidden" name="post_status" value="publish">
				<input type="submit" class="button-primary" name="save" value="Save Changes">
			</td>
		</tr>
		</tbody>
		</table>

		<?php
		wp_nonce_field( 'sf_social_icons_meta_box', 'sf_social_icons_meta_box_nonce' );
	}

	private function _get_post_meta( $post_id, $value_id, $default )
	{
		$value = get_post_meta( $post_id, $value_id, true );

		return strlen( $value ) ? $value : $default;
	}
}
