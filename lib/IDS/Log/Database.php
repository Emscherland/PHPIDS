<?php

/**
 * PHP IDS
 *
 * Requirements: PHP5, SimpleXML
 *
 * Copyright (c) 2007 PHPIDS (http://php-ids.org)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 2 of the license.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

/**
 * You can use this SQL to create the db and tabkle structure the logger needs.
 * 

	DROP DATABASE IF NOT EXISTS `phpids`; 
	CREATE DATABASE IF NOT EXISTS `phpids` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
	DROP TABLE IF EXISTS `intrusions`;
	CREATE TABLE IF NOT EXISTS `intrusions` (
	  `id` int(11) unsigned NOT NULL auto_increment,
	  `name` varchar(128) NOT NULL,
	  `value` text NOT NULL,
	  `page` varchar(255) NOT NULL,
	  `ip` varchar(15) NOT NULL,
	  `impact` int(11) unsigned NOT NULL,
	  `created` datetime NOT NULL,
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM ;

 * 
 * 
 *  
 */

require_once 'IDS/Log/Interface.php';

/**
* File wrapper
*
* This class is designed to store incoming data in
* a file and implements the singleton pattern
*
* @author   christ1an <ch0012@gmail.com>
*
* @version  $Id: File.php 338 2007-08-05 17:36:06Z mario $
*/
class IDS_Log_Database implements IDS_Log_Interface {

    private $wrapper = null;
    private $user = null;
    private $password = null;
    private $handle = null;
    private $statement = null;
    private static $instances = array();

    /**
    * Constructor
    *
    * @param    string
    * @access   protected
    * @return   void
    */
    protected function __construct($wrapper = false, $user = false, $password = false) {
    
    	if($wrapper && $user && $password) {
            $this->wrapper = $wrapper;
            $this->user = $user;
            $this->password = $password;	
		} else {
            throw new Exception('Invalid connection parameters');
		}

		try {
			$this->handle = new PDO($this->wrapper, 
			$this->user, 
			$this->password);
			    	                                       
			$this->statement = $this->handle->prepare('INSERT INTO intrusions 
			                                          (name, value, page, ip, impact, created) 
			                                          VALUES (:name, :value, :page, :ip, :impact, now())');    	                                       
			
		} catch (PDOException $e) {
            die('PDOException: ' . $e->getMessage());    	
		}
    }

    /**
    * Returns an instance of this class
    *
    * @param    string
    * @access   public
    * @return   object
    */
    public static function getInstance($wrapper, $user, $password) {
        if (!isset(self::$instances[$wrapper])) {
            self::$instances[$wrapper] = new IDS_Log_Database(
                $wrapper,
                $user,
                $password
            );
        }

        return self::$instances[$wrapper];
    }

    /**
    * Just for the sake of completeness
    * of a correct singleton pattern
    */
    private function __clone() { }

    /**
    * Stores incoming data record into a file
    *
    * @param    mixed
    * @access   public
    * @return   mixed   bool or exception object on failure
    */
    public function execute(IDS_Report $data) {
        
        foreach($data as $event) {
        	
        	$page = isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'';
        	$ip = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:$_SERVER['HTTP_X_FORWARDED_FOR'];
        	
            $this->statement->bindParam('name', $event->getName());
            $this->statement->bindParam('value', $event->getValue());
            $this->statement->bindParam('page', $page);
            $this->statement->bindParam('ip', $ip);
            $this->statement->bindParam('impact', $data->getImpact());
            
            if(!$this->statement->execute()) { 
                throw new Exception($this->statement->errorCode());     
            }
        }
        
        return true;
    }

}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 */