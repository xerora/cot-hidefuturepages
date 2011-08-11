
What this plugin does:
-----------------------------------

1. Hides pages that are set to display in the future from lists and feeds. Normally, pages set for a future date
are still displayed in lists and feeds and will just display how long until the page will display.
2. (optional, see below) Allows pages to expire on the date set
3. Ability to ajust the default number of years until a page expires. 
4. Provides an administration tool to manage hidden future pages; Administration -> Tools -> Hide Future Pages


Installation
-----------------------------------

Standard plugin installation:

Backup your database as a precaution.

1. Download, unpack and upload the hidefuturepages plugin folder to your plugin directory.
2. Install plugin in administration panel.
3. Check the plugin's configurations in the plugin administration to make sure everything is set to your
preference.


How to allow pages to expire:
-----------------------------------

**Warning:** Allowing pages to expire while having pages in a one year or older seditio/cotonti installation 
may cause those pages to be deleted. You should backup your database before enabling this feature.

You must add the following line anywhere in your datas/config.php to allow pages to expire:

``$cfg['allowpageexpire'] = TRUE;``