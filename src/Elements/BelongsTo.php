<?php


namespace Underwear\Import\Elements;

use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Cell\Cell;

class BelongsTo extends Text
{
    /**
     * @var string
     */
    protected $ownerTable;

    /**
     * @var string
     */
    protected $ownerTableSearchColumn;

    /**
     *
     * @var string
     */
    protected $ownerTableKeyColumn;

    /**
     * BelongsTo constructor.
     *
     * @param string $tableColumnName
     * @param string $worksheetColumnIndex
     * @param string $ownerTable
     * @param string $ownerTableSearchColumn
     * @param string $ownerTableKeyColumn
     */
    public function __construct(
        string $tableColumnName,
        string $worksheetColumnIndex,
        string $ownerTable,
        string $ownerTableSearchColumn,
        string $ownerTableKeyColumn
    ) {
        $this->ownerTable = $ownerTable;
        $this->ownerTableSearchColumn = $ownerTableSearchColumn;
        $this->ownerTableKeyColumn = $ownerTableKeyColumn;

        parent::__construct($tableColumnName, $worksheetColumnIndex);
    }

    /**
     * @return $this
     */
    public function failIfNotFound()
    {
        $this->rules(["exists:{$this->ownerTable},{$this->ownerTableSearchColumn}"]);

        return $this;
    }

    /**
     * Return key column value from founded record from related table
     *
     * @param Cell $cell
     *
     * @return string|null
     * @throws \Underwear\Import\Exceptions\ImportException
     */
    public function getValue(Cell $cell): ?string
    {
        $value = parent::getValue($cell);

        $result = DB::table($this->ownerTable)
            ->select([$this->ownerTableKeyColumn, $this->ownerTableSearchColumn])
            ->where($this->ownerTableSearchColumn, $value)
            ->first();

        if (!$result) {
            return null;
        }

        return $result->{$this->ownerTableKeyColumn};
    }
}