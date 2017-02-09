<?php

namespace tomzx\PolicyEvaluator\Test;

use tomzx\PolicyEvaluator\Resource;

class ResourceTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialize()
    {
        $resource = new Resource('arn:aws:test');
        $this->assertNotNull($resource);
    }

    public function testInitializeWithArray()
    {
        $resource = new Resource(['arn:aws:test', 'arn:aws:Test']);
        $this->assertNotNull($resource);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid resource "test".
     */
    public function testInitializeWithInvalidResourceName()
    {
        $resource = new Resource('test');
    }

    public function testMatch()
    {
        $resource = new Resource('arn:aws:test');
        $actual = $resource->matches('arn:aws:test');
        $this->assertTrue($actual);
    }

    public function testMatchWithWildcard()
    {
        $resource = new Resource('arn:aws:te*');
        $actual = $resource->matches('arn:aws:test');
        $this->assertTrue($actual);
    }

    public function testMatchWithWildcardAndNoMatch()
    {
        $resource = new Resource('arn:aws:te*');
        $actual = $resource->matches('arn:aws:fail');
        $this->assertFalse($actual);
    }

    // TODO(tom@tomrochette.com): Not sure how a wildcard query should work
    public function testMatchWithWildcardRequest()
    {
        $resource = new Resource('arn:aws:test');
        $actual = $resource->matches('arn:aws:*');
        $this->assertFalse($actual);
    }

    public function testMatchWithShorterRequestString()
    {
        $resource = new Resource('arn:aws:test');
        $actual = $resource->matches('arn:aws:tes');
        $this->assertFalse($actual);
    }

    public function testMatchWithLongerRequestString()
    {
        $resource = new Resource('arn:aws:test');
        $actual = $resource->matches('arn:aws:tests');
        $this->assertFalse($actual);
    }
}
