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

if (!defined('DC_RC_PATH')) {return;}

$core->addBehavior('coreBlogBeforeGetPosts', ['behaviorDiscreteCat', 'coreBlogBeforeGetPosts']);

class behaviorDiscreteCat
{
    public static function coreBlogBeforeGetPosts($params)
    {
        global $core, $_ctx;

        if ($core->blog->settings->discretecat->discretecat_active && ($core->blog->settings->discretecat->discretecat_cat != '')) {
            // discreteCat active and a category to exclude
            if (!isset($params['no_context']) && !isset($params['cat_url']) && !isset($params['cat_id']) && !isset($params['cat_id_not'])) {
                $url_types = ['default', 'default-page', 'feed'];
                if (in_array($core->url->type, $url_types)) {
                    $params['cat_url'] = $core->blog->settings->discretecat->discretecat_cat . ' ?not';
                }
            }
        }
    }
}
