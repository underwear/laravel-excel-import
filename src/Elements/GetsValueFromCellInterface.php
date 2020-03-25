<?php

namespace Underwear\Import\Elements;

use PhpOffice\PhpSpreadsheet\Cell\Cell;

interface GetsValueFromCellInterface
{
    public function getValue(Cell $cell);

    public function getColumnIndex();
}