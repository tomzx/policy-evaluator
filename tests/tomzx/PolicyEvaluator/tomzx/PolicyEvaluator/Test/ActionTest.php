<?php

namespace tomzx\PolicyEvaluator\Test;

use tomzx\PolicyEvaluator\Action;

class ActionTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialize()
    {
        $action = new Action('service:action');
        $this->assertNotNull($action);
    }

    public function testInitializeWithArray()
    {
        $action = new Action(['service1:action1', 'service2:action2']);
        $this->assertNotNull($action);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid service prefix for action "service".
     */
    public function testInitializeWithInvalidActionShouldThrowAnException()
    {
        $action = new Action('service');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid service prefix for action "service2".
     */
    public function testInitializeWithInvalidActionInArrayShouldThrowAnException()
    {
        $action = new Action(['service1:action2', 'service2']);
    }

    public function testMatch()
    {
        $action = new Action('arn:aws:test');
        $actual = $action->matches('arn:aws:test');
        $this->assertTrue($actual);
    }

    public function testMatchWithWildcard()
    {
        $action = new Action('arn:aws:te*');
        $actual = $action->matches('arn:aws:test');
        $this->assertTrue($actual);
    }

    public function testMatchWithWildcardAndNoMatch()
    {
        $action = new Action('arn:aws:te*');
        $actual = $action->matches('arn:aws:fail');
        $this->assertFalse($actual);
    }

    // TODO(tom@tomrochette.com): Not sure how a wildcard query should work
    public function testMatchWithWildcardRequest()
    {
        $action = new Action('arn:aws:test');
        $actual = $action->matches('arn:aws:*');
        $this->assertFalse($actual);
    }

    public function testMatchWithShorterRequestString()
    {
        $action = new Action('arn:aws:test');
        $actual = $action->matches('arn:aws:tes');
        $this->assertFalse($actual);
    }

    public function testMatchWithLongerRequestString()
    {
        $action = new Action('arn:aws:test');
        $actual = $action->matches('arn:aws:tests');
        $this->assertFalse($actual);
    }
}
