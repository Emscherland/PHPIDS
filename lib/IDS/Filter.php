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

namespace IDS;

use InvalidArgumentException;

/**
 * PHPIDS Filter object
 *
 * Each object of this class serves as a container for a specific filter. The
 * object provides methods to get information about this particular filter and
 * also to match an arbitrary string against it.
 *
 * @category  Security
 * @package   PHPIDS
 * @author    Christian Matthies <ch0012@gmail.com>
 * @author    Mario Heiderich <mario.heiderich@gmail.com>
 * @author    Lars Strojny <lars@strojny.net>
 * @copyright 2007-2009 The PHPIDS Group
 * @license   http://www.gnu.org/licenses/lgpl.html LGPL
 * @link      http://php-ids.org/
 * @since     Version 0.4
 */
class Filter
{
    /**
     * Constructor
     *
     * @param integer $id filter id
     * @param string $rule filter rule
     * @param string $description filter description
     * @param string[]|array $tags list of tags
     * @param integer $impact filter impact level
     */
    public function __construct(protected int $id, protected string $rule, protected string $description, protected array $tags, protected int $impact)
    {
    }

    /**
     * Matches a string against current filter
     *
     * Matches given string against the filter rule the specific object of this
     * class represents
     *
     * @param string $input
     * @return bool
     */
    public function match(string $input): bool
    {
        if (!is_string($input)) {
            throw new InvalidArgumentException(
                'Invalid argument. Expected a string, received ' . gettype($input)
            );
        }

        return (bool)preg_match('/' . $this->getRule() . '/ms', strtolower($input));
    }

    /**
     * Returns filter description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Return list of affected tags
     *
     * Each filter rule is concerned with a certain kind of attack vectors.
     * This method returns those affected kinds.
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * Returns filter rule
     */
    public function getRule(): string
    {
        return $this->rule;
    }

    /**
     * Get filter impact level
     */
    public function getImpact(): int
    {
        return $this->impact;
    }

    /**
     * Get filter ID
     */
    public function getId(): int
    {
        return $this->id;
    }
}
