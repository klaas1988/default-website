<?php
/**
 * Functions to handle general shortcodes
 *
 * WARNING: This file is part of the core PrimaThemes framework.
 * DO NOT edit this file under any circumstances. 
 *
 * @category   PrimaThemes
 * @package    Framework
 * @subpackage Shortcodes
 * @author     PrimaThemes
 * @link       http://www.primathemes.com
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Register general shortcodes.
 *
 * @since PrimaThemes 2.0
 */ 
add_shortcode( 'is_logged_in', 'prima_general_shortcode' );
add_shortcode( 'not_logged_in', 'prima_general_shortcode' );
add_shortcode( 'year', 'prima_general_shortcode' );
add_shortcode( 'date', 'prima_general_shortcode' );
add_shortcode( 'prima_twitter', 'prima_general_shortcode' );
add_shortcode( 'search_form', 'prima_general_shortcode' );
add_shortcode( 'feedburner_form', 'prima_general_shortcode' );
add_shortcode( 'prima_vimeo', 'prima_general_shortcode' );
add_shortcode( 'prima_youtube', 'prima_general_shortcode' );
add_shortcode( 'prima_googlemaps', 'prima_general_shortcode' );

/**
 * Define general shortcodes.
 *
 * @since PrimaThemes 2.0
 */ 
function prima_general_shortcode($attr, $content=null, $code=""){
	switch( $code ){
		
		/* Logged in user */
		case 'is_logged_in':
			if(is_user_logged_in()) return do_shortcode($content);
		break;
		
		/* Not logged in user */
		case 'not_logged_in':
			if(!is_user_logged_in()) return do_shortcode($content);
		break;
		
		/* Year */
		case 'year':
			return date( __( 'Y', 'primathemes' ) );
		break;
		
		/* Date */
		case 'date':
			$attr = shortcode_atts( array( 'format' => __( 'l, F j, Y', 'primathemes' ) ), $attr );
			return date( $attr['format'] );
		break;
		
		/* Twitter */
		case 'prima_twitter':
			$attr = shortcode_atts( array( 'title' => '', 'username' => 'primathemes', 'limit' => 3 ), $attr );
			$out = '';
			if($attr['title']) $out .= '<h3 class="widget-title">'.$attr['title'].'</h3>';
			$out .= prima_get_twitter( $attr );
			return $out;
		break;
		
		/* Search Form */
		case 'search_form':
			$attr = shortcode_atts( array( 'search_text' => __('Search this website&hellip;', 'primathemes'), 'button_text' => __( 'Search', 'primathemes' ) ), $attr );
			$attr['echo'] = false;
			return prima_search_form( $attr );
		break;
		
		/* Feedburner Form */
		case 'feedburner_form':
			$attr = shortcode_atts( array( 'id' => '', 'language' => __( 'en_US', 'primathemes' ), 'input_text' => __( 'Enter your email address...', 'primathemes' ), 'button_text' => __( 'Subscribe', 'primathemes' ) ), $attr );
			$attr['echo'] = false;
			return prima_feedburner_form( $attr );
		break;
		
		/* Vimeo */
		case 'prima_vimeo':
			extract(shortcode_atts(array(
				'id' 	=> '30153918',
				'width' 	=> '700',
				'height' 	=> '394',
				'autoplay' 	=> false,
				'loop' 		=> false,
				'portrait' 	=> false,
				'title' 	=> false,
				'byline' 	=> false,
			), $attr));
			if ( $autoplay ) $autoplay = '&amp;autoplay=1'; 
			if ( $loop ) $loop = '&amp;loop=1'; 
			if ( !$portrait ) $portrait = '&amp;portrait=0'; 
			if ( !$title ) $title = '&amp;title=0'; 
			if ( !$byline ) $byline = '&amp;byline=0'; 
			return '<if'.'rame src="http://player.vimeo.com/video/'.$id.'?wmode=opaque'.$autoplay.$loop.$portrait.$title.$byline.'" width="'.$width.'" height="'.$height.'" frameborder="0" webkitAllowFullScreen allowFullScreen></if'.'rame>';
		break;
		
		/* Youtube */
		case 'prima_youtube':
			extract(shortcode_atts(array(
				'id' 	=> 'chTkQgQKotA',
				'width' 	=> '700',
				'height' 	=> '386',
			), $attr));
			return '<if'.'rame width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$id.'?wmode=opaque&amp;rel=0" frameborder="0" allowfullscreen></if'.'rame>';
		break;
		
		/* Google Maps */
		case 'prima_googlemaps':
			$attr = shortcode_atts( array( 
				'width' => '', 
				'height' => '200', 
				'zoom' => '8', 
				'latitude' => '-37.82', 
				'longitude' => '144.97',
				'marker' 	=> 'yes',
				'type' 		=> 'roadmap',
			), $attr );
			$map_id = rand(1, 100);
			$map_width = $attr['width'] ? $attr['width'].'px' : '100%';
			$map_height = $attr['height'].'px';
			if ( $attr['type'] == 'roadmap' ) $map_type = 'ROADMAP';
			elseif ( $attr['type'] == 'satellite' ) $map_type = 'SATELLITE';
			elseif ( $attr['type'] == 'hybrid' ) $map_type = 'HYBRID';
			elseif ( $attr['type'] == 'terrain' ) $map_type = 'TERRAIN';
			else $map_type = 'ROADMAP';
			$map_output = '<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>';
			$map_output .= '<script>function initialize() { ';
			$map_output .= 'var myLatlng'.$map_id.' = new google.maps.LatLng('.$attr['latitude'].','.$attr['longitude'].'); ';
			$map_output .= 'var mapOptions'.$map_id.' = { zoom: '.$attr['zoom'].', center: myLatlng'.$map_id.', mapTypeId: google.maps.MapTypeId.'.$map_type.' }; ';
			$map_output .= 'var map'.$map_id.' = new google.maps.Map(document.getElementById("ps-googlemaps-'.$map_id.'"), mapOptions'.$map_id.'); ';
			if ( $attr['marker'] == 'yes' ) {
				$map_output .= 'var marker'.$map_id.' = new google.maps.Marker({ position: myLatlng'.$map_id.', map: map'.$map_id.' }); ';
			}
			$map_output .= '}google.maps.event.addDomListener(window, "load", initialize);</script>';
			$map_output .= '<div class="ps-googlemaps" id="ps-googlemaps-'.$map_id.'" style="width:'.$map_width.';height:'.$map_height.';"></div>';
			return $map_output;
		break;
		
	}
}

