<?php

namespace tomzx\PolicyEvaluator;

class Evaluator
{
    /**
     * @var array
     */
    private $policies;

    /**
     * @var Statement[]
     */
    private $statements;

    /**
     * @param array $policies
     */
    public function __construct(array $policies)
    {
        $this->policies = $policies;

        if ( ! isset($this->policies['Statement'])) {
            throw new \InvalidArgumentException('The policy must have at least one statement.');
        }

        $this->parseStatements($this->policies['Statement']);
    }

    /**
     * @return array
     */
    public function getStatements()
    {
        return $this->statements;
    }

    /**
     * @param string $action
     * @param string $resource
     * @return bool
     */
    public function canExecuteActionOnResource($action, $resource)
    {
        // TODO(tom@tomrochette.com): Validate action and resource are in valid format
        $statements = $this->matchStatement($action, $resource);
        return ! empty($statements);
    }

    /**
     * @param string $document
     * @return \tomzx\PolicyEvaluator\Evaluator
     */
    public static function fromJsonString($document)
    {
        return new self(json_decode($document, true));
    }

    /**
     * @param array $statements
     */
    private function parseStatements(array $statements)
    {
        if (empty($statements)) {
            throw new \InvalidArgumentException('The policy must have at least one statement.');
        }

        foreach ($statements as $statement) {
            $statement = new Statement($statement);
            $this->statements[] = $statement;
        }
    }

    /**
     * @param string $action
     * @param string $resource
     * @return array
     */
    private function matchStatement($action, $resource)
    {
        $statements = [];
        foreach ($this->statements as $statement) {
            if ( ! $statement->matchesAction($action)) {
                continue;
            }

            if ( ! $statement->matchesResource($resource)) {
                continue;
            }

            if ( ! $statement->getEffect()->isAllow()) {
                continue;
            }

            $statements[] = $statement;
        }
        return $statements;
    }
}
