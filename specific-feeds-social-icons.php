<?php 
if(!class_exists('specificFeedsSocialIcons')):

require_once('specific-feeds-meta-boxes.php');

class specificFeedsSocialIcons
{
	function __construct()
	{
		global $post;

		add_theme_support( 'post-thumbnails', array( 'sf_social_icons' ) );

		add_filter( 'plugin_action_links', array( $this, 'specificFeedsSettingsLink' ), 10, 2 );

		add_action( 'init', array( $this, 'specificFeedsSocialIcons' ), 0 );
		add_filter( 'bulk_actions-edit-sf_social_icons', array( $this, 'specificFeedsRemoveBulk' ) );
		add_filter( 'months_dropdown_results', array( $this, 'specificFeedsRemoveBulk' ) );
		add_filter( 'post_row_actions', array( $this, 'specificFeedsRemoveRowActions' ) );
		add_filter( 'bulk_post_updated_messages', array( $this, 'specificFeedsCustomMessages' ) );
		add_filter( 'post_updated_messages', array( $this, 'specificFeedsCustomEditMessages' ) );
		add_filter( 'redirect_post_location', array( $this, 'specificFeedsRedirectPostLocation' ), 10, 2 );
		add_action( 'save_post', array( $this, 'saveSpecifFeedsData' ) );		

		// add_action( 'wp_head', array( $this, 'stylesAndScripts' ) );
		add_action( 'admin_init', array( $this, 'adminGeneralStyles' ) );
		add_action( 'admin_print_scripts-post-new.php',  array( $this, 'adminStylesAndScripts' ), 11 );
		add_action( 'admin_print_scripts-post.php',  array( $this, 'adminStylesAndScripts' ), 11 );
		add_action( 'admin_print_scripts-edit.php',  array( $this, 'adminStylesAndScripts' ), 11 );
		add_action( 'admin_print_scripts-sf_social_icons_page_specific-feeds-admin-options',  array( $this, 'adminStylesAndScripts' ), 11 );

		add_action( 'manage_sf_social_icons_posts_columns', array( $this, 'specificFeedsAddColumns') );
		add_action( 'manage_posts_custom_column', array( $this, 'specificFeedsManageColumns'), 10, 2 );

		add_action( 'parse_query', array( $this, 'specificFeedsSetSortOrder' ) );

		add_action( 'wp_ajax_sf_icons_menu_sort', array( $this, 'specificFeedsUpdateOrder' ) );
		add_action( 'wp_ajax_sf_add_common', array( $this, 'specificFeedsAddCommon' ) );
		add_action( 'wp_ajax_sf_set_default_icon', array( $this, 'spedificFeedsSetIconSet' ) );
		add_action( 'in_admin_footer', array( $this, 'specificFeedsPostListExtras' ) );

		add_action( 'the_content', array( $this, 'specificFeedsContent' ) );
		add_shortcode( 'specificfeeds', array( $this, 'specificFeedsShortCode' ) );

		if( is_admin() && ( $_REQUEST['post_type'] == 'sf_social_icons' ) && ( $_REQUEST['message'] == '1' ) )
		{
			$notices = get_option( 'specificFeedsAdminNotice', array() );
			$notices[] = 'Icon updated';
			update_option( 'specificFeedsAdminNotice', $notices );
		}

		add_action( 'admin_notices', array( $this, 'specificFeedsAdminNotice' ) );			
	}

	function specificFeedsSettingsLink( $links, $file )
	{
		if(!is_admin() || !current_user_can('manage_options'))
			return $links;

		static $plugin;

		$plugin = 'free-social-media-icons/index.php';

		if( $file == $plugin )
		{
			$settings_link = sprintf( '<a href="%s">%s</a>', admin_url( 'edit.php' ).'?post_type=sf_social_icons&page=specific-feeds-admin-options.php', __( 'Settings', 'specificfeeds' ) );
			array_unshift( $links, $settings_link );
		}

		return $links;
	}

