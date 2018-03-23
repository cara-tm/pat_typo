<?php
/**
 * Typographic enhancements for titles in Textpattern CMS
 *
 * @author:  Patrick LEFEVRE.
 * @link:    https://github.com/cara-tm/pat_typo
 * @type:    Admin + Public
 * @prefs:   no
 * @order:   5
 * @version: 0.4.0
 * @license: GPLv2
*/


/**
 * This plugin tag registry.
 */
if (class_exists('\Textpattern\Tag\Registry')) {
	Txp::get('\Textpattern\Tag\Registry')
		->register('pat_typo');
}


/**
 * This plugin lifecycle.
 */
if (txpinterface == 'admin')
{
	register_callback('pat_typo_prefs', 'prefs', '', 1);
	register_callback('pat_typo_cleanup', 'plugin_lifecycle.pat_typo', 'deleted');
}


/**
 * Main plugin function
 *
 * @param  $text   string  The text content
 * @param  $lang   string  Country code (ISO2)
 * @param  $choice boolean Change the replacement sign
 * @return $atts   string  The text content
 */
function pat_typo($atts, $thing = null)
{
	global $_proceed;

	extract(lAtts(array(
		'text'         => 'title',
		'no_widow'     => false,
		'lang'         => get_pref('language', TEXTPATTERN_DEFAULT_LANG, true),
		'force'        => false,
		'inclusive'    => false,
		'preview_only' => true,
	), $atts));

	$_proceed = true;

	// Text content choice
	if ($text == 'title') {
		$text = parse('<txp:title escape="tidy, textile" no_widow="0" />');
	} elseif ($text == 'body') {
		$_proceed = false;
		$text = body(array());
	} else {
		trigger_error(gTxt('invalid_attribute_value', array('{text}' => 'Incorrect text content..')), E_USER_WARNING);
	}


	// List of functions to load:
	if (true == $preview_only or true == get_pref('pat_typo_preview_only')) {

		if (gps('txpreview')) {

			$text = _errors($text);
			$text = _dialogs($text, $lang);
			$text =  _numerals($text);
			$text = _smallwords($text);
			$text = _punctuation($text, $lang);
			$text = _widont($text, $no_widow);
			$text = _first_signs($text , $lang, $force);
			$text = _last_signs($text , $lang, $force);
			$text = _dash($text, $lang, $force);
			$text = _alpha($text, $lang);
			$text = _inclusive($text, $inclusive);
		}

	} elseif (false == $preview_only or false == get_pref('pat_typo_proview_only')) {

		$text = _errors($text);
		$text = _dialogs($text, $lang);
		$text = _numerals($text);
		$text = _smallwords($text);
		$text = _punctuation($text, $lang);
		$text = _widont($text, $no_widow);
		$text = _first_signs($text , $lang, $force);
		$text = _last_signs($text , $lang, $force);
		$text = _dash($text, $lang, $force);
		$text = _alpha($text, $lang);
		$text = _inclusive($text, $inclusive);
	}

	return $text;
}


/**
 * _errors
 *
 * Remove some writing errors
 *
 * @param $text string  The text entry
 * @return $text string The new text entry
*/
function _errors($text)
{
	$first = '/(\()\s+?/im';
	$temp = preg_replace($first, '$1', $text);
	$matches = '/\s+?(\,|\.|\)|\/)/im';

	return preg_replace($matches, '$1', $temp);

}


/**
 * _dialogs
 *
 * Converts starting hyphens into new lines by the relevant signs
 *
 * @param $text  string The text entry
 * @return $text string The new text entry
 */
function _dialogs($text, $lang)
{
	$matches = '/^((<.*>)?[A-Za-z]*|(\R)|\s)\-(\s+)?(.*)/mxu';
	return preg_replace($matches, '$1&mdash;&nbsp;$5', $text);
}


/**
 * _numerals
 *
 * Converts some numbers
 *
 * @param $text  string The text entry
 * @return $text string The new text entry
 */
function _numerals($text)
{

	$pos = '/\s?(\/1|\/2|\/3)/';
	$temp = preg_replace($pos, '$1', $text);

	// Note: Textile use this '[1/2]' convention instead but do not render '[1/3]'
	$matches = array('1/2', '1/3', '1/4', '3/4', '0/00', '/1', '/2', '/3');
	$numbers = array('½', '⅓', '¼', '¾', '‰', '¹', '²', '³');

	return str_replace($matches, $numbers, $temp);
}


/**
 * _smallwords
 *
 * Surrounds few characters with non breaking spaces
 *
 * @param $text  string The text entry
 * @return $text string The new text entry
 */
function _smallwords($text)
{
	global $_proceed;

	if ($_proceed) {
		$matches = '/(\s)([\p{L}]{0,2}?[^&#39;|\»|\!|\–|\:|\«]\s)/';
		return preg_replace($matches, '&nbsp;$2', $text);
	} else {
		return $text;
	}
}


/**
 * _punctuation
 *
 * Adds a space before some signs for French language
 *
 * @param  $text string The text entry
 * @param  $lang string The country code
 * @return $text string The new text entry
 */
function _punctuation($text , $lang = null)
{
	if ($lang === 'fr' or $lang === 'fr-FR') {
		// Semi colons
		$pos = '/([^&\w\;]\p{L}+|\»|\"|\))(\s+)?([\;])/';
		$temp = preg_replace($pos, '$1&#x2005;;', $text);
		// All others punctuation signs
		$pattern = '/(\s+)?(\:[^\/\/]|\?|\!|\%|\€|km|cm|kg|mg|kW)/m'; // '/(\s)?(:|\?|\!|\%|\€)/im';
		return preg_replace($pattern, '&#160;$2', $temp);
	} else {
		// All other languages than FRench
		return $text;
	}
}


