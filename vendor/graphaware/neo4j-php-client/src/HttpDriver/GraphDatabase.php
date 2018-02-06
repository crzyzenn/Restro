<?php

/*
 * This file is part of the GraphAware Neo4j Client package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GraphAware\Neo4j\Client\HttpDriver;

use GraphAware\Common\Connection\BaseConfiguration;
use GraphAware\Common\Driver\ConfigInterface;
use GraphAware\Common\GraphDatabaseInterface;

class GraphDatabase implements GraphDatabaseInterface
{
    /**
     * @param string                 $uri
     * @param BaseConfiguration|null $config
     *
     * @return Driver
     */
    public static function driver($uri, ConfigInterface $config = null)
    {
        return new Driver($uri, $config);
    }
}
