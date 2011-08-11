<?php
defined('SED_CODE') or die('Wrong URL');

$itemsperpageconfig = (int)$cfg['plugin']['hidefuturepages']['maxitemsperpage']; 
$itemsperpage = ($itemsperpageconfig>0) ? $itemsperpageconfig : 10;
define('HFP_TOOL_ITEMS_PER_PAGE', $itemsperpage);

function hfp_tool_action_showall() {
	global $t, $db_pages, $cfg, $usr;
	
	$d = 0;
	$itemcount = 0;
	$page = sed_import('page', 'G', 'INT');
	$page = (int)$page;
	$page = ($page!=0) ? $page-1 : 0;
	$orderby = sed_import('orderby', 'G', 'ALP');
	$sortby = strtoupper(sed_import('sortby', 'G', 'ALP'));
	$sortby_options = array('DESC', 'ASC');
	$orderby_options = array('id', 'begin', 'expire', 'title');	
	$sortby = (empty($sortby) || !in_array($sortby, $sortby_options)) ? "DESC" : $sortby;
	$orderby = (empty($orderby) || !in_array($orderby, $orderby_options)) ? "id" : $orderby;
	$orderby = "page_".$orderby;
	$realpage = $page+1;
	
	$limit = HFP_TOOL_ITEMS_PER_PAGE;
	$offset = ceil($page*$limit);
	$sql_total = sed_sql_query("SELECT COUNT(*) FROM $db_pages WHERE page_state='3'");
	$total_count = (int)sed_sql_result($sql_total, 0, "COUNT(*)");
	$sql = sed_sql_query("SELECT page_state, page_title, page_begin, page_expire, page_id FROM $db_pages ".
	"WHERE page_state='3' ORDER BY ".sed_sql_prep($orderby)." ".sed_sql_prep($sortby)." LIMIT ".(int)$offset.", ".(int)$limit);
	
	while($result = sed_sql_fetchassoc($sql)) {
		$t->assign(array(
			"ITEM_PAGE_ID" => (int)$result['page_id'],
			"ITEM_PAGE_TITLE" => htmlspecialchars($result['page_title']),
			"ITEM_PAGE_BEGIN" => @date($cfg['dateformat'], (int)$result['page_begin'] + $usr['timezone'] * 3600),
			"ITEM_PAGE_EXPIRE" => @date($cfg['dateformat'], (int)$result['page_expire'] + $usr['timezone'] * 3600),
			"ITEM_PAGE_EDIT_URL" => sed_url('page', "m=edit&id=".(int)$result['page_id']."&r=adm"),
			"ITEM_SORT_TITLE_ASC" => sed_url('admin', 'm=tools&p=hidefuturepages&orderby=title&sortby=asc&page='.$realpage),
			"ITEM_SORT_TITLE_DESC" => sed_url('admin', 'm=tools&p=hidefuturepages&orderby=title&sortby=desc&page='.$realpage),
			"ITEM_SORT_ID_ASC" => sed_url('admin', 'm=tools&p=hidefuturepages&orderby=id&sortby=asc&page='.$realpage),
			"ITEM_SORT_ID_DESC" => sed_url('admin', 'm=tools&p=hidefuturepages&orderby=id&sortby=desc&page='.$realpage),
			"ITEM_SORT_BEGIN_ASC" => sed_url('admin', 'm=tools&p=hidefuturepages&orderby=begin&sortby=asc&page='.$realpage),
			"ITEM_SORT_BEGIN_DESC" => sed_url('admin', 'm=tools&p=hidefuturepages&orderby=begin&sortby=desc&page='.$realpage),
			"ITEM_SORT_EXPIRE_ASC" => sed_url('admin', 'm=tools&p=hidefuturepages&orderby=expire&sortby=asc&page='.$realpage),
			"ITEM_SORT_EXPIRE_DESC" => sed_url('admin', 'm=tools&p=hidefuturepages&orderby=expire&sortby=desc&page='.$realpage),
			"ITEM_PAGE_ADD_TO_QUEUE_URL" => sed_url('admin', 'm=tools&p=hidefuturepages&action=add_to_queue&id='.(int)$result['page_id'])."&".sed_xg(),
			"ITEM_PAGE_SET_TO_DISPLAY_URL" => sed_url('admin', 'm=tools&p=hidefuturepages&action=set_to_display&id='.(int)$result['page_id'])."&".sed_xg(),
			"ITEM_PAGE_PAGINATION" => hfp_create_pagination($total_count, $page+1), 
		));
		$itemcount++;
		$t->parse("MAIN.ACTION_SHOWALL.NONEMPTY_LIST.ITEM_LIST");
	}
	$t->assign(array(
		"TOOL_SHOW_COUNT" => $total_count,
		"TOOL_SHOW_PAGELIMIT" => $itemcount,
	));
	if($total_count==0) {
		$t->parse("MAIN.ACTION_SHOWALL.EMPTY_LIST");
	}
	else {
		$t->parse("MAIN.ACTION_SHOWALL.NONEMPTY_LIST");
	}
	$t->parse("MAIN.ACTION_SHOWALL");
}

