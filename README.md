# pat_typo
Typographic enhancements for titles in Textpattern CMS

![Without plugin](https://raw.githubusercontent.com/cara-tm/pat_typo/master/without-plugin.png "Without plugin")

A plugin for titles where Textpattern can't improves the peculiarities of the French language that adds some enhancements in order to avoid the typographic widow effect in responsive design context:

* injects hair spaces after the opening and before the closing french quotes "guillemets";
* injects non breaking spaces arround small words;
* injects non breaking spaces before colons, exclamation points, question marks, semi colons.

## Additional CSS rules

For good renderings, please add this simple rule into your stylesheets:

    .thinsp {
    	white-space: nowrap;
    }

The same process noticed within some online french newspaper websites.

Notice: This plugin solves all writting errors and adds spaces accordingly based to typographic rules.

![Result with plugin](https://raw.githubusercontent.com/cara-tm/pat_typo/master/with-plugin-enabled.png "With plugin enabled")
