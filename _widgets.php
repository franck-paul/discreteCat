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

use Dotclear\Plugin\widgets\WidgetsElement;
use Dotclear\Plugin\widgets\WidgetsStack;

if (!defined('DC_RC_PATH')) {
    return;
}

class widgetDiscreteCat
{
    /**
     * Render widget
     *
     * @param      \Dotclear\Plugin\widgets\WidgetsElement  $widget  The widget
     *
     * @return     string                                   Widget content rendered
     */
    public static function categories(WidgetsElement $widget): string
    {
        if ($widget->offline) {
            return '';
        }

        if (!$widget->checkHomeOnly(dcCore::app()->url->type)) {
            return '';
        }

        $rs = dcCore::app()->blog->getCategories(['post_type' => 'post', 'without_empty' => !$widget->with_empty]);
        if ($rs->isEmpty()) {
            return '';
        }

        $res = ($widget->title ? $widget->renderTitle(html::escapeHTML($widget->title)) : '');

        $ref_level = $level = $rs->level - 1;
        while ($rs->fetch()) {
            if (dcCore::app()->blog->settings->discretecat->discretecat_active && (dcCore::app()->blog->settings->discretecat->discretecat_cat != '')) {
                if (dcCore::app()->blog->settings->discretecat->discretecat_cat === $rs->cat_url) {
                    // Ignore discrete category
                    continue;
                }
            }
            $class = '';
            if ((dcCore::app()->url->type == 'category' && dcCore::app()->ctx->categories instanceof dcRecord && dcCore::app()->ctx->categories->cat_id == $rs->cat_id)
                || (dcCore::app()->url->type == 'post' && dcCore::app()->ctx->posts instanceof dcRecord && dcCore::app()->ctx->posts->cat_id == $rs->cat_id)) {
                $class = ' class="category-current"';
            }

            if ($rs->level > $level) {
                $res .= str_repeat('<ul><li' . $class . '>', $rs->level - $level);
            } elseif ($rs->level < $level) {
                $res .= str_repeat('</li></ul>', -($rs->level - $level));
            }

            if ($rs->level <= $level) {
                $res .= '</li><li' . $class . '>';
            }

            $res .= '<a href="' . dcCore::app()->blog->url . dcCore::app()->url->getURLFor('category', $rs->cat_url) . '">' .
            html::escapeHTML($rs->cat_title) . '</a>' .
                ($widget->postcount ? ' <span>(' . ($widget->subcatscount ? $rs->nb_total : $rs->nb_post) . ')</span>' : '');

            $level = $rs->level;
        }

        if ($ref_level - $level < 0) {
            $res .= str_repeat('</li></ul>', -($ref_level - $level));
        }

        return $widget->renderDiv($widget->content_only, 'categories ' . $widget->class, '', $res);
    }

    /**
     * Initializes the given widgets.
     *
     * @param      \Dotclear\Plugin\widgets\WidgetsStack  $widgets  The widgets
     */
    public static function init(WidgetsStack $widgets)
    {
        $widgets
            ->create('discreteCategories', __('List of categories (non discrete)'), [widgetDiscreteCat::class, 'categories'], null, 'List of categories (non discrete)')
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
