<?php


namespace Underwear\Import\Elements;

use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Underwear\Import\Elements\GetsValueFromCellInterface;

abstract class Element
{
    /**
     * @var string
     */
    protected $attributeName;

    /**
     * Element constructor.
     *
     * @param string $tableColumnName
     */
    public function __construct(string $tableColumnName)
    {
        $this->attributeName = $tableColumnName;
    }

    public static function make(...$arguments)
    {
        return new static(...$arguments);
    }

    /**
     * Get Attribute Name
     *
     * @return string
     */
    public function attributeName(): string
    {
        return $this->attributeName;
    }
}