<?php

namespace GraphAware\Bolt\Tests\Integration\Packing;

use GraphAware\Bolt\Tests\IntegrationTestCase;

/**
 * Class PackingMapsIntegrationTest
 * @package GraphAware\Bolt\Tests\Integration\Packing
 *
 * @group integration
 * @group packing
 * @group map
 */
class PackingMapsIntegrationTest extends IntegrationTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->emptyDB();
    }

    public function testMapTiny()
    {
        $this->doRangeTest(1, 15);
    }

    public function testMap8()
    {
        $this->doRangeTest(16, 18);
        $this->doRangeTest(253, 255);
    }

    public function testMap16()
    {
        $this->doRangeTest(1024, 1026);
    }

    /**
     * @group fail
     */
    public function testMap16High()
    {
        $this->doRangeTest(65533, 65535);
    }

    private function doRangeTest($min, $max)
    {
        $query = 'CREATE (n:MapTest) SET n += {props} RETURN n';
        $session = $this->getSession();

        for ($i = $min; $i < $max; ++$i) {
            $parameters = [];
            foreach (range(1, $i) as $x) {
                $parameters['prop' . $x] = $i;
            }
            $result = $session->run($query, ['props' => $parameters]);
            $node = $result->firstRecord()->nodeValue('n');
            $this->assertEquals($i, count($node->values()));
        }
    }
}
