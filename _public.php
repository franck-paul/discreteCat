<?php
# -- BEGIN LICENSE BLOCK ---------------------------------------
#
# This file is part of Dotclear 2.
#
# Copyright (c) 2003-2011 Franck Paul
# Licensed under the GPL version 2.0 license.
# See LICENSE file or
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# -- END LICENSE BLOCK -----------------------------------------
if (!defined('DC_RC_PATH')) { return; }

$core->addBehavior('templateBeforeBlock',array('behaviorDiscreteCat','templateBeforeBlock'));

class behaviorDiscreteCat
{
	public static function templateBeforeBlock($core,$b,$attr)
	{
		if ($b == 'Entries' && !isset($attr['no_context']) && !isset($attr['category']))
		{
			$url_types = array('default','default-page','feed');
			if (in_array($core->url->type,$url_types)) {
				$attr['category'] = 'Photos ?not';
			}
		}
	}
}
?>