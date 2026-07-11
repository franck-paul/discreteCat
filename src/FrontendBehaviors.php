<?php

/**
 * @brief discreteCat, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul and contributors
 *
 * @copyright Franck Paul contact@open-time.net
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
declare(strict_types=1);

namespace Dotclear\Plugin\discreteCat;

use ArrayObject;
use Dotclear\App;

class FrontendBehaviors
{
    /**
     * @param      ArrayObject<string, mixed>  $params  The parameters
     */
    public static function coreBlogBeforeGetPosts(ArrayObject $params): string
    {
        $settings = My::settings();

        // discreteCat active and a category to exclude
        if ($settings->getBool('active', false)
            && $settings->getStr('cat', false) !== ''
            && (!isset($params['no_context'])
                && !isset($params['cat_url'])
                && !isset($params['cat_id'])
                && !isset($params['cat_id_not']))
            && App::url()->isType(['default', 'default-page', 'feed'])
        ) {
            $params['cat_url'] = $settings->getStr('cat', false) . ' ?not';
        }

        return '';
    }
}
