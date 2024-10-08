[![tests](https://github.com/Emscherland/PHPIDS/actions/workflows/tests.yml/badge.svg)](https://github.com/Emscherland/PHPIDS/actions/workflows/tests.yml)
<a href="https://packagist.org/packages/emscherland/phpids"><img src="https://img.shields.io/packagist/v/emscherland/phpids" alt="Latest Stable Version"></a>
<a href=""><img alt="PHP Version" src="https://img.shields.io/packagist/dependency-v/emscherland/phpids/php?server=https%3A%2F%2Fpackagist.org&label=PHP"></a>
<a href="https://packagist.org/packages/emscherland/phpids"><img src="https://img.shields.io/packagist/l/emscherland/phpids" alt="License"></a>

# PHPIDS

PHPIDS (PHP-Intrusion Detection System) is a simple to use, well structured, fast and state-of-the-art
security layer for your PHP based web application. The IDS **neither strips, sanitizes nor filters any
malicious input**, it simply recognizes when an attacker tries to break your site and reacts in exactly
the way you want it to. Based on a set of approved and heavily tested filter rules any attack is given a
numerical impact rating which makes it easy to decide what kind of action should follow the hacking attempt.
This could range from simple logging to sending out an emergency mail to the development team, displaying
a warning message for the attacker or even ending the user’s session.

PHPIDS enables you to see who’s attacking your site and how and all without the tedious trawling of
logfiles or searching hacker forums for your domain. Last but not least it’s licensed under the fair LGPL!


## Installation

```bash
composer require emscherland/phpids
```

## Contributions

If you would like to contribute, please open a pull request. If you need something to do, have a look at our
[open issues](https://github.com/Emscherland/PHPIDS/issues).


## Credits

This Package is forked from the original PHPIDS that is no longer maintained and that is lacking support for modern PHP.

The Credits originally contained the following:

The project was started by Christian Matthies <ch0012@gmail.com> and Mario Heiderich <mario.heiderich@gmail.com>.
Mario spend a lot of time maintaining PHPIDS mostly on his own. Huge props for that. Currently Lars Strojny <lars@strojny.net>
merges pull requests.

### An incomplete list of contributors:

 - **LeverOne** for his outstanding work, testing and XSS vectors from the depths of markup hell
 - [Kishor](http://wasjournal.blogspot.com/) for providing cutting edge XSS and great help in the group
 - [Martin Hinks](http://www.the-mice.co.uk/switch/) for great hints, the .NETIDS and help with false positives
 - [SirDarckCat](http://sirdarckcat.blogspot.com) for providing XSS so advanced it made us shiver
 - [Gareth Heyes](http://thespanner.co.uk/) for his help enhancing the rules and very creative XSS vectors
 - **Kevin Schroeder** for the audit and great help on testing and enhancing the PHPIDS
 - **xorrer** for his help optimizing the rules against his cryptic and sophisticated XSS vectors
 - [Johannes Dahse](http://websec.wordpress.com/) for his help optimizing the SQLI rules
 - [Roberto Salgado](http://websec.ca/) for helping hardening the SQLI rules with his SQL-Fu
 - [tx](http://lowtechlive.com/) for even more outstanding SQLI stuff and almost magic PHP code injection vectors
 - [Giorgio Maone](http://hackademix.net/) for redefining the word JavaScript with his vectors
 - [thornmaker](http://p42.us/) for submitting smart and very hard to detect JavaScript concatenation vectors
 - [Martin Trauth](http://www.pix7.de/blog/) for helping us with the design and giving hints on estate usability
 - [Ronald v.d. Heetkamp](http://0x000000.com/) for helping on the SQLI and XSS detection issues
 - **Dan** for helping i18n-ing the PHPIDS
 - **CrYpTiC_MauleR** for providing great hints and XSS magic
 - [Robert Hansen](http://www.ha.ckers.org/) for providing (sl|h)a.ckers.org and the XSS cheat sheet
 - [beford](http://blog.beford.org/) for providing great hints and esoteric but working XSS

