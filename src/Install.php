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
use dcNsProcess;
use Exception;

class Install extends dcNsProcess
{
    public static function init(): bool
    {
        static::$init = My::checkContext(My::INSTALL);

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init) {
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
            $settings->put('active', false, 'boolean', 'Active', false, true);
            $settings->put('cat', '', 'string', 'Category to exclude', false, true);
        } catch (Exception $e) {
            dcCore::app()->error->add($e->getMessage());
        }

        return true;
    }
}
