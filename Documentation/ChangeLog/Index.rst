.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _changelog:

ChangeLog
=========
4.0.4
#. Moved Globals and plugin registering to overrides tt_content.php for caching.

4.0.3
#. Small bugfix ext_emconf.php 

4.0.2
#. Fix for documentation

4.0.1
#. Fix for documentation

4.0.0
#. TYPO3 7.6+ compatibility. Totally rewritten.

3.0.0
#. TYPO3 6.2 compatibility. Do not use for older TYPO3 versions.
#. Removed support for t3jQuery

2.1.5
#. Re-added dependencies.
#. TYPO3 6.0 compatibility: changed name of default.hml to Default.hml

2.1.4
#. Changed $ in jQuery in QuestionController.php and quicksearch.js to avoid javascript conflicts.

2.1.3
#. Fixed a bug which inserted an empty js file when using own jQuery library. Thx to André Spindler.

2.1.2
#. TYPO3 4.7 compatibility: replaced deprecated f:form.textbox for f:form:textfield in Fluid templates. Thx to Tim Hengeveld.

2.1.1
#. Fixed rendering of richtext answers in template. Paragraph tags were not parsed.

2.1.0
#. Improved toggling further. If all answers of a category are unfolded, the link “Hide all Answers” will be shown, else “Show all Answers”. (Also thanks to Pascal Uhlmann)

2.0.2
#. Added dependencies for Extbase and Fluid. Some people seem to install jpFAQ without Extbase and get problems.

2.0.1
#. Fixed a bug which prevented realurl links in answers. (Thanks to Klaus Hörmann)

2.0.0
#. Added quicksearch
#. All javascript to footer
#. Fixed toggling all answers

1.1.2
#. Re-uploaded, cause of error on uploading in repository of 1.1.1

1.1.1
#. Added French translation
#. Added Russian translation (Thanks to Antony A. Antonenko)

1.1.0
#. Added German translation
#. Setting status to stable

1.0.0
#. Initial release

