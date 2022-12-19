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

require dirname(__DIR__, 3) . '/include/cp_header.php';
require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/functions.php';
require_once __DIR__ . '/functions.php';

$op = $_GET['op'] ?? 'default';
$teamid = isset($_GET['teamid']) ? (int)$_GET['teamid'] : 'default';
if (isset($_POST)) {
    foreach ($_POST as $k => $v) {
        ${$k} = $v;
    }
}

xoops_cp_header();
$teamHandler        = Helper::getInstance()->getHandler('Team');
$team               = $teamHandler->get($teamid);
switch ($op) {
    case 'addmember':
         $success = 0;
         $failure = 0;
         foreach ($addteammembers as $member_id) {
             if ($team->addTeamMember((int)$member_id)) {
                 $success++;
             } else {
                 $failure++;
             }
         }
         $feedback = $success . ' ' . _AM_GAMERS_PLAYERSADDED . '<br>';
         if ($failure) {
             $feedback .= $failure . ' ' . _AM_GAMERS_PLAYERSNOTADDED . '';
         }
         redirect_header('teamadmin.php?teamid=' . $teamid, 3, $feedback);
         break;
    case 'delmember':
         $success = 0;
         $failure = 0;
         foreach ($removeteammembers as $teammember_id) {
             if ($team->delTeamMember($teammember_id)) {
                 $success++;
             } else {
                 $failure++;
             }
         }
         $feedback = $success . ' ' . _AM_GAMERS_PLAYERSREMOVED . '<br>';
         if ($failure) {
             $feedback .= $failure . ' ' . _AM_GAMERS_PLAYERSNOTREMOVED . '';
         }
         redirect_header('teamadmin.php?teamid=' . $teamid, 3, $feedback);
         break;
    case 'addmap':
         $success = 0;
         $failure = 0;
         foreach ($addteammaps as $map_id) {
             if ($team->addMap((int)$map_id)) {
                 $success++;
             } else {
                 $failure++;
             }
         }
         $feedback = $success . ' ' . _AM_GAMERS_MAPSADDED . '<br>';
         if ($failure) {
             $feedback .= $failure . ' ' . _AM_GAMERS_MAPSNOTADDED . '';
         }
         redirect_header('teamadmin.php?teamid=' . $teamid, 3, $feedback);
         break;
    case 'delmap':
         $success = 0;
         $failure = 0;
         foreach ($removeteammaps as $teammap_id) {
             if ($team->delMap($teammap_id)) {
                 $success++;
             } else {
                 $failure++;
             }
         }
         $feedback = $success . ' ' . _AM_GAMERS_MAPSREMOVED . '<br>';
         if ($failure) {
             $feedback .= $failure . ' ' . _AM_GAMERS_MAPSNOTREMOVED . '';
         }
         redirect_header('teamadmin.php?teamid=' . $teamid, 3, $feedback);
         break;
    case 'addserver':
         $success = 0;
         $failure = 0;
         foreach ($addteamservers as $server_id) {
             if ($team->addTeamServer((int)$server_id)) {
                 $success++;
             } else {
                 $failure++;
             }
         }
         $feedback = $success . ' ' . _AM_GAMERS_SERVERSADDED . '<br>';
         if ($failure) {
             $feedback .= $failure . ' ' . _AM_GAMERS_SERVERSNOTADDED . '';
         }
         redirect_header('teamadmin.php?teamid=' . $teamid, 3, $feedback);

         break;
    case 'delserver':
         $success = 0;
         $failure = 0;
         foreach ($removeteamserver as $teamserver_id) {
             if ($team->delTeamServer($teamserver_id)) {
                 $success++;
             } else {
                 $failure++;
             }
         }
         $feedback = $success . ' ' . _AM_GAMERS_SERVERSREMOVED . '<br>';
         if ($failure) {
             $feedback .= $failure . ' ' . _AM_GAMERS_SERVERSNOTREMOVED . '';
         }
         redirect_header('teamadmin.php?teamid=' . $teamid, 3, $feedback);
         break;
    case 'addsize':
         $success = 0;
         $failure = 0;
         foreach ($addteamsizes as $size_id) {
             if ($team->addTeamSize((int)$size_id)) {
                 $success++;
             } else {
                 $failure++;
             }
         }
         $feedback = $success . ' ' . _AM_GAMERS_SIZESADDED . '<br>';
         if ($failure) {
             $feedback .= $failure . ' ' . _AM_GAMERS_SIZESNOTADDED . '';
         }
         redirect_header('teamadmin.php?teamid=' . $teamid, 3, $feedback);
         break;
    case 'delsize':
         $success = 0;
         $failure = 0;
         foreach ($removeteamsizes as $size_id) {
             if ($team->delTeamSize($size_id)) {
                 $success++;
             } else {
                 $failure++;
             }
         }
         $feedback = $success . ' ' . _AM_GAMERS_SIZESREMOVED . '<br>';
         if ($failure) {
             $feedback .= $failure . ' ' . _AM_GAMERS_SIZESNOTREMOVED . '';
         }
         redirect_header('teamadmin.php?teamid=' . $teamid, 3, $feedback);
         break;
    case 'addside':
         $success = 0;
         $failure = 0;
         foreach ($addteamsides as $side_id) {
             if ($team->addTeamSide((int)$side_id)) {
                 $success++;
             } else {
                 $failure++;
             }
         }
         $feedback = $success . ' ' . _AM_GAMERS_SIDESADDED . '<br>';
         if ($failure) {
             $feedback .= $failure . ' ' . _AM_GAMERS_SIDESNOTADDED . '';
         }
         redirect_header('teamadmin.php?teamid=' . $teamid, 3, $feedback);
         break;
    case 'delside':
         $success = 0;
         $failure = 0;
         foreach ($removeteamsides as $side_id) {
             if ($team->delTeamSide($side_id)) {
                 $success++;
             } else {
                 $failure++;
             }
         }
         $feedback = $success . ' ' . _AM_GAMERS_SIDESREMOVED . '<br>';
         if ($failure) {
             $feedback .= $failure . ' ' . _AM_GAMERS_SIDESNOTREMOVED . '';
         }
         redirect_header('teamadmin.php?teamid=' . $teamid, 3, $feedback);
         break;
    case 'addrank':
         $success = 0;
         $failure = 0;
         foreach ($addteamranks as $rankid) {
             if ($team->addTeamRank((int)$rankid)) {
                 $success++;
             } else {
                 $failure++;
             }
         }
         $feedback = $success . ' ' . _AM_GAMERS_RANKSADDED . '<br>';
         if ($failure) {
             $feedback .= $failure . ' ' . _AM_GAMERS_RANKSNOTADDED . '';
         }
         redirect_header('teamadmin.php?teamid=' . $teamid, 3, $feedback);
         break;
    case 'delrank':
         $success = 0;
         $failure = 0;
         foreach ($removeteamranks as $rankid) {
             if ($team->delTeamRank($rankid)) {
                 $success++;
             } else {
                 $failure++;
             }
         }
         $feedback = $success . ' ' . _AM_GAMERS_RANKSREMOVED . '<br>';
         if ($failure) {
             $feedback .= $failure . ' ' . _AM_GAMERS_RANKSNOTREMOVED . '';
         }
         redirect_header('teamadmin.php?teamid=' . $teamid, 3, $feedback);
         break;
    case 'addpos':
         $success = 0;
         $failure = 0;
         foreach ($addteampos as $pos_id) {
             if ($team->addTeamPosition((int)$pos_id)) {
                 $success++;
             } else {
                 $failure++;
             }
         }
         $feedback = $success . ' ' . _AM_GAMERS_POSITIONSADDED . '<br>';
         if ($failure) {
             $feedback .= $failure . ' ' . _AM_GAMERS_POSITIONSNOTADDED . '';
         }
         redirect_header('teamadmin.php?teamid=' . $teamid, 3, $feedback);
         break;
    case 'delpos':
         $success = 0;
         $failure = 0;
         foreach ($removeteampos as $teampos_id) {
             if ($team->delTeamPosition($teampos_id)) {
                 $success++;
             } else {
                 $failure++;
             }
         }
         $feedback = $success . ' ' . _AM_GAMERS_POSITIONSREMOVED . '<br>';
         if ($failure) {
             $feedback .= $failure . ' ' . _AM_GAMERS_POSITIONSNOTREMOVED . '';
         }
         redirect_header('teamadmin.php?teamid=' . $teamid, 3, $feedback);
         break;
    case 'addskill':
         $success = 0;
         $failure = 0;
         foreach ($addteamskills as $pos_id) {
             if ($team->addTeamSkill((int)$pos_id)) {
                 $success++;
             } else {
                 $failure++;
             }
         }
         $feedback = $success . ' ' . _AM_GAMERS_POSITIONSADDED . '<br>';
         if ($failure) {
             $feedback .= $failure . ' ' . _AM_GAMERS_POSITIONSNOTADDED . '';
         }
         redirect_header('teamadmin.php?teamid=' . $teamid, 3, $feedback);
         break;
    case 'delskill':
         $success = 0;
         $failure = 0;
         foreach ($removeteamskills as $teampos_id) {
             if ($team->delTeamSkill($teampos_id)) {
                 $success++;
             } else {
                 $failure++;
             }
         }
         $feedback = $success . ' ' . _AM_GAMERS_POSITIONSREMOVED . '<br>';
         if ($failure) {
             $feedback .= $failure . ' ' . _AM_GAMERS_POSITIONSNOTREMOVED . '';
         }
         redirect_header('teamadmin.php?teamid=' . $teamid, 3, $feedback);
         break;
    case 'addladder':
         $success = 0;
         $failure = 0;
         foreach ($addteamladders as $ladder_id) {
             if ($team->addTeamLadder((int)$ladder_id)) {
                 $success++;
             } else {
                 $failure++;
             }
         }
         $feedback = $success . ' ' . _AM_GAMERS_LADDERSADDED . '<br>';
         if ($failure) {
             $feedback .= $failure . ' ' . _AM_GAMERS_LADDERSNOTADDED . '';
         }
         redirect_header('teamadmin.php?teamid=' . $teamid, 3, $feedback);
         break;
    case 'delladder':
         $success = 0;
         $failure = 0;
         foreach ($removeteamladders as $ladder_id) {
             if ($team->delTeamLadder($ladder_id)) {
                 $success++;
             } else {
                 $failure++;
             }
         }
         $feedback = $success . ' ' . _AM_GAMERS_LADDERSREMOVED . '<br>';
         if ($failure) {
             $feedback .= $failure . ' ' . _AM_GAMERS_LADDERSNOTREMOVED . '';
         }
         redirect_header('teamadmin.php?teamid=' . $teamid, 3, $feedback);
         break;
    case 'default':
//         $img = XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/manageteam.gif";
//         $url[0]["url"] = "index.php";
//         $url[0]["text"] = _AM_GAMERS_TEAM_CONFIG;
//         $url[1]["url"] = "index.php?op=teammanager";
//         $url[1]["text"] = _AM_GAMERS_TEAM_MNGR;
//         $url[2]["url"] = "";
//         $url[2]["text"] = _AM_GAMERS_EDITGAMERS_;
//         teamTableLink($img, $url);
         teamTableClose();
         teamTableOpen();
         echo '<td colspan=2>';
         require XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
         $tform = new \XoopsThemeForm('' . _AM_GAMERS_OPTIONSFOR . ' ' . $team->getVar('teamname'), 'editteam', 'index.php');
         $op_hidden = new \XoopsFormHidden('op', 'saveteam');
         $submit = new \XoopsFormButton('', 'submit', 'Edit', 'submit');
         $action_hidden = new \XoopsFormHidden('submit', 'Edit');
         $teamid_hidden = new \XoopsFormHidden('teamid', $teamid);
         $button_tray = new \XoopsFormElementTray('', '');
         $name = new \XoopsFormText(_AM_GAMERS_NAME, 'name', 20, 20, $team->getVar('teamname'), 'E');
         $type = new \XoopsFormText(_AM_GAMERS_TYPE, 'type', 20, 20, $team->getVar('teamtype'), 'E');
         $maps_select = new \XoopsFormSelect(_AM_GAMERS_MAPSPERMATCH, 'maps', $team->getVar('maps'));
         for ($i = 1; $i <= 5; ++$i) {
             $maps_select->addOption($i);
         }
         $button_tray->addElement($submit);
         $tform->addElement($name);
         $tform->addElement($type);
         $tform->addElement($maps_select);
         $tform->addElement($op_hidden);
         $tform->addElement($action_hidden);
         $tform->addElement($teamid_hidden);
         $tform->addElement($button_tray);
         $tform->display();
         $allmembers = getAllMembers();
         $members   = $team->getTeamMembers();
         $nomembers = array_diff($allmembers, $members);
         echo '</td></tr>';
         $select[0] = 'addteammembers';
         $select[1] = 'removeteammembers';
         $ops[0] = 'addmember';
         $ops[1] = 'delmember';
         $lang[0] = _AM_GAMERS_NONMEMBERS;
         $lang[1] = _AM_GAMERS_MEMBERADMIN;
         $lang[2] = _AM_GAMERS_TEAMMEMBERS;
         teamItemManage($nomembers, $members, $teamid, $ops, $select, $lang);

         $allmaps = getAllMaps();
         $maps      = $team->getMaps();
         $nomaps    = array_diff($allmaps, $maps);
         $select[0] = 'addteammaps';
         $select[1] = 'removeteammaps';
         $ops[0] = 'addmap';
         $ops[1] = 'delmap';
         $lang[0] = _AM_GAMERS_NONSELECTED;
         $lang[1] = _AM_GAMERS_MAPSELECTION;
         $lang[2] = _AM_GAMERS_TEAM_MAPS;
         teamItemManage($nomaps, $maps, $teamid, $ops, $select, $lang);

         $allpos = getAllPositions();
         $pos       = $team->getPositions();
         $nopos     = array_diff($allpos, $pos);
         $select[0] = 'addteampos';
         $select[1] = 'removeteampos';
         $ops[0] = 'addpos';
         $ops[1] = 'delpos';
         $lang[0] = _AM_GAMERS_NONSELECTED;
         $lang[1] = _AM_GAMERS_POSITIONSELECTION;
         $lang[2] = _AM_GAMERS_SELECTED;
         teamItemManage($nopos, $pos, $teamid, $ops, $select, $lang);

         $allskills = getAllSkills();
         $skills    = $team->getSkills();
         $noskills  = array_diff($allskills, $skills);
         $select[0] = 'addteamskills';
         $select[1] = 'removeteamskills';
         $ops[0] = 'addskill';
         $ops[1] = 'delskill';
         $lang[0] = _AM_GAMERS_NONSELECTED;
         $lang[1] = _AM_GAMERS_POSITIONSKILLSELECTION;
         $lang[2] = _AM_GAMERS_SELECTED;
         teamItemManage($noskills, $skills, $teamid, $ops, $select, $lang);

         $allservers = getAllServers();
         $servers   = $team->getServers();
         $noservers = array_diff($allservers??[], $servers);
         $select[0] = 'addteamservers';
         $select[1] = 'removeteamservers';
         $ops[0] = 'addserver';
         $ops[1] = 'delserver';
         $lang[0] = _AM_GAMERS_NONSELECTED;
         $lang[1] = _AM_GAMERS_SERVERSELECTION;
         $lang[2] = _AM_GAMERS_SELECTED;
         teamItemManage($noservers, $servers, $teamid, $ops, $select, $lang);

         $allsizes = getAllTeamsizes();
         $teamsizes = $team->getTeamSizes();
         $nosizes   = array_diff($allsizes, $teamsizes);
         $select[0] = 'addteamsizes';
         $select[1] = 'removeteamsizes';
         $ops[0] = 'addsize';
         $ops[1] = 'delsize';
         $lang[0] = _AM_GAMERS_NONSELECTED;
         $lang[1] = _AM_GAMERS_SIZESELECTION;
         $lang[2] = _AM_GAMERS_SELECTED;
         teamItemManage($nosizes, $teamsizes, $teamid, $ops, $select, $lang);

         $allsides = getAllTeamsides();
         $teamsides = $team->getSides();
         $nosides   = array_diff($allsides, $teamsides);
         $select[0] = 'addteamsides';
         $select[1] = 'removeteamsides';
         $ops[0] = 'addside';
         $ops[1] = 'delside';
         $lang[0] = _AM_GAMERS_NONSELECTED;
         $lang[1] = _AM_GAMERS_SIDESELECTION;
         $lang[2] = _AM_GAMERS_SELECTED;
         teamItemManage($nosides, $teamsides, $teamid, $ops, $select, $lang);

         $allranks = getAllTeamranks();
         $teamranks = $team->getRanks($teamid);
         $ranks = [];
         foreach ($teamranks as $rankid => $rank) {
             $ranks[$rankid] = $rank['rank'];
         }
         $noranks     = array_diff($allranks, $ranks);
         $select[0]   = 'addteamranks';
         $select[1] = 'removeteamranks';
         $ops[0] = 'addrank';
         $ops[1] = 'delrank';
         $lang[0] = _AM_GAMERS_NONSELECTED;
         $lang[1] = _AM_GAMERS_RANKSELECTION;
         $lang[2] = _AM_GAMERS_SELECTED;
         teamItemManage($noranks, $ranks, $teamid, $ops, $select, $lang);

         $allladders = getAllLadders();
         foreach ($allladders as $ladderid => $ladder) {
             $all[$ladderid] = $ladder['ladder'];
         }
         $allladders = $all;
         $teamladders = $team->getLadders();
         $noladders   = array_diff($allladders, $teamladders);
         $select[0]   = 'addteamladders';
         $select[1] = 'removeteamladders';
         $ops[0] = 'addladder';
         $ops[1] = 'delladder';
         $lang[0] = _AM_GAMERS_NONSELECTED;
         $lang[1] = _AM_GAMERS_LADDERSELECTION;
         $lang[2] = _AM_GAMERS_SELECTED;
         teamItemManage($noladders, $teamladders, $teamid, $ops, $select, $lang);

         teamTableClose();
         break;
}

xoops_cp_footer();
