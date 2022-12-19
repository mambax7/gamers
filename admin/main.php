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

use Xmf\Module\Admin;
use Xmf\Request;
use XoopsModules\Gamers\{
    Helper
};

require_once __DIR__ . '/admin_header.php';
require dirname(__DIR__, 3) . '/include/cp_header.php';
require dirname(__DIR__) . '/functions.php';
require __DIR__ . '/functions.php';

$op = $_GET['op'] ?? 'default';
$posid = isset($_GET['posid']) ? (int)$_GET['posid'] : 'default';
$op = $_POST['op'] ?? $op;
if (!isset($_POST['action'])) {
    $action = '';
}

/**
 * @param string $id
 */
function ladderedit($id = '')
{
    global $xoopsDB;

    $op = 'addladder';

    $action = 'Add';

    $laddername = '';

    $ladderid = '';

    $laddervisible = 1;

    $scoresvisible = 0;

    if ($id) {
        $sql = 'SELECT * FROM ' . $xoopsDB->prefix('gamers_ladders') . ' WHERE ladderid=' . (int)$id;

        $result = $xoopsDB->query($sql);

        if (!$xoopsDB->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
        }

        while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
            $ladderid = $myrow['ladderid'];

            $laddername = $myrow['ladder'];

            $laddervisible = $myrow['visible'];

            $scoresvisible = $myrow['scoresvisible'];

            $op = 'editladder';

            $action = 'Edit';
        }
    }

    require XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    $pform = new \XoopsThemeForm($action . ' Ladder', 'ladderform', xoops_getenv('PHP_SELF'));

    $button_tray = new \XoopsFormElementTray('', '');

    $submit = new \XoopsFormButton('', 'select', $action, 'submit');

    $op_hidden = new \XoopsFormHidden('op', $op);

    $name = new \XoopsFormText(_AM_GAMERS_LADDERNAME, 'laddername', 32, 32, $laddername, 'E');

    $visible = new \XoopsFormRadioYN(_AM_GAMERS_LADDERVISIBLE, 'laddervisible', $laddervisible, _AM_GAMERS_YES, _AM_GAMERS_NO);

    $scores = new \XoopsFormRadioYN(_AM_GAMERS_SCORESVISIBLE, 'scoresvisible', $scoresvisible, _AM_GAMERS_YES, _AM_GAMERS_NO);

    $id = new \XoopsFormHidden('ladderid', $ladderid);

    $button_tray->addElement($submit);

    $pform->addElement($name);

    $pform->addElement($visible);

    $pform->addElement($scores);

    $pform->addElement($id);

    $pform->addElement($button_tray);

    $pform->addElement($op_hidden);

    $pform->display();
}

/**
 * @param string $id
 */
function posedit($id = '')
{
    global $xoopsDB;

    $op = 'addpos';

    $action = 'Add';

    $posid = '';

    $postype = 'Pos';

    $posname = '';

    $posshort = ' ';

    if ($id) {
        $sql = 'SELECT * FROM ' . $xoopsDB->prefix('gamers_positions') . ' WHERE posid=' . (int)$id;

        $result = $xoopsDB->query($sql);

        if (!$xoopsDB->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
        }

        while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
            $posid = $myrow['posid'];

            $postype = $myrow['postype'];

            $posname = $myrow['posname'];

            $posshort = $myrow['posshort'];

            $op = 'editpos';

            $action = 'Edit';
        }
    }

    require XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    $pform = new \XoopsThemeForm($action . ' Position', 'posform', xoops_getenv('PHP_SELF'));

    $button_tray = new \XoopsFormElementTray('', '');

    $submit = new \XoopsFormButton('', 'select', $action, 'submit');

    $op_hidden = new \XoopsFormHidden('op', $op);

    $name = new \XoopsFormText(_AM_GAMERS_POSITIONNAME, 'posname', 35, 35, $posname, 'E');

    $short = new \XoopsFormText(_AM_GAMERS_POSITIONSHORT, 'posshort', 20, 20, $posshort, 'E');

    $type = new \XoopsFormRadio(_AM_GAMERS_POSITIONTYPE, 'postype', $postype);

    $id = new \XoopsFormHidden('posid', $posid);

    $button_tray->addElement($submit);

    $type->addOption('Pos', 'Pos');

    $type->addOption('Skill', 'Skill');

    $pform->addElement($name);

    $pform->addElement($short);

    $pform->addElement($type);

    $pform->addElement($id);

    $pform->addElement($button_tray);

    $pform->addElement($op_hidden);

    $pform->display();
}

/**
 * @param string $id
 */
function mapedit($id = '')
{
    //global $xoopsDB;

    $op = 'addmap';

    $action = _AM_GAMERS_ADD;

    $mapid = '';

    $mapname = '';

    if ('' != $id) {
        $mapHandler = Helper::getInstance()->getHandler('Map');

        $mapArray = $mapHandler->get($id);
//        list($mapid, $mapname) = $mapArray;

        $mapid = $mapArray->getVar('mapid');

        $mapname = $mapArray->getVar('mapname');

        $op = 'editmap';

        $action = _AM_GAMERS_EDIT;
    }

    require XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    $mform = new \XoopsThemeForm(_AM_GAMERS_MAPMNGR, 'mapedit', xoops_getenv('PHP_SELF'));

    $button_tray = new \XoopsFormElementTray('', '');

    $submit = new \XoopsFormButton('', 'select', $action, 'submit');

    $op_hidden = new \XoopsFormHidden('op', $op);

    $mapid_hidden = new \XoopsFormHidden('mapid', $mapid);

    $name = new \XoopsFormText(_AM_GAMERS_NEWMAPNAME, 'mapname', 25, 25, $mapname, 'E');

    $button_tray->addElement($submit);

    $mform->addElement($name);

    $mform->addElement($button_tray);

    $mform->addElement($op_hidden);

    $mform->addElement($mapid_hidden);

    $mform->display();
}

/**
 * @param        $action
 * @param string $serverid
 */
function serverForm($action, $serverid = '')
{
    if ('Edit' == $action) {
        $submittext = _AM_GAMERS_EDITSERVER;
    } else {
        $submittext = _AM_GAMERS_ADDSERVER;
    }

    require XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    $mform = new \XoopsThemeForm(_AM_GAMERS_ADDSERVER, 'serverform', xoops_getenv('PHP_SELF'));

    $op_hidden = new \XoopsFormHidden('op', 'saveserver');

    $submit = new \XoopsFormButton('', 'submit', $submittext, 'submit');

    $action_hidden = new \XoopsFormHidden('action', $action);

    $button_tray = new \XoopsFormElementTray('', '');

    if ('' != $serverid) {
        $server = getServer($serverid);

        $name = $server['name'];

        $ip = $server['ip'];

        $port = $server['port'];

        $serverid_hidden = new \XoopsFormHidden('serverid', $serverid);

        $mform->addElement($serverid_hidden);
    } else {
        $name = 'Servername';

        $ip = 'IP';

        $port = 'Port';
    }

    $name = new \XoopsFormText(_AM_GAMERS_SERVERNAME, 'servername', 30, 30, $name, 'E');

    $ip = new \XoopsFormText(_AM_GAMERS_SERVERIP, 'serverip', 20, 20, $ip, 'E');

    $port = new \XoopsFormText(_AM_GAMERS_SERVERPORT, 'serverport', 10, 10, $port, 'E');

    $button_tray->addElement($submit);

    $mform->addElement($name);

    $mform->addElement($ip);

    $mform->addElement($port);

    $mform->addElement($op_hidden);

    $mform->addElement($action_hidden);

    $mform->addElement($button_tray);

    $mform->display();
}

/**
 * @param string $action
 * @param string $sizeId
 */
