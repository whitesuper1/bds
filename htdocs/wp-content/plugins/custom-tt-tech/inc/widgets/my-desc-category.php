<?php
// =============================== My Recent Posts (News widget) ======================================
class MY_DescCategoryWidgetCustomTTTech extends WP_Widget {
	/** constructor */
	function MY_DescCategoryWidgetCustomTTTech() {
		parent::WP_Widget(false, $name = __('TT-Tech - Show Description Category', CHERRY_PLUGIN_DOMAIN));
	}

	/** @see WP_Widget::widget */
	function widget($args, $instance) {
		wp_reset_postdata();
		extract( $args );
		$title         = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$show_title    = $instance['show_title'];//apply_filters('widget_post_format', $instance['post_format']);
		
		
		global $post;
		if($category == ''){
			$category = get_the_category($post->ID)[0]->description;
			$tags = wp_get_post_tags($post->ID);
		}
		
		
		echo $before_widget;
		?>
			<p class="category-desc <?php echo empty($category) ? 'not-value' : '' ;?>">
			<?php if($show_title == 'show'){
				echo $before_title . $title . $after_title;
			}?>
			<?php echo $category ?>

			</p>

		<?php echo $after_widget;
	}

	/** @see WP_Widget::update */
	function update($new_instance, $old_instance) {
		return $new_instance;
	}

	/** @see WP_Widget::form */
	function form($instance) {
		/* Set up some default widget settings. */
		$defaults = array( 'title' => '', 'show_title' => '' );
		$instance = wp_parse_args( (array) $instance, $defaults );

		$title         = esc_attr($instance['title']);
		$show_title    = esc_attr($instance['show_title']);
		
	?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', CHERRY_PLUGIN_DOMAIN); ?><input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
		<label for="<?php echo $this->get_field_id('show_title'); ?>"><?php _e('Allow Show Title', CHERRY_PLUGIN_DOMAIN); ?>
			<select id="<?php echo $this->get_field_id('show_title'); ?>" name="<?php echo $this->get_field_name('show_title'); ?>" style="width:150px;" >
				<option value="show" <?php echo ($show_title === 'show' ? ' selected="selected"' : ''); ?>><?php _e('Show', CHERRY_PLUGIN_DOMAIN); ?></option>
				<option value="not-show" <?php echo ($show_title === 'not-show' ? ' selected="selected"' : ''); ?>><?php _e('Not Show', CHERRY_PLUGIN_DOMAIN); ?></option>
				</select>
		</label></p>

		<?php
	}
} // class Widget
?>