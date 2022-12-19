<?php declare(strict_types=1);


// Module Info

// The name of this module
define('_MI_GAMERS_NAME', 'Gamers');

// A brief description of this module
define('_MI_GAMERS_DESC', 'Creates a section for Teams, Matches and Availability');

// Names of blocks for this module (Not all module has blocks)
define('_MI_GAMERS_BNAME1', 'Availabilities');
define('_MI_GAMERS_BNAME2', 'Team Menu');

// Sub menus in main menu block
define('_MI_GAMERS_SMNAME2', 'Matches');
define('_MI_GAMERS_SMNAME3', 'Roster');
define('_MI_GAMERS_SMNAME4', 'Tactics');

// Names of admin menu items

define('_MI_GAMERS_ADMENU0', 'Home');
define('_MI_GAMERS_ADMENU2', 'Matches');
define('_MI_GAMERS_ADMENU3', 'Positions');
define('_MI_GAMERS_ADMENU4', 'Maps');
define('_MI_GAMERS_ADMENU6', 'Teams');
define('_MI_GAMERS_ADMENU7', 'Servers');
define('_MI_GAMERS_ADMENU8', 'TeamSizes');
define('_MI_GAMERS_ADMENU9', 'Sides');
define('_MI_GAMERS_ADMENU10', 'Ranks');
define('_AM_GAMERS_ABOUT', 'About');


//Added 10/9-2003 Mithrandir for Notification
define('_MI_GAMERS_MATCH_NOTIFY', 'Match');
define('_MI_GAMERS_MATCH_NOTIFYDSC', 'Notification options that apply to the current match');

define('_MI_GAMERS_NEWMATCH_NOTIFY', 'New Match');
define('_MI_GAMERS_NEWMATCH_NOTIFYCAP', 'Notify me of new matches for the current team.');
define('_MI_GAMERS_NEWMATCH_NOTIFYDSC', 'Receive notification when a new match is created for the current team.');
define('_MI_GAMERS_NEWMATCH_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New match for team');

define('_MI_GAMERS_NEWLINEUP_NOTIFY', 'New Lineup');
define('_MI_GAMERS_NEWLINEUP_NOTIFYCAP', 'Notify me when lineup for the current match is set.');
define('_MI_GAMERS_NEWLINEUP_NOTIFYDSC', 'Receive notification when the lineup is created for the current match.');
define('_MI_GAMERS_NEWLINEUP_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : Lineup for match set');

//Added 17/10-2003 - v1.30 - Mithrandir
define('_MI_GAMERS_SECTIONS', 'Sections Enabled');
define('_MI_GAMERS_SECTIONSDESC', 'Select the sections you want to be enabled in the Gamers Module');

// Added 17.11.2003 - Jace
define('_MI_GAMERS_ADMENU11', 'Layout');

// Added 24.11.2003 - Jace
define('_MI_GAMERS_ADMENU12', 'Ladders');

//Config
define('MI_GAMERS_EDITOR_ADMIN', 'Editor: Admin');
define('MI_GAMERS_EDITOR_ADMIN_DESC', 'Select the Editor to use by the Admin');
define('MI_GAMERS_EDITOR_USER', 'Editor: User');
define('MI_GAMERS_EDITOR_USER_DESC', 'Select the Editor to use by the User');

//Help
define('_MI_GAMERS_DIRNAME', basename(dirname(__DIR__, 2)));
define('_MI_GAMERS_HELP_HEADER', __DIR__ . '/help/helpheader.tpl');
define('_MI_GAMERS_BACK_2_ADMIN', 'Back to Administration of ');
define('_MI_GAMERS_OVERVIEW', 'Overview');

//define('_MI_GAMERS_HELP_DIR', __DIR__);

//help multipage
define('_MI_GAMERS_DISCLAIMER', 'Disclaimer');
define('_MI_GAMERS_LICENSE', 'License');
define('_MI_GAMERS_SUPPORT', 'Support');
define('_MI_GAMERS_TERMS', 'Gaming Terms');


