# Uploadable

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

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
    
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
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

[ico-version]: https://img.shields.io/packagist/v/UniSharp/laravel-uploadable.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/UniSharp/laravel-uploadable/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/UniSharp/laravel-uploadable.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/UniSharp/laravel-uploadable.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/UniSharp/laravel-uploadable.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/unisharp/categorizable
[link-travis]: https://travis-ci.org/UniSharp/laravel-uploadable
[link-scrutinizer]: https://scrutinizer-ci.com/g/UniSharp/laravel-uploadable/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/UniSharp/laravel-uploadable
[link-downloads]: https://packagist.org/packages/UniSharp/laravel-uploadable
[link-author]: https://github.com/Nehemis1615
[link-contributors]: ../../contributors
