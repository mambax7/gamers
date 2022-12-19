<?php declare(strict_types=1);

use XoopsModules\Gamers\{
    Helper
};

/** @var Helper $helper */

$GLOBALS['xoopsOption']['template_main'] = 'gamers_avstats.tpl';

require_once __DIR__ . '/header.php';

global $xoopsUser, $xoopsDB;

$teamid = isset($_GET['teamid']) ? (int)$_GET['teamid'] : null;
if ($xoopsUser) {
    if (!isset($teamid)) {
        $teamid = getDefaultTeam();
    }

    require_once XOOPS_ROOT_PATH . '/class/module.textsanitizer.php';

    $teamHandler = Helper::getInstance()
                         ->getHandler('Team');

    $team = $teamHandler->get($teamid);

    if (!$team->isTeamMember($xoopsUser->getVar('uid'))) {
        redirect_header('roster.php?teamid=' . $teamid, 3, _MD_GAMERS_ACCESSDENY);

        exit();
    }

    if (isset($limit)) {
        $limitstr = 'LIMIT 0, ' . $limit;
    } else {
        $limitstr = '';
    }

    $statuses = getAllStatus();

    $members = $team->getAllMembers();

    $player = [];

    $thislayout = getLayout();

    foreach ($members as $key => $member) {
        $status = $statuses[$member['status']];

        if ('Active' === $status) {
            $player[$key]['statuscolor'] = $thislayout['color_perfect'];
        } elseif ('Inactive' === $status) {
            $player[$key]['statuscolor'] = $thislayout['color_warn'];
        } elseif ('On Leave' === $status) {
            $player[$key]['statuscolor'] = $thislayout['color_good'];
        } else {
            $player[$key]['statuscolor'] = $thislayout['color_bad'];
        }

        $player[$key]['uname'] = $member['uname'];

        $player[$key]['uid'] = $member['uid'];

        $avcolor      = '';
        $navcolor     = '';
        $subcolor     = '';
        $noreplycolor = '';

        $av      = 0;
        $avperc  = 0;
        $noreply = 0;
        $nav     = 0;
        $sub     = 0;
        $count   = 0;
        $total   = 0;

        $sql = 'SELECT a.availability FROM ' . $xoopsDB->prefix('gamers_availability') . ' a, ' . $xoopsDB->prefix('gamers_matches') . ' m WHERE m.teamid=' . $teamid . ' AND a.matchid=m.matchid AND a.userid=' . $member['uid'] . " AND m.matchresult<>'Pending' ORDER BY a.matchid DESC " . $limitstr;

        $result = $xoopsDB->query($sql);

        if (!$xoopsDB->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
        }

        while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
            $total++;

            $myav = $myrow['availability'];

            if (('Yes' === $myav) || ('LateYes' === $myav)) {
                $av++;
            } elseif ('Not Set' === $myav) {
                $noreply++;
            } elseif (('No' === $myav) || ('LateNo' === $myav)) {
                $nav++;
            } elseif ('Sub' === $myav) {
                $sub++;
            }
        }

        if (0 !== $total) {
            $avperc = $av / $total * 100;

            $avperc = number_format($avperc, 2);

            if ($avperc > 66) {
                $avcolor = $thislayout['color_perfect'];
            } elseif ($avperc > 50) {
                $avcolor = $thislayout['color_good'];
            } elseif ($avperc > 33) {
                $avcolor = $thislayout['color_warn'];
            } else {
                $avcolor = $thislayout['color_bad'];
            }

            $noreply = $noreply / $total * 100;

            $noreply = number_format($noreply, 2);

            if ($noreply > 75) {
                $noreplycolor = $thislayout['color_bad'];
            } elseif ($noreply > 50) {
                $noreplycolor = $thislayout['color_warn'];
            } elseif ($noreply > 25) {
                $noreplycolor = $thislayout['color_good'];
            } else {
                $noreplycolor = $thislayout['color_perfect'];
            }

            $nav = $nav / $total * 100;

            $nav = number_format($nav, 2);

            if ($nav > 50) {
                $navcolor = $thislayout['color_bad'];
            } elseif ($nav > 33) {
                $navcolor = $thislayout['color_warn'];
            } elseif ($nav > 20) {
                $navcolor = $thislayout['color_good'];
            } else {
                $navcolor = $thislayout['color_perfect'];
            }

            $sub = $sub / $total * 100;

            $sub = number_format($sub, 2);

            if ($sub > 66) {
                $subcolor = $thislayout['color_good'];
            } elseif ($sub > 33) {
                $subcolor = $thislayout['color_warn'];
            } else {
                $subcolor = $thislayout['color_perfect'];
            }
        }

        if (isset($class) && ('even' === $class)) {
            $class = 'odd';
        } else {
            $class = 'even';
        }

        $player[$key]['class'] = $class;

        $player[$key]['status'] = $status;

        $player[$key]['av'] = $av;

        $player[$key]['avcolor'] = $avcolor;

        $player[$key]['avperc'] = $avperc . '%';

        $player[$key]['nav'] = $nav . '%';

        $player[$key]['navcolor'] = $navcolor;

        $player[$key]['sub'] = $sub . '%';

        $player[$key]['subcolor'] = $subcolor;

        $player[$key]['noreply'] = $noreply . '%';

        $player[$key]['noreplycolor'] = $noreplycolor;

        $player[$key]['total'] = $total;
    }

    $xoopsTpl->assign('players', $player);

    $xoopsTpl->assign('teamname', $team->getVar('teamname'));

    $team->select();

    if ($xoopsUser->isAdmin($xoopsModule->mid()) || ($team->isTeamAdmin($uid))) {
        $xoopsTpl->assign('admin', 'Yes');
    }

    $xoopsTpl->assign('teamid', $teamid);

    $xoopsTpl->assign('teamname', $team->getVar('teamname'));

    $xoopsTpl->assign('lang_teamroster', _MD_GAMERS_ROSTER);

    $xoopsTpl->assign('lang_teamadmin', _MD_GAMERS_ADMIN);

    $xoopsTpl->assign('lang_teamavailstats', _MD_GAMERS_AVAILSTATS);

    $xoopsTpl->assign('lang_teamplaying', _MD_GAMERS_PLAYING);

    $xoopsTpl->assign('lang_teamposoverview', _MD_GAMERS_POSOVERVIEW);

    $xoopsTpl->assign('lang_teammypos', _MD_GAMERS_MYPOS);

    $xoopsTpl->assign('lang_teamnickname', _MD_GAMERS_NICKNAME);

    $xoopsTpl->assign('lang_teamstatus', _MD_GAMERS_STATUS);

    $xoopsTpl->assign('lang_teammatches', _MD_GAMERS_MATCHES);

    $xoopsTpl->assign('lang_teamavailable', _MD_GAMERS_AVAILABLE);

    $xoopsTpl->assign('lang_teamnotavailable', _MD_GAMERS_NOTAVAILABLE);

    $xoopsTpl->assign('lang_teamsub', _MD_GAMERS_SUB);

    $xoopsTpl->assign('lang_teamnoreply', _MD_GAMERS_NOREPLY);

    if (isset($limit)) {
        if (20 === $limit) {
            $xoopsTpl->assign('link1', '?limit=10');

            $xoopsTpl->assign('link1txt', _MD_GAMERS_LAST10MATCHES);

            $xoopsTpl->assign('link2', '');

            $xoopsTpl->assign('link2txt', _MD_GAMERS_ALLMATCHES);
        } elseif (10 === $limit) {
            $xoopsTpl->assign('link2', '?limit=20');

            $xoopsTpl->assign('link2txt', _MD_GAMERS_LAST20MATCHES);

            $xoopsTpl->assign('link1', '');

            $xoopsTpl->assign('link1txt', _MD_GAMERS_ALLMATCHES);
        }
    } else {
        $xoopsTpl->assign('link1', '?limit=10');

        $xoopsTpl->assign('link1txt', _MD_GAMERS_LAST10MATCHES);

        $xoopsTpl->assign('link2', '?limit=20');

        $xoopsTpl->assign('link2txt', _MD_GAMERS_LAST20MATCHES);
    }
}
require XOOPS_ROOT_PATH . '/footer.php';
