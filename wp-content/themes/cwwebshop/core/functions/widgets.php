<?php
/**
 * Functions to handle widgets
 *
 * WARNING: This file is part of the core PrimaThemes framework.
 * DO NOT edit this file under any circumstances. 
 *
 * @category   PrimaThemes
 * @package    Framework
 * @subpackage Widgets
 * @author     PrimaThemes
 * @link       http://www.primathemes.com
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Register widgets.
 *
 * @since PrimaThemes 2.0
 */
add_action( 'widgets_init', 'prima_register_widgets' );
function prima_register_widgets() {
	register_widget('Prima_RecentPosts_Widget');
	register_widget('Prima_RecentComments_Widget');
	register_widget('Prima_FeedburnerForm_Widget');
	register_widget('Prima_SearchForm_Widget');
	register_widget('Prima_Twitter_Widget');
	register_widget('Prima_Flickr_Widget');
}

/**
 * Prima Recent Posts Widget class
 *
 * @since PrimaThemes 2.0
 */
class Prima_RecentPosts_Widget extends WP_Widget {
	function Prima_RecentPosts_Widget() {
		$widget_ops = array( 'classname' => 'prima_recent_posts', 'description' => __('Display most recent posts', 'primathemes') );
		$control_ops = array( 'id_base' => 'prima-recent-posts' );
		$this->WP_Widget( 'prima-recent-posts', __('Advanced Recent Posts', 'primathemes'), $widget_ops, $control_ops );
		$this->alt_option_name = 'prima_recent_posts';
	}
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		$number = (int)$instance['number'];
		$image = $instance['image'] ? '1' : '0';
		$image_width = (int)$instance['image_width'];
		$image_height = (int)$instance['image_height'];
		$postmeta = $instance['postmeta'] ? '1' : '0';
		$postexcerpt = $instance['postexcerpt'] ? '1' : '0';
		$excerpt_length = (int)$instance['excerpt_length'];
 		$output = '';
		$output .= $before_widget;
		if ( $title ) $output .= $before_title . $title . $after_title;
		$args = array(
			'post_type'	=> 'post',
			'ignore_sticky_posts'	=> 1,
			'posts_per_page' => $number
		);
		query_posts($args);
		if (have_posts()) : 
			$output .= '<ul>';
		while (have_posts()) : the_post();
			$output .= '<li>';
			if ( $image )
				$output .= prima_get_image( array ( 'width' => $image_width, 'height' => $image_height ) );
			$output .= '<h3><a href="'.get_permalink().'" title="" rel="bookmark">'.get_the_title().'</a></h3>';
			if ( $postmeta )
				$output .= '<p class="post-meta">'.do_shortcode( __( 'Posted on [post-date]', 'primathemes' ) ).'</p>';
			if ( $postexcerpt )
				$output .= prima_get_excerpt_limit($excerpt_length,false);
			$output .= '</li>';
		endwhile;
			$output .= '</ul>';
		endif;
		wp_reset_query();
		$output .= $after_widget;
		echo $output;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = ( (int)$new_instance['number'] > 0 ? (int)$new_instance['number'] : '3' );
		$instance['image'] = ( isset( $new_instance['image'] ) ? 1 : 0 );
		$instance['image_width'] = ( (int)$new_instance['image_width'] > 0 ? (int)$new_instance['image_width'] : '50' );
		$instance['image_height'] = ( (int)$new_instance['image_height'] > 0 ? (int)$new_instance['image_height'] : '50' );
		$instance['postmeta'] = ( isset( $new_instance['postmeta'] ) ? 1 : 0 );
		$instance['postexcerpt'] = ( isset( $new_instance['postexcerpt'] ) ? 1 : 0 );
		$instance['excerpt_length'] = ( (int)$new_instance['excerpt_length'] > 0 ? (int)$new_instance['excerpt_length'] : '75' );
		return $instance;
	}
	function form( $instance ) {
		$defaults = array( 'title' => 'Recent Posts', 'number' => 3, 'image' => true, 'image_width' => 50, 'image_height' => 50, 'postmeta' => true, 'postexcerpt' => true, 'excerpt_length' => 75 );
		$instance = wp_parse_args( (array) $instance, $defaults );
		prima_widget_input_text( __('Title:', 'primathemes'), $this->get_field_id( 'title' ), $this->get_field_name( 'title' ), $instance['title'] );
		prima_widget_input_text_small( __('Number of posts to show:', 'primathemes'), $this->get_field_id( 'number' ), $this->get_field_name( 'number' ), $instance['number'] );
		prima_widget_input_checkbox( __( 'Show image', 'primathemes' ), $this->get_field_id( 'image' ), $this->get_field_name( 'image' ), checked( $instance['image'], true, false ) );
		prima_widget_input_text_small( __('Image width (px):', 'primathemes'), $this->get_field_id( 'image_width' ), $this->get_field_name( 'image_width' ), $instance['image_width'] );
		prima_widget_input_text_small( __('Image height (px):', 'primathemes'), $this->get_field_id( 'image_height' ), $this->get_field_name( 'image_height' ), $instance['image_height'] );
		prima_widget_input_checkbox( __( 'Show post meta', 'primathemes' ), $this->get_field_id( 'postmeta' ), $this->get_field_name( 'postmeta' ), checked( $instance['postmeta'], true, false ) );
		prima_widget_input_checkbox( __( 'Show post excerpt', 'primathemes' ), $this->get_field_id( 'postexcerpt' ), $this->get_field_name( 'postexcerpt' ), checked( $instance['postexcerpt'], true, false ) );
		prima_widget_input_text_small( __('Post excerpt length:', 'primathemes'), $this->get_field_id( 'excerpt_length' ), $this->get_field_name( 'excerpt_length' ), $instance['excerpt_length'] );
	}
}

