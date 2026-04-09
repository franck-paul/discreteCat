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
use Dotclear\Database\MetaRecord;
use Dotclear\Helper\Html\Html;
use Dotclear\Plugin\widgets\WidgetsElement;

class FrontendWidgets
{
    /**
     * Render widget
     *
     * @param      WidgetsElement  $w  The widget
     *
     * @return     string Widget content rendered
     */
    public static function categories(WidgetsElement $w): string
    {
        if ($w->offline) {
            return '';
        }

        if (!$w->checkHomeOnly(App::url()->getType())) {
            return '';
        }

        $rs = App::blog()->getCategories(['post_type' => 'post', 'without_empty' => !$w->get('with_empty')]);
        if ($rs->isEmpty()) {
            return '';
        }

        $res = ($w->title ? $w->renderTitle(Html::escapeHTML($w->title)) : '');

        // Variable data helpers
        $_Bool = fn (mixed $var): bool => (bool) $var;
        $_Str  = fn (mixed $var, string $default = ''): string => $var !== null && is_string($val = $var) ? $val : $default;

        $settings = My::settings();

        $cat_level = is_numeric($cat_level = $rs->level) ? (int) $cat_level - 1 : 0;

        $ref_level = $cat_level;
        $level     = $cat_level;
        while ($rs->fetch()) {
            if ($_Bool($settings->active) && $_Str($settings->cat) !== '' && $_Str($settings->cat) === $rs->cat_url) {
                // Ignore discrete category
                continue;
            }

            $class = '';
            if ((App::url()->getType() == 'category' && App::frontend()->context()->categories instanceof MetaRecord && App::frontend()->context()->categories->cat_id == $rs->cat_id)
                || (App::url()->getType() == 'post' && App::frontend()->context()->posts instanceof MetaRecord && App::frontend()->context()->posts->cat_id == $rs->cat_id)) {
                $class = ' class="category-current"';
            }

            $cat_level = is_numeric($cat_level = $rs->level) ? (int) $cat_level - 1 : 0;
            if ($cat_level > $level) {
                $res .= str_repeat('<ul><li' . $class . '>', $cat_level - $level);
            } elseif ($cat_level < $level) {
                $res .= str_repeat('</li></ul>', -($cat_level - $level));
            }

            if ($cat_level <= $level) {
                $res .= '</li><li' . $class . '>';
            }

            $cat_url   = is_string($cat_url = $rs->cat_url) ? $cat_url : '';
            $cat_title = is_string($cat_title = $rs->cat_title) ? $cat_title : '';
            $nb_total  = is_numeric($nb_total = $rs->nb_total) ? (int) $nb_total : 0;
            $nb_post   = is_numeric($nb_post = $rs->nb_post) ? (int) $nb_post : 0;

            $res .= '<a href="' . App::blog()->url() . App::url()->getURLFor('category', $cat_url) . '">' .
            Html::escapeHTML($cat_title) . '</a>' .
                ($w->get('postcount') ? ' <span>(' . ($w->get('subcatscount') ? $nb_total : $nb_post) . ')</span>' : '');

            $level = $cat_level;
        }

        if ($ref_level - $level < 0) {
            $res .= str_repeat('</li></ul>', -($ref_level - $level));
        }

        return $w->renderDiv((bool) $w->content_only, 'categories ' . $w->class, '', $res);
    }
}
