<?php


namespace Underwear\Import;


use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;
use Underwear\Import\Elements\GeneratesValueInterface;
use Underwear\Import\Elements\GetsValueFromCellInterface;
use Underwear\Import\Exceptions\ImportException;

class Importer
{
    /**
     * @var Import
     */
    protected $import;

    /**
     * Importer constructor.
     *
     * @param Import $import
     */
    public function __construct(Import $import)
    {
        $this->import = $import;
    }

    /**
     * @param $filepath
     *
     * @return bool
     *
     * @throws ImportException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function parse($filepath)
    {
        $worksheet = $this->load($filepath);

        $startFromRow = $this->import->startFromRow();

        $items = [];
        foreach ($worksheet->getRowIterator($startFromRow) as $row) {
            $items[] = $this->parseRow($row);
        }

        return $this->insertToDB($items);
    }

    /**
     * @param $filepath
     *
     * @return \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet
     * @throws ImportException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function load($filepath)
    {
        if (!file_exists($filepath)) {
            throw new ImportException("File $filepath is not existed!");
        }

        $reader = new XlsxReader();

        try {
            $spreadsheet = $reader->load($filepath);
        } catch (Exception $readerException) {
            throw new ImportException("Unable to load xlsx: " . $readerException->getMessage(),
                $readerException->getCode(), $readerException);
        }

        $worksheet = $spreadsheet->getActiveSheet();

        return $worksheet;
    }

    /**
     * @param Row $row
     *
     * @return array
     * @throws ImportException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function parseRow(Row $row): array
    {
        $data = [];
        foreach ($this->import->elements() as $element) {

            if ($element instanceof GeneratesValueInterface) {
                $value = $element->getValue();
            } elseif ($element instanceof GetsValueFromCellInterface) {
                $cellCoordinates = $element->getColumnIndex() . $row->getRowIndex();
                $cell = $row->getWorksheet()->getCell($cellCoordinates);
                $value = $element->getValue($cell);
            } else {
                throw new ImportException('Bad element ' . get_class($element));
            }

            $data[$element->attributeName()] = $value;
        }

        return $data;
    }

    /**
     * @param array $items
     *
     * @return bool
     */
    private function insertToDB(array $items)
    {
        return DB::table($this->import->table())
            ->insert($items);
    }
}