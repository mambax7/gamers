<?php declare(strict_types=1);


use XoopsModules\Gamers\{
    Helper
};
/** @var Helper $helper */


// resize picture and copy it to destination
/**
 * @param $sourcefile
 * @param $dest_x
 * @param $dest_y
 * @param $targetfile
 * @param $jpegqual
 * @return bool
 */
function resizeToFile($sourcefile, $dest_x, $dest_y, $targetfile, $jpegqual)
{
    /* Get the dimensions of the source picture */

    $picsize = getimagesize((string)$sourcefile);

    $source_x = $picsize[0];

    $source_y = $picsize[1];

    $source_id = imagecreatefromjpeg((string)$sourcefile);

    /* Create a new image object (not neccessarily true color) */

    $target_id = imagecreatetruecolor($dest_x, $dest_y);

    /* Resize the original picture and copy it into the just created image
    object. */

    $target_pic = imagecopyresampled($target_id, $source_id, 0, 0, 0, 0, $dest_x, $dest_y, $source_x, $source_y);

    /* Create a jpeg with the quality of "$jpegqual" out of the
    image object "$target_pic".
    This will be saved as $targetfile */

    imagejpeg($target_id, (string)$targetfile, $jpegqual);

    return true;
}

/**
 * @param int $serverid
 * @return array
 */
function getServer($serverid)
{
    global $xoopsDB;

    $server = [];

    $serverid = (int)$serverid;

    $sql = 'SELECT serverid, servername, serverip, serverport FROM ' . $xoopsDB->prefix('gamers_server') . " WHERE serverid=$serverid";

    $result = $xoopsDB->query($sql);
    if (!$xoopsDB->isResultSet($result)) {
        \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
    }

    $myserver = $xoopsDB->fetchArray($result);

    $server['id'] = $myserver['serverid'];

    $server['name'] = $myserver['servername'];

    $server['ip'] = $myserver['serverip'];

    $server['port'] = $myserver['serverport'];

    return $server;
}

/**
 * @return mixed
 */
function getTeams()
{
    global $xoopsDB;

    $team = [];

    $teamHandler = Helper::getInstance()->getHandler('Team');

    $criteria = new \CriteriaCompo();

    $criteria->setSort('defteam DESC, teamname');

    return $teamHandler->getList($criteria);
}

/**
 * @return int
 */
function getDefaultTeam()
{
    global $xoopsDB;

    $teamHandler = Helper::getInstance()->getHandler('Team');

    $criteria = new \Criteria('defteam', 1);

    $teams = $teamHandler->getObjects($criteria, false);

    if (isset($teams[0])) {
        return $teams[0]->getVar('teamid');
    }

    return 0;
}

/**
 * @param $posid
 * @return false|mixed
 */
function getPosName($posid)
{
    global $xoopsDB;

    if (null === $posid || 0 == $posid) {
        return false;
    }

    $posid = (int)$posid;

    $sql = 'SELECT posname FROM ' . $xoopsDB->prefix('gamers_positions') . " WHERE posid=$posid";

    $result = $xoopsDB->query($sql);
    if (!$xoopsDB->isResultSet($result)) {
        \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
    }

    $posname = $xoopsDB->fetchArray($result);

    return $posname['posname'];
}

/**
 * @param $posid
 * @return mixed|string
 */
function getShort($posid)
{
    global $xoopsDB;

    $posid = (int)$posid;

    $sql = 'SELECT posshort FROM ' . $xoopsDB->prefix('gamers_positions') . " WHERE posid=$posid";

    $result = $xoopsDB->query($sql);
    if (!$xoopsDB->isResultSet($result)) {
        \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
    }

    $count = 0;

    $posshort = '';

    while (false !== ($row = $xoopsDB->fetchArray($result))) {
        $posshort = $row['posshort'];

        $count++;
    }

    return $posshort;
}

/**
 * @return string
 */
function getAllShort()
{
    global $xoopsDB;

    $sql = 'SELECT posid, posshort FROM ' . $xoopsDB->prefix('gamers_positions') . ' ORDER BY posid';

    $result = $xoopsDB->query($sql);
    if (!$xoopsDB->isResultSet($result)) {
        \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
    }

    $posshort = '';

    while (false !== ($row = $xoopsDB->fetchArray($result))) {
        $posshort[$row['posid']] = $row['posshort'];
    }

    return $posshort;
}

/**
 * @return string
 */
