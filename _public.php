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
require_once __DIR__ . '/_widgets.php';

class behaviorDiscreteCat
{
    public static function coreBlogBeforeGetPosts($params)
    {
        if (dcCore::app()->blog->settings->discretecat->discretecat_active && (dcCore::app()->blog->settings->discretecat->discretecat_cat != '')) {
            // discreteCat active and a category to exclude
            if (!isset($params['no_context']) && !isset($params['cat_url']) && !isset($params['cat_id']) && !isset($params['cat_id_not'])) {
                $url_types = ['default', 'default-page', 'feed'];
                if (in_array(dcCore::app()->url->type, $url_types)) {
                    $params['cat_url'] = dcCore::app()->blog->settings->discretecat->discretecat_cat . ' ?not';
                }
            }
        }
    }
}

dcCore::app()->addBehavior('coreBlogBeforeGetPosts', [behaviorDiscreteCat::class, 'coreBlogBeforeGetPosts']);

/* Register widget */
dcCore::app()->addBehavior('initWidgets', [widgetDiscreteCat::class,'init']);
