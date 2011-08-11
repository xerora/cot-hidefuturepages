<?php
/* ====================
[BEGIN_SED_EXTPLUGIN]
Code=hidefuturepages
Part=page
File=hidefuturepages.page.add.add.query
Hooks=page.add.add.query
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */
defined('SED_CODE') or die('Wrong URL');

if((int)$newpagebegin>(int)$sys['now_offset'] && $page_state==0) { 
	$page_state = 3;
}