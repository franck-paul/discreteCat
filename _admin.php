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

require_once __DIR__ . '/_widgets.php';

// dead but useful code, in order to have translations
__('Discrete category') . __('Exclude a category from Home and RSS/Atom feed');

dcCore::app()->menu[dcAdmin::MENU_BLOG]->addItem(
    __('Discrete category'),
    'plugin.php?p=discreteCat',
    [urldecode(dcPage::getPF('discreteCat/icon.svg')), urldecode(dcPage::getPF('discreteCat/icon-dark.svg'))],
    preg_match('/plugin.php\?p=discreteCat(&.*)?$/', $_SERVER['REQUEST_URI']),
    dcCore::app()->auth->check(dcCore::app()->auth->makePermissions([
        dcAuth::PERMISSION_ADMIN,
    ]), dcCore::app()->blog->id)
);

class adminDiscreteCat
{
    public static function adminDashboardFavorites($favs)
    {
        $favs->register('discreteCat', [
            'title'       => __('Discrete category'),
            'url'         => 'plugin.php?p=discreteCat',
            'small-icon'  => [
                urldecode(dcPage::getPF('discreteCat/icon.svg')),
                urldecode(dcPage::getPF('discreteCat/icon-dark.svg')),
            ],
            'large-icon'  => [
                urldecode(dcPage::getPF('discreteCat/icon.svg')),
                urldecode(dcPage::getPF('discreteCat/icon-dark.svg')),
            ],
            'permissions' => dcCore::app()->auth->makePermissions([
                dcAuth::PERMISSION_ADMIN,
            ]),
        ]);
    }
}

/* Register favorite */
dcCore::app()->addBehavior('adminDashboardFavoritesV2', [adminDiscreteCat::class, 'adminDashboardFavorites']);

/* Register widget */
dcCore::app()->addBehavior('initWidgets', [widgetDiscreteCat::class,'init']);
