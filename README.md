# pat_typo
Typographic enhancements for titles in Textpattern CMS. The same processes in use within some online french newspaper websites (e.g. courtesy © Libération: http://bit.ly/2EatTuE).

![Without plugin](https://raw.githubusercontent.com/cara-tm/pat_typo/master/without-plugin.png "Without plugin")

A plugin for titles where Textpattern can't improves the peculiarities of the French language (or gives some solutions for english) that adds some enhancements in order to avoid the typographic widow effect in responsive design context:

* provides a in build generator for automatical creation of real french quote signs or real english quote signs based on a simple writting convention;
* injects thin spaces after the opening and before the closing french quotes "guillemets";
* injects non breaking spaces arround small (3 letters width) words (to be improved);
* injects non breaking spaces before colons, exclamation points, question marks, semi colons (v 0.3.0 onwards) even if omitted for French language;
* replaces simple keyboard dashes (`-`) to en dashes (`–`), for French users, or converts it to em dashes (`—`) for English ones;
* converts specific numerals to typographic equivalents: 1/2 to ½ 1/3 to ⅓ 1/4 to ¼ 3/4 to ¾ 0/00 to ‰ and, for these writting conventions: `/1` to ¹ `/2` to ² `/3` to ³ superscript numbers (v 0.3.0 onwards);
* support for the "inclusive notation" (optional, v 0.3.0 onwards). Could be a SEO problem depending on the use.


## Performance considerations

This plugin works on the fly but do not saves its results into the Textpattern corresponding table because of the numerous changes applyied onto titles whose display could be a little bit confusing for writers. Even if we didn't notice notable slowdowns within website renderings, you could take advantages to adopt a cache system. We suggest you the use of the `etc_cache` plugin (http://www.iut-fbleau.fr/projet/etc/index.php?id=52)... or (see Advice below)


e.g. Into a page template:

    <txp:etc_cache id="articles">
        <txp:article form="default" listform="default" limit="10" />
    </txp:etc_cache>

and the corresponding `default` form:

    <h2><txp:pat_typo force="0" no_widow="1" lang="fr" inclusive="1" /></h2>

## Advice

...Authors can preview their draft articles then copy/paste the resulting titles in replacement of the original ones (without all the special writing conventions).

## Usage

In replacement of the native `<txp:title />`tag:

    <txp:pat_typo no_widow="" lang="" force="" inclusive="" preview_only="" />

## Attributes

* `no_widow` boolean (optional): same feature as the build in, if set to `true` (or `1`) avoid widow effect on last word or sign. Default: `false`.
* `lang` string (optional): country code for French language in order to apply specific typographic rules. Default: user preferences language.
* `force` boolean (optional): allow to switch from HEX encoding non breaking hair spaces to HTML tags markup. Default: false (`0`) HEX non breaking hair spaces.
* `inclusive` boolean (optional): supports the "_inclusive notation_" where dots are replaced by bull signs into words. Default: false (`0`) (v 0.3.0 onwards).
* `preview_only` boolean (optional): if set to `true` apply changes only on article preview. Default: true (`1`) depending on the plugin parameter "_pat_typo_preview_only_"_ radio button available into the Textpattern preferences panel.

## Typography helper

Depending of the `lang`attribute value, automatic conversion to real French quotes signs with this simple typographic convention: `"/my word/"` that will be displayed as this: `« my word »` (signs surrounding with hair spaces) or `“my word”` for English users.
Automatic conversion for keyboard `-` signs to en dashes (`–`) for French users or em dashes (`—`) for English ones. Converts `/1` to ¹ `/2` to ² and `/3` to ³ superscript numbers.

## Additional CSS rules

For good renderings, please add this simple rule into your stylesheets (if force attribute is set to `1`):

    .thinsp {
    	white-space: nowrap;
    }


Notice: This plugin solves all writting errors and adds spaces accordingly based to typographic rules.


Here is an example of many writing errors (but with the alternative French quote signs generator):
 
![Lot of writing errors sample](https://raw.githubusercontent.com/cara-tm/pat_typo/master/writing-errors.png "Sample of writing errors")


And here is the results:
 
![Result with plugin](https://raw.githubusercontent.com/cara-tm/pat_typo/master/with-plugin-enabled.png "With plugin enabled")
