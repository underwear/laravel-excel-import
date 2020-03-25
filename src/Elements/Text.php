<?php


namespace Underwear\Import\Elements;

use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Underwear\Import\Elements\GetsValueFromCellInterface;
use Underwear\Import\Exceptions\ImportException;

class Text extends Element implements GetsValueFromCellInterface
{
    /**
     * @var string
     */
    protected $columnIndex;

    /**
     * @var string
     */
    protected $attributeName;

    /**
     * @var array
     */
    protected $validationRules = [];

    /**
     * @var callable
     */
    protected $prepare;

    /**
     * Text constructor.
     *
     * @param string $tableColumnName
     * @param string $worksheetColumnIndex
     */
    public function __construct(string $tableColumnName, $worksheetColumnIndex)
    {
        $this->columnIndex = $worksheetColumnIndex;

        return parent::__construct($tableColumnName);
    }

    /**
     * Return column index
     */
    public function getColumnIndex()
    {
        return $this->columnIndex;
    }

    /**
     * Add validation rules
     *
     * @param array $rules
     *
     * @return $this
     */
    public function rules(array $rules)
    {
        $this->validationRules = array_merge($this->validationRules, $rules);

        return $this;
    }

    /**
     * Add closure for preparing value from cell
     *
     * @param callable $closure
     *
     * @return $this
     */
    public function prepare(callable $closure)
    {
        $this->prepare = $closure;

        return $this;
    }

    /**
     * Extract value from cell
     *
     * @param Cell $cell
     *
     * @return string|null
     * @throws ImportException
     */
    public function getValue(Cell $cell): ?string
    {
        $value = $cell->getValue();

        if (is_callable($this->prepare)) {
            $value = call_user_func($this->prepare, $value);
        }

        $validator = Validator::make(['value' => $value], ['value' => $this->validationRules]);
        if ($validator->fails()) {
            $cellCoordinates = $cell->getCoordinate();
            $message = $validator->getMessageBag()->first();
            throw new ImportException("Bad value in {$cellCoordinates} cell: {$message}");
        }

        return $value;
    }
}