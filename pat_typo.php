<?php
/**
 * Typographic enhancements for titles in Textpattern CMS
 *
 * @author:  Patrick LEFEVRE.
 * @link:    https://github.com/cara-tm/pat_typo
 * @type:    Public
 * @prefs:   no
 * @order:   5
 * @version: 0.3.0
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
 * Main plugin function
 *
 * @param  $text   string  The text content
 * @param  $lang   string  Country code (ISO2)
 * @param  $choice boolean Change the replacement sign
 * @return $atts   string  The text content
 */
function pat_typo($atts, $thing = null)
{

	extract(lAtts(array(
		'text'      => title(array('no_widow' => 0)),
		'no_widow'  => false,
		'lang'      => get_pref('language', TEXTPATTERN_DEFAULT_LANG, true),
		'force'     => false,
		'inclusive' => false,
	), $atts));

	//$text = _fewchars($text);
	$text = _numerals($text);
	$text = _punctuation($text, $lang);
	$text = _widont($text, $no_widow);
	$text = _first_signs($text , $lang, $force);
	$text = _last_signs($text , $lang, $force);
	$text = _dash($text, $lang, $force);
	$text = _inclusive($text, $inclusive);

	return $text;

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

	$matches = array('1/2', '1/3', '1/4', '3/4', '0/00', '/1', '/2', '/3');
	$numbers = array('½', '⅓', '¼', '¾', '‰', '¹', '²', '³');

	return str_replace($matches, $numbers, $temp);

}


/**
 * _fewchars
 *
 * Surrounds few characters with non breaking spaces
 *
 * @param $text  string The text entry
 * @return $text string The new text entry
 */
function _fewchars($text)
{
	$matches = '/\s([a-zA-Z0-9]{1,3}[^&#39;])(\s)/';

	return preg_replace($matches, '&nbsp;$1$2', $text);
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
		$pos = '/\s?[^&#\w;](\w+)(\;)/im';
		$temp = preg_replace($pos, ' $1&nbsp;$2', $text);
		$pattern = '/(\s)?(:|\?|\!|\%|\€)/im';
		return preg_replace($pattern, '&nbsp;$2', $temp);
	} else {
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
	if ($lang == 'fr' or $lang == 'fr-FR') {
		$thin = ($force == false ? '«&#x0202F;' : '<span class="thinsp">«&#8202;</span>');
		// Starting quotes
		$matches = '/(«|&#34;\/|"\/)(\s?)/';
		return preg_replace($matches, $thin, $text);
	} else {
		$sign = ($force == false ? '“$2' : '<span class="thinsp">“</span>$2');
		$matches = '/(&#34;\/|"\/)(\s?)/';
		return preg_replace($matches, $sign, $text);
	}
}
function _last_signs($text, $lang, $force)
{
	if ($lang == 'fr' or $lang == 'fr-FR') {
		$thin = $force == false ? '&#x0202F;»' : '<span class="thinsp">&#8202;»</span>';
		// Final quotes
		$matches = '/(\s?)(»|\/&#34;|\/")/';
		return preg_replace($matches, $thin, $text);
	} else {
		$sign = $force == false ? '”$2' : '<span class="thinsp">”</span>$2';
		$matches = '/(\/&#34;|\/")(\s?)/';
		return preg_replace($matches, $sign, $text);
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

	$sign = '—';
	$thin = $force == false ? $sign.'$3'.$sign.'$6' : '<span class="thinsp">'.$sign.'$3'.$sign.'</span>$6';
	if ($lang == 'fr' or $lang == 'fr-FR') {
		$sign = '–';
	$thin = $force == false ? $sign.'&#x0202F;$3&#x0202F;'.$sign.'$6' : '<span class="thinsp">'.$sign.'&#8202;$3&#8202;'.$sign.'</span>$6';
	}

	//$thin = $force == false ? $sign.'&#x0202F;$3&#x0202F;'.$sign.'$6' : '<span class="thinsp">'.$sign.'&#8202;$3&#8202;'.$sign.'</span>$6';
	$matches = '/(&mdash;|&ndash;|&#x2013;|&#8211;|&#x2014;|&#8212;|—|–|-)(\s|&nbsp;|&thinsp;)?(\w*)(\s|&nbsp;|&thinsp;)?(&mdash;|&ndash;|&#x2013;|&#8211;|&#x2014;|&#8212;|—|–|-)(\s|&nbsp;|&thinsp;)?/sU';

	return preg_replace($matches, $thin, $text);

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
	if (true == $inclusive) {
		$matches = '/(\w+)\.(\w+)?/im';
		return preg_replace($matches, '$1<span class="bull">•</span>$2', $text);
	} else {
		return $text;
	}
}
