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

use XoopsModules\Gamers\{
    Helper
};
/** @var Helper $helper */

if (!defined('XOOPS_ROOT_PATH')) {
    die('Xoops root path not defined');
}
require XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
$mform = new \XoopsThemeForm(_MD_GAMERS_SCREENSHOTS, 'screenshotform', xoops_getenv('PHP_SELF'));
$uid_hidden = new \XoopsFormHidden('uid', $xoopsUser->getVar('uid'));
$mid_hidden = new \XoopsFormHidden('mid', $mid);

$matchmapHandler = Helper::getInstance()->getHandler('MatchMap');

// Output list with maps for selected match
echo "<table width='100%' border='0' cellspacing='1' class='outer'><tr><th>" . _MD_GAMERS_MAPNAME . '</th><th>' . _MD_GAMERS_SIDENAME . '</th><th>' . _MD_GAMERS_SCREENSHOTNAME . '</th><th>' . _MD_GAMERS_EDIT . '</th></tr>';
for ($mapno = 1; $mapno <= $nummaps; $mapno++) {
    if (isset($class) && ('even' == $class)) {
        $class = 'odd';
    } else {
        $class = 'even';
    }

    echo "<tr class=\"$class\">";

    $thismap = $matchmapHandler->getByMatchid($mid, $mapno);

    echo '<td>' . (is_object($thismap->map) ? $thismap->map->getVar('mapname') : '??') . '</td>';

    echo '<td>' . getSide($thismap->getVar('side')) . '</td>';

    if (mb_strlen($thismap->getVar('screenshot')) > 0) {
        echo '<td>' . $thismap->getVar('screenshot') . '<br><img src="'. XOOPS_UPLOAD_URL . '/' . $moduleDirName . '/screenshots/thumbs/' . $thismap->getVar('screenshot') . '" alt="" border="0"></td>';

        echo '<td><a href="index.php?op=deletescreenshot&matchmapid=' . $thismap->getVar('matchmapid') . '">' . _MD_GAMERS_DELETE . '</a></td>';
    } else {
        echo '<td>&nbsp;</td>';

        echo "<td><a href=\"index.php?op=screenshotform&action=add&mid=$mid&matchmapid=" . $thismap->getVar('matchmapid') . '">' . _MD_GAMERS_ADD . '</a></td>';
    }
}
echo '</table>';
