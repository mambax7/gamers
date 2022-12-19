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
 * Class XoopsMatchHandler
 */
class XoopsMatchHandler extends \XoopsObjectHandler
{
    /**
     * create a new match object
     *
     * @param bool $isNew flag the new objects as "new"?
     * @return object {@link XoopsMatch}
     */
    public function &create(bool $isNew = true)
    {
        $match = new \XoopsMatch();

        if ($isNew) {
            $match->setNew();
        }

        return $match;
    }

    /**
     * retrieve a match
     *
     * @param mixed $id
     * @return mixed reference to the {@link XoopsMatch} object, FALSE if failed
     */
    public function get($id)
    {
        $id = (int)$id;

        if ($id > 0) {
            $sql = 'SELECT * FROM ' . $this->db->prefix('gamers_matches') . " WHERE matchid=$id";

            $result = $this->db->query($sql);
            if (!$this->db->isResultSet($result)) {
//                \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), E_USER_ERROR);
                return false;
            }

            $match = $this->create(false);

            $match->assignVars($this->db->fetchArray($result));

            return $match;
        }

        return false;
    }

    /*
    * Save match in database
    * @param object $object reference to the {@link XoopsMatch} object
    * @param bool $force
    * @return bool FALSE if failed, TRUE if already present and unchanged or successful
    */

    /**
     * @param \XoopsObject $object
     * @param bool         $force
     * @return array|bool|int|mixed|void|null
     */
    public function insert(\XoopsObject $object, bool $force = false)
    {
        $uid = null;
        $matchdate = null;
        $teamid = null;
        $created = null;
        $teamsize = null;
        $opponent = null;
        $ladder = null;
        $review = null;
        $server = null;
        $customserver = null;
        $matchresult = null;
        $alock = null;
        $matchid = null;
        // If server from list specified do not save customserver

        if (0 != $object->getVar('server')) {
            $object->setVar('customserver', '');
        }

        if ('xoopsmatch' != mb_strtolower(get_class($object))) {
            return false;
        }

        if (!$object->isDirty()) {
            return true;
        }

        if (!$object->cleanVars()) {
            return false;
        }

        foreach ($object->cleanVars as $k => $v) {
            ${$k} = $v;
        }

        if ($object->isNew()) {
            $sql = 'INSERT INTO ' . $this->db->prefix('gamers_matches') . "
            (uid, matchdate, teamid, created, teamsize, opponent, ladder, matchresult, review, server, customserver, alock)
            VALUES ($uid, $matchdate, $teamid, $created, $teamsize, " . $this->db->quoteString($opponent) . ', ' . $this->db->quoteString($ladder) . ", 'Pending', " . $this->db->quoteString($review) . ", $server, " . $this->db->quoteString($customserver) . ',0)';
        } else {
            $sql = 'UPDATE ' . $this->db->prefix('gamers_matches') . "
            SET uid=$uid,
            opponent=" . $this->db->quoteString($opponent) . ",
            matchdate=$matchdate,
            matchresult=" . $this->db->quoteString($matchresult) . ",
            teamsize=$teamsize,
            review=" . $this->db->quoteString($review) . ',
            ladder=' . $this->db->quoteString($ladder) . ",
            server=$server,
            customserver=" . $this->db->quoteString($customserver) . ",
            alock=$alock WHERE matchid = $matchid";

            $newmatchid = $object->getVar('matchid');
        }

        if (!$result = $this->db->query($sql)) {
            return false;
        }

        if (empty($newmatchid)) {
            $newmatchid = $this->db->getInsertId();

            $object->setVar('matchid', $newmatchid);
        }

        return $newmatchid;
    }

    /**
     * delete a match from the database
     *
     * @param \XoopsObject $object reference to the {@link XoopsMatch} to delete
     * @param bool         $force
     * @return bool FALSE if failed.
     */
    public function delete(\XoopsObject $object, bool $force = false): bool
    {
        global $xoopsModule;

        $matchid = (int)$object->getVar('matchid');

        $sql = 'DELETE FROM ' . $this->db->prefix('gamers_matches') . " WHERE matchid = $matchid";

        if ($this->db->query($sql)) {
            $sql = 'DELETE FROM ' . $this->db->prefix('gamers_matchmaps') . ' WHERE matchid = ' . $matchid;

            if (!$result = $this->db->query($sql)) {
                return false;
            }

            $sql = 'DELETE FROM ' . $this->db->prefix('gamers_availability') . ' WHERE matchid = ' . $matchid;

            if (!$result = $this->db->query($sql)) {
                return false;
            }

            $sql = 'DELETE FROM ' . $this->db->prefix('gamers_lineups_positions') . ' WHERE matchid = ' . $matchid;

            if (!$result = $this->db->query($sql)) {
                return false;
            }

            $moduleId = $xoopsModule->getVar('mid');

            \xoops_notification_deletebyitem($moduleId, 'match', $matchid);

            return true;
        }

        return false;
    }
}
