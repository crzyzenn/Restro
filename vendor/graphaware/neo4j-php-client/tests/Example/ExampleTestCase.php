<?php

/*
 * This file is part of the GraphAware Neo4j Client package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GraphAware\Neo4j\Client\Tests\Example;

use GraphAware\Neo4j\Client\ClientBuilder;

abstract class ExampleTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \GraphAware\Neo4j\Client\Client
     */
    protected $client;

    public function setUp()
    {
        $boltUrl = 'bolt://localhost';
        if (isset($_ENV['NEO4J_USER'])) {
            $boltUrl = sprintf(
                'bolt://%s:%s@%s',
                getenv('NEO4J_USER'),
                getenv('NEO4J_PASSWORD'),
                getenv('NEO4J_HOST')
            );
        }

        $this->client = ClientBuilder::create()
            ->addConnection('default', $boltUrl)
            ->build();
    }

    public function emptyDB()
    {
        $this->client->run('MATCH (n) DETACH DELETE n');
    }
}