/**
 * Prima Recent Comments Widget class
 *
 * @since PrimaThemes 2.0
 */
class Prima_RecentComments_Widget extends WP_Widget {
	function Prima_RecentComments_Widget() {
		$widget_ops = array( 'classname' => 'prima_recent_comments', 'description' => __('Display most recent comments', 'primathemes') );
		$control_ops = array( 'id_base' => 'prima-recent-comments' );
		$this->WP_Widget( 'prima-recent-comments', __('Advanced Recent Comments', 'primathemes'), $widget_ops, $control_ops );
		$this->alt_option_name = 'prima_recent_comments';
		add_action( 'comment_post', array(&$this, 'flush_widget_cache') );
		add_action( 'transition_comment_status', array(&$this, 'flush_widget_cache') );
	}
	function flush_widget_cache() {
		wp_cache_delete('prima_recent_comments', 'widget');
	}
	function widget( $args, $instance ) {
		global $comments, $comment;
		$cache = wp_cache_get('prima_recent_comments', 'widget');
		if ( ! is_array( $cache ) )
			$cache = array();
		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		$number = (int)$instance['number'];
		$avatar = $instance['avatar'] ? '1' : '0';
		$avatar_size = (int)$instance['avatar_size'];
		$excerpt_length = (int)$instance['excerpt_length'];
 		$output = '';
		$output .= $before_widget;
		if ( $title ) $output .= $before_title . $title . $after_title;
		$comments = get_comments(array( 'number' => $number, 'status' => 'approve', 'type' => 'comment' ));
		if ($comments) {
			$output .= '<ul>';
			foreach ($comments as $comment) :
				$comment_link = get_comment_link($comment->comment_ID);
				$output .= ( $avatar ? '<li class="comment-with-avatar">' : '<li class="group">' );
				if ( $avatar ) $output .= '<a href="'. $comment_link.'">'.get_avatar($comment, $size=$avatar_size).'</a>';
				$output .= '<a href="'.$comment_link.'"><strong>'.$comment->comment_author.'</strong>:</a> '.substr(get_comment_excerpt( $comment->comment_ID ), 0, $excerpt_length).'&hellip;';
				$output .= '</li>'; 
			endforeach;
			$output .= '</ul>';
		}
		else {
			$output .= __( 'No comments were found', 'primathemes' );
		}
		$output .= $after_widget;
		echo $output;
		$cache[$args['widget_id']] = $output;
		wp_cache_set('prima_recent_comments', $cache, 'widget');
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = ( (int)$new_instance['number'] > 0 ? (int)$new_instance['number'] : '3' );
		$instance['avatar'] = ( isset( $new_instance['avatar'] ) ? 1 : 0 );
		$instance['avatar_size'] = ( (int)$new_instance['avatar_size'] > 0 ? (int)$new_instance['avatar_size'] : '48' );
		$instance['excerpt_length'] = ( (int)$new_instance['excerpt_length'] > 0 ? (int)$new_instance['excerpt_length'] : '75' );
		return $instance;
	}
	function form( $instance ) {
		$defaults = array( 'title' => 'Recent Comments', 'number' => 3, 'avatar' => true, 'avatar_size' => 48, 'excerpt_length' => 75 );
		$instance = wp_parse_args( (array) $instance, $defaults );
		prima_widget_input_text( __('Title:', 'primathemes'), $this->get_field_id( 'title' ), $this->get_field_name( 'title' ), $instance['title'] );
		prima_widget_input_text_small( __('Number of comments to show:', 'primathemes'), $this->get_field_id( 'number' ), $this->get_field_name( 'number' ), $instance['number'] );
		prima_widget_input_checkbox( __( 'Show avatar', 'primathemes' ), $this->get_field_id( 'avatar' ), $this->get_field_name( 'avatar' ), checked( $instance['avatar'], true, false ) );
		prima_widget_input_text_small( __('Avatar size (px):', 'primathemes'), $this->get_field_id( 'avatar_size' ), $this->get_field_name( 'avatar_size' ), $instance['avatar_size'] );
		prima_widget_input_text_small( __('Comment excerpt length:', 'primathemes'), $this->get_field_id( 'excerpt_length' ), $this->get_field_name( 'excerpt_length' ), $instance['excerpt_length'] );
	}
}

