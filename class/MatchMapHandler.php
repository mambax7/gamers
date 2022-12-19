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

if (!\defined('XOOPS_ROOT_PATH')) {
    die('Xoops root path not defined');
}
//if (!class_exists('XoopsPersistableObjectHandler')) {
//    require_once XOOPS_ROOT_PATH . '/modules/gamers/class/object.php';
//}

/**
 * Class MatchMapHandler
 */
class MatchMapHandler extends \XoopsPersistableObjectHandler
{
    /**
     * MatchMapHandler constructor.
     * @param $db
     */
    public function __construct($db)
    {
        parent::__construct($db, 'gamers_matchmaps', MatchMap::class, 'matchmapid');
    }

    /**
     * @param mixed $id
     * @param bool  $fields
     * @return array|mixed|object
     */

    public function get($id = null, $fields = null)//public function &get($id, $as_object = true)
    {
        $ret = parent::get($id, $fields);

        $mapHandler = Helper::getInstance()
                            ->getHandler('Map');

        $map = $mapHandler->get($ret->getVar('mapid'));

        $ret->map = $map;

        return $ret;
    }

    /**
     * @param bool $isNew
     * @return MatchMap
     */
    public function create($isNew = true): MatchMap
    {
        $ret = parent::create($isNew);

        $mapHandler = Helper::getInstance()
                            ->getHandler('Map');

        $map = $mapHandler->create(true);

        $ret->map = $map;

        return $ret;
    }

    /**
     * @param      $matchid
     * @param null $mapno
     * @return array|mixed|object
     */
    public function getByMatchid($matchid, $mapno = null)
    {
        $mapids   = [];
        $criteria = new \Criteria('matchid', (int)$matchid);

        if (null !== $mapno) {
            $criteria = new \CriteriaCompo($criteria);

            $criteria->add(new \Criteria('mapno', (int)$mapno));
        }

        $criteria->setSort('mapno');

        $objs = $this->getObjects($criteria);

        $ret = [];

        if ((\is_countable($objs) ? \count($objs) : 0) > 0) {
            foreach (\array_keys($objs) as $i) {
                $mapids[] = $objs[$i]->getVar('mapid');
            }

            $mapHandler = Helper::getInstance()
                                ->getHandler('Map');

            $maps = $mapHandler->getObjects(new \Criteria('mapid', '(' . \implode(',', \array_unique($mapids)) . ')', 'IN'), true);

            foreach (\array_keys($objs) as $i) {
                $objs[$i]->map = $maps[$objs[$i]->getVar('mapid')] ?? $mapHandler->create(false);

                $ret[$objs[$i]->getVar('mapno')] = $objs[$i];
            }
        }

        return null === $mapno ? $ret : ($ret[$mapno] ?? $this->create());
    }
}