/**
 * Define contact form shortcode.
 *
 * @since PrimaThemes 2.0
 */ 
add_shortcode( 'prima_contact_form', 'prima_contactform_shortcode' );
function prima_contactform_shortcode ( $atts, $content = null ) {
	$defaults = array(
					'email' => get_bloginfo('admin_email'),
					'subject' => __( 'Message via the contact form', 'primathemes' ),
					'sendcopy' => 'yes',
					'question' => '',
					'answer' => '',
					'button_text' => __( 'Submit', 'primathemes' )
					);
	extract( shortcode_atts( $defaults, $atts ) );
	if( trim($email) == '' )
		$email = get_bloginfo('admin_email');
	
	$html = '';
	$error_messages = array();
	$notification = false;
	$email_sent = false;
	if ( ( count( $_POST ) > 3 ) && isset( $_POST['submitted'] ) ) {
		if ( isset ( $_POST['checking'] ) && $_POST['checking'] != '' )
			$error_messages['checking'] = 1;
		if ( isset ( $_POST['contact-name'] ) && $_POST['contact-name'] != '' )
			$message_name = $_POST['contact-name'];
		else 
			$error_messages['contact-name'] = __( 'Please enter your name', 'primathemes' );
		if ( isset ( $_POST['contact-email'] ) && $_POST['contact-email'] != '' && is_email( $_POST['contact-email'] ) )
			$message_email = $_POST['contact-email'];
		else 
			$error_messages['contact-email'] = __( 'Please enter your email address (and please make sure it\'s valid)', 'primathemes' );
		if ( isset ( $_POST['contact-message'] ) && $_POST['contact-message'] != '' )
			$message_body = $_POST['contact-message'] . "\n\r\n\r";
		else 
			$error_messages['contact-message'] = __( 'Please enter your message', 'primathemes' );
		if ( $question && $answer ) {
			if ( isset ( $_POST['contact-quiz'] ) && $_POST['contact-quiz'] != '' ) {
				$message_quiz = $_POST['contact-quiz']; 
				if ( esc_attr( $message_quiz ) != esc_attr( $answer ) )
					$error_messages['contact-quiz'] = __( 'Your answer was wrong!', 'primathemes' );
			}
			else {
				$error_messages['contact-quiz'] = __( 'Please enter your answer', 'primathemes' );
			}
		}
		if ( count( $error_messages ) ) {
			$notification = do_shortcode( '[box color="red"]' . __( 'There were one or more errors while submitting the form.', 'primathemes' ) . '[/box]' );
		} 
		else {
			$ipaddress = '';
			if ($_SERVER['HTTP_CLIENT_IP'])
				$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
			else if($_SERVER['HTTP_X_FORWARDED_FOR'])
				$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
			else if($_SERVER['HTTP_X_FORWARDED'])
				$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
			else if($_SERVER['HTTP_FORWARDED_FOR'])
				$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
			else if($_SERVER['HTTP_FORWARDED'])
				$ipaddress = $_SERVER['HTTP_FORWARDED'];
			else if($_SERVER['REMOTE_ADDR'])
				$ipaddress = $_SERVER['REMOTE_ADDR'];
			else
				$ipaddress = 'UNKNOWN';
			$useragent = $_SERVER['HTTP_USER_AGENT'];
			$message_body = __( 'Email:', 'primathemes' ) . ' '. $message_email . "\r\n\r\n" . $message_body;
			$message_body = __( 'Name:', 'primathemes' ) . ' '. $message_name . "\r\n" . $message_body;
			$message_body = $message_body."\r\n\r\n".__( 'IP Address:', 'primathemes' ).$ipaddress . "\r\n" . __( 'User Agent:', 'primathemes' ).$useragent;
			
			$headers = array();
			$headers[] = 'From: '.$message_name.' <' . $email . '>';
			$headers[] = 'Reply-To: '.$message_email;
			$email_sent = wp_mail($email, $subject, $message_body, $headers);
			
			if ( $sendcopy == 'yes' ) {
				// Send a copy of the e-mail to the sender, if specified.
				if ( isset( $_POST['send-copy'] ) && $_POST['send-copy'] == 'true' ) {
					$subject = __( '[COPY]', 'primathemes' ) . ' ' . $subject;
					$headers = array();
					$headers[] = 'From: '.get_bloginfo('name').' <' . $email . '>';
					$headers[] = 'Reply-To: '.$email;
					$email_sent = wp_mail($message_email, $subject, $message_body, $headers);
				}
			}
			
			if( $email_sent == true ) {
				$notification = do_shortcode( '[box color="green"]' . __( 'Your email was successfully sent.', 'primathemes' ) . '[/box]' );
			}
			else {
				$notification = do_shortcode( '[box color="red"]' . __( 'There were technical error while submitting the form. Sorry for the inconvenience.', 'primathemes' ) . '[/box]' );
			}
	
		}
	}

	$html .= '<div class="contact-form">' . "\n";
	$html .= $notification;
	if ( $email == '' ) {
		$html .= do_shortcode( '[box color="red"]' . __( 'E-mail has not been setup properly. Please add your contact e-mail!', 'primathemes' ) . '[/box]' );
	} 
	else {
		$html .= '<form action="" id="contact-form" method="post">' . "\n";
		$html .= '<fieldset class="forms">' . "\n";
		$contact_name = '';
		if( isset( $_POST['contact-name'] ) ) { $contact_name = $_POST['contact-name']; }
		$contact_email = '';
		if( isset( $_POST['contact-email'] ) ) { $contact_email = $_POST['contact-email']; }
		$contact_message = '';
		if( isset( $_POST['contact-message'] ) ) { $contact_message = stripslashes( $_POST['contact-message'] ); }
		
		$html .= '<p class="field-contact-name">' . "\n";
		$html .= '<input placeholder="' . __( 'Your Name', 'primathemes' ) . '" type="text" name="contact-name" id="contact-name" value="' . esc_attr( $contact_name ) . '" class="txt requiredField" />' . "\n";
		if( array_key_exists( 'contact-name', $error_messages ) ) {
			$html .= '<span class="contact-error">' . $error_messages['contact-name'] . '</span>' . "\n";
		}
		$html .= '</p>' . "\n";

		$html .= '<p class="field-contact-email">' . "\n";
		$html .= '<input placeholder="' . __( 'Your Email', 'primathemes' ) . '" type="text" name="contact-email" id="contact-email" value="' . esc_attr( $contact_email ) . '" class="txt requiredField email" />' . "\n";
		if( array_key_exists( 'contact-email', $error_messages ) ) {
			$html .= '<span class="contact-error">' . $error_messages['contact-email'] . '</span>' . "\n";
		}
		$html .= '</p>' . "\n";

		$html .= '<p class="field-contact-message">' . "\n";
		$html .= '<textarea placeholder="' . __( 'Your Message', 'primathemes' ) . '" name="contact-message" id="contact-message" rows="10" cols="30" class="textarea requiredField">' . esc_textarea( $contact_message ) . '</textarea>' . "\n";
		if( array_key_exists( 'contact-message', $error_messages ) ) {
			$html .= '<span class="contact-error">' . $error_messages['contact-message'] . '</span>' . "\n";
		}
		$html .= '</p>' . "\n";

		if ( $question && $answer ) {
			$html .= '<p class="field-contact-quiz">' . "\n";
			$html .= $question.'<br/>' . "\n";
			$html .= '<input placeholder="' . __( 'Your Answer', 'primathemes' ) . '" type="text" name="contact-quiz" id="contact-quiz" value="" class="txt requiredField quiz" />' . "\n";
			if( array_key_exists( 'contact-quiz', $error_messages ) ) {
				$html .= '<span class="contact-error">' . $error_messages['contact-quiz'] . '</span>' . "\n";
			}
			$html .= '</p>' . "\n";
		}
		
		if ( $sendcopy == 'yes' ) {
			$send_copy = '';
			if(isset($_POST['send-copy']) && $_POST['send-copy'] == true) {
				$send_copy = ' checked="checked"';
			}
			$html .= '<p class="inline"><input type="checkbox" name="send-copy" id="send-copy" value="true"' . $send_copy . ' />&nbsp;&nbsp;<label for="send-copy">' . __( 'Send a copy of this email to yourself', 'primathemes' ) . '</label></p>' . "\n";
		}

		$checking = '';
		if(isset($_POST['checking'])) {
			$checking = $_POST['checking'];
		}

		$html .= '<p class="screen-reader-text"><label for="checking" class="screen-reader-text">' . __('If you want to submit this form, do not enter anything in this field', 'primathemes') . '</label><input type="text" name="checking" id="checking" class="screen-reader-text" value="' . esc_attr( $checking ) . '" /></p>' . "\n";

		$html .= '<p class="buttons"><input type="hidden" name="submitted" id="submitted" value="true" /><input id="contactSubmit" type="submit" value="' . $button_text . '" /></p>';

		$html .= '</fieldset>' . "\n";
		$html .= '</form>' . "\n";

		$html .= '</div><!--/.post .contact-form-->' . "\n";

	}
	
	return $html;
	
}

