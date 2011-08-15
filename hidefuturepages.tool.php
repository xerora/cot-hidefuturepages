<?php
/* ====================
[BEGIN_SED_EXTPLUGIN]
Code=hidefuturepages
Part=tool
File=hidefuturepages.tool
Hooks=tools
Tags=
Order=1
[END_SED_EXTPLUGIN]
==================== */
defined('SED_CODE') or die('Wrong URL');

$action = sed_import('action', 'G', 'SLU');
$action = (!empty($action)) ? $action : '';

require_once $cfg['plugins_dir']."/hidefuturepages/inc/hidefuturepages.tool.inc.php";
$t = new XTemplate($cfg['plugins_dir']."/hidefuturepages/tpl/hidefuturepages.tool.main.tpl");
$tool_path = $cfg['plugins_dir']."/hidefuturepages";

switch($action) {
	default:
		hfp_tool_action_showall();
	break;
	case 'add_to_queue':
		$id = sed_import('id', 'G', 'INT');
		hfp_tool_action_add_to_queue($id);
	break;
	case 'set_to_display':
		$id = sed_import('id', 'G', 'INT');
		hfp_tool_action_set_to_display($id);
	break;
}
$t->assign(array(
	"HFP_TOOL_PATH" => $tool_path,
	"HFP_TOOL_STYLESHEET_HREF" => $cfg['plugins_dir']."/hidefuturepages/inc/hidefuturepages.tool.css",
));

$t->parse("MAIN");
$plugin_body .= $t->text("MAIN");
