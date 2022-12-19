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

/**
 * @param $category
 * @param $item_id
 * @return false
 */
function team_notify_iteminfo($category, $item_id)
{
    $item = [];
    $pathparts = explode('/', __DIR__);

    $moduleDirName = $pathparts[array_search('modules', $pathparts, true) + 1];

    $item_id = (int)$item_id;

    if ('global' == $category) {
        $item['name'] = '';

        $item['url'] = '';

        return $item;
    }

    global $xoopsDB;

    if ('team' == $category) {
        // Assume we have a valid team id

        $teamHandler = Helper::getInstance()->getHandler('Team');

        $team = $teamHandler->get($item_id);

        if ($team->isNew()) {
            return false;
        }

        $item['name'] = $team->getVar('teamname');

        $item['url'] = XOOPS_URL . '/modules/' . $moduleDirName . '/index.php?teamid=' . $item_id;

        return $item;
    }

    if ('match' == $category) {
        // Assume we have a valid team id

        $sql = 'SELECT t.teamname, m.opponent FROM ' . $xoopsDB->prefix('gamers_matches') . ' m, ' . $xoopsDB->prefix('gamers_team') . ' t WHERE t.teamid = m.teamid AND m.matchid = ' . $item_id . ' limit 1';

        $result = $xoopsDB->query($sql); // TODO: error check

        if (!$xoopsDB->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
        }

        $result_array = $xoopsDB->fetchArray($result);

        $item['name'] = $result_array['teamname'] . ' vs. ' . $result_array['opponent'];

        $item['url'] = XOOPS_URL . '/modules/' . $moduleDirName . '/matchdetails.php?mid=' . $item_id;

        return $item;
    }
}
