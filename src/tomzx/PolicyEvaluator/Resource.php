<?php

namespace tomzx\PolicyEvaluator;

class Resource
{
    public static $prefix = 'urn';

    /**
     * @var array
     */
    private $resources;

    /**
     * @param array|string $resources
     */
    public function __construct($resources)
    {
        if ( ! is_array($resources)) {
            $resources = (array)$resources;
        }

        foreach ($resources as $resource) {
            if ($resource === '*') {
                continue;
            }

            if (strpos($resource, self::$prefix . ':') !== 0) {
                throw new \InvalidArgumentException('Invalid resource "' . $resource . '".');
            }
        }

        $this->resources = $resources;
    }

    /**
     * @return array
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * @return array
     */
    public function getResourcesCompiled(array $variables = [])
    {
        $variableStrings = $this->mapVariables($variables);
        $resources = [];

        foreach ($this->resources as $resource) {
            $resources[] = $this->replaceVariables($resource, $variableStrings);
        }

        return $resources;
    }

    /**
     * @param string $requestedResource
     * @return bool
     */
    public function matches($requestedResource, array $variables = [])
    {
        $resources = $this->getResourcesCompiled($variables);

        foreach ($resources as $resource) {
            $preparedResource = $this->replaceWildcard($resource);
            $resourceRegex    = '/^'.$preparedResource.'$/';

            if (preg_match($resourceRegex, $requestedResource)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $variables
     * @return array
     */
    private function mapVariables(array $variables)
    {
        $mappedVariables = [];
        foreach ($variables as $key => $value) {
            $mappedVariables['${'.$key.'}'] = $value;
        }
        return $mappedVariables;
    }

    /**
     * @param string $resource
     * @param array $variables
     * @return string
     */
    private function replaceVariables($resource, array $variables)
    {
        return strtr($resource, $variables);
    }

    /**
     * @param string $resource
     * @return string
     */
    private function replaceWildcard($resource)
    {
        return str_replace('\*', '.*', preg_quote($resource, '/'));
    }
}
