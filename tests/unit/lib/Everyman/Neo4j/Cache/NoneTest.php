<?php
namespace Everyman\Neo4j\Cache;

class NoneTest extends \PHPUnit_Framework_TestCase
{
	protected $cache = null;

	public function setUp()
	{
		$this->cache = new None();
	}

	public function testDelete_ReturnsTrue()
	{
		$this->assertTrue($this->cache->delete('somekey'));
	}

	public function testGet_ReturnsFalse()
	{
		$this->assertFalse($this->cache->get('somekey'));
	}

	public function testSet_ReturnsTrue()
	{
		$this->assertTrue($this->cache->set('somekey', 'somevalue', 12345));
	}
}
