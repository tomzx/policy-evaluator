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
     * @param string $requestedResource
     * @return bool
     */
    public function matches($requestedResource, array $variables = [])
    {
        $variableStrings = $this->mapVariables($variables);
        foreach ($this->resources as $resource) {
            $preparedResource = $this->replaceWildcard($this->replaceVariables($resource, $variableStrings));
            $resourceRegex = '/^'.$preparedResource.'$/';
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
