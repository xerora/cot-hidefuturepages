<?php
/* ====================
[BEGIN_SED_EXTPLUGIN]
Code=hidefuturepages
Name=Hide Future Pages
Description=Hides pages from feeds and lists until the date set to display
Date=2011-aug-9
Author=Xerora
Version=1.0
Copyright=
Notes=
SQL=
Auth_guests=R
Lock_guests=W12345A
Auth_members=R
Lock_members=W12345A
[END_SED_EXTPLUGIN]

[BEGIN_SED_EXTPLUGIN_CONFIG]
maxitemsperpage=01:select:5,10,15,20,25,30,35,40,45,50:10:Max number of items to display per page in the tool
pageexpireaction=02:select:Delete,Hide:Hide:Action to take when a page expires
deletepagerelated=03:select:Yes,No:No:Delete page related items ( comments, ratings, etc ) when a page expires ( if page expire is configured )
yearstillpageexpire=04:select:1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20:1:Default number of year until page expires
[END_SED_EXTPLUGIN_CONFIG]
==================== */
defined('SED_CODE') or die('Wrong URL');
