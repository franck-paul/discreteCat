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

$core->blog->settings->addNamespace('discretecat');
$dc_active = (boolean) $core->blog->settings->discretecat->discretecat_active;
$dc_category = $core->blog->settings->discretecat->discretecat_cat;

if (!empty($_POST))
{
	try
	{
		$dc_active = (boolean) $_POST['dc_active'];
		$dc_category = '';
		if (!empty($_POST['dc_category'])) {
			$dc_category = $_POST['dc_category'];
		}

		# Everything's fine, save options
		$core->blog->settings->addNamespace('discretecat');
		$core->blog->settings->discretecat->put('discretecat_active',$dc_active);
		$core->blog->settings->discretecat->put('discretecat_cat',$dc_category);

		$core->blog->triggerBlog();

		http::redirect($p_url.'&upd=1');
	}
	catch (Exception $e)
	{
		$core->error->add($e->getMessage());
	}
}

$categories_combo = array();
try {
	$rs = $core->blog->getCategories(array('post_type'=>'post'));
	while ($rs->fetch()) {
		$categories_combo[] = new formSelectOption(
			str_repeat('&nbsp;&nbsp;',$rs->level-1).($rs->level-1 == 0 ? '' : '&bull; ').html::escapeHTML($rs->cat_title),
			$rs->cat_url
		);
	}
} catch (Exception $e) { }

?>
<html>
<head>
	<title><?php echo __('Discrete category'); ?></title>
</head>

<body>
<?php
echo dcPage::breadcrumb(
	array(
		html::escapeHTML($core->blog->name) => '',
		'<span class="page-title">'.__('Discrete category').'</span>' => ''
	));

if (!empty($_GET['upd'])) {
	dcPage::success(__('Settings have been successfully updated.'));
}

echo
'<form action="'.$p_url.'" method="post">'.
'<p><label for="dc_active" class="classic">'.__('Activate discrete categorie on this blog').'</label> '.
form::checkbox('dc_active',1,$dc_active).'</p>'.
'</fieldset>';

$rs = $core->blog->getCategories(array('post_type'=>'post'));
if ($rs->isEmpty()) {
	echo '<p>'.__('No category yet.').'</p>';
} else {
	echo '<p><label for="dc_category" class="classic">'.__('Select category:').'</label> '.
		form::combo('dc_category',$categories_combo,$dc_category).'</p>'.
		'<p class="form-note">'.__('This category will be excluded from home and it\'s RSS/Atom feeds only.').'</p>';
}

echo
'<p>'.$core->formNonce().'<input type="submit" value="'.__('Save').'" /></p>'.
'</form>';
?>
</body>
</html>