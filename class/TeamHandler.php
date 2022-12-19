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
 * Class TeamHandler
 */
class TeamHandler extends \XoopsPersistableObjectHandler
{
    /**
     * TeamHandler constructor.
     * @param $db
     */
    public function __construct($db)
    {
        parent::__construct($db, 'gamers_team', Team::class, 'teamid', 'teamname');
    }

    /**
     * delete a team from the database
     *
     * @param \XoopsObject $team reference to the {@link Team} to delete
     * @param bool         $force
     * @return bool FALSE if failed.
     */
    public function delete(\XoopsObject $team, $force = false)
    {
        if (parent::delete($team, $force)) {
            $teamid = $team->getVar('teamid');

            $sql = 'DELETE FROM ' . $this->db->prefix('gamers_teamstatus') . " WHERE teamid = $teamid";

            if (!$result = $this->db->query($sql)) {
                return false;
            }

            $sql = 'DELETE FROM ' . $this->db->prefix('gamers_teamrank') . " WHERE teamid = $teamid";

            if (!$result = $this->db->query($sql)) {
                return false;
            }

            $sql = 'DELETE FROM ' . $this->db->prefix('gamers_teampositions') . " WHERE teamid = $teamid";

            if (!$result = $this->db->query($sql)) {
                return false;
            }

            $sql = 'DELETE FROM ' . $this->db->prefix('gamers_teammaps') . " WHERE teamid = $teamid";

            if (!$result = $this->db->query($sql)) {
                return false;
            }

            $sql = 'DELETE FROM ' . $this->db->prefix('gamers_teamservers') . " WHERE teamid = $teamid";

            if (!$result = $this->db->query($sql)) {
                return false;
            }

            $sql = 'DELETE FROM ' . $this->db->prefix('gamers_teamsizes') . " WHERE teamid = $teamid";

            if (!$result = $this->db->query($sql)) {
                return false;
            }

            $module_id = $GLOBALS['xoopsModule']->getVar('mid');

            xoops_notification_deletebyitem($module_id, 'team', $teamid);
        }

        return true;
    }

    /**
     * @param $team
     * @return bool
     */
    public function setDefault($team)
    {
        $this->updateAll('defteam', 0);

        return $this->updateAll('defteam', 1, new \Criteria('teamid', $team->getVar('teamid')));
    }
}
