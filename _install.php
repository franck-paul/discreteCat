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

$new_version = dcCore::app()->plugins->moduleInfo('discreteCat', 'version');
$old_version = dcCore::app()->getVersion('discreteCat');

if (version_compare((string) $old_version, $new_version, '>=')) {
    return;
}

try {
    dcCore::app()->blog->settings->addNamespace('discretecat');

    // Default state is active for entries content and inactive for comments
    dcCore::app()->blog->settings->discretecat->put('discretecat_active', false, 'boolean', 'Active', false, true);
    dcCore::app()->blog->settings->discretecat->put('discretecat_cat', '', 'string', 'Category to exclude', false, true);

    dcCore::app()->setVersion('discreteCat', $new_version);

    return true;
} catch (Exception $e) {
    dcCore::app()->error->add($e->getMessage());
}

return false;
