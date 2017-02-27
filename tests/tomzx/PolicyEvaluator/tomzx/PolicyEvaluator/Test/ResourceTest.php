<?php

namespace tomzx\PolicyEvaluator\Test;

use tomzx\PolicyEvaluator\Resource;

class ResourceTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialize()
    {
        $resource = new Resource('urn:test:test');
        $this->assertNotNull($resource);
    }

    public function testInitializeWithWildcard()
    {
        $resource = new Resource('*');
        $this->assertNotNull($resource);
    }

    public function testInitializeWithArray()
    {
        $resource = new Resource(['urn:test:test', 'urn:test:Test']);
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
        $resource = new Resource('urn:test:test');
        $actual = $resource->matches('urn:test:test');
        $this->assertTrue($actual);
    }

    public function testMatchWithWildcard()
    {
        $resource = new Resource('urn:test:te*');
        $actual = $resource->matches('urn:test:test');
        $this->assertTrue($actual);
    }

    public function testMatchWithWildcardOnly()
    {
        $resource = new Resource('*');
        $actual = $resource->matches('urn:test:test');
        $this->assertTrue($actual);
    }

    public function testMatchWithWildcardAndNoMatch()
    {
        $resource = new Resource('urn:test:te*');
        $actual = $resource->matches('urn:test:fail');
        $this->assertFalse($actual);
    }

    // TODO(tom@tomrochette.com): Not sure how a wildcard query should work
    public function testMatchWithWildcardRequest()
    {
        $resource = new Resource('urn:test:test');
        $actual = $resource->matches('urn:test:*');
        $this->assertFalse($actual);
    }

    public function testMatchWithShorterRequestString()
    {
        $resource = new Resource('urn:test:test');
        $actual = $resource->matches('urn:test:tes');
        $this->assertFalse($actual);
    }

    public function testMatchWithLongerRequestString()
    {
        $resource = new Resource('urn:test:test');
        $actual = $resource->matches('urn:test:tests');
        $this->assertFalse($actual);
    }

    public function testMatchDoesNotMatchRegex()
    {
        $resource = new Resource('urn:test:te[st]+');
        $actual = $resource->matches('urn:test:test');
        $this->assertFalse($actual);
    }
}
