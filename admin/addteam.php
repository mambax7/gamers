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
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author       Mithrandir, Mamba, XOOPS Development Team
 */


use Xmf\Module\Admin;
use XoopsModules\Gamers\{
    Helper
};

require_once __DIR__ . '/admin_header.php';
//require dirname(__DIR__, 3) . '/include/cp_header.php';
//require __DIR__ . '/functions.php';

xoops_cp_header();
if (isset($teamid)) {
    $teamHandler = Helper::getInstance()->getHandler('Team');

    $team = $teamHandler->get($teamid);

    $teamname = $team->getVar('teamname');

    $teamtype = $team->getVar('teamtype');

    $maps = $team->getVar('maps');

    $submit = 'Edit';
} else {
    $teamname = 'Name';

    $teamtype = 'Game';

    $maps = '3';

    $submit = 'Add';
}
$uid = $xoopsUser->getVar('uid');
echo "<table border='0' cellpadding='0' cellspacing='0' valign='top' width='100%'>";
echo "<tr><td class='bg6'><table width='100%' border='0' cellpadding='0' cellspacing='0'>";
echo "<tr class='bg6'><td><img src='" . $pathIcon32 . "/add.png'></td>";
echo '</tr></table>';

//$adminObject = Admin::getInstance();
//$adminObject->displayNavigation(basename(__FILE__));

echo "<table width='100%' border='0' cellpadding='4' cellspacing='1'>
          <form method='post' action='main.php' ENCTYPE=\"multipart/form-data\" NAME=\"Add\">
          <input type='hidden' name='op' value='saveteam'>";
echo "<input type='hidden' name='created' value=" . time() . '>';
echo "<input type='hidden' name='uid' value=" . $uid . '>';
echo "<input type='hidden' name='submit' value=" . $submit . '>';
if (isset($teamid)) {
    echo "<input type='hidden' name='teamid' value=" . $teamid . '>';
}
echo '<tr><td><b>' . _AM_GAMERS_NAME . "</b></td><td><input type='text' name='name' size='20' maxlength='25' value='" . $teamname . "'</td></tr>
  		<tr><td><b>" . _AM_GAMERS_TYPE . "</b></td><td><input type='text' name='type' size='20' maxlength='25' value='" . $teamtype . "'</td></tr>
        <tr><td><b>" . _AM_GAMERS_MAPSPERMATCH . "</b></td><td><input type='text' name='maps' size='10' maxlength='15' value='" . $maps . "'</td></tr>
        <tr><td align='left'><input type=submit value='" . $submit . "'></form></td></tr>
        </table></td></tr></table>";

xoops_cp_footer();
