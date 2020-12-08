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

use IDS\Version;
use PHPUnit\Framework\TestCase;

class VersionTest extends TestCase
{
    public function testVersionConstantIsDefined()
    {
        $this->assertSame('master', Version::VERSION);
    }
}