function addSizeForm($action = _AM_GAMERS_ADD, $sizeId = '')
{
    global $xoopsDB;

    require XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    $size = 0;

    $mform = new \XoopsThemeForm(_AM_GAMERS_ADDSIZE, 'sizeform', xoops_getenv('PHP_SELF'));

    if ('' != $sizeId) {
        $sql = 'SELECT size FROM ' . $xoopsDB->prefix('gamers_sizes') . ' WHERE sizeid=' . (int)$sizeId;

        $result = $xoopsDB->query($sql);

        if (!$xoopsDB->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
        }

        $size = $xoopsDB->fetchArray($result)['size'];

        $sizeid_hidden = new \XoopsFormHidden('sizeid', $sizeId);

        $mform->addElement($sizeid_hidden);

        $action = _AM_GAMERS_EDIT;
    }

    $action_hidden = new \XoopsFormHidden('action', $action);

    $op_hidden = new \XoopsFormHidden('op', 'savesize');

    $submit = new \XoopsFormButton('', 'submit', $action, 'submit');

    $button_tray = new \XoopsFormElementTray('', '');

    $teamsize = new \XoopsFormText(_AM_GAMERS_SIZENAME, 'size', 20, 20, $size, 'E');

    $button_tray->addElement($submit);

    $mform->addElement($teamsize);

    $mform->addElement($op_hidden);

    $mform->addElement($action_hidden);

    $mform->addElement($button_tray);

    $mform->display();
}

/**
 * @param string $action
 * @param string $sideId
 */
function addSideForm($action = _AM_GAMERS_ADD, $sideId = '')
{
    global $xoopsDB;

    require XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    $sideText = _AM_GAMERS_ADDSIDE;

    $sideShortText = _AM_GAMERS_SIDESHORT;

    $mform = new \XoopsThemeForm(_AM_GAMERS_ADDSIDE, 'sideform', xoops_getenv('PHP_SELF'));

    if ($sideId) {
        $sql
                       =
            'SELECT side, sideshort FROM ' . $xoopsDB->prefix('gamers_sides') . ' WHERE sideid=' . (int)$sideId;

        $result = $xoopsDB->query($sql);

        if (!$xoopsDB->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
        }

        $currentSide = $xoopsDB->fetchArray($result);

        $sideText = $currentSide['side'];

        $sideShortText = $currentSide['sideshort'];

        $sideid_hidden = new \XoopsFormHidden('sizeid', $sideId);

        $mform->addElement($sideid_hidden);

        $action = _AM_GAMERS_EDIT;
    }

    $action_hidden = new \XoopsFormHidden('action', $action);

    $op_hidden = new \XoopsFormHidden('op', 'saveside');

    $submit = new \XoopsFormButton('', 'submit', $action, 'submit');

    $button_tray = new \XoopsFormElementTray('', '');

    $teamside = new \XoopsFormText(_AM_GAMERS_SIDENAME, 'side', 12, 20, $sideText, 'E');

    $sideshort = new \XoopsFormText(_AM_GAMERS_SIDESHORT, 'sideshort', 5, 20, $sideShortText, 'E');

    $button_tray->addElement($submit);

    $mform->addElement($teamside);

    $mform->addElement($sideshort);

    $mform->addElement($op_hidden);

    $mform->addElement($button_tray);

    $mform->addElement($action_hidden);

    $mform->display();
}

/**
 * @param string $rankid
 */
function rankform($rankid = '')
{
    $thisrank = [];
    global $xoopsDB;

    require XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    $rform = new \XoopsThemeForm(_AM_GAMERS_ADDRANK, 'rankform', xoops_getenv('PHP_SELF'));

    if ($rankid) {
        $sql
                       =
            'SELECT rankid, rank, matches, tactics, color FROM ' . $xoopsDB->prefix('gamers_rank') . ' WHERE rankid='
            . (int)$rankid;

        $result = $xoopsDB->query($sql);

        if (!$xoopsDB->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
        }

        $thisrank = $xoopsDB->fetchArray($result);

        $action = 'Edit';

        $submittext = _AM_GAMERS_EDITRANK;

        $rankid_hidden = new \XoopsFormHidden('rankid', $rankid);

        $rform->addElement($rankid_hidden);
    } else {
        $action = 'Add';

        $submittext = _AM_GAMERS_ADDRANK;

        $thisrank['rank'] = 'Input Rank';

        $thisrank['tactics'] = 0;

        $thisrank['matches'] = 0;

        $thisrank['color'] = '#007700';
    }

    $op_hidden = new \XoopsFormHidden('op', 'saverank');

    $action_hidden = new \XoopsFormHidden('action', $action);

    $submit = new \XoopsFormButton('', 'submit', $submittext, 'submit');

    $button_tray = new \XoopsFormElementTray('', '');

    $rank = new \XoopsFormText(_AM_GAMERS_RANK, 'rank', 20, 20, $thisrank['rank'], 'E');

    $tactics = new \XoopsFormRadioYN(_AM_GAMERS_TACTICSRANK, 'tactics', $thisrank['tactics'], _YES, _NO);

    $matches = new \XoopsFormRadioYN(_AM_GAMERS_MATCHRANK, 'matches', $thisrank['matches'], _YES, _NO);

    $color = new \XoopsFormColorPicker(_AM_GAMERS_RANKCOLOR, 'color', $thisrank['color']);

    $button_tray->addElement($submit);

    $rform->addElement($rank);

    $rform->addElement($op_hidden);

    $rform->addElement($action_hidden);

    $rform->addElement($tactics);

    $rform->addElement($matches);

    $rform->addElement($color);

    $rform->addElement($button_tray);

    $rform->display();
}

/**
 * @param $data
 */
function layoutform($data)
{
    require XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    $lform = new \XoopsThemeForm('Edit Layout', 'layform', xoops_getenv('PHP_SELF'));

    $button_tray = new \XoopsFormElementTray('', '');

    $submit = new \XoopsFormButton('', 'submit', _AM_GAMERS_SAVE, 'submit');

    $op_hidden = new \XoopsFormHidden('op', 'savelayout');

    $color_status_active = new \XoopsFormColorPicker(_AM_GAMERS_LAYOUTCOLORSTATUSACTIVE, 'color_status_active', $data['color_status_active']);

    $color_status_inactive = new \XoopsFormColorPicker(_AM_GAMERS_LAYOUTCOLORSTATUSINACTIVE, 'color_status_inactive', $data['color_status_inactive'], 'E');

    $color_status_onleave = new \XoopsFormColorPicker(_AM_GAMERS_LAYOUTCOLORSTATUSONLEAVE, 'color_status_onleave', $data['color_status_onleave']);

    $color_match_win = new \XoopsFormColorPicker(_AM_GAMERS_LAYOUTCOLORSTATUSWIN, 'color_match_win', $data['color_match_win']);

    $color_match_loss = new \XoopsFormColorPicker(_AM_GAMERS_LAYOUTCOLORSTATUSLOSS, 'color_match_loss', $data['color_match_loss']);

    $color_match_draw = new \XoopsFormColorPicker(_AM_GAMERS_LAYOUTCOLORSTATUSDRAW, 'color_match_draw', $data['color_match_draw']);

    $color_match_pending = new \XoopsFormColorPicker(_AM_GAMERS_LAYOUTCOLORSTATUSPENDING, 'color_match_pending', $data['color_match_pending']);

    $color_perfect = new \XoopsFormColorPicker(_AM_GAMERS_LAYOUTPERFECTCOLOR, 'color_perfect', $data['color_perfect']);

    $color_good = new \XoopsFormColorPicker(_AM_GAMERS_LAYOUTGOODCOLOR, 'color_good', $data['color_good']);

    $color_warn = new \XoopsFormColorPicker(_AM_GAMERS_LAYOUTWARNCOLOR, 'color_warn', $data['color_warn']);

    $color_bad = new \XoopsFormColorPicker(_AM_GAMERS_LAYOUTBADCOLOR, 'color_bad', $data['color_bad']);

    $button_tray->addElement($submit);

    //$lform->addElement($color_status_active0);

    $lform->addElement($color_status_active);

    $lform->addElement($color_status_inactive);

    $lform->addElement($color_status_onleave);

    $lform->addElement($color_match_win);

    $lform->addElement($color_match_loss);

    $lform->addElement($color_match_draw);

    $lform->addElement($color_match_pending);

    $lform->addElement($color_perfect);

    $lform->addElement($color_good);

    $lform->addElement($color_warn);

    $lform->addElement($color_bad);

    $lform->addElement($button_tray);

    $lform->addElement($op_hidden);

    $lform->display();
}

