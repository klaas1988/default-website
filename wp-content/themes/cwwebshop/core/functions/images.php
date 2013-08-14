<?php
/**
 * Functions to handle images
 *
 * WARNING: This file is part of the core PrimaThemes framework.
 * DO NOT edit this file under any circumstances. 
 *
 * Credits (and Inspirations):
 * - Get The Image plugin by Justin Tadlock http://justintadlock.com
 *
 * @category   PrimaThemes
 * @package    Framework
 * @subpackage Functions
 * @author     PrimaThemes
 * @link       http://www.primathemes.com
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Add featured image or "post-thumbnails" support.
 *
 * @since PrimaThemes 2.0
 */
add_theme_support( 'post-thumbnails' );

/**
 * Class to control image.
 *
 * @since PrimaThemes 2.0
 */
class Prima_Image {

	/**
	 * Settings array for default values.
	 *
	 * @since PrimaThemes 2.0
	 */
	protected $args = array();

	/**
	 * Constructor.
	 *
	 * @since PrimaThemes 2.0
	 */
	public function __construct() {
	
		$this->args = array(
			'image_id' => false,
			'parent_id' => false,
			'post_id' => false,
			
			'the_post_thumbnail' => true, // WP 2.9+ image function
			'attachment' => true,
			'meta_key' => false,
			'image_scan' => false,
			'default_image' => false,
			
			'link_to_parent' => false,
			'link_to_post' => false,
			'link_to_image' => false,
			'link_to_meta' => false,
			'link_to' => false,

			'link_attr' => false,
			'image_class' => false,
			
			'size' => false,
			'width' => false,
			'max_width' => 1024,
			'height' => false,
			'max_height' => 1024,
			'crop' => true,
			
			'output' => 'image',
			
			'before' => '',
			'after' => '',
			
			'callback' => null,
		);
		
	}

