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
use Dotclear\Helper\Html\Form\Checkbox;
use Dotclear\Helper\Html\Form\Form;
use Dotclear\Helper\Html\Form\Label;
use Dotclear\Helper\Html\Form\Para;
use Dotclear\Helper\Html\Form\Select;
use Dotclear\Helper\Html\Form\Submit;
use Dotclear\Helper\Html\Form\Text;
use Dotclear\Helper\Html\Html;
use Dotclear\Helper\Process\TraitProcess;
use Exception;

class Manage
{
    use TraitProcess;

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

        if ($_POST !== []) {
            try {
                // Post data helpers
                $_Bool = fn (string $name): bool => !empty($_POST[$name]);
                $_Str  = fn (string $name, string $default = ''): string => isset($_POST[$name]) && is_string($val = $_POST[$name]) ? $val : $default;

                // Everything's fine, save options
                $settings = My::settings();
                $settings->put('active', $_Bool('dc_active'), App::blogWorkspace()::NS_BOOL);
                $settings->put('cat', $_Str('dc_category'), App::blogWorkspace()::NS_STRING);

                App::blog()->triggerBlog();

                App::backend()->notices()->addSuccessNotice(__('Settings have been successfully updated.'));
                My::redirect();
            } catch (Exception $e) {
                App::error()->add($e->getMessage());
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

        // Variable data helpers
        $_Bool = fn (mixed $var): bool => (bool) $var;
        $_Str  = fn (mixed $var, string $default = ''): string => $var !== null && is_string($val = $var) ? $val : $default;

        $settings = My::settings();

        $dc_active   = $_Bool($settings->active);
        $dc_category = $_Str($settings->cat);

        $categories_combo = App::backend()->combos()->getCategoriesCombo(
            App::blog()->getCategories(['post_type' => 'post']),
            true,
            true
        );

        App::backend()->page()->openModule(My::name());

        echo App::backend()->page()->breadcrumb(
            [
                Html::escapeHTML(App::blog()->name()) => '',
                __('Discrete category')               => '',
            ]
        );
        echo App::backend()->notices()->getNotices();

        // Prepare form fields
        $rs = App::blog()->getCategories(['post_type' => 'post']);
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
            ->action(App::backend()->getPageURL())
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

        App::backend()->page()->closeModule();
    }
}
