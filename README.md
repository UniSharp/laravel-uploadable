# Uploadable

A simple package to attach files to a eloquent model.

## Installation

```
composer require unisharp/laravel-uploadable dev-master
```

## Usages

Use trait in the model

```php

namespace App;

use Illuminate\Database\Eloquent\Model;
use UniSharp\Uploadable;

class Movie extends Model
{
    use CanUpload;
}
```

Upload and Delete the file

```php
// Upload a file

$product = new Product();

$product->upload(request()->file());

// Delete a file

$file = $product->files()->first();

$product->removeFiles($file->id);

```

Include route into api.php

```php

// POST /files/ & DELETE /files/{file}
UniSharp\Uploadable\UploaderManager::route();

// POST /files/
UniSharp\Uploadable\UploaderManager::route(['store']);

// POST /files/ with callback
UniSharp\Uploadable\UploaderManager::route(['store'], function () {
    ...
});

```
