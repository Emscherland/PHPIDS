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
use PHPUnit\Framework\TestCase;

class InitTest extends TestCase
{
    /**
     * @var Init
     */
    private $init = null;

    public function setUp():void
    {
        $this->init = Init::init(IDS_CONFIG);
    }

    public function testInit()
    {
        $this->assertTrue($this->init instanceof Init);
    }

    public function testInitConfig()
    {
        $keys = array('General', 'Caching');
        $this->assertEquals($keys, array_keys($this->init->config));
    }

    public function testInitClone()
    {
        $config2 = clone $this->init;
        $this->assertEquals($config2, $this->init);
    }

    public function testInitSetConfigOverwrite()
    {
        $this->init->setConfig(array('General' => array('filter_type' => 'json')), true);
        $this->assertEquals($this->init->config['General']['filter_type'], 'json');

        $this->init->setConfig(
            array('General' => array('exceptions' => array('foo'))),
            true
        );
        $this->assertSame(
            array('foo', 'GET.__utmc'),
            $this->init->config['General']['exceptions']
        );
    }

    public function testInitSetConfigNoOverwrite()
    {
        $this->init->setConfig(array('General' => array('filter_type' => 'xml')), true);
        $this->init->setConfig(array('General' => array('filter_type' => 'json')));
        $this->assertEquals($this->init->config['General']['filter_type'], 'xml');
    }

    public function testInitGetConfig()
    {
        $data = $this->init->getConfig();
        $this->assertEquals($this->init->config, $data);
    }

    public function testInstanciatingInitObjectWithoutPassingConfigFile()
    {
        $init = Init::init();
        $this->assertInstanceOf('IDS\\Init', $init);
    }
}
