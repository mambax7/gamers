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

// Class for Match management for Gamers Module


/**
 * Class XoopsMatch
 */
class XoopsMatch extends \XoopsObject
{
    public string $table;
    public \XoopsDatabase $db;
    public int $teamid;
    public int $matchid;


    //Constructor

    /**
     * XoopsMatch constructor.
     * @param int $matchid
     */
    public function __construct(int $matchid = -1)
    {
        $this->db = \XoopsDatabaseFactory::getDatabaseConnection();

        $this->table = $this->db->prefix('gamers_matches');

        $this->initVar('matchid', \XOBJ_DTYPE_INT);

        $this->initVar('uid', \XOBJ_DTYPE_INT);

        $this->initVar('matchdate', \XOBJ_DTYPE_INT);

        $this->initVar('teamid', \XOBJ_DTYPE_INT);

        $this->initVar('created', \XOBJ_DTYPE_INT);

        $this->initVar('teamsize', \XOBJ_DTYPE_INT, 0);

        $this->initVar('opponent', \XOBJ_DTYPE_TXTBOX);

        $this->initVar('ladder', \XOBJ_DTYPE_TXTBOX, '');

        $this->initVar('matchresult', \XOBJ_DTYPE_TXTBOX);

        $this->initVar('review', \XOBJ_DTYPE_TXTBOX);

        $this->initVar('server', \XOBJ_DTYPE_INT);

        $this->initVar('customserver', \XOBJ_DTYPE_TXTBOX);

        $this->initVar('alock', \XOBJ_DTYPE_INT);

        if (\is_array($matchid)) {
            $this->assignVars($matchid);
        } elseif (-1 != $matchid) {
            $matchHandler = \xoops_getHandler('match');

            $match = $matchHandler->get($matchid);

            foreach ($match->vars as $k => $v) {
                $this->assignVar($k, $v['value']);
            }

            unset($match);
        }
    }

    /**
     * @return string
     */
    public function uname(): string
    {
        return \XoopsUser::getUnameFromId($this->getVar('uid'));
    }

