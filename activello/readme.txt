=== Activello ===
Contributors: colorlib, silkalns
Tags: blog, e-commerce, custom-background, custom-colors, custom-logo, custom-menu, editor-style, featured-images, full-width-template, left-sidebar, right-sidebar, sticky-post, theme-options, threaded-comments, translation-ready, block-styles, wide-blocks
Requires at least: 6.4
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A clean, minimal blog theme with a full-width featured slider, four layouts and WooCommerce support.

== Description ==

Activello is a clean and minimal WordPress blog theme with a premium look and feel, well suited for food, fashion, travel, lifestyle and any other beautiful blog. It ships a full-width featured slider, a widgetized sidebar, four layout options you can set globally or per post, and Customizer options for colours, header and footer with live preview. The front end is responsive and built on Bootstrap 3, the theme's own JavaScript runs without jQuery, and the colours you choose carry into the block editor. Activello is WooCommerce ready, translation ready and comes with over twenty bundled translations.

Documentation: https://colorlib.com/wp/support/activello/

Support forum: https://colorlib.com/wp/forums/forum/activello/

== Installation ==

1. In your admin panel, go to Appearance > Themes and click Add New Theme.
2. Search for "Activello", then click Install and Activate.
3. Open Appearance > About Activello for the getting-started steps, and Appearance > Customize > Activello Options to set the slider, layout and colours.

== Frequently Asked Questions ==

= How do I turn on the featured slider? =

Go to Appearance > Customize > Activello Options > Slider Options and switch on Show Slider. It shows posts with a featured image, optionally from one category, on the front page and the blog page.

= How do I show social icons? =

Create a menu with links to your profiles (Twitter/X, Facebook, Instagram, GitHub, Bluesky, Mastodon and more are recognised) and assign it to the Social Menu location. The icons appear in the footer and in the Activello Social widget.

= How do I change the layout of one post or page? =

Use the layout box in the post editor's sidebar. It overrides the default set under Activello Options > Layout Options.

== Copyright ==

Activello WordPress Theme, Copyright 2015-2026 Colorlib
Activello is distributed under the terms of the GNU GPL v2 or later.

Activello is based on Underscores https://underscores.me/, (C) 2012-2026 Automattic, Inc.
Underscores is distributed under the terms of the GNU GPL v2 or later.

This theme bundles the following third-party resources:

Bootstrap 3.4.1, Copyright 2011-2019 Twitter, Inc.
License: MIT
Source: https://getbootstrap.com/
The bundled JavaScript carries a small patch for jQuery 4 compatibility, noted in its header.

GLYPHICONS Halflings (bundled with Bootstrap 3)
License: MIT, as part of Bootstrap
Source: https://glyphicons.com/

Font Awesome Free 7.3.1, Copyright Fonticons, Inc.
License: Icons CC BY 4.0, Fonts SIL OFL 1.1, Code MIT
Source: https://fontawesome.com/

FlexSlider 2.7.0, Copyright WooThemes
License: GPLv2 or later
Source: https://github.com/woocommerce/FlexSlider

WP Bootstrap Navwalker, Edward McIntyre
License: GPLv2 or later
Source: https://github.com/wp-bootstrap/wp-bootstrap-navwalker

Lora, Copyright The Lora Project Authors
License: SIL Open Font License 1.1
Source: https://fonts.google.com/specimen/Lora

Montserrat, Copyright The Montserrat Project Authors
License: SIL Open Font License 1.1
Source: https://fonts.google.com/specimen/Montserrat

Maven Pro, Copyright The Maven Pro Project Authors
License: SIL Open Font License 1.1
Source: https://fonts.google.com/specimen/Maven+Pro

Unless otherwise specified, all other theme files, including the screenshot and the welcome screen logo, are created by Colorlib and licensed under the GPLv2 or later.

== Changelog ==

