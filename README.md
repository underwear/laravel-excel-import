# laravel-excel-import

Easily import xlsx file right into you database just with some code lines!
Validate your data with default laravel validator and search related values from other tables.

```php
use Underwear\Import\Import;
use Underwear\Import\Elements\Text;
        
$dbTable = 'categories';
$xlsxFilepath = '/some/path/to/file.xlsx';

Import::make($dbTable, [

    Text::make('title', 'A'),

    Text::make('code', 'B')
        ->rules(['filled', 'size:12'])
        ->prepare(function ($value) {
            return ucfirst($value);
        }),

])->parseFile($xlsxFilepath);
```

## Supported Columns Types
### Text
```php
use Underwear\Import\Elements\Text;

Text::make('targetTableColumnName', 'A');
```

You can add validation rules:
```php
use Underwear\Import\Elements\Text;

Text::make('target_table_colum_name', 'A')
        ->rules(['filled', 'email']);
```

Or even add your own closure to mutate value
```php
use Underwear\Import\Elements\Text;

Text::make('target_table_colum_name', 'A')
        ->prepare(function($value) {
                return ucfirst($value);
        });
```
### Slug
You can generate slug from text

```php
use Underwear\Import\Elements\Slug;

Slug::make('slug', 'B');
```
### Boolean
```php
use Underwear\Import\Elements\Boolean;

Boolean::make('is_published', 'D')
      ->trueValue('yeap');
```
### Autoincrement
Do not uses xls cells to get value. Just generates by it owns.
```php
use Underwear\Import\Elements\Autoincrement;

Autoincrement::make('order_column');
```
### BelongsTo
```php

use Underwear\Import\Elements\BelongsTo;

BelongsTo::make('category_id', 'C', 'categories' , 'title', 'id');

// 3th arg: related table in database;
// 4th arg: column for searching in related table;
// 5th arg: column for value returning from related table;
```

### Closure
Do not uses xls cells to get value. Just returns value
```php

use Underwear\Import\Elements\Closure;

Closure::make('some_table_field', function() {
        // code here whatever you want
        return "something";
});
```

### Faker
Use can also use `fzaninotto/Faker`
```php

use Underwear\Import\Elements\Faker;
use Faker\Generator;

Faker::make('some_table_field', function(Generator $faker) {
        return $faker->word;
});

```


## Installation
You can install the package via composer:

```
composer require underwear/laravel-excel-import
```

## Usage example

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

     