function hfp_tool_action_add_to_queue($id) {
	global $db_pages, $sys, $db_structure;
	sed_check_xg();
	$id = (int)$id;
	if($id>0) {
		$sql = sed_sql_query("SELECT page_cat FROM $db_pages WHERE page_id='".$id."'");
		if($result = sed_sql_fetchassoc($sql)) {
			sed_sql_query("UPDATE $db_pages SET page_state='1' WHERE page_id='".$id."'");
			sed_sql_query("UPDATE $db_structure SET structure_pagecount=structure_pagecount-1 WHERE structure_code='".sed_sql_prep($result['page_cat'])."'");
		}
	}
	sed_redirect(sed_url('admin', 'm=tools&p=hidefuturepages', NULL, TRUE));
}

function hfp_tool_action_set_to_display($id) {
	global $db_pages, $sys, $db_structure;
	sed_check_xg();
	$id = (int)$id;
	if($id>0) {
		$sql = sed_sql_query("SELECT page_cat FROM $db_pages WHERE page_id='".$id."'");
		if($result = sed_sql_fetchassoc($sql)) {
			sed_sql_query("UPDATE $db_pages SET page_begin='".(int)$sys['now_offset']."', page_state='0' WHERE page_id='$id'");
			sed_sql_query("UPDATE $db_structure SET structure_pagecount=structure_pagecount+1 WHERE structure_code='".sed_sql_prep($result['page_cat'])."'");
		}
	}
	sed_redirect(sed_url('admin', 'm=tools&p=hidefuturepages', NULL, TRUE));
}

function hfp_create_pagination($total, $page) {
	global $cfg;
	$limit = HFP_TOOL_ITEMS_PER_PAGE;
	$offset_page = $page-1;
	$total = ($total!=0) ? ceil($total/$limit) : 0;
	
	if($offset_page!=0) {
		$prev_page = $page-1;
		$output = '<a style="text-decoration: underline;" href="'.sed_url('admin', 'm=tools&p=hidefuturepages&page='.$prev_page).'"><img class="hfp_icon_16" src="'.$cfg['plugins_dir'].'/hidefuturepages/img/arrow-left.png" /></a> &nbsp; ';
	}
	for($i=0; $total>$i; $i++) {
		$page_out = $i+1;
		if($page_out!=$page) {
			$output .= '<a style="text-decoration: underline;" href="'.sed_url('admin', 'm=tools&p=hidefuturepages&page='.$page_out).'">'.$page_out.'</a> ';
		}
		else {
			$output .= '<b>'.$page_out.'</b> ';
		}
		if($page_out!=$total) {
			$output .= '&nbsp;';
		}
	}
	

	if((int)$page!=(int)$total) {
		$next_page = $page+1;
		$output .= ' &nbsp; <a style="text-decoration: underline;" href="'.sed_url('admin', 'm=tools&p=hidefuturepages&page='.$next_page).'"><img class="hfp_icon_16" src="'.$cfg['plugins_dir'].'/hidefuturepages/img/arrow-right.png" /></a> ';
	}
	return $output;
}