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
                    'Resource' => 'arn:aws:test',
                    'Effect' => 'Allow',
                ],
            ],
        ]);
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
                    'Resource' => 'arn:aws:test',
                    'Effect' => 'Allow',
                ],
            ],
        ]);

        $actual = $evaluator->canExecuteActionOnResource('service:test', 'arn:aws:test');
        $this->assertTrue($actual);
    }

    public function testCannotExecuteActionOnResourceWithActionNotPartOfStatements()
    {

        $evaluator = new Evaluator([
            'Statement' => [
                [
                    'Action' => 'service:test',
                    'Resource' => 'arn:aws:test',
                    'Effect' => 'Allow',
                ],
            ],
        ]);

        $actual = $evaluator->canExecuteActionOnResource('service:test1', 'arn:aws:test');
        $this->assertFalse($actual);
    }

    public function testCannotExecuteActionOnResourceWithResourceNotPartOfStatements()
    {

        $evaluator = new Evaluator([
            'Statement' => [
                [
                    'Action' => 'service:test',
                    'Resource' => 'arn:aws:test',
                    'Effect' => 'Allow',
                ],
            ],
        ]);

        $actual = $evaluator->canExecuteActionOnResource('service:test', 'arn:aws:test1');
        $this->assertFalse($actual);
    }

    public function testCannotExecuteActionOnResourceWithDenyStatementMatch()
    {

        $evaluator = new Evaluator([
            'Statement' => [
                [
                    'Action' => 'service:test',
                    'Resource' => 'arn:aws:test',
                    'Effect' => 'Deny',
                ],
            ],
        ]);

        $actual = $evaluator->canExecuteActionOnResource('service:test', 'arn:aws:test');
        $this->assertFalse($actual);
    }

    public function testCanExecuteActionOnResourceWithWildcard()
    {
        $evaluator = new Evaluator([
            'Statement' => [
                [
                    'Action' => 'service:test',
                    'Resource' => 'arn:aws:*',
                    'Effect' => 'Allow',
                ],
            ],
        ]);

        $actual = $evaluator->canExecuteActionOnResource('service:test', 'arn:aws:test');
        $this->assertTrue($actual);
    }
}
