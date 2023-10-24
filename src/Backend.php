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
declare(strict_types=1);

namespace Dotclear\Plugin\discreteCat;

use Dotclear\App;
use Dotclear\Core\Backend\Favorites;
use Dotclear\Core\Process;

class Backend extends Process
{
    public static function init(): bool
    {
        // dead but useful code, in order to have translations
        __('Discrete category') . __('Exclude a category from Home and RSS/Atom feed');

        return self::status(My::checkContext(My::BACKEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        My::addBackendMenuItem(App::backend()->menus()::MENU_BLOG);

        /* Register favorite */
        App::behavior()->addBehavior('adminDashboardFavoritesV2', function (Favorites $favs) {
            $favs->register('sysInfo', [
                'title'       => __('Discrete category'),
                'url'         => My::manageUrl(),
                'small-icon'  => My::icons(),
                'large-icon'  => My::icons(),
                'permissions' => My::checkContext(My::MENU),
            ]);
        });

        /* Register widget */
        App::behavior()->addBehavior('initWidgets', Widgets::initWidgets(...));

        return true;
    }
}
