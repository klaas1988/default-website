<?php 
/**
 * General functions, no need to categorize at this time
 *
 * WARNING: This file is part of the core PrimaThemes framework.
 * DO NOT edit this file under any circumstances. 
 *
 * Credits (and Inspirations):
 * - Twitter widget of Genesis by StudioPress http://studiopress.com
 * - Snipe.net http://www.snipe.net/2009/09/php-twitter-clickable-links/ 
 *
 * @category   PrimaThemes
 * @package    Framework
 * @subpackage Functions
 * @author     PrimaThemes
 * @link       http://www.primathemes.com
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Output custom search form 
 *
 * @since PrimaThemes 2.0
 */ 
function prima_search_form( $args = array() ) {
	$defaults = array(
		'search_text' => __('Search this website&hellip;', 'primathemes'),
		'button_text' => __( 'Search', 'primathemes' ),
		'echo' => false
	);
	$args = apply_filters( 'prima_search_form_args', $args );
	$args = wp_parse_args( $args, $defaults );
	$search_text = esc_js( $args['search_text'] );
	$query_text = get_search_query() ? esc_js( get_search_query() ) : '';
	$button_text = esc_attr( $args['button_text'] );
	$searchform = '
		<form method="get" class="searchform" action="' . home_url() . '/" >
			<input type="text" value="'.$query_text.'" name="s" class="searchtext" placeholder="'.$search_text.'" />
			<input type="submit" class="searchsubmit" value="'. $button_text .'" />
		</form>
	';
	if ($args['echo']) echo $searchform;
	else return $searchform;
}

/**
 * Output Feedburner subscription form 
 *
 * @since PrimaThemes 2.0
 */ 
function prima_feedburner_form( $args = array() ) {
	$defaults = array(
		'id' => '', 
		'language' => __( 'en_US', 'primathemes' ), 
		'input_text' => __( 'Enter your email address...', 'primathemes' ), 
		'button_text' => __( 'Subscribe', 'primathemes' ),
		'echo' => false
	);
	$args = apply_filters( 'prima_search_form_args', $args );
	$args = wp_parse_args( $args, $defaults );
	$id = esc_js( $args['id'] );
	$language = esc_attr( $args['language'] );
	$input_text = esc_attr( $args['input_text'] );
	$button_text = esc_attr( $args['button_text'] );
	$onsubmit = " onsubmit=\"window.open('http://feedburner.google.com/fb/a/mailverify?uri=$id', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true\" ";
	if ( $id ) {
		$feedburnerform = '
			<form class="feedburner-subscribe" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" '. $onsubmit .'>
				<input type="text" value="" class="feedburnertext" placeholder="'.$input_text.'" />
				<input type="hidden" value="'. $id .'" name="uri"/>
				<input type="hidden" name="loc" value="'. $language .'"/>
				<input type="submit" class="feedburnersubmit" value="'. $button_text .'" />
			</form>
		';
	}
	else {
		$feedburnerform = '<p>'.__ ( 'No Feedburner ID was available', 'primathemes' ).'</p>';
	}
	if ($args['echo']) echo $feedburnerform;
	else return $feedburnerform;
}
 
/**
 * Echo twitter latest tweets
 *
 * @since PrimaThemes 2.0
 */
function prima_twitter( $args = array() ) {
	echo prima_get_twitter( $args );
}

/**
 * Return twitter latest tweets
 *
 * @since PrimaThemes 2.0
 */
function prima_get_twitter( $args = array() ) {
	$defaults = array(
		'username' => 'primathemes', 
		'limit' => 3, 
		'interval' => 600, 
	);
	$args = apply_filters( 'prima_twitter_args', $args );
	$args = wp_parse_args( $args, $defaults );
	extract( $args );
	if ( ! $username ) return false;
	$output = '';
	$output .= '<ul>';
	$tweets = get_transient( $username . '_' . $limit . '_' . $interval );
	if ( ! $tweets ) {
		$twitter = wp_remote_retrieve_body(
			wp_remote_request(
				sprintf( 'http://api.twitter.com/1/statuses/user_timeline.json?screen_name=%s&count=%s&trim_user=1', $username, $limit ),
				array( 'timeout' => 60, )
			)
		);
		$json = json_decode( $twitter );
		if ( !$twitter )
			$tweets[] = '<li>' . __( 'The Twitter API is taking too long to respond. Please try again later.', 'primathemes' ) . '</li>';
		elseif ( is_wp_error( $twitter ) )
			$tweets[] = '<li>' . __( 'There was an error while attempting to contact the Twitter API. Please try again.', 'primathemes' ) . '</li>';
		elseif ( is_object( $json ) && $json->error )
			$tweets[] = '<li>' . __( 'The Twitter API returned an error while processing your request. Please try again.', 'primathemes' ) . '</li>';
		else {
			foreach ( (array) $json as $tweet ) {
				$timeago = sprintf( __( 'about %s ago', 'primathemes' ), human_time_diff( strtotime( $tweet->created_at ) ) );
				$timeago_link = sprintf( '<a href="%s" rel="nofollow">%s</a>', esc_url( sprintf( 'http://twitter.com/%s/status/%s', $username, $tweet->id_str ) ), esc_html( $timeago ) );
				$tweets[] = '<li>' . prima_tweet_linkify( $tweet->text ) . ' <span style="font-size: 85%;">' . $timeago_link . '</span></li>' . "\n";
			}
			$tweets = array_slice( (array) $tweets, 0, (int) $limit );
			$time = ( absint( $interval ) * 60 );
			set_transient( $username.'_'.$limit.'_'.$interval, $tweets, $time );
		}
	}
	foreach( (array) $tweets as $tweet )
		$output .= $tweet;

	$output .= '</ul>';
	return $output;
}

/**
 * Make tweet linkable
 *
 * @since PrimaThemes 2.0
 */
function prima_tweet_linkify($tweet) {
	$tweet = preg_replace_callback( '~(?<!\w)(https?://\S+\w|www\.\S+\w|@\w+|#\w+)|[<>&]~u', 'prima_tweet_clickable', html_entity_decode($tweet, ENT_QUOTES, 'UTF-8'));
	return $tweet;
}

/**
 * Make tweet clickable
 *
 * @since PrimaThemes 2.0
 */
function prima_tweet_clickable($m) {
	$m = htmlspecialchars($m[0]);
	if ($m[0] === '#') {
		$m = substr($m, 1);
		return "<a href='http://twitter.com/search?q=%23$m'>#$m</a>";
	} 
	elseif ($m[0] === '@') {
		$m = substr($m, 1);
		return "@<a href='http://twitter.com/$m'>$m</a>";
	} 
	elseif ($m[0] === 'w') {
		return "<a href='http://$m'>$m</a>";
	}
	elseif ($m[0] === 'h') {
		return "<a href='$m'>$m</a>";
	} 
	else {
		return $m;
	}
}