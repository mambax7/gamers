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
                                      //
if (!\defined('XOOPS_ROOT_PATH')) {
    die('Xoops root path not defined');
}
//if (!class_exists('XoopsPersistableObjectHandler')) {
//    require_once XOOPS_ROOT_PATH . '/modules/gamers/class/object.php';
//}
/*
CREATE TABLE `gamers_availability` (
  `avid` mediumint(11) unsigned NOT NULL auto_increment,
  `userid` int(12) unsigned NOT NULL default '0',
  `availability` varchar(12) NOT NULL,
  `comment` varchar(25),
  `matchid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`avid`),
  UNIQUE KEY `avid` (`avid`)
) ENGINE=MyISAM;
*/

/**
 * Class AvailabilityHandler
 */
class AvailabilityHandler extends \XoopsPersistableObjectHandler
{
    /**
     * AvailabilityHandler constructor.
     * @param $db
     */
    public function __construct($db)
    {
        parent::__construct($db, 'gamers_availability', Availability::class, 'avid');
    }

    /**
     * @param int $uid
     * @return array
     */
    public function getPendingByUser(int $uid): array
    {
        $matchHandler = Helper::getInstance()->getHandler('Match');

        $sql = 'SELECT * FROM ' . $this->table . ' a, ' . $matchHandler->table . ' m WHERE a.userid=' . (int)$uid . " AND a.matchid=m.matchid AND m.matchresult='Pending' ORDER BY m.matchdate DESC";

        $ret = [];

        $result = $this->db->query($sql);

        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), \E_USER_ERROR);
        }

        while (false !== ($myrow = $this->db->fetchArray($result))) {
            $ret[] = $myrow;
        }

        return $ret;
    }
}
