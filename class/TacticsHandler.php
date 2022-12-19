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

// Class for Tactics management for Gamers Module

if (!defined('XOOPS_ROOT_PATH')) {
    die('Xoops root path not defined');
}
//if (!class_exists('XoopsPersistableObjectHandler')) {
//    require_once XOOPS_ROOT_PATH . '/modules/gamers/class/object.php';
//}

/**
 * Class TacticsHandler
 */
class TacticsHandler extends \XoopsPersistableObjectHandler
{
    /**
     * TacticsHandler constructor.
     * @param $db
     */
    public function __construct($db)
    {
        parent::__construct($db, 'gamers_tactics', Tactics::class, 'tacid');
    }

    /**
     * retrieve a tactics
     *
     * @param int $id ID of the tactics
     * @return mixed reference to the {@link XoopsTactics} object, FALSE if failed
     */
    public function get($id = null, $fields = null)//public function &get($tacid)
    {
        $ret = parent::get($id, true);

        $mapHandler = Helper::getInstance()->getHandler('Map');

        $map = $mapHandler->get($ret->getVar('mapid'));

        $ret->map = $map;

        return $ret;
    }

    /**
     * retrieve a tactics without tacid
     *
     * @param int $teamid ID of team
     * @param int $mapid ID of map
     * @param int $teamsize number of players for this tactics
     * @return mixed reference to the {@link XoopsTactics} object, FALSE if failed
     */
    public function getByParams($teamid, $mapid, $teamsize)
    {
        $teamid = (int)$teamid;

        $mapid = (int)$mapid;

        $teamsize = (int)$teamsize;

        $sql = 'SELECT t.*, m.mapname FROM ' . $this->db->prefix('gamers_tactics') . ' t, ' . $this->db->prefix('gamers_mappool') . " m WHERE t.mapid=m.mapid AND teamid=$teamid AND t.mapid=$mapid AND teamsize=" . $teamsize;

        $result = $this->db->query($sql);
        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), E_USER_ERROR);
        }

        $tactics = $this->create(false);

        $tactics->setVar('teamid', $teamid);

        $tactics->setVar('mapid', $mapid);

        $tactics->setVar('teamsize', $teamsize);

        if ($this->db->getRowsNum($result) > 0) {
            $tactics->assignVars($this->db->fetchArray($result));

            return $tactics;
        }

        $mapHandler = Helper::getInstance()->getHandler('Map');

        $map = $mapHandler->get($mapid);

        $tactics->map = $map;

        return $tactics;
    }
}
