Fellowship One Guest Entry App
==============================

Enter first time guests into Fellowship One through your church website
-----------------------------------------------------------------------

This small application allows you to enter guests into your Fellowship One database via your church website.  A video demonstration showing how this works can be found here: [http://developer.fellowshipone.com] (http://developer.fellowshipone.com).  It utilizes the Fellowship One PHP Helper Class submitted by a member of our Developer Community.

Data validation should be added.

Password protection should also be added.  This could be done with inFellowship credentials.  See this github repository for an example on doing authentication with our API: [https://github.com/tracymazelin/F1SingleSignOn](https://github.com/tracymazelin/F1SingleSignOn).

One household is created with the creation of each person.  If additional members of the same household need to be added this script should be altered to write new individuals to the newly created householdID.

Requirements
-------------

An API Key.  Apply here: [https://developer.fellowshipone.com/index.php/key/](https://developer.fellowshipone.com/index.php/key/)

Portal User credentials linked to a person in your database.


