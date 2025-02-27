<?php declare(strict_types=1);

namespace XoopsModules\Gamers\Common;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Class Migrate synchronize existing tables with target schema
 *
 * @category  Migrate
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2016 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
 */
class Migrate extends \Xmf\Database\Migrate
{
    /**
     * @readonly
     */
    private string $moduleDirName;
    /**
     * @readonly
     */
    private array $renameColumns;
    /**
     * @readonly
     */
    private array $renameTables;

    /**
     * Migrate constructor.
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function __construct()
    {
        $class = __NAMESPACE__ . '\\' . 'Configurator';
        if (!\class_exists($class)) {
            throw new \RuntimeException("Class '$class' not found");
        }
        $configurator        = new $class();
        $this->renameTables  = $configurator->renameTables;
        $this->renameColumns = $configurator->renameColumns;

        $this->moduleDirName = \basename(\dirname(__DIR__, 2));
        parent::__construct($this->moduleDirName);
    }

    /**
     * change table prefix if needed
     */
    private function changePrefix(): void
    {
        //        foreach ($this->renameTables as $oldName => $newName) {
        //            if ($this->tableHandler->useTable($oldName) && !$this->tableHandler->useTable($newName)) {
        //                $this->tableHandler->renameTable($oldName, $newName);
        //            }
        //        }
    }

    /**
     * Change integer IPv4 column to varchar IPv6 capable
     *
     * @param string $tableName  table to convert
     * @param string $columnName column with IP address
     */
    private function convertIPAddresses(string $tableName, string $columnName): void
    {
        //        if ($this->tableHandler->useTable($tableName)) {
        //            $attributes = $this->tableHandler->getColumnAttributes($tableName, $columnName);
        //            if (false !== \mb_strpos($attributes, ' int(')) {
        //                if (false === \mb_strpos($attributes, 'unsigned')) {
        //                    $this->tableHandler->alterColumn($tableName, $columnName, " bigint(16) NOT NULL  DEFAULT '0' ");
        //                    $this->tableHandler->update($tableName, [$columnName => "4294967296 + $columnName"], "WHERE $columnName < 0", false);
        //                }
        //                $this->tableHandler->alterColumn($tableName, $columnName, " varchar(45)  NOT NULL  DEFAULT '' ");
        //                $this->tableHandler->update($tableName, [$columnName => "INET_NTOA($columnName)"], '', false);
        //            }
        //        }
    }

    /**
     * @deprecated (just as an example here)
     * Move do* columns from newbb_posts to newbb_posts_text table
     */
    private function moveDoColumns(): void
    {
        //        $tableName    = 'newbb_posts_text';
        //        $srcTableName = 'newbb_posts';
        //        if ($this->tableHandler->useTable($tableName)
        //            && $this->tableHandler->useTable($srcTableName)) {
        //            $attributes = $this->tableHandler->getColumnAttributes($tableName, 'dohtml');
        //            if (false === $attributes) {
        //                $this->synchronizeTable($tableName);
        //                $updateTable = $GLOBALS['xoopsDB']->prefix($tableName);
        //                $joinTable   = $GLOBALS['xoopsDB']->prefix($srcTableName);
        //                $sql         = "UPDATE `$updateTable` t1 INNER JOIN `$joinTable` t2 ON t1.post_id = t2.post_id \n" . "SET t1.dohtml = t2.dohtml,  t1.dosmiley = t2.dosmiley, t1.doxcode = t2.doxcode\n" . '  , t1.doimage = t2.doimage, t1.dobr = t2.dobr';
        //                $this->tableHandler->addToQueue($sql);
        //            }
        //        }
    }

    /**
     * rename table if needed
     */
    private function renameTable(): void
    {
        foreach ($this->renameTables as $oldName => $newName) {
            if ($this->tableHandler->useTable($oldName) && !$this->tableHandler->useTable($newName)) {
                $this->tableHandler->renameTable($oldName, $newName);
            }
        }
    }

    /**
     * rename columns if needed
     */
    private function renameColumns(): void
    {
        foreach ($this->renameColumns as $tableName) {
            if ($this->tableHandler->useTable($tableName)) {
                $oldName    = $tableName['from'];
                $newName    = $tableName['to'];
                $attributes = $this->tableHandler->getColumnAttributes($tableName, $oldName);
                if (strpos((string)$attributes, ' int(') !== false) {
                    $this->tableHandler->alterColumn($tableName, $oldName, $attributes, $newName);
                }
            }
        }
    }

    /**
     * Perform any upfront actions before synchronizing the schema
     *
     * Some typical uses include
     *   table and column renames
     *   data conversions
     */
    protected function preSyncActions(): void
    {
        // change 'bb' table prefix to 'newbb'
        $this->changePrefix();
        // columns dohtml, dosmiley, doxcode, doimage and dobr moved between tables as some point
        $this->moveDoColumns();
        // Convert IP address columns from int to readable varchar(45) for IPv6
        //        $this->convertIPAddresses('newbb_posts', 'poster_ip');
        //        $this->convertIPAddresses('newbb_report', 'reporter_ip');

        // rename table
        if ($this->renameTables && \is_array($this->renameTables)) {
            $this->renameTable();
        }
        // rename column
        if ($this->renameColumns && \is_array($this->renameColumns)) {
            $this->renameColumns();
        }
    }
}
