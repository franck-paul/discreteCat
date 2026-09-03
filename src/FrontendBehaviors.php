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
     * @param      ArrayObject<string, mixed>  $arrayObject  The parameters
     */
    public static function coreBlogBeforeGetPosts(ArrayObject $arrayObject): string
    {
        $settings = My::settings();

        // discreteCat active and a category to exclude
        if ($settings->getBool('active', false)
            && $settings->getStr('cat', false) !== ''
            && (!isset($arrayObject['no_context'])
                && !isset($arrayObject['cat_url'])
                && !isset($arrayObject['cat_url_not'])
                && !isset($arrayObject['cat_id'])
                && !isset($arrayObject['cat_id_not']))
            && App::url()->isType(['default', 'default-page', 'feed'])
        ) {
            // Each excluded category URL is separated by a space
            $arrayObject['cat_url']     = explode(' ', (string) $settings->getStr('cat', false));
            $arrayObject['cat_url_not'] = true;
        }

        return '';
    }
}
