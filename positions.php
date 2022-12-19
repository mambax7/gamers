<?php declare(strict_types=1);

use XoopsModules\Gamers\{
    Helper,
    Player
};

/** @var Helper $helper */

$GLOBALS['xoopsOption']['template_main'] = 'gamers_positions.tpl';

require_once __DIR__ . '/header.php';

$teamid = isset($_GET['teamid']) ? (int)$_GET['teamid'] : null;
$mid    = isset($_GET['mid']) ? (int)$_GET['mid'] : null;
if (isset($_POST)) {
    foreach ($_POST as $k => $v) {
        ${$k} = $v;
    }
}
if ($xoopsUser) {
    $teamHandler = Helper::getInstance()
                         ->getHandler('Team');

    $uid = $xoopsUser->getVar('uid');

    require_once XOOPS_ROOT_PATH . '/class/module.textsanitizer.php';

    if (isset($mid)) {
        $matchHandler = Helper::getInstance()
                              ->getHandler('Match');

        $match = $matchHandler->get($mid);

        $teamid = $match->getVar('teamid');

        $team = $teamHandler->get($teamid);

        if (!$team->isTeamMember($uid)) {
            redirect_header('index.php', 3, _MD_GAMERS_ACCESSDENY);

            exit();
        }

        $maps = $match->getMatchMaps();

        foreach ($maps as $mapno => $thismap) {
            $map[$mapno]['caption'] = getCaption($mapno);

            $map[$mapno]['name'] = is_object($thismap->map) ? $thismap->map->getVar('mapname') : '??';
        }

        $xoopsTpl->assign('maps', $map);

        if ($available = $match->getPositions('Yes')) {
            $team->positions(_MD_GAMERS_AVAILABLE, $available);
        }

        if ($latepos = $match->getPositions('LateYes')) {
            $team->positions(_MD_GAMERS_LATEPOSITIVE, $latepos);
        }

        if ($subs = $match->getPositions('Sub')) {
            $team->positions(_MD_GAMERS_SUBSTITUTES, $subs);
        }

        $xoopsTpl->assign('opponent', $match->getVar('opponent'));

        $xoopsTpl->assign('match', 1);

        $xoopsTpl->assign('mid', $mid);

        $xoopsTpl->assign('teamid', $teamid);
    } else {
        if (!isset($teamid)) {
            $teamid = getDefaultTeam();
        }

        $team = $teamHandler->get($teamid);

        $team->select();

        if (!$team->isTeamMember($uid)) {
            redirect_header('index.php', 3, _MD_GAMERS_SORRYRESTRICTEDAREA);
        } else {
            $players = $team->getPlayerPositions();

            $team->positions($team->getVar('teamname'), $players);

            $xoopsTpl->assign('teamid', $teamid);
        }
    }

    if ($team->isTeamAdmin($uid)) {
        $xoopsTpl->assign('admin', 'Yes');
    }

    $xoopsTpl->assign('allranks', getAllRanks());

    $xoopsTpl->assign('teamname', $team->getVar('teamname'));

    $xoopsTpl->assign('lang_teamnickname', _MD_GAMERS_NICKNAME);

    $xoopsTpl->assign('lang_teamversus', _MD_GAMERS_VERSUS);

    $xoopsTpl->assign('lang_teammatchlist', _MD_GAMERS_MATCHLIST);

    $xoopsTpl->assign('lang_teammatchdetails', _MD_GAMERS_MATCHDETAILS);

    $xoopsTpl->assign('lang_teammatchavailability', _MD_GAMERS_MATCHAVAILABILITY);

    $xoopsTpl->assign('lang_teamadmin', _MD_GAMERS_ADMIN);

    $xoopsTpl->assign('lang_teammatchlist', _MD_GAMERS_MATCHLIST);

    $xoopsTpl->assign('lang_teamroster', _MD_GAMERS_ROSTER);

    $xoopsTpl->assign('lang_teammypos', _MD_GAMERS_MYPOS);

    $xoopsTpl->assign('lang_teamavailstats2', _MD_GAMERS_AVAILSTATS2);

    $xoopsTpl->assign('lang_teamprimaryposition', _MD_GAMERS_PRIMARYPOSITION);

    $xoopsTpl->assign('lang_teamsecondary', _MD_GAMERS_SECONDARY);

    $xoopsTpl->assign('lang_teamtertiary', _MD_GAMERS_TERTIARY);

    $xoopsTpl->assign('lang_teamfirstpos', _MD_GAMERS_FIRSTPOS);

    $xoopsTpl->assign('lang_teamsecondpos', _MD_GAMERS_SECONDPOS);

    $xoopsTpl->assign('lang_teamthirdpos', _MD_GAMERS_THIRDPOS);
} else {
    redirect_header('index.php', 3, _MD_GAMERS_SORRYRESTRICTEDAREA);
}
require XOOPS_ROOT_PATH . '/footer.php';
