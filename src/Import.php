<?php

namespace Underwear\Import;

use Underwear\Import\Elements\Element;

class Import
{
    /**
     * @var string
     */
    protected $table;

    /**
     * @var Element[]
     */
    protected $elements;

    /**
     * @var int
     */
    protected $startFromRow;

    /**
     * Import constructor.
     *
     * @param string    $table
     * @param Element[] $columns
     * @param int       $startFromRow
     */
    public function __construct(string $table, array $columns, ?int $startFromRow = 2)
    {
        $this->table = $table;
        $this->elements = $columns;
        $this->startFromRow = $startFromRow;
    }

    public static function make(...$arguments)
    {
        return new static(...$arguments);
    }

    /**
     * Get model name
     *
     * @return string
     */
    public function table(): string
    {
        return $this->table;
    }

    /**
     * @return int
     */
    public function startFromRow(): int
    {
        return $this->startFromRow;
    }

    /**
     * Get elements
     *
     * @return Element[]
     */
    public function elements(): array
    {
        return $this->elements;
    }

    /**
     * Parse xlsx file and insert new records to database
     *
     * @param string $filepath
     *
     * @return bool
     * @throws Exceptions\ImportException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function parseFile(string $filepath)
    {
        return (new Importer($this))->parse($filepath);
    }
}