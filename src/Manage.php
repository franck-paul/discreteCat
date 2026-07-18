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
use Dotclear\Helper\Html\Form\Checkbox;
use Dotclear\Helper\Html\Form\Form;
use Dotclear\Helper\Html\Form\Label;
use Dotclear\Helper\Html\Form\Note;
use Dotclear\Helper\Html\Form\Para;
use Dotclear\Helper\Html\Form\Submit;
use Dotclear\Helper\Html\Form\Table;
use Dotclear\Helper\Html\Form\Tbody;
use Dotclear\Helper\Html\Form\Td;
use Dotclear\Helper\Html\Form\Text;
use Dotclear\Helper\Html\Form\Th;
use Dotclear\Helper\Html\Form\Thead;
use Dotclear\Helper\Html\Form\Tr;
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
                $settings->put('active', $_Bool('active'), App::blogWorkspace()::NS_BOOL);

                $urls = [];
                if (isset($_POST['cat_urls']) && is_array($_POST['cat_urls'])) {
                    $urls = array_filter(array_values($_POST['cat_urls']), is_string(...));
                }

                $settings->put('cat', implode(' ', $urls), App::blogWorkspace()::NS_STRING);

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

        $settings = My::settings();

        $active = $settings->getBool('active', false);
        $cat    = $settings->getStr('cat', false);

        $cat_urls = explode(' ', (string) $cat);

        App::backend()->page()->openModule(
            My::name(),
            My::cssLoad('admin.css')
        );

        echo App::backend()->page()->breadcrumb(
            [
                Html::escapeHTML(App::blog()->name()) => '',
                __('Discrete categories')             => '',
            ]
        );
        echo App::backend()->notices()->getNotices();

        // Prepare form fields
        $rs = App::blog()->getCategories(['post_type' => 'post']);
        if ($rs->isEmpty()) {
            $table = (new Para())->items([
                (new Text(null, __('No category yet.'))),
            ]);
        } else {
            $raws = [];
            while ($rs->fetch()) {
                $cat_id = $rs->intField('cat_id');
                if ($cat_id > 0) {
                    $cat_level = $rs->intField('level');
                    $cat_title = $rs->strField('cat_title');
                    $cat_url   = $rs->strField('cat_url');

                    $raws[] = (new Tr('cat-' . $cat_id))
                        ->items([
                            (new Td())
                                ->items([
                                    (new Checkbox(['cat_urls[]'], in_array($cat_url, $cat_urls)))
                                        ->value($cat_url),
                                    (new Text(null, str_repeat('&nbsp;&nbsp;', $cat_level - 1) . Html::escapeHTML($cat_title))),
                                ]),
                            (new Td())
                                ->class('count')
                                ->items([
                                    (new Text(null, (string) $rs->intField('nb_post'))),
                                ]),
                        ]);
                }
            }

            $table = (new Table())
                ->class('discrete-cats')
                ->thead((new Thead())
                    ->items([
                        (new Th())
                            ->items([
                                (new Text(null, __('Category'))),
                            ]),
                        (new Th())
                            ->class('count')
                            ->items([
                                (new Text(null, __('Number of posts'))),
                            ]),
                    ]))
                ->tbody((new Tbody())
                    ->items($raws));
        }

        // Form
        echo (new Form('discrete-cat'))
            ->action(App::backend()->getPageURL())
            ->method('post')
            ->fields([
                (new Para())
                    ->items([
                        (new Checkbox('active', $active))
                            ->value(1)
                            ->label((new Label(__('Activate discrete categories on this blog'), Label::INSIDE_TEXT_AFTER))),
                    ]),
                (new Note())
                    ->class('form-note')
                    ->text(__('The following selected categories will be excluded from home and it\'s RSS/Atom feeds only.')),
                $table,
                (new Para())
                    ->items([
                        (new Submit(['frmsubmit']))
                            ->value(__('Save')),
                        ... My::hiddenFields(),
                    ]),
            ])
        ->render();

        App::backend()->page()->closeModule();
    }
}
