<?php

namespace Underwear\Import\Elements;

use Faker\Generator;

class Faker extends Element implements GeneratesValueInterface
{
    /**
     * @var callable
     */
    protected $closure;

    /**
     * Closure constructor.
     *
     * @param string   $tableColumnName
     * @param callable $closure
     */
    public function __construct(string $tableColumnName, callable $closure)
    {
        $this->closure = $closure;
        parent::__construct($tableColumnName);
    }

    public function getValue()
    {
        $faker = app(Generator::class);
        return call_user_func($this->closure, $faker);
    }
}