/**
 * Define raw code shortcode.
 *
 * Credits: 
 * RawR (Raw Revisited) Plugin by Derek Simkowiak http://derek.simkowiak.net/
 *
 * @since PrimaThemes 2.0
 */ 
class Prima_Code {
	function __construct() {
		if ( !function_exists('add_shortcode') ) return;
		$this->unformatted_shortcode_blocks = array();
		add_filter( 'the_content', array(&$this, 'get_unformatted_shortcode_blocks'), 8 );
		add_shortcode( 'prima_code', array(&$this, 'my_shortcode_handler2') );
	}
	function get_unformatted_shortcode_blocks( $content ) {
		global $shortcode_tags;
		$orig_shortcode_tags = $shortcode_tags;
		remove_all_shortcodes();
		add_shortcode( 'prima_code', array(&$this, 'my_shortcode_handler1') );
		$content = do_shortcode( $content );
		$shortcode_tags = $orig_shortcode_tags;
		return $content;
	}
	function my_shortcode_handler1( $atts, $content=null, $code="" ) {
		$this->unformatted_shortcode_blocks[] = $content;
		$content_index = count( $this->unformatted_shortcode_blocks ) - 1;
		return "[prima_code]" . $content_index . "[/prima_code]";
	}
	function my_shortcode_handler2( $atts, $content=null, $code="" ) {
		return '<pre><code>'.$this->unformatted_shortcode_blocks[ $content ].'</code></pre>';
	}
}
global $_prima_code; 
$_prima_code = new Prima_Code();
