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
$this->registerModule(
    'Discrete category',
    'Exclude a category from Home and RSS/Atom feed',
    'Franck Paul',
    '6.2',
    [
        'date'        => '2026-04-09T19:25:24+0200',
        'requires'    => [['core', '2.36']],
        'permissions' => '',
        'type'        => 'plugin',
        'settings'    => [],

        'details'    => 'https://open-time.net/?q=discreteCat',
        'support'    => 'https://github.com/franck-paul/discreteCat',
        'repository' => 'https://raw.githubusercontent.com/franck-paul/discreteCat/main/dcstore.xml',
        'license'    => 'gpl2',
    ]
);
