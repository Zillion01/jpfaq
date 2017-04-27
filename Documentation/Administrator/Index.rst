.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _admin-manual:

Administrator Manual
====================

#. Remove jQuery in typoscript if you have your own library included.
#. In the page properties of the records folder, under tab 'Behaviour' select 'jpFAQ' at 'Contains Plugin', this gives an own folder icon in the page tree and loads some tsconfig, like hiding tt_content records.
#. In the page properties of the folder, under tab 'Resources' enter 'TCEMAIN.clearCacheCmd = 12,45,78'. Where the numbers represent the page id's of the page(s) with a jpFAQ plugin from which you want the cache get auto cleared when adding or editing FAQ records (optional)
#. If you like to restrict at the answers the additional IRRE tt_content types (for example allow only textmedia), check out typo3conf/ext/jpfaq/Configuration/TypoScript/TSconfig/Page/pageTSconfig.ts


.. _admin-installation:

Installation
------------

To install the extension, perform the following steps:

#. Go to the Extension Manager
#. Install the extension
#. Include static template jpFAQ
#. Then... see Users manual

