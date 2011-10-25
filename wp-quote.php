<?php
/*
Plugin Name: WP Quote
Version: 1.0
Author: CyberSEO.NET
Author URI: http://www.cyberseo.net/
Plugin URI: http://www.cyberseo.net/wp-quote/
Description: Adds random quotes from famous people or whatever text you want to every post and page of your blog. HTML tags are allowed.
*/

define ( 'WP_QUOTE', 'wp_quote_array' );

if (isset ( $_POST ['Save'] )) {
	$wp_quote_array = explode ( "\n", $_POST [WP_QUOTE] );
	for($i = 0; $i < sizeof ( $wp_quote_array ); $i ++) {
		$wp_quote_array [$i] = trim ( $wp_quote_array [$i] );
		if (mb_strlen ( $wp_quote_array [$i] ) == 0) {
			array_splice ( $wp_quote_array, $i, 1 );
		}
	}
	update_option ( WP_QUOTE, $wp_quote_array );
}

function wpquote_show_menu() {
global $wpdb;
?>
<div class="wrap">
<h2>WP Quote</h2>
<table class="form-table" style="margin-top: .5em" width="100%">
	<tbody>
		<tr>
			<td>
			<form method="post">
			<table class="widefat">
				<tr valign="top">
					<td align="left">Place here the famous quotes or whatever text you want. One quote per line. HTML tags are allowed.<br />
					<textarea name="<?php echo WP_QUOTE; ?>" wrap="off" style="margin:0;height:30em;width:100%;"><?php
					$wp_quote_array = get_option ( WP_QUOTE );
					echo trim ( stripslashes ( htmlspecialchars ( implode ( "\n", $wp_quote_array ) ) ) );
					?></textarea>
				</tr>
			</table>
			<br />
			<div align="center"><input type="submit" name="Save"
				class="button-primary" value="Save Changes" />&nbsp;&nbsp;<input
				type="button" name="cancel" value="Cancel" class="button"
				onclick="javascript:history.go(-1)" /></div>
			</form>
			</td>
		</tr>
	</tbody>
</table>
</div>
<?php
}

if (get_option ( WP_QUOTE ) === false) {
	add_option ( WP_QUOTE, array ('"Patriot: the person who can holler the loudest without knowing what he is hollering about."<br />~ Mark Twain', '"Glory is fleeting, but obscurity is forever."<br />~ Napoleon Bonaparte', '"Not everything that can be counted counts, and not everything that counts can be counted."<br />~ Albert Einstein', '"I find that the harder I work, the more luck I seem to have."<br />~ Thomas Jefferson', '"Do, or do not. There is no \'try\'."<br />~ Master Yoda', '"The only difference between me and a madman is that I\'m not mad."<br />~ Salvador Dali' ), '', 'yes' );
}

function wpquote_content($content) {
	global $post;
	$wp_quote_array = get_option ( WP_QUOTE );
	if (count ( $wp_quote_array )) {
		mt_srand ( intval ( $post->ID ) );
		$content .= '<div id="wp_quote">' . $wp_quote_array [mt_rand ( 0, count ( $wp_quote_array ) - 1 )] . '</div>';
	}
	return $content;
}

function wpquote_main_menu() {
	if (function_exists ( 'add_options_page' )) {
		add_options_page ( __ ( 'WP Quote' ), __ ( 'WP Quote' ), 'edit_posts', 'wp_quote', 'wpquote_show_menu' );
	}
}

if (is_admin ()) {
	add_action ( 'admin_menu', 'wpquote_main_menu' );
} else {
	add_filter ( 'the_content', 'wpquote_content' );
	add_filter ( 'the_excerpt', 'wpquote_content' );
}
?>