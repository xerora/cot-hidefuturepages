<?php
/* ====================
[BEGIN_SED_EXTPLUGIN]
Code=hidefuturepages
Part=admin
File=hidefuturepages.admin.page.queue.validate
Hooks=admin.page.queue.validate
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */
defined('SED_CODE') or die('Wrong URL');

$sql_select_page = sed_sql_query("SELECT page_state, page_id, page_cat, page_begin FROM $db_pages WHERE page_id='$id' LIMIT 1");
$result = sed_sql_fetchassoc($sql_select_page);

if((int)$result['page_begin']>(int)$sys['now_offset'] && (int)$result['page_state']==1 ) { 
	sed_block(sed_auth('page', $result['page_cat'], 'A'));
	$id = (int)$id;
	$sql = sed_sql_query("UPDATE $db_pages SET page_state='3' WHERE page_id='$id'");
	sed_cache_clear('latestpages');

	$adminwarnings = '#'.$id.' - '.$L['adm_queue_validated'];
	sed_redirect(sed_url('admin', 'm=page&s=queue', NULL, TRUE));
}