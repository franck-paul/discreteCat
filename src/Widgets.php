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

use Dotclear\Plugin\widgets\WidgetsStack;

class Widgets
{
    /**
     * Initializes the given widgets.
     *
     * @param      \Dotclear\Plugin\widgets\WidgetsStack  $widgets  The widgets
     */
    public static function initWidgets(WidgetsStack $widgets)
    {
        $widgets
            ->create('discreteCategories', __('List of categories (non discrete)'), [FrontendWidgets::class, 'categories'], null, 'List of categories (non discrete)')
            ->addTitle(__('Categories'))
            ->setting('postcount', __('With entries counts'), 0, 'check')
            ->setting('subcatscount', __('Include sub cats in count'), false, 'check')
            ->setting('with_empty', __('Include empty categories'), 0, 'check')
            ->addHomeOnly()
            ->addContentOnly()
            ->addClass()
            ->addOffline();
    }
}