xoops_cp_header();
$teamHandler = Helper::getInstance()->getHandler('Team');
switch ($op) {
    case 'savelayout':
        if (_AM_GAMERS_SAVE == $_POST['submit']) {
            $sql = 'UPDATE ' . $xoopsDB->prefix('gamers_layout') . ' SET color_status_active = '
                . $xoopsDB->quoteString($_POST['color_status_active']) . ',' . ' color_status_inactive='
                . $xoopsDB->quoteString($_POST['color_status_inactive']) . ',' . ' color_status_onleave='
                . $xoopsDB->quoteString($_POST['color_status_onleave']) . ',' . ' color_match_win='
                . $xoopsDB->quoteString($_POST['color_match_win']) . ',' . ' color_match_loss='
                . $xoopsDB->quoteString($_POST['color_match_loss']) . ',' . ' color_match_draw='
                . $xoopsDB->quoteString($_POST['color_match_draw']) . ',' . ' color_match_pending='
                . $xoopsDB->quoteString($_POST['color_match_pending']) . ',' . ' color_perfect = '
                . $xoopsDB->quoteString($_POST['color_perfect']) . ',' . ' color_good = '
                . $xoopsDB->quoteString($_POST['color_good']) . ',' . ' color_warn = '
                . $xoopsDB->quoteString($_POST['color_warn']) . ',' . ' color_bad = '
                . $xoopsDB->quoteString($_POST['color_bad']) . ' WHERE layoutid = 1';

            if (!$xoopsDB->query($sql)) {
                redirect_header('main.php?op=layoutmanager', 3, _AM_GAMERS_ERRORWHILESAVINGLAYOUT);
            }

            redirect_header('main.php?op=layoutmanager', 3, _AM_GAMERS_LAYOUTSAVED);
        }
        break;
    case 'saveteam':
        if ('Add' == $_POST['submit']) {
            $thisteam = $teamHandler->create();

            $message = _AM_GAMERS_ADDED;
        } else {
            $thisteam = $teamHandler->get((int)$_POST['teamid']);

            $message = _AM_GAMERS_EDITED;
        }
        $thisteam->setVar('teamname', $_POST['name']);
        $thisteam->setVar('teamtype', $_POST['type']);
        $thisteam->setVar('maps', $_POST['maps']);
        if (!getDefaultTeam()) {
            $thisteam->setVar('defteam', 1);
        } else {
            $thisteam->setVar('defteam', 0);
        }
        if ($teamHandler->insert($thisteam)) {
            redirect_header('teamadmin.php?teamid=' . $thisteam->getVar('teamid'), 3, $_POST['name'] . ' ' . $message);

            break;
        }
            $errors = 1;

        if (isset($errors)) {
            redirect_header('main.php?op=teammanager', 3, _AM_GAMERS_ERRORWHILESAVINGGAMERS_);
        }
        break;
    case 'saverank':
        if ('Add' == $_POST['action']) {
            $sql = 'INSERT INTO ' . $xoopsDB->prefix('gamers_rank') . ' (rank, tactics, matches, color) VALUES ('
                . $xoopsDB->quoteString($_POST['rank']) . ', ' . (int)$_POST['tactics'] . ', '
                   . (int)$_POST['matches'] . ', ' . $xoopsDB->quoteString($_POST['color']) . ')';

            $comment = $_POST['rank'] . ' Added';
        } elseif ('Edit' == $_POST['action']) {
            $sql
                     =
                'UPDATE ' . $xoopsDB->prefix('gamers_rank') . ' SET rank = ' . $xoopsDB->quoteString($_POST['rank'])
                    . ', matches=' . (int)$_POST['matches'] . ', tactics=' . (int)$_POST['tactics'] . ', color='
                    . $xoopsDB->quoteString($_POST['color']) . '  WHERE rankid=' . (int)$_POST['rankid'];

            $comment = $_POST['rank'] . ' Edited';
        }
        if (!$xoopsDB->query($sql)) {
            $comment = _AM_GAMERS_ERRORWHILESAVINGRANK;
        }
        redirect_header('main.php?op=rankmanager', 3, $comment);
        break;
    case 'addpos':
        $sql = 'INSERT INTO ' . $xoopsDB->prefix('gamers_positions') . ' (postype, posname, posshort) VALUES ('
            . $xoopsDB->quoteString($_POST['postype']) . ', ' . $xoopsDB->quoteString($_POST['posname']) . ', '
            . $xoopsDB->quoteString($_POST['posshort']) . ')';
        $xoopsDB->query($sql);
        redirect_header('main.php?op=positionmanager', 3, _AM_GAMERS_POSITIONADDED);
        break;
    case 'editpos':
        if (isset($_POST['postype'])) {
            $sql = 'UPDATE ' . $xoopsDB->prefix('gamers_positions') . ' SET postype='
                . $xoopsDB->quoteString($_POST['postype']) . ', posname=' . $xoopsDB->quoteString($_POST['posname'])
                . ', posshort=' . $xoopsDB->quoteString($_POST['posshort']) . ' WHERE posid=' . (int)$_POST['posid'];

            $xoopsDB->query($sql);

            redirect_header('main.php?op=positionmanager', 3, _AM_GAMERS_POSITIONMODIFIED);

            break;
        }
        break;
    case 'deletepos':
        if (!empty($_POST['ok'])) {
            if (empty($_POST['posid'])) {
                redirect_header('main.php?op=positionmanager', 2, _AM_GAMERS_EMPTYNODELETE);

                break;
            }

            $sql = 'DELETE FROM ' . $xoopsDB->prefix('gamers_positions') . ' WHERE posid=' . (int)$_POST['posid'];

            $xoopsDB->query($sql);

            redirect_header('main.php?op=positionmanager', 3, _AM_GAMERS_POSITIONDELETED);

            break;
        }
            echo '<h4>' . _AM_GAMERS_TEAM_CONFIG . '</h4>';
            xoops_confirm(
                [
                    'op' => 'deletepos',
                    'posid' => $_GET['posid'],
                    'ok' => 1,
                ],
                'main.php',
                _AM_GAMERS_RUSUREDELPOS
            );

        break;
    case 'addladder':
        $sql = 'INSERT INTO ' . $xoopsDB->prefix('gamers_ladders') . ' (ladder, visible, scoresvisible) VALUES ('
            . $xoopsDB->quoteString($_POST['laddername']) . ', ' . (int)$_POST['laddervisible'] . ', '
               . (int)$_POST['scoresvisible'] . ')';
        $xoopsDB->query($sql);
        redirect_header('main.php?op=laddermanager', 3, _AM_GAMERS_LADDERADDED);
        break;
    case 'editladder':
        $sql
            =
            'UPDATE ' . $xoopsDB->prefix('gamers_ladders') . ' SET ladder=' . $xoopsDB->quoteString($_POST['laddername'])
                . ', visible=' . (int)$_POST['laddervisible'] . ', scoresvisible=' . (int)$_POST['scoresvisible']
                . ' WHERE ladderid=' . (int)$_POST['ladderid'];
        $xoopsDB->query($sql);
        redirect_header('main.php?op=laddermanager', 3, _AM_GAMERS_LADDERMODIFIED);
        break;
    case 'deleteladder':
        if (!empty($_POST['ok'])) {
            if (empty($_POST['ladderid'])) {
                redirect_header('main.php?op=laddermanager', 2, _AM_GAMERS_EMPTYNODELETE);

                break;
            }

            $sql = 'DELETE FROM ' . $xoopsDB->prefix('gamers_ladders') . ' WHERE ladderid=' . (int)$_POST['ladderid'];

            $xoopsDB->query($sql);

            redirect_header('main.php?op=laddermanager', 3, _AM_GAMERS_LADDERDELETED);

            break;
        }
            echo '<h4>' . _AM_GAMERS_TEAM_CONFIG . '</h4>';
            xoops_confirm(
                [
                    'op' => 'deleteladder',
                    'ladderid' => (int)$_GET['ladderid'],
                    'ok' => 1,
                ],
                'main.php',
                _AM_GAMERS_RUSUREDELLADDER
            );

        break;
    case 'addmap':
        $sql = 'INSERT INTO ' . $xoopsDB->prefix('gamers_mappool') . ' (mapname) VALUES ('
            . $xoopsDB->quoteString($_POST['mapname']) . ')';
        $xoopsDB->query($sql);
        redirect_header('main.php?op=mappoolmanager', 3, $_POST['mapname'] . ' ' . _AM_GAMERS_ADDEDTOMAPPOOL . '');
        break;
    case 'editmap':
        if (isset($_POST['mapname'])) {
            $sql = 'UPDATE ' . $xoopsDB->prefix('gamers_mappool') . ' SET mapname='
                . $xoopsDB->quoteString($_POST['mapname']) . ' WHERE mapid=' . (int)$_POST['mapid'];

            $xoopsDB->query($sql);

            redirect_header('main.php?op=mappoolmanager', 3, _AM_GAMERS_MAPNAMEMODIF);

            break;
        }
        break;
    case 'deletemap':
        if (!empty($_POST['ok'])) {
            if (empty($_POST['mapid'])) {
                redirect_header('main.php?op=mappoolmanager', 2, _AM_GAMERS_EMPTYNODELETE);

                exit();
            }

            $sql = 'DELETE FROM ' . $xoopsDB->prefix('gamers_mappool') . ' WHERE mapid=' . (int)$_POST['mapid'];

            $xoopsDB->query($sql);

            redirect_header('main.php?op=mappoolmanager', 3, _AM_GAMERS_MAPDELFROMPOOLMAP);
        } else {
            echo '<h4>' . _AM_GAMERS_TEAM_CONFIG . '</h4>';

            xoops_confirm(
                [
                    'op' => 'deletemap',
                    'mapid' => (int)$_GET['mapid'],
                    'ok' => 1,
                ],
                'main.php',
                _AM_GAMERS_RUSUREDELMAP
            );
        }
        break;
    case 'deleteteam':
        if (!empty($_POST['ok'])) {
            if (empty($_POST['teamid'])) {
                redirect_header('main.php?op=teammanager', 2, _AM_GAMERS_EMPTYNODELETE);

                exit();
            }

            $teamid = (int)$_POST['teamid'];

            $team = $teamHandler->get($teamid);

            $teamHandler->delete($team);

            redirect_header('main.php?op=teammanager', 3, _AM_GAMERS_DELETED);
        } else {
            echo '<h4>' . _AM_GAMERS_TEAM_CONFIG . '</h4>';

            xoops_confirm(
                [
                    'op' => 'deleteteam',
                    'teamid' => (int)$_GET['teamid'],
                    'ok' => 1,
                ],
                'main.php',
                _AM_RUSUREDELGAMERS_
            );
        }
        break;
    case 'deleteserver':
        if (!empty($_POST['ok'])) {
            if (empty($_POST['serverid'])) {
                redirect_header('main.php?op=default', 2, _AM_GAMERS_EMPTYNODELETE);

                exit();
            }

            $serverid = (int)$_POST['serverid'];

            $sql = 'DELETE FROM ' . $xoopsDB->prefix('gamers_server') . " WHERE serverid=$serverid";

            if ($xoopsDB->query($sql)) {
                $sql = 'DELETE FROM ' . $xoopsDB->prefix('server_bookings') . " WHERE serverid=$serverid";

                if ($xoopsDB->query($sql)) {
                    redirect_header('main.php?op=servermanager', 3, _AM_GAMERS_SERVERDELETED);
                } else {
                    redirect_header('main.php?op=servermanager', 3, _AM_GAMERS_SERVERDELBOOKNOT);
                }
            } else {
                redirect_header('main.php?op=servermanager', 3, _AM_GAMERS_ERRSERVERNOTDEL);
            }
        } else {
            echo '<h4>' . _AM_GAMERS_TEAM_CONFIG . '</h4>';

            xoops_confirm(
                [
                    'op' => 'deleteserver',
                    'serverid' => (int)$_GET['serverid'],
                    'ok' => 1,
                ],
                'main.php',
                _AM_GAMERS_RUSUREDELSERVER
            );
        }
        break;
    case 'deleterank':
        if (!empty($_POST['ok'])) {
            if (empty($_POST['rankid'])) {
                redirect_header('main.php?op=rankmanager', 2, _AM_GAMERS_EMPTYNODELETE);

                exit();
            }

            $sql = 'DELETE FROM ' . $xoopsDB->prefix('gamers_rank') . ' WHERE rankid=' . (int)$_POST['rankid'];

            if ($xoopsDB->query($sql)) {
                redirect_header('main.php?op=rankmanager', 1, _AM_GAMERS_DBUPDATED);

                exit();
            }
        } else {
            echo '<h4>' . _AM_GAMERS_TEAM_CONFIG . '</h4>';

            xoops_confirm(
                [
                    'op' => 'deleterank',
                    'rankid' => (int)$_GET['rankid'],
                    'ok' => 1,
                ],
                'main.php',
                _AM_GAMERS_RUSUREDELRANK
            );
        }
        break;
    case 'deletematch':
        if (!empty($_POST['ok'])) {
            if (empty($_POST['matchid'])) {
                redirect_header('main.php?op=matchmanager', 2, _AM_GAMERS_EMPTYNODELETE);

                break;
            }

            $matchid = (int)$_POST['matchid'];

            $matchHandler = Helper::getInstance()->getHandler('Match');

            $match = $matchHandler->get($matchid);

            $matchHandler->delete($match);

            redirect_header('main.php?op=matchmanager', 1, _AM_GAMERS_DBUPDATED);

            break;
        }
            echo '<h4>' . _AM_GAMERS_MATCH_CONFIG . '</h4>';
            xoops_confirm(
                [
                    'op' => 'deletematch',
                    'matchid' => (int)$_GET['matchid'],
                    'ok' => 1,
                ],
                'main.php',
                _AM_GAMERS_RUSUREDEL
            );

        break;
    case 'matchmanager':
        if (isset($_POST['teamid'])) {
            $teamid = (int)$_POST['teamid'];

            $sql
                    =
                'SELECT * FROM ' . $xoopsDB->prefix('gamers_matches') . " WHERE teamid=$teamid ORDER BY matchdate DESC";

            $team = getTeam($teamid);
        } else {
            $sql = 'SELECT * FROM ' . $xoopsDB->prefix('gamers_matches') . ' ORDER BY matchdate DESC';

            $teamid = getDefaultTeam();
        }
        $result = $xoopsDB->query($sql);

        if (!$xoopsDB->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
        }

        $adminObject = Admin::getInstance();
        $adminObject->displayNavigation('main.php?op=matchmanager');
        $adminObject->addItemButton(_AM_GAMERS_ADDMATCH, '../index.php?op=matchform', 'add');
        $adminObject->displayButton('left', '');

        teamTableClose();
        teamTableOpen();

        echo '<th><b>' . _AM_GAMERS_DATE . '</b></th><th><b>' . _AM_GAMERS_OPPONENT . '</b></th><th><b>' . _AM_GAMERS_MATCHTYPE
            . '</b></th><th><b>' . _AM_GAMERS_RESULT . '</b></th><th><b>' . _AM_GAMERS_ACTION . '</b></th>';
        echo "</tr>\n";

        while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
            if (!isset($class) || 'even' == $class) {
                $class = 'odd';
            } else {
                $class = 'even';
            }

            $mid = (int)$myrow['matchid'];

            $mdate = (int)$myrow['matchdate'];

            $mdate = date(_SHORTDATESTRING, $mdate);

            echo "<tr align = 'center' class='" . $class . "'><td>" . $mdate . '</td><td>' . $myrow['opponent']
                . '</td><td>' . $myrow['ladder'] . '</td><td>';

            echo $myrow['matchresult'] . '</td>';

            echo '<td>';
//        echo "<td><form method='post' action='../main.php' ENCTYPE=\"multipart/form-data\" NAME=\"ModifyMatch\">";
//        echo "<input type=hidden name='op' value='matchform'>";
//        echo "<input type=hidden name='mid' value='".$mid."'>";
//        echo "<input type=submit value='"._AM_GAMERS_EDIT."'></form></td>";
//
//        echo "<td><form method='post' action='main.php' ENCTYPE=\"multipart/form-data\" NAME=\"delete\">";
//        echo "<input type=hidden name='matchid' value='".$mid."'>";
//        echo "<input type=hidden name='op' value='deletematch'>
//                       <input type=submit value='"._AM_GAMERS_DELETE."'></form></td>";

            echo "<a href='../index.php?op=matchform&mid=$mid' title=''" . _EDIT . '><img src=' . $pathIcon16
                . '/edit.png' . " alt=' " . _EDIT . " ' title=' " . _EDIT . " '></a>
<a href='main.php?op=deletematch&matchid=$mid' title=''" . _DELETE . '><img src=' . $pathIcon16 . '/delete.png'
                . " alt=' " . _DELETE . " ' title=' " . _DELETE . " '></a>";

            echo '</td>';

            echo "</tr>\n";
        }
        echo '</table></td></tr></table>';
        break;
    case 'rankmanager':

        $adminObject = Admin::getInstance();
        $adminObject->displayNavigation('main.php?op=rankmanager');

        echo "<td colspan='5'>";
        if (isset($_GET['rankid'])) {
            rankform((int)$_GET['rankid']);
        } else {
            rankform();
        }
        echo '</td>';
        teamTableClose();
        teamTableOpen();
        echo '<th><b>' . _AM_GAMERS_RANKID . '</b></th><th><b>' . _AM_GAMERS_RANK . '</b></th><th><b>' . _AM_GAMERS_TACTICSRANK
            . '</b></th><th><b>' . _AM_GAMERS_MATCHRANK . '</b></th><th><b>' . _AM_GAMERS_RANKCOLOR . '</th><th>' . _AM_GAMERS_ACTION
            . '</th>';
        $sql = 'SELECT rankid, rank, matches, tactics, color FROM ' . $xoopsDB->prefix('gamers_rank');
        $result = $xoopsDB->query($sql);

        if (!$xoopsDB->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
        }

        while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
            if (isset($class) && 'even' == $class) {
                $class = 'odd';
            } else {
                $class = 'even';
            }

            $rankid = $myrow['rankid'];

            $rankname = $myrow['rank'];

            $tactics = $myrow['tactics'];

            if (1 == $tactics) {
                $tactics = 'Yes';
            } else {
                $tactics = 'No';
            }

            $matches = $myrow['matches'];

            if (1 == $matches) {
                $matches = 'Yes';
            } else {
                $matches = 'No';
            }

            $color = $myrow['color'];

            echo "</tr><tr align = 'center' class='" . $class . "'><td>" . $rankid . '</td><td>' . $rankname
                . '</td><td>' . $tactics . '</td>';

            echo '<td>' . $matches . '</td>';
