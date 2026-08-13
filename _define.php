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

if (isset($this) && is_object($this) && method_exists($this, 'registerModule') && isset($this->id) && is_string($this->id)) {
    $this->registerModule(
        'Discrete categories',
        'Exclude some categories from Home and RSS/Atom feed',
        'Franck Paul',
        '7.0',
        [
            'date'        => '2026-08-03T09:51:10+0200',
            'requires'    => [['core', '2.39']],
            'permissions' => '',
            'type'        => 'plugin',
            'settings'    => [],

            'details'    => 'https://open-time.net/?q=discreteCat',
            'support'    => 'https://github.com/franck-paul/discreteCat',
            'repository' => 'https://raw.githubusercontent.com/franck-paul/discreteCat/main/dcstore.xml',
            'license'    => 'gpl2',
        ]
    );
}
