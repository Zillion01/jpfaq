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

A few TypoScript settings..

Property details
^^^^^^^^^^^^^^^^

.. only:: html

	.. contents::
          	:local:
		:depth: 1


.. _templateRootPaths:

templateRootPaths
"""""""""""""""""
.. container:: table-row

   Property
         templateRootPaths
   Data type
         string
   Description
         The path to the template. templateRootPaths.0 points to the extension. Use templateRootPaths.1 to override.

.. _partialRootPaths:

partialRootPaths
""""""""""""""""
.. container:: table-row

   Property
         partialRootPaths
   Data type
         string
   Description
         The path to the partial (the search form, which has some Bootstrap markup). partialRootPaths.0 points to the extension. Use partialRootPaths.1 to override.

.. _layoutRootPaths:

layoutRootPaths
"""""""""""""""
.. container:: table-row

   Property
         layoutRootPaths
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
