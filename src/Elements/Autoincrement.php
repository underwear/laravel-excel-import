<?php

namespace Underwear\Import\Elements;


use Underwear\Import\Elements\GeneratesValueInterface;

class Autoincrement extends Element implements GeneratesValueInterface
{
    protected $counter = 0;

    public function getValue()
    {
        return ++$this->counter;
    }
}