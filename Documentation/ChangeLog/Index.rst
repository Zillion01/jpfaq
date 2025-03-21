.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _changelog:

ChangeLog
=========
13.4.2
"""""
#. Bug fix. Added setSchemaOrgOnHtmlTagPages constant see documentation. This allows to choose the page uid's where the Schema.org/FAQPage is set as an HTML attribute. This was a bug because it set on all pages before the HTML tag. It is not ideal, but unfortunately there is no easy way to set the html tag attributes based on the content of the page.
#. Fixed casing of the word fontAwesome in typoscript, verify your settings!
#. Update jQuery to 3.7.1

13.4.1
"""""
#. For TYPO3 v13. Removed unfinished upgrade wizard. Since the flexform is changed, I would suggest reinserting the plugin as a content element manually and saving the flexform again.

13.4.0
"""""
#. For TYPO3 v13. Notice that with the changes needed for TYPO3 v13, the plugin needs to be reinserted as a content element and the flexform needs to be saved again.

12.5.0
"""""
#. Use null coalescence for undefined array indices, for if not all settings are set. (Michiel Roos)
#. Improved bot detection for helpfullness and comment actions

12.4.2
"""""
#. Small bugfix in isBot function

12.4.1
"""""
#. Prevent well known bots to click on helpfullness actions

12.4.0
"""""
#. For TYPO3 v12 (also thx to Christian-Hackl)
#. Improved Schema.org validation
#. Improved inclusion of JS and CSS
#. Answer field is no longer required
#. jQuery and FontAwesome now locally loaded

11.6.2
"""""
#. Possible fix for bug excludeAlreadyDisplayedQuestions #43

11.6.1
"""""
#. PHP 7.4 compatibility #41 (thx to Tobias Bünter)

11.6.0
"""""
#. Constants for libs, CSS
#. Comment settings now available in the flexform
#. Empty flexform fields for the comments will default to the typoscript settings
#. New feature, confirmation email to the commenter
#. Additional security in the form handling
#. Fixed a bug with excludeAlreadyDisplayedQuestions
#. excludeAlreadyDisplayedQuestions is moved to the flexform
#. Sponsored by h_da - Hochschule Darmstadt

11.5.1
""""""
#. Condition uses `traverse()` to avoid errors
#. Replace old import with `@import` syntax (thx toMathias Bolt Lesniak)

11.5.0
""""""
Update to TYPO3 11 LTS

10.4.1
""""""
#. TCA fixes for TYPO3 10.4. (thx to Sven Wappler)
#. Add extension key to composer.json (thx to Klaus Hörmann-Engl)

10.4.0
""""""
For TYPO3 CMS versions 9.5 and 10.4. (thx to Cristian Fries)

9.5.3
"""""
Prevent search engine indexing of helpful links and comment form (thx to Nemo64)

9.5.2
"""""
Some code improvements, added settings for manual

9.5.1
"""""
Added to packagist, updated schema.org (thx to Cristian Fries and Starkmann)

9.5.0
"""""
For TYPO3 9.5

8.8.2
"""""
#. Make compatible with Linux case-sensitivity and MySQL Strict Mode (thx to Ferdinand Kasper)
#. Improved German translation (thx to Ferdinand Kasper)

8.8.1
"""""
Bugfix TCA sorting of comments. This produced an error when copying a page with jpFAQ. (Thx to Michael Ecker).

8.8.0
"""""
#. Added optional helpfulness with optional Google Analytics event tracking under each question with a comment form
#. Added optional commentform under each plugin
#. Commentforms are stored in the database and can be emailed
#. TYPO3 7.6 and 8.7 compatibility
#. Exclude already displayed questions
#. Thanks to Fenster24.de for sponsoring

8.7.1
"""""
Added default constants for paths and prevent submit search form

8.7.0
"""""
For TYPO3 8.7

8.5.2
"""""
Fixed mm relations question - category

8.5.1
"""""
Fixed typos

8.5.0
"""""
Changed requirements to TYPO3 version <= 8.5.99. Changed version number to max. supported TYPO3 version. Added tt_content IRRE field for custom content at answers.

8.4.0
"""""
Changed requirements to TYPO3 version <= 8.4.99. Changed version number to max. supported TYPO3 version.

4.0.4
"""""
Moved globals and plugin registering to overrides tt_content.php for caching.

4.0.3
"""""
Small bugfix ext_emconf.php

4.0.2
"""""
Fix for documentation

4.0.1
"""""
Fix for documentation

4.0.0
"""""
TYPO3 7.6+ compatibility. Totally rewritten.

3.0.0
"""""
TYPO3 6.2 compatibility. Do not use for older TYPO3 versions.
Removed support for t3jQuery

2.1.5
"""""
Re-added dependencies.
TYPO3 6.0 compatibility: changed name of default.hml to Default.hml

2.1.4
"""""
Changed $ in jQuery in QuestionController.php and quicksearch.js to avoid javascript conflicts.

2.1.3
"""""
Fixed a bug which inserted an empty js file when using own jQuery library. Thx to André Spindler.

2.1.2
"""""
TYPO3 4.7 compatibility: replaced deprecated f:form.textbox for f:form:textfield in Fluid templates. Thx to Tim Hengeveld.

2.1.1
"""""
Fixed rendering of richtext answers in template. Paragraph tags were not parsed.

2.1.0
"""""
Improved toggling further. If all answers of a category are unfolded, the link “Hide all Answers” will be shown, else “Show all Answers”. (Also thanks to Pascal Uhlmann)

2.0.2
"""""
Added dependencies for Extbase and Fluid. Some people seem to install jpFAQ without Extbase and get problems.

2.0.1
"""""
Fixed a bug which prevented realurl links in answers. (Thanks to Klaus Hörmann)

2.0.0
"""""
Added quicksearch
All javascript to footer
Fixed toggling all answers

1.1.2
"""""
Re-uploaded, cause of error on uploading in repository of 1.1.1

1.1.1
"""""
Added French translation
Added Russian translation (Thanks to Antony A. Antonenko)

1.1.0
"""""
Added German translation
Setting status to stable

1.0.0
"""""
Initial release

