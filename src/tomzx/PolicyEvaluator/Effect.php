<?php

namespace tomzx\PolicyEvaluator;

class Effect
{
    /**
     * @var string
     */
    private $effect;

    /**
     * @param string $effect
     */
    public function __construct($effect)
    {
        if (!in_array($effect, ['Allow', 'Deny'])) {
            throw new \InvalidArgumentException('Effect must be either "Allow" or "Deny".');
        }

        $this->effect = $effect;
    }

    /**
     * @return string
     */
    public function getEffect()
    {
        return $this->effect;
    }

    public function isAllow()
    {
        return $this->effect === 'Allow';
    }

    public function isDeny()
    {
        return $this->effect === 'Deny';
    }
}
