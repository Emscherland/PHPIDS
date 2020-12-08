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

use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;

/**
 * PHPIDS event object
 *
 * This class represents a certain event that occured while applying the filters
 * to the supplied data. It aggregates a bunch of IDS_Filter implementations and
 * is a assembled in IDS_Report.
 *
 * Note that this class implements both Countable and IteratorAggregate
 *
 * @category  Security
 * @package   PHPIDS
 * @author    Christian Matthies <ch0012@gmail.com>
 * @author    Mario Heiderich <mario.heiderich@gmail.com>
 * @author    Lars Strojny <lars@strojny.net>
 * @copyright 2007-2009 The PHPIDS Group
 * @license   http://www.gnu.org/licenses/lgpl.html LGPL
 * @link      http://php-ids.org/
 */
class Event implements Countable, IteratorAggregate
{

    /**
     * List of filter objects
     * Filter objects in this array are those that matched the events value
     */
    protected array $filters = array();

    /**
     * Calculated impact
     *
     * Total impact of the event
     */
    protected int $impact = 0;

    /**
     * Affected tags
     */
    protected array $tags = array();

    /**
     * Constructor
     *
     * Fills event properties
     *
     * @param string $name the event name
     * @param mixed $value the event value
     * @param Filter[]|array $filters the corresponding filters
     */
    public function __construct(protected string $name, protected mixed $value, array $filters)
    {

        if (!is_scalar($value)) {
            throw new InvalidArgumentException(
                'Expected $value to be a scalar,' . gettype($value) . ' given'
            );
        }

        foreach ($filters as $filter) {
            if (!$filter instanceof Filter) {
                throw new InvalidArgumentException(
                    'Filter must be derived from IDS_Filter'
                );
            }

            $this->filters[] = $filter;
        }

        return $this;
    }

    /**
     * Returns event name
     *
     * The name of the event usually is the key of the variable that was
     * considered to be malicious
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns event value
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * Returns calculated impact
     */
    public function getImpact(): int
    {
        if (!$this->impact) {
            $this->impact = 0;
            foreach ($this->filters as $filter) {
                $this->impact += $filter->getImpact();
            }
        }

        return $this->impact;
    }

    /**
     * Returns affected tags
     *
     * @return array
     */
    public function getTags(): array
    {
        foreach ($this->getFilters() as $filter) {
            $this->tags = array_merge($this->tags, $filter->getTags());
        }

        $this->tags = array_values(array_unique($this->tags));

        return $this->tags;
    }

    /**
     * Returns list of filter objects
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * Returns number of filters
     *
     * To implement interface Countable this returns the number of filters
     * appended.
     */
    public function count(): int
    {
        return count($this->getFilters());
    }

    /**
     * IteratorAggregate iterator getter
     * Returns an iterator to iterate over the appended filters.
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->getFilters());
    }
}