	function specificFeedsRedirectPostLocation( $location, $post_id )
	{
		$post_type = get_post_type( $post_id );

		if('sf_social_icons' == $post_type )
		{
			if( strstr( $location, 'message=1' ) )
			{
				$location = admin_url('edit.php?post_type=sf_social_icons&message=1');

			}
		}

		return $location;
	}

	function specificFeedsAdminNotice()
	{
		$notices = get_option( 'specificFeedsAdminNotice', array() );

		foreach( $notices as $key => $notice )
		{
			echo "<div class='updated below-h2 specific-feeds-notice'><p>$notice</p></div>";
		}

		delete_option( 'specificFeedsAdminNotice' );
	}

	public function specificFeedsSocialIcons() 
	{
		$labels = array(
			'name'					=> _x( 'Free Social Media Icons', 'Post Type General Name', 'specificfeeds' ),
			'singular_name'	  		=> _x( 'Free Social Media Icons', 'Post Type Singular Name', 'specificfeeds' ),
			'menu_name'		  		=> __( 'Free Social Media Icons', 'specificfeeds' ),
			'parent_item_colon' 	=> __( 'Parent Icon:', 'specificfeeds' ),
			'all_items'				=> __( 'Manage Icons', 'specificfeeds' ),
			'view_item'				=> __( 'View Icon', 'specificfeeds' ),
			'add_new_item'			=> __( 'Add New Icon', 'specificfeeds' ),
			'add_new'				=> __( 'Add New Icon', 'specificfeeds' ),
			'edit_item'				=> __( 'Edit Icon', 'specificfeeds' ),
			'update_item'			=> __( 'Update Icon', 'specificfeeds' ),
			'search_items'			=> __( 'Search Icons', 'specificfeeds' ),
			'not_found'				=> __( 'No icons found', 'specificfeeds' ),
			'not_found_in_trash'	=> __( 'No icons found in Trash', 'specificfeeds' ),
		);

		$args = array(
			'labels'				=> $labels,
			'supports'				=> array( 'title', 'thumbnail' ),
			'hierarchical'			=> false,
			'public'				=> false,
			'show_ui'				=> true,
			'show_in_menu'			=> true,
			'show_in_nav_menus'		=> false,
			'show_in_admin_bar'		=> true,
			'menu_position'			=> 60,
			'can_export'			=> true,
			'has_archive'			=> false,
			'exclude_from_search'	=> true,
			'publicly_queryable'	=> false,
			'rewrite'				=> false,
			'capability_type'		=> 'page'
		);

		register_post_type( 'sf_social_icons', $args );
	}

	public function specificFeedsRemoveBulk( $actions )
	{
		global $post;

		return $post->post_type == 'sf_social_icons' ? array() : $actions;
	}

	public function specificFeedsRemoveRowActions( $actions )
	{
		global $post;

		if( $post->post_type == 'sf_social_icons' )
			unset( $actions['inline hide-if-no-js'] );

		return $actions;
	}

	public function specificFeedsCustomMessages( $messages )
	{
		global $post;
		global $bulk_counts;

		if( $post->post_type == 'sf_social_icons' )
		{
			$messages['post'] = array(
				'updated'   => _n( '%s icon updated.', '%s icons updated.', $bulk_counts['updated'] ),
				'locked'    => _n( '%s icon not updated, somebody is editing it.', '%s icons not updated, somebody is editing them.', $bulk_counts['locked'] ),
				'deleted'   => _n( '%s icon permanently deleted.', '%s icons permanently deleted.', $bulk_counts['deleted'] ),
				'trashed'   => _n( '%s icon moved to the Trash.', '%s icons moved to the Trash.', $bulk_counts['trashed'] ),
				'untrashed' => _n( '%s icon restored from the Trash.', '%s icons restored from the Trash.', $bulk_counts['untrashed'] ),
			);
		}

		return $messages;
	}

