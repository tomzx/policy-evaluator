<?php

namespace tomzx\PolicyEvaluator\Test;

use tomzx\PolicyEvaluator\Effect;

class EffectTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialize()
    {
        $effect = new Effect('Allow');
        $this->assertNotNull($effect);
        $effect = new Effect('Deny');
        $this->assertNotNull($effect);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInitializeWithInvalidEffectShouldThrowAnException()
    {
        $effect = new Effect('Invalid');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInitializeWithIncorrectCaseShouldThrowAnException()
    {
        $effect = new Effect('allow');
    }
}
