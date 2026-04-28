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
        // Variable data helpers
        $_Bool = fn (mixed $var): bool => (bool) $var;
        $_Str  = fn (mixed $var, string $default = ''): string => $var !== null && is_string($val = $var) ? $val : $default;

        $settings = My::settings();

        // discreteCat active and a category to exclude
        if ($_Bool($settings->active) && $_Str($settings->cat) !== '' && (!isset($params['no_context']) && !isset($params['cat_url']) && !isset($params['cat_id']) && !isset($params['cat_id_not']))) {
            $url_types = ['default', 'default-page', 'feed'];
            if (in_array(App::url()->getType(), $url_types)) {
                $params['cat_url'] = $_Str($settings->cat) . ' ?not';
            }
        }

        return '';
    }
}