//        echo "<td>".$color."</td>";

            echo "<td align='center'><span style=\"background-color:" . $color . '">&nbsp;&nbsp;&nbsp;</span> -> '
                . $color . '</td>';

            echo '<td>';
//        echo "<td><form method='post' action='main.php' ENCTYPE=\"multipart/form-data\" NAME=\"ModifyMatch\">";
//        echo "<input type=hidden name='op' value='rankmanager'>";
//        echo "<input type=hidden name='rankid' value='".$rankid."'>";
//        echo "<input type=submit value='"._AM_GAMERS_EDIT."'></form></td>";
//        echo "<td><form method='post' action='main.php' ENCTYPE=\"multipart/form-data\" NAME=\"delete\">";
//        echo "<input type=hidden name='rankid' value=".$rankid.">";
//        echo "<input type=hidden name='op' value='deleterank'> <input type=submit value='"._AM_GAMERS_DELETE."'></form></td>";

            echo "<a href='main.php?op=rankmanager&rankid=$rankid' title=''" . _EDIT . '><img src=' . $pathIcon16
                . '/edit.png' . " alt=' " . _EDIT . " ' title=' " . _EDIT . " '></a>
<a href='main.php?op=deleterank&rankid=$rankid' title=''" . _DELETE . '><img src=' . $pathIcon16 . '/delete.png'
                . " alt=' " . _DELETE . " ' title=' " . _DELETE . " '></a>";

            echo '</td>';

            echo "</tr>\n";
        }
        teamTableClose();
        break;
    case 'posorderedit':
        foreach ($_POST['posorder'] as $posid => $posorder) {
            $sql
                =
                'UPDATE ' . $xoopsDB->prefix('gamers_positions') . ' SET posorder=' . (int)$posorder . ' WHERE posid='
                . (int)$posid;

            $xoopsDB->query($sql);
        }
        redirect_header('main.php?op=positionmanager', 3, _AM_GAMERS_POSITIONMODIFIED);
        break;
    case 'laddermanager':
        $sql = 'SELECT * FROM ' . $xoopsDB->prefix('gamers_ladders');
        $result = $xoopsDB->query($sql);

        if (!$xoopsDB->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
        }

        $adminObject = Admin::getInstance();
        $adminObject->displayNavigation('main.php?op=laddermanager');

        echo '<td colspan=2>';
        if (isset($_GET['ladderid'])) {
            ladderedit((int)$_GET['ladderid']);
        } else {
            ladderedit('');
        }
        echo '</td>';
        teamTableClose();
        teamTableOpen();
        echo '<th><b>' . _AM_GAMERS_LADDERNAME . '</b></th><th><b>' . _AM_GAMERS_LADDERVISIBLE . '</b></th><th>'
            . _AM_GAMERS_SCORESVISIBLE . '</th><th>' . _AM_GAMERS_ACTION . '</th>';
        while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
            if (isset($class) && 'even' == $class) {
                $class = 'odd';
            } else {
                $class = 'even';
            }

            $ladderid = $myrow['ladderid'];

            $laddername = $myrow['ladder'];

            $laddervisible = 0 == $myrow['visible'] ? _AM_GAMERS_NO : _AM_GAMERS_YES;

            $scoresvisible = 0 == $myrow['scoresvisible'] ? _AM_GAMERS_NO : _AM_GAMERS_YES;

            echo "<tr align = 'center' class='" . $class . "'><td>" . $laddername . '</td><td>';

            echo $laddervisible . '</td><td>';

            echo $scoresvisible . '</td>';

            echo '<td>';
