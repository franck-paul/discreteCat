<?php
/**
 * @brief discreteCat, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul and contributors
 *
 * @copyright Franck Paul carnet.franck.paul@gmail.com
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
if (!defined('DC_CONTEXT_ADMIN')) {
    return;
}

dcCore::app()->blog->settings->addNamespace('discretecat');
$dc_active   = (bool) dcCore::app()->blog->settings->discretecat->discretecat_active;
$dc_category = dcCore::app()->blog->settings->discretecat->discretecat_cat;

if (!empty($_POST)) {
    try {
        $dc_active   = (bool) $_POST['dc_active'];
        $dc_category = '';
        if (!empty($_POST['dc_category'])) {
            $dc_category = $_POST['dc_category'];
        }

        # Everything's fine, save options
        dcCore::app()->blog->settings->addNamespace('discretecat');
        dcCore::app()->blog->settings->discretecat->put('discretecat_active', $dc_active);
        dcCore::app()->blog->settings->discretecat->put('discretecat_cat', $dc_category);

        dcCore::app()->blog->triggerBlog();

        dcPage::addSuccessNotice(__('Settings have been successfully updated.'));
        http::redirect($p_url);
    } catch (Exception $e) {
        dcCore::app()->error->add($e->getMessage());
    }
}

$categories_combo = [];

try {
    $rs = dcCore::app()->blog->getCategories(['post_type' => 'post']);
    while ($rs->fetch()) {
        $categories_combo[] = new formSelectOption(
            str_repeat('&nbsp;&nbsp;', $rs->level - 1) . ($rs->level - 1 == 0 ? '' : '&bull; ') . html::escapeHTML($rs->cat_title),
            $rs->cat_url
        );
    }
} catch (Exception $e) {
    // Ignore exceptions
}

?>
<html>
<head>
  <title><?php echo __('Discrete category'); ?></title>
</head>

<body>
<?php
echo dcPage::breadcrumb(
    [
        html::escapeHTML(dcCore::app()->blog->name) => '',
        __('Discrete category')                     => '',
    ]
);
echo dcPage::notices();

echo
'<form action="' . $p_url . '" method="post">' .
'<p>' . form::checkbox('dc_active', 1, $dc_active) . ' ' .
'<label for="dc_active" class="classic">' . __('Activate discrete categorie on this blog') . '</label></p>';

$rs = dcCore::app()->blog->getCategories(['post_type' => 'post']);
if ($rs->isEmpty()) {
    echo '<p>' . __('No category yet.') . '</p>';
} else {
    echo '<p><label for="dc_category" class="classic">' . __('Select category:') . '</label> ' .
    form::combo('dc_category', $categories_combo, $dc_category) . '</p>' .
    '<p class="form-note">' . __('This category will be excluded from home and it\'s RSS/Atom feeds only.') . '</p>';
}

echo
'<p>' . dcCore::app()->formNonce() . '<input type="submit" value="' . __('Save') . '" /></p>' .
    '</form>';
?>
</body>
</html>
