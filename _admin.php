<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of Dotclear 2.
#
# Copyright (c) 2003-2013 Franck Paul
# Licensed under the GPL version 2.0 license.
# See LICENSE file or
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# -- END LICENSE BLOCK ------------------------------------
if (!defined('DC_CONTEXT_ADMIN')) { return; }

$_menu['Plugins']->addItem(__('Discrete category'),'plugin.php?p=discreteCat','index.php?pf=discreteCat/icon.png',
		preg_match('/plugin.php\?p=discreteCat(&.*)?$/',$_SERVER['REQUEST_URI']),
		$core->auth->check('admin',$core->blog->id));

$core->addBehavior('adminDashboardFavs','catOrderDashboardFavs');

function catOrderDashboardFavs($core,$favs)
{
	$favs['discreteCat'] = new ArrayObject(array('discreteCat','Discrete category','plugin.php?p=discreteCat',
		'index.php?pf=discreteCat/icon.png','index.php?pf=discreteCat/icon-big.png',
		'admin',null,null));
}
?>