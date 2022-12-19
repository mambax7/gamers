<?php declare(strict_types=1);

use XoopsModules\Gamers\{
    Helper,
    Player
};

/** @var Helper $helper */

require_once __DIR__ . '/header.php';
//require_once __DIR__ . '/functions.php';

//require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/functions.php';

$teamid                                  = isset($_GET['teamid']) ? (int)$_GET['teamid'] : getDefaultTeam();
$teamHandler                             = Helper::getInstance()
                                                 ->getHandler('Team');
$team                                    = $teamHandler->get($teamid);
$GLOBALS['xoopsOption']['template_main'] = 'gamers_roster.tpl';
require XOOPS_ROOT_PATH . '/header.php';
if ($xoopsUser) {
    $uid = $xoopsUser->getVar('uid');

    if ($team->isTeamAdmin($uid)) {
        $xoopsTpl->assign('admin', 'Yes');
    }

    if ($team->isTeamMember($uid)) {
        $xoopsTpl->assign('teammember', 'Yes');
    }
}
$teamplayer = [];
$players    = $team->getAllMembers();
$count      = 0;
$ranks      = getAllRanks();
$statuses   = getAllStatus();
$positions  = getAllShort();
$layout     = getLayout();
foreach ($players as $key => $player) {
    $teamplayer[$key]['uname'] = $player['uname'];

    $teamplayer[$key]['user_from'] = $player['user_from'];

    $teamplayer[$key]['uid'] = $player['uid'];

    $teamplayer[$key]['status'] = $statuses[$player['status']];

    $teamplayer[$key]['bio'] = $player['bio'];

    $teamplayer[$key]['rankcolor'] = $ranks[$player['rank']]['color'];

    $teamplayer[$key]['rank'] = $ranks[$player['rank']]['rank'];

    $avatarpath = XOOPS_URL . '/uploads/' . $player['user_avatar'];

    $teamplayer[$key]['avatar'] = $player['user_avatar'];

    if ('Active' == $statuses[$player['status']]) {
        $teamplayer[$key]['statuscolor'] = $layout['color_status_active'];

        $count++;
    } elseif ('Inactive' == $statuses[$player['status']]) {
        $teamplayer[$key]['statuscolor'] = $layout['color_status_inactive'];
    } elseif ('On Leave' == $statuses[$player['status']]) {
        $teamplayer[$key]['statuscolor'] = $layout['color_status_onleave'];
    }

    $teamplayer[$key]['JoinedDate'] = date(_SHORTDATESTRING, $player['user_regdate']);

    if (isset($class) && ('even' == $class)) {
        $teamplayer[$key]['class'] = 'odd';
    } else {
        $teamplayer[$key]['class'] = 'even';
    }

    if (isset($player['primarypos'])) {
        $playerpositions = $positions[$player['primarypos']] . ', ' . $positions[$player['secondarypos']];

        if (isset($positions[$player['tertiarypos']]) && ('-None-' != $positions[$player['tertiarypos']])) {
            $playerpositions .= ', ' . $positions[$player['tertiarypos']];
        }

        $teamplayer[$key]['positions'] = $playerpositions;
    }

    if ((null != $player['user_from']) && (file_exists('assets/images/flags/' . $player['user_from'] . '.gif'))) {
        $teamplayer[$key]['flag'] = 'Yes';
    } elseif (null != $player['user_from']) {
        $teamplayer[$key]['flag'] = 'No';
    }
}
$team->select();
$xoopsTpl->assign('goodcolor', $layout['color_perfect']);
$xoopsTpl->assign('XOOPS_URL', XOOPS_URL);
$xoopsTpl->assign('players', $teamplayer);
$xoopsTpl->assign('actives', $count);
$xoopsTpl->assign('count', is_countable($players) ? count($players) : 0);
$xoopsTpl->assign('teamid', $teamid);
$xoopsTpl->assign('teamname', $team->getVar('teamname'));
$xoopsTpl->assign('teamtype', $team->getVar('teamtype'));
$xoopsTpl->assign('lang_teamrosterfor', _MD_GAMERS_ROSTERFOR);
$xoopsTpl->assign('lang_teamplaying', _MD_GAMERS_PLAYING);
$xoopsTpl->assign('lang_teamadmin', _MD_GAMERS_ADMIN);
$xoopsTpl->assign('lang_teamposoverview', _MD_GAMERS_POSOVERVIEW);
$xoopsTpl->assign('lang_teammypos', _MD_GAMERS_MYPOS);
$xoopsTpl->assign('lang_teamavailstats2', _MD_GAMERS_AVAILSTATS2);
$xoopsTpl->assign('lang_teamnickname', _MD_GAMERS_NICKNAME);
$xoopsTpl->assign('lang_teamnationality', _MD_GAMERS_NATIONALITY);
$xoopsTpl->assign('lang_teamrank', _MD_GAMERS_RANK);
$xoopsTpl->assign('lang_teamposition', _MD_GAMERS_POSITION);
$xoopsTpl->assign('lang_teammembersince', _MD_GAMERS_MEMBERSINCE);
$xoopsTpl->assign('lang_teamstatus', _MD_GAMERS_STATUS);
$xoopsTpl->assign('lang_teamtotalmembers', _MD_GAMERS_TOTALMEMBERS);
$xoopsTpl->assign('lang_teamactiveplayers', _MD_GAMERS_ACTIVEPLAYERS);
$xoopsTpl->assign('teamversion', $xoopsModule->getVar('version'));
require_once XOOPS_ROOT_PATH . '/footer.php';
