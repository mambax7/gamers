<?php declare(strict_types=1);

use XoopsModules\Gamers\{
    Helper,
    Player
};

/** @var Helper $helper */

require_once __DIR__ . '/header.php';

$teamid = isset($_GET['teamid']) ? (int)$_GET['teamid'] : null;
$uid    = isset($_GET['uid']) ? (int)$_GET['uid'] : null;
if (isset($_POST)) {
    foreach ($_POST as $k => $v) {
        ${$k} = $v;
    }
}
$teamHandler = Helper::getInstance()
                     ->getHandler('Team');
if ($xoopsUser) {
    if (isset($submit)) {
        $team = $teamHandler->get($teamid);

        if ($team->isTeamMember($xoopsUser->getVar('uid'))) {
            $tertiary = (int)$tertiary;

            if ($tertiary > 0) {
                $terclause = ", tertiarypos='$tertiary'";
            } else {
                $terclause = ", tertiarypos=''";
            }

            $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('gamers_teamstatus') . ' SET primarypos=' . (int)$primary . ', secondarypos=' . (int)$secondary . ' ' . $terclause . ' WHERE uid=' . (int)$uid . " AND teamid='$teamid'");

            $xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix('gamers_skills') . " WHERE uid='$uid'");

            $teamskills = $team->getSkills();

            foreach ($teamskills as $skillid => $skillname) {
                if (isset($checked[$skillid])) {
                    $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('gamers_skills') . " (uid, posid, teamid) VALUES ('$uid', '$skillid', '$teamid')");
                }
            }

            redirect_header('positions.php?teamid=' . $teamid, 3, _MD_GAMERS_PSSKILLSUPDATE);
        } else {
            redirect_header('positions.php?teamid=' . $teamid, 3, _MD_GAMERS_ACCESSDENY);
        }
    } else {
        if (!isset($teamid)) {
            $teamid = getDefaultTeam();
        }

        $team = $teamHandler->get($teamid);

        echo "<table border='0' cellpadding='0' cellspacing='0' valign='top' width='100%'>";

        echo "<tr><td><table width='100%' border='0' cellpadding='0' cellspacing='0'>";

        echo "<tr class='head'><td align='right'>";

        if ($xoopsUser->isAdmin($xoopsModule->mid()) || ($team->isTeamAdmin($xoopsUser->getVar('uid')))) {
            echo "<a href='memberadmin.php?teamid=" . $teamid . "'>" . _MD_GAMERS_ADMIN . '</a> | ';
        }

        echo "<a href='roster.php?teamid=" . $teamid . "'>" . _MD_GAMERS_ROSTER . '</a> | ';

        echo "<a href='positions.php?teamid=" . $teamid . "'>" . _MD_GAMERS_POSOVERVIEW . "</a> | <a href='avstats.php?teamid=" . $teamid . "'>" . _MD_GAMERS_AVAILSTATS2 . '</a>';

        echo '</td></tr></table>';

        echo "<tr><td><table width='100%' border='0' cellpadding='4' cellspacing='1'>";

        if (isset($uid)) {
            $thisuid = $xoopsUser->getVar('uid');

            if (!$team->isTeamAdmin($thisuid)) {
                $uid = $thisuid;
            }
        } else {
            $uid = $xoopsUser->getVar('uid');
        }

        $thisUser = new \XoopsUser($uid);

        $user = $thisUser->getVar('uname');

        $sql = 'SELECT rank, primarypos, secondarypos, tertiarypos FROM ' . $xoopsDB->prefix('gamers_teamstatus') . ' WHERE uid=' . $uid . ' AND teamid=' . $teamid;

        $result = $xoopsDB->query($sql);
        if (!$xoopsDB->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
        }

        $myteamstatus = $xoopsDB->fetchArray($result);

        $rankid = $myteamstatus['rank'];

        $primary = $myteamstatus['primarypos'];

        $secondary = $myteamstatus['secondarypos'];

        $tertiary = $myteamstatus['tertiarypos'];

        $rank = getRank($rankid);

        echo "<tr class='head'><td><b>" . $team->getVar('teamname') . '</b></td><td></td></tr>';

        echo "<tr class='even'><td>" . _MD_GAMERS_PLAYERNAME . "</td><td><a href='profile.php?uid=" . $uid . "'>" . $user . '</a></td>';

        echo "<tr class='odd'><td>" . _MD_GAMERS_PLAYERRANK . "</td><td><span color='" . $rank['color'] . "'>" . $rank['rank'] . '</span></td>';

        echo "<tr class='head'><td colspan=2><b>" . _MD_GAMERS_POSITIONS . '</b></td>';

        echo "<form method='post' action='mypositions.php' ENCTYPE=\"multipart/form-data\" NAME=\"Positions\">";

        echo "<input type='hidden' name='uid' value='" . $uid . "'>";

        echo "<tr class='odd'><td>" . _MD_GAMERS_PRIMARYPOSITION . '</td><td>';

        echo "<SELECT name='primary'>";

        $teampos = $team->getPositions();

        foreach ($teampos as $posid => $posname) {
            echo '<OPTION value=' . $posid . ' ' . selectcheck($primary, $posid) . '>' . $posname . '</OPTION>';
        }

        echo '</SELECT>';

        echo '</td></tr>';

        echo "<tr class='even'><td>" . _MD_GAMERS_SECONDARYPOSITION . '</td><td>';

        echo "<SELECT name='secondary'>";

        foreach ($teampos as $posid => $posname) {
            echo '<OPTION value=' . $posid . ' ' . selectcheck($secondary, $posid) . '>' . $posname . '</OPTION>';
        }

        echo '</SELECT>';

        echo '</td></tr>';

        echo "<tr class='odd'><td>" . _MD_GAMERS_TERTIARYPOSITION . '</td><td>';

        echo "<SELECT name='tertiary'><OPTION value=null " . selectcheck($tertiary, null) . '>' . _MD_GAMERS_NONE . '</OPTION>';

        foreach ($teampos as $posid => $posname) {
            echo '<OPTION value=' . $posid . ' ' . selectcheck($tertiary, $posid) . '>' . $posname . '</OPTION>';
        }

        echo '</SELECT>';

        echo '</td></tr>';

        echo "<tr class='head'><td colspan=2><b>" . _MD_GAMERS_SKILLS . ':</b></td></tr>';

        $teamskills = $team->getSkills();

        foreach ($teamskills as $skillid => $skillname) {
            if (isset($class) && ('even' === $class)) {
                $class = 'odd';
            } else {
                $class = 'even';
            }

            echo '<tr class=' . $class . '><td>' . $skillname . '</td>';

            echo "<td><input type='checkbox' name='checked[" . $skillid . "]' " . skillcheck($teamid, $uid, $skillid) . '></td></tr>';
        }

        echo '</td></tr>';

        echo "<tr class='head'><td colspan=2><input type=hidden name='teamid' value=" . $teamid . '>';

        echo "<input type=hidden name='submit' value=1>";

        echo "<input type=submit value='Update'></form></td></tr>";

        echo '</table></td></tr></table>';
    }
} else {
    redirect_header('../../index.php', 3, _MD_GAMERS_SORRYRESTRICTEDAREA);
}
require_once XOOPS_ROOT_PATH . '/footer.php';
