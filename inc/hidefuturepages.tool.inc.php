<?php
defined('SED_CODE') or die('Wrong URL');
error_reporting(E_ALL ^E_NOTICE);
$itemsperpageconfig = (int)$cfg['plugin']['hidefuturepages']['maxitemsperpage']; 
$itemsperpage = ($itemsperpageconfig>0) ? $itemsperpageconfig : 10;
define('HFP_TOOL_ITEMS_PER_PAGE', $itemsperpage);

function hfp_tool_action_showall() {
	global $t, $db_pages, $cfg, $usr;

	$page = sed_import('page', 'G', 'INT', 2);
	$sortby = strtoupper(sed_import('sortby', 'G', 'ALP', 4));
	$orderby = sed_import('orderby', 'G', 'ALP', 4);	
	$state = sed_import('state', 'G', 'INT', 1);

	$d = 0;
	$itemcount = 0;
	$page = (int)$page;
	$page = ($page!=0) ? $page-1 : 0;
	$state_options = array(3, 4);
	$sortby_options = array('DESC', 'ASC');
	$orderby_options = array('id', 'begin', 'expire', 'title');	
	$sortby = (empty($sortby) || !in_array($sortby, $sortby_options)) ? "DESC" : $sortby;
	$orderby = (empty($orderby) || !in_array($orderby, $orderby_options)) ? "id" : $orderby;
	$orderby = "page_".$orderby;
	$realpage = $page+1;
	$state = (empty($state) || !in_array($state, $state_options)) ? 3 : $state;
	
	$limit = HFP_TOOL_ITEMS_PER_PAGE;
	$offset = ceil($page*$limit);
	$sql_total = sed_sql_query("SELECT COUNT(*) FROM $db_pages WHERE page_state='$state'");
	$total_count = (int)sed_sql_result($sql_total, 0, "COUNT(*)");
	$sql = sed_sql_query("SELECT page_state, page_title, page_begin, page_expire, page_id FROM $db_pages ".
	"WHERE page_state='$state' ORDER BY ".sed_sql_prep($orderby)." ".sed_sql_prep($sortby)." LIMIT ".(int)$offset.", ".(int)$limit);
	
	while($result = sed_sql_fetchassoc($sql)) {
		$t->assign(array(
			"ITEM_PAGE_ID" => (int)$result['page_id'],
			"ITEM_PAGE_TITLE" => htmlspecialchars($result['page_title']),
			"ITEM_PAGE_BEGIN" => @date($cfg['dateformat'], (int)$result['page_begin'] + $usr['timezone'] * 3600),
			"ITEM_PAGE_EXPIRE" => @date($cfg['dateformat'], (int)$result['page_expire'] + $usr['timezone'] * 3600),
			"ITEM_PAGE_EDIT_URL" => sed_url('page', "m=edit&id=".(int)$result['page_id']."&r=adm"),
			"ITEM_SORT_TITLE_ASC" => sed_url('admin', 'm=tools&p=hidefuturepages&orderby=title&sortby=asc&state='.$state.'&page='.$realpage),
			"ITEM_SORT_TITLE_DESC" => sed_url('admin', 'm=tools&p=hidefuturepages&orderby=title&sortby=desc&state='.$state.'&page='.$realpage),
			"ITEM_SORT_ID_ASC" => sed_url('admin', 'm=tools&p=hidefuturepages&orderby=id&sortby=asc&state='.$state.'&page='.$realpage),
			"ITEM_SORT_ID_DESC" => sed_url('admin', 'm=tools&p=hidefuturepages&orderby=id&sortby=desc&state='.$state.'&page='.$realpage),
			"ITEM_SORT_BEGIN_ASC" => sed_url('admin', 'm=tools&p=hidefuturepages&orderby=begin&sortby=asc&state='.$state.'&page='.$realpage),
			"ITEM_SORT_BEGIN_DESC" => sed_url('admin', 'm=tools&p=hidefuturepages&orderby=begin&sortby=desc&state='.$state.'&page='.$realpage),
			"ITEM_SORT_EXPIRE_ASC" => sed_url('admin', 'm=tools&p=hidefuturepages&orderby=expire&sortby=asc&state='.$state.'&page='.$realpage),
			"ITEM_SORT_EXPIRE_DESC" => sed_url('admin', 'm=tools&p=hidefuturepages&orderby=expire&sortby=desc&state='.$state.'&page='.$realpage),
			"ITEM_PAGE_ADD_TO_QUEUE_URL" => sed_url('admin', 'm=tools&p=hidefuturepages&action=add_to_queue&id='.(int)$result['page_id'])."&".sed_xg(),
			"ITEM_PAGE_SET_TO_DISPLAY_URL" => sed_url('admin', 'm=tools&p=hidefuturepages&action=set_to_display&id='.(int)$result['page_id'])."&".sed_xg(),
			"ITEM_PAGE_PAGINATION" => hfp_create_pagination($total_count, $page+1), 
		));
		$itemcount++;
		$t->parse("MAIN.ACTION_SHOWALL.NONEMPTY_LIST.ITEM_LIST");
	}
	switch($state) {
		case 3:
			$tooltitle = "Future pages";
		break;
		case 4:
			$tooltitle = "Hidden pages";
		break;
	}
	$t->assign(array(
		"TOOL_SHOW_TITLE" => $tooltitle,
		"TOOL_SHOW_OPTIONS" => hfp_tool_show_options($state),
		"TOOL_SHOW_COUNT" => $total_count,
		"TOOL_SHOW_PAGELIMIT" => $itemcount,
		"TOOL_STATE_SELECTED_URL_FUTURE" => sed_url('admin', 'm=tools&p=hidefuturepages&state=3', NULL, TRUE),
		"TOOL_STATE_SELECTED_URL_HIDDEN" => sed_url('admin', 'm=tools&p=hidefuturepages&state=4', NULL, TRUE),
	));
	if($total_count==0) {
		$t->parse("MAIN.ACTION_SHOWALL.EMPTY_LIST");
	}
	else {
		$t->parse("MAIN.ACTION_SHOWALL.NONEMPTY_LIST");
	}
	$t->parse("MAIN.ACTION_SHOWALL");
}

