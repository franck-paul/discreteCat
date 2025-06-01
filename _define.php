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
$this->registerModule(
    'Discrete category',
    'Exclude a category from Home and RSS/Atom feed',
    'Franck Paul',
    '5.4',
    [
        'date'        => '2025-06-01T08:08:30+0200',
        'requires'    => [['core', '2.28']],
        'permissions' => '',
        'type'        => 'plugin',
        'settings'    => [],

        'details'    => 'https://open-time.net/?q=discreteCat',
        'support'    => 'https://github.com/franck-paul/discreteCat',
        'repository' => 'https://raw.githubusercontent.com/franck-paul/discreteCat/main/dcstore.xml',
        'license'    => 'gpl2',
    ]
);
