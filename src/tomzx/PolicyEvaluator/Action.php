<?php

namespace tomzx\PolicyEvaluator;

class Action
{
    /**
     * @var array
     */
    private $actions;

    /**
     * @param array|string $actions
     */
    public function __construct($actions)
    {
        if ( ! is_array($actions)) {
            $actions = (array)$actions;
        }

        foreach ($actions as $action) {
            if ($action === '*') {
                continue;
            }

            if (strpos($action, ':') === false) {
                throw new \InvalidArgumentException('Invalid service prefix for action "' . $action . '".');
            }
        }

        $this->actions = $actions;
    }

    /**
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @param string $requestedAction
     * @return bool
     */
    public function matches($requestedAction)
    {
        // TODO(tom@tomrochette.com): Wildcard support
        foreach ($this->actions as $action) {
            $actionRegex = '/^'.str_replace('*', '.*', $action).'$/';
            if (preg_match($actionRegex, $requestedAction)) {
                return true;
            }
        }

        return false;
    }
}
