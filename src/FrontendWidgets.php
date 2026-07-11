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

        $settings = My::settings();

        $cat_level = $rs->intField('level');
        if ($cat_level > 0) {
            $cat_level--;
        }

        $ref_level = $cat_level;
        $level     = $cat_level;
        while ($rs->fetch()) {
            if ($settings->getBool('active')
                && $settings->getStr('cat', false) !== ''
                && $settings->getStr('cat', false) === $rs->strField('cat_url')
            ) {
                // Ignore discrete category
                continue;
            }

            $class = '';
            if ((App::url()->isType('category')
                    && App::frontend()->context()->categories instanceof MetaRecord
                    && App::frontend()->context()->categories->intField('cat_id') === $rs->intField('cat_id'))
                || (App::url()->isType('post')
                    && App::frontend()->context()->posts instanceof MetaRecord
                    && App::frontend()->context()->posts->intField('cat_id') === $rs->intField('cat_id'))
            ) {
                $class = ' class="category-current"';
            }

            $cat_level = max(0, $rs->intField('level') - 1);
            if ($cat_level > $level) {
                $res .= str_repeat('<ul><li' . $class . '>', $cat_level - $level);
            } elseif ($cat_level < $level) {
                $res .= str_repeat('</li></ul>', -($cat_level - $level));
            }

            if ($cat_level <= $level) {
                $res .= '</li><li' . $class . '>';
            }

            $cat_url   = $rs->strField('cat_url');
            $cat_title = $rs->strField('cat_title');
            $nb_total  = $rs->intField('nb_total');
            $nb_post   = $rs->intField('nb_post');

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
