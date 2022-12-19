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

if (!defined('XOOPS_ROOT_PATH')) {
    die('Xoops root path not defined');
}
//if (!class_exists('XoopsPersistableObjectHandler')) {
//    require_once XOOPS_ROOT_PATH . '/modules/gamers/class/object.php';
//}

/**
 * Class MatchMap
 */
class MatchMap extends \XoopsObject
{
    public $map;
    public $db;

    //Constructor

    public function __construct()
    {
        $this->initVar('matchmapid', XOBJ_DTYPE_INT);

        $this->initVar('matchid', XOBJ_DTYPE_INT);

        $this->initVar('mapid', XOBJ_DTYPE_INT);

        $this->initVar('mapno', XOBJ_DTYPE_INT);

        $this->initVar('ourscore', XOBJ_DTYPE_INT);

        $this->initVar('theirscore', XOBJ_DTYPE_INT);

        $this->initVar('side', XOBJ_DTYPE_INT);

        $this->initVar('general', XOBJ_DTYPE_TXTAREA);

        $this->initVar('screenshot', XOBJ_DTYPE_TXTBOX, '');

        $this->db = \XoopsDatabaseFactory::getDatabaseConnection();
    }

    //Find winner of a map

    /**
     * @param $layout
     * @return mixed
     */
    public function winner($layout)
    {
        $our = $this->getVar('ourscore');

        $their = $this->getVar('theirscore');

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
         WHERE mapid=' . $this->getVar('mapid') . ' AND teamid=' . $teamid . ' AND teamsize=' . $teamsize;

        $result = $this->db->query($sql);

        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), E_USER_ERROR);
        }

        $array = $this->db->fetchArray($result);

        return $array['tacid'];
    }
}
