# laravel-excel-import

Easily import xlsx file right into you database.

Data validation and searching related values from other tables.

## Getting Started

Import categories from xlsx file (xlsx file has only one column 'A' and database has name field 'title')

```php
use Underwear\Import\Import;
use Underwear\Import\Elements\Text;
        
$dbTable = 'categories';
$xlsxFilepath = '/some/path/to/file.xlsx';

Import::make($dbTable, [
    Text::make('title', 'A')
])->parseFile($xlsxFilepath);
```

## Supported Columns Types
### Text
### Slug
### Boolean
### Autoincrement
### BelongsTo

## More examples

Imagine we need to import some articles from xlsx file.

Xlsx file has some fields like:
*  "title" (A column)
*  "body" (B column)
*  "category name" (C column)
*  "is_published" (D column) with values "yeap" if true

DB table has columns:
*  (int) id
*  (string) title
*  (string) slug
*  (int fk) category_id
*  (bool) is_published
*  (int) order_column


```php
use Underwear\Import\Import;
use Underwear\Import\Elements\Text;
use Underwear\Import\Elements\BelongsTo;
use Underwear\Import\Elements\Boolean;
use Underwear\Import\Elements\Autoincrement;

Import::make('articles', [

    Text::make('title', 'A')
      ->rules(['filled']),

    Slug::make('slug', 'A'),

    BelongsTo::make('category_id', 'C', 'categories' , 'title', 'id'),

    Boolean::make('is_published', 'D')
      ->trueValue('yeap'),

    Autoincrement::make('order_column'),
  
])->parseFile('/path/to/articles.xlsx')

     
