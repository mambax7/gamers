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
if (isset($matchdate)) {
    $date = $matchdate;

    $op = _MD_GAMERS_EDITMATCH;

    $op_hidden = new XoopsFormHidden('op', 'editmatch');
} else {
    $curday = (int)date('d');

    $curmonth = (int)date('m');

    $curyear = (int)date('Y');

    $date = mktime(21, 0, 0, $curmonth, $curday, $curyear);

    $op = _MD_GAMERS_SUBMITMATCH;

    $op_hidden = new XoopsFormHidden('op', 'savematch');
}
$mform = new XoopsThemeForm($op . ' for ' . $team->getVar('teamname'), 'matchform', xoops_getenv('PHP_SELF'));
$uid_hidden = new XoopsFormHidden('uid', $xoopsUser->getVar('uid'));
$mid_hidden = new XoopsFormHidden('mid', $mid);
$team_hidden = new XoopsFormHidden('teamid', $teamid);

for ($i = 1; $i <= 31; ++$i) {
    $dayarray[$i] = $i;
}
$curday = date('d', $date);
$day_select = new XoopsFormSelect(_MD_GAMERS_DAYC, 'day', $curday);
$day_select->addOptionArray($dayarray);

for ($xmonth = 1; $xmonth < 13; $xmonth++) {
    $month = date('F', mktime(0, 0, 0, $xmonth, 0, 0));

    $monthno = date('n', mktime(0, 0, 0, $xmonth, 0, 0));

    $mvalue[$monthno] = $month;
}
$curmonth = date('n', $date);
$monthselect = new XoopsFormSelect(_MD_GAMERS_MONTHC, 'month', $curmonth);
$monthselect->addOptionArray($mvalue);

$curyear = date('Y', $date);
$xyear = $curyear;
for ($i = 1; $i < 6; ++$i) {
    $yvalue[$xyear] = $xyear;

    $xyear++;
}
$yearselect = new XoopsFormSelect(_MD_GAMERS_YEARC, 'year', $curyear);
$yearselect->addOptionArray($yvalue);

$clock = date('H:i', $date);
$clock = new XoopsFormText(_MD_GAMERS_TIMEC, 'time', 10, 10, $clock, 'E');
$button_tray = new XoopsFormElementTray('', '');
$button_tray->addElement(new XoopsFormButton('', 'save', _MD_GAMERS_NW_POST, 'submit'));

$teamsize ??= '';
$teamsize_select = new XoopsFormSelect(_MD_GAMERS_SIZE, 'teamsize', $teamsize);
foreach ($teamsizes as $size_id => $ts) {
    $teamsize_select->addOption($ts);
}
$opponent ??= '';
$opponent = new XoopsFormText(_MD_GAMERS_OPPONENT, 'opponent', 25, 25, $opponent, 'E');

$ladder ??= '';
$ladder_select = new XoopsFormSelect(_MD_GAMERS_MATCHTYPE, 'ladder', $ladder);
foreach ($teamladders as $ladder_id => $tl) {
    $ladder_select->addOption($tl);
}

/*
$ladder_select = new XoopsFormSelect(_MD_GAMERS_MATCHTYPE, 'ladder', $ladder);
$ladder_select->addOption("Ladder");
$ladder_select->addOption("Scrim");
$ladder_select->addOption("Practice");
*/
$matchresult ??= '';
$result_select = new XoopsFormSelect(_MD_GAMERS_MATCHRESULT, 'matchresult', $matchresult);
$result_select->addOption('Pending');
$result_select->addOption('Win');
$result_select->addOption('Loss');
$result_select->addOption('Draw');

$nummaps = $team->getVar('maps');
$matchmapHandler = Helper::getInstance()->getHandler('MatchMap');
$matchmaps = $matchmapHandler->getByMatchid($mid);
$teamsides = $team->getSides();
for ($mapno = 1; $mapno <= $nummaps; $mapno++) {
    if (_MD_GAMERS_EDITMATCH == $op) {
        $thismap = $matchmaps[$mapno] ?? $matchmapHandler->create();

        $our[$mapno] = new XoopsFormText(_MD_GAMERS_US, 'ourscore[]', 10, 10, $thismap->getVar('ourscore', 'E'));

        $their[$mapno] = new XoopsFormText(_MD_GAMERS_THEM, 'theirscore[]', 10, 10, $thismap->getVar('theirscore', 'E'));

        $mapid = $thismap->getVar('mapid');

        $thisside = $thismap->getVar('side');
    }

    $mapid ??= '';

    $map_select[$mapno] = new XoopsFormSelect(getCaption($mapno), 'map[]', $mapid);

    $thisside ??= '';

    $side[$mapno] = new XoopsFormSelect(_MD_GAMERS_SIDE, 'side[]', $thisside);

    foreach ($teamsides as $sideid => $sidename) {
        $side[$mapno]->addOption($sideid, $sidename);
    }
}

$teammaps = $team->getMaps();
for ($mapno = 1; $mapno <= $nummaps; $mapno++) {
    $map_select[$mapno]->addOption(0, _MD_GAMERS_UNDECIDED);

    foreach ($teammaps as $mapid => $mapname) {
        $map_select[$mapno]->addOption($mapid, $mapname);
    }
}
$server ??= '';
$server_select = new XoopsFormSelect(_MD_GAMERS_SERVER, 'server', $server);
$myserver = $team->getServers();
$server_select->addOption(0, _MD_GAMERS_CUSTOMSERVER);
foreach ($myserver as $serverid => $servername) {
    $server_select->addOption($serverid, $servername);
}

$customserver_label = new XoopsFormLabel('', _MD_GAMERS_MAYCHOOSECUSTOMSERVER);
$customserver ??= '';
$customserver_text = new XoopsFormText('', 'customserver', 40, 40, $customserver);
$review ??= '';
$review_tarea = new XoopsFormTextArea(_MD_GAMERS_MATCHREVIEW, 'review', $review);

$mform->addElement($uid_hidden);
$mform->addElement($mid_hidden);
$mform->addElement($op_hidden);
$mform->addElement($team_hidden);
$mform->addElement($day_select);
$mform->addElement($monthselect);
$mform->addElement($yearselect);
$mform->addElement($clock);
$mform->addElement($opponent);
$mform->addElement($teamsize_select);
$mform->addElement($ladder_select);
if (_MD_GAMERS_EDITMATCH == $op) {
    $mform->addElement($result_select);
}
for ($mapno = 1; $mapno <= $nummaps; $mapno++) {
    $mform->addElement($map_select[$mapno]);

    $mform->addElement($side[$mapno]);

    if (_MD_GAMERS_EDITMATCH == $op) {
        $mform->addElement($our[$mapno]);

        $mform->addElement($their[$mapno]);
    }
}
$mform->addElement($server_select);
$mform->addElement($customserver_label);
$mform->addElement($customserver_text);
$mform->addElement($review_tarea);
if ($mid > 0) {
    $screenshots_label = new XoopsFormLabel(_MD_GAMERS_SCREENSHOTS, "<a href=\"index.php?op=screenshotform&mid=$mid\">" . _MD_GAMERS_ADDSCREENSHOTS . '</a>');

    $mform->addElement($screenshots_label);
}
$mform->addElement($button_tray);
$mform->display();
