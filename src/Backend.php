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

use dcAdmin;
use dcCore;
use dcFavorites;
use dcNsProcess;

class Backend extends dcNsProcess
{
    protected static $init = false; /** @deprecated since 2.27 */
    public static function init(): bool
    {
        static::$init = My::checkContext(My::BACKEND);

        // dead but useful code, in order to have translations
        __('Discrete category') . __('Exclude a category from Home and RSS/Atom feed');

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init) {
            return false;
        }

        dcCore::app()->menu[dcAdmin::MENU_BLOG]->addItem(
            __('Discrete category'),
            My::makeUrl(),
            My::icons(),
            preg_match(My::urlScheme(), $_SERVER['REQUEST_URI']),
            My::checkContext(My::MENU)
        );

        /* Register favorite */
        dcCore::app()->addBehavior('adminDashboardFavoritesV2', function (dcFavorites $favs) {
            $favs->register('sysInfo', [
                'title'      => __('Discrete category'),
                'url'        => My::makeUrl(),
                'small-icon' => My::icons(),
                'large-icon' => My::icons(),
                My::checkContext(My::MENU),
            ]);
        });

        /* Register widget */
        dcCore::app()->addBehavior('initWidgets', [Widgets::class, 'initWidgets']);

        return true;
    }
}
