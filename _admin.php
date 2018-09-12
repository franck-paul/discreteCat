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

// dead but useful code, in order to have translations
__('Discrete category') . __('Exclude a category from Home and RSS/Atom feed');

$_menu['Blog']->addItem(__('Discrete category'),
    'plugin.php?p=discreteCat',
    urldecode(dcPage::getPF('discreteCat/icon.png')),
    preg_match('/plugin.php\?p=discreteCat(&.*)?$/', $_SERVER['REQUEST_URI']),
    $core->auth->check('admin', $core->blog->id));

/* Register favorite */
$core->addBehavior('adminDashboardFavorites', ['adminDiscreteCat', 'adminDashboardFavorites']);

class adminDiscreteCat
{
    public static function adminDashboardFavorites($core, $favs)
    {
        $favs->register('discreteCat', [
            'title'       => __('Discrete category'),
            'url'         => 'plugin.php?p=discreteCat',
            'small-icon'  => urldecode(dcPage::getPF('discreteCat/icon.png')),
            'large-icon'  => urldecode(dcPage::getPF('discreteCat/icon-big.png')),
            'permissions' => 'admin'
        ]);
    }
}
