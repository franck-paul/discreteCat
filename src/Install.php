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
use Dotclear\Core\Process;
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
            $old_version = dcCore::app()->getVersion(My::id());
            if (version_compare((string) $old_version, '3.0', '<')) {
                // Rename settings namespace
                if (dcCore::app()->blog->settings->exists('discretecat')) {
                    dcCore::app()->blog->settings->delNamespace(My::id());
                    dcCore::app()->blog->settings->renNamespace('discretecat', My::id());
                }

                // Change settings names (remove discretecat_ prefix in them)
                $rename = function (string $name, dcNamespace $settings): void {
                    if ($settings->settingExists('discretecat_' . $name, true)) {
                        $settings->rename('discretecat_' . $name, $name);
                    }
                };

                $settings = dcCore::app()->blog->settings->get(My::id());

                foreach ([
                    'active',
                    'cat',
                ] as $value) {
                    $rename($value, $settings);
                }
            }

            // Init
            $settings = dcCore::app()->blog->settings->get(My::id());

            // Default state is active for entries content and inactive for comments
            $settings->put('active', false, dcNamespace::NS_BOOL, 'Active', false, true);
            $settings->put('cat', '', dcNamespace::NS_STRING, 'Category to exclude', false, true);
        } catch (Exception $e) {
            dcCore::app()->error->add($e->getMessage());
        }

        return true;
    }
}
