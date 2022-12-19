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

use XoopsModules\Gamers\{
    Helper
};

/**
 * @param string $img
 * @param array  $url
 * @param array  $rightlink
 */
function teamTableLink($img = '', $url = [], $rightlink = [])
{
    teamTableOpen();

    echo "<td><img src='" . $img . "'></td>";

    if ($rightlink) {
        foreach ($rightlink as $key => $link) {
            echo "<td align=right><a href='" . $link['url'] . "'>" . $link['text'] . '</td>';
        }
    }

    echo '</tr><tr>';

    echo '<td align=left>';

    foreach ($url as $key => $link) {
        if (isset($first)) {
            echo ' >> >> ';
        }

        if ($link['url']) {
            echo "<a href='" . $link['url'] . "'>";
        }

        echo $link['text'];

        if ($link['url']) {
            echo '</a>';
        }

        $first = 1;
    }

    echo '</td>';

    teamTableClose();

    teamTableOpen();
}

function teamTableOpen()
{
    echo "<table border='0' cellpadding='0' cellspacing='0' valign='top' width='100%'><tr><td>";

    echo "<tr><td><table width='100%' border='0' cellpadding='4' cellspacing='1'>";

    echo '<tr>';
}

function teamTableClose()
{
    echo '</tr></table></td></tr></table>';
}

/**
 * @param $nomembers
 * @param $members
 * @param $teamid
 * @param $op
 * @param $select
 * @param $lang
 */
function teamItemManage($nomembers, $members, $teamid, $op, $select, $lang)
{
    echo '<tr><th><b>' . $lang[0] . '</b></th><th align=center><b>' . $lang[1] . '</b></th><th><b>' . $lang[2] . '</b></th>';

    echo "</tr>\n";

    echo '<tr><td class="even"><form action="teamadmin.php" method="post">';

    echo '<select name="' . $select[0] . '[]" size="10" multiple="multiple">' . "\n";

    foreach ($nomembers as $member_id => $member_name) {
        echo '<option value="' . $member_id . '">' . $member_name . '</option>' . "\n";
    }

    echo '</select>';

    echo "</td><td align='center' class='odd'>
         <input type='hidden' name='op' value='" . $op[0] . "'>
		<input type='hidden' name='teamid' value='" . $teamid . "'>
		<input type='submit' name='submit' value='" . _AM_GAMERS_ADDBUTTON . "'>
		</form><br>
		<form action='teamadmin.php' method='post'>
		<input type='hidden' name='op' value='" . $op[1] . "'>
		<input type='hidden' name='teamid' value='" . $teamid . "'>
		<input type='submit' name='submit' value='" . _AM_GAMERS_DELBUTTON . "'>
		</td>
		<td class='even'>";

    echo "<select name='" . $select[1] . "[]' size='10' multiple='multiple'>";

    foreach ($members as $member_id => $member_name) {
        echo '<option value="' . $member_id . '">' . $member_name . '</option>' . "\n";
    }

    echo '</select>';

    echo '</form></td></tr>';
}

/**
 * @return mixed
 */
function getAllMembers()
{
    $memberHandler = xoops_getHandler('member');

    return $memberHandler->getUserList();
}

/**
 * @return mixed
 */
function getAllMaps()
{
    $mapHandler = Helper::getInstance()
                        ->getHandler('Map');

    return $mapHandler->getList();
}

/**
 * @return array
 */
function getAllPositions()
{
    global $xoopsDB;

    $sql = 'SELECT posid, posname FROM ' . $xoopsDB->prefix('gamers_positions') . " WHERE postype='Pos' ORDER BY posorder, posname ASC";

    $result = $xoopsDB->query($sql);

    if (!$xoopsDB->isResultSet($result)) {
        \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
    }

    $count = 0;

    $allpos = [];

    while (false !== ($row = $xoopsDB->fetchArray($result))) {
        $allpos[$row['posid']] = $row['posname'];

        $count++;
    }

    return $allpos;
}

/**
 * @return array
 */
function getAllSkills()
{
    global $xoopsDB;

    $sql = 'SELECT posid, posname FROM ' . $xoopsDB->prefix('gamers_positions') . " WHERE postype='Skill' ORDER BY posorder, posname ASC";

    $result = $xoopsDB->query($sql);

    if (!$xoopsDB->isResultSet($result)) {
        \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
    }

    $count = 0;

    $allskills = [];

    while (false !== ($row = $xoopsDB->fetchArray($result))) {
        $allskills[$row['posid']] = $row['posname'];

        $count++;
    }

    return $allskills;
}

/**
 * @return mixed
 */
function getAllServers()
{
    $allservers = [];
    global $xoopsDB;

    $sql = 'SELECT serverid, servername FROM ' . $xoopsDB->prefix('gamers_server') . ' ORDER BY servername ASC';

    $result = $xoopsDB->query($sql);

    if (!$xoopsDB->isResultSet($result)) {
        \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
    }

    $count = 0;

    while (false !== ($row = $xoopsDB->fetchArray($result))) {
        $allservers[$row['serverid']] = $row['servername'];

        $count++;
    }

    return $allservers;
}

/**
 * @return array
 */
function getAllTeamsizes()
{
    global $xoopsDB;

    $sql = 'SELECT sizeid, size FROM ' . $xoopsDB->prefix('gamers_sizes') . ' ORDER BY size';

    $result = $xoopsDB->query($sql);

    if (!$xoopsDB->isResultSet($result)) {
        \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
    }

    $teamsizes = [];

    while (false !== ($row = $xoopsDB->fetchArray($result))) {
        $teamsizes[$row['sizeid']] = $row['size'];
    }

    return $teamsizes;
}

/**
 * @return array
 */
function getAllTeamsides()
{
    global $xoopsDB;

    $sql = 'SELECT sideid, side FROM ' . $xoopsDB->prefix('gamers_sides') . ' ORDER BY side';

    $result = $xoopsDB->query($sql);

    if (!$xoopsDB->isResultSet($result)) {
        \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
    }

    $teamsides = [];

    while (false !== ($row = $xoopsDB->fetchArray($result))) {
        $teamsides[$row['sideid']] = $row['side'];
    }

    return $teamsides;
}

/**
 * @return array
 */
function getAllTeamranks()
{
    global $xoopsDB;

    $sql = 'SELECT rankid, rank FROM ' . $xoopsDB->prefix('gamers_rank') . ' ORDER BY rank';

    $result = $xoopsDB->query($sql);

    if (!$xoopsDB->isResultSet($result)) {
        \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
    }

    $teamranks = [];

    while (false !== ($row = $xoopsDB->fetchArray($result))) {
        $teamranks[$row['rankid']] = $row['rank'];
    }

    return $teamranks;
}
