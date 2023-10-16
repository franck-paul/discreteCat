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
use dcNamespace;
use Dotclear\Core\Backend\Notices;
use Dotclear\Core\Backend\Page;
use Dotclear\Core\Process;
use Dotclear\Helper\Html\Form\Checkbox;
use Dotclear\Helper\Html\Form\Form;
use Dotclear\Helper\Html\Form\Label;
use Dotclear\Helper\Html\Form\Para;
use Dotclear\Helper\Html\Form\Select;
use Dotclear\Helper\Html\Form\Submit;
use Dotclear\Helper\Html\Form\Text;
use Dotclear\Helper\Html\Html;
use Exception;

class Manage extends Process
{
    /**
     * Initializes the page.
     */
    public static function init(): bool
    {
        return self::status(My::checkContext(My::MANAGE));
    }

    /**
     * Processes the request(s).
     */
    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        if (!empty($_POST)) {
            try {
                $dc_active   = (bool) $_POST['dc_active'];
                $dc_category = '';
                if (!empty($_POST['dc_category'])) {
                    $dc_category = $_POST['dc_category'];
                }

                // Everything's fine, save options
                $settings = My::settings();
                $settings->put('active', $dc_active, dcNamespace::NS_BOOL);
                $settings->put('cat', $dc_category, dcNamespace::NS_STRING);

                dcCore::app()->blog->triggerBlog();

                Notices::addSuccessNotice(__('Settings have been successfully updated.'));
                dcCore::app()->adminurl->redirect('admin.plugin.' . My::id());
            } catch (Exception $e) {
                dcCore::app()->error->add($e->getMessage());
            }
        }

        return true;
    }

    /**
     * Renders the page.
     */
    public static function render(): void
    {
        if (!self::status()) {
            return;
        }

        $settings    = My::settings();
        $dc_active   = (bool) $settings->active;
        $dc_category = $settings->cat;

        $categories_combo = [];

        try {
            $rs = dcCore::app()->blog->getCategories(['post_type' => 'post']);
            while ($rs->fetch()) {
                $categories_combo[str_repeat('&nbsp;&nbsp;', $rs->level - 1) . ($rs->level - 1 == 0 ? '' : '&bull; ') . Html::escapeHTML($rs->cat_title)] = $rs->cat_url;
            }
        } catch (Exception $e) {
            // Ignore exceptions
        }

        Page::openModule(My::name());

        echo Page::breadcrumb(
            [
                Html::escapeHTML(dcCore::app()->blog->name) => '',
                __('Discrete category')                     => '',
            ]
        );
        echo Notices::getNotices();

        // Prepare form fields
        $rs = dcCore::app()->blog->getCategories(['post_type' => 'post']);
        if ($rs->isEmpty()) {
            $fields = [
                (new Para())->items([
                    (new Text(null, __('No category yet.'))),
                ]),
            ];
        } else {
            $fields = [
                (new Para())->items([
                    (new Select('dc_category'))
                        ->items($categories_combo)
                        ->default($dc_category)
                        ->label((new Label(__('Select category:'), Label::INSIDE_TEXT_BEFORE))),
                ]),
                (new Para())->class('form-note')->items([
                    (new Text(null, __('This category will be excluded from home and it\'s RSS/Atom feeds only.'))),
                ]),
            ];
        }

        // Form
        echo (new Form('discrete-cat'))
            ->action(dcCore::app()->admin->getPageURL())
            ->method('post')
            ->fields([
                (new Para())->items([
                    (new Checkbox('dc_active', $dc_active))
                        ->value(1)
                        ->label((new Label(__('Activate discrete categorie on this blog'), Label::INSIDE_TEXT_AFTER))),
                ]),
                ...$fields,
                (new Para())->items([
                    (new Submit(['frmsubmit']))
                        ->value(__('Save')),
                    ... My::hiddenFields(),
                ]),
            ])
        ->render();

        Page::closeModule();
    }
}
