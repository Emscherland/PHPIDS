<?php
/**
 * PHPIDS
 *
 * Copyright (c) 2008 PHPIDS group (https://phpids.org) and other Contributors
 *
 * PHPIDS is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, version 3 of the License, or
 * (at your option) any later version.
 *
 * PHPIDS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with PHPIDS. If not, see <http://www.gnu.org/licenses/>.
 *
 * @category Security
 * @package  PHPIDS
 * @author   Mario Heiderich <mario.heiderich@gmail.com>
 * @author   Christian Matthies <ch0012@gmail.com>
 * @author   Lars Strojny <lars@strojny.net>
 * @license  http://www.gnu.org/licenses/lgpl.html LGPL
 * @link     http://php-ids.org/
 */

namespace IDS\Caching;

use Exception;
use IDS\Init;
use PDO;
use PDOException;

/**
 *
 */

/**
 * Database caching wrapper
 *
 * This class inhabits functionality to get and set cache via a database.
 *
 * Needed SQL:
 *
 *
 * #create the database
 *
 * CREATE DATABASE IF NOT EXISTS `phpids` DEFAULT CHARACTER
 * SET utf8 COLLATE utf8_general_ci;
 * DROP TABLE IF EXISTS `cache`;
 *
 * #now select the created datbase and create the table
 *
 * CREATE TABLE `cache` (
 * `type` VARCHAR( 32 ) NOT null ,
 * `data` TEXT NOT null ,
 * `created` DATETIME NOT null ,
 * `modified` DATETIME NOT null
 * ) ENGINE = MYISAM ;
 *
 * @category  Security
 * @package   PHPIDS
 * @author    Christian Matthies <ch0012@gmail.com>
 * @author    Mario Heiderich <mario.heiderich@gmail.com>
 * @author    Lars Strojny <lars@strojny.net>
 * @copyright 2007-2009 The PHPIDS Groupup
 * @license   http://www.gnu.org/licenses/lgpl.html LGPL
 * @link      http://php-ids.org/
 * @since     Version 0.4
 */
class DatabaseCache implements CacheInterface
{
    /**
     * Cache configuration
     *
     * @var array
     */
    private $config = null;

    /**
     * DBH
     *
     * @var object
     */
    private $handle = null;

    /**
     * Holds an instance of this class
     *
     * @var object
     */
    private static $cachingInstance = null;

    /**
     * Constructor
     *
     * Connects to database.
     *
     * @param string|null $type caching type
     * @param Init $init the IDS_Init object
     *
     * @throws Exception
     */
    public function __construct(private ?string $type, Init $init)
    {
        $this->config = $init->config['Caching'];
        $this->handle = $this->connect();
    }

    /**
     * Returns an instance of this class
     *
     * @static
     * @param string $type caching type
     * @param Init $init the IDS_Init object
     *
     * @return CacheInterface $this
     * @throws Exception
     */
    public static function getInstance(string $type, Init $init): CacheInterface
    {
        if (!self::$cachingInstance) {
            self::$cachingInstance = new DatabaseCache($type, $init);
        }

        return self::$cachingInstance;
    }

    /**
     * Writes cache data into the database
     *
     * @param array $data the caching data
     *
     * @return CacheInterface $this
     */
    public function setCache(array $data): CacheInterface
    {
        $handle = $this->handle;

        $rows = $handle->query('SELECT created FROM `' . $this->config['table'] . '`');

        if (!$rows || $rows->rowCount() === 0) {

            $this->write($handle, $data);
        } else {

            foreach ($rows as $row) {

                if ((time() - strtotime($row['created'])) >
                    $this->config['expiration_time']) {

                    $this->write($handle, $data);
                }
            }
        }

        return $this;
    }

    /**
     * Returns the cached data
     *
     * Note that this method returns false if either type or file cache is
     * not set
     *
     * @return mixed        cache data or false
     * @throws PDOException if a db error occurred
     */
    public function getCache():mixed
    {
        try {
            $handle = $this->handle;
            $result = $handle->prepare(
                'SELECT * FROM `' .
                $this->config['table'] .
                '` where type=?'
            );
            $result->execute(array($this->type));

            foreach ($result as $row) {
                return unserialize($row['data']);
            }

        } catch (PDOException $e) {
            throw new PDOException('PDOException: ' . $e->getMessage());
        }

        return false;
    }

    /**
     * Connect to database and return a handle
     *
     * @return object       PDO
     * @throws Exception    if connection parameters are faulty
     * @throws PDOException if a db error occurred
     */
    private function connect(): PDO
    {
        // validate connection parameters
        if (!$this->config['wrapper']
            || !$this->config['user']
            || !$this->config['password']
            || !$this->config['table']) {

            throw new Exception('Insufficient connection parameters');
        }

        // try to connect
        try {
            $handle = new PDO(
                $this->config['wrapper'],
                $this->config['user'],
                $this->config['password']
            );
            $handle->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

        } catch (PDOException $e) {
            throw new PDOException('PDOException: ' . $e->getMessage());
        }

        return $handle;
    }

    /**
     * Write the cache data to the table
     *
     * @param object $handle the database handle
     * @param array $data the caching data
     * @throws PDOException if a db error occurred
     */
    private function write($handle, $data)
    {
        try {
            $handle->query('TRUNCATE ' . $this->config['table'] . '');
            $statement = $handle->prepare(
                'INSERT INTO `' .
                $this->config['table'] . '` (
                    type,
                    data,
                    created,
                    modified
                )
                VALUES (
                    :type,
                    :data,
                    now(),
                    now()
                )'
            );

            $statement->bindValue(
                'type',
                $handle->quote($this->type)
            );
            $statement->bindValue('data', serialize($data));

            if (!$statement->execute()) {
                throw new PDOException($statement->errorCode());
            }
        } catch (PDOException $e) {
            throw new PDOException('PDOException: ' . $e->getMessage());
        }
    }
}
