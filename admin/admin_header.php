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
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author       Mithrandir, Mamba, XOOPS Development Team
 */

use Xmf\Module\Admin;
use XoopsModules\Gamers\{
    Helper
};

/** @var Admin $adminObject */
/** @var Helper $helper */

$path = dirname(__DIR__, 3);
require dirname(__DIR__) . '/preloads/autoloader.php';

require dirname(__DIR__, 3) . '/include/cp_header.php';
//require dirname(__DIR__, 3) . '/class/xoopsformloader.php';
require dirname(__DIR__) . '/include/common.php';

global $xoopsModule;

$thisModuleDir = $GLOBALS['xoopsModule']->getVar('dirname');

$moduleDirName      = \basename(\dirname(__DIR__));
$moduleDirNameUpper = mb_strtoupper($moduleDirName);

$adminObject = Admin::getInstance();

$helper = Helper::getInstance();
// Load language files
$helper->loadLanguage('admin');
$helper->loadLanguage('modinfo');
$helper->loadLanguage('common');

$pathIcon16 = Admin::iconUrl('', '16');
$pathIcon32 = Admin::iconUrl('', '32');

//$pathIcon16 = '../' . $xoopsModule->getInfo('icons16');
//$pathIcon32 = '../' . $xoopsModule->getInfo('icons32');
//$pathModuleAdmin = $xoopsModule->getInfo('dirmoduleadmin');
