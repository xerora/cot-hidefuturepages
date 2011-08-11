<?php 
/* ====================
[BEGIN_SED_EXTPLUGIN]
Code=hidefuturepages
Part=page
File=hidefuturepages.page.add.tags
Hooks=page.add.tags
Tags=
Order=1
[END_SED_EXTPLUGIN]
==================== */
defined('SED_CODE') or die('Wrong URL');

$yearstillpageexpire = (int)$cfg['plugin']['hidefuturepages']['yearstillpageexpire'];
$yearstillpageexpire = ($yearstillpageexpire>0) ? $yearstillpageexpire : 1;
$newpage_form_expire = sed_selectbox_date($sys['now_offset']+$usr['timezone']*3600 + 31536000*$yearstillpageexpire, 'long', '_exp');
$t->assign(array(
	"PAGEADD_FORM_EXPIRE" => $newpage_form_expire
));
