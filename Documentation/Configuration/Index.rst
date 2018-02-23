.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _configuration:

Configuration Reference
=======================

.. _configuration-typoscript:

TypoScript Reference
--------------------

Prefix TypoScript view and settings with plugin.tx_jpfaq_faq.

Property details
^^^^^^^^^^^^^^^^

.. only:: html

	.. contents::
          	:local:
		:depth: 1


.. _view.templateRootPaths:

view.templateRootPaths
""""""""""""""""""""""
.. container:: table-row

   Property
         view.templateRootPaths
   Data type
         string
   Description
         The path to the template. templateRootPaths.0 points to the extension. Use templateRootPaths.1 to override.

.. _view.partialRootPaths:

view.partialRootPaths
"""""""""""""""""""""
.. container:: table-row

   Property
         view.partialRootPaths
   Data type
         string
   Description
         The path to the partial (the search form, which has some Bootstrap markup). partialRootPaths.0 points to the extension. Use partialRootPaths.1 to override.

.. _view.layoutRootPaths:

view.layoutRootPaths
""""""""""""""""""""
.. container:: table-row

   Property
         view.layoutRootPaths
   Data type
         string
   Description
         The path to the layoutRootPaths. layoutRootPaths.0 points to the extension. Use layoutRootPaths.1 to override.

.. _persistence.storagePid:

persistence.storagePid
""""""""""""""""""""""
.. container:: table-row

   Property
         persistence.storagePid
   Data type
         int
   Description
         Disabled, to be able to use the Record Storage Page at the plugin properties.

.. _settings.excludeAlreadyDisplayedQuestions:

settings.excludeAlreadyDisplayedQuestions
"""""""""""""""""""""""""""""""""""""""""
.. container:: table-row

   Property
         settings.excludeAlreadyDisplayedQuestions
   Data type
         int
   Description
         Exclude already displayed questions when multiple plugins are inserted on a page.

.. _settings.gtag.enable:

settings.gtag.enable
""""""""""""""""""""
.. container:: table-row

   Property
         settings.gtag.enable
   Data type
         int
   Description
         Enable Google Analytics Event tracking for helpful / unhelpfulresponse. First install gtag.js snippet https://developers.google.com/analytics/devguides/collection/gtagjs/

.. _settings.gtag.event:

settings.gtag.event
"""""""""""""""""""
.. container:: table-row

   Property
         settings.gtag.event
   Data type
         string
   Description
         Event for Gtag. Note that that the label will be the question title.

.. _settings.gtag.category:

settings.gtag.category
""""""""""""""""""""""
.. container:: table-row

   Property
         settings.gtag.category
   Data type
         string
   Description
         Category for Gtag.

.. _settings.gtag.valueHelpful:

settings.gtag.valueHelpful
""""""""""""""""""""""""""
.. container:: table-row

   Property
         settings.gtag.valueHelpful
   Data type
         string
   Description
         Value for Gtag when clicked helpful.

.. _settings.gtag.valueUnhelpful:

settings.gtag.valueUnhelpful
""""""""""""""""""""""""""""
.. container:: table-row

   Property
         settings.gtag.valueUnhelpful
   Data type
         string
   Description
         Value for Gtag when clicked unhelpful.

.. _settings.question.comment.email.enable:

settings.question.comment.email.enable
""""""""""""""""""""""""""""""""""""""
.. container:: table-row

   Property
         settings.question.comment.email.enable
   Data type
         int
   Description
         Send an email when visitors fill in the question comment form.

.. _settings.question.comment.email.subject:

settings.question.comment.email.subject
"""""""""""""""""""""""""""""""""""""""
.. container:: table-row

   Property
         settings.question.comment.email.subject
   Data type
         string
   Description
         Subject for question comment email.

.. _settings.question.comment.email.sender.name:

settings.question.comment.email.sender.name
"""""""""""""""""""""""""""""""""""""""""""
.. container:: table-row

   Property
         settings.question.comment.email.sender.name
   Data type
         string
   Description
         Sender name for question comment email.

.. _settings.question.comment.email.sender.email:

settings.question.comment.email.sender.email
""""""""""""""""""""""""""""""""""""""""""""
.. container:: table-row

   Property
         settings.question.comment.email.sender.email
   Data type
         string
   Description
         Sender email address for question comment email.

.. _settings.question.comment.email.receivers.email:

settings.question.comment.email.receivers.email
"""""""""""""""""""""""""""""""""""""""""""""""
.. container:: table-row

   Property
         settings.question.comment.email.receivers.email
   Data type
         string
   Description
         Receiver email address or comma seperated list of email addresses without spaces of receivers for question comment email.

