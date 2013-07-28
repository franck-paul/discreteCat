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

if (!defined('DC_CONTEXT_ADMIN')){return;}

$new_version = $core->plugins->moduleInfo('discreteCat','version');
$old_version = $core->getVersion('discreteCat');

if (version_compare($old_version,$new_version,'>=')) return;

try
{
	$core->blog->settings->addNamespace('discretecat');

	// Default state is active for entries content and inactive for comments
	$core->blog->settings->discretecat->put('discretecat_active',false,'boolean','Active',false,true);
	$core->blog->settings->discretecat->put('discretecat_cat','','string','Category to exclude',false,true);

	$core->setVersion('discreteCat',$new_version);
	
	return true;
}
catch (Exception $e)
{
	$core->error->add($e->getMessage());
}
return false;

?>