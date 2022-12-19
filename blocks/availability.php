<?php declare(strict_types=1);

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

use XoopsModules\Gamers\{
    Helper
};

/** @var Helper $helper */

xoops_loadLanguage('main', 'gamers');

/**
 * @return array|false
 */
function sh_availability()
{
    if (!xoops_isActiveModule('gamers')) {
        return [];
    }

    if (!class_exists(Helper::class)) {
        return [];
    }

    $helper = Helper::getInstance();

    global $xoopsUser;

    if (is_object($xoopsUser)) {
        $userid = $xoopsUser->getVar('uid');

        $block = [];

        $block['title'] = 'Availability for ' . $xoopsUser->getVar('uname');

        $block['content'] = "<table border='0' cellspacing='1'><div align='left'>";

        $teamHandler = $helper->getHandler('Team');

        $teamnames = $teamHandler->getList();

        $availabilityHandler = $helper->getHandler('Availability');

        $availabilities = $availabilityHandler->getPendingByUser($userid);

        foreach ($availabilities as $myrow) {
            $weekday = date('D', $myrow['matchdate']);

            $day = date(_MEDIUMDATESTRING, $myrow['matchdate']);

            $teamid = $myrow['teamid'];

            $teamname = $teamnames[$teamid];

            if ('Not Set' == $myrow['availability']) {
                $notset = 1;

                $match = 1;

                $fontcl = 'Orange';

                $avail = 'No Reply';
            } elseif (('No' == $myrow['availability']) or ('LateNo' == $myrow['availability'])) {
                $match = 1;

                $fontcl = 'Red';

                $avail = 'No';
            } elseif (('Yes' == $myrow['availability']) or ('LateYes' == $myrow['availability'])) {
                $match = 1;

                $fontcl = 'green';

                $avail = 'Yes';
            } elseif ('Sub' == $myrow['availability']) {
                $match = 1;

                $fontcl = 'blue';

                $avail = 'Sub';
            }

            if (isset($class) && ('odd' == $class)) {
                $class = 'even';
            } else {
                $class = 'odd';
            }

            $block['content'] .= '<tr class=' . $class . "><td><span color='"
                                 . $fontcl . "'>" . $weekday . ' ' . $day . ' ' . $teamname . ' vs ' . $myrow['opponent'] . "</span> - <a href='"
                                 . XOOPS_URL . '/modules/gamers/availability.php?mid=' . $myrow['matchid'] . "' target='_self'>" . $avail . "</a> - <a href='" . XOOPS_URL . '/modules/gamers/matchdetails.php?mid=' . $myrow['matchid'] . "' target='_self'>" . _BL_GAMERS_MATCHDETAILS . '</td></tr>';
        }

        if (!isset($notset)) {
            $block['content'] .= "<tr><th><span style=\"color: green; \">" . _BL_GAMERS_NOUNSETAVAIL . '</span></th></tr>';
        }

        if (!isset($match)) {
            $block['content'] .= "<tr><th><span style=\"color: green; \"></br>" . _BL_GAMERS_NOUPCOMEMATCHES . '</span></th></tr>';
        }

        $block['content'] .= '</div></table>';

        return $block;
    }

    return false;
}