	public function specificFeedsCustomEditMessages( $messages )
	{
		global $post;
		global $bulk_counts;

		if( $post->post_type == 'sf_social_icons' )
		{
			$messages['post'] = array(
				 0 => '', // Unused. Messages start at index 1.
				 1 => __('Icon updated.'),
				 2 => __('Custom field updated.'),
				 3 => __('Custom field deleted.'),
				 4 => __('Icon updated.'),
				/* translators: %s: date and time of the revision */
				 5 => isset($_GET['revision']) ? sprintf( __('Icon restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
				 6 => __('Icon published.'),
				 7 => __('Icon saved.'),
				 8 => __('Icon submitted.'),
				 9 => __('Icon scheduled for: <strong>%1$s</strong>.'),
				10 => __('Icon draft updated.'),
			);
		}

		return $messages;
	}

	public function saveSpecifFeedsData( $post_id )
	{
		// Check if our nonce is set.
		if ( ! isset( $_POST['sf_social_icons_meta_box_nonce'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['sf_social_icons_meta_box_nonce'], 'sf_social_icons_meta_box' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}

		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		update_post_meta( $post_id, 'icon_url', $_POST['icon_url'] );
		update_post_meta( $post_id, 'target', $_POST['target'] );

		if( strlen( $_POST['custom_icon_url'] ) )
		{
			update_post_meta( $post_id, 'custom_icon_url', $_POST['custom_icon_url'] );
		}
		else
		{
			delete_post_meta( $post_id, 'custom_icon_url' );
		}
	}

	public function specificFeedsSetSortOrder( $query )
	{
		global $pagenow;
		if( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'sf_social_icons' )
		{
			$query->query_vars['orderby'] = 'menu_order';
			$query->query_vars['order'] = 'asc';
		}
	}

	public function specificFeedsAddColumns( $columns )
	{
		unset( $columns['cb'] );
		unset( $columns['date'] );

		$columns = array_merge( 
			array( 'sort' => __( 'Sort', 'specificfeeds' ), 'icon' => __( 'Icon', 'specificfeeds' ) ),
			$columns );

		return array_merge( $columns, 
				array('url' => __( 'URL', 'specificfeeds' ),
					'target' => __( 'Open In', 'specificfeeds' ),
					// 'menu_order' => __( 'Sort Order', 'specificfeeds' ),
					'edit' => __( 'Edit', 'specificfeeds' ),
					'delete' => __( 'Delete', 'specificfeeds' ),
					)
			);
	}

	public function specificFeedsManageColumns( $column, $post_id )
	{
		global $post;
		switch( $column )
		{
			case 'sort':
				echo '<span class="sort-handle ui-icon ui-icon-arrowthick-2-n-s"></span>';
			break;

			case 'url':
				if( stristr( $post->post_title, 'Follow' ) )
				{
					echo '(No link required; Allows visitors to subscribe to<br> your blog by Email or RSS &mdash; for FREE)';
				}
				else
				{
					echo get_post_meta( $post_id, 'icon_url', true );					
				}
			break;

			case 'menu_order':
				echo '<input type="text" name="menu_order" id="menu_order" class="menu-order-set" value="'.$post->menu_order.'" size="3" /><span class="spinner"></span>';
			break;

			case 'target':
				echo get_post_meta( $post_id, 'target', true ) == 'parent' ? 'New window' : 'Same window';
			break;

			case 'icon':
				if( has_post_thumbnail( $post_id ) )
				{
					echo get_the_post_thumbnail( $icon->ID, 'big-icons', array( 'class' => 'sf-social-icon' ) );
				}
				else
				{
					echo '<img src="' . get_post_meta( $post_id, 'custom_icon_url', true ) . '" class="list-icon-image" />';
				}
			break;

			case 'edit':
				echo '<a href="' . admin_url( 'post.php?post=' . $post_id . '&action=edit') . '">Edit</a>';
			break;

			case 'delete':
				echo '<a href="' . get_delete_post_link( $post_id, '', true ) . '">Delete</a>';
			break;
		}
	}

	public function specificFeedsUpdateOrder()
	{
		global $wpdb;

		$sql = "UPDATE `{$wpdb->prefix}posts` SET `menu_order` = CASE ";

		unset($_POST['action']);
		foreach( $_POST as $key => $post )
		{
			$pIDs[] = $post['post_id'];
			$sql .= "WHEN ID = '{$post['post_id']}' THEN '{$post['menu_order']}' ";
		}

		$sql .= "END WHERE ID IN (".implode( ',', $pIDs ).")";

		$wpdb->query( $sql );

		die();
	}

	public function specificFeedsPostListExtras()
	{
		global $wpdb;

		$screen = get_current_screen();
		$title_query = $wpdb->get_results("SELECT `post_title` FROM {$wpdb->posts} WHERE `post_type` = 'sf_social_icons'");
		$titles = array();

		foreach( $title_query as $tq ) 
		{
			if( stristr( $tq->post_title, 'follow' ) ) 
			{
				$tq->post_title = 'mail';
			}
			$titles[] = $tq->post_title;
		}

		if( ( isset( $_GET['post_type'] ) && ( $_GET['post_type'] == 'sf_social_icons' ) ) && ( $screen->id == 'edit-sf_social_icons' ) )
		{
			require_once( dirname( __FILE__ ) . '/specific-feeds-post-bottom.php' );
		}

		?>
		<script>
		jQuery(document).ready(function()
		{
			jQuery('.specific-feeds-notice').insertAfter('div.wrap h2:first');
		})
		</script>
		<?php
	}

	public function specificFeedsContent( $content )
	{
		if( is_page() )
			return $content;

		$settings = maybe_unserialize( get_option( 'sf_settings' ) );
		$settings['size'] = ( $settings['size'] == 'custom' ) ? array( $settings['width'], $settings['height'] ) : $settings['size'];

		if( $settings['content'] == 'none' )
			return $content;

		$icon_list = $this->specificFeedsGenerator( $settings );

		if( ( $settings['content'] == 'before' ) || ( $settings['content'] == 'both' ) )
			$content = $icon_list . $content;

		if( ( $settings['content'] == 'after' ) || ( $settings['content'] == 'both' ) )
			$content = $content . $icon_list;

		return $content;
	}

	public function specificFeedsShortCode( $settings )
	{
		$sf_settings = maybe_unserialize( get_option( 'sf_settings' ) );
		$settings = shortcode_atts( $sf_settings, $settings );

		$settings['size'] = ( $settings['size'] == 'custom' ) ? array( $settings['width'], $settings['height'] ) : $settings['size'];

		$icon_list = $this->specificFeedsGenerator( $settings );

		return $icon_list;
	}

	public function specificFeedsGenerator( $settings )
	{
		$thumb_size = $settings['size'];
		$settings['size'] = is_array( $settings['size'] ) ? 'custom' : $settings['size'];

		$sizes = array(
			'tiny-icons' 	=> "width: 16px; height: 16px; max-width: 16px",
			'mid-icons' 	=> "width: 32px; height: 32px; max-width: 32px",
			'big-icons' 	=> "width: 64px; height: 64px; max-width: 64px",
			'custom' 		=> "width: {$settings['width']}px; height: {$settings['height']}px; max-width: {$settings['width']}px;" );

		$width = array(
			'tiny-icons' 	=> 16,
			'mid-icons' 	=> 32,
			'big-icons' 	=> 64,
			'custom' 		=> $settings['width'] );

		$icons = get_posts( array( 'post_type' => 'sf_social_icons', 'orderby' => 'menu_order', 'order' => 'asc', 'posts_per_page' => -1 ) );
		$direction = $settings['direction'];
		$_numIcons = count( $icons );
		$_colCount = $settings['rows'] ? $settings['rows'] : 1;
		$_tableWidth = ( $direction == 'vertical' ) ? ( $width[$settings['size']] * ceil( $_colCount / 2 ) ) + (ceil( $_colCount / 2 ) + $settings['margin'] ) : $_numIcons * ( $width[$settings['size']] + $settings['margin'] );
		
		$icon_list = array( 
			'<div class="sf-social-icon-list">',
			'<table border="0" cellspacing="0" cellpadding="">');

		$location = 0;
		foreach( $icons as $key => $icon )
		{
			$link = "<a href='{$icon->icon_url}' target='{$icon->target}'>";			

			if( ( $direction == 'vertical' ) && ( ( $location % $_colCount ) == 0) )
			{
				$icon_list[] = '<tr>';
			}

			if( has_post_thumbnail( $icon->ID ) )
			{
				$icon_list[] = '<td style="padding: ' . $settings['margin'] . 'px">' . $link . get_the_post_thumbnail( $icon->ID, $thumb_size, array( 'class' => 'sf-social-icon' ) ) . '</a></td>';

				$location++;
			}
			else if( $custom_icon_url = get_post_meta( $icon->ID, 'custom_icon_url', true ) )
			{
				if( !is_array( $settings['size'] ) )
				{
					$icon_list[] = '<td style="padding: ' . $settings['margin'] . 'px">' . $link . '<img src="' . $custom_icon_url . '" class="sf-social-icon" style="' . $sizes[$settings['size']] . '" /></a></td>';
				}
				else
				{
					$icon_list[] = '<td style="padding: ' . $settings['margin'] . 'px">' . $link . '<img src="' . $custom_icon_url . '" class="sf-social-icon" style="' . $sizes['custom'] . '" /></a></td>';
				}

				$location++;
			}

			if( ( $direction == 'vertical' ) && ( ( $location % $_colCount ) == 0) )
			{
				$icon_list[] = '</tr>';
			}
		}

		$icon_list[] = '</table>';
		$icon_list[] = '</div>';

		return implode( "\n", $icon_list );
	}

	public function stylesAndScripts()
	{
		if( !is_admin() )
		{
			$sf_settings = maybe_unserialize( get_option( 'sf_settings' ) );

			$sizes = array(
				'tiny-icons' 	=> "width: 16px;\nheight: 16px\n",
				'mid-icons' 	=> "width: 32px;\nheight: 32px\n",
				'big-icons' 	=> "width: 64px;\nheight: 64px\n",
				'custom' 		=> "width: {$sf_settings['width']}px;\nheight: {$sf_settings['height']}px\n");

			?>
			<style>
			.sf-social-icon-list ul {
				list-style: none;
				margin: 0 !important;
				padding: 0 !important;
			}
			.sf-social-icon-list td {
				list-style: none;
				padding: <?php echo $sf_settings['margin']; ?>px;
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
			</style>
			<?php
		}
	}

	public function adminStylesAndScripts()
	{
		global $post_type;
		$post_type = strlen( $post_type ) ? $post_type : $_REQUEST['post_type'];

		if( is_admin() && 'sf_social_icons' == $post_type )
		{
			wp_enqueue_style( 'jquery-ui-smoothness', 'https://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/themes/smoothness/jquery-ui.css?ver=1.10.3' );
			wp_enqueue_style( 'specific-feeds-list-style', plugins_url('free-social-media-icons') . '/assets/admin/free-social-media-icons.css' );
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-sortable', false, array( 'jquery-ui-core' ) );
			wp_enqueue_script( 'specific-feeds-list-script', plugins_url('free-social-media-icons') . '/assets/admin/free-social-media-icons.js', array('jquery', 'jquery-ui-core', 'jquery-ui-sortable'), '1.0.0', true );	
		}	
	}

	public function adminGeneralStyles()
	{
		wp_enqueue_style( 'specific-feeds-general-style', plugins_url('free-social-media-icons') . '/assets/admin/specific-feeds-general-style.css' );
	}

	public function specificFeedsInstall()
	{
		global $icon_list;
		$sf_settings = (array)maybe_unserialize( get_option( 'sf_settings' ) );
		$settings = array_replace( array(
			'size' => 'mid-icons',
			'margin' => '5',
			'direction' => 'horizontal',
			'content' => 'after'),
		$sf_settings);

		add_option( 'sf_settings', $settings );
		add_option( 'default_icon_choice', 'default set' );
		$count = 0;

		foreach( $icon_list as $key => $site )
		{
			if( null == get_page_by_title( $site['title'], OBJECT, 'sf_social_icons' ) && $site['default'] )
			{
				$post_id = wp_insert_post( array(
					'post_title' 		=> $site['title'],
					'post_status' 		=> 'publish',
					'post_type'			=> 'sf_social_icons',
					'comment_status'	=> 'closed',
					'menu_order'		=> $count++
					)
				);

				update_post_meta( $post_id, 'icon_url', $site['url'] );
				update_post_meta( $post_id, 'target', $site['target'] );
				update_post_meta( $post_id, 'custom_icon_url', $site['custom_icon_url'] );
			}
		}

		$notices = get_option( 'specificFeedsAdminNotice', array() );
		$notices[] = sprintf( '%s <a href="%s">%s</a> %s', __('Thank you for installing the Free Social Media Icons plugin. Please go to the', 'specificfeeds'), admin_url( 'edit.php' ).'?post_type=sf_social_icons', __( 'Plugin Settings Page ', 'specificfeeds' ), __('to make your selections.', 'specificfeeds') );
		update_option( 'specificFeedsAdminNotice', $notices );
	}

	public function specificFeedsUninstall()
	{
		delete_option( 'default_icon_choice' );
	}

	public function specificFeedsAddCommon()
	{
		global $icon_list;
		$sf_settings = (array)maybe_unserialize( get_option( 'sf_settings' ) );
		$settings = array_replace( array(
			'size' => 'mid-icons',
			'margin' => '5',
			'direction' => 'horizontal',
			'content' => 'after'),
		$sf_settings);

		update_option( 'sf_settings', $settings );
		
		$count = wp_count_posts( 'sf_social_icons' );		
		$site = $icon_list[$_GET['to_add']];

		$post_id = wp_insert_post( array(
			'post_title' 		=> $site['title'],
			'post_status' 		=> 'publish',
			'post_type'			=> 'sf_social_icons',
			'comment_status'	=> 'closed',
			'menu_order'		=> $count->publish
			)
		);

		update_post_meta( $post_id, 'icon_url', $site['url'] );
		update_post_meta( $post_id, 'target', $site['target'] );
		update_post_meta( $post_id, 'custom_icon_url', $site['custom_icon_url'] );

		wp_redirect( admin_url( 'edit.php?post_type=sf_social_icons' ) );
		die();
	}

	public function spedificFeedsSetIconSet()
	{
		global $wpdb;
		$icon_set = $_GET['icon_set'];
		$title_query = $wpdb->get_results("SELECT `id`, `post_title` FROM {$wpdb->posts} WHERE `post_type` = 'sf_social_icons'");
		$titles = array();
		$dir = $icons_dir = dirname( __FILE__ ) . '/assets/icons/' . $icon_set . '*';

		foreach( $title_query as $tq ) 
		{
			if( stristr( $tq->post_title, 'follow' ) ) 
			{
				$tq->post_title = 'mail';
			}
			$titles[$tq->id] = $tq->post_title;
		}

		foreach( glob( $dir . '/*' ) as $image )
		{
			$base = substr( basename( $image ), 0, -4 );

			foreach( $titles as $id => $title )
			{
				$title = trim( str_replace( '+', '', $title ) );
				
				if( stristr( $base, $title ) || stristr( $title, $base ) )
				{
					update_post_meta( $id, 'custom_icon_url', plugins_url( str_replace( dirname( __FILE__ ), '', $image ), dirname( __FILE__ ).'/free-social-media-icons' ) );
				}
			}
		}

		update_option( 'default_icon_choice', $icon_set );
		wp_redirect( admin_url( 'edit.php?post_type=sf_social_icons' ) );
		die();
	}
}
endif;

if (!function_exists('array_replace'))
{
	function array_replace( array $array, array $array1 )
	{
		$args = func_get_args();
		$count = func_num_args();

		for ($i = 0; $i < $count; ++$i)
		{
			if (is_array($args[$i]))
			{
				foreach ($args[$i] as $key => $val)
				{
					$array[$key] = $val;
				}
			}
			else 
			{
				trigger_error(
				__FUNCTION__ . '(): Argument #' . ($i+1) . ' is not an array',
				E_USER_WARNING
				);
				return NULL;
			}
		}

		return $array;
	}
}