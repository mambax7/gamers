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

if (!\defined('XOOPS_ROOT_PATH')) {
    die('Xoops root path not defined');
}
//if (!class_exists('XoopsPersistableObjectHandler')) {
//    require_once XOOPS_ROOT_PATH . '/modules/gamers/class/object.php';
//}

/**
 * Class MatchHandler
 */
class MatchHandler extends \XoopsPersistableObjectHandler
{
    /**
     * MatchHandler constructor.
     * @param $db
     */
    public function __construct($db)
    {
        parent::__construct($db, 'gamers_matches', Match2::class, 'matchid');
    }

    /**
     * delete a match from the database
     *
     * @param \XoopsObject $object reference to the {@link XoopsMatch} to delete
     * @param bool         $force
     * @return bool FALSE if failed.
     */
    public function delete(\XoopsObject $object, $force = false): bool
    {
        if (parent::delete($object, $force)) {
            $matchid = (int)$object->getVar('matchid');

            $criteria = new \Criteria('matchid', $matchid);

            $mapHandler = Helper::getInstance()->getHandler('MatchMap');

            if (!$mapHandler->deleteAll($criteria, $force)) {
                return false;
            }

            $availabilityHandler = Helper::getInstance()->getHandler('Availability');

            if (!$availabilityHandler->deleteAll($criteria, $force)) {
                return false;
            }

            $lineupHandler = Helper::getInstance()->getHandler('LineupPosition');

            if (!$lineupHandler->deleteAll($criteria, $force)) {
                return false;
            }

            global $xoopsModule;

            $moduleId = $xoopsModule->getVar('mid');

            \xoops_notification_deletebyitem($moduleId, 'match', $matchid);

            return true;
        }

        return false;
    }
}