/**
 * Prima Search Forms Widget class
 *
 * @since PrimaThemes 2.0
 */
class Prima_SearchForm_Widget extends WP_Widget {
	function Prima_SearchForm_Widget() {
		$widget_ops = array( 'classname' => 'prima_searchform', 'description' => __('Display search form', 'primathemes') );
		$control_ops = array( 'id_base' => 'prima-searchform' );
		$this->WP_Widget( 'prima-searchform', __('Advanced Search Form', 'primathemes'), $widget_ops, $control_ops );
	}
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;
		$attr = array( 'search_text' => $instance['search_text'], 'button_text' => $instance['button_text'], 'echo' => true );
		prima_search_form( $attr );
		echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['search_text'] = strip_tags( $new_instance['search_text'] );
		$instance['button_text'] = strip_tags( $new_instance['button_text'] );
		return $instance;
	}
	function form( $instance ) {
		$defaults = array( 'title' => '', 'search_text' => __('Search this website&hellip;', 'primathemes'), 'button_text' => __( 'Search', 'primathemes' ) );
		$instance = wp_parse_args( (array) $instance, $defaults );
		prima_widget_input_text( __('Title:', 'primathemes'), $this->get_field_id( 'title' ), $this->get_field_name( 'title' ), $instance['title'] );
		prima_widget_input_text( __('Input text:', 'primathemes'), $this->get_field_id( 'search_text' ), $this->get_field_name( 'search_text' ), $instance['search_text'] );
		prima_widget_input_text( __('Button text:', 'primathemes'), $this->get_field_id( 'button_text' ), $this->get_field_name( 'button_text' ), $instance['button_text'] );
	}
}

/**
 * Prima Feedburner Forms Widget class
 *
 * @since PrimaThemes 2.0
 */
