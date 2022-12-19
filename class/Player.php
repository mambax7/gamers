<?php declare(strict_types=1);

namespace XoopsModules\Gamers;
// Class for Player management for Gamers Module
if (!defined('XOOPS_ROOT_PATH')) {
    die('Xoops root path not defined');
}

/**
 * Class Player
 */
class Player extends \XoopsUser
{
    public array $teams = [];

    public array $positions = [];

    public $db;

    //Constructor

    /**
     * Player constructor.
     * @param int $id
     */
    public function __construct($id = -1)
    {
        $this->db = \XoopsDatabaseFactory::getDatabaseConnection();

        parent::__construct($id);
    }

    /**
     * @return array
     */
    public function getTeams(): array
    {
        $player = [];

        $sql = 'SELECT statusid, teamid FROM ' . $this->db->prefix('gamers_teamstatus') . " WHERE uid='" . $this->getVar('uid') . "'";

        $result = $this->db->query($sql);
        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), E_USER_ERROR);
        }

        while (false !== ($teammember = $this->db->fetchArray($result))) {
            $player[$teammember['statusid']] = $teammember['teamid'];
        }

        return $player;
    }

    /**
     * @param $pending
     * @return array
     */
    public function getAvailabilities($pending): array
    {
        if (1 === $pending) {
            $type = "AND m.matchresult='Pending'";
        } else {
            $type = "AND m.matchresult<>'Pending'";
        }

        $availability = [];

        $sql = 'SELECT a.matchid, a.availability, m.matchdate, m.matchresult FROM ' . $this->db->prefix('gamers_availability') . ' a, ' . $this->db->prefix('gamers_matches') . ' m WHERE m.matchid=a.matchid AND a.userid=' . $this->getVar('uid') . ' ' . $type . ' ORDER BY m.matchdate DESC';

        $result = $this->db->query($sql);
        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), E_USER_ERROR);
        }

        while (false !== ($availabilities = $this->db->fetchArray($result))) {
            $availability[$availabilities['matchid']] = $availabilities['availability'];
        }

        return $availability;
    }

    /**
     * @param $teamid
     * @return array
     */
    public function getRank($teamid): array
    {
        $thisrank = [];

        $teamid = (int)$teamid;

        $sql = 'SELECT r.rankid, r.rank FROM ' . $this->db->prefix('gamers_teamstatus') . ' ts, ' . $this->db->prefix('gamers_rank') . " r WHERE ts.rank=r.rankid AND ts.teamid=$teamid AND ts.uid='" . $this->getVar('uid') . "'";

        $result = $this->db->query($sql);
        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), E_USER_ERROR);
        }

        while (false !== ($rank = $this->db->fetchArray($result))) {
            $thisrank['rank'] = $rank['rank'];

            $thisrank['rankid'] = $rank['rankid'];
        }

        return $thisrank;
    }
}
