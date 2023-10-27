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
use Dotclear\Core\Process;
use Dotclear\Interface\Core\BlogWorkspaceInterface;
use Exception;

class Install extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::INSTALL));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        try {
            // Update
            $old_version = App::version()->getVersion(My::id());
            if (version_compare((string) $old_version, '3.0', '<')) {
                // Rename settings namespace
                if (App::blog()->settings()->exists('discretecat')) {
                    App::blog()->settings()->delWorkspace(My::id());
                    App::blog()->settings()->renWorkspace('discretecat', My::id());
                }

                // Change settings names (remove discretecat_ prefix in them)
                $rename = static function (string $name, BlogWorkspaceInterface $settings) : void {
                    if ($settings->settingExists('discretecat_' . $name, true)) {
                        $settings->rename('discretecat_' . $name, $name);
                    }
                };

                $settings = My::settings();

                foreach ([
                    'active',
                    'cat',
                ] as $value) {
                    $rename($value, $settings);
                }
            }

            // Init
            $settings = My::settings();

            // Default state is active for entries content and inactive for comments
            $settings->put('active', false, App::blogWorkspace()::NS_BOOL, 'Active', false, true);
            $settings->put('cat', '', App::blogWorkspace()::NS_STRING, 'Category to exclude', false, true);
        } catch (Exception $exception) {
            App::error()->add($exception->getMessage());
        }

        return true;
    }
}