class Prima_FeedburnerForm_Widget extends WP_Widget {
	function Prima_FeedburnerForm_Widget() {
		$widget_ops = array( 'classname' => 'prima_feedburnerform', 'description' => __('Display Feedburner subscription form', 'primathemes') );
		$control_ops = array( 'id_base' => 'prima-feedburnerform' );
		$this->WP_Widget( 'prima-feedburnerform', __('Advanced Feedburner Form', 'primathemes'), $widget_ops, $control_ops );
	}
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;
		if ( $instance['id'] && $instance['intro_text'] )
			echo wpautop( $instance['intro_text'] );
		$attr = array( 'id' => $instance['id'], 'input_text' => $instance['input_text'], 'button_text' => $instance['button_text'], 'echo' => true );
		prima_feedburner_form( $attr );
		echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['intro_text'] = strip_tags( $new_instance['intro_text'] );
		$instance['id'] = strip_tags( $new_instance['id'] );
		$instance['input_text'] = strip_tags( $new_instance['input_text'] );
		$instance['button_text'] = strip_tags( $new_instance['button_text'] );
		return $instance;
	}
	function form( $instance ) {
		$defaults = array( 'title' => '', 'intro_text' => '', 'id' => '', 'input_text' => __( 'Enter your email address...', 'primathemes' ), 'button_text' => __( 'Subscribe', 'primathemes' ) );
		$instance = wp_parse_args( (array) $instance, $defaults );
		prima_widget_input_text( __('Title:', 'primathemes'), $this->get_field_id( 'title' ), $this->get_field_name( 'title' ), $instance['title'] );
		prima_widget_input_text( __('Introduction text:', 'primathemes'), $this->get_field_id( 'intro_text' ), $this->get_field_name( 'intro_text' ), $instance['intro_text'] );
		prima_widget_input_text( __('Feedburner ID:', 'primathemes'), $this->get_field_id( 'id' ), $this->get_field_name( 'id' ), $instance['id'] );
		prima_widget_input_text( __('Input text:', 'primathemes'), $this->get_field_id( 'input_text' ), $this->get_field_name( 'input_text' ), $instance['input_text'] );
		prima_widget_input_text( __('Button text:', 'primathemes'), $this->get_field_id( 'button_text' ), $this->get_field_name( 'button_text' ), $instance['button_text'] );
	}
}

/**
 * Prima Twitter Widget class
 *
 * @since PrimaThemes 2.0
 */
class Prima_Twitter_Widget extends WP_Widget {
	function Prima_Twitter_Widget() {
		$widget_ops = array( 'classname' => 'prima_twitter', 'description' => __('Display most recent Twitter feed', 'primathemes') );
		$control_ops = array( 'id_base' => 'prima-twitter' );
		$this->WP_Widget( 'prima-twitter', __('Advanced Twitter Feed', 'primathemes'), $widget_ops, $control_ops );
	}
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;
		$attr = array( 'username' => $instance['username'], 'limit' => $instance['limit'], 'interval' => $instance['interval'] );
		prima_twitter( $attr );
		echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['username'] = strip_tags( $new_instance['username'] );
		$instance['limit'] = strip_tags( $new_instance['limit'] );
		$instance['interval'] = strip_tags( $new_instance['interval'] );
		return $instance;
	}
	function form( $instance ) {
		$defaults = array( 'title' => '', 'username' => 'primathemes', 'limit' => '3', 'interval' => '10' );
		$instance = wp_parse_args( (array) $instance, $defaults );
		prima_widget_input_text( __('Title:', 'primathemes'), $this->get_field_id( 'title' ), $this->get_field_name( 'title' ), $instance['title'] );
		prima_widget_input_text( __('Twitter username:', 'primathemes'), $this->get_field_id( 'username' ), $this->get_field_name( 'username' ), $instance['username'] );
		prima_widget_input_text( __('Number of tweets:', 'primathemes'), $this->get_field_id( 'limit' ), $this->get_field_name( 'limit' ), $instance['limit'] );
		$interval = array('5' => __('5 minutes', 'primathemes'), '10' => __('10 minutes', 'primathemes'), '15' => __('15 minutes', 'primathemes'), '30' => __('30 minutes', 'primathemes'),  '60' => __('1 hour', 'primathemes'), 
'120' => __('1 hour', 'primathemes'), '240' => __('4 hour', 'primathemes'), '720' => __('12 hour', 'primathemes'), '1440' => __('24 hours', 'primathemes') ); 
		prima_widget_select_single( __( 'Load new Tweets every:', 'primathemes' ), $this->get_field_id( 'interval' ), $this->get_field_name( 'interval' ), $instance['interval'], $interval, false );
	}
}

/**
 * Prima Flickr Widget class
 *
 * @since PrimaThemes 2.0
 */