	/**
	 * Return the final image.
	 *
	 * @since PrimaThemes 2.0
	 */
	public function get_image( array $args = array() ) {

		global $post;

		$args = apply_filters( 'prima_image_args', wp_parse_args( $args, $this->args ) );

		$args['post_id'] = ( ! $args['post_id'] && is_object( $post ) && $post->ID ) ? $post->ID : $args['post_id'];
		
		/* Check size. */
		if (!$args['size']) {
			if (!$args['width'] && !$args['height']) {
				$args['size'] = 'full';
			}
			else {
				$args['size'] = (int)$args['width'].'x'.(int)$args['height'];
			}
		}
		else {
			if ($args['size'] == 'thumbnail') {
				$args['width'] = get_option('thumbnail_size_w');
				$args['height'] = get_option('thumbnail_size_h');
			}
			elseif ($args['size'] == 'medium') {
				$args['width'] = get_option('medium_size_w');
				$args['height'] = get_option('medium_size_h');
			}
			elseif ($args['size'] == 'large') {
				$args['width'] = get_option('large_size_w');
				$args['height'] = get_option('large_size_h');
			}
			else {
				global $_wp_additional_image_sizes;
				if ( isset($_wp_additional_image_sizes[$args['size']]) ) {
					$args['width'] = $_wp_additional_image_sizes[$args['size']]['width'];
					$args['height'] = $_wp_additional_image_sizes[$args['size']]['height'];
					$args['crop'] = $_wp_additional_image_sizes[$args['size']]['crop'];
				}
			}
		}
		
		if ( 'attachment' == get_post_type( $args['post_id'] ) )
			$args['image_id'] = $args['post_id'];

		if ( !empty( $args['image_id'] ) )
			$args['post_id'] = $args['image_id'];
			
		$image = '';
		
		/* If image ID found, check for the image. */
		if ( !empty( $args['image_id'] ) )
			$image = $this->get_output( $args );
		
		/* If no image found and $the_post_thumbnail is set to true, check for a post image (WP feature). */
		if ( empty( $image ) && !empty( $args['the_post_thumbnail'] ) ) {
			$args['image_id'] = get_post_thumbnail_id( $args['post_id'] );
			if ( !empty( $args['image_id'] ) )
				$image = $this->get_output( $args );
		}
			
		/* If no image found and $attachment is set to true, check for an image by attachment. */
		if ( empty( $image ) && !empty( $args['attachment'] ) ) {
			$args['image_id'] = get_post_meta( $args['post_id'], '_prima_image_attachment', true );
			if ( empty( $args['image_id'] ) ) {
				$attachments = get_children(
					array(
						'post_parent' => $args['post_id'],
						'post_status' => 'inherit',
						'post_type' => 'attachment',
						'post_mime_type' => 'image',
						'order' => 'ASC',
						'orderby' => 'menu_order ID',
						'suppress_filters' => true
					)
				);
				$attachments = array_keys($attachments);
			}
			if ( !empty( $attachments ) && $attachments[0] ) {
				$args['image_id'] = $attachments[0];
				update_post_meta( $args['post_id'], '_prima_image_attachment', $attachments[0] );
			}
			if ( !empty( $args['image_id'] ) )
				$image = $this->get_output( $args );
		}
			
		/* If a custom field key (array) is defined, check for images by custom field. */
		if ( empty( $image ) && !empty( $args['meta_key'] ) ) {
			if ( !is_array( $args['meta_key'] ) ) {
				$image_meta = get_post_meta( $args['post_id'], $args['meta_key'], true );
			}
			elseif ( is_array( $args['meta_key'] ) ) {
				foreach ( $args['meta_key'] as $meta_key ) {
					$image_meta = get_post_meta( $args['post_id'], $meta_key, true );
					if ( !empty( $image_meta ) )
						break;
				}
			}
			if ( !empty( $image_meta ) )
				$image = array( 'url' => $image_meta, 'url_full' => $image_meta, 'width' => $args['width'], 'height' => $args['height'] );
		}

		/* If no image found and $image_scan is set to true, scan the post for images. */
		if ( empty( $image ) && !empty( $args['image_scan'] ) ) {
			preg_match_all( '|<img.*?src=[\'"](.*?)[\'"].*?>|i', get_post_field( 'post_content', $args['post_id'] ), $matches );
			// if ( isset( $matches ) && $matches[1][0] )
			if ( isset( $matches ) && $matches[1] )
				$image = array( 'url' => $matches[1][0], 'url_full' => $matches[1][0], 'width' => $args['width'], 'height' => $args['height'] );
		}
		
		/* If no image found and a callback function was given. */
		if ( empty( $image ) && !is_null( $args['callback'] ) && function_exists( $args['callback'] ) )
			$image = call_user_func( $args['callback'], $args );
		
		/* If no image found and a $default_image is set, get the default image. */
		if ( empty( $image ) && !empty( $args['default_image'] ) )
			$image = array( 'url' => $args['default_image'], 'url_full' => $args['default_image'], 'width' => $args['width'], 'height' => $args['height'] );
		
		if ( empty( $image ) )
			return false;
		
		if ( $args['output'] == 'image' ) { 
			$image = $this->display( $args, $image );
			/* Allow plugins/theme to override the final output. */
			$image = apply_filters( 'prima_image', $image );
			return $image;
		}
		elseif ( $args['output'] == 'url' ) { 
			return $image['url'];
		}

	}

