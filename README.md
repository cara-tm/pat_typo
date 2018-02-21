# pat_typo
Typographic enhancements for titles in Textpattern CMS. The same processes in use within some online french newspaper websites.

![Without plugin](https://raw.githubusercontent.com/cara-tm/pat_typo/master/without-plugin.png "Without plugin")

A plugin for titles where Textpattern can't improves the peculiarities of the French language that adds some enhancements in order to avoid the typographic widow effect in responsive design context:

* injects hair spaces after the opening and before the closing french quotes "guillemets";
* injects non breaking spaces arround small words (to improve);
* injects non breaking spaces before colons, exclamation points, question marks, semi colons even if omitted;
* replaces simple dashes to endashes.

## Usage

In replacement of the native `<txp:title />`tag:

    <txp:pat_typo no_widow="" lang="" force="" />

## Attributes

* `no_widow` boolean (optional): same feature as the build in, if set to `true` (or `1`) avoid widow effect on last word or sign. Default: `false`.
* `lang` string (optional): country code for French language in order to apply specific typographic rules. Default: user preferences language.
* `force` boolean (optional): allow to switch from HEX encoding non breaking hair spaces to HTML tags markup. Default: false (`0`) HEX non breaking hair spaces. 

## Additional CSS rules

For good renderings, please add this simple rule into your stylesheets (if force attribute is set to `1`):

    .thinsp {
    	white-space: nowrap;
    }


Notice: This plugin solves all writting errors and adds spaces accordingly based to typographic rules.


Here is an example of many writing errors:
 
![Lot of writting errors sample](https://raw.githubusercontent.com/cara-tm/pat_typo/master/writing-errors.png "Sample of writting errors")


And here is the results:
 
![Result with plugin](https://raw.githubusercontent.com/cara-tm/pat_typo/master/with-plugin-enabled.png "With plugin enabled")
