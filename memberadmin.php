<?php declare(strict_types=1);


use XoopsModules\Gamers\{
    Helper,
    Player
};
/** @var Helper $helper */

$GLOBALS['xoopsOption']['template_main'] = 'gamers_teamadmin.tpl';

require_once __DIR__ . '/header.php';

$op = $_GET['op'] ?? 'default';
$teamid = isset($_GET['teamid']) ? (int)$_GET['teamid'] : null;
if (isset($_POST)) {
    foreach ($_POST as $k => $v) {
        ${$k} = $v;
    }
}

if ($xoopsUser) {
    $teamHandler = Helper::getInstance()->getHandler('Team');

    $uid = $xoopsUser->getVar('uid');

    if (!isset($teamid)) {
        redirect_header('roster.php', 3, _MD_NOGAMERS_SELECTED);
    } else {
        $team = $teamHandler->get($teamid);
    }

    if ($team->isTeamAdmin($uid) || ($xoopsUser->isAdmin($xoopsModule->mid()))) {
        switch ($op) {
            case 'update':
            require XOOPS_ROOT_PATH . '/header.php';
            $rankerrors = 0;
            $statuserrors = 0;
            foreach ($user as $uid => $thisuser) {
                $uid = (int)$uid;

                $rank = (int)$thisuser['rank'];

                $oldrank = $thisuser['oldrank'];

                $status = (int)$thisuser['status'];

                $oldstatus = $thisuser['oldstatus'];

                if ($status != $oldstatus) {
                    $sql = 'UPDATE ' . $xoopsDB->prefix('gamers_teamstatus') . " SET status=$status WHERE uid=$uid AND teamid=$teamid";

                    if ($xoopsDB->query($sql)) {
                        if (!$xoopsDB->getAffectedRows()) {
                            $statuserrors++;
                        }
                    } else {
                        redirect_header('memberadmin.php?teamid=' . $teamid, 2, _MD_GAMERS_USERSTATUSNOTUPDATED);
                    }
                }

                if ($rank != $oldrank) {
                    $sql = 'UPDATE ' . $xoopsDB->prefix('gamers_teamstatus') . " SET rank=$rank WHERE uid=$uid AND teamid=$teamid";

                    if ($xoopsDB->query($sql)) {
                        if (!$xoopsDB->getAffectedRows()) {
                            $rankerrors++;
                        }
                    } else {
                        redirect_header('memberadmin.php?teamid=' . $teamid, 2, _MD_GAMERS_ERRORUSERNOTUPDATED);
                    }
                }
            }
            if (($statuserrors > 0) || ($rankerrors > 0)) {
                redirect_header('memberadmin.php?teamid=' . $teamid, 2, $statuserrors . ' ' . _MD_GAMERS_STATUSERRORS . '<br>' . $rankerrors . ' ' . _MD_GAMERS_RANKERRORS);
            } else {
                redirect_header('memberadmin.php?teamid=' . $teamid, 2, _MD_GAMERS_USERRANKUPDATED . '<br>' . _MD_GAMERS_USERSTATUSUPDATED);
            }
                 break;
           default:
            require_once XOOPS_ROOT_PATH . '/class/module.textsanitizer.php';

            require XOOPS_ROOT_PATH . '/header.php';
            $players = $team->getAllMembers();
            $ranks = $team->getRanks();
            foreach ($ranks as $rankid => $rank) {
                $allranks[$rankid] = $rank['rank'];
            }
            $statuses = getAllStatus();
            $count = 0;
            $layout = getLayout();
            foreach ($players as $key => $player) {
                $teamplayer[$key]['uname'] = $player['uname'];

                $teamplayer[$key]['uid'] = $player['uid'];

                $teamplayer[$key]['status'] = $statuses[$player['status']];

                $teamplayer[$key]['statusid'] = $player['status'];

                $teamplayer[$key]['rankcolor'] = $ranks[$player['rank']]['color']??'';

                $teamplayer[$key]['rank'] = $ranks[$player['rank']]['rank']??'';

                $teamplayer[$key]['rankid'] = $player['rank'];

                if ('Active' == $statuses[$player['status']]) {
                    $teamplayer[$key]['statuscolor'] = '00AA00';

                    $count++;
                } elseif ('Inactive' == $statuses[$player['status']]) {
                    $teamplayer[$key]['statuscolor'] = '006600';
                } elseif ('On Leave' == $statuses[$player['status']]) {
                    $teamplayer[$key]['statuscolor'] = 'bbbb10';
                }

                if (isset($class) && ('even' == $class)) {
                    $teamplayer[$key]['class'] = 'odd';

                    $class = 'odd';
                } else {
                    $teamplayer[$key]['class'] = 'even';

                    $class = 'even';
                }
            }
            $team->select();
            $xoopsTpl->assign('XOOPS_URL', XOOPS_URL);
            $xoopsTpl->assign('activecolor', $layout['color_perfect']);
            $xoopsTpl->assign('teammembers', $teamplayer);
            $xoopsTpl->assign('activecount', $count);
            $xoopsTpl->assign('totalcount', is_countable($players) ? count($players) : 0);
            $xoopsTpl->assign('teamid', $teamid);
            $xoopsTpl->assign('teamname', $team->getVar('teamname'));
            $xoopsTpl->assign('teamtype', $team->getVar('teamtype'));
            $xoopsTpl->assign('allstatus', $statuses);
            $xoopsTpl->assign('allranks', $allranks??'');
            $xoopsTpl->assign('lang_administrationof', _MD_GAMERS_ADMINISTRATIONOF);
            $xoopsTpl->assign('lang_teamplaying', _MD_GAMERS_PLAYING);
            $xoopsTpl->assign('lang_teamroster', _MD_GAMERS_ROSTER);
            $xoopsTpl->assign('lang_posoverview', _MD_GAMERS_POSOVERVIEW);
            $xoopsTpl->assign('lang_teammypos', _MD_GAMERS_MYPOS);
            $xoopsTpl->assign('lang_teamavailstats2', _MD_GAMERS_AVAILSTATS2);
            $xoopsTpl->assign('lang_teamnickname', _MD_GAMERS_NICKNAME);
            $xoopsTpl->assign('lang_teamrank', _MD_GAMERS_RANK);
            $xoopsTpl->assign('lang_teamstatus', _MD_GAMERS_STATUS);
            $xoopsTpl->assign('lang_teamtotalmembers', _MD_GAMERS_TOTALMEMBERS);
            $xoopsTpl->assign('lang_teamactiveplayers', _MD_GAMERS_ACTIVEPLAYERS);
            break;
          }
    } else {
        redirect_header('roster.php', 3, _MD_GAMERS_NOACCESSTOTHISGAMERS_);
    }
} else {
    redirect_header('index.php', 3, _MD_GAMERS_NOTLOGGEDIN);
}
require_once XOOPS_ROOT_PATH . '/footer.php';
