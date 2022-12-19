<?php declare(strict_types=1);

/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author       Mithrandir, Mamba, XOOPS Development Team
 */

use Xmf\Module\Admin;
use XoopsModules\Gamers\{
    Helper
};

/** @var Admin $adminObject */
/** @var Helper $helper */

include dirname(__DIR__) . '/preloads/autoloader.php';

$moduleDirName      = \basename(\dirname(__DIR__));
$moduleDirNameUpper = mb_strtoupper($moduleDirName);

$helper = Helper::getInstance();
$helper->loadLanguage('common');

$pathIcon32    = Admin::menuIconPath('');
$pathModIcon32 = XOOPS_URL . '/modules/' . $moduleDirName . '/assets/images/icons/32/';
if (is_object($helper->getModule())
    && false !== $helper->getModule()
                        ->getInfo('modicons32')) {
    $pathModIcon32 = $helper->url(
        $helper->getModule()
               ->getInfo('modicons32')
    );
}

//$dirname = basename(dirname(__DIR__));
//$moduleHandler = xoops_getHandler('module');
//$module = $moduleHandler->getByDirname($dirname);
//
//$pathIcon32 = Admin::menuIconPath('');
//
//xoops_loadLanguage('main', $dirname);

$adminmenu = [];

$adminmenu[] = [
    'title' => _MI_GAMERS_ADMENU0,
    'link'  => 'admin/index.php',
    'icon'  => $pathIcon32 . '/home.png',
];

//$adminmenu[] = [
//'title' => _MI_OLEDRION_ADMENU10,
//'link' => "admin/main.php",
//$adminmenu[$i]["icon"]  = $pathIcon32 . '/manage.png';
//];

$adminmenu[] = [
    'title' => _MI_GAMERS_ADMENU6,
    'link'  => 'admin/main.php?op=teammanager',
    'icon'  => $pathIcon32 . '/cash_stack.png',
];

$adminmenu[] = [
    'title' => _MI_GAMERS_ADMENU2,
    'link'  => 'admin/main.php?op=matchmanager',
    'icon'  => $pathIcon32 . '/button_ok.png',
];

$adminmenu[] = [
    'title' => _MI_GAMERS_ADMENU11,
    'link'  => 'admin/main.php?op=layoutmanager',
    'icon'  => $pathIcon32 . '/type.png',
];

$adminmenu[] = [
    'title' => _MI_GAMERS_ADMENU10,
    'link'  => 'admin/main.php?op=rankmanager',
    'icon'  => $pathIcon32 . '/extention.png',
];

$adminmenu[] = [
    'title' => _MI_GAMERS_ADMENU4,
    'link'  => 'admin/main.php?op=mappoolmanager',
    'icon'  => $pathIcon32 . '/globe.png',
];

$adminmenu[] = [
    'title' => _MI_GAMERS_ADMENU3,
    'link'  => 'admin/main.php?op=positionmanager',
    'icon'  => $pathIcon32 . '/insert_table_row.png',
];

$adminmenu[] = [
    'title' => _MI_GAMERS_ADMENU8,
    'link'  => 'admin/main.php?op=sizemanager',
    'icon'  => $pathIcon32 . '/discount.png',
];

$adminmenu[] = [
    'title' => _MI_GAMERS_ADMENU9,
    'link'  => 'admin/main.php?op=sidemanager',
    'icon'  => $pathIcon32 . '/groupmod.png',
];

$adminmenu[] = [
    'title' => _MI_GAMERS_ADMENU7,
    'link'  => 'admin/main.php?op=servermanager',
    'icon'  => $pathIcon32 . '/exec.png',
];

$adminmenu[] = [
    'title' => _MI_GAMERS_ADMENU12,
    'link'  => 'admin/main.php?op=laddermanager',
    'icon'  => $pathIcon32 . '/stats.png',
];

// Blocks Admin
$adminmenu[] = [
    'title' => constant('CO_' . $moduleDirNameUpper . '_' . 'BLOCKS'),
    'link'  => 'admin/blocksadmin.php',
    'icon'  => $pathIcon32 . '/block.png',
];

if (is_object($helper->getModule()) && $helper->getConfig('displayDeveloperTools')) {
    $adminmenu[] = [
        'title' => constant('CO_' . $moduleDirNameUpper . '_' . 'ADMENU_MIGRATE'),
        'link'  => 'admin/migrate.php',
        'icon'  => $pathIcon32 . '/database_go.png',
    ];
}

//Clone
$adminmenu[] = [
    'title' => _CLONE,
    'link'  => 'admin/clone.php',
    'icon'  => $pathIcon32 . '/page_copy.png',
];

$adminmenu[] = [
    'title' => _AM_GAMERS_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32 . '/about.png',
];
