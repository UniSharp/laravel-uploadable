# Uploadable

A simple package to attach files to a eloquent model.

## Installation

```
composer require unisharp/laravel-uploadable dev-master
```

## Configuration

Set configuration in `config/uploadable.php`

```php
return [
    // Set image orientate enable or disable
    'use_image_orientate' => false,

    // Set thumbnail size
    'thumbs' => [
        's' => '96x96',
        'm' => '256x256',
        'l' => '480x480'
    ],

    // Set image handler
    'plugins' => [
        ImageHandler::class
    ]
];
```

## Usages

### Use trait in the model

```php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Unisharp\Uploadable\CanUpload;

class Product extends Model
{
    use CanUpload;
}
```

### Manually upload and Delete files

```php
// Upload a file
$product = new Product();
$product->upload(request()->file());

// Delete a file
$file = $product->files()->first();
$product->removeFiles($file->id);

// Delete files
$files = $product->files->pluck('id');
$product->removeFiles($files);
```

### Upload/Delete through APIs

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

### Customize image handler

Image Handler

```php
use Intervention\Image\Facades\Image;
use Illuminate\Filesystem\FilesystemAdapter;

class CustomImageHandler {
    public function handle(FilesystemAdapter $storage, $path)
    {
        $image = Image::make($storage->path($path));
    
        ...
    
        $image->save();
    }
}
```

Set Custom image handler in `config/uploadable.php`

```php
return [
    'plugins' => [
        CustomImageHandler::class
    ]
];
```
