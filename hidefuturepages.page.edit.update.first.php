<?php 
/* ====================
[BEGIN_SED_EXTPLUGIN]
Code=hidefuturepages
Part=page
File=hidefuturepages.page.edit.update.first
Hooks=page.edit.update.first
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */
defined('SED_CODE') or die('Wrong URL');

$sql_pagestate = sed_sql_query("SELECT page_state FROM $db_pages WHERE page_id='$id'");
$result_currentpagestate = sed_sql_fetchassoc($sql_pagestate);

$currentpagestate = $result_currentpagestate['page_state'];