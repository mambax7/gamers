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

// Class for Match Map management for Gamers Module

/**
 * Class XoopsMatchMap
 */
class XoopsMatchMap extends \XoopsObject
{
    public $table;

    public $db;

    public $matchmapid;

    public $matchid;

    public $mapname;

    public $mapid;

    public $mapno;

    public $ourscore;

    public $theirscore;

    public $side;

    public $general;

    public $screenshot;

    //Constructor

    /**
     * XoopsMatchMap constructor.
     * @param null $matchid
     * @param null $mapno
     */
    public function __construct($matchid = null, $mapno = null)
    {
        $this->db = \XoopsDatabaseFactory::getDatabaseConnection();

        $this->table = $this->db->prefix('gamers_matchmaps');

        if (is_array($matchid)) {
            $this->makeMatchMap($matchid);
        } elseif (null != $mapno) {
            $this->getMatchMap((int)$matchid, (int)$mapno);
        } else {
            $this->fetchMatchMap((int)$matchid);
        }
    }

    /**
     * @param $matchid
     * @param $mapno
     */
    public function getMatchMap($matchid, $mapno)
    {
        $sql = 'SELECT map.matchmapid, map.mapno, map.mapid, map.matchid, map.ourscore, map.theirscore, pool.mapname, map.side, map.general, map.screenshot FROM ' . $this->table . ' map, ' . $this->db->prefix('gamers_mappool') . ' pool WHERE map.mapid=pool.mapid AND matchid=' . $matchid . ' AND mapno=' . $mapno;

        $result = $this->db->query($sql);
        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), E_USER_ERROR);
        }

        $array = $this->db->fetchArray($result);

        if ($array) {
            $this->makeMatchMap($array);
        }
    }

    /**
     * @param $matchmapid
     */
    public function fetchMatchMap($matchmapid)
    {
        $sql = 'SELECT map.matchmapid, map.mapno, map.mapid, map.matchid, map.ourscore, map.theirscore, pool.mapname, map.side, map.general, map.screenshot FROM ' . $this->table . ' map, ' . $this->db->prefix('gamers_mappool') . ' pool WHERE map.mapid=pool.mapid AND map.matchmapid=' . $matchmapid;

        $result = $this->db->query($sql);
        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), E_USER_ERROR);
        }

        $array = $this->db->fetchArray($result);

        if ($array) {
            $this->makeMatchMap($array);
        }
    }

    /**
     * @param $array
     */
    public function makeMatchMap($array)
    {
        foreach ($array as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * @param $value
     */
    public function setMatchmapId($value)
    {
        $this->matchmapid = (int)$value;
    }

    /**
     * @param $value
     */
    public function setMatchId($value)
    {
        $this->matchid = (int)$value;
    }

    /**
     * @param $value
     */
    public function setMapid($value)
    {
        $this->mapid = (int)$value;
    }

    /**
     * @param $value
     */
    public function setMapname($value)
    {
        $this->mapname = $value;
    }

    /**
     * @param $value
     */
    public function setMapno($value)
    {
        $this->mapno = (int)$value;
    }

    /**
     * @param $value
     */
    public function setOurscore($value)
    {
        $this->ourscore = (int)$value;
    }

    /**
     * @param $value
     */
    public function setTheirscore($value)
    {
        $this->theirscore = (int)$value;
    }

    /**
     * @param $value
     */
    public function setSide($value)
    {
        $this->side = (int)$value;
    }

    /**
     * @param $value
     */
    public function setScreenshot($value)
    {
        $this->screenshot = $value;
    }

    /**
     * @return false
     */
    public function store()
    {
        if (!isset($this->matchmapid)) {
            $sql = 'INSERT INTO ' . $this->table . '
            (matchid, mapid, mapno, side)
            VALUES (' . (int)$this->matchid . ', ' . (int)$this->mapid . ', ' . (int)$this->mapno . ', ' . (int)$this->side . ')';
        } else {
            $sql = 'UPDATE ' . $this->table . '
            SET matchid=' . (int)$this->matchid . ',
            mapid=' . (int)$this->mapid . ',
            mapno=' . (int)$this->mapno . ',
            ourscore=' . (int)$this->ourscore . ',
            theirscore=' . (int)$this->theirscore . ',
            side=' . (int)$this->side . '
            WHERE matchmapid = ' . (int)$this->matchmapid;

            $newmatchmapid = $this->matchmapid;
        }

        if (!$result = $this->db->query($sql)) {
            return false;
        }

        if (empty($newmatchmapid)) {
            $newmatchmapid = $this->db->getInsertId();

            $this->matchmapid = $newmatchmapid;
        }

        return $newmatchmapid;
    }

    /**
     * @return mixed
     */
    public function matchmapid()
    {
        return $this->matchmapid;
    }

    /**
     * @return mixed
     */
    public function matchid()
    {
        return $this->matchid;
    }

    /**
     * @return mixed
     */
    public function mapid()
    {
        return $this->mapid;
    }

    /**
     * @return mixed
     */
    public function mapname()
    {
        return $this->mapname;
    }

    /**
     * @return mixed
     */
    public function mapno()
    {
        return $this->mapno;
    }

    /**
     * @return mixed
     */
    public function ourscore()
    {
        return $this->ourscore;
    }

    /**
     * @return mixed
     */
    public function theirscore()
    {
        return $this->theirscore;
    }

    /**
     * @return mixed
     */
    public function side()
    {
        return $this->side;
    }

    /**
     * @return mixed
     */
    public function screenshot()
    {
        return $this->screenshot;
    }

    //Find winner of a map

    /**
     * @param $layout
     * @return mixed
     */
    public function winner($layout)
    {
        $our = $this->ourscore;

        $their = $this->theirscore;

        $winner = $our - $their;

        if ($winner >= 1) {
            return $layout['color_match_win'];
        } elseif ($winner <= -1) {
            return $layout['color_match_loss'];
        }

        return $layout['color_match_draw'];
    }

    /**
     * @param $teamid
     * @param $teamsize
     * @return mixed
     */
    public function getTacid($teamid, $teamsize)
    {
        $teamid = (int)$teamid;

        $teamsize = (int)$teamsize;

        $sql = 'SELECT tacid FROM ' . $this->db->prefix('gamers_tactics') . '
         WHERE mapid=' . $this->mapid . ' AND teamid=' . $teamid . ' AND teamsize=' . $teamsize;

        $result = $this->db->query($sql);
        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), E_USER_ERROR);
        }

        $array = $this->db->fetchArray($result);

        return $array['tacid'];
    }
}


