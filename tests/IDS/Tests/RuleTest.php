<?php
/**
 * PHPIDS
 *
 * Copyright (c) 2008 PHPIDS group (https://phpids.org) and other Contributors
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 2 of the license.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * @package    PHPIDS tests
 */

namespace IDS\Tests;

use IDS\Init;
use IDS\Monitor;
use PHPUnit\Framework\TestCase;

class RuleTest extends TestCase
{
    /**
     * @var Init
     */
    protected $init;

    public function getPayloads()
    {
        return array(
            array(20, "if  ("),
            array(20, "if ("),
            array(20, "if("),
            array(20, "elseif  ("),
            array(20, "elseif ("),
            array(20, "elseif("),
            array(20, "for  ("),
            array(20, "for ("),
            array(20, "for("),
            array(20, "foreach  ("),
            array(20, "foreach ("),
            array(20, "foreach("),
            array(20, "for each  ("),
        );
    }

    public function setUp():void
    {
        $this->init = Init::init(IDS_CONFIG);
        $this->init->config['General']['tmp_path'] = IDS_TEMP_DIR;
        $this->init->config['Caching']['path'] = IDS_FILTER_CACHE_FILE;
        $this->init->config['General']['filter_type'] = IDS_FILTER_TYPE;
        $this->init->config['General']['filter_path'] = IDS_FILTER_SET;
    }

}
