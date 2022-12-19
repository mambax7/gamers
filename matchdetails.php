<?php declare(strict_types=1);


use XoopsModules\Gamers\{
    Helper,
    Player
};
/** @var Helper $helper */

$GLOBALS['xoopsOption']['template_main'] = 'gamers_matchdetails.tpl';

require_once __DIR__ . '/header.php';

$op = $_GET['op'] ?? 'default';
$mid = isset($_GET['mid']) ? (int)$_GET['mid'] : null;
$mapid = isset($_GET['mapid']) ? (int)$_GET['mapid'] : null;
if (isset($_POST)) {
    foreach ($_POST as $k => $v) {
        ${$k} = $v;
    }
}
$matchHandler = Helper::getInstance()->getHandler('Match');
$mymatch      = $matchHandler->get($mid);
$teamid       = $mymatch->getVar('teamid');
$teamHandler  = Helper::getInstance()->getHandler('Team');
$team         = $teamHandler->get($teamid);
switch ($op) {
    case 'savelineup':
    $lineupposHandler = Helper::getInstance()->getHandler('LineupPosition');
    $lineupHandler = Helper::getInstance()->getHandler('Lineup');
    if ('Edit' == $action) {
        $lineups = explode(':', $lineupid);

        $count = count($lineups);

        $message = _MD_GAMERS_LINEEDITED;

        $edit = true;
    } elseif ('Add' == $action) {
        $count = $teamsize;

        $message = _MD_GAMERS_LINEUPADDED;

        $edit = false;
    }
    $thislineup = $lineupHandler->get($matchmapid);
    $thislineup->setVar('general', $general);
    $thislineup->saveGeneral();
    //UPDATE database
    for ($i = 0; $i < $count; ++$i) {
        if ($edit) {
            $thislineuppos = $lineupposHandler->create(false);

            $thislineuppos->setVar('lineupid', $lineups[$i]);
        } else {
            $thislineuppos = $lineupposHandler->create();
        }

        $thislineuppos->setVar('posid', $posid[$i]);

        $thislineuppos->setVar('matchmapid', $matchmapid);

        $thislineuppos->setVar('posdesc', $posdesc[$i]);

        $thislineuppos->setVar('uid', $playerid[$i]);

        $lineupposHandler->insert($thislineuppos);

        unset($thislineuppos);
    }

    //Notification
    $tags = [];
    $tags['SIZE'] = $mymatch->getVar('teamsize');
    $tags['GAMERS_NAME'] = $team->getVar('teamname');
    $tags['MAPNAME'] = getMap($mapid);
    $tags['OPPONENT'] = $mymatch->getVar('opponent');
    $tags['MATCHDATE'] = date(_SHORTDATESTRING, $mymatch->getVar('matchdate'));
    $tags['MATCHTIME'] = date('H:i', $mymatch->getVar('matchdate'));
    $tags['DETAILS_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/matchdetails.php?mid=' . $mid;
    $notificationHandler = xoops_getHandler('notification');
    $notificationHandler->triggerEvent('match', $mid, 'new_lineup', $tags);

    //Redirect
    redirect_header('matchdetails.php?mid=' . $mid, 3, $message);
    break;
    case 'lineup':
    $lineupposHandler = Helper::getInstance()->getHandler('LineupPosition');
    $lineupHandler = Helper::getInstance()->getHandler('Lineup');
    if ($xoopsUser && $team->isTacticsAdmin($xoopsUser->getVar('uid'))) {
        $playerid = [];

        $pos = [];

        $desc = [];

        if (isset($_GET['matchmapid'])) {
            $teamsize = $mymatch->getVar('teamsize');

            $lineup = $lineupHandler->get($_GET['matchmapid']);

            $map = is_object($lineup->map) ? $lineup->map->getVar('mapname') : '??';

            $general = $lineup->getVar('general');

            $positions = $lineup->getPositions();

            if ((is_countable($positions) ? count($positions) : 0) > 0) {
                $i = 0;

                $lineupid = '';

                foreach ($positions as $key => $position) {
                    if ($lineupid) {
                        $lineupid .= ':';
                    }

                    $lineupid .= $position['lineupid'];

                    $pos[$i] = $position['posid'];

                    $desc[$i] = $position['posdesc'];

                    $playerid[$i] = $position['uid'];

                    $i++;
                }

                $action = 'Edit';
            } else {
                $tacticsHandler = Helper::getInstance()->getHandler('Tactics');

                $positionHandler = Helper::getInstance()->getHandler('TacticsPosition');

                $tactics = $tacticsHandler->getByParams($teamid, $mapid, $teamsize);

                $tacid = $tactics->getVar('tacid');

                $general = $tactics->getVar('general');

                $positions = $tactics->getPositions();

                $action = 'Add';

                $i = 0;

                if ((is_countable($positions) ? count($positions) : 0) > 0) {
                    foreach ($positions as $key => $tacposid) {
                        $thisposition = $positionHandler->get($tacposid);

                        $pos[$i] = $thisposition->getVar('posid');

                        $desc[$i] = $thisposition->getVar('posdesc');

                        $i++;
                    }
                }
            }
        } else {
            redirect_header('index.php', 2, _MD_GAMERS_NOLINEUPSELECTED);

            break;
        }

        echo "<table border='0' cellpadding='0' cellspacing='0' valign='top' width='100%'>";

        echo "<tr><td><table width='100%' border='0' cellpadding='0' cellspacing='0'>";

        echo "<tr class='head'><td colspan=2><h3>";

        echo '' . _MD_GAMERS_LINEUPFOR . '' . $team->getVar('teamname') . ' ' . _MD_GAMERS_VERSUS . ' ' . $mymatch->getVar('opponent') . ' ' . _MD_GAMERS_ON . ' ' . $map;

        echo '</h3></td></tr>';

        echo '<tr><td colspan=2>';

        require XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

        $mform = new XoopsThemeForm(_MD_GAMERS_LINEUPADDITION, 'savelineup', xoops_getenv('PHP_SELF'));

        $players = $mymatch->getMatchPlayers();

        $subs = $mymatch->getMatchSubs();

        $generaltacs = new XoopsFormTextArea(_MD_GAMERS_GENERALTACS, 'general', $general);

        $mform->addElement($generaltacs);

        $teampositions = $team->getPositions();

        for ($i = 0; $i < $teamsize; ++$i) {
            $thispos = 0;

            $player = 0;

            $thisdesc = '';

            if (isset($pos[$i])) {
                $thispos = $pos[$i];
            }

            if (isset($playerid[$i])) {
                $player = $playerid[$i];
            }

            if (isset($desc[$i])) {
                $thisdesc = $desc[$i];
            }

            $position_select[$i] = new XoopsFormSelect(_MD_GAMERS_POSITION . ' ' . ($i + 1), 'posid[' . $i . ']', $thispos);

            foreach ($teampositions as $positionid => $positionname) {
                $position_select[$i]->addOption($positionid, $positionname);
            }

            $player_select[$i] = new XoopsFormSelect(_MD_GAMERS_PLAYER, 'playerid[' . $i . ']', $player);

            $player_select[$i]->addOption(0, _MD_GAMERS_UNDECIDED);

            foreach ($players as $pid => $pname) {
                $player_select[$i]->addOption($pid, $pname);
            }

            $player_select[$i]->addOption(-1, '---');

            foreach ($subs as $pid => $pname) {
                $player_select[$i]->addOption($pid, '(Sub)' . $pname);
            }

            $description[$i] = new XoopsFormTextArea(_MD_GAMERS_DESCRIPTION, 'posdesc[' . $i . ']', $thisdesc);

            $mform->addElement($position_select[$i]);

            $mform->addElement($player_select[$i]);

            $mform->addElement($description[$i]);
        }

        $button_tray = new XoopsFormElementTray('', '');

        $submit = new XoopsFormButton('', 'action', $action, 'Submit');

        $button_tray->addElement($submit);

        $teamsize_hidden = new XoopsFormHidden('teamsize', $teamsize);

        $matchmapid_hidden = new XoopsFormHidden('matchmapid', $lineup->getVar('matchmapid'));

        $mapid_hidden = new XoopsFormHidden('mapid', $lineup->getVar('mapid'));

        $matchid_hidden = new XoopsFormHidden('mid', $lineup->getVar('matchid'));

        $op_hidden = new XoopsFormHidden('op', 'savelineup');

        if (isset($lineupid)) {
            $lineupid_hidden = new XoopsFormHidden('lineupid', $lineupid);

            $mform->addElement($lineupid_hidden);
        }

        $mform->addElement($teamsize_hidden);

        $mform->addElement($mapid_hidden);

        $mform->addElement($matchid_hidden);

        $mform->addElement($matchmapid_hidden);

        $mform->addElement($op_hidden);

        $mform->addElement($button_tray);

        $mform->display();

        echo '</table></td></tr></table>';
    } else {
        redirect_header('index.php', 3, _MD_GAMERS_ACCESSDENIED);

        break;
    }
    break;
    case 'default':
    $layout = getLayout();
    require_once XOOPS_ROOT_PATH . '/class/module.textsanitizer.php';

    $teamname = $team->getVar('teamname');
    $mdate = $mymatch->getVar('matchdate');
    $matchresult = $mymatch->getVar('matchresult');
    switch (mb_strtolower($matchresult)) {
        case 'win': $matchresultcolor = $layout['color_match_win']; break;
        case 'loss': $matchresultcolor = $layout['color_match_loss']; break;
        case 'draw': $matchresultcolor = $layout['color_match_draw']; break;
        default: $matchresultcolor = $layout['color_match_pending']; break;
    }
    $time = date('H:i', $mdate);
    $maps = $team->getVar('maps');
    $side = '';
    $sides = getAllSides();
    $screenshotnumber = 0;
    $ourscoresum = 0; $theirscoresum = 0;
    $matchmapHandler = Helper::getInstance()->getHandler('MatchMap');
    $matchmaps = $matchmapHandler->getByMatchid($mid);
    for ($i = 1; $i <= $maps; ++$i) {
        $thismap = isset($matchmaps[$i]) && is_object($matchmaps[$i]) ? $matchmaps[$i] : $matchmapHandler->create();

        $sideindex = $thismap->getVar('side');

        if (isset($sideindex)) {
            $side = $sides[$sideindex];
        }

        $map[$i]['matchmapid'] = $thismap->getVar('matchmapid');

        $map[$i]['name'] = is_object($thismap->map) ? $thismap->map->getVar('mapname') . ' (' . $side . ')' : '?? (' . $side . ')';

        $map[$i]['mapid'] = $thismap->getVar('mapid');

        $map[$i]['ourscore'] = $thismap->getVar('ourscore');

        $ourscoresum += $thismap->getVar('ourscore');

        $theirscoresum += $thismap->getVar('theirscore');

        $map[$i]['theirscore'] = $thismap->getVar('theirscore');

        $map[$i]['matchid'] = $thismap->getVar('matchid');

        $map[$i]['caption'] = getCaption($i);

        $map[$i]['screenshot'] = $thismap->getVar('screenshot');

        if ('' != $map[$i]['screenshot']) {
            $screenshotnumber++;
        }

        $map[$i]['tacid'] = $thismap->getTacid($teamid, $mymatch->getVar('teamsize'));

        if ('Pending' == $mymatch->getVar('matchresult')) {
            $map[$i]['color'] = $layout['color_match_pending'];
        } else {
            $map[$i]['color'] = $thismap->winner($layout);
        }
    }
    if ('21:00' != $time) {
        $xoopsTpl->assign('msize', 3);
    } else {
        $xoopsTpl->assign('msize', 2);
    }
    $firstday = date('w', $mdate);
    if (1 == $firstday) {
        $weekday = _MD_GAMERS_MONDAY;
    } elseif (2 == $firstday) {
        $weekday = _MD_GAMERS_TUESDAY;
    } elseif (3 == $firstday) {
        $weekday = _MD_GAMERS_WEDNESDAY;
    } elseif (4 == $firstday) {
        $weekday = _MD_GAMERS_THURSDAY;
    } elseif (5 == $firstday) {
        $weekday = _MD_GAMERS_FRIDAY;
    } elseif (6 == $firstday) {
        $weekday = _MD_GAMERS_SATURDAY;
    } else {
        $weekday = _MD_GAMERS_SUNDAY;
    }
    $xoopsTpl->assign('ourscoresum', $ourscoresum);
    $xoopsTpl->assign('theirscoresum', $theirscoresum);
    $xoopsTpl->assign('matchresultcolor', $matchresultcolor);
    $xoopsTpl->assign('weekday', $weekday);
    $xoopsTpl->assign('teamname', $teamname);
    $xoopsTpl->assign('teamid', $teamid);
    $xoopsTpl->assign('day', date(_SHORTDATESTRING, $mdate));
    $xoopsTpl->assign('time', $time);
    $xoopsTpl->assign('screenshotnumber', $screenshotnumber);
    $xoopsTpl->assign('matchresult', $matchresult);
    $review = $mymatch->getVar('review');
    if (mb_strlen($review) > 0) {
        $xoopsTpl->assign('review', $review);
    }
    if (0 != $mymatch->getVar('server')) {
        $matchserver = getServer($mymatch->getVar('server'));

        $xoopsTpl->assign('servername', $matchserver['name']);

        $xoopsTpl->assign('ip', $matchserver['ip']);

        $xoopsTpl->assign('port', $matchserver['port']);
    } else {
        // custom server

        $xoopsTpl->assign('servername', $mymatch->getVar('customServer'));
    }
    $xoopsTpl->assign('opponent', $mymatch->getVar('opponent'));
    $xoopsTpl->assign('mid', $mid);
    $xoopsTpl->assign('teamsize', $mymatch->getVar('teamsize'));
    $xoopsTpl->assign('ladder', $mymatch->getVar('ladder'));
    $xoopsTpl->assign('lang_opponent', _MD_GAMERS_AGAINST);
    $xoopsTpl->assign('lang_availability', _MD_GAMERS_MATCHAVAILABILITY);
    $xoopsTpl->assign('lang_teammatchlist', _MD_GAMERS_MATCHLIST);
    $xoopsTpl->assign('lang_matchpositions', _MD_GAMERS_MATCHPOSITIONS);
    $xoopsTpl->assign('lang_at', _MD_GAMERS_AT);
    $xoopsTpl->assign('lang_matchtype', _MD_GAMERS_MATCHTYPE);
    $xoopsTpl->assign('lang_versus', _MD_GAMERS_VERSUS);
    $xoopsTpl->assign('lang_server', _MD_GAMERS_SERVER);
    $xoopsTpl->assign('lang_review', _MD_GAMERS_MATCHREVIEW);
    $xoopsTpl->assign('lang_lineupfor', _MD_GAMERS_LINEUPFOR);
    $xoopsTpl->assign('lang_lineup', _MD_GAMERS_LINEUP);
    $xoopsTpl->assign('lang_nolineupyet', _MD_GAMERS_NOLINEUPYET);
    $xoopsTpl->assign('lang_screenshots', _MD_GAMERS_SCREENSHOTS);
    $xoopsTpl->assign('lock', $mymatch->getVar('alock'));
    if ('Pending' == $mymatch->getVar('matchresult')) {
        $xoopsTpl->assign('pending', 1);
    } else {
        $xoopsTpl->assign('pending', 0);
    }
    $allpos = getAllPos();
    $lineupHandler = Helper::getInstance()->getHandler('Lineup');
    foreach ($map as $thismap) {
        $thislineup = $lineupHandler->get($thismap['matchmapid']);

        $general = $thislineup->getVar('general');

        $lineuppos = $thislineup->getPositions();

        $lineup = [];

        if (isset($general)) {
            $lineup[] = [
'uname' => '',
            'posname' => _MD_GAMERS_GENERALTACS,
            'posdesc' => $general,
            'class' => 'even',
];
        }

        $i = 0;

        if ((is_countable($lineuppos) ? count($lineuppos) : 0) > 0) {
            foreach ($lineuppos as $key => $thislineup) {
                $i++;

                if (isset($class) && ('odd' == $class)) {
                    $class = 'even';
                } else {
                    $class = 'odd';
                }

                if ($thislineup['uid']) {
                    $thisuser = XoopsUser::getUnameFromId($thislineup['uid']);

                    $thisuser = $i . ' ' . $thisuser;
                } else {
                    $thisuser = $i . ' -';
                }

                $lineup[] = [
'uname' => $thisuser,
                'posname' => $allpos[$thislineup['posid']],
                'posdesc' => $thislineup['posdesc'],
                'class' => $class,
];
            }

            $edit = 'edit';
        } else {
            $edit = 'Set';
        }

        $xoopsTpl->append('map', ['mapid' => $thismap['mapid'], 'edit' => $edit, 'mapno' => $thismap['caption'], 'linenumbers' => is_countable($lineuppos) ? count($lineuppos) : 0, 'mapname' => $thismap['name'], 'ourscore' => $thismap['ourscore'], 'theirscore' => $thismap['theirscore'], 'color' => $thismap['color'], 'tacid' => $thismap['tacid'], 'screenshot' => $thismap['screenshot'], 'lineup' => $lineup, 'matchmapid' => $thismap['matchmapid']]);
    }
    if ($xoopsUser && $team->isTeamMember($xoopsUser->getVar('uid'))) {
        $xoopsTpl->assign('isTeamMember', 'yes');
    }

    if ($xoopsUser && $team->isTacticsAdmin($xoopsUser->getVar('uid'))) {
        $xoopsTpl->assign('admin', 'yes');
    }

    break;
}

require XOOPS_ROOT_PATH . '/footer.php';
