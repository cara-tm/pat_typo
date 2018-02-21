<?php
/**
 * Typographic enhancements for titles in Textpattern CMS
 *
 * @author:  Patrick LEFEVRE.
 * @link:    https://github.com/cara-tm/pat_typo
 * @type:    Public
 * @prefs:   no
 * @order:   5
 * @version: 0.2.0
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
		'text'     => title(array()),
		'no_widow' => false,
		'lang'     => get_pref('language', TEXTPATTERN_DEFAULT_LANG, true),
		'force'    => false,
	), $atts));

	$text = _widont($text, $no_widow);
	$text = _first_guillemets($text , $force);
	$text = _last_guillemets($text , $force);
	//$text = _fewchars($text);
	$text = _punctuation($text, $lang);
	$text = _dash($text, $force);

	return $text;

}


/**
 * _widont
 * 
 * Replaces the space between the last two words in a string with &160; even with punctuation signs
 * 
 * @param  $text     string  The text entry
 * @param  $no_widow boolean TXP attribute
 * @return $text     string  The text entry
 */
function _widont($text, $no_widow)
{
	return (true == no_widow ? preg_replace( '/\s([^\s\»]+)\s*$/', '&160;$1', $text) : $text);
}


/**
 * _guillemet
 *
 * Adds spaces around the french quotes 'guillemets' (2 functions & regex)
 *
 * @param  $text   string  The article title
 * @param  $choice boolean What kind of traitement? Hair spaces or HTML tags
 * @return $string
 */
function _first_guillemets($text, $force)
{
	$thin = ($force == false ? '«&#x0202F;' : '<span class="thinsp">«&#8202;</span>');
	// Starting quotes
	$matches = '/(«)(\s?)/';
	return preg_replace($matches, $thin, $text);
}
function _last_guillemets($text, $force)
{
	$thin = $force == false ? '&#x0202F;»' : '<span class="thinsp">&#8202;»</span>';
	// Final quotes
	$matches = '/(\s?)(»)/';
	return preg_replace($matches, $thin, $text);

}


/**
 * _fewchars
 *
 * Surrounds few characters with non breaking spaces
 *
 * @param
 * @return
 */
function _fewchars($text)
{
	$matches = '/\s([a-z0-9\:]{0,2})\s/';

	return preg_replace($matches, '&nbsp;$1&nbsp;', $text);
}


/**
 * _punctuation
 *
 * Adds a space before some signs for French language
 *
 * @param $text  string The text entry
 * @param $lang  string French country code
 * @return $text string The new text entry
 */
function _punctuation($text, $lang = null)
{

	if ($lang === 'fr' or $lang === 'fr-FR') {
		$pattern = '/(\s)?(:|\?|\!|\;|\%|\€)(\s)/im';
		return preg_replace($pattern, '&nbsp;$2$3', $text);
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
 * @param  $text string The text entry
 * @return $text string The new text entry
 */
function _dash($text, $force)
{

	$thin = $force == false ? '–&#x0202F;$3&#x0202F;–$6' : '<span class="thinsp">–&#8202;$3&#8202;–</span>$6';
	$matches = '/(&mdash;|&ndash;|&#x2013;|&#8211;|&#x2014;|&#8212;|—|–|-)(\s|&nbsp;|&thinsp;)?(\w*)(\s|&nbsp;|&thinsp;)?(&mdash;|&ndash;|&#x2013;|&#8211;|&#x2014;|&#8212;|—|–|-)(\s|&nbsp;|&thinsp;)?/sU';

	return preg_replace($matches, $thin, $text);

}

