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
    Helper,
    Player
};

/** @var Helper $helper */

$GLOBALS['xoopsOption']['template_main'] = 'gamers_tactics_list.tpl';

require_once __DIR__ . '/header.php';
//require_once __DIR__ . '/functions.php';

define('dirname', $xoopsModule->dirname());
//require_once XOOPS_ROOT_PATH . '/modules/' . dirname . '/class/player.php';
//require_once XOOPS_ROOT_PATH . '/modules/' . dirname . '/functions.php';

$op       = $_GET['op'] ?? 'default';
$tacid    = isset($_GET['tacid']) ? (int)$_GET['tacid'] : null;
$mapid    = isset($_GET['mapid']) ? (int)$_GET['mapid'] : null;
$teamsize = isset($_GET['teamsize']) ? (int)$_GET['teamsize'] : null;
$teamid   = isset($_GET['teamid']) ? (int)$_GET['teamid'] : null;
if (isset($_POST)) {
    foreach ($_POST as $k => $v) {
        ${$k} = $v;
    }
}
$tacticsHandler = Helper::getInstance()
                        ->getHandler('Tactics');
if ($xoopsUser) {
    $uid = $xoopsUser->getVar('uid');

    if (!isset($teamid)) {
        if (!isset($tacid)) {
            $thisplayer = new Player($uid);

            $team = $thisplayer->getTeams();

            foreach ($team as $statusid => $teamid) {
                $teamid = $teamid;
            }

            if (!isset($teamid)) {
                redirect_header('index.php', 3, _MD_GAMERS_ACCESSDENY);
            }
        } else {
            $tactic = $tacticsHandler->get($tacid);

            $teamid = $tactic->getVar('teamid');
        }
    }

    $teamHandler = Helper::getInstance()
                         ->getHandler('Team');

    $team = $teamHandler->get($teamid);

    if ($team->isTacticsAdmin($uid)) {
        $admin = 'Yes';
    } else {
        $admin = 'No';
    }

    if ($team->isTeamMember($uid)) {
        switch ($op) {
            case 'display':
                if (isset($tacid)) {
                    require XOOPS_ROOT_PATH . '/header.php';

                    $tactic = $tacticsHandler->get($tacid);

                    $tactic->show();
                } else {
                    redirect_header('tactics.php', 2, _MD_GAMERS_NOTACTICSSEL);

                    break;
                }
                break;
            case 'mantactics':
                require XOOPS_ROOT_PATH . '/header.php';
                if (isset($mapid) && isset($teamid) && isset($teamsize)) {
                    $tactic = $tacticsHandler->getByParams($teamid, $mapid, $teamsize);

                    $action = 'Add';
                } elseif (isset($tacid)) {
                    $tactic = $tacticsHandler->get($tacid);

                    $action = 'Edit';
                }
                $teamsize = $tactic->getVar('teamsize');
                require XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
                $mform         = new \XoopsThemeForm($teamsize . ' ' . _MD_GAMERS_VERSUS . ' ' . $teamsize . ' ' . _MD_GAMERS_TACTICSFOR . ' ' . $team->getVar('teamname') . ' ' . _MD_GAMERS_ON . ' ' . (is_object($tactic->map) ? $tactic->map->getVar('mapname') : '??'), 'savetactics', xoops_getenv('PHP_SELF'));
                $general       = new \XoopsFormTextArea(_MD_GAMERS_GENERALTACS, 'general', $tactic->getVar('general'));
                $teampositions = $team->getPositions();
                if ($tactic->getVar('tacid')) {
                    $tacticspositions = $tactic->getPositions();
                }
                $mform->addElement($general);
                $tacpos          = '';
                $positionHandler = Helper::getInstance()
                                         ->getHandler('TacticsPosition');
                for ($i = 0; $i < $teamsize; ++$i) {
                    if (isset($tacticspositions[$i])) {
                        $thispos = $positionHandler->get($tacticspositions[$i]);

                        $thisposid = $thispos->getVar('posid');

                        $thisposdesc = $thispos->getVar('posdesc');

                        $tacpos .= $thispos->getVar('tacposid') . ':';
                    } else {
                        $thisposid = 0;

                        $thisposdesc = '';
                    }

                    $position_select[$i] = new \XoopsFormSelect(_MD_GAMERS_POSITION . ($i + 1), 'posid[' . $i . ']', $thisposid);

                    foreach ($teampositions as $positionid => $positionname) {
                        $position_select[$i]->addOption($positionid, $positionname);
                    }

                    $description[$i] = new \XoopsFormTextArea(_MD_GAMERS_DESCRIPTION, 'posdesc[' . $i . ']', $thisposdesc);

                    $mform->addElement($position_select[$i]);

                    $mform->addElement($description[$i]);
                }
                $button_tray = new \XoopsFormElementTray('', '');
                $submit      = new \XoopsFormButton('', 'action', $action, 'Submit');
                $button_tray->addElement($submit);
                if (isset($tacpos)) {
                    $tacpos_hidden = new \XoopsFormHidden('tacpos', $tacpos);

                    $mform->addElement($tacpos_hidden);
                }
                $teamsize_hidden = new \XoopsFormHidden('teamsize', $tactic->getVar('teamsize'));
                $tacid_hidden    = new \XoopsFormHidden('tacid', $tactic->getVar('tacid'));
                $mapid_hidden    = new \XoopsFormHidden('mapid', $tactic->getVar('mapid'));
                $teamid_hidden   = new \XoopsFormHidden('teamid', $tactic->getVar('teamid'));
                $op_hidden       = new \XoopsFormHidden('op', 'savetactics');
                $mform->addElement($teamsize_hidden);
                $mform->addElement($tacid_hidden);
                $mform->addElement($teamid_hidden);
                $mform->addElement($mapid_hidden);
                $mform->addElement($op_hidden);
                $mform->addElement($button_tray);
                $mform->display();
                break;
            case 'savetactics':
                if ('Edit' == $action) {
                    $tactic = $tacticsHandler->get($tacid);
                } else {
                    $tactic = $tacticsHandler->create();

                    $tactic->setVar('mapid', $mapid);

                    $tactic->setVar('teamid', $teamid);

                    $tactic->setVar('teamsize', $teamsize);
                }
                $tactic->setVar('general', $general);
                if ($tacticsHandler->insert($tactic)) {
                    $tacid = $tactic->getVar('tacid');

                    $tacpos = explode(':', $tacpos);

                    $tacserrors = 0;

                    $positionHandler = Helper::getInstance()
                                             ->getHandler('TacticsPosition');

                    for ($i = 0; $i < $teamsize; ++$i) {
                        $thispos = $positionHandler->create();

                        if (isset($tacpos[$i]) && $tacpos[$i]) {
                            $thispos->setVar('tacposid', $tacpos[$i]);

                            $thispos->unsetNew();
                        }

                        $thispos->setVar('posid', $posid[$i]);

                        $thispos->setVar('posdesc', $posdesc[$i]);

                        $thispos->setVar('tacid', $tacid);

                        if (!$positionHandler->insert($thispos)) {
                            $tacserros++;
                        }
                    }

                    if ($tacserrors > 0) {
                        redirect_header('tactics.php?teamid=' . $teamid, 3, $tacserrors . _MD_GAMERS_TACTICSERRORS);
                    } else {
                        if ('Add' == $action) {
                            redirect_header('tactics.php?teamid=' . $teamid, 3, _MD_GAMERS_TACTICSADDED);
                        } else {
                            redirect_header('tactics.php?teamid=' . $teamid, 3, _MD_GAMERS_TACTICSEDITED);
                        }
                    }
                } else {
                    redirect_header('tactics.php?teamid=' . $teamid, 3, _MD_GAMERS_GENERALTACSERROR);
                }
                break;
            case 'default':
            default:
                require XOOPS_ROOT_PATH . '/header.php';
                $sizes     = [];
                $tactics   = [];
                $teamsizes = $team->getTeamSizes();
                $count     = is_countable($teamsizes) ? count($teamsizes) : 0;
                if ($team->isTeamAdmin($uid)) {
                    $colspan = $count * 2;

                    $firstspan = 2;
                } else {
                    $colspan = $count;

                    $firstspan = 1;
                }
                $headspan = $colspan - 1;

                foreach ($teamsizes as $teamsize) {
                    $sizes[] = $teamsize;
                }

                $maps = $team->getMaps();
                foreach (array_keys($maps) as $mapid) {
                    for ($i = 0; $i < $count; ++$i) {
                        $tactics[$mapid][$sizes[$i]] = $tacticsHandler->getByParams($teamid, $mapid, $sizes[$i]);
                    }
                }
                $team->select();
                $xoopsTpl->assign('teamsizes', $sizes);
                $xoopsTpl->assign('team', $team->toArray());
                $xoopsTpl->assign('headspan', $headspan);
                $xoopsTpl->assign('firstspan', $firstspan);
                $xoopsTpl->assign('maps', $maps);
                $xoopsTpl->assign('tactics', $tactics);
                $xoopsTpl->assign('admin', $admin);
                $xoopsTpl->assign('url', XOOPS_URL . '/modules/' . $moduleDirName);
                break;
        }
    } else {
        redirect_header('index.php?teamid=' . $teamid, 3, _MD_GAMERS_ACCESSDENY);
    }
} else {
    redirect_header('index.php?teamid=' . $teamid, 3, _MD_GAMERS_MEMBAREA);
}
require_once XOOPS_ROOT_PATH . '/footer.php';
