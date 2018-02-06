<?php

namespace GraphAware\Bolt\Tests\Integration\Packing;

use GraphAware\Bolt\Tests\IntegrationTestCase;

/**
 * Class PackingTextIntegrationTest
 * @package GraphAware\Bolt\Tests\Integration\Packing
 *
 * @group integration
 * @group packing
 * @group text
 */
class PackingTextIntegrationTest extends IntegrationTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->emptyDB();

        $this->getSession()->run("CREATE INDEX ON :Text(value)");
    }

    /**
     * @group text-tiny
     */
    public function testTinyTextPacking()
    {
        $this->doRangeTest(1, 15);
    }

    /**
     * @group text8
     */
    public function testText8Packing()
    {
        $this->doRangeTest(16, 255);
    }

    /**
     * @group text16
     */
    public function testText16Packing()
    {
        $this->doRangeTest(256, 356);
        $this->doRangeTest(1024, 1026);
        $this->doRangeTest(2048, 2050);
        //$this->doRangeTest(16351, 16383);
        //$this->doRangeTest(65500, 65535);
    }

    /**
     * @group text32
     * @group fail
     * @group stringx
     */
    public function testText32Packing()
    {
        //$this->markTestSkipped("Neo4j3.0M02 has issues with 64 bits texts");
        //$this->doRangeTest(65537, 65537);
        //$this->doRangeTest(500000, 500000);
    }

    public function doRangeTest($min, $max)
    {
        $session = $this->getSession();

        $q = 'CREATE (n:Text) SET n.value = {value}';

        foreach (range($min, $max) as $i) {
            $txt = str_repeat('a', $i);
            $session->run($q, array('value' => $txt));
        }

        $q = 'MATCH (n:Text) WHERE n.value = {value} RETURN n.value as x';

        foreach (range($min, $max) as $i) {
            $txt = str_repeat('a', $i);
            $response = $session->run($q, ['value' => $txt]);
            $this->assertCount(1, $response->getRecords());
            $this->assertEquals($txt, $response->getRecord()->value('x'));
        }
    }
}
