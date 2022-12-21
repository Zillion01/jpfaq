.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _admin-manual:

Administrator Manual
====================

#. Remove jQuery and FontAwesome in typoscript if you have your own libraries included.
#. In the page properties of the records folder, under tab 'Behaviour' select 'jpFAQ' at 'Contains Plugin', this gives an own folder icon in the page tree and loads some tsconfig, like hiding tt_content records.
#. In the page properties of the folder, under tab 'Resources' enter 'TCEMAIN.clearCacheCmd = 12,45,78'. Where the numbers represent the page id's of the page(s) with a jpFAQ plugin from which you want the cache get auto cleared when adding or editing FAQ records (optional)
#. If you like to restrict at the answers the additional IRRE tt_content types (for example allow only textmedia), check out Configuration/TsConfig/Page/pageTSconfig.tsconfig
#. Check out Configuration/TypoScript/setup.typoscript for settings to exclude already displayed questions, configuring comment emails and Google Analytics tracking
#. In the extension manager you can go to 'Configure' at jpFAQ to anonymize comment IP addresses. This is needed in for example Germany.
#. Required fields the comment forms (there is a simple JS frontend validation), are determined by the class jpfaqRequired in the FormFields.html files.


.. _admin-installation:

Installation
------------

To install the extension, perform the following steps:

#. Go to the Extension Manager
#. Install the extension
#. Include static template jpFAQ
#. Then... see Users manual

Since 9.5.1. the extension can be installed via composer require jvdp/jpfaq

