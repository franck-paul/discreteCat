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

        if (!$w->checkHomeOnly(App::url()->type)) {
            return '';
        }

        $rs = App::blog()->getCategories(['post_type' => 'post', 'without_empty' => !$w->get('with_empty')]);
        if ($rs->isEmpty()) {
            return '';
        }

        $res = ($w->title ? $w->renderTitle(Html::escapeHTML($w->title)) : '');

        $settings  = My::settings();
        $ref_level = $level = $rs->level - 1;
        while ($rs->fetch()) {
            if ($settings->active && ($settings->cat != '')) {
                if ($settings->cat === $rs->cat_url) {
                    // Ignore discrete category
                    continue;
                }
            }
            $class = '';
            if ((App::url()->type == 'category' && App::frontend()->context()->categories instanceof MetaRecord && App::frontend()->context()->categories->cat_id == $rs->cat_id)
                || (App::url()->type == 'post' && App::frontend()->context()->posts instanceof MetaRecord && App::frontend()->context()->posts->cat_id == $rs->cat_id)) {
                $class = ' class="category-current"';
            }

            if ($rs->level > $level) {
                $res .= str_repeat('<ul><li' . $class . '>', (int) ($rs->level - $level));
            } elseif ($rs->level < $level) {
                $res .= str_repeat('</li></ul>', (int) -($rs->level - $level));
            }

            if ($rs->level <= $level) {
                $res .= '</li><li' . $class . '>';
            }

            $res .= '<a href="' . App::blog()->url() . App::url()->getURLFor('category', $rs->cat_url) . '">' .
            Html::escapeHTML($rs->cat_title) . '</a>' .
                ($w->get('postcount') ? ' <span>(' . ($w->get('subcatscount') ? $rs->nb_total : $rs->nb_post) . ')</span>' : '');

            $level = $rs->level;
        }

        if ($ref_level - $level < 0) {
            $res .= str_repeat('</li></ul>', (int) -($ref_level - $level));
        }

        return $w->renderDiv((bool) $w->content_only, 'categories ' . $w->class, '', $res);
    }
}
