<?php declare(strict_types=1);

namespace XoopsModules\Gamers;

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

// Class for Team management for Gamers Module

if (!defined('XOOPS_ROOT_PATH')) {
    die('Xoops root path not defined');
}
//if (!class_exists('XoopsPersistableObjectHandler')) {
//    require_once XOOPS_ROOT_PATH . '/modules/gamers/class/object.php';
//}

/**
 * Class Team
 */
class Team extends \XoopsObject
{
    public $db;

    //Constructor

    /**
     * Team constructor.
     * @param int $teamid
     */
    public function __construct(int $teamid = -1)
    {
        $this->initVar('teamid', XOBJ_DTYPE_INT, null, false);

        $this->initVar('teamname', XOBJ_DTYPE_TXTBOX);

        $this->initVar('teamtype', XOBJ_DTYPE_TXTBOX);

        $this->initVar('maps', XOBJ_DTYPE_INT);

        $this->initVar('defteam', XOBJ_DTYPE_INT);

        $this->db = \XoopsDatabaseFactory::getDatabaseConnection();
    }

    /**
     * @param string|null $clause
     * @return array
     */
    final public function getMatches(string $clause = null): array
    {
        $sql = 'SELECT * FROM ' . $this->db->prefix('gamers_matches') . ' WHERE teamid= ' . $this->getVar('teamid') . ' ORDER BY matchdate DESC ' . $clause . ' ';

        $result = $this->db->query($sql);
        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), E_USER_ERROR);
        }

        $matches = [];

        $matchHandler = Helper::getInstance()->getHandler('Match');

        while (false !== ($row = $this->db->fetchArray($result))) {
            $thismatch = $matchHandler->create(false);

            $thismatch->assignVars($row);

            $matches[$row['matchid']] = $thismatch;

            unset($thismatch);
        }

        return $matches;
    }

    /**
     * @return array
     */
    final public function getMaps(): array
    {
        $sql = 'SELECT m.mapname, m.mapid FROM ' . $this->db->prefix('gamers_mappool') . ' m, ' . $this->db->prefix('gamers_teammaps') . ' tm WHERE m.mapid=tm.mapid AND tm.teamid=' . $this->getVar('teamid') . ' ORDER BY m.mapname ASC';

        $result = $this->db->query($sql);
        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), E_USER_ERROR);
        }

        $maps = [];

        while (false !== ($row = $this->db->fetchArray($result))) {
            $maps[$row['mapid']] = $row['mapname'];
        }

        return $maps;
    }

    /**
     * @return array
     */
    final public function getTeamMembers(): array
    {
        $sql = 'SELECT u.uname, u.uid FROM ' . $this->db->prefix('gamers_teamstatus') . ' ts, ' . $this->db->prefix('users') . ' u WHERE u.uid=ts.uid AND ts.teamid=' . $this->getVar('teamid') . ' ORDER BY u.uname ASC';

        $result = $this->db->query($sql);
        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), E_USER_ERROR);
        }

        $members = [];

        while (false !== ($row = $this->db->fetchArray($result))) {
            $members[$row['uid']] = $row['uname'];
        }

        return $members;
    }

    /**
     * @return array
     */
    final public function getPositions(): array
    {
        $sql = 'SELECT p.posname, p.posid FROM ' . $this->db->prefix('gamers_positions') . ' p, ' . $this->db->prefix('gamers_teampositions') . ' tp WHERE p.posid=tp.posid AND tp.teamid=' . $this->getVar('teamid') . " AND p.postype='Pos' ORDER BY p.posorder ASC";

        $result = $this->db->query($sql);
        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), E_USER_ERROR);
        }

        $pos = [];

        while (false !== ($row = $this->db->fetchArray($result))) {
            $pos[$row['posid']] = $row['posname'];
        }

        return $pos;
    }

    /**
     * @return array
     */
    final public function getSkills(): array
    {
        $sql = 'SELECT p.posname, p.posid FROM ' . $this->db->prefix('gamers_positions') . ' p, ' . $this->db->prefix('gamers_teampositions') . ' tp WHERE p.posid=tp.posid AND tp.teamid=' . $this->getVar('teamid') . " AND p.postype='Skill' ORDER BY p.posorder ASC";

        $result = $this->db->query($sql);
        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), E_USER_ERROR);
        }

        $pos = [];

        while (false !== ($row = $this->db->fetchArray($result))) {
            $pos[$row['posid']] = $row['posname'];
        }

        return $pos;
    }

    /**
     * @return array
     */
    final public function getPlayerPositions(): array
    {
        $sql = 'SELECT u.uid, u.uname, ts.primarypos, ts.secondarypos, ts.tertiarypos, p.posid FROM ' . $this->db->prefix('users') . ' u, ' . $this->db->prefix('gamers_teamstatus') . ' ts, ' . $this->db->prefix('gamers_positions') . ' p WHERE u.uid=ts.uid AND ts.teamid=' . $this->getVar('teamid') . ' AND ts.primarypos=p.posid ORDER BY ts.status, p.posorder ASC';

        $result = $this->db->query($sql);
        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), E_USER_ERROR);
        }

        $players = [];

        while (false !== ($row = $this->db->fetchArray($result))) {
            $players[$row['uid']]['name'] = $row['uname'];

            $players[$row['uid']]['primary'] = $row['primarypos'];

            $players[$row['uid']]['secondary'] = $row['secondarypos'];

            $players[$row['uid']]['tertiary'] = $row['tertiarypos'];

            $sql = 'SELECT s.posid, p.posshort FROM ' . $this->db->prefix('gamers_skills') . ' s, ' . $this->db->prefix('gamers_positions') . ' p WHERE s.uid=' . $row['uid'] . ' AND s.teamid=' . $this->getVar('teamid') . ' AND s.posid=p.posid ORDER BY p.posorder ASC';

            $result = $this->db->query($sql);
            if (!$this->db->isResultSet($result)) {
                \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), E_USER_ERROR);
            }

            $skills = [];

            while (false !== ($skill = $this->db->fetchArray($result))) {
                $skills[$skill['posid']] = $skill['posshort'];
            }

            $players[$row['uid']]['skills'] = $skills;
        }

        return $players;
    }

    /**
     * @return array
     */
    final public function getShortList(): array
    {
        $sql = 'SELECT p.posid, p.posshort FROM ' . $this->db->prefix('gamers_positions') . ' p, ' . $this->db->prefix('gamers_teampositions') . ' tp WHERE tp.posid=p.posid AND tp.teamid=' . $this->getVar('teamid');

        $result = $this->db->query($sql);
        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), E_USER_ERROR);
        }

        $posshort = [];

        while (false !== ($row = $this->db->fetchArray($result))) {
            $posshort[$row['posid']] = $row['posshort'];
        }

        return $posshort;
    }

    /**
     * @return array
     */
    final public function getTeamSizes(): array
    {
        $sql = 'SELECT s.sizeid, s.size FROM ' . $this->db->prefix('gamers_teamsizes') . ' ts, ' . $this->db->prefix('gamers_sizes') . ' s WHERE ts.sizeid=s.sizeid AND ts.teamid=' . $this->getVar('teamid') . ' ORDER BY s.size';

        $result = $this->db->query($sql);
        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), E_USER_ERROR);
        }

        $teamsizes = [];

        while (false !== ($row = $this->db->fetchArray($result))) {
            $teamsizes[$row['sizeid']] = $row['size'];
        }

        return $teamsizes;
    }

    /**
     * @return array
     */
    final public function getSides(): array
    {
        $sql = 'SELECT s.sideid, s.side FROM ' . $this->db->prefix('gamers_teamsides') . ' ts, ' . $this->db->prefix('gamers_sides') . ' s WHERE ts.sideid=s.sideid AND ts.teamid=' . $this->getVar('teamid') . ' ORDER BY s.side';

        $result = $this->db->query($sql);
        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), E_USER_ERROR);
        }

        $teamsides = [];

        while (false !== ($row = $this->db->fetchArray($result))) {
            $teamsides[$row['sideid']] = $row['side'];
        }

        return $teamsides;
    }

    /**
     * @return array
     */
    final public function getLadders(): array
    {
        $sql = 'SELECT l.ladderid, l.ladder FROM ' . $this->db->prefix('gamers_teamladders') . ' tl, ' . $this->db->prefix('gamers_ladders') . ' l WHERE tl.ladderid=l.ladderid AND tl.teamid=' . $this->getVar('teamid') . ' ORDER BY l.ladder';

        $result = $this->db->query($sql);
        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), E_USER_ERROR);
        }

        $teamladders = [];

        while (false !== ($row = $this->db->fetchArray($result))) {
            $teamladders[$row['ladderid']] = $row['ladder'];
        }

        return $teamladders;
    }

    /**
     * @return array
     */
    final public function getRanks(): array
    {
        $sql = 'SELECT r.rankid, r.rank, r.color FROM ' . $this->db->prefix('gamers_teamrank') . ' tr, ' . $this->db->prefix('gamers_rank') . ' r WHERE tr.rankid=r.rankid AND tr.teamid=' . $this->getVar('teamid') . ' ORDER BY r.rank';

        $result = $this->db->query($sql);
        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), E_USER_ERROR);
        }

        $teamranks = [];

        while (false !== ($row = $this->db->fetchArray($result))) {
            $teamranks[$row['rankid']]['rank'] = $row['rank'];

            $teamranks[$row['rankid']]['color'] = $row['color'];
        }

        return $teamranks;
    }

    /**
     * @return array
     */
    final public function getServers(): array
    {
        $sql = 'SELECT s.servername, s.serverid FROM ' . $this->db->prefix('gamers_server') . ' s, ' . $this->db->prefix('gamers_teamservers') . " ts WHERE s.serverid=ts.serverid AND ts.teamid='" . $this->getVar('teamid') . "' ORDER BY s.servername ASC";

        $result = $this->db->query($sql);
        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), E_USER_ERROR);
        }

        $servers = [];

        while (false !== ($row = $this->db->fetchArray($result))) {
            $servers[$row['serverid']] = $row['servername'];
        }

        return $servers;
    }

    /**
     * @return array
     */
    final public function getActiveMembers(): array
    {
        $sql = 'SELECT u.uname, u.uid, ts.teamid FROM ' . $this->db->prefix('gamers_teamstatus') . ' ts, ' . $this->db->prefix('users') . ' u WHERE u.uid=ts.uid AND ts.teamid=' . $this->getVar('teamid') . " AND ts.status='1' ORDER BY u.uname ASC";

        $result = $this->db->query($sql);
        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), E_USER_ERROR);
        }

        $members = [];

        while (false !== ($row = $this->db->fetchArray($result))) {
            $members[$row['uid']] = $row['uname'];
        }

        return $members;
    }

    /**
     * @return mixed
     */
    final public function getAllMembers()
    {
        $members = [];
        $sql = 'SELECT t.uid, t.teamid, t.status, t.rank, t.primarypos, t.secondarypos, t.tertiarypos FROM ' . $this->db->prefix('gamers_teamstatus') . ' t WHERE t.teamid=' . $this->getVar('teamid') . ' AND t.status>0 ORDER BY t.status ASC';

        $result = $this->db->query($sql);
        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), E_USER_ERROR);
        }
        $uids = [];

        $i = 0;

        while (false !== ($row = $this->db->fetchArray($result))) {
            $members[$i]['uid'] = $row['uid'];

            $members[$i]['status'] = $row['status'];

            $members[$i]['rank'] = $row['rank'];

            $members[$i]['primarypos'] = $row['primarypos'];

            $members[$i]['secondarypos'] = $row['secondarypos'];

            $members[$i]['tertiarypos'] = $row['tertiarypos'];

            $uids[$row['uid']] = $i;

            $i++;
        }

        /** @var \XoopsMemberHandler $memberHandler */
        $memberHandler = xoops_getHandler('member');

        $users = $memberHandler->getUsers(new \Criteria('uid', '(' . implode(',', array_keys($uids)) . ')', 'IN'));

        if ((is_countable($users) ? count($users) : 0) > 0) {
            foreach (array_keys($users) as $i) {
                $key = $uids[$users[$i]->getVar('uid')];

                $members[$key]['uname'] = $users[$i]->getVar('uname');

                $members[$key]['user_avatar'] = $users[$i]->getVar('user_avatar');

                $members[$key]['user_from'] = $users[$i]->getVar('user_from');

                $members[$key]['user_regdate'] = $users[$i]->getVar('user_regdate');

                $members[$key]['user_icq'] = $users[$i]->getVar('user_icq');

                $members[$key]['bio'] = $users[$i]->getVar('bio');
            }
        }

        return $members;
    }

    /**
     * @param int $uid
     * @return bool
     */
    final public function isTeamAdmin(int $uid): bool
    {
        global $xoopsModule;
        $ret = false;

        $thisUser = new \XoopsUser($uid);

        if ($thisUser->isAdmin($xoopsModule->mid())) {
            return true;
        }

        $sql = 'SELECT r.matches, r.tactics FROM ' . $this->db->prefix('gamers_teamstatus') . ' ts, ' . $this->db->prefix('gamers_rank') . ' r WHERE ts.teamid=' . $this->getVar('teamid') . " AND ts.uid=$uid AND ts.rank=r.rankid";

        $result = $this->db->query($sql);
        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), E_USER_ERROR);
        }

        $teamadmin = $this->db->fetchArray($result);

        if ((1 === $teamadmin['matches']) && (1 === $teamadmin['tactics'])) {
            $ret =  true;
        }

        return $ret;
    }

    /**
     * @param int $uid
     * @return bool
     */
    final public function isMatchAdmin(int $uid): bool
    {
        global $xoopsModule;

        $thisUser = new \XoopsUser($uid);

        if ($thisUser->isAdmin($xoopsModule->mid())) {
            return true;
        }

        $sql = 'SELECT r.matches FROM ' . $this->db->prefix('gamers_teamstatus') . ' ts, ' . $this->db->prefix('gamers_rank') . ' r WHERE ts.teamid=' . $this->getVar('teamid') . " AND ts.uid=$uid AND ts.rank=r.rankid";

        $result = $this->db->query($sql);
        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), E_USER_ERROR);
        }

        $teamadmin = $this->db->fetchArray($result);

        if (1 === $teamadmin['matches']) {
            return true;
        }

        return false;
    }

    /**
     * @param int $uid
     * @return bool
     */
    final public function isTacticsAdmin(int $uid): bool
    {
        $sql = 'SELECT r.tactics FROM ' . $this->db->prefix('gamers_teamstatus') . ' ts, ' . $this->db->prefix('gamers_rank') . ' r WHERE ts.teamid=' . $this->getVar('teamid') . " AND ts.uid=$uid AND ts.rank=r.rankid";

        $result = $this->db->query($sql);
        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), E_USER_ERROR);
        }

        $teamadmin = $this->db->fetchArray($result);

        if (1 === $teamadmin['tactics']) {
            return true;
        }

        return false;
    }

    /**
     * @param int $uid
     * @return bool
     */
    final public function isTeamMember(int $uid): bool
    {
        $uid = (int)$uid;

        $sql = 'SELECT rank FROM ' . $this->db->prefix('gamers_teamstatus') . ' WHERE teamid=' . $this->getVar('teamid') . " AND uid=$uid";

        $result = $this->db->query($sql);

        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), E_USER_ERROR);
        }

        $teammember = $this->db->fetchArray($result);

        if ((is_countable($teammember) ? count($teammember) : 0) >= 1) {
            if (null !== $teammember['rank']) {
                return true;
            }

            return false;
        }

        return false;
    }

    /**
     * @return void
     */
    final public function select()
    {
        global $xoopsTpl;

        $allteams = getTeams();

        if ((is_countable($allteams) ? count($allteams) : 0) > 1) {
            $xoopsTpl->assign('lang_selecttitle', _MD_GAMERS_TEAMSELECTION);

            $xoopsTpl->assign('lang_selectcaption', _MD_GAMERS_TEAM);

            $xoopsTpl->assign('lang_submit', _MD_GAMERS_SELECT);

            $xoopsTpl->assign('lang_teamid', 'teamid');

            $xoopsTpl->assign('showselect', 1);

            $xoopsTpl->assign('teams', $allteams);

            $xoopsTpl->assign('selected', $this->getVar('teamid'));
        }
    }

    /**
     * @param int $memberid
     * @return bool
     */
    final public function addTeamMember(int $memberid): bool
    {
        $memberid = (int)$memberid;

        $sql = 'INSERT INTO ' . $this->db->prefix('gamers_teamstatus') . " (uid, teamid, status, rank) VALUES ($memberid, " . $this->getVar('teamid') . ", '1', '3')";

        if (!$this->db->query($sql)) {
            return false;
        }

        $sql = 'SELECT matchid FROM ' . $this->db->prefix('gamers_matches') . ' WHERE teamid=' . $this->getVar('teamid') . " AND matchresult='Pending' ORDER BY matchdate DESC";

        $result = $this->db->query($sql);
        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), E_USER_ERROR);
        }

        while (false !== ($match = $this->db->fetchArray($result))) {
            $this->db->query('INSERT INTO ' . $this->db->prefix('gamers_availability') . " (userid, availability, matchid) VALUES ($memberid, 'Not Set', " . $match['matchid'] . ')');

            if (!$this->db->getInsertId()) {
                $error = 1;
            }
        }

        if (isset($error)) {
            return false;
        }

        return true;
    }

    /**
     * @param int $uid
     * @return bool
     */
    final public function delTeamMember(int $uid): bool
    {
        $uid = (int)$uid;

        $sql = 'DELETE FROM ' . $this->db->prefix('gamers_teamstatus') . " WHERE uid=$uid AND teamid=" . $this->getVar('teamid');

        if (!$this->db->query($sql)) {
            return false;
        }

        $sql = 'SELECT a.avid FROM ' . $this->db->prefix('gamers_availability') . ' a, ' . $this->db->prefix('gamers_matches') . ' m, ' . $this->db->prefix('gamers_teamstatus') . " ts WHERE ts.uid=$uid AND ts.teamid=" . $this->getVar('teamid') . " AND ts.uid=a.userid AND a.matchid=m.matchid AND m.matchresult='Pending'";

        $result = $this->db->query($sql);
        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), E_USER_ERROR);
        }

        while (false !== ($availability = $this->db->fetchArray($result))) {
            $this->db->query('DELETE FROM ' . $this->db->prefix('gamers_availability') . ' WHERE avid=' . $availability['avid']);

            if (!$this->db->getAffectedRows()) {
                $error = 1;
            }
        }

        if (isset($error)) {
            return false;
        }

        return true;
    }

    /**
     * @param int $positionid
     * @return bool
     */
    final public function addTeamPosition(int $positionid): bool
    {
        $positionid = (int)$positionid;

        $sql = 'INSERT INTO ' . $this->db->prefix('gamers_teampositions') . " (posid, teamid) VALUES ($positionid, " . $this->getVar('teamid') . ')';

        if (!$this->db->query($sql)) {
            return false;
        }

        return true;
    }

    /**
     * @param int $positionid
     * @return bool
     */
    final public function delTeamPosition(int $positionid): bool
    {
        $positionid = (int)$positionid;

        $sql = 'DELETE FROM ' . $this->db->prefix('gamers_teampositions') . " WHERE posid=$positionid AND teamid=" . $this->getVar('teamid');

        if (!$this->db->query($sql)) {
            return false;
        }

        return true;
    }

    /**
     * @param int $positionid
     * @return bool
     */
    final public function addTeamSkill(int $positionid): bool
    {
        $positionid = (int)$positionid;

        $sql = 'INSERT INTO ' . $this->db->prefix('gamers_teampositions') . " (posid, teamid) VALUES ($positionid, " . $this->getVar('teamid') . ')';

        if (!$this->db->query($sql)) {
            return false;
        }

        return true;
    }

    /**
     * @param int $positionid
     * @return bool
     */
    final public function delTeamSkill(int $positionid): bool
    {
        $positionid = (int)$positionid;

        $sql = 'DELETE FROM ' . $this->db->prefix('gamers_teampositions') . " WHERE posid=$positionid AND teamid=" . $this->getVar('teamid');

        if (!$this->db->query($sql)) {
            return false;
        }

        return true;
    }

    /**
     * @param int $serverid
     * @return bool
     */
    final public function addTeamServer(int $serverid): bool
    {
        $serverid = (int)$serverid;

        $sql = 'INSERT INTO ' . $this->db->prefix('gamers_teamservers') . " (serverid, teamid) VALUES ($serverid, " . $this->getVar('teamid') . ')';

        if (!$this->db->query($sql)) {
            return false;
        }

        return true;
    }

    /**
     * @param int $serverid
     * @return bool
     */
    final public function delTeamServer(int $serverid): bool
    {
        $serverid = (int)$serverid;

        $sql = 'DELETE FROM ' . $this->db->prefix('gamers_teamservers') . " WHERE serverid=$serverid AND teamid=" . $this->getVar('teamid');

        if (!$this->db->query($sql)) {
            return false;
        }

        return true;
    }

    /**
     * @param int $mapid
     * @return bool
     */
    final public function addMap(int $mapid): bool
    {
        $mapid = (int)$mapid;

        $sql = 'INSERT INTO ' . $this->db->prefix('gamers_teammaps') . " (mapid, teamid) VALUES ($mapid, " . $this->getVar('teamid') . ')';

        if (!$this->db->query($sql)) {
            return false;
        }

        return true;
    }

    /**
     * @param int $mapid
     * @return bool
     */
    final public function delMap(int $mapid): bool
    {
        $mapid = (int)$mapid;

        $sql = 'DELETE FROM ' . $this->db->prefix('gamers_teammaps') . " WHERE mapid=$mapid AND teamid=" . $this->getVar('teamid');

        if (!$this->db->query($sql)) {
            return false;
        }

        return true;
    }

    /**
     * @param int $size_id
     * @return bool
     */
    final public function addTeamSize(int $size_id): bool
    {
        $size_id = (int)$size_id;

        $sql = 'INSERT INTO ' . $this->db->prefix('gamers_teamsizes') . ' (teamid, sizeid) VALUES (' . $this->getVar('teamid') . ", $size_id)";

        if (!$this->db->query($sql)) {
            return false;
        }

        return true;
    }

    /**
     * @param int $sizeid
     * @return bool
     */
    final public function delTeamSize(int $sizeid): bool
    {
        $sizeid = (int)$sizeid;

        $sql = 'DELETE FROM ' . $this->db->prefix('gamers_teamsizes') . " WHERE sizeid=$sizeid AND teamid=" . $this->getVar('teamid');

        if (!$this->db->query($sql)) {
            return false;
        }

        return true;
    }

    /**
     * @param int $side_id
     * @return bool
     */
    final public function addTeamSide(int $side_id): bool
    {
        $side_id = (int)$side_id;

        $sql = 'INSERT INTO ' . $this->db->prefix('gamers_teamsides') . ' (teamid, sideid) VALUES (' . $this->getVar('teamid') . ", $side_id)";

        if (!$this->db->query($sql)) {
            return false;
        }

        return true;
    }

    /**
     * @param int $sideid
     * @return bool
     */
    final public function delTeamSide(int $sideid): bool
    {
        $sideid = (int)$sideid;

        $sql = 'DELETE FROM ' . $this->db->prefix('gamers_teamsides') . " WHERE sideid=$sideid AND teamid=" . $this->getVar('teamid');

        if (!$this->db->query($sql)) {
            return false;
        }

        return true;
    }

    /**
     * @param int $rank_id
     * @return bool
     */
    final public function addTeamRank(int $rank_id): bool
    {
        $rank_id = (int)$rank_id;

        $sql = 'INSERT INTO ' . $this->db->prefix('gamers_teamrank') . ' (teamid, rankid) VALUES (' . $this->getVar('teamid') . ", $rank_id)";

        if (!$this->db->query($sql)) {
            return false;
        }

        return true;
    }

    /**
     * @param int $rankid
     * @return bool
     */
    final public function delTeamRank(int $rankid): bool
    {
        $rankid = (int)$rankid;

        $sql = 'DELETE FROM ' . $this->db->prefix('gamers_teamrank') . " WHERE rankid=$rankid AND teamid=" . $this->getVar('teamid');

        if (!$this->db->query($sql)) {
            return false;
        }

        return true;
    }

    /**
     * @param int $ladder_id
     * @return bool
     */
    final public function addTeamLadder(int $ladder_id): bool
    {
        $ladder_id = (int)$ladder_id;

        $sql = 'INSERT INTO ' . $this->db->prefix('gamers_teamladders') . ' (teamid, ladderid) VALUES (' . $this->getVar('teamid') . ", $ladder_id)";

        if (!$this->db->query($sql)) {
            return false;
        }

        return true;
    }

    /**
     * @param int $ladderid
     * @return bool
     */
    final public function delTeamLadder(int $ladderid): bool
    {
        $ladderid = (int)$ladderid;

        $sql = 'DELETE FROM ' . $this->db->prefix('gamers_teamladders') . " WHERE ladderid=$ladderid AND teamid=" . $this->getVar('teamid');

        if (!$this->db->query($sql)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $caption
     * @param array  $array
     */
    final public function positions(string $caption, array $array)
    {
        global $xoopsTpl;

        $teampositions = $this->getPositions();

        $teamskills = $this->getSkills();

        $shortlist = $this->getShortList();

        $players    = [];
        $pricount   = [];
        $seccount   = [];
        $tercount   = [];
        $skillcount = [];
        $positions  = [];
        $skills     = [];

        foreach ($teampositions as $posid => $posshort) {
            $pricount[$posid] = 0;

            $seccount[$posid] = 0;

            $tercount[$posid] = 0;

            $positions[$posid] = $shortlist[$posid];
        }

        foreach ($teamskills as $posid => $posshort) {
            $skillcount[$posid] = 0;

            $skills[$posid] = $shortlist[$posid];
        }

        foreach ($array as $uid => $player) {
            $pos = [];

            if ($player['primary']) {
                $pos[0]['posshort'] = $shortlist[$player['primary']];

                $pos[0]['priority'] = 1;

                $count = $pricount[$player['primary']];

                $count++;

                $pricount[$player['primary']] = $count;
            }

            if ($player['secondary']) {
                $pos[1]['posshort'] = $shortlist[$player['secondary']];

                $pos[1]['priority'] = 2;

                $count = $seccount[$player['secondary']];

                $count++;

                $seccount[$player['secondary']] = $count;
            }

            if (isset($player['tertiary']) && ($player['tertiary'] > 0)) {
                $pos[2]['posshort'] = $shortlist[$player['tertiary']];

                $pos[2]['priority'] = 3;

                $count = $tercount[$player['tertiary']];

                $count++;

                $tercount[$player['tertiary']] = $count;
            }

            foreach ($player['skills'] as $key => $posshort) {
                $pos[]['posshort'] = $posshort;

                $count = $skillcount[$key];

                $count++;

                $skillcount[$key] = $count;
            }

            if (isset($class) && ('odd' === $class)) {
                $class = 'even';
            } else {
                $class = 'odd';
            }

            $players[] = ['uid' => $uid, 'name' => $player['name'], 'class' => $class, 'positions' => $pos];
        }

        $table = ['caption' => $caption, 'players' => $players, 'pricount' => $pricount, 'seccount' => $seccount, 'tercount' => $tercount, 'skillcount' => $skillcount];

        $xoopsTpl->append('tables', $table);

        $xoopsTpl->assign('teampos', ['posshort' => $positions]);

        $xoopsTpl->assign('teamskills', ['posshort' => $skills]);

        $xoopsTpl->assign('numcells', count($teampositions) + count($teamskills) + 1);

        $xoopsTpl->assign('width', 100 / (count($teampositions) + count($teamskills) + 1));

        $xoopsTpl->assign('numskills', count($teamskills));
    }

    /**
     * Returns an array representation of the object
     *
     * @return array
     */
    final public function toArray(): array
    {
        $ret = [];

        $vars = $this->getVars();

        foreach (array_keys($vars) as $i) {
            $ret[$i] = $this->getVar($i);
        }

        return $ret;
    }
}