function getAllPos()
{
    global $xoopsDB;

    $sql = 'SELECT posid, posname FROM ' . $xoopsDB->prefix('gamers_positions') . ' ORDER BY posid';

    $result = $xoopsDB->query($sql);
    if (!$xoopsDB->isResultSet($result)) {
        \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
    }

    $posshort = '';

    while (false !== ($row = $xoopsDB->fetchArray($result))) {
        $posshort[$row['posid']] = $row['posname'];
    }

    return $posshort;
}

/**
 * @param int $sideid
 * @return mixed|string
 */
function getSideShort($sideid)
{
    global $xoopsDB;

    $sideid = (int)$sideid;

    $sql = 'SELECT sideshort FROM ' . $xoopsDB->prefix('gamers_sides') . " WHERE sideid=$sideid";

    $result = $xoopsDB->query($sql);
    if (!$xoopsDB->isResultSet($result)) {
        \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
    }

    $count = 0;

    $short = '';

    while (false !== ($row = $xoopsDB->fetchArray($result))) {
        $short = $row['sideshort'];

        $count++;
    }

    return $short;
}

/**
 * @return string
 */
function getAllSideShort()
{
    global $xoopsDB;

    $sql = 'SELECT sideid, sideshort FROM ' . $xoopsDB->prefix('gamers_sides') . ' ORDER BY sideid';

    $result = $xoopsDB->query($sql);
    if (!$xoopsDB->isResultSet($result)) {
        \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
    }

    $sides = '';

    while (false !== ($row = $xoopsDB->fetchArray($result))) {
        $sides[$row['sideid']] = $row['sideshort'];
    }

    return $sides;
}

/**
 * @return array
 */
function getAllLadders()
{
    global $xoopsDB;

    $sql = 'SELECT ladderid, ladder, visible, scoresvisible FROM ' . $xoopsDB->prefix('gamers_ladders');

    $result = $xoopsDB->query($sql);
    if (!$xoopsDB->isResultSet($result)) {
        \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
    }

    $ladders = [];

    while (false !== ($row = $xoopsDB->fetchArray($result))) {
        $ladders[$row['ladderid']] = ['ladder' => $row['ladder'], 'visible' => $row['visible'], 'scoresvisible' => $row['scoresvisible']];
    }

    return $ladders;
}

/**
 * @param int $sideid
 * @return mixed|string
 */
function getSide($sideid)
{
    global $xoopsDB;

    $sideid = (int)$sideid;

    $sql = 'SELECT side FROM ' . $xoopsDB->prefix('gamers_sides') . " WHERE sideid=$sideid";

    $result = $xoopsDB->query($sql);
    if (!$xoopsDB->isResultSet($result)) {
        \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
    }

    $count = 0;

    $side = '';

    while (false !== ($row = $xoopsDB->fetchArray($result))) {
        $side = $row['side'];

        $count++;
    }

    return $side;
}

/**
 * @return string
 */
function getAllSides()
{
    global $xoopsDB;

    $sql = 'SELECT sideid, side FROM ' . $xoopsDB->prefix('gamers_sides') . ' ORDER BY sideid';

    $result = $xoopsDB->query($sql);
    if (!$xoopsDB->isResultSet($result)) {
        \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
    }

    $sides = '';

    while (false !== ($row = $xoopsDB->fetchArray($result))) {
        $sides[$row['sideid']] = $row['side'];
    }

    return $sides;
}

/**
 * @param $teamid
 * @param int $uid
 * @param $skillid
 * @return false|string
 */
function skillcheck($teamid, $uid, $skillid)
{
    global $xoopsDB;

    $teamid = (int)$teamid;

    $uid = (int)$uid;

    $skillid = (int)$skillid;

    $sql = 'SELECT skillid FROM ' . $xoopsDB->prefix('gamers_skills') . " WHERE teamid=$teamid AND uid=$uid AND posid=$skillid";

    $result = $xoopsDB->query($sql);

    if ($xoopsDB->getRowsNum($result) > 0) {
        return 'checked';
    }

    return false;
}

/**
 * @param $status
 * @return mixed
 */
function getStatus($status)
{
    global $xoopsDB;

    $status = (int)$status;

    $sql = 'SELECT status FROM ' . $xoopsDB->prefix('gamers_status') . ' WHERE statusid=' . $status;

    $result = $xoopsDB->query($sql);
    if (!$xoopsDB->isResultSet($result)) {
        \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
    }

    $mystatus = $xoopsDB->fetchArray($result);

    return $mystatus['status'];
}

/**
 * @return array
 */
