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

if (!\defined('XOOPS_ROOT_PATH')) {
    die('Xoops root path not defined');
}
//if (!class_exists('XoopsPersistableObjectHandler')) {
//    require_once XOOPS_ROOT_PATH . '/modules/gamers/class/object.php';
//}

/**
 * Class Tactics
 */
class Tactics extends \XoopsObject
{
    public $tacticstable;

    public $positionstable;

    public $maptable;

    public $db;

    public $map;

    //Constructor

    /**
     * Tactics constructor.
     * @param int  $tacid
     * @param int|null $mapid
     * @param int|null $teamsize
     */
    public function __construct(int $tacid = 0, $mapid = null, $teamsize = null)
    {
        $this->initVar('tacid', \XOBJ_DTYPE_INT, 0, false);

        $this->initVar('teamsize', \XOBJ_DTYPE_INT);

        $this->initVar('teamid', \XOBJ_DTYPE_INT);

        $this->initVar('general', \XOBJ_DTYPE_TXTAREA);

        $this->initVar('mapid', \XOBJ_DTYPE_INT);

        $this->db = \XoopsDatabaseFactory::getDatabaseConnection();

        $this->tacticstable = $this->db->prefix('gamers_tactics');

        $this->positionstable = $this->db->prefix('gamers_tactics_positions');

        $this->maptable = $this->db->prefix('gamers_mappool');

        if (\is_array($tacid)) {
            $this->assignVars($tacid);
        } elseif ((0 !== $tacid) && (null !== $teamsize)) {
            //$tacid is actually a teamid

            $tacticsHandler = Helper::getInstance()->getHandler('Tactics');

            $tactics = $tacticsHandler->getByParams($tacid, $mapid, $teamsize);

            foreach ($tactics->vars as $k => $v) {
                $this->assignVar($k, $v['value']);
            }

            unset($tactics);
        } elseif (0 !== $tacid) {
            $tacticsHandler = Helper::getInstance()->getHandler('Tactics');

            $tactics = $tacticsHandler->get($tacid);

            foreach ($tactics->vars as $k => $v) {
                $this->assignVar($k, $v['value']);
            }

            unset($tactics);
        }
    }

    /**
     * @return array
     */
    public function getPositions()
    {
        $array = [];
        $sql = 'SELECT tacposid FROM ' . $this->positionstable . ' WHERE tacid=' . $this->getVar('tacid') . ' ORDER BY tacid';

        $result = $this->db->query($sql);

        if (!$this->db->isResultSet($result)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), \E_USER_ERROR);
        }

        while (false !== ($row = $this->db->fetchArray($result))) {
            $array[] = $row['tacposid'];
        }

        return $array;
    }

    /**
     * @return void
     */
    public function show()
    {
        $positionHandler = Helper::getInstance()->getHandler('TacticsPosition');

        $teamHandler = Helper::getInstance()->getHandler('Team');

        $team = $teamHandler->get($this->getVar('teamid'));

        echo "<table border='0' cellpadding='0' cellspacing='0' valign='top' width='100%'>";

        echo "<tr><td><table width='100%' border='0' cellpadding='0' cellspacing='0'>";

        echo "<tr class='head'><td><h3>";

        echo $this->getVar('teamsize') . ' ' . _MD_GAMERS_VERSUS . ' ' . $this->getVar('teamsize') . ' ' . _MD_GAMERS_TACTICSFOR . ' ' . $team->getVar('teamname') . ' ' . _MD_GAMERS_ON . ' ' . $this->map->getVar('mapname');

        echo "</h3></td><td align='right'>";

        echo "<a href='tactics.php?op=mantactics&tacid=" . $this->getVar('tacid') . "'>";

        echo "<img src='images/edit.gif' border='0' alt='Edit'></a></td></tr>";

        echo '<tr><td colspan=2>';

        require XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

        $mform = new \XoopsThemeForm(_MD_GAMERS_TACTICSDISPLAY, 'display', \xoops_getenv('PHP_SELF'));

        $general = new \XoopsFormLabel(_MD_GAMERS_GENERALTACS, $this->getVar('general'));

        $mform->addElement($general);

        $positions = $this->getPositions();

        $posshortlist = $team->getShortList();

        foreach ($positions as $key => $tacposid) {
            $thispos = $positionHandler->get($tacposid);

            $posshort = $posshortlist[$thispos->getVar('posid')];

            $position[$key] = new \XoopsFormLabel($posshort, $thispos->getVar('posdesc'));

            $mform->addElement($position[$key]);
        }

        $mform->display();

        echo '</table></td></tr></table>';
    }
}
