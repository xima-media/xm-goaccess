.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. _DidYouMean:

Did you mean ... ?
==================

You can activate the feature "Did you mean?" in the extension configuration of ``ke_search_premium``
in the "BasicSettings" section.

When the "Did you mean?"-feature is activated and a search query does not give any
results, instead of the "no results" message either the correctly spelled word is displayed
or a similar word. After klicking on that word, a new search will be executed.

This feature uses the open databese of `Openthesaurus <http://www.openthesaurus.de>`_,
talking to it through the `Openthesaurus API <http://www.openthesaurus.de/about/api>`_.

Since Openthesaurus.de is German only, this feature makes only sense on german websites.

*IMPORTANT*
  You have to active "Show individual text if no results were found?" in the ke_search plugin additionally to the activation here in the extension manager.


Configuration
-------------

=========== ======= ================================================================================================================================================================================================================================================================================================================================================ ==============================================
Value       Type    Description                                                                                                                                                                                                                                                                                                                                      Default
=========== ======= ================================================================================================================================================================================================================================================================================================================================================ ==============================================
LinkForXml  Wrap    This is the wrapping link which calls the www.openthesaurus.de API to get the result in XML format                                                                                                                                                                                                                                               http://www.openthesaurus.de/synonyme/search?q=
LinkForJson Wrap    This is the wrapping link which calls the www.openthesaurus.de API to get the result in JSON format (BETA).                                                                                                                                                                                                                                      http://www.openthesaurus.de/synonyme/search?q=
ApiFormat   Option  With help of this selectbox you can decide if the results of OpenThesaurus should be received as XML or JSON. Be careful: JSON is in BETA status. But on our test systems we havn't found any problem. So it's at you to use JSON or not.                                                                                                        Xml
MaxDistance Option  The lower this setting is as more equal is the new word to the given search word. A distance of 1 will find "auto" for "autho" but not "paaren" for "parken". In this case you have to set max distance to 2. It seems that the max distance of OpenThesaurus API is 3. So it can be that we will reduce the distance selectbox to 3 in future   2
=========== ======= ================================================================================================================================================================================================================================================================================================================================================ ==============================================
