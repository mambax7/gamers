<?php declare(strict_types=1);

use XoopsModules\Gamers\{
    Helper
};

/** @var Helper $helper */

$GLOBALS['xoopsOption']['template_main'] = 'gamers_availability.tpl';

require_once __DIR__ . '/header.php';

$op  = $_GET['op'] ?? 'default';
$mid = isset($_GET['mid']) ? (int)$_GET['mid'] : null;
if (isset($_POST)) {
    foreach ($_POST as $k => $v) {
        ${$k} = $v;
    }
}
/**
 * @param int    $uid
 * @param int    $mid
 * @param string $av
 * @return bool
 */
function set(int $uid, int $mid, string $av): bool
{
    global $xoopsModule;

    $uid = (int)$uid;

    $mid = (int)$mid;

    $availabilityHandler = Helper::getInstance()
                                 ->getHandler('Availability');

    $availability = $availabilityHandler->create();

    $availability->setVar('userid', $uid);

    $availability->setVar('availability', $av);

    $availability->setVar('matchid', $mid);

    if ($availabilityHandler->insert($availability)) {
        $notificationHandler = xoops_getHandler('notification');

        if (('Yes' === $av) && (!$notificationHandler->isSubscribed('match', $mid, 'new_lineup', $xoopsModule->getVar('mid'), $uid))) {
            $notificationHandler->subscribe('match', $mid, 'new_lineup', null, $xoopsModule->getVar('mid'), $uid);
        }

        return true;
    }

    return false;
}

/**
 * @param int $availid
 * @param string $av
 * @param int $mid
 * @return bool
 */
function change(int $availid, string $av, int $mid): bool
{
    global $xoopsModule, $xoopsUser;
    $myts = MyTextSanitizer::getInstance();

    $availid = (int)$availid;

    $mid = (int)$mid;

    $availabilityHandler = Helper::getInstance()
                                 ->getHandler('Availability');

    if ($availabilityHandler->updateAll('availability', $myts->addSlashes($av), new \Criteria('avid', (int)$availid))) {
        $notificationHandler = xoops_getHandler('notification');

        if (('Yes' === $av) && (!$notificationHandler->isSubscribed('match', $mid, 'new_lineup', $xoopsModule->getVar('mid'), $xoopsUser->getVar('uid')))) {
            $notificationHandler->subscribe('match', $mid, 'new_lineup', null, $xoopsModule->getVar('mid'), $xoopsUser->getVar('uid'));
        }

        return true;
    }

    return false;
}

/**
 * @param int|string $val1
 * @param string     $comm
 * @param string     $val2
 */
function comment($val1, string $comm, string $val2 = '')
{
    $matchid = null;
    $userid  = null;
    $avid    = null;
    if ($val2) {
        $userid = (int)$val1;

        $matchid = (int)$val2;
    } else {
        $avid = (int)$val1;
    }

    echo "<table width='100%' border='0' cellpadding='4' cellspacing='1'><tr class='head'><td>";

    echo "<form method='post' action='availability.php'>";

    echo "<input type='hidden' name='op' value='setcomment'>";

    echo "<input type='hidden' name='matchid' value=" . $matchid . '>';

    echo "<input type='hidden' name='userid' value=" . $userid . '>';

    echo "<input type='hidden' name='avid' value=" . $avid . '>';

    echo _MD_GAMERS_SUBREASSHORTEXPL . " </td><td><input type='text' name='newcomment' value='" . $comm . "' size=20>";

    echo "</td><td><input type=submit value='Submit'></form></td></tr></table>";
}

/**
 * @param int|string $mid
 * @param int|string $uid
 * @param int|string $aid
 * @param string     $comment
 */
