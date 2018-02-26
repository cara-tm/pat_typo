# pat_typo
Typographic enhancements for titles in Textpattern CMS. The same processes in use within some online french newspaper websites (e.g. courtesy © Libération: http://bit.ly/2EatTuE).

![Without plugin](https://raw.githubusercontent.com/cara-tm/pat_typo/master/without-plugin.png "Without plugin")

A plugin for titles where Textpattern can't improves the peculiarities of the French language (or gives some solutions for english) that adds some enhancements in order to avoid the typographic widow effect in responsive design context:

* provides a in build generator for automatical creation of real french quote signs or real english quote signs based on a simple writting convention;
* injects thin spaces after the opening and before the closing french quotes "guillemets";
* injects non breaking spaces arround small (3 letters width) words (to be improved);
* injects non breaking spaces before colons, exclamation points, question marks, semi colons (v 0.3.0 onwards) even if omitted for French language;
* replaces simple keyboard dashes (`-`) to en dashes (`–`), for French users, or converts it to em dashes (`—`) for English ones;
* support for the "inclusive notation" (optional, v 0.3.0 onwards).

## Usage

In replacement of the native `<txp:title />`tag:

    <txp:pat_typo no_widow="" lang="" force="" />

## Attributes

* `no_widow` boolean (optional): same feature as the build in, if set to `true` (or `1`) avoid widow effect on last word or sign. Default: `false`.
* `lang` string (optional): country code for French language in order to apply specific typographic rules. Default: user preferences language.
* `force` boolean (optional): allow to switch from HEX encoding non breaking hair spaces to HTML tags markup. Default: false (`0`) HEX non breaking hair spaces. 

## Typography helper

Depending of the `lang`attribute value, automatic conversion to real French quotes signs with this simple typographic convention: `"/my word/"` that will be displayed as this: `« my word »` (signs surrounding with hair spaces) or `“my word”` for English users.
Automatic conversion for keyboard `-` signs to en dashes (`–`) for French users or em dashes (`—`) for English ones.

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
