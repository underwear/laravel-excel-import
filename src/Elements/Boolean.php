<?php


namespace Underwear\Import\Elements;


use PhpOffice\PhpSpreadsheet\Cell\Cell;

class Boolean extends Text
{
    /**
     * @var mixed
     */
    protected $trueValue;

    /**
     * Parse true if cell is equal to value
     *
     * @param $value
     */
    public function trueValue($value)
    {
        $this->trueValue = $value;
    }

    /**
     * Get boolean value from cell
     *
     * @param Cell $cell
     *
     * @return string|null
     * @throws \Underwear\Import\Exceptions\ImportException
     */
    public function getValue(Cell $cell): ?string
    {
        $value = parent::getValue($cell);
        return $value == $this->trueValue;
    }
}