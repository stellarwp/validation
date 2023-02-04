<?php

declare(strict_types=1);

namespace StellarWP\Validation\Concerns;

use StellarWP\Validation\Conditions\Condition;
use StellarWP\Validation\Conditions\ConditionsArray;

trait HasConditions
{
    /**
     * @var ConditionsArray
     */
    protected $conditions;

    /**
     * @param Condition[] $conditions
     */
    public function __construct(Condition ...$conditions)
    {
        $this->conditions = new ConditionsArray(...$conditions);
    }
}
