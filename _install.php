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

if (!defined('DC_CONTEXT_ADMIN')) {return;}

$new_version = $core->plugins->moduleInfo('discreteCat', 'version');
$old_version = $core->getVersion('discreteCat');

if (version_compare($old_version, $new_version, '>=')) {
    return;
}

try
{
    $core->blog->settings->addNamespace('discretecat');

    // Default state is active for entries content and inactive for comments
    $core->blog->settings->discretecat->put('discretecat_active', false, 'boolean', 'Active', false, true);
    $core->blog->settings->discretecat->put('discretecat_cat', '', 'string', 'Category to exclude', false, true);

    $core->setVersion('discreteCat', $new_version);

    return true;
} catch (Exception $e) {
    $core->error->add($e->getMessage());
}
return false;
