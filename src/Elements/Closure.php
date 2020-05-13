<?php

namespace Underwear\Import\Elements;

class Closure extends Element implements GeneratesValueInterface
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
        return call_user_func($this->closure);
    }
}