	/**
	 * Return the final image gallery.
	 *
	 * @since PrimaThemes 2.0
	 */
	public function get_gallery( array $args = array() ) {

		global $post;

		$args = apply_filters( 'prima_image_args', wp_parse_args( $args, $this->args ) );

		$args['post_id'] = ( ! $args['post_id'] && is_object( $post ) && $post->ID ) ? $post->ID : $args['post_id'];
		$args['parent_id'] = $args['parent_id'] ? $args['parent_id'] : $args['post_id'];
		
		$images = '';
		$post_thumbnail_id = get_post_thumbnail_id( $args['post_id'] );
		if ( $post_thumbnail_id ) {
			$image_args = $args;
			$image_args['post_id']= $post_thumbnail_id;
			$image_args['image_id']= $post_thumbnail_id;
			if ( $args['link_to_parent'] ) $image_args['parent_id']= $args['post_id'];
			$image = $this->get_output( $image_args );
			if ($image) {
				$image = $this->display( $image_args, $image );
				$images .= $image;
			}
		}
		
		$attachments = get_children( array( 'post_parent' => $args['post_id'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC' ) );
		if ( !empty($attachments) ) {
			foreach ( $attachments as $id => $attachment ) {
				if ( $post_thumbnail_id && ( $id == $post_thumbnail_id ) )
					continue;
				$image_args = $args;
				$image_args['post_id']= $id;
				$image_args['image_id']= $id;
				if ( $args['link_to_parent'] ) $image_args['parent_id']= $args['post_id'];
				$image = $this->get_output( $image_args );
				if ($image) {
					$image = $this->display( $image_args, $image );
					$images .= $image;
				}
			}
		}
		
		if ($images) {
			$images = $args['before_container'].$images.$args['after_container'];
			/* Allow plugins/theme to override the final output. */
			$images = apply_filters( 'prima_gallery', $images );
			return $images;
		}
		else return false;

	}

	/**
	 * Return image url,width,height.
	 *
	 * @since PrimaThemes 2.0
	 */
	protected function get_output( array $args ) {
		
		if ( !$args['width'] && $args['height'] ) $args['width'] = $args['max_width'];
		if ( $args['width'] && !$args['height'] ) $args['height'] = $args['max_height'];

		$imagedata = get_post_meta( $args['image_id'], '_wp_attachment_metadata', true );
		if ( empty( $imagedata ) )
			return false;
		$img_width = $imagedata['width'];
		$img_height = $imagedata['height'];
		$img_file = $imagedata['file'];
		
		if ( empty( $img_file ) )
			return false;
		
		if ( ($uploads = wp_upload_dir()) && false === $uploads['error'] ) { // Get upload directory
			if ( 0 === strpos($img_file, $uploads['basedir']) ) { // Check that the upload base exists in the file location
				$img_url = str_replace($uploads['basedir'], $uploads['baseurl'], $img_file); // replace file location with url location
				$img_path = $img_file;
			}
			elseif ( false !== strpos($img_file, 'wp-content/uploads') ) {
				$img_url = $uploads['baseurl'] . substr( $img_file, strpos($img_file, 'wp-content/uploads') + 18 );
				$img_path = $uploads['baseurl'] . substr( $img_file, strpos($img_file, 'wp-content/uploads') + 18 );
			}
			else {
				$img_url = $uploads['baseurl'] . "/$img_file"; //Its a newly uploaded file, therefor $file is relative to the basedir.
				$img_path = $uploads['basedir'] . "/$img_file";
			}
		}
		
		if( !file_exists( $img_path ) )
			return false;

		$output = array();
		$output['width'] = $img_width;
		$output['height'] = $img_height;
		$output['url'] = $img_url;
		$output['url_full'] = $img_url;
		
		if ( $args['output'] == 'image' ) { 
			$img_alt = get_post_meta( $args['image_id'], '_wp_attachment_image_alt', true );
			if( empty( $img_alt ) ) 
				$img_alt = apply_filters( 'the_title', get_post_field( 'post_title', $args['image_id'] ) );
			if( empty( $img_alt ) && ( $args['image_id'] != $args['post_id'] ) ) 
				$img_alt = apply_filters( 'the_title', get_post_field( 'post_title', $args['post_id'] ) );
			$output['alt'] = $img_alt;
		}
		
		if ( $args['size'] == 'full' )
			return $output;
		
		$img_resize = image_resize_dimensions( $img_width, $img_height, $args['width'], $args['height'], $args['crop'] );
		$img_width_new = $img_resize[4];
		$img_height_new = $img_resize[5];
		
		$img_info = pathinfo($img_path);
		$img_dir = $img_info['dirname'];
		$img_name = $img_info['filename'];
		$img_ext = $img_info['extension'];
		
		$img_path_new = $img_dir.'/'.$img_name.'-'.$img_width_new.'x'.$img_height_new.'.'.$img_ext;
		$img_url_new = str_replace( $uploads['basedir'], $uploads['baseurl'], $img_path_new );
		
		if( file_exists( $img_path_new ) ) {
			$output['width'] = $img_width_new;
			$output['height'] = $img_height_new;
			$output['url'] = $img_url_new;
			return $output;
		}
		
		$img_editor = wp_get_image_editor( $img_path );
		if ( !is_wp_error( $img_editor ) ) {
			$img_editor->resize( $args['width'],  $args['height'], $args['crop'] );
			$img_save = $img_editor->save( $img_path_new );
			if ( !is_wp_error( $img_editor ) ) {
				if ( $img_save['path'] != $img_path_new ) {
					$img_url_new = str_replace( $uploads['basedir'], $uploads['baseurl'], $img_save['path'] );
				}
				$output['url'] = $img_url_new;
				$output['width'] = $img_save['width'];
				$output['height'] = $img_save['height'];
				return $output;
			}
		}

		return $output;
	}
	
	/**
	 * Return image in html format.
	 *
	 * @since PrimaThemes 2.0
	 */
	protected function display( array $args, array $image ) {
	
		if ( empty( $image['url'] ) )
			return false;
			
		$width = ( isset($image['width']) && $image['width'] ? ' width="' . intval( $image['width'] ) . '" ' : '' );
		$height = ( isset($image['height']) && $image['height'] ? ' height="' . intval( $image['height'] ) . '" ' : '' );
		
		$classes = array();
		if ( is_array( $args['meta_key'] ) ) {
			foreach ( $args['meta_key'] as $key )
				$classes[] = 'image-'.str_replace( ' ', '-', strtolower( $key ) );
		}
		$classes[] = 'attachment-'.$args['size'];
		$classes[] = $args['image_class'];
		$class = join( ' ', array_unique( $classes ) );
		
		if ( empty($image['alt']) ) $image['alt'] = '';
		
		$html = '<img src="' . esc_url( $image['url'] ) . '" alt="' . esc_attr( $image['alt'] ) . '" class="' . esc_attr( $class ) . '" '.$width.$height.'/>';
		
		if ( $args['link_to_parent'] && $args['parent_id'] ) {
			$html = '<a '.$args['link_attr'].' href="' . get_permalink( $args['parent_id'] ) . '" title="' . esc_attr( $image['alt'] ) . '">' . $html . '</a>';
		}
		elseif ( $args['link_to_post'] ) {
			$html = '<a '.$args['link_attr'].' href="' . get_permalink( $args['post_id'] ) . '" title="' . esc_attr( $image['alt'] ) . '">' . $html . '</a>';
		}
		elseif ( $args['link_to_image'] ) {
			$html = '<a '.$args['link_attr'].' href="' . $image['url_full'] . '" title="' . esc_attr( $image['alt'] ) . '">' . $html . '</a>';
		}
		elseif ( $args['link_to_meta'] ) {
			if ( $metaurl = get_post_meta( $args['image_id'], $args['link_to_meta'], true ) ) {
				$html = '<a '.$args['link_attr'].' href="' . $metaurl . '" title="' . esc_attr( $image['alt'] ) . '">' . $html . '</a>';
			}
		}
		elseif ( $args['link_to'] ) {
			$html = '<a '.$args['link_attr'].' href="' . esc_url( $args['link_to'] ) . '" title="' . esc_attr( $image['alt'] ) . '">' . $html . '</a>';
		}
		
		$html = $args['before'].$html.$args['after'];
		
		return $html;
		
	}
	
}


/**
 * Echo formatted image from a post.
 *
 * @since PrimaThemes 2.0
 */
function prima_image( $args = array() ) {
	echo prima_get_image( $args );
}

/**
 * Return formatted image from a post.
 *
 * @since PrimaThemes 2.0
 */
function prima_get_image( $args = array() ) {
	global $_prima_image;
	if ( ! $_prima_image ) $_prima_image = new Prima_Image;
	return $_prima_image->get_image( $args );
}

/**
 * Echo formatted images gallery from a post.
 *
 * @since PrimaThemes 2.0
 */
function prima_gallery( $args = array() ) {
	echo prima_get_gallery( $args );
}

/**
 * Return formatted images gallery from a post.
 *
 * @since PrimaThemes 2.0
 */
function prima_get_gallery( $args = array() ) {
	global $_prima_image;
	if ( ! $_prima_image ) $_prima_image = new Prima_Image;
	return $_prima_image->get_gallery( $args );
}

/**
 * Helper function to _prima_image_attachment cache when saving a post.
 *
 * @since PrimaThemes 2.0
 */
add_action( 'save_post', 'prima_get_image_attachments_delete' );
function prima_get_image_attachments_delete( $post_id ) {
	delete_post_meta( $post_id, '_prima_image_attachment' );
}