class Prima_Flickr_Widget extends WP_Widget {
	function Prima_Flickr_Widget() {
		$widget_ops = array( 'classname' => 'prima_flickr', 'description' => __('Display photos from Flickr', 'primathemes') );
		$control_ops = array( 'id_base' => 'prima-flickr' );
		$this->WP_Widget( 'prima-flickr', __('Advanced Flickr Photos', 'primathemes'), $widget_ops, $control_ops );
	}
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		$source = $instance['source'];
		$user_ID = $instance['user_ID'];
		$group_ID = $instance['user_ID'];
		$set_ID = $instance['set_ID'];
		$tag = $instance['tag'];
		$display = $instance['display'];
		$number = $instance['number'];
		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;
		$params = 'count='.$number.'.&amp;display='.$display.'&amp;size=s&amp;layout=x';
		if ($source=='user')
			$params .= '&amp;source=user&amp;user='.$user_ID; 
		elseif ($source=='group')
			$params .= '&amp;source=group&amp;group='.$group_ID; 
		elseif ($source=='user_set')
			$params .= '&amp;source=user_set&amp;set='.$set_ID; 
		elseif ($source=='all_tag')
			$params .= '&amp;source=all_tag&amp;tag='.$tag; 
		echo '<div id="prima-flickr-wrapper" class="group"><script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?'.$params.'"></script></div>';
		echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['source'] = strip_tags( $new_instance['source'] );
		$instance['user_ID'] = strip_tags( $new_instance['user_ID'] );
		$instance['group_ID'] = strip_tags( $new_instance['group_ID'] );
		$instance['set_ID'] = strip_tags( $new_instance['set_ID'] );
		$instance['tag'] = strip_tags( trim($new_instance['tag']) );
		$instance['display'] = strip_tags( $new_instance['display'] );
		$instance['number'] = (int)$new_instance['number'];
		return $instance;
	}
	function form( $instance ) {
		$defaults = array( 'title' => __('Flickr Photos', 'primathemes'), 'source' => 'user', 'user_ID' => '', 'group_ID' => '', 'set_ID' => '', 'tag' => '', 'display' => 'latest', 'number' => 10 );
		$instance = wp_parse_args( (array) $instance, $defaults );
		prima_widget_input_text( __('Title:', 'primathemes'), $this->get_field_id( 'title' ), $this->get_field_name( 'title' ), $instance['title'] );
		$source_opt = array('user' => __('User', 'primathemes'), 'group' => __('Group', 'primathemes'), 'user_set' => __('User Set', 'primathemes'), 'all_tag' => __('Tag', 'primathemes')); 
		prima_widget_select_single( __( 'Source:', 'primathemes' ), $this->get_field_id( 'source' ), $this->get_field_name( 'source' ), $instance['source'], $source_opt, false );
		prima_widget_input_text( __('User ID:', 'primathemes'), $this->get_field_id( 'user_ID' ), $this->get_field_name( 'user_ID' ), $instance['user_ID'] );
		echo '<p><small>'.__('* find your user ID using', 'primathemes').' <a href="ht'.'tp://www.idgettr.com" target="_blank">idGettr</a></small></p>';
		prima_widget_input_text( __('Group ID:', 'primathemes'), $this->get_field_id( 'group_ID' ), $this->get_field_name( 'group_ID' ), $instance['group_ID'] );
		prima_widget_input_text( __('Set ID:', 'primathemes'), $this->get_field_id( 'set_ID' ), $this->get_field_name( 'set_ID' ), $instance['set_ID'] );
		prima_widget_input_text( __('Tag (separated by comma):', 'primathemes'), $this->get_field_id( 'tag' ), $this->get_field_name( 'tag' ), $instance['tag'] );
		$display_opt = array('latest' => __('Latest', 'primathemes'), 'random' => __('Random', 'primathemes'));
		prima_widget_select_single( __( 'Display:', 'primathemes' ), $this->get_field_id( 'display' ), $this->get_field_name( 'display' ), $instance['display'], $display_opt, false );
		prima_widget_input_text_small( __('Number of photos (from 1 to 10):', 'primathemes'), $this->get_field_id( 'number' ), $this->get_field_name( 'number' ), $instance['number'] );
	}
}