.. _settings.question.comment.email.introText:

settings.question.comment.email.introText
"""""""""""""""""""""""""""""""""""""""""
.. container:: table-row

   Property
         settings.question.comment.email.introText
   Data type
         string
   Description
         Simple HTML introtext in the question comment email.

.. _settings.question.comment.email.closeText:

settings.question.comment.email.closeText
"""""""""""""""""""""""""""""""""""""""""
.. container:: table-row

   Property
         settings.question.comment.email.closeText
   Data type
         string
   Description
         Simple HTML text at the bottom of the question comment email.

.. _settings.category.comment.email.enable:

settings.category.comment.email.enable
""""""""""""""""""""""""""""""""""""""
.. container:: table-row

   Property
         settings.category.comment.email.enable
   Data type
         int
   Description
         Send an email when visitors fill in the 'categories' form (at the bottom) of the plugin.

.. _settings.category.comment.email.subject:

settings.category.comment.email.subject
"""""""""""""""""""""""""""""""""""""""
.. container:: table-row

   Property
         settings.category.comment.email.subject
   Data type
         string
   Description
         Subject for categories comment email.

.. _settings.category.comment.email.sender.name:

settings.category.comment.email.sender.name
"""""""""""""""""""""""""""""""""""""""""""
.. container:: table-row

   Property
         settings.category.comment.email.sender.name
   Data type
         string
   Description
         Sender name for categories comment email.

.. _settings.category.comment.email.sender.email:

settings.category.comment.email.sender.email
""""""""""""""""""""""""""""""""""""""""""""
.. container:: table-row

   Property
         settings.category.comment.email.sender.email
   Data type
         string
   Description
         Sender email address for categories comment email.

.. _settings.category.comment.email.receivers.email:

settings.category.comment.email.receivers.email
"""""""""""""""""""""""""""""""""""""""""""""""
.. container:: table-row

   Property
         settings.category.comment.email.receivers.email
   Data type
         string
   Description
         Receiver email address or comma seperated list of email addresses without spaces of receivers for categories comment email.

.. _settings.category.comment.email.introText:

settings.category.comment.email.introText
"""""""""""""""""""""""""""""""""""""""""
.. container:: table-row

   Property
         settings.category.comment.email.introText
   Data type
         string
   Description
         Simple HTML introtext in the categories comment email.

.. _settings.category.comment.email.closeText:

settings.category.comment.email.closeText
"""""""""""""""""""""""""""""""""""""""""
.. container:: table-row

   Property
         settings.category.comment.email.closeText
   Data type
         string
   Description
         Simple HTML text at the bottom of the categories comment email.

.. _page.includeJSFooter.tx_jpfaq_jquery:

page.includeJSFooter.tx_jpfaq_jquery
""""""""""""""""""""""""""""""""""""
.. container:: table-row

   Property
         page.includeJSFooter.tx_jpfaq_jquery
   Data type
         string
   Description
         The path to the jQuery lib. Unset if you already have your owen jQuery library.

.. _page.includeJSFooter.tx_jpfaq:

page.includeJSFooter.tx_jpfaq
"""""""""""""""""""""""""""""
.. container:: table-row

   Property
         page.includeJSFooter.tx_jpfaq
   Data type
         string
   Description
         The path to the jQuery for opening and closing questions and the search form. If this does not work, you need to recheck if jQuery is loaded before this. Also make sure to not double load jQuery lib.

.. _page.includeCSS.tx_jpfaq:

page.includeCSS.tx_jpfaq
""""""""""""""""""""""""
.. container:: table-row

   Property
         page.includeCSS.tx_jpfaq
   Data type
         string
   Description
         The path to some basic CSS.

.. _page.includeCSS.tx_jpfaq_fontAwesome:

page.includeCSS.tx_jpfaq_fontAwesome
""""""""""""""""""""""""""""""""""""
.. container:: table-row

   Property
         page.includeCSS.tx_jpfaq_fontAwesome
   Data type
         string
   Description
         The path to some FontAwesome CSS. Disable if you don't want to use it in the templates or if you already load this.