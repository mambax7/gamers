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

$GLOBALS['xoopsOption']['template_main'] = 'gamers_matchlist.tpl';

require_once __DIR__ . '/header.php';

//require dirname(__DIR__, 2) . '/mainfile.php';
//require XOOPS_ROOT_PATH . '/header.php';
//require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/functions.php';
//require_once __DIR__ . '/functions.php';

$op = $_GET['op'] ?? 'default';
$mid = isset($_GET['mid']) ? (int)$_GET['mid'] : null;

if (isset($_POST)) {
    foreach ($_POST as $k => $v) {
        ${$k} = $v;
    }
}

// show form to upload a screenshot
/**
 * @param int $matchmapid
 */
function screenshotadd($matchmapid)
{
    $op = 'savescreenshot';

    $action = 'Upload';

    $laddername = '';

    $ladderid = '';

    $laddervisible = 1;

    $matchmapHandler = Helper::getInstance()->getHandler('MatchMap');

    $thismap = $matchmapHandler->get($matchmapid);

    require XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    $pform = new \XoopsThemeForm(_MD_GAMERS_ADD . ' ' . _MD_GAMERS_SCREENSHOTNAME, 'screenshotform', xoops_getenv('PHP_SELF'));

    $button_tray = new \XoopsFormElementTray('', '');

    $submit = new \XoopsFormButton('', 'submit', _MD_GAMERS_UPLOAD, 'submit');

    $matchmapid_hidden = new \XoopsFormHidden('matchmapid', $matchmapid);

    $op_hidden = new \XoopsFormHidden('op', $op);

    $mid_hidden = new \XoopsFormHidden('mid', $thismap->getVar('matchid'));

    $mapname_label = new \XoopsFormLabel(_MD_GAMERS_MAPNAME, (is_object($thismap->map) ? $thismap->map->getVar('mapname') : ''));

    $mapside_label = new \XoopsFormLabel(_MD_GAMERS_SIDENAME, getSide($thismap->getVar('side')));

    $file = new \XoopsFormFile(_MD_GAMERS_SCREENSHOTNAME, 'screenshot', 200000);

    $button_tray->addElement($submit);

    $pform->addElement($mapname_label);

    $pform->addElement($mapside_label);

    $pform->addElement($matchmapid_hidden);

    $pform->addElement($op_hidden);

    $pform->addElement($mid_hidden);

    $pform->addElement($file);

    $pform->addElement($button_tray);

    $pform->setExtra('enctype="multipart/form-data"');

    $pform->display();
}
$teamHandler = Helper::getInstance()->getHandler('Team');
$matchHandler = Helper::getInstance()->getHandler('Match');
switch ($op) {
    case 'matchform':
    if ($xoopsUser) {
        echo '<h4>' . _MD_GAMERS_CONFIG . '</h4>';

        echo "<table width='100%' border='0' cellspacing='1' class='outer'><tr><td class=\"odd\">";

        if (isset($mid)) {
            $mymatch = $matchHandler->get($mid);

            $matchdate = $mymatch->getVar('matchdate');

            $teamid = $mymatch->getVar('teamid');

            $teamsize = $mymatch->getVar('teamsize');

            $opponent = $mymatch->getVar('opponent');

            $ladder = $mymatch->getVar('ladder');

            $matchresult = $mymatch->getVar('matchresult');

            $review = $mymatch->getVar('review');

            $server = $mymatch->getVar('server');

            $customserver = $mymatch->getVar('customserver');

            $showScreenshotLink = 'Pending' == $matchresult ? false : true;
        }

        if (!isset($teamid)) {
            require XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

            $mform = new \XoopsThemeForm(_MD_GAMERS_SELECT, 'matchform', xoops_getenv('PHP_SELF'));

            $team_select = new \XoopsFormSelect('Team', 'teamid', '1');

            $teams = $teamHandler->getObjects();

            foreach (array_keys($teams) as $i) {
                $thisteam = &$teams[$i];

                if ($thisteam->isTeamAdmin($xoopsUser->getVar('uid'))) {
                    $team_select->addOption($thisteam->getVar('teamid'), $thisteam->getVar('teamname'));
                }
            }

            $button_tray = new \XoopsFormElementTray('', '');

            $button_tray->addElement(new \XoopsFormButton('', 'select', 'select', 'submit'));

            $op_hidden = new \XoopsFormHidden('op', 'matchform');

            $mform->addElement($team_select);

            $mform->addElement($button_tray);

            $mform->addElement($op_hidden);

            $mform->display();
        } else {
            $team = $teamHandler->get($teamid);

            if ($team->isMatchAdmin($xoopsUser->getVar('uid'))) {
                $teamsizes = $team->getTeamSizes();

                $teamladders = $team->getLadders();

                require __DIR__ . '/include/matchform.inc.php';
            } else {
                redirect_header('index.php', 3, _MD_GAMERS_ACCESSDENIED);
            }
        }

        echo '</td></tr></table>';
    } else {
        redirect_header('index.php', 3, _MD_GAMERS_ACCESSDENIED);
    }
    break;
    case 'screenshotform':
    if ($xoopsUser) {
        // display add screenshotform

        if (isset($_GET['action']) && 'add' == $_GET['action']) {
            screenshotadd($_GET['matchmapid']);
        }

        echo '<h4>' . _MD_GAMERS_CONFIG . '</h4>';

        if (isset($mid)) {
            $mymatch = $matchHandler->get($mid);

            $nummaps = $mymatch->getMapCount();

            $opponent = $mymatch->getVar('opponent');

            require __DIR__ . '/include/screenshotform.inc.php';
        }
    } else {
        redirect_header('index.php', 3, _MD_GAMERS_ACCESSDENIED);
    }
    break;
    case 'savematch':
    if ($xoopsUser) {
        $team = $teamHandler->get($teamid);

        if ($day && ($month)) {
            $clock = explode(':', (string) $time);

            $hour = (int)$clock[0];

            $minute = (int)$clock[1];

            $matchdate = mktime($hour, $minute, 0, (int)$month, (int)$day, (int)$year);
        }

        $match = $matchHandler->create();

        $match->setVar('uid', $uid);

        $match->setVar('matchdate', $matchdate);

        $match->setVar('teamid', $teamid);

        $match->setVar('created', time());

        $match->setVar('teamsize', $teamsize);

        $match->setVar('opponent', $opponent);

        $match->setVar('ladder', $ladder);

        $match->setVar('review', $review);

        $match->setVar('alock', 0);

        $match->setVar('server', $server);

        $match->setVar('customserver', $customserver);

        if ($matchHandler->insert($match)) {
            $matchid = $match->getVar('matchid');

            $error = 0;

            $matchmapHandler = Helper::getInstance()->getHandler('MatchMap');

            for ($h = 0; $h < $team->getVar('maps'); $h++) {
                $thismap = $matchmapHandler->create();

                $thismap->setVar('matchid', $matchid);

                $thismap->setVar('mapid', $map[$h]);

                $thismap->setVar('side', $side[$h]);

                $thismap->setVar('mapno', $h + 1);

                if (!$matchmapHandler->insert($thismap)) {
                    $error++;
                }
            }

            $teammembers = $team->getActiveMembers();

            $availabilityHandler = Helper::getInstance()->getHandler('Availability');

            foreach ($teammembers as $member_id => $member_name) {
                $obj = $availabilityHandler->create();

                $obj->setVar('userid', $member_id);

                $obj->setVar('availability', 'Not Set');

                $obj->setVar('matchid', $matchid);

                if (!$availabilityHandler->insert($obj)) {
                    $error++;
                }
            }

            if ($error > 0) {
                redirect_header('index.php?teamid=' . $teamid, 3, $error . ' Insert(s) Failed');

                break;
            }

            //Notification

            $matchcreator = new \XoopsUser($uid);

            $creatorname = $matchcreator->getVar('uname');

            $teamname = $team->getVar('teamname');

            $tags = [];

            $tags['SIZE'] = $teamsize;

            $tags['GAMERS_NAME'] = $teamname;

            $tags['OPPONENT'] = $opponent;

            $tags['CREATOR'] = $creatorname;

            $tags['MATCHDATE'] = date(_SHORTDATESTRING, $matchdate);

            $tags['MATCHTIME'] = date('H:i', $matchdate);

            $tags['DETAILS_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/matchdetails.php?mid=' . $matchid;

            $notificationHandler = xoops_getHandler('notification');

            $notificationHandler->triggerEvent('gamers', $teamid, 'new_match', $tags);

            redirect_header('index.php?teamid=' . $teamid, 3, _MD_GAMERS_DBUPDATED);

            break;
        }

        redirect_header('index.php?teamid=' . $teamid, 3, 'Error - Match not created');

        break;
        if ((is_countable($error) ? count($error) : 0) > 0) {
            echo 'Error - Maps not created';
        }

        break;
    }
        redirect_header('index.php', 3, _MD_GAMERS_ACCESSDENIED);

    break;
    case 'editmatch':
    if ($xoopsUser) {
        $team = $teamHandler->get($teamid);

        if ($day && ($month)) {
            $clock = explode(':', (string) $time);

            $hour = (int)$clock[0];

            $minute = (int)$clock[1];

            $matchdate = mktime($hour, $minute, 0, (int)$month, (int)$day, (int)$year);
        }

        $match = $matchHandler->get($mid);

        $match->setVar('matchdate', $matchdate);

        $match->setVar('teamid', $teamid);

        $match->setVar('teamsize', $teamsize);

        $match->setVar('opponent', $opponent);

        $match->setVar('ladder', $ladder);

        $match->setVar('review', $review);

        $match->setVar('matchresult', $matchresult);

        $match->setVar('server', $server);

        $match->setVar('customserver', $customserver);

        $error = [];

        if (!$matchHandler->insert($match)) {
            redirect_header('index.php?teamid=' . $teamid, 2, _MD_GAMERS_DBNOTUPDATED);
        } else {
            $maps = $match->getMatchMaps();

            $matchmapHandler = Helper::getInstance()->getHandler('MatchMap');

            for ($count = 0; $count < $team->getVar('maps'); $count++) {
                $thismap = $maps[$count + 1] ?? $matchmapHandler->create();

                $thismap->setVar('mapno', $count + 1);

                $thismap->setVar('matchid', $match->getVar('matchid'));

                $thismap->setVar('ourscore', $ourscore[$count]);

                $thismap->setVar('theirscore', $theirscore[$count]);

                $thismap->setVar('mapid', $map[$count]);

                $thismap->setVar('side', $side[$count]);

                if (!$matchmapHandler->insert($thismap)) {
                    $error[] = $thismap->map->getVar('mapname') . ' Not Updated';
                }
            }
        }

        if (count($error) > 0) {
            $errormess = '';

            foreach ($error as $message) {
                $errormess .= $message;
            }

            redirect_header('index.php?teamid=' . $teamid, 2, _MD_GAMERS_DBNOTUPDATED . '<br>' . $errormess);
        } else {
            redirect_header('index.php?teamid=' . $teamid, 2, _MD_GAMERS_DBUPDATED);
        }

        break;
    }
        redirect_header('index.php', 3, _MD_GAMERS_ACCESSDENIED);

    break;
    // user has uploaded screenshot
    case 'savescreenshot':
    if (_MD_GAMERS_UPLOAD == $submit) {
        // do some error checking:

        if (!preg_match('/jpeg/', (string) $_FILES['screenshot']['type'])) {
            $message = _MD_GAMERS_ERRORNOTJPG;
        }

        if (UPLOAD_ERR_INI_SIZE == $_FILES['screenshot']['error']) {
            $message = _MD_GAMERS_ERRORMAXFILESIZEINI;
        }

        if (UPLOAD_ERR_FORM_SIZE == $_FILES['screenshot']['error']) {
            $message = _MD_GAMERS_ERRORMAXFILESIZEFORM;
        }

        // on error redirect to error page

        if (isset($message)) {
            redirect_header('index.php?op=screenshotform&mid=' . $_POST['mid'], 5, $message);

            exit();
        }

        // copy file to destination

        if (!move_uploaded_file($_FILES['screenshot']['tmp_name'], XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/screenshots/' . $_FILES['screenshot']['name'])) {
            redirect_header('index.php?op=screenshotform&mid=' . $_POST['mid'], 5, _MD_GAMERS_ERRORCOULDNOTCOPY);

            exit();
        }

        $matchmapid = (int)$matchmapid;

        // create thumbnail

        if (resizeToFile('screenshots/' . $_FILES['screenshot']['name'], 150, 113, XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/screenshots/thumbs/' . $_FILES['screenshot']['name'], 90)) {
            $sql = 'UPDATE ' . $xoopsDB->prefix('gamers_matchmaps') . ' SET screenshot = ' . $xoopsDB->quoteString($_FILES['screenshot']['name']) . " WHERE matchmapid = $matchmapid";

            if (!$xoopsDB->query($sql)) {
                redirect_header('index.php?op=screenshotform&mid=' . $_POST['mid'], 3, _MD_GAMERS_ERRORWHILESAVINGSCREENSHOT);

                exit();
            }

            redirect_header('index.php?op=screenshotform&mid=' . $_POST['mid'], 3, _MD_GAMERS_SCREENSHOTUPLOADED);

            exit();
        }

        redirect_header('index.php?op=screenshotform&mid=' . $_POST['mid'], 5, _MD_GAMERS_ERRORGDLIB);

        exit();
    }
    break;
    case 'deletescreenshot':
    $matchmapid = $matchmapid;
    $matchmapHandler = Helper::getInstance()->getHandler('MatchMap');
    $thismap = $matchmapHandler->get($matchmapid);
    $mid = $thismap->getVar('matchid');
    $filename = $thismap->getVar('screenshot');
    $result = $matchmapHandler->updateAll('screenshot', '', new \Criteria('matchmapid', $matchmapid), true);
    if (!$result) {
        redirect_header('index.php?op=screenshotform&mid=' . $mid, 5, _MD_GAMERS_ERRORDELETESCREENSHOT);
    }
    if (!unlink(XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/screenshots/' . $filename)) {
        redirect_header('index.php?op=screenshotform&mid=' . $mid, 5, _MD_GAMERS_ERRORDELETESCREENSHOTSERVER);
    }
    if (!unlink(XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/screenshots/thumbs/' . $filename)) {
        redirect_header('index.php?op=screenshotform&mid=' . $mid, 5, _MD_GAMERS_ERRORDELETETHUMBNAIL);
    }
    redirect_header('index.php?op=screenshotform&mid=' . $mid, 3, _MD_GAMERS_SCREENSHOTDELETED);
    break;
    // display matchlist
    case 'default':
    default:
    $teamid = isset($_GET['teamid']) && $_GET['teamid'] > 0 ? $_GET['teamid'] : getDefaultTeam();
    $start = isset($_GET['start']) ? (int)$_GET['start'] : 0;
    require_once XOOPS_ROOT_PATH . '/class/module.textsanitizer.php';

    $layout = getLayout();
    $curteam = $teamHandler->get($teamid);
    $curteam->select();
    $clause = 'LIMIT ' . $start . ' , 10';
    if (0 == $start) {
        $xoopsTpl->assign('prevstart', 0);

        $xoopsTpl->assign('nextstart', 10);
    } else {
        $prev = $start - 10;

        $next = $start + 10;

        $xoopsTpl->assign('prevstart', $prev);

        $xoopsTpl->assign('nextstart', $next);
    }
    $wins = 0;
    $losses = 0;
    $draws = 0;
    $teammember = 0;
    if ($xoopsUser) {
        if ($curteam->isMatchAdmin($xoopsUser->getVar('uid'))) {
            $xoopsTpl->assign('admin', 'yes');

            $xoopsTpl->assign('isTeamMember', 'yes');

            $teammember = 1;
        }

        if ($curteam->isTeamMember($xoopsUser->getVar('uid'))) {
            $xoopsTpl->assign('isTeamMember', 'yes');

            $teammember = 1;
        }
    }
    $mapno = $curteam->getVar('maps');
    for ($i = 1; $i <= $mapno; ++$i) {
        $xoopsTpl->append('captions', ['caption' => getCaption($i)]);
    }
    $count = 0;
    // get all ladders
    $ladders = getAllLadders();
    $hidden_ladders = [];
    foreach ($ladders as $ladderid => $thisladder) {
        if (0 == $thisladder['visible']) {
            $hidden_ladders[] = mb_strtolower((string) $thisladder['ladder']);
        } elseif (0 == $thisladder['scoresvisible']) {
            $hidden_scores[] = mb_strtolower((string) $thisladder['ladder']);
        }
    }
    // fetch matches from database
    $matches = $curteam->getMatches($clause);
    $allshorts = getAllSideShort();
    $matchmapHandler = Helper::getInstance()->getHandler('MatchMap');
    foreach ($matches as $mid => $match) {
        // only draw match if its ladder is not in $hidden_ladders and match result is not pending

        if (!('Pending' != $match->getVar('matchresult') && in_array(mb_strtolower((string) $match->getVar('ladder')), $hidden_ladders, true))) {
            $yes = $no = $wins = $losses = $noreply = 0;

            $pic = '';

            if (isset($all) or ($count < 10)) {
                if ('Pending' != $match->getVar('matchresult')) {
                    $count++;
                }

                $mdate = date(_MEDIUMDATESTRING, $match->getVar('matchdate'));

                $weekday = date('D', $match->getVar('matchdate'));

                $type = $match->getVar('ladder') . ' <nobr>' . $match->getVar('teamsize') . ' ' . _MD_GAMERS_VERSUS . ' ' . $match->getVar('teamsize') . '</nobr>';

                if (isset($class) && ('even' == $class)) {
                    $class = 'odd';
                } else {
                    $class = 'even';
                }

                $map = [];

                $nomaps = $curteam->getVar('maps');

                for ($count = 1; $count <= $nomaps; $count++) {
                    $mapno = $count;

                    $thismap = $matchmapHandler->getByMatchid($mid, $mapno);

                    if (is_object($thismap) && isset($allshorts[$thismap->getVar('side')])) {
                        $side = $allshorts[$thismap->getVar('side')];
                    } else {
                        $side = '';
                    }

                    $mapname = is_object($thismap->map) ? $thismap->map->getVar('mapname') : '--';

                    //Only show scores for matches for non-members if its ladder allows it

                    if (!isset($mapname) || (!$teammember && in_array(mb_strtolower((string) $match->getVar('ladder')), $hidden_scores, true))) {
                        $map[$mapno]['ourscore'] = 0;

                        $map[$mapno]['theirscore'] = 0;

                        $map[$mapno]['name'] = '';
                    } else {
                        $map[$mapno]['ourscore'] = $thismap->getVar('ourscore');

                        $map[$mapno]['theirscore'] = $thismap->getVar('theirscore');

                        $map[$mapno]['name'] = $mapname . ' (' . $side . ')';
                    }

                    if (('Pending' == $match->getVar('matchresult')) or (!$xoopsUser)) {
                        $map[$mapno]['color'] = $layout['color_match_pending'];
                    } else {
                        $map[$mapno]['color'] = $thismap->winner($layout);
                    }
                }

                if ('Win' == $match->getVar('matchresult')) {
                    $matchcolor = $layout['color_match_win'];

                    $wins++;
                } elseif ('Loss' == $match->getVar('matchresult')) {
                    $matchcolor = $layout['color_match_loss'];

                    $losses++;
                } elseif ('Draw' == $match->getVar('matchresult')) {
                    $matchcolor = $layout['color_match_draw'];

                    $draws++;
                } else {
                    $matchcolor = $layout['color_match_pending'];
                }

                if ($xoopsUser) {
                    if (1 == $teammember) {
                        $yes = 0;

                        $no = 0;

                        $noreply = 0;

                        $availabilities = $match->getAvailabilities();

                        while (false !== ($myav = $xoopsDB->fetchArray($availabilities))) {
                            if (('Yes' == $myav['availability']) or ('LateYes' == $myav['availability'])) {
                                $yes++;
                            } elseif (('No' == $myav['availability']) or ('LateNo' == $myav['availability'])) {
                                $no++;
                            } elseif (('Not Set' == $myav['availability']) or ('Sub' == $myav['availability'])) {
                                $noreply++;
                            }
                        }

                        if ('Pending' != $match->getVar('matchresult')) {
                            $pic = 'check';
                        } elseif (1 == $match->getVar('alock')) {
                            $pic = 'padlock';
                        } else {
                            $pic = 'notepad';
                        }
                    }
                }
            }

            $xoopsTpl->append('match', ['mid' => $mid, 'opponent' => $match->getVar('opponent'), 'matchresult' => $match->getVar('matchresult'), 'matchcolor' => $matchcolor, 'weekday' => $weekday, 'mdate' => $mdate, 'class' => $class, 'type' => $type, 'map' => $map, 'yes' => $yes, 'no' => $no, 'noreply' => $noreply, 'pic' => $pic]);
        } // if visible
    }
    $xoopsTpl->assign('wins', $wins);
    $xoopsTpl->assign('losses', $losses);
    $xoopsTpl->assign('draws', $draws);
    $xoopsTpl->assign('matchlistfor', _MD_GAMERS_MATCHLISTFOR);
    $xoopsTpl->assign('teamname', $curteam->getVar('teamname'));
    $xoopsTpl->assign('addmatch', _MD_GAMERS_SUBMITMATCH);
    $xoopsTpl->assign('teamdate', _MD_GAMERS_DATE);
    $xoopsTpl->assign('teamid', $teamid);
    $xoopsTpl->assign('teamopponent', _MD_GAMERS_OPPONENT);
    $xoopsTpl->assign('teammatchtype', _MD_GAMERS_MATCHTYPE);
    $xoopsTpl->assign('teamresult', _MD_GAMERS_RESULT);
    $xoopsTpl->assign('teamy', _MD_GAMERS_Y);
    $xoopsTpl->assign('teamn', _MD_GAMERS_N);
    $xoopsTpl->assign('teamwins', _MD_GAMERS_WINS);
    $xoopsTpl->assign('teamlosses', _MD_GAMERS_LOSSES);
    $xoopsTpl->assign('teamdraws', _MD_GAMERS_DRAWS);
    $xoopsTpl->assign('teamallmatches', _MD_GAMERS_ALLMATCHES);
    $xoopsTpl->assign('lang_prevmatches', _MD_GAMERS_PREVMATCHES);
    $xoopsTpl->assign('lang_nextmatches', _MD_GAMERS_NEXTMATCHES);
    break;
}
require_once XOOPS_ROOT_PATH . '/footer.php';
