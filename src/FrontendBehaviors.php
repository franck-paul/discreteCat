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

use dcCore;

class FrontendBehaviors
{
    public static function coreBlogBeforeGetPosts($params)
    {
        $settings = dcCore::app()->blog->settings->get(My::id());
        if ($settings->active && ($settings->cat != '')) {
            // discreteCat active and a category to exclude
            if (!isset($params['no_context']) && !isset($params['cat_url']) && !isset($params['cat_id']) && !isset($params['cat_id_not'])) {
                $url_types = ['default', 'default-page', 'feed'];
                if (in_array(dcCore::app()->url->type, $url_types)) {
                    $params['cat_url'] = $settings->cat . ' ?not';
                }
            }
        }
    }
}
