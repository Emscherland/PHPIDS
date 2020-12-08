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
use IDS\Caching\CacheFactory;
use IDS\Caching\FileCache;
use IDS\Caching\SessionCache;
use PHPUnit\Framework\TestCase;

class CachingTest extends TestCase
{
    protected Init $init;

    public function setUp(): void
    {
        $this->init = Init::init(IDS_CONFIG);
    }

    public function testCachingNone()
    {
        $this->init->config['Caching']['caching'] = 'none';
        $this->assertNull(CacheFactory::factory($this->init, 'storage'));
    }

    public function testCachingSession()
    {
        $this->init->config['Caching']['caching'] = 'session';
        $this->assertTrue(CacheFactory::factory($this->init, 'storage') instanceof SessionCache);
    }

    public function testCachingSessionSetCache()
    {
        $this->init->config['Caching']['caching'] = 'session';

        $cache = CacheFactory::factory($this->init, 'storage');
        $cache = $cache->setCache(array(1, 2, 3, 4));
        $this->assertTrue($cache instanceof SessionCache);
    }

    public function testCachingSessionGetCache()
    {
        $this->init->config['Caching']['caching'] = 'session';

        $cache = CacheFactory::factory($this->init, 'storage');
        $cache = $cache->setCache(array(1, 2, 3, 4));
        $this->assertEquals($cache->getCache(), array(1, 2, 3, 4));
    }

    public function testCachingSessionGetCacheDestroyed()
    {
        $this->init->config['Caching']['caching'] = 'session';

        $cache = CacheFactory::factory($this->init, 'storage');
        $cache = $cache->setCache(array(1, 2, 3, 4));
        $_SESSION['PHPIDS']['storage'] = null;
        $this->assertFalse($cache->getCache());
    }

    public function tearDown(): void
    {
        @unlink(IDS_FILTER_CACHE_FILE);
    }
}
