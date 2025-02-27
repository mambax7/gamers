CREATE TABLE `gamers_availability` (
    `avid`         mediumint(11) unsigned NOT NULL auto_increment,
    `userid`       int(12) unsigned NOT NULL default '0',
    `availability` varchar(12) NOT NULL,
    `comment`      varchar(25),
    `matchid`      int(11) NOT NULL default '0',
    PRIMARY KEY (`avid`),
    UNIQUE KEY `avid` (`avid`)
) ENGINE=MyISAM;

CREATE TABLE `gamers_ladders` (
    `ladderid`      TINYINT             NOT NULL AUTO_INCREMENT,
    `ladder`        VARCHAR(32)         NOT NULL,
    `visible`       TINYINT DEFAULT '1' NOT NULL,
    `scoresvisible` TINYINT DEFAULT '1' NOT NULL,
    PRIMARY KEY (`ladderid`)
);

CREATE TABLE `gamers_layout` (
    `layoutid`              mediumint(8) unsigned NOT NULL auto_increment,
    `color_status_active`   VARCHAR(12) NOT NULL,
    `color_status_inactive` VARCHAR(12) NOT NULL,
    `color_status_onleave`  VARCHAR(12) NOT NULL,
    `color_match_pending`   VARCHAR(12) NOT NULL,
    `color_match_win`       VARCHAR(12) NOT NULL,
    `color_match_loss`      VARCHAR(12) NOT NULL,
    `color_match_draw`      VARCHAR(12) NOT NULL,
    `color_perfect`         VARCHAR(12) NOT NULL,
    `color_good`            VARCHAR(12) NOT NULL,
    `color_warn`            VARCHAR(12) NOT NULL,
    `color_bad`             VARCHAR(12) NOT NULL,
    PRIMARY KEY (`layoutid`),
    UNIQUE KEY `layoutid` (`layoutid`)
);

CREATE TABLE `gamers_lineups_positions` (
    `lineupid`   mediumint(8) unsigned NOT NULL auto_increment,
    `posid`      mediumint(8),
    `posdesc`    text,
    `uid`        int(12),
    `matchmapid` int(12),
    PRIMARY KEY (`lineupid`),
    UNIQUE KEY `lineupid` (`lineupid`),
    KEY          `lineupid_2` (`lineupid`)
) ENGINE=MyISAM;

CREATE TABLE `gamers_mappool` (
    `mapid` int(11) NOT NULL auto_increment, `mapname` varchar(25) NOT NULL, PRIMARY KEY (`mapid`), UNIQUE KEY `mapid` (`mapid`)
) ENGINE=MyISAM;

CREATE TABLE `gamers_matches` (
    `matchid`      mediumint(11) unsigned NOT NULL auto_increment,
    `uid`          int(12),
    `matchdate`    int(12),
    `teamid`       int(11) NOT NULL,
    `created`      int(12),
    `teamsize`     int(5) NOT NULL,
    `opponent`     varchar(25) NOT NULL,
    `ladder`       varchar(32) NOT NULL,
    `matchresult`  varchar(12) NOT NULL default '',
    `review`       text,
    `server`       int(11),
    `customserver` varchar(32) NULL,
    `alock`        tinyint(4) default '0',
    PRIMARY KEY (`matchid`),
    UNIQUE KEY `matchid` (`matchid`)
) ENGINE=MyISAM;

CREATE TABLE `gamers_matchmaps` (
    `matchmapid` int(11) NOT NULL auto_increment,
    `mapno`      tinyint(4) NOT NULL,
    `matchid`    mediumint(11) NOT NULL,
    `mapid`      int(11),
    `side`       int(11),
    `ourscore`   int(11) default '0',
    `theirscore` int(11) default '0',
    `general`    text,
    `screenshot` varchar(64) default '',
    PRIMARY KEY (`matchmapid`),
    UNIQUE KEY `matchmapid` (`matchmapid`)
);

CREATE TABLE `gamers_positions` (
    `posid`    mediumint(8) unsigned NOT NULL auto_increment,
    `postype`  varchar(25),
    `posname`  varchar(25),
    `posshort` varchar(12),
    `posorder` tinyint(4) default '0',
    PRIMARY KEY (`posid`),
    UNIQUE KEY `posid` (`posid`)
) ENGINE=MyISAM;

CREATE TABLE `gamers_rank` (
    `rankid`  int(11) NOT NULL auto_increment,
    `rank`    varchar(25),
    `tactics` tinyint(2),
    `matches` tinyint(2),
    `color`   varchar(12),
    PRIMARY KEY (`rankid`)
);

CREATE TABLE `gamers_server` (
    `serverid`    mediumint(8) unsigned NOT NULL auto_increment,
    `servername`  varchar(32),
    `serverip`    varchar(20),
    `serverport`  mediumint(8),
    `is_bookable` tinyint(4),
    `serverzone`  tinyint(4),
    PRIMARY KEY (`serverid`),
    UNIQUE KEY `serverid` (`serverid`),
    KEY           `serverid_2` (`serverid`)
);

CREATE TABLE `gamers_sides` (
    `sideid`    mediumint(8) NOT NULL auto_increment,
    `side`      varchar(20) NOT NULL,
    `sideshort` varchar(5),
    PRIMARY KEY (`sideid`)
);

CREATE TABLE `gamers_sizes` (
    `sizeid` mediumint(8) NOT NULL auto_increment, `size` tinyint(4) NOT NULL, PRIMARY KEY (`sizeid`)
);

