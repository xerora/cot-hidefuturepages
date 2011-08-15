<?php
/* ====================
[BEGIN_SED_EXTPLUGIN]
Code=hidefuturepages
Part=page
File=hidefuturepages.page.edit.update.done
Hooks=page.edit.update.done
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */
defined('SED_CODE') or die('Wrong URL');

if((int)$rpagebegin>(int)$sys['now_offset']) {
	$id = (int)$id;
	sed_sql_query("UPDATE $db_pages SET page_state='3' WHERE page_id='$id'");
	sed_log("Edited page #".$id,'adm');
	sed_redirect(sed_url('admin', 'm=page&s=queue', NULL, TRUE));
}
