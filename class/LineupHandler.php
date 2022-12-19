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

// Class for Lineup management for Gamers Module

if (!defined('XOOPS_ROOT_PATH')) {
    die('Xoops root path not defined');
}
//if (!class_exists('XoopsPersistableObjectHandler')) {
//    require_once XOOPS_ROOT_PATH . '/modules/gamers/class/object.php';
//}

/**
 * Class LineupHandler
 */
class LineupHandler extends \XoopsObjectHandler
{
    /**
     * retrieve a lineup
     *
     * @param array $matchmapid ID of matchmap
     * @return mixed reference to the {@link Lineup} object, FALSE if failed
     */
    public function &get($matchmapid)
    {
        $ret = new Lineup();

        $matchmapHandler = Helper::getInstance()
                                 ->getHandler('MatchMap');

        $matchmap = $matchmapHandler->get($matchmapid);

        $ret->setVar('matchmapid', $matchmap->getVar('matchmapid'));

        $ret->setVar('matchid', $matchmap->getVar('matchid'));

        $ret->setVar('mapid', $matchmap->getVar('mapid'));

        $ret->setVar('general', $matchmap->getVar('general'));

        $ret->map = $matchmap->map;

        return $ret;
    }
}