CREATE TABLE `gamers_skills` (
    `skillid` mediumint(8) unsigned NOT NULL auto_increment,
    `posid`   mediumint(8),
    `uid`     int(11),
    `teamid`  int(11),
    PRIMARY KEY (`skillid`),
    UNIQUE KEY `skillid` (`skillid`)
) ENGINE=MyISAM;

CREATE TABLE `gamers_status` (
    `statusid` int(11) NOT NULL auto_increment, `status` varchar(12) NOT NULL, PRIMARY KEY (`statusid`)
);

CREATE TABLE `gamers_tactics` (
    `tacid`    mediumint(8) unsigned NOT NULL auto_increment,
    `mapid`    int(11),
    `teamsize` tinyint(4) NOT NULL default '0',
    `teamid`   int(11),
    `general`  text,
    PRIMARY KEY (`tacid`),
    UNIQUE KEY `tacid` (`tacid`),
    KEY        `tacid_2` (`tacid`)
) ENGINE=MyISAM;

CREATE TABLE `gamers_tactics_positions` (
    `tacposid` mediumint(8) unsigned NOT NULL auto_increment,
    `posid`    mediumint(8),
    `posdesc`  text,
    `tacid`    mediumint(8),
    PRIMARY KEY (`tacposid`),
    UNIQUE KEY `tacposid` (`tacposid`),
    KEY        `tacposid_2` (`tacposid`)
) ENGINE=MyISAM;

CREATE TABLE `gamers_team` (
    `teamid`   int(11) NOT NULL auto_increment,
    `teamname` varchar(25) NOT NULL,
    `teamtype` varchar(25) NOT NULL,
    `maps`     tinyint(4),
    `defteam`  tinyint(4),
    PRIMARY KEY (`teamid`),
    UNIQUE KEY `teamid` (`teamid`)
);

CREATE TABLE `gamers_teamladders` (
    `teamladderid` int(11) NOT NULL auto_increment,
    `ladderid`     int(11) NOT NULL,
    `teamid`       int(11) NOT NULL,
    PRIMARY KEY (`teamladderid`)
);

CREATE TABLE `gamers_teammaps` (
    `teammapid` int(11) NOT NULL auto_increment,
    `mapid`     int(11) NOT NULL,
    `teamid`    int(11) NOT NULL,
    PRIMARY KEY (`teammapid`),
    UNIQUE KEY `teammapid` (`teammapid`)
);

CREATE TABLE `gamers_teampositions` (
    `teamposid` int(11) NOT NULL auto_increment,
    `posid`     mediumint(8) NOT NULL,
    `teamid`    int(11) NOT NULL,
    PRIMARY KEY (`teamposid`),
    UNIQUE KEY `teamposid` (`teamposid`)
);

CREATE TABLE `gamers_teamrank` (
    `teamrankid` int(11) NOT NULL auto_increment,
    `rankid`     int(11) NOT NULL,
    `teamid`     int(11) NOT NULL,
    PRIMARY KEY (`teamrankid`)
);

CREATE TABLE `gamers_teamservers` (
    `teamserverid` int(11) NOT NULL auto_increment,
    `serverid`     mediumint(8) NOT NULL,
    `teamid`       int(11) NOT NULL,
    PRIMARY KEY (`teamserverid`),
    UNIQUE KEY `teamserverid` (`teamserverid`)
);

CREATE TABLE `gamers_teamsides` (
    `teamsideid` int(11) NOT NULL auto_increment,
    `teamid`     int(11) NOT NULL,
    `sideid`     int(11) NOT NULL,
    PRIMARY KEY (`teamsideid`)
);

CREATE TABLE `gamers_teamsizes` (
    `teamsizeid` int(11) NOT NULL auto_increment,
    `teamid`     int(11) NOT NULL,
    `sizeid`     int(11) NOT NULL,
    PRIMARY KEY (`teamsizeid`)
);

CREATE TABLE `gamers_teamstatus` (
    `statusid`     int(11) NOT NULL auto_increment,
    `uid`          int(12) NOT NULL,
    `teamid`       int(11) NOT NULL,
    `status`       mediumint(8) NOT NULL,
    `rank`         int(11) NOT NULL,
    `primarypos`   mediumint(8),
    `secondarypos` mediumint(8),
    `tertiarypos`  mediumint(8),
    PRIMARY KEY (`statusid`)
);

INSERT INTO `gamers_status`
VALUES (1, 'Active');
INSERT INTO `gamers_status`
VALUES (2, 'On Leave');
INSERT INTO `gamers_status`
VALUES (3, 'Inactive');

INSERT INTO `gamers_rank`
VALUES (1, 'Player', 0, 0, 'green');
INSERT INTO `gamers_rank`
VALUES (2, 'Leader', 1, 1, 'red');
INSERT INTO `gamers_rank`
VALUES (3, 'Trial', 0, 0, 'blue');

INSERT INTO `gamers_layout`
VALUES (1, 'green', 'darkgreen', 'blue', 'black', 'green', 'red', 'blue', 'green', 'blue', 'yellow', 'red');

INSERT INTO `gamers_ladders` (`ladderid`, `ladder`, `visible`)
VALUES (null, 'Ladder', '1');
INSERT INTO `gamers_ladders` (`ladderid`, `ladder`, `visible`)
VALUES (null, 'Scrim', '1');
INSERT INTO `gamers_ladders` (`ladderid`, `ladder`, `visible`)
VALUES (null, 'Practice', '0');