function getAllStatus()
{
    global $xoopsDB;

    $status = [];

    $sql = 'SELECT statusid, status FROM ' . $xoopsDB->prefix('gamers_status') . ' ORDER BY statusid';

    $result = $xoopsDB->query($sql);
    if (!$xoopsDB->isResultSet($result)) {
        \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
    }

    while (false !== ($mystatus = $xoopsDB->fetchArray($result))) {
        $status[$mystatus['statusid']] = $mystatus['status'];
    }

    return $status;
}

/**
 * @return mixed
 */
function getAllRanks()
{
    $rank = [];
    global $xoopsDB;

    $sql = 'SELECT rankid, rank, color FROM ' . $xoopsDB->prefix('gamers_rank') . ' ORDER BY rankid';

    $result = $xoopsDB->query($sql);
    if (!$xoopsDB->isResultSet($result)) {
        \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
    }

    while (false !== ($myrank = $xoopsDB->fetchArray($result))) {
        $rank[$myrank['rankid']]['rank'] = $myrank['rank'];

        $rank[$myrank['rankid']]['color'] = $myrank['color'];
    }

    return $rank;
}

/**
 * @param $rank
 * @return array|false
 */
function getRank($rank)
{
    global $xoopsDB;

    $rank = (int)$rank;

    $sql = 'SELECT rank, color FROM ' . $xoopsDB->prefix('gamers_rank') . ' WHERE rankid=' . $rank;

    $result = $xoopsDB->query($sql);
    if (!$xoopsDB->isResultSet($result)) {
        \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
    }

    return $xoopsDB->fetchArray($result);
}

/**
 * @param $statusid
 * @return mixed
 */
function getPlayerStatus($statusid)
{
    $playerstatus = null;
    global $xoopsDB;

    $statusid = (int)$statusid;

    $sql = 'SELECT status FROM ' . $xoopsDB->prefix('gamers_teamstatus') . " WHERE statusid=$statusid";

    $result = $xoopsDB->query($sql);
    if (!$xoopsDB->isResultSet($result)) {
        \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
    }

    while (false !== ($teamstatus = $xoopsDB->fetchArray($result))) {
        $playerstatus = getStatus($teamstatus['status']);
    }

    return $playerstatus;
}

/**
 * @param $teamid
 * @param int $uid
 * @return array|false
 */
function getPlayerRank($teamid, $uid)
{
    global $xoopsDB;

    $teamid = (int)$teamid;

    $uid = (int)$uid;

    $sql = 'SELECT rank FROM ' . $xoopsDB->prefix('gamers_teamstatus') . " WHERE teamid=$teamid AND uid=$uid";

    $result = $xoopsDB->query($sql);
    if (!$xoopsDB->isResultSet($result)) {
        \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
    }

    $playerrank = $xoopsDB->fetchArray($result);

    return getRank($playerrank['rank']);
}

/**
 * @param int $mapid
 * @return mixed|string
 */
function getMap($mapid)
{
    global $xoopsDB;

    $mapid = (int)$mapid;

    $sql = 'SELECT mapname FROM ' . $xoopsDB->prefix('gamers_mappool') . ' WHERE mapid=' . $mapid;

    $result = $xoopsDB->query($sql);
    if ($xoopsDB->isResultSet($result)) {
        $result = $xoopsDB->fetchArray($result);

        return $result['mapname'];
    }

    return _MD_GAMERS_UNDECIDED;
}

/**
 * @param $mapno
 * @return string
 */
function getCaption($mapno)
{
    if (1 == $mapno) {
        return _MD_GAMERS_FIRSTMAP;
    } elseif (2 == $mapno) {
        return _MD_GAMERS_SECONDMAP;
    } elseif (3 == $mapno) {
        return _MD_GAMERS_THIRDMAP;
    } elseif (4 == $mapno) {
        return _MD_GAMERS_FOURTHMAP;
    } elseif (5 == $mapno) {
        return _MD_GAMERS_FIFTHMAP;
    }
}

/****************************
/* Fetches and returns layout data from database
 */
function getLayout()
{
    global $xoopsDB;

    $sql = 'SELECT * FROM ' . $xoopsDB->prefix('gamers_layout');

    $result = $xoopsDB->query($sql);
    if (!$xoopsDB->isResultSet($result)) {
        \trigger_error("Query Failed! SQL: $sql- Error: " . $xoopsDB->error(), E_USER_ERROR);
    }

    return $xoopsDB->fetchArray($result);
}

/**
 * @param $val1
 * @param $val2
 * @return false|string
 */
function selectcheck($val1, $val2)
{
    if ($val1 == $val2) {
        return 'selected';
    }

    return false;
}
