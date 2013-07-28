<?php
# -- BEGIN LICENSE BLOCK ---------------------------------------
#
# This file is part of Dotclear 2.
#
# Copyright (c) 2003-2013 Franck Paul
# Licensed under the GPL version 2.0 license.
# See LICENSE file or
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# -- END LICENSE BLOCK -----------------------------------------
if (!defined('DC_RC_PATH')) { return; }

$core->addBehavior('coreBlogBeforeGetPosts',array('behaviorDiscreteCat','coreBlogBeforeGetPosts'));

class behaviorDiscreteCat
{
	public static function coreBlogBeforeGetPosts($params)
	{
		global $core, $_ctx;

		if ($core->blog->settings->discretecat->discretecat_active && ($core->blog->settings->discretecat->discretecat_cat != '')) {
			// discreteCat active and a category to exclude
			if (!isset($params['no_context']) && !isset($params['cat_url']) && !isset($params['cat_id']) && !isset($params['cat_id_not']))
			{
				$url_types = array('default','default-page','feed');
				if (in_array($core->url->type,$url_types)) {
					$params['cat_url'] = $core->blog->settings->discretecat->discretecat_cat.' ?not';
				}
			}
		}
	}
}
?>