function hfp_tool_get_page_count($page_state) {
	global $db_pages;
	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_pages WHERE page_state='".(int)$page_state."'");
	$result = sed_sql_result($sql, 0, "COUNT(*)");
	return (int)$result;
}

function hfp_tool_show_options($currentstate=3) {
	$count_futurepages = hfp_tool_get_page_count(3);
	$count_hiddenpages = hfp_tool_get_page_count(4);
	
	$output  = "<span id=\"hfp_options_nojs\">";
	if($currentstate!=3) {
		$output .= "<a href=\"".sed_url('admin', 'm=tools&p=hidefuturepages&state=3')."\">Future pages (".(int)$count_futurepages.")</a> &nbsp;-&nbsp;";
	}
	else {
		$output .= "<strong>Future pages (".(int)$count_futurepages.")</strong> &nbsp;-&nbsp;";
	}	
	if($currentstate!=4) {
		$output .= "<a href=\"".sed_url('admin', 'm=tools&p=hidefuturepages&state=4')."\">Hidden pages (".(int)$count_hiddenpages.")</a>"; 
	}
	else {
		$output .= "<strong>Hidden pages (".(int)$count_hiddenpages.")</strong>"; 
	}
	$output .= "</span>"; 
	
	$futurepagesselected = ($currentstate==3) ? ' selected="selected"' : '';
	$hiddenpagesselected = ($currentstate==4) ? ' selected="selected"' : '';
	
	$output .= "<span style=\"display: none;\" id=\"hfp_options_js\">";
	$output .= "<select id=\"hfp_select_options\">";
	$output .= "<option".$futurepagesselected." value=\"3\">Future pages (".(int)$count_futurepages.")</option>";
	$output .= "<option".$hiddenpagesselected." value=\"4\">Hidden pages (".(int)$count_hiddenpages.")</option>";
	$output .= "</select>";
	$output .= "</span>";
	return $output; 
}

function hfp_tool_action_add_to_queue($id) {
	global $db_pages, $sys, $db_structure;
	sed_check_xg();
	$id = (int)$id;
	$state = sed_import('state', 'G', 'INT', 1);
	if($id>0) {
		$sql = sed_sql_query("SELECT page_cat FROM $db_pages WHERE page_id='".$id."'");
		if($result = sed_sql_fetchassoc($sql)) {
			$pageexpire = hfp_tool_get_yearstillexpire();
			sed_sql_query("UPDATE $db_pages SET page_state='1', page_expire='".$pageexpire."' WHERE page_id='".$id."'");
			sed_sql_query("UPDATE $db_structure SET structure_pagecount=structure_pagecount-1 WHERE structure_code='".sed_sql_prep($result['page_cat'])."'");
		}
	}
	sed_redirect(sed_url('admin', 'm=tools&p=hidefuturepages&state='.$state, NULL, TRUE));
}

function hfp_tool_get_yearstillexpire() {
	global $cfg, $sys;
	$yearstillexpire = (int)$cfg['plugin']['hidefuturepages']['yearstillpageexpire'];
	$yearstillexpire = ($yearstillexpire==0) ? 1: $yearstillexpire; 
	$pageexpire = (31536000*$yearstillexpire);
	$pageexpire = $sys['now_offset']+$pageexpire;
	return $pageexpire;	
}

function hfp_tool_action_set_to_display($id) {
	global $db_pages, $cfg, $sys, $usr, $db_structure;
	sed_check_xg();
	$id = (int)$id;
	$state = sed_import('state', 'G', 'INT', 1);
	if($id>0) {
		$sql = sed_sql_query("SELECT page_cat FROM $db_pages WHERE page_id='".$id."'");
		if($result = sed_sql_fetchassoc($sql)) {
			$pageexire = hfp_tool_get_yearstillexpire();
			sed_sql_query("UPDATE $db_pages SET page_begin='".(int)$sys['now_offset']."', page_expire='".$pageexpire."', page_state='0' WHERE page_id='$id'");
			sed_sql_query("UPDATE $db_structure SET structure_pagecount=structure_pagecount+1 WHERE structure_code='".sed_sql_prep($result['page_cat'])."'");
		}
	}
	sed_redirect(sed_url('admin', 'm=tools&p=hidefuturepages&state='.$state, NULL, TRUE));
}

function hfp_create_pagination($total, $page) {
	global $cfg, $showstate;
	$limit = HFP_TOOL_ITEMS_PER_PAGE;
	$offset_page = $page-1;
	$total = ($total!=0) ? ceil($total/$limit) : 0;
	$state = $showstate;
	
	if($offset_page!=0) {
		$prev_page = $page-1;
		$output = '<a style="text-decoration: underline;" href="'.sed_url('admin', 'm=tools&p=hidefuturepages&page='.$prev_page.'&state='.$state).'"><img class="hfp_icon_16" src="'.$cfg['plugins_dir'].'/hidefuturepages/img/arrow-left.png" /></a> &nbsp; ';
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
