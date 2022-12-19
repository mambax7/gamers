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

xoops_loadLanguage('main', 'gamers');

/**
 * @return array
 */
function sh_navmenu()
{
    if (!xoops_isActiveModule('gamers')) {
        return [];
    }

    if (!class_exists(Helper::class)) {
        return [];
    }

    $helper = Helper::getInstance();

    global $xoopsDB, $xoopsUser;

    if (is_object($xoopsUser)) {
        $uid = $xoopsUser->getVar('uid');
    } else {
        $uid = 0;
    }

    $block = [];

    $block['title'] = _BL_GAMERS_MENU;

    $block['content'] = "<table border='0' cellspacing='1'><tr><td id='mainmenu'>";

    $teamHandler = $helper->getHandler('Team');

    $criteria = new \CriteriaCompo();

    $criteria->setSort('defteam DESC, teamname');

    $teams = $teamHandler->getObjects($criteria, false, false);

    foreach ($teams as $myrow) {
        $teamid = $myrow['teamid'];

        $teamname = $myrow['teamname'];

        $teamtype = $myrow['teamtype'];

        if (isset($counter)) {
            $class = 'menuMain';
        } else {
            $class = 'menuTop';
        }

        $block['content'] .= "<a class='" . $class . "' href='" . XOOPS_URL . '/modules/gamers/index.php?teamid=' . $teamid . "' target='_self'>" . $teamname . '</a><br>';

        $block['content'] .= "<a class='menuSub' href='" . XOOPS_URL . '/modules/gamers/index.php?teamid=' . $teamid . "' target='_self'>" . _BL_GAMERS_MATCHES . '</a><br>';

        $block['content'] .= "<a class='menuSub' href='" . XOOPS_URL . '/modules/gamers/roster.php?teamid=' . $teamid . "' target='_self'>" . _BL_GAMERS_ROSTER . '</a><br>';

        $sql = 'SELECT rank FROM ' . $xoopsDB->prefix('gamers_teamstatus') . " WHERE teamid=$teamid AND uid=$uid";

        $result = $xoopsDB->query($sql);

        if (!$xoopsDB->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
        }

        /** @var array $teammember */
        $teammember = $xoopsDB->fetchArray($result);

        if ($teammember && null != $teammember['rank']) {
            $allow = true;
        } else {
            $allow = false;
        }

        if ($allow) {
            $block['content'] .= "<a class='menuSub' href='" . XOOPS_URL . '/modules/gamers/tactics.php?teamid=' . $teamid . "' target='_self'>" . _BL_GAMERS_TACTICS . '</a><br>';
        }
    }

    $block['content'] .= '</td></tr></table>';

    return $block;
}