= 1.7.0 =
* Requires WordPress 6.4 and PHP 7.4; tested up to WordPress 7.1 and PHP 8.5
* Fixed: social menu icons rendered as empty boxes since the Font Awesome 7 update; every network now shows its brand icon (X, Bluesky, Mastodon, Threads, TikTok and more are recognised) and each link has an accessible name
* Fixed: the mobile menu button, and every plugin using the theme's Bootstrap, broke on jQuery 4 (WordPress without jQuery Migrate)
* Fixed: menu items set to open in a new tab opened two tabs
* Fixed: on pages with a sidebar the footer was rendered outside the page wrapper
* Fixed: a post's own layout was ignored by the body classes; layout logic now has a single source
* Fixed: the password form lost core's "Invalid password" message and redirect
* Fixed: a JavaScript error on every in-page link (#comments, #respond)
* Fixed: theme widgets: unlinked form labels, unescaped titles, Recent Posts skipping posts without text, Categories mangling names with brackets
* Fixed: recommended plugins with a non-standard main file were offered for installation while already installed
* Fixed: the Copyright Text field showed empty while the footer printed "Activello"; it now also accepts links
* Fixed: duplicate ids on search forms and social menus; search and comment fields have labels; email and website fields use the right input types
* New: theme colours come from a theme.json palette, so a custom accent colour also applies in the block editor and its colour pickers
* New: the block editor is sized like the column the post renders in, and uses the theme's fonts
* New: block patterns (About the author, Latest posts grid, Call to action) and a "Blocks (full width, no title)" page template
* Accessibility: skip link, real buttons for the sub-menu toggles and back to top, one h1 per view, named landmarks, WCAG AA text contrast (the default accent is a slightly deeper purple)
* Tooling: npm/Composer lint, build and i18n scripts, PHPCS (WordPress-Extra), ESLint and Stylelint at zero warnings, GitHub Actions CI
* readme.txt now follows the WordPress.org readme format and lists every bundled resource and its licence

= 1.6.2 =
* Removed KB Support from the recommended plugins. WordPress.org closed it on 2025-04-03 over a security issue
* Corrected the capitalisation of WordPress in the French and Romanian translation files

= 1.6.1 =
* The no-js class swap is now printed from a wp_head hook (priority 0, still ahead of the stylesheets) instead of being hardcoded in header.php, so child themes and plugins can remove it
* Footer credit links use https

= 1.6.0 =
* Removed the Epsilon framework: the Customizer toggles are now a small theme-owned control with identical appearance, and saved settings are untouched (Epsilon's upstream repository no longer exists, which also made fresh git clones of this theme unusable)
* The repository no longer uses git submodules -- cloning or downloading from GitHub now just works
* Rebuilt the About Activello screen on core admin markup; the Recommended Plugins tab now renders WordPress' own plugin cards with details modals
* Removed the never-satisfiable "required actions" importer nag and its notification system
* Regenerated the translation template (181 strings, zero extraction warnings) and refreshed all 26 bundled translations
* Passed Theme Check: development files no longer ship with the theme, the 1.1 MB screenshot is now an optimized 318 KB JPG and the welcome logo shrank from 778 KB to 11 KB
* Verified WooCommerce shop, product, cart, checkout and my-account templates on WordPress 7.0 / PHP 8.5
* Removed the obsolete Grunt/Travis toolchain
* Corrected the style.css theme headers for the WordPress.org directory: added the missing "Requires at least" header, removed tags the theme cannot back up (rtl-language-support without an rtl.css, footer-widgets with no footer widget areas, accessibility-ready), added the e-commerce tag, refreshed the description and aligned the licence statement (GPL v2 or later) across style.css and the readme

= 1.5.0 =
* Security: added nonce and capability checks to the welcome-screen AJAX handlers, which previously ran without either
* Security: removed unused plugin activate/deactivate handlers that ran on admin_init, and moved the demo front-page setter onto a proper authenticated AJAX action
* Fixed the Bootstrap version mix: the theme now ships genuine Bootstrap 3.4.1 CSS and JS (includes the CVE-2019-8331 fix) instead of 3.3.7 CSS with 4.x JS and a hand-patched mobile menu
* Fixed the front-page slider failing to initialise when jQuery Migrate is disabled; updated FlexSlider from 2.6.3 to 2.7.0
* Rewrote theme JavaScript in plain DOM APIs -- theme scripts no longer depend on jQuery, load in the footer, and honour prefers-reduced-motion
* Removed Modernizr and the legacy IE conditional-comment markup
* Escaped remaining output across templates, widgets and admin screens; colours are re-validated at output time
* Fixed PHP 8.5 deprecations and the WordPress 6.7+ early-translation notice; verified with zero notices on WordPress 7.0 / PHP 8.5
* Added block editor support: wp-block-styles, align-wide, responsive-embeds and an editor stylesheet matching the front end
* All theme assets are now versioned from the theme version for reliable cache busting

= 1.4.9 =
* Fixed Epsilon_Section_Recommended_Actions class loading in customizer
* Added missing required header information in style.css
* Fixed CSS syntax error in style.css
* Updated theme version and compatibility information
* Improved theme stability and performance

= 1.4.8 =
* Fixed translation loading issue by properly initializing translations after WordPress init
* Moved welcome screen setup to load after init hook
* Fixed Epsilon_Control_Toggle class loading in customizer
* Improved theme compatibility with WordPress 6.8
* Added support for block styles and wide blocks
* Enhanced accessibility features
* Updated theme tags to reflect new features

= 1.4.7 =
* Fixed customizer controls
* Improved theme compatibility with WordPress 6.7
* Enhanced security features
* Updated theme dependencies

= 1.4.6 =
* Fixed responsive issues
* Improved theme compatibility with WordPress 6.6
* Enhanced performance
* Updated theme dependencies

= 1.4.5 = 
* Improved Escaping

= 1.4.4 = 
* Improved Escaping

= 1.4.3 =
* Compatibility with jQuery 3.0

= 1.4.2 =
* Sanitization fix

= 1.4.1 =
* Security Fix

= 1.4.0 =
* Improved accesibility with keyboard navigation
* Updated list of recommended plugins

= 1.3.8 =
* Removed subject tags, only kept 3

= 1.3.4
* Structured data missing hatom author
* Allow theme to display more than 2 sub level menus

= 1.3.3
* Fixed search functionality

= 1.3.2
* Added a new Blog Layout
* Added option to show all categories in the blog page
* Fixed mobile menu
* Integrated with Travis
* Added a notice inside admin dashboard so users know they need to regenerate thumbnails

= 1.3.0 - 16.05.2017

* Fixed slider & JetPack Photon integration
* Added Epsilon Framework as a git sub-module
* Fixed image serving - we were serving larger images than necessary

= 1.2.0 - 08.03.2017

* Added Welcome Screen
* Added Customizer Documentation Section
* Fixed search menu css
* Added for customizer colors defaults
* Fixed Social Widget title bug
* Fixed responsive menu css
* Added logo max height
* Added native WordPress Additional CSS section
* Activello functions now are pluggable.
* Fixed Woocommerce related tab issue
* Added dates for comments
* Fixed WordPress gallery css issue
* Fixed categories html bug

= 1.1.0 - 11.10.2016 =

* Updated Bootstrap to 3.3.7
* Updated Font Awesome to 4.6.3
* Updated FlexSlider to 2.6.3

= 1.1.0 - 11.10.2016 =

* Added Link to documentation
* Fixed compatibility errors with the WP 4.6 and PHP 7.
* Now you can use unlimited number of slides on Slider
* Other minor code tweaks and improvements
* Added French translation thanks to Eddy Lelièvre-Berna
* Added Greek translation thanks to Tsakman
* Added Slovak translation thanks to Marek

= 1.0.3 - 28.06.2016 =

* Added TGMPA & Kiwi Social Share Plugin
* Updated theme tags

= 1.0.2 - 17.02.2016 =

* Prefixed functions
* Added missing translations
* Escaped translation strings
* Updated libraries
* Added missing untouched libraries and scripts
* Added licensing information
* Theme Documentation now available on https://colorlib.com/wp/support/activello

= 1.0.1 - 11.02.2016 =

* Removed Instagram widget which where no longer in use.
* Improved theme translation

= 1.0 - 06.11.2015 =

* Initial release
