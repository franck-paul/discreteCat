<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
# This file is part of discreteCat, a plugin for Dotclear 2.
#
# Copyright (c) Franck Paul and contributors
# carnet.franck.paul@gmail.com
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_CONTEXT_ADMIN')) { return; }

// dead but useful code, in order to have translations
__('Discrete category').__('Exclude a category from Home and RSS/Atom feed');

$_menu['Blog']->addItem(__('Discrete category'),'plugin.php?p=discreteCat','index.php?pf=discreteCat/icon.png',
		preg_match('/plugin.php\?p=discreteCat(&.*)?$/',$_SERVER['REQUEST_URI']),
		$core->auth->check('admin',$core->blog->id));

/* Register favorite */
$core->addBehavior('adminDashboardFavorites',array('adminDiscreteCat','adminDashboardFavorites'));

class adminDiscreteCat
{
	public static function adminDashboardFavorites($core,$favs)
	{
		$favs->register('discreteCat', array(
			'title' => __('Discrete category'),
			'url' => 'plugin.php?p=discreteCat',
			'small-icon' => 'index.php?pf=discreteCat/icon.png',
			'large-icon' => 'index.php?pf=discreteCat/icon-big.png',
			'permissions' => 'admin'
		));
	}
}
