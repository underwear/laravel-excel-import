<?php


namespace Underwear\Import\Elements;


use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Cell\Cell;

class Slug extends Text
{
    /**
     * @var string
     */
    protected $separator = '-';

    /**
     * @var string
     */
    protected $language = 'en';

    /**
     * Slug constructor.
     *
     * @param string $tableColumnName
     * @param string $worksheetColumnIndex
     */
    public function __construct(string $tableColumnName, string $worksheetColumnIndex)
    {
        parent::__construct($tableColumnName, $worksheetColumnIndex);
    }

    /**
     * Set separator for slug generating
     *
     * @param string $separator
     *
     * @return $this
     */
    public function separator(string $separator)
    {
        $this->separator = $separator;

        return $this;
    }

    /**
     * Set language for slug generating
     *
     * @param string $language
     *
     * @return $this
     */
    public function language(string $language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get value from cell and generate slug
     *
     * @param Cell $cell
     *
     * @return string|null
     * @throws \Underwear\Import\Exceptions\ImportException
     */
    public function getValue(Cell $cell): ?string
    {
        $value = parent::getValue($cell);

        return Str::slug($value, $this->separator, $this->language);
    }
}