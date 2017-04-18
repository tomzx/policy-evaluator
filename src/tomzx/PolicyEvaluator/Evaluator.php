<?php

namespace tomzx\PolicyEvaluator;

class Evaluator
{
    /**
     * @var array
     */
    private $policies;

    /**
     * @var array
     */
    private $variables;

    /**
     * @var Statement[]
     */
    private $statements;

    /**
     * @param array $policies
     */
    public function __construct(array $policies, array $variables = [])
    {
        $this->policies = $policies;
        $this->variables = $variables;

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
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * @param string $action
     * @param string $resource
     * @param array $variables
     * @return bool
     */
    public function canExecuteActionOnResource($action, $resource, array $variables = [])
    {
        // TODO(tom@tomrochette.com): Validate action and resource are in valid format
        $statements = $this->matchStatement($action, $resource, $variables);

        foreach ($statements as $statement) {
            // If we found a matching statement with an explicit deny, deny right away
            if ($statement->getEffect()->isDeny()) {
                return false;
            }
        }

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
            $statement = $this->makeStatement($statement);
            $this->statements[] = $statement;
        }
    }

    /**
     * @param array $statement
     * @return \tomzx\PolicyEvaluator\Statement
     */
    protected function makeStatement(array $statement)
    {
        return new Statement($statement);
    }

    /**
     * @param string $action
     * @param string $resource
     * @param array $variables
     * @return array
     */
    public function matchStatement($action, $resource, array $variables = [])
    {
        $variables += $this->variables;
        $statements = [];
        foreach ($this->statements as $statement) {
            if ( ! $statement->matchesAction($action)) {
                continue;
            }

            if ( ! $statement->matchesResource($resource, $variables)) {
                continue;
            }

            $statements[] = $statement;
        }
        return $statements;
    }
}