function setcomment($mid, $uid, $aid, string $comment)
{
    $myts = MyTextSanitizer::getInstance();
    $mid  = (int)$mid;
    $uid  = (int)$uid;

    if ($aid) {
        $criteria = new \Criteria('avid', (int)$aid);
    } else {
        $criteria = new \CriteriaCompo(new \Criteria('matchid', $mid));

        $criteria->add(new \Criteria('userid', $uid));
    }

    $availabilityHandler = Helper::getInstance()
                                 ->getHandler('Availability');

    $availabilityHandler->updateAll('comment', $myts->addSlashes($comment), $criteria);
}

if ($xoopsUser) {
    $matchHandler = Helper::getInstance()
                          ->getHandler('Match');

    switch ($op) {
        case 'reset':
            if (!empty($ok)) {
                if (empty($matchid)) {
                    redirect_header('index.php', 2, _MD_GAMERS_EMPTY_NO_RESET);

                    break;
                }

                $matchid = (int)$matchid;

                $availabilityHandler = Helper::getInstance()
                                             ->getHandler('Availability');

                $availabilityHandler->updateAll('availability', 'Not Set', new \Criteria('matchid', $matchid));

                redirect_header('availability.php?mid=' . $matchid, 3, _MD_GAMERS_AVSRESAT);
            } else {
                xoops_confirm(['op' => 'reset', 'matchid' => $matchid, 'ok' => 1], 'availability.php', _MD_GAMERS_RUSURERESET);
            }
            break;
        case 'lock':
            $thismatch = $matchHandler->get($matchid);
            if (1 === $alock) {
                $thismatch->lock();
            } else {
                $thismatch->unlock();
            }
            break;
        case 'setcomment':
            $avid = (int)$avid;
            setcomment($matchid, $userid, $avid, $newcomment);
            if (!$matchid) {
                $availabilityHandler = Helper::getInstance()
                                             ->getHandler('Availability');

                $availability = $availabilityHandler->get($avid);

                $matchid = $availability->getVar('matchid');
            }
            redirect_header('availability.php?mid=' . $matchid, 3, _MD_GAMERS_AVAILABILITYSET);
            break;
        case 'set':
            if (set($userid, $matchid, $availability)) {
                if ('Sub' === $availability) {
                    comment($userid, $comment, $matchid);
                } else {
                    redirect_header('availability.php?mid=' . $matchid, 3, _MD_GAMERS_AVAILABILITYSET);
                }
            } else {
                redirect_header('availability.php?mid=' . $matchid, 3, _MD_GAMERS_DBNOTUPDATED);
            }
            break;
        case 'AvailOverride':
            global $xoopsDB;
            $myts                = \MyTextSanitizer::getInstance();
            $availabilityHandler = Helper::getInstance()
                                         ->getHandler('Availability');
            $criteria            = new \CriteriaCompo(new \Criteria('userid', (int)$uid));
            $criteria->add(new \Criteria('matchid', (int)$matchid));
            if ($availabilityHandler->updateAll('availability', $myts->addSlashes($availability), $criteria)) {
                redirect_header('availability.php?mid=' . $matchid, 3, _MD_GAMERS_AVAILABILITYMODIFIED);
            } else {
                redirect_header('availability.php?mid=' . $matchid, 3, _MD_GAMERS_ERRORUSERNOTUPDATED);
            }
            break;
        case _MD_GAMERS_CHANGE:
            if (change($avid, $availability, $matchid)) {
                if ('Sub' === $availability) {
                    comment($avid, $comment);
                } else {
                    redirect_header('availability.php?mid=' . $matchid, 3, _MD_GAMERS_AVAILABILITYMODIFIED);
                }
            } else {
                redirect_header('availability.php?mid=' . $matchid, 3, _MD_GAMERS_DBNOTUPDATED);
            }
            break;
        case 'default':
        default:
            if ($mid) {
                require_once XOOPS_ROOT_PATH . '/class/module.textsanitizer.php';

                $thislayout = getLayout();

                $xoopsTpl->assign('layout', [
                    'perfect' => $thislayout['color_perfect'],
                    'good'    => $thislayout['color_good'],
                    'warn'    => $thislayout['color_warn'],
                    'bad'     => $thislayout['color_bad'],
                ]);

                $mymatch = $matchHandler->get($mid);

                $teamid = $mymatch->getVar('teamid');

                $teamHandler = Helper::getInstance()
                                     ->getHandler('Team');

                $myteam = $teamHandler->get($teamid);

                if (!$myteam->isTeamMember($xoopsUser->getVar('uid')) && (!$xoopsUser->isAdmin($xoopsModule->mid()))) {
                    redirect_header('index.php?teamid=' . $teamid, 3, _MD_GAMERS_ACCESSDENY);

                    break;
                }

                $mdate = $mymatch->getVar('matchdate');

                $time = date('H:i', $mdate);

                if ('21:00' !== $time) {
                    $xoopsTpl->assign('msize', 3);
                } else {
                    $xoopsTpl->assign('msize', 2);
                }

                $maps = $mymatch->getMatchMaps();

                foreach ($maps as $mapno => $thismap) {
                    $map[$mapno]['name'] = is_object($thismap->map) ? $thismap->map->getVar('mapname') : '';

                    $map[$mapno]['caption'] = getCaption($mapno);
                }

                $firstday = date('w', $mdate);

                if (1 === (int)$firstday) {
                    $weekday = _MD_GAMERS_MONDAY;
                } elseif (2 === (int)$firstday) {
                    $weekday = _MD_GAMERS_TUESDAY;
                } elseif (3 === (int)$firstday) {
                    $weekday = _MD_GAMERS_WEDNESDAY;
                } elseif (4 === (int)$firstday) {
                    $weekday = _MD_GAMERS_THURSDAY;
                } elseif (5 === (int)$firstday) {
                    $weekday = _MD_GAMERS_FRIDAY;
                } elseif (6 === (int)$firstday) {
                    $weekday = _MD_GAMERS_SATURDAY;
                } else {
                    $weekday = _MD_GAMERS_SUNDAY;
                }

                $lock = $mymatch->getVar('alock');

                if ('Pending' === $mymatch->getVar('matchresult')) {
                    $pending = 1;

                    $xoopsTpl->assign('pending', 1);
                }

                $yes = 0;

                $no = 0;

                $notsure = 0;

                $noreply = 0;

                $lateyes = 0;

                $lateno = 0;

                $notreplied = [];

                $avid = [];

                $navid = [];

                $subid = [];

                $notavailable = [];

                $available = [];

                $subs = [];

                $subcomment = [];

                $lateneg = [];

                $latepos = [];

                $latenegid = [];

                $lateposid = [];

                $result = $mymatch->getAvailabilities();

                while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
                    $comment = $myrow['comment'];

                    $uid = $myrow['userid'];

                    $myavail = $myrow['availability'];

                    $nick = $myrow['uname'];

                    if ('Yes' === $myavail) {
                        $available[$yes]['name'] = $nick;

                        $available[$yes]['id'] = $uid;

                        $yes++;
                    } elseif ('No' === $myavail) {
                        $notavailable[$no]['name'] = $nick;

                        $notavailable[$no]['id'] = $uid;

                        $no++;
                    } elseif ('Sub' === $myavail) {
                        $subs[$notsure]['name'] = $nick;

                        $subs[$notsure]['id'] = $uid;

                        if (isset($comment)) {
                            $subs[$notsure]['comment'] = '- ' . $comment;
                        }

                        $notsure++;
                    } elseif ('Not Set' === $myavail) {
                        $notreplied[$noreply]['name'] = $nick;

                        $notreplied[$noreply]['id'] = $uid;

                        $noreply++;
                    } elseif ('LateYes' === $myavail) {
                        $latepos[$lateyes]['name'] = $nick;

                        $latepos[$lateyes]['id'] = $uid;

                        $lateyes++;
                    } elseif ('LateNo' === $myavail) {
                        $lateneg[$lateno]['name'] = $nick;

                        $lateneg[$lateno]['id'] = $uid;

                        $lateno++;
                    }

                    if ($uid == $xoopsUser->getVar('uid')) {
                        $myav = $myrow['availability'];

                        $xoopsTpl->assign('avid', $myrow['avid']);

                        $xoopsTpl->assign('myav', $myav);

                        $xoopsTpl->assign('comment', $myrow['comment']);
                    }
                }

                $yestotal = $yes + $lateyes;

                $nototal = $no + $lateno;

                $max1 = max($yes, $no);

                if ($max1 < $notsure) {
                    $max1 = $notsure;
                }

                $max2 = max($lateyes, $lateno);

                if ($max2 < $noreply) {
                    $max2 = $noreply;
                }

                if ($yes < $max1) {
                    for ($i = $yes; $i < $max1; ++$i) {
                        $available[$i]['name'] = '&nbsp ';
                    }
                }

                $i = $no;

                while ($i < $max1) {
                    $notavailable[]['name'] = '&nbsp ';

                    $i++;
                }

                $i = $notsure;

                while ($i < $max1) {
                    $subs[]['name'] = '&nbsp ';

                    $i++;
                }

                $i = $lateyes;

                while ($i < $max2) {
                    $latepos[]['name'] = '&nbsp ';

                    $i++;
                }

                $i = $lateno;

                while ($i < $max2) {
                    $lateneg[]['name'] = '&nbsp ';

                    $i++;
                }

                $i = $noreply;

                while ($i < $max2) {
                    $notreplied[]['name'] = '&nbsp ';

                    $i++;
                }

                $uid = 0;

                //Availability Setup for teammembers only

                if ($myteam->isTeamMember($xoopsUser->getVar('uid'))) {
                    $uid = $xoopsUser->getVar('uid');

                    $uname = $xoopsUser->getVar('uname');

                    $action = 'set';

                    if (isset($pending) && (1 === $pending)) {
                        if (isset($myav)) {
                            $action = _MD_GAMERS_CHANGE;

                            if ('Yes' === $myav) {
                                $xoopsTpl->assign('avcheck', 'selected');

                                $xoopsTpl->assign('navcheck', '');

                                $xoopsTpl->assign('subcheck', '');

                                $xoopsTpl->assign('greeting', _MD_GAMERS_HELLO . ' ' . $uname . ', ' . _MD_GAMERS_YOUSETAVAIL);
                            } elseif ('No' === $myav) {
                                $xoopsTpl->assign('avcheck', '');

                                $xoopsTpl->assign('navcheck', 'selected');

                                $xoopsTpl->assign('subcheck', '');

                                $xoopsTpl->assign('greeting', _MD_GAMERS_HELLO . ' ' . $uname . ', ' . _MD_GAMERS_YOUSETNOTAVAIL);
                            } elseif ('Sub' === $myav) {
                                $xoopsTpl->assign('avcheck', '');

                                $xoopsTpl->assign('navcheck', '');

                                $xoopsTpl->assign('subcheck', 'selected');

                                $xoopsTpl->assign('greeting', _MD_GAMERS_HELLO . ' ' . $uname . ', ' . _MD_GAMERS_YOUSETSUB);
                            } else {
                                $xoopsTpl->assign('greeting', _MD_GAMERS_HELLO . ' ' . $uname . ', ' . _MD_GAMERS_YOUHAVENOTSETAVAIL);
                            }
                        }

                        if (1 === $lock) {
                            $xoopsTpl->assign('greeting', _MD_GAMERS_AVAILHASLOCKADMIN);
                        }

                        $xoopsTpl->assign('action', $action);
                    } else {
                        $xoopsTpl->assign('greeting', _MD_GAMERS_MATCHPLAYED);
                    }

                    $xoopsTpl->assign('uname', $uname);

                    $xoopsTpl->assign('uid', $uid);
                }

                //Admin options

                if ($myteam->isMatchAdmin($uid) || ($myteam->isTacticsAdmin($uid))) {
                    $players = $myteam->getActiveMembers();

                    $i = 0;

                    foreach ($players as $playerid => $playername) {
                        $playerarray[$i]['uid'] = $playerid;

                        $playerarray[$i]['uname'] = $playername;

                        $i++;
                    }

                    $xoopsTpl->assign('players', $playerarray);

                    $xoopsTpl->assign('admin', 'Yes');
                }

                $xoopsTpl->assign('map', $map);

                $xoopsTpl->assign('opponent', $mymatch->getVar('opponent'));

                $xoopsTpl->assign('teamname', $myteam->getVar('teamname'));

                $xoopsTpl->assign('ladder', $mymatch->getVar('ladder'));

                $xoopsTpl->assign('teamsize', $mymatch->getVar('teamsize'));

                $xoopsTpl->assign('available', $available);

                $xoopsTpl->assign('yes', $yes);

                $xoopsTpl->assign('notavailable', $notavailable);

                $xoopsTpl->assign('no', $no);

                $xoopsTpl->assign('subs', $subs);

                $xoopsTpl->assign('notsure', $notsure);

                $xoopsTpl->assign('notreplied', $notreplied);

                $xoopsTpl->assign('noreply', $noreply);

                $xoopsTpl->assign('latepos', $latepos);

                $xoopsTpl->assign('lateyes', $lateyes);

                $xoopsTpl->assign('lateneg', $lateneg);

                $xoopsTpl->assign('lateno', $lateno);

                $xoopsTpl->assign('time', $time);

                $xoopsTpl->assign('mid', $mid);

                $xoopsTpl->assign('teamid', $teamid);

                $xoopsTpl->assign('weekday', $weekday);

                $xoopsTpl->assign('maps', $map);

                $xoopsTpl->assign('lock', $lock);

                $xoopsTpl->assign('day', date(_SHORTDATESTRING, $mdate));

                $xoopsTpl->assign('lang_teammatchlist', _MD_GAMERS_MATCHLIST);

                $xoopsTpl->assign('lang_teammatchdetails', _MD_GAMERS_MATCHDETAILS);

                $xoopsTpl->assign('lang_teammatchpositions', _MD_GAMERS_MATCHPOSITIONS);

                $xoopsTpl->assign('lang_against', _MD_GAMERS_AGAINST);

                $xoopsTpl->assign('lang_teamat', _MD_GAMERS_AT);

                $xoopsTpl->assign('lang_teamvs', _MD_GAMERS_VERSUS);

                $xoopsTpl->assign('lang_teamavailable', _MD_GAMERS_AVAILABLE);

                $xoopsTpl->assign('lang_teamnotavailable', _MD_GAMERS_NOTAVAILABLE);

                $xoopsTpl->assign('lang_teamsubs', _MD_GAMERS_SUBS);

                $xoopsTpl->assign('lang_teamlatepositive', _MD_GAMERS_LATEPOSITIVE);

                $xoopsTpl->assign('lang_teamlatenegative', _MD_GAMERS_LATENEGATIVE);

                $xoopsTpl->assign('lang_teamnoreply', _MD_GAMERS_NOREPLY);

                $xoopsTpl->assign('lang_teamsub', _MD_GAMERS_SUB);

                $xoopsTpl->assign('lang_teamlockavail', _MD_GAMERS_LOCKAVAIL);

                $xoopsTpl->assign('lang_teamunlockavail', _MD_GAMERS_UNLOCKAVAIL);

                $xoopsTpl->assign('lang_teamresetavail', _MD_GAMERS_RESETAVAIL);

                $xoopsTpl->assign('lang_teamoverride', _MD_GAMERS_OVERRIDE);
            } else {
                redirect_header('index.php', 3, _MD_GAMERS_NOMATCHSELECTED);
            }
            break;
    }
} else {
    redirect_header('../../index.php', 3, _MD_GAMERS_SORRYRESTRICTEDAREA);
}
require XOOPS_ROOT_PATH . '/footer.php';
