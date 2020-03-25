<?php

namespace Underwear\Import\Elements;

use Illuminate\Contracts\Support\MessageBag;
use PhpOffice\PhpSpreadsheet\Cell\Cell;

interface GeneratesValueInterface
{
    public function getValue();
}