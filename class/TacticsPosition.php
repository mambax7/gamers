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
 * Class TacticsPosition
 */
class TacticsPosition extends \XoopsObject
{
    public $db;

    //Constructor

    /**
     * TacticsPosition constructor.
     * @param int $tacposid
     */
    public function __construct(int $tacposid = 0)
    {
        $lineupid = null;
        $this->db = \XoopsDatabaseFactory::getDatabaseConnection();

        $this->initVar('tacposid', XOBJ_DTYPE_INT);

        $this->initVar('tacid', XOBJ_DTYPE_INT);

        $this->initVar('posid', XOBJ_DTYPE_INT);

        $this->initVar('posdesc', XOBJ_DTYPE_TXTAREA);

        if (is_array($tacposid)) {
            $this->assignVars($tacposid);
        } elseif (0 !== $tacposid) {
            $positionHandler = Helper::getInstance()->getHandler('TacticsPosition');

            $position = $positionHandler->get($lineupid);

            foreach ($position->vars as $k => $v) {
                $this->assignVar($k, $v['value']);
            }

            unset($position);
        }
    }
}
