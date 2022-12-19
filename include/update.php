<?php declare(strict_types=1);


use XoopsModules\Gamers\{
    Helper
};
/** @var Helper $helper */

/**
 * @param $module
 * @return bool|\mysqli_result
 */
function xoops_module_update_team($module)
{
    $lineupposHandler = Helper::getInstance()->getHandler('LineupPosition');

    $sql = 'ALTER TABLE ' . $lineupposHandler->table . ' ADD `matchmapid` int AFTER `posid`';

    $lineupposHandler->db->query($sql);

    $matchmapHandler = Helper::getInstance()->getHandler('MatchMap');

    $sql = 'UPDATE ' . $lineupposHandler->table . ' p, ' . $matchmapHandler->table . ' m SET p.matchmapid=m.matchmapid WHERE p.matchid=m.matchid AND p.mapid=m.mapid';

    if ($lineupposHandler->db->query($sql)) {
        $sql = 'ALTER TABLE ' . $lineupposHandler->table . ' DROP `matchid`, DROP `mapid`';

        return $matchmapHandler->db->query($sql);
    }

    return false;
}
