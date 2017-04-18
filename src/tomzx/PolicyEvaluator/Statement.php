<?php

namespace tomzx\PolicyEvaluator;

class Statement
{
    /**
     * @var \tomzx\PolicyEvaluator\Action
     */
    private $action;
    /**
     * @var \tomzx\PolicyEvaluator\Resource
     */
    private $resource;

    /**
     * @var \tomzx\PolicyEvaluator\Effect
     */
    private $effect;

    /**
     * @param array $statement
     */
    public function __construct(array $statement)
    {
        if (!isset($statement['Action'])) {
            throw new \InvalidArgumentException('Missing required field Action.');
        }

        if (!isset($statement['Resource'])) {
            throw new \InvalidArgumentException('Missing required field Resource.');
        }

        if (!isset($statement['Effect'])) {
            throw new \InvalidArgumentException('Missing required field Effect.');
        }

        $this->action = new Action($statement['Action']);
        $this->resource = new Resource($statement['Resource']);
        $this->effect = new Effect($statement['Effect']);
    }

    /**
     * @return \tomzx\PolicyEvaluator\Action
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return \tomzx\PolicyEvaluator\Resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @return \tomzx\PolicyEvaluator\Effect
     */
    public function getEffect()
    {
        return $this->effect;
    }

    /**
     * @param string $resource
     * @param array $variables
     * @return bool
     */
    public function matchesResource($resource, array $variables = [])
    {
        return $this->resource->matches($resource, $variables);
    }

    /**
     * @param string $action
     * @return bool
     */
    public function matchesAction($action)
    {
        return $this->action->matches($action);
    }
}