    /**
     * @return array
     */
    public function getMatchPlayers(): array
    {
        $sql = 'SELECT u.uid, u.uname FROM ' . $this->db->prefix('users') . ' u, ' . $this->db->prefix('gamers_availability') . ' a WHERE u.uid=a.userid AND a.matchid=' . $this->getVar('matchid') . " AND (a.availability='Yes' OR a.availability='LateYes') ORDER BY u.uname ASC";

        $result = $this->db->query($sql);
        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), \E_USER_ERROR);
        }

        $players = [];

        while (false !== ($row = $this->db->fetchArray($result))) {
            $players[$row['uid']] = $row['uname'];
        }

        return $players;
    }

    /**
     * @return array
     */
    public function getMatchSubs(): array
    {
        $sql = 'SELECT u.uid, u.uname FROM ' . $this->db->prefix('users') . ' u, ' . $this->db->prefix('gamers_availability') . ' a WHERE u.uid=a.userid AND a.matchid=' . $this->getVar('matchid') . " AND a.availability='Sub' ORDER BY u.uname ASC";

        $result = $this->db->query($sql);
        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), \E_USER_ERROR);
        }

        $players = [];

        while (false !== ($row = $this->db->fetchArray($result))) {
            $players[$row['uid']] = $row['uname'];
        }

        return $players;
    }

    /**
     * @param $criteria
     * @return array|false
     */
    public function getPositions($criteria)
    {
        $sql = 'SELECT u.uid, u.uname, ts.primarypos, ts.secondarypos, ts.tertiarypos FROM ' . $this->db->prefix('users') . ' u, ' . $this->db->prefix('gamers_availability') . ' a, ' . $this->db->prefix('gamers_teamstatus') . ' ts WHERE u.uid=a.userid AND u.uid=ts.uid AND ts.teamid=' . $this->getVar('teamid') . ' AND a.matchid=' . $this->getVar('matchid') . ' AND a.availability = ' . $this->db->quoteString($criteria) . ' ORDER BY u.uname ASC';

        $result = $this->db->query($sql);
        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), \E_USER_ERROR);
        }

        $players = [];

        if ($this->db->getRowsNum($result) > 0) {
            while (false !== ($row = $this->db->fetchArray($result))) {
                $players[$row['uid']]['name'] = $row['uname'];

                $players[$row['uid']]['primary'] = $row['primarypos'];

                $players[$row['uid']]['secondary'] = $row['secondarypos'];

                $players[$row['uid']]['tertiary'] = $row['tertiarypos'];

                $sql = 'SELECT s.posid, p.posshort FROM ' . $this->db->prefix('gamers_skills') . ' s, ' . $this->db->prefix('gamers_positions') . ' p WHERE s.uid=' . $row['uid'] . ' AND s.posid=p.posid AND s.teamid=' . $this->getVar('teamid') . ' ORDER BY p.posorder ASC';

                $result = $this->db->query($sql);
                if (!$this->db->isResultSet($result)) {
                    \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), \E_USER_ERROR);
                }

                $skills = [];

                while (false !== ($skill = $this->db->fetchArray($result))) {
                    $skills[$skill['posid']] = $skill['posshort'];
                }

                $players[$row['uid']]['skills'] = $skills;
            }

            return $players;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getMatchMaps(): array
    {
        $sql = 'SELECT mapno, matchmapid FROM ' . $this->db->prefix('gamers_matchmaps') . ' WHERE matchid = ' . $this->getVar('matchid') . ' ORDER BY mapno';

        $result = $this->db->query($sql);
        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), \E_USER_ERROR);
        }

        $maps = [];

        while (false !== ($thismap = $this->db->fetchArray($result))) {
            $maps[$thismap['mapno']] = $thismap['matchmapid'];
        }

        return $maps;
    }

    /**
     * @return void
     */
    public function lock()
    {
        $sql = 'UPDATE ' . $this->db->prefix('gamers_matches') . ' SET alock=1 WHERE matchid=' . $this->getVar('matchid');

        $this->db->query($sql);

        if ($this->db->getAffectedRows() > 0) {
            \redirect_header('index.php?teamid=' . $this->teamid, 3, _MD_GAMERS_MATCHLOCKED);
        } else {
            \redirect_header('availability.php?mid=' . $this->matchid, 3, _MD_GAMERS_DBNOTUPDATED);
        }
    }

    /**
     * @return void
     */
    public function unlock()
    {
        $sql = 'UPDATE ' . $this->db->prefix('gamers_matches') . ' SET alock=0 WHERE matchid=' . $this->getVar('matchid');

        $this->db->query($sql);

        if ($this->db->getAffectedRows() > 0) {
            \redirect_header('index.php?teamid=' . $this->teamid, 3, _MD_GAMERS_MATCHUNLOCKED);
        } else {
            \redirect_header('availability.php?mid=' . $this->matchid, 3, _MD_GAMERS_DBNOTUPDATED);
        }
    }

    /**
     * @return mixed
     */
    public function getAvailabilities()
    {
        $sql = 'SELECT a.avid, a.userid, a.availability, a.comment, u.uname FROM ' . $this->db->prefix('gamers_availability') . ' a, ' . $this->db->prefix('users') . ' u WHERE a.matchid=' . $this->getVar('matchid') . ' AND u.uid=a.userid ORDER BY u.uname ASC';

        return $this->db->query($sql);
    }

    // Returns number of maps for this match

    /**
     * @return mixed
     */
    public function getMapCount()
    {
        $sql = 'SELECT COUNT(matchmapid) as maps FROM ' . $this->db->prefix('gamers_matchmaps') . ' WHERE matchid=' . $this->getVar('matchid');

        $result = $this->db->query($sql);
        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), \E_USER_ERROR);
        }

        $row = $this->db->fetchArray($result);

        return $row['maps'];
    }
}
