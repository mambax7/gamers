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
                                      //
if (!defined('XOOPS_ROOT_PATH')) {
    die('Xoops root path not defined');
}
//if (!class_exists('XoopsPersistableObjectHandler')) {
//    require_once XOOPS_ROOT_PATH . '/modules/gamers/class/object.php';
//}

/**
 * Class Lineup
 */
class Lineup extends \XoopsObject
{
    public $map;

    //Constructor

    public function __construct()
    {
        $this->initVar('matchmapid', XOBJ_DTYPE_INT);

        $this->initVar('matchid', XOBJ_DTYPE_INT);

        $this->initVar('mapid', XOBJ_DTYPE_INT);

        $this->initVar('general', XOBJ_DTYPE_TXTAREA);
    }

    /**
     * @return mixed
     */
    public function getPositions()
    {
        $lineupposHandler = Helper::getInstance()->getHandler('LineupPosition');

        $criteria = new \CriteriaCompo(new \Criteria('matchmapid', $this->getVar('matchmapid')));

        $criteria->setSort('lineupid');

        return $lineupposHandler->getObjects($criteria, false, false);
    }

    /**
     * @return mixed|string
     */
    public function getMapname()
    {
        $mapHandler = Helper::getInstance()->getHandler('Map');

        $map_list = $mapHandler->getList(new \Criteria('mapid', $this->getVar('mapid')));

        return $map_list[$this->getVar('mapid')] ?? '';
    }

    /**
     * @return string
     */
    public function fetchGeneral(): string
    {
        $mapHandler = Helper::getInstance()->getHandler('MatchMap');

        $criteria = new \CriteriaCompo('matchmapid', $this->getVar('matchmapid'));

        $map = $mapHandler->getObjects($criteria);

        if (isset($map[0])) {
            $this->setVar('general', $map[0]->getVar('general', 'n'));

            return $map[0]->getVar('general');
        }

        return '';
    }

    /**
     * @return false|void
     */
    public function saveGeneral()
    {
        if (!$this->getVar('matchid')) {
            return false;
        }

        $mapHandler = Helper::getInstance()->getHandler('MatchMap');

        $criteria = new \CriteriaCompo('matchmapid', $this->getVar('matchmapid'));

        $map = $mapHandler->getObjects($criteria);

        if (isset($map[0])) {
            $map[0]->setVar('general', $this->getVar('general'));

            return $mapHandler->insert($map[0]);
        }

        return false;
    }

    public function show(): void
    {
        $teamHandler = Helper::getInstance()->getHandler('Team');

        $team = $teamHandler->get($this->getVar('teamid'));

        echo "<table border='0' cellpadding='0' cellspacing='0' valign='top' width='100%'>";

        echo "<tr><td><table width='100%' border='0' cellpadding='0' cellspacing='0'>";

        echo "<tr class='head'><td colspan=2><h3>";

        echo $this->getVar('teamsize') . ' ' . _MD_GAMERS_VERSUS . ' ' . $this->getVar('teamsize') . ' ' . _AM_GAMERS_TACTICSFOR . ' ' . $team->getVar('teamname') . ' ' . _AM_GAMERS_ON . ' ' . $this->getVar('mapname');

        echo '</h3></td></tr>';

        echo '<tr><td colspan=2>';

        require XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

        $mform = new \XoopsThemeForm(_AM_GAMERS_TACTICSDISPLAY, 'display', xoops_getenv('PHP_SELF'));

        $general = new \XoopsFormLabel(_AM_GAMERS_GENERALTACS, $this->getVar('general'));

        $mform->addElement($general);

        $positions = $this->getPositions();

        $posshortlist = $team->getShortList();

        foreach ($positions as $key => $tacpos) {
            $posshort = $posshortlist[$tacpos['posid']];

            $position[$key] = new \XoopsFormLabel($posshort, $tacpos['posdesc']);

            $mform->addElement($position[$key]);
        }

        $mform->display();

        echo '</table></td></tr></table>';
    }
}


