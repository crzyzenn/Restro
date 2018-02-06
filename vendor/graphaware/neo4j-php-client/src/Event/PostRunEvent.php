<?php

/*
 * This file is part of the GraphAware Neo4j Client package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GraphAware\Neo4j\Client\Event;

use GraphAware\Common\Result\ResultCollection;
use Symfony\Component\EventDispatcher\Event;

class PostRunEvent extends Event
{
    /**
     * @var ResultCollection
     */
    protected $results;

    /**
     * @param ResultCollection $results
     */
    public function __construct(ResultCollection $results)
    {
        $this->results = $results;
    }

    /**
     * @return ResultCollection
     */
    public function getResults()
    {
        return $this->results;
    }
}
