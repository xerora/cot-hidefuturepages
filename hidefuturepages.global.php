<?php
/* ====================
[BEGIN_SED_EXTPLUGIN]
Code=hidefuturepages
Part=global
File=hidefuturepages.global
Hooks=global
Tags=
Order=1
[END_SED_EXTPLUGIN]
==================== */
defined('SED_CODE') or die('Wrong URL');

sed_sql_query("UPDATE $db_pages SET page_state='0' WHERE page_state='3' AND page_begin<=".(int)$sys['now_offset']);
if(isset($cfg['allowpageexpire']) && $cfg['allowpageexpire']) {
	$expirepages_count = 0;
	$pageexpireaction = trim(strtolower($cfg['plugin']['hidefuturepages']['pageexpireaction']));
	if($cfg['trash_page']) {
		$sql_expiredpages = sed_sql_query("SELECT * FROM $db_pages WHERE page_expire<".(int)$sys['now_offset']." && page_state!='4'");
	}
	else {
		$sql_expirepages = sed_sql_query("SELECT page_id, page_cat FROM $db_pages WHERE page_expire<".(int)$sys['now_offset']." && page_state!='4'");
	}
	while($expirepages_result = sed_sql_fetchassoc($sql_expiredpages)) {
		$pageid = (int)$expirepages_result['page_id'];
		switch($pageexpireaction) { 
			case 'delete':
				if($cfg['trash_page']) {
					$newtempexpiredate = $sys['now_offset']+(31556926*7);
					$expirepages_result['page_expire'] = (int)$newtempexpiredate;
					$expirepages_result['page_state'] = 1;
					$expirepages_result['page_comcount'] = 0;
					sed_trash_put('page', $expirepages_result['page_title'], $expirepages_result['page_id'], $expirepages_result);
				}
				$pagedeletedstatus = sed_sql_query("DELETE FROM $db_pages WHERE page_id='".(int)$expirepages_result['page_id']."'");
				sed_log("Deleted page #".(int)$expirepages_result['page_id'],'adm');
				sed_sql_query("UPDATE $db_structure SET structure_pagecount=structure_pagecount-1 WHERE structure_code='".sed_sql_prep($expirepages_result['page_cat'])."'");
	
				if($cfg['plugin']['hidefuturepages']['deletepagerelated']=='Yes') {
					$pagecode = "p".$pageid;
					sed_sql_query("DELETE FROM $db_ratings WHERE rating_code='$pagecode'");
					sed_sql_query("DELETE FROM $db_rated WHERE rated_code='$pagecode'");
					sed_sql_query("DELETE FROM $db_com WHERE com_code='$pagecode'");
				}					
			break;
			case 'hide':
				sed_sql_query("UPDATE $db_pages SET page_state='4' WHERE page_id='$pageid'");
			break;
		}		
		if($pagedeletedstatus) {
			$expirepages_count++;
		}
	}
	if($expirepages_count>0 && $cfg['trash_page']) {
		sed_log($expirepages_count." page(s) had expired and were put in the trash", 'adm');
	}
	elseif($expirepages_count>0) {
		sed_log($expirepages_count." page(s) had expired and were deleted", 'adm');
	}
}