//        echo "<td><a href='main.php?op=laddermanager&ladderid=".$ladderid."'>";
//        echo ""._AM_GAMERS_EDIT."</td>";
//        echo "<td><a href='main.php?op=deleteladder&ladderid=".$ladderid."'>";
//        echo ""._AM_GAMERS_DELETE."</td>";
//        echo "</tr>\n";

            echo "<a href='main.php?op=laddermanager&ladderid=$ladderid' title=''" . _EDIT . '><img src=' . $pathIcon16
                . '/edit.png' . " alt=' " . _EDIT . " ' title=' " . _EDIT . " '></a>
<a href='main.php?op=deleteladder&ladderid=$ladderid' title=''" . _DELETE . '><img src=' . $pathIcon16 . '/delete.png'
                . " alt=' " . _DELETE . " ' title=' " . _DELETE . " '></a>";

            echo '</td>';

            echo "</tr>\n";
        }
        echo '<tr><td colspan=3></td><td colspan=3></td>';
        teamTableClose();
        break;
    case 'positionmanager':
        $sql = 'SELECT * FROM ' . $xoopsDB->prefix('gamers_positions') . ' ORDER BY postype ASC, posorder ASC';
        $result = $xoopsDB->query($sql);

        if (!$xoopsDB->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
        }

        $adminObject = Admin::getInstance();
        $adminObject->displayNavigation('main.php?op=positionmanager');

        echo '<td colspan=4>';
        if (isset($_GET['posid'])) {
            posedit((int)$_GET['posid']);
        } else {
            posedit('');
        }
        echo '</td>';
        teamTableClose();
        teamTableOpen();
        echo '<th><b>' . _AM_GAMERS_POSITIONSHORT . '</b></th><th><b>' . _AM_GAMERS_POSITIONNAME . '</b></th><th><b>'
            . _AM_GAMERS_TYPE2 . '</b></th><th><b>' . _AM_GAMERS_ORDER . '</b></th><th>' . _AM_GAMERS_ACTION . '</th>';
        echo "<form method='post' action='main.php?op=posorderedit'></tr>\n";
        while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
            if (isset($class) && 'even' == $class) {
                $class = 'odd';
            } else {
                $class = 'even';
            }

            $posid = $myrow['posid'];

            $posname = $myrow['posname'];

            $posshort = $myrow['posshort'];

            $postype = $myrow['postype'];

            $posorder = $myrow['posorder'];

            echo "<tr align = 'center' class='" . $class . "'><td>" . $posshort . '</td><td>';

            echo $posname . '</td>';

            echo '<td>' . $postype . '</td>';

            echo "<td><input type=text size='4' name='posorder[" . $posid . "]' value='" . $posorder . "'></td>";

            echo '<td>';
