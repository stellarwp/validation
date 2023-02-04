<?php

namespace StellarWP\Validation\Conditions;

/**
 * @since 2.13.0
 */
class NestedCondition extends Condition
{

    /** @var string */
    const TYPE = 'nested';

    /** @var Condition[] */
    public $conditions = [];


    /**
     * @since 2.13.0
     *
     * @param Condition[] $conditions
     * @param string $boolean
     */
    public function __construct(array $conditions, string $boolean = 'and')
    {
        $this->conditions = $conditions;
        $this->boolean = $boolean;
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize(): array
    {
        return [
            'type' => static::TYPE,
            'conditions' => $this->conditions,
            'boolean' => $this->boolean,
        ];
    }

    public function passes(array $testValues): bool
    {
        return array_reduce(
            $this->conditions,
            static function ($carry, $condition) use ($testValues) {
                return $condition->boolean === 'and'
                    ? $carry && $condition->passes($testValues)
                    : $carry || $condition->passes($testValues);
            },
            true
        );
    }
}
