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

require_once __DIR__ . '/preloads/autoloader.php';

$moduleDirName      = basename(__DIR__);
$moduleDirNameUpper = \mb_strtoupper($moduleDirName);
$helper             = Helper::getInstance();

$modversion['version']             = '3.2.0-Alpha 1';
$modversion['module_status']       = '';
$modversion['release_date']        = '2022/12/16';
$modversion['name']                = _MI_GAMERS_NAME;
$modversion['versionstatus']       = 'Full';
$modversion['description']         = _MI_GAMERS_DESC;
$modversion['credits']             = 'The XOOPS Project';
$modversion['license']             = 'GNU GPL 2.0 or later';
$modversion['license_url']         = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['official']            = 1;
$modversion['author']              = 'Mithrandir';
$modversion['image']               = 'images/logo.png';
$modversion['dirname']             = $moduleDirName;
$modversion['module_website_url']  = 'www.xoops.org';
$modversion['module_website_name'] = 'XOOPS';
$modversion['min_php']             = '7.4';
$modversion['min_xoops']           = '2.5.10';
$modversion['min_admin']           = '1.2';
$modversion['min_db']              = ['mysql' => '5.5'];

// ------------------- Help files ------------------- //
$modversion['help']        = 'page=help';
$modversion['helpsection'] = [
    ['name' => _MI_GAMERS_OVERVIEW, 'link' => 'page=help'],
    ['name' => _MI_GAMERS_DISCLAIMER, 'link' => 'page=disclaimer'],
    ['name' => _MI_GAMERS_LICENSE, 'link' => 'page=license'],
    ['name' => _MI_GAMERS_SUPPORT, 'link' => 'page=support'],
    ['name' => _MI_GAMERS_TERMS, 'link' => 'page=terms'],
];

// ------------------- Mysql ------------------- //
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
// Tables created by sql file (without prefix!)
$modversion['tables'] = [
    $moduleDirName . '_' . 'availability',
    $moduleDirName . '_' . 'layout', //Move to module config
    $moduleDirName . '_' . 'ladders', //No class
    $moduleDirName . '_' . 'lineups_positions',
    $moduleDirName . '_' . 'mappool',
    $moduleDirName . '_' . 'matches',
    $moduleDirName . '_' . 'matchmaps',
    $moduleDirName . '_' . 'positions', //No class
    $moduleDirName . '_' . 'rank', //No class
    $moduleDirName . '_' . 'server', //No class
    $moduleDirName . '_' . 'sides', //No class
    $moduleDirName . '_' . 'sizes', //No class
    $moduleDirName . '_' . 'skills', //No class
    $moduleDirName . '_' . 'status', //No class
    $moduleDirName . '_' . 'tactics',
    $moduleDirName . '_' . 'tactics_positions',
    $moduleDirName . '_' . 'team',
    $moduleDirName . '_' . 'teamladders', //No class
    $moduleDirName . '_' . 'teammaps', //No class
    $moduleDirName . '_' . 'teampositions', //No class
    $moduleDirName . '_' . 'teamservers', //No class
    $moduleDirName . '_' . 'teamstatus', //No class
    $moduleDirName . '_' . 'teamsides', //No class
    $moduleDirName . '_' . 'teamsizes', //No class
    $moduleDirName . '_' . 'teamrank', //No class
];

// Admin things
$modversion['hasAdmin']    = 1;
$modversion['system_menu'] = 1;
$modversion['adminindex']  = 'admin/index.php';
$modversion['adminmenu']   = 'admin/menu.php';

$modversion['onUpdate'] = 'include/update.php';

// Blocks
$modversion['blocks'][1]['file']        = 'availability.php';
$modversion['blocks'][1]['name']        = _MI_GAMERS_BNAME1;
$modversion['blocks'][1]['description'] = 'Shows personal availability';
$modversion['blocks'][1]['show_func']   = 'sh_availability';
$modversion['blocks'][2]['file']        = 'navmenu.php';
$modversion['blocks'][2]['name']        = _MI_GAMERS_BNAME2;
$modversion['blocks'][2]['description'] = 'Shows Navigation Menu';
$modversion['blocks'][2]['show_func']   = 'sh_navmenu';

