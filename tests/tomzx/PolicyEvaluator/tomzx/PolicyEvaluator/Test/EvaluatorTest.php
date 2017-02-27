<?php

namespace tomzx\PolicyEvaluator\Test;

use tomzx\PolicyEvaluator\Evaluator;

class EvaluatorTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialize()
    {
        $evaluator = new Evaluator([
            'Statement' => [
                [
                    'Action' => 'service:test',
                    'Resource' => 'urn:test:test',
                    'Effect' => 'Allow',
                ],
            ],
        ]);
        $this->assertNotNull($evaluator);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The policy must have at least one statement.
     */
    public function testInitializeEmptyPolicyShouldThrowAnException()
    {
        $evaluator = new Evaluator([]);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The policy must have at least one statement.
     */
    public function testInitializeWithNoStatementShouldThrowAnException()
    {
        $evaluator = new Evaluator([
            'Statement' => [],
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Missing required field Action.
     */
    public function testInitializeWithEmptyStatementShouldThrowAnException()
    {
        $evaluator = new Evaluator([
            'Statement' => [
                [],
            ],
        ]);
    }

    public function testCanExecuteActionOnResource()
    {
        $evaluator = new Evaluator([
            'Statement' => [
                [
                    'Action' => 'service:test',
                    'Resource' => 'urn:test:test',
                    'Effect' => 'Allow',
                ],
            ],
        ]);

        $actual = $evaluator->canExecuteActionOnResource('service:test', 'urn:test:test');
        $this->assertTrue($actual);
    }

    public function testCannotExecuteActionOnResourceWithActionNotPartOfStatements()
    {

        $evaluator = new Evaluator([
            'Statement' => [
                [
                    'Action' => 'service:test',
                    'Resource' => 'urn:test:test',
                    'Effect' => 'Allow',
                ],
            ],
        ]);

        $actual = $evaluator->canExecuteActionOnResource('service:test1', 'urn:test:test');
        $this->assertFalse($actual);
    }

    public function testCannotExecuteActionOnResourceWithResourceNotPartOfStatements()
    {

        $evaluator = new Evaluator([
            'Statement' => [
                [
                    'Action' => 'service:test',
                    'Resource' => 'urn:test:test',
                    'Effect' => 'Allow',
                ],
            ],
        ]);

        $actual = $evaluator->canExecuteActionOnResource('service:test', 'urn:test:test1');
        $this->assertFalse($actual);
    }

    public function testCannotExecuteActionOnResourceWithDenyStatementMatch()
    {

        $evaluator = new Evaluator([
            'Statement' => [
                [
                    'Action' => 'service:test',
                    'Resource' => 'urn:test:test',
                    'Effect' => 'Deny',
                ],
            ],
        ]);

        $actual = $evaluator->canExecuteActionOnResource('service:test', 'urn:test:test');
        $this->assertFalse($actual);
    }

    public function testCanExecuteActionOnResourceWithWildcard()
    {
        $evaluator = new Evaluator([
            'Statement' => [
                [
                    'Action' => 'service:test',
                    'Resource' => 'urn:test:*',
                    'Effect' => 'Allow',
                ],
            ],
        ]);

        $actual = $evaluator->canExecuteActionOnResource('service:test', 'urn:test:test');
        $this->assertTrue($actual);
    }

    public function testCanExecuteOnResourceWithExplicitDeny()
    {
        $evaluator = new Evaluator([
            'Statement' => [
                [
                    'Action' => 'service:test',
                    'Resource' => 'urn:test:test',
                    'Effect' => 'Deny',
                ],
                [
                    'Action' => 'service:test',
                    'Resource' => 'urn:test:test',
                    'Effect' => 'Allow',
                ],
            ],
        ]);

        $actual = $evaluator->canExecuteActionOnResource('service:test', 'urn:test:test');
        $this->assertFalse($actual);
    }
}