/**
 * widont
 * 
 * Replaces the space between the last two words in a string with &160;
 * 
 * @param  $text     string  The text entry
 * @param  $no_widow boolean TXP attribute
 * @return $text     string  The new text entry
 */
function _widont($text, $no_widow)
{
	return (true == $no_widow ? preg_replace( '/(\s)([^\s\»]+)\s*(\s)?$/', '&#160;$2', $text) : $text);
}


/**
 * _signs
 *
 * Adds spaces around the french quotes 'guillemets' or change by quotation marks
 *
 * @param  $text   string  The article title
 * @param  $lang   string  The country code
 * @param  $choice boolean Choice for hair spaces or HTML tags
 * @return $string
 */
function _first_signs($text, $lang, $force)
{
	global $_proceed;

	if ($_proceed) {
		if ($lang == 'fr' or $lang == 'fr-FR') {
			$thin = $force == false ? '$1&#x0202F;' : '<span class="thinsp">$1&#8202;</span>';
			// Starting quotes
			$matches = '/(\«)(?<space> )(\s+)?/'; // '/(«|&#34;\/|"\/)(\s?)/';
			return preg_replace($matches, $thin, $text);
		} else {
			$sign = $force == false ? '“$2' : '<span class="thinsp">“</span>$2';
			$matches = '/(&#34;\/|"\/)(\s?)/';
			return preg_replace($matches, $sign, $text);
		}
	} else {
		return $text;
	}
	
}
function _last_signs($text, $lang, $force)
{
	global $_proceed;

	if ($_proceed) {
		if ($lang == 'fr' or $lang == 'fr-FR') {
			$thin = $force == false ? '&#x0202F;$3' : '<span class="thinsp">&#8205;$3</span>';
			// Final quotes
			//$matches = '/([&#]\w+?;|(\bspa\b|\b160\b))?(\s+)?(\»)/'; // '/(\s?)(»|\/&#34;|\/")/';
			$matches = '/(\s+)?(?<char>�)?(\s+)?(\»)/';
			return preg_replace($matches, $thin, $text);
		} else {
			$sign = $force == false ? '”$2' : '<span class="thinsp">”</span>$2';
			$matches = '/(\/&#34;|\/")(\s?)/';
			return preg_replace($matches, $sign, $text);
		}
	} else {
		return $text;
	}

}


/**
 * _dash
 * 
 * Puts a &thinsp; before and after an &ndash or &mdash;
 * Change sinple dash by endashes instead.
 *
 * @param  $text string   The text entry
 * @param  $lang string   The country code
 * @param  $force boolean Choice for hair spaces or HTML tags
 * @return $text string   The new text entry
 */
function _dash($text, $lang, $force)
{
	if ($lang == 'fr' or $lang == 'fr-FR') {

		$thin = $force == false ? $sign.'&#x0202F;$3&#x0202F;'.$sign.'$6' : '<span class="thinsp">'.$sign.'&#8202;$3&#8202;'.$sign.'</span>$6';

		$matches = '/(&#8211;|—|–|-)(\s+)?(.*)(&#8211;|—|–|-)\s/';
		return preg_replace($matches, '–&#x2005;$3&#x2005;– ', $text);
	} else {
		return $text;
	}
	
}


/**
 * _alpha
 *
 * Change individual first 'A' letter to its propper 'À' sign
 *
 * @param  $text string The text entry
 * @param  $lang string The country code
 * @return $text string The new text content
 */
function _alpha($text, $lang)
{
	if ($lang == 'fr' or $lang == 'fr-FR') {
		$matches = '/^(<.*>)?(—\s|&mdash;\s)?[A][^\p{L}]|(\.\s)[A](\s)/im'; // '/^(<.*>)?[A][^\p{L}]|(\.\s)[A](\s)/im';
		return preg_replace($matches, '$1$2$3À ', $text);
	} else {
		return $text;
	}
}


/**
 * _inclusive
 *
 * Adopts the "inclusive" notation for French users only
 *
 * @param $text      string  The text content
 * @param $inclusive boolean Choose to adopt or not the "inclusive notation"
 * @return $text     string  The new text content
 *
 */
function _inclusive($text, $inclusive)
{
	global $_proceed;

	if (true == $inclusive and true == $_proceed) {
		$matches = '/\.(\p{Ll}+)/m';
		return preg_replace($matches, '<span class="bull">&#8288;•&#8288;</span>$1', $text);
	} else {
		return $text;
	}
}


/**
 * This plugin preferences
 *
 * @param
 * @return SQL Plugin preference field
 */
function pat_typo_prefs()
{
	global $textarray;

	$textarray['pat_typo_preview_only'] = 'Enable pat_typo on article preview only?';

	if (!safe_field('name', 'txp_prefs', "name='pat_typo_preview_only'"))
	{
		safe_insert('txp_prefs', "name='pat_typo_preview_only', val='1', type=1, event='admin', html='yesnoradio', position=30");
	}
}


/**
 * This plugin cleanup on deletion
 *
 * @param
 * @return SQL Safe delete field
 */
function pat_typo_cleanup()
{
	safe_delete('txp_prefs', "name='pat_typo_preview_only'");
}


