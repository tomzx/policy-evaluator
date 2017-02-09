<?php

namespace tomzx\PolicyEvaluator\Test;

use tomzx\PolicyEvaluator\Statement;

class StatementTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialize()
    {
        $statement = new Statement([
            'Action' => 'service:test',
            'Resource' => 'arn:aws:test',
            'Effect' => 'Allow',
        ]);

        $this->assertNotNull($statement);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Missing required field Action.
     */
    public function testInitializeWithNoAction()
    {
        $statement = new Statement([]);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Missing required field Resource.
     */
    public function testInitializeWithNoResource()
    {
        $statement = new Statement([
            'Action' => 'test',
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Missing required field Effect.
     */
    public function testInitializeWithNoEffect()
    {
        $statement = new Statement([
            'Action' => 'test',
            'Resource' => 'test',
        ]);
    }
}
