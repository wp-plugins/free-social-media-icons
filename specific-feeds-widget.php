<?php
/**
 * Featured_Posts Widget Class
 *
 * @since 2.8.0
 */
class specificFeedsWidget extends WP_Widget 
{
	function __construct() 
	{		
		$widget_ops = array( 'classname' => 'specificFeedsWidget', 'description' => __( "Show your social network icons in the sidebar", 'specificfeeds' ) );
		$this->WP_Widget( 'social_icons', __( 'Free Social Media Icons', 'specificfeeds' ), $widget_ops );
		$this->alt_option_name = 'specificFeedsWidget';

		add_action( 'save_post', array( &$this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( &$this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( &$this, 'flush_widget_cache' ) );
	}

	function widget( $args, $instance )
	{
		$cache = wp_cache_get( 'specificFeedsWidget', 'widget' );
		$cname = '';

		if ( !is_array( $cache ) )
			$cache = array();

		// if ( isset( $cache[$args['widget_id']] ) )
		// {
		// 	echo $cache[$args['widget_id']];
		// 	return;
		// }

		ob_start();
		extract( $args );

		global $specificFeedsSocialIcons;
		
		echo $before_widget; ?>
		<?php if ( $instance['title'] ) echo $before_title . $instance['title']. $after_title;

		$settings = maybe_unserialize( get_option( 'sf_settings' ) );

		// if( $instance['width'] > 0 || $instance['height'] > 0 )
		// 	$instance['size'] = 'custom';

		// $settings = shortcode_atts( $sf_settings, $instance );
		
		// $settings['size'] = ( $settings['size'] == 'custom' ) ? array( $instance['width'], $instance['height'] ) : $settings['size'];

		echo $specificFeedsSocialIcons->specificFeedsGenerator( $settings );
		echo $after_widget; 

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set( 'specificFeedsWidget', $cache, 'widget' );
	}

	function update( $new_instance, $old_instance )
	{
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		// $instance['width'] = ( int ) $new_instance['width'];
		// $instance['height'] = ( int ) $new_instance['height'];
		// $instance['margin'] = ( int ) $new_instance['margin'];
		// $instance['rows'] = ( int ) $new_instance['rows'];
		// $instance['direction'] = $new_instance['direction'];

		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions['widget_featured_entries'] ) )
			delete_option( 'widget_featured_entries' );

		return $instance;
	}

	function flush_widget_cache()
	{
		wp_cache_delete( 'specificFeedsWidget', 'widget' );
	}

	function form( $instance )
	{
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : 'Follow us!';
		// $width = ( int ) $instance['width'];
		// $height = ( int ) $instance['height'];
		// $margin = ( int ) $instance['margin'];
		// $rows = ( int ) $instance['rows'];
		// $direction = $instance['direction'];
		

		// if ( !isset( $instance['number'] ) || !$number = ( int ) $instance['number'] )
		// 	$number = 5;

		// if ( !isset( $instance['desc_length'] ) || !$desc_length = ( int ) $instance['desc_length'] )
		// 	$desc_length = 80;
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'specificfeeds' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<!-- <p><label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e( 'Custom width:', 'specificfeeds' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" type="text" value="<?php echo $width; ?>" size="3" /></p>

		<p><label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e( ' Custom height:', 'specificfeeds' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" type="text" value="<?php echo $height; ?>" size="3" /></p>

		<p><label for="<?php echo $this->get_field_id( 'margin' ); ?>"><?php _e( ' Custom margin:', 'specificfeeds' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'margin' ); ?>" name="<?php echo $this->get_field_name( 'margin' ); ?>" type="text" value="<?php echo $margin; ?>" size="3" /></p>

		<p><label for="<?php echo $this->get_field_id( 'rows' ); ?>"><?php _e( ' Custom columns:', 'specificfeeds' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'rows' ); ?>" name="<?php echo $this->get_field_name( 'rows' ); ?>" type="text" value="<?php echo $rows; ?>" size="3" />
		<span class="description"><i>Vertical only></i></span>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'direction' ); ?>"><?php _e( 'Direction:', 'specificfeeds' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'direction' ); ?>" id="<?php echo $this->get_field_id( 'direction' ); ?>" class="widefat">
				<option value="horizontal"<?php selected( $direction,'horizontal' );?>><?php _e( 'Horizontally', 'specificfeeds' ); ?></option>
				<option value="vertical"<?php selected( $direction,'vertical' );?>><?php _e( 'Vertically', 'specificfeeds' ); ?></option>
			</select>
		</p> -->
		<?php
	}
}