//        echo "<td><a href='main.php?op=positionmanager&posid=".$posid."'>";
//        echo ""._AM_GAMERS_EDIT."</td>";
//        echo "<td><a href='main.php?op=deletepos&posid=".$posid."'>";
//        echo ""._AM_GAMERS_DELETE."</td>";

            echo "<a href='main.php?op=positionmanager&posid=$posid' title=''" . _EDIT . '><img src=' . $pathIcon16
                . '/edit.png' . " alt=' " . _EDIT . " ' title=' " . _EDIT . " '></a>
<a href='main.php?op=deletepos&posid=$posid' title=''" . _DELETE . '><img src=' . $pathIcon16 . '/delete.png'
                . " alt=' " . _DELETE . " ' title=' " . _DELETE . " '></a>";

            echo '</td>';

            echo "</tr>\n";
        }
        echo "<tr><td colspan=3></td><td colspan=3><input type=submit value='Set Order'></form></td>";
        teamTableClose();
        break;
    case 'setdefault':
        $team = $teamHandler->get((int)$_POST['teamid']);
        if ($teamHandler->setDefault($team)) {
            redirect_header(
                'main.php?op=teammanager',
                3,
                $team->getVar('teamname') . ' ' . _AM_GAMERS_SETASDEFAULTTEAM . ''
            );

            break;
        }
            redirect_header('main.php?op=teammanager', 2, _AM_GAMERS_ERRORDEFAULTTEAMNOTCHANGED);

        break;
    case 'mappoolmanager':

        $adminObject = Admin::getInstance();
        $adminObject->displayNavigation('main.php?op=mappoolmanager');

        echo "<td colspan='2'>";
        if (isset($_GET['mapid'])) {
            mapedit((int)$_GET['mapid']);
        } else {
            mapedit('');
        }
        echo '</td>';
        teamTableClose();
        teamTableOpen();
        echo '<th><b>' . _AM_GAMERS_MAPID . '</b></th><th><b>' . _AM_GAMERS_MAPNAME . '</b></th><th><b>' . _AM_GAMERS_ACTION
            . '</b></th>';
        $sql = 'SELECT * FROM ' . $xoopsDB->prefix('gamers_mappool') . ' ORDER BY mapname ASC';

        $result = $xoopsDB->query($sql);

        if (!$xoopsDB->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
        } else {
            while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
                if (isset($class) && 'even' == $class) {
                    $class = 'odd';
                } else {
                    $class = 'even';
                }

                $mapid = $myrow['mapid'];

                $mapname = $myrow['mapname'];

                echo "</tr><tr align = 'center' class='" . $class . "'><td>" . $mapid . '</td><td>';

                echo $mapname . '</td>';

                echo '<td>';
//            echo "<td><form method='post' action='main.php' ENCTYPE=\"multipart/form-data\" NAME=\"ModifyMap\">";
//            echo "<input type=hidden name='mapid' value='".$mapid."'>";
//            echo "<input type=hidden name='op' value='mappoolmanager'>";
//            echo "<input type=submit value='"._AM_GAMERS_EDIT."'></form></td>";
//            echo "<td><form method='post' action='main.php' ENCTYPE=\"multipart/form-data\" NAME=\"delete\">";
//            echo "<input type=hidden name='op' value='deletemap'>";
//            echo "<input type=hidden name='mapid' value='".$mapid."'>";
//            echo "<input type=submit value='"._AM_GAMERS_DELETE."'></form></td>";

                echo "<a href='main.php?op=mappoolmanager&mapid=$mapid' title=''" . _EDIT . '><img src=' . $pathIcon16
                    . '/edit.png' . " alt=' " . _EDIT . " ' title=' " . _EDIT . " '></a>
    <a href='main.php?op=deletemap&mapid=$mapid' title=''" . _DELETE . '><img src=' . $pathIcon16 . '/delete.png'
                    . " alt=' " . _DELETE . " ' title=' " . _DELETE . " '></a>";

                echo '</td>';

                echo "</tr>\n";
            }
        }
        teamTableClose();
        break;
    case 'teammanager':

        $adminObject = Admin::getInstance();
        $adminObject->displayNavigation('main.php?op=teammanager');
        $adminObject->addItemButton(_AM_GAMERS_ADDTEAM, 'addteam.php', 'add');
        $adminObject->displayButton('left', '');

        teamTableClose();
        teamTableOpen();
        echo '<th><b>' . _AM_GAMERS_TEAMID . '</b></th><th><b>' . _AM_GAMERS_NAME . '</b></th><th><b>' . _AM_GAMERS_TYPE2
            . '</b></th><th><b>' . _AM_GAMERS_MAPSMATCH . '</b></th><th><b>' . _AM_GAMERS_DEFAULT . '</b></th><th><b>'
            . _AM_GAMERS_ACTION . '</b></th>';
        $teams = $teamHandler->getObjects(null, false, false);
        foreach ($teams as $myrow) {
            if (isset($class) && 'even' == $class) {
                $class = 'odd';
            } else {
                $class = 'even';
            }

            $teamid = $myrow['teamid'];

            $teamname = $myrow['teamname'];

            $teamtype = $myrow['teamtype'];

            $maps = $myrow['maps'];

            echo "</tr><tr align='center' class='" . $class . "'><td>" . $teamid . '</td><td>';

            echo "<a href='teamadmin.php?teamid=" . $teamid . "'>";

            echo $teamname . '</a></td>';

            echo '<td>' . $teamtype . '</td>';

            echo '<td>' . $maps . '</td>';

            echo '<td>';

            if (1 == $myrow['defteam']) {
                echo 'Default';
            } else {
                echo "<form method='post' action='main.php?op=setdefault' ENCTYPE=\"multipart/form-data\" NAME=\"ModifyTeam\">";

                echo "<input type=hidden name='teamid' value='" . $teamid . "'>";

                echo "<input type=submit value='Set Default'></form>";
            }

            echo '</td>';

            echo '<td>';
//        echo "<form method='post' action='main.php' ENCTYPE=\"multipart/form-data\" NAME=\"delete\">";
//        echo "<input type=hidden name='teamid' value='".$teamid."'>";
//        echo "<input type=hidden name='op' value='deleteteam'>";
//        echo "<input type=submit value='"._AM_GAMERS_DELETE."'></form>

            echo "<a href='teamadmin.php?teamid=$teamid' title=''" . _EDIT . '><img src=' . $pathIcon16 . '/edit.png'
                . " alt=' " . _EDIT . " ' title=' " . _EDIT . " '></a>
<a href='main.php?op=deleteteam&teamid=$teamid' title=''" . _DELETE . '><img src=' . $pathIcon16 . '/delete.png'
                . " alt=' " . _DELETE . " ' title=' " . _DELETE . " '></a>";

            echo '</td>';

            echo "</tr>\n";
        }
        teamTableClose();
        break;
    case 'saveserver':
        if ('Add' === Request::getString('action', '', 'POST')) {
            $sql = 'INSERT INTO ' . $xoopsDB->prefix('gamers_server') . ' (servername, serverip, serverport) VALUES ('
                   . $xoopsDB->quoteString(Request::getString('servername', '', 'POST')) . ', ' . $xoopsDB->quoteString(Request::getString('serverip', '', 'POST')) . ', '
                   . (int)$_POST['serverport'] . ')';

            $comment = Request::getString('servername', '', 'POST') . ' ' . _AM_GAMERS_ADDED . '';
        } elseif ('Edit' == Request::getString('action', '', 'POST')) {
            $sql = 'UPDATE ' . $xoopsDB->prefix('gamers_server') . ' SET serverip = '
                   . $xoopsDB->quoteString(Request::getString('serverip', '', 'POST')) . ', servername='
                   . $xoopsDB->quoteString(Request::getString('servername', '', 'POST')) . ', serverport=' . (int)$_POST['serverport']
                   . '  WHERE serverid=' . (int)$_POST['serverid'];

            $comment = Request::getString('servername', '', 'POST') . ' ' . _AM_GAMERS_EDITED . '';
        }
        if (!$xoopsDB->query($sql)) {
            $comment = _AM_GAMERS_ERRORWHILESAVINGSERVER;
        }
        echo $comment;

        // no break
    case 'servermanager':

        $adminObject = Admin::getInstance();
        $adminObject->displayNavigation('main.php?op=servermanager');

        echo '<td>';
        if (isset($_GET['serverid'])) {
            serverForm('Edit', (int)$_GET['serverid']);
        } else {
            serverForm('Add', '');
        }
        echo '</td>';
        teamTableClose();
        teamTableOpen();
        echo '<th><b>' . _AM_GAMERS_SERVERNAME . '</b></th><th><b>' . _AM_GAMERS_SERVERIP . '</b></th><th><b>'
            . _AM_GAMERS_SERVERPORT . '</b></th><th><b>' . _AM_GAMERS_ACTION . '</b></th>';
        $sql = 'SELECT * FROM ' . $xoopsDB->prefix('gamers_server') . ' ORDER BY servername ASC';


        $result = $xoopsDB->query($sql);

        if (!$xoopsDB->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
        } else {
            while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
                if (isset($class) && 'even' == $class) {
                    $class = 'odd';
                } else {
                    $class = 'even';
                }

                $serverid = $myrow['serverid'];

                $servername = $myrow['servername'];

                $serverip = $myrow['serverip'];

                $serverport = $myrow['serverport'];

                echo "</tr><tr align = 'center' class='" . $class . "'><td>" . $servername . '</td><td>';

                echo $serverip . '</td>';

                echo '<td>' . $serverport . '</td>';

                echo '<td>';
//            echo "<td><form method='post' action='main.php'>";
//            echo "<input type=hidden name='op' value='servermanager'>";
//            echo "<input type=hidden name='serverid' value='".$serverid."'>";
//            echo "<input type=submit value='"._AM_GAMERS_EDIT."'></form></td>";
//            echo "<td><form method='post' action='main.php'>";
//            echo "<input type=hidden name='serverid' value='".$serverid."'>";
//            echo "<input type=hidden name='op' value='deleteserver'>";
//            echo "<input type=submit value='"._AM_GAMERS_DELETE."'></form></td>";

                echo "<a href='main.php?op=servermanager&serverid=$serverid' title=''" . _EDIT . '><img src='
                    . $pathIcon16 . '/edit.png' . " alt=' " . _EDIT . " ' title=' " . _EDIT . " '></a>
    <a href='main.php?op=deleteserver&serverid=$serverid' title=''" . _DELETE . '><img src=' . $pathIcon16
                    . '/delete.png' . " alt=' " . _DELETE . " ' title=' " . _DELETE . " '></a>";

                echo '</td>';

                echo "</tr>\n";
            }
        }
        teamTableClose();
        break;
    case 'deletesize':
        if (!empty($_POST['ok'])) {
            if (empty($_POST['size_id'])) {
                redirect_header('main.php?op=default', 2, _AM_GAMERS_EMPTYNODELETE);

                break;
            }

            $sql = 'DELETE FROM ' . $xoopsDB->prefix('gamers_sizes') . ' WHERE sizeid=' . (int)$_POST['size_id'];

            if ($xoopsDB->query($sql)) {
                redirect_header('main.php?op=sizemanager', 3, _AM_GAMERS_SIZEDELETED);

                break;
            }

            redirect_header('main.php?op=sizemanager', 3, _AM_GAMERS_ERRSIZENOTDEL);

            break;
        }
            echo '<h4>' . _AM_GAMERS_TEAM_CONFIG . '</h4>';
            xoops_confirm(
                [
                    'op' => 'deletesize',
                    'size_id' => (int)$_GET['size_id'],
                    'ok' => 1,
                ],
                'main.php?op=sizemanager',
                _AM_GAMERS_RUSUREDELSIZE
            );

        break;
    case 'savesize':

        if ('Add' == $_POST['action']) {
            $sql = 'INSERT INTO ' . $xoopsDB->prefix('gamers_sizes') . ' (size) VALUES ('
                . $xoopsDB->quoteString($_POST['size']) . ')';

            $comment = (int)$_POST['size'] . ' ' . _AM_GAMERS_SIZESADDED . '';
        } elseif ('Edit' == $_POST['action']) {
            $sql
                     =
                'UPDATE ' . $xoopsDB->prefix('gamers_sizes') . ' SET size = ' . $xoopsDB->quoteString($_POST['size'])
                    . '  WHERE sizeid=' . (int)$_POST['sizeid'];

            $comment = sprintf(_AM_GAMERS_SIZE_EDITED_OK, (int)$_POST['sizeid']);
        }
        if (!$xoopsDB->query($sql)) {
            $comment = _AM_GAMERS_ERRORWHILESAVINGSIZE;
        }
        redirect_header('main.php?op=sizemanager', 3, $comment);
        break;
    case 'sizemanager':

        $adminObject = Admin::getInstance();
        $adminObject->displayNavigation('main.php?op=sizemanager');

        echo '<td>';
        if (isset($_GET['size_id'])) {
            addSizeForm('Edit', (int)$_GET['size_id']);
        } else {
            addSizeForm('Add', '');
        }
        echo '</td>';
        teamTableClose();
        teamTableOpen();
        echo '<th><b>' . _AM_GAMERS_SIZEID . '</b></th><th><b>' . _AM_GAMERS_SIZES . '</b></th><th><b>' . _AM_GAMERS_ACTION
            . '</b></th>';
        $sql = 'SELECT * FROM ' . $xoopsDB->prefix('gamers_sizes') . ' ORDER BY size ASC';

        $result = $xoopsDB->query($sql);

        if (!$xoopsDB->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
        } else {
            while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
                if (isset($class) && 'even' == $class) {
                    $class = 'odd';
                } else {
                    $class = 'even';
                }

                $size_id = $myrow['sizeid'];

                $size = $myrow['size'];

                echo "</tr><tr align = 'center' class='" . $class . "'><td>" . $size_id . '</td><td>';

                echo $size . '</td>';

                echo '<td>';
//            echo "<td><form method='post' action='main.php'>";
//            echo "<input type=hidden name='size_id' value='".$size_id."'>";
//            echo "<input type=hidden name='op' value='deletesize'>";
//            echo "<input type=submit value='"._AM_GAMERS_DELETE."'></form></td>";

                echo "<a href='main.php?op=sizemanager&size_id=$size_id' title=''" . _EDIT . '><img src=' . $pathIcon16
                    . '/edit.png' . " alt=' " . _EDIT . " ' title=' " . _EDIT . " '></a>
    <a href='main.php?op=deletesize&size_id=$size_id' title=''" . _DELETE . '><img src=' . $pathIcon16 . '/delete.png'
                    . " alt=' " . _DELETE . " ' title=' " . _DELETE . " '></a>";

                echo '</td>';

                echo "</tr>\n";
            }
        }
        teamTableClose();
        break;
    case 'deleteside':
        if (!empty($_POST['ok'])) {
            if (empty($_POST['side_id'])) {
                redirect_header('main.php?op=default', 2, _AM_GAMERS_EMPTYNODELETE);

                exit();
            }

            $sql = 'DELETE FROM ' . $xoopsDB->prefix('gamers_sides') . ' WHERE sideid=' . (int)$_POST['side_id'];

            if ($xoopsDB->query($sql)) {
                redirect_header('main.php?op=sidemanager', 3, _AM_GAMERS_SIDEDELETED);
            } else {
                redirect_header('main.php?op=sidemanager', 3, _AM_GAMERS_ERRSIDENOTDEL);
            }
        } else {
            echo '<h4>' . _AM_GAMERS_TEAM_CONFIG . '</h4>';

            xoops_confirm(
                [
                    'op' => 'deleteside',
                    'side_id' => (int)$_GET['side_id'],
                    'ok' => 1,
                ],
                'main.php?op=sidemanager',
                _AM_GAMERS_RUSUREDELSIDE
            );
        }
        break;
    case 'saveside':

        if ('Add' == $_POST['action']) {
            $sql = 'INSERT INTO ' . $xoopsDB->prefix('gamers_sides') . ' (side, sideshort) VALUES ('
                . $xoopsDB->quoteString($_POST['side']) . ', ' . $xoopsDB->quoteString($_POST['sideshort']) . ')';

            $temp = Request::getString('side', '', 'POST');
            $comment = $temp . ' ' . _AM_GAMERS_SIDESADDED . '';
        } elseif ('Edit' == Request::getInt('action', '', 'POST')) {
            $sql
                     =
                'UPDATE ' . $xoopsDB->prefix('gamers_sides') . ' SET side = ' . $xoopsDB->quoteString($_POST['side'])
                    . ', sideshort = ' . $xoopsDB->quoteString($_POST['sideshort']) . '  WHERE sideid='
                . (int)$_POST['sizeid'];

            $comment = sprintf(_AM_GAMERS_SIDE_EDITED_OK, $_POST['side']);
        }
        if (!$xoopsDB->query($sql)) {
            $comment = _AM_GAMERS_ERRORWHILESAVINGSIZE;
        }
        redirect_header('main.php?op=sidemanager', 3, $comment);
        break;
    case 'sidemanager':
        $adminObject = Admin::getInstance();
        $adminObject->displayNavigation('main.php?op=sidemanager');

        echo '<td>';
        if (isset($_GET['side_id'])) {
            addSideForm('Edit', (int)$_GET['side_id']);
        } else {
            addSideForm('Add', '');
        }
        echo '</td>';
        teamTableClose();
        teamTableOpen();
        echo '<th><b>' . _AM_GAMERS_SIDEID . '</b></th><th><b>' . _AM_GAMERS_SIDES . '</b></th><th><b>' . _AM_GAMERS_SIDESHORT
            . '</th><th><b>' . _AM_GAMERS_ACTION . '</b></th>';
        $sql = 'SELECT * FROM ' . $xoopsDB->prefix('gamers_sides') . ' ORDER BY side ASC';

        $result = $xoopsDB->query($sql);

        if (!$xoopsDB->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
        } else {
            while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
                if (isset($class) && 'even' == $class) {
                    $class = 'odd';
                } else {
                    $class = 'even';
                }

                $side_id = $myrow['sideid'];

                $side = $myrow['side'];

                $sideshort = $myrow['sideshort'];

                echo "</tr><tr align = 'center' class='" . $class . "'><td>" . $side_id . '</td><td>';

                echo $side . '</td>';

                echo '<td>' . $sideshort . '</td>';

                echo '<td>';
//            echo "<td><form method='post' action='main.php'>";
//            echo "<input type=hidden name='side_id' value='".$side_id."'>";
//            echo "<input type=hidden name='op' value='deleteside'>";
//            echo "<input type=submit value='"._AM_GAMERS_DELETE."'></form></td>";

                echo "<a href='main.php?op=sidemanager&side_id=$side_id' title=''" . _EDIT . '><img src=' . $pathIcon16
                    . '/edit.png' . " alt=' " . _EDIT . " ' title=' " . _EDIT . " '></a>
    <a href='main.php?op=deleteside&side_id=$side_id' title=''" . _DELETE . '><img src=' . $pathIcon16 . '/delete.png'
                    . " alt=' " . _DELETE . " ' title=' " . _DELETE . " '></a>";

                echo '</td>';

                echo "</tr>\n";
            }
        }
        teamTableClose();
        break;
    case 'layoutmanager':
        $sql = 'SELECT * FROM ' . $xoopsDB->prefix('gamers_layout');

        $result = $xoopsDB->query($sql);

        if (!$xoopsDB->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
        }
        $myrow = $xoopsDB->fetchArray($result);

        $adminObject = Admin::getInstance();
        $adminObject->displayNavigation('main.php?op=layoutmanager');

        echo "<td colspan='5'>";
        layoutform($myrow);
        echo '</td>';
        teamTableClose();
        break;
    case 'default':
    default:
        echo '<h4>' . _AM_GAMERS_TEAM_CONFIG . '</h4>';
        echo "<table width='100%' border='0' cellspacing='1' class='outer'><tr><td class=\"odd\">";
        echo " - <b><a href='main.php?op=teammanager'>" . _AM_GAMERS_TEAM_MNGR . '</a></b><br><br>';
        echo " - <b><a href='main.php?op=matchmanager'>" . _AM_GAMERS_MATCH_MNGR . '</a></b><br><br>';
        echo " - <b><a href='main.php?op=layoutmanager'>" . _AM_GAMERS_LAYOUTMNGR . '</a></b><br><br>';
        echo " - <b><a href='main.php?op=rankmanager'>" . _AM_GAMERS_RANKMNGR . '</a></b><br><br>';
        echo " - <b><a href='main.php?op=mappoolmanager'>" . _AM_GAMERS_MAPMNGR . '</a></b><br><br>';
        echo " - <b><a href='main.php?op=positionmanager'>" . _AM_GAMERS_POSMNGR . '</a></b><br><br>';
        echo " - <b><a href='main.php?op=sizemanager'>" . _AM_GAMERS_SIZEMNGR . '</a></b><br><br>';
        echo " - <b><a href='main.php?op=sidemanager'>" . _AM_GAMERS_SIDEMNGR . '</a></b><br><br>';
        echo " - <b><a href='main.php?op=servermanager'>" . _AM_GAMERS_SERVERMNGR . '</a></b><br><br>';
        echo " - <b><a href='main.php?op=laddermanager'>" . _AM_GAMERS_LADDERMNGR . '</a></b><br><br>';
        echo '</td></tr></table>';
        break;
}

require_once __DIR__ . '/admin_footer.php';
