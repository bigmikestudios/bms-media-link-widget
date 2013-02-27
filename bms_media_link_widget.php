<?php
/**
 * @author Mike Lathrop
 * @version 0.0.3
 */
/*
Plugin Name: BMS Media Link Widget
Plugin URI: http://bigmikestudios.com
Description: Creates a widget for linking to media library items.
Version: 0.0.1
Author URI: http://bigmikestudios.com

/**
* Media_link_widget Class
*/
class Media_link_widget extends WP_Widget {
    /** constructor */
    function Media_link_widget() {
        parent::WP_Widget(false, $name = 'Media Link', array('description' => __('Adds a link to media library items from a dropdown list.')) );
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
		$mimetype = $instance['mimetype'];
		$label = $instance['label'];
		$media_id = $instance['ID'];
		$media = get_post( $media_id );
		$permalink = site_url()."/".$media->post_name;
		$url = wp_get_attachment_url( $media_id );

		echo $before_widget; 
		if ( $title ) echo $before_title . $title . $after_title;
		?>
		<ul class="widget-button">
			<li><a href = "<?php echo $url ?>" target="_blank"><?=$label?></a></li>
		</ul>
		<?
		echo $after_widget; 
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
	$instance = $old_instance;
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['label'] = $new_instance['label'];
	$instance['ID'] = strip_tags($new_instance['ID']);
	$instance['mimetype'] = strip_tags(trim($new_instance['mimetype']));
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
        $title = esc_attr($instance['title']);
		$label = esc_attr($instance['label']);
		$mimetype =  esc_attr($instance['mimetype']);
        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
         </p>
         
         <p>
          <label for="<?php echo $this->get_field_id('label'); ?>"><?php _e('Label:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('label'); ?>" name="<?php echo $this->get_field_name('label'); ?>" type="text" value="<?php echo $label; ?>" />
         </p>
         <p>
          <label for="<?php echo $this->get_field_id('mimetype'); ?>"><?php _e('Mime type:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('mimetype'); ?>" name="<?php echo $this->get_field_name('mimetype'); ?>" type="text" value="<?php echo $mimetype; ?>" /><br /><small>eg: "application/pdf" or "image"</small>
         </p>
        <?php 
		
		// PAGE DROPDOWN
		// ===============================================
		// Build the options list for our select
	
		$options = array();
		$args = array( 
			'post_type' => 'attachment', 
			'numberposts' => 9999,
			'orderby' => 'menu_order', );
		if ($mimetype != '') $args['post_mime_type'] = $mimetype;
		$medias = get_posts($args);
		
		foreach($medias as $media) 
		{
			$selected_html = '';
			if ($media->ID==$instance['ID']) {
			  $selected_html = ' selected="true"';
			}
			$options[] = "<option value=\"{$media->ID}\"{$selected_html}>{$media->post_title}</option>";
		}
		$options = implode("\n",$options);
	
		// Get form attributes from Widget API convenience functions
		$media_field_id = $this->get_field_id( 'ID' );
		$media_field_name = $this->get_field_name( 'ID' );
	
		// Get HTML for the form
		$html = array();
		$html = <<<HTML
		<p>
		  <label for="{$media_field_id}">Media:</label>
		  <select id="{$media_field_id}" name="{$media_field_name}">
			{$options}
		  </select>
		</p>
HTML;
		echo $html;
		// ===============================================
		
		/**/
    }

} // class Media_link_widget

// register Media_link_widget widget
add_action('widgets_init', create_function('', 'return register_widget("Media_link_widget");'));

?>