// Templates
$modversion['templates'][1]['file']         = 'gamers_userprofile.tpl';
$modversion['templates'][1]['description']  = 'Player Profile';
$modversion['templates'][2]['file']         = 'gamers_matchdetails.tpl';
$modversion['templates'][2]['description']  = 'Match Details';
$modversion['templates'][3]['file']         = 'gamers_matchlist.tpl';
$modversion['templates'][3]['description']  = 'List of Matches';
$modversion['templates'][4]['file']         = 'gamers_availability.tpl';
$modversion['templates'][4]['description']  = 'Match Availability';
$modversion['templates'][5]['file']         = 'gamers_roster.tpl';
$modversion['templates'][5]['description']  = 'Team Roster';
$modversion['templates'][6]['file']         = 'gamers_select.tpl';
$modversion['templates'][6]['description']  = 'Selection Template';
$modversion['templates'][7]['file']         = 'gamers_teamadmin.tpl';
$modversion['templates'][7]['description']  = 'Team Administration';
$modversion['templates'][8]['file']         = 'gamers_avstats.tpl';
$modversion['templates'][8]['description']  = 'Availability Statistics';
$modversion['templates'][9]['file']         = 'gamers_positions.tpl';
$modversion['templates'][9]['description']  = 'Position Tables';
$modversion['templates'][10]['file']        = 'gamers_tactics_list.tpl';
$modversion['templates'][10]['description'] = 'Tactics list table';

// Menu
$modversion['hasMain']        = 1;
$modversion['sub'][1]['name'] = _MI_GAMERS_SMNAME2;
$modversion['sub'][1]['url']  = 'index.php';
$modversion['sub'][2]['name'] = _MI_GAMERS_SMNAME3;
$modversion['sub'][2]['url']  = 'roster.php';
$modversion['sub'][3]['name'] = _MI_GAMERS_SMNAME4;
$modversion['sub'][3]['url']  = 'tactics.php';

//Preferences
/*
$modversion['config'][1]['name'] = 'selected_sections';
$modversion['config'][1]['title'] = '_MI_GAMERS_SECTIONS';
$modversion['config'][1]['description'] = '_MI_GAMERS_SECTIONSDESC';
$modversion['config'][1]['formtype'] = 'select_multi';
$modversion['config'][1]['valuetype'] = 'array';
$modversion['config'][1]['default'] = array('Tactics', 'Matches', 'Availability', 'Stats', 'Player Profiles');
$modversion['config'][1]['options'] = array(
                                    'Tactics' => 1,
                                    'Matches' => 2,
                                    'Availability' => 3,
                                    'Stats' => 4,
                                    'Player Profiles' => 5);
*/
//Notification
$modversion['hasNotification']             = 1;
$modversion['notification']['lookup_file'] = 'include/notification.inc.php';
$modversion['notification']['lookup_func'] = 'gamers_notify_iteminfo';

$modversion['notification']['category'][1]['name']           = 'match';
$modversion['notification']['category'][1]['title']          = _MI_GAMERS_MATCH_NOTIFY;
$modversion['notification']['category'][1]['description']    = _MI_GAMERS_MATCH_NOTIFYDSC;
$modversion['notification']['category'][1]['subscribe_from'] = ['matchdetails.php', 'availability.php'];
$modversion['notification']['category'][1]['item_name']      = 'mid';

$modversion['notification']['category'][2]['name']           = 'team';
$modversion['notification']['category'][2]['title']          = _MI_GAMERS_MATCH_NOTIFY;
$modversion['notification']['category'][2]['description']    = _MI_GAMERS_MATCH_NOTIFYDSC;
$modversion['notification']['category'][2]['subscribe_from'] = 'index.php';
$modversion['notification']['category'][2]['item_name']      = 'teamid';

$modversion['notification']['event'][1]['name']          = 'new_match';
$modversion['notification']['event'][1]['category']      = 'team';
$modversion['notification']['event'][1]['title']         = _MI_GAMERS_NEWMATCH_NOTIFY;
$modversion['notification']['event'][1]['caption']       = _MI_GAMERS_NEWMATCH_NOTIFYCAP;
$modversion['notification']['event'][1]['description']   = _MI_GAMERS_NEWMATCH_NOTIFYDSC;
$modversion['notification']['event'][1]['mail_template'] = 'gamers_newmatch_notify';
$modversion['notification']['event'][1]['mail_subject']  = _MI_GAMERS_NEWMATCH_NOTIFYSBJ;

$modversion['notification']['event'][2]['name']          = 'new_lineup';
$modversion['notification']['event'][2]['category']      = 'match';
$modversion['notification']['event'][2]['title']         = _MI_GAMERS_NEWLINEUP_NOTIFY;
$modversion['notification']['event'][2]['caption']       = _MI_GAMERS_NEWLINEUP_NOTIFYCAP;
$modversion['notification']['event'][2]['description']   = _MI_GAMERS_NEWLINEUP_NOTIFYDSC;
$modversion['notification']['event'][2]['mail_template'] = 'gamers_newlineup_notify';
$modversion['notification']['event'][2]['mail_subject']  = _MI_GAMERS_NEWLINEUP_NOTIFYSBJ;

/**
 * Make Sample button visible?
 */
$modversion['config'][] = [
    'name'        => 'displaySampleButton',
    'title'       => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON',
    'description' => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

/**
 * Show Developer Tools?
 */
$modversion['config'][] = [
    'name'        => 'displayDeveloperTools',
    'title'       => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_DEV_TOOLS',
    'description' => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_DEV_TOOLS_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];
