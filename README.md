# Non-Official Laravel Package for ShortPixel SDK and API 

[![Latest Stable Version](https://poser.pugx.org/zaherg/laravel-shortpixel/v/stable)](https://packagist.org/packages/zaherg/laravel-shortpixel)
[![Latest Unstable Version](https://poser.pugx.org/zaherg/laravel-shortpixel/v/unstable)](https://packagist.org/packages/zaherg/laravel-shortpixel)
[![License](https://poser.pugx.org/zaherg/laravel-shortpixel/license)](https://packagist.org/packages/zaherg/laravel-shortpixel)
[![composer.lock available](https://poser.pugx.org/zaherg/laravel-shortpixel/composerlock)](https://packagist.org/packages/zaherg/laravel-shortpixel)

Non-Official Laravel Package for the ShortPixel API. [ShortPixel](https://shortpixel.com) optimizes your images 
and improves website performance by reducing images size. Read more at [http://shortpixel.com](http://shortpixel.com).

## Documentation

[Go to the documentation for the PHP client](https://github.com/short-pixel-optimizer/shortpixel-php).

## Installation

Install the API client with Composer. Add this to your `composer.json`:

```json
{
  "require": {
    "zaherg/laravel-shortpixel": "*"
  }
}
```

Then install with:

```
composer install
```

Get your API Key from https://shortpixel.com/free-sign-up

## Usage

Add the API Key to your `.env` file

```bash
SHORTPIXEL_API_KEY=
```

Then you can use it as the following examples:

```php

// Compress with default settings
ShortPixel::fromUrls("https://your.site/img/unoptimized.png")->toFiles("/path/to/save/to");
// Compress with default settings but specifying a different file name
ShortPixel::fromUrls("https://your.site/img/unoptimized.png")->toFiles("/path/to/save/to", "optimized.png");

// Compress with default settings from a local file
ShortPixel::fromFile("/path/to/your/local/unoptimized.png")->toFiles("/path/to/save/to");
// Compress with default settings from several local files
ShortPixel::fromFiles(array("/path/to/your/local/unoptimized1.png", "/path/to/your/local/unoptimized2.png"))->toFiles("/path/to/save/to");

// Compress with a specific compression level: 0 - lossless, 1 - lossy (default), 2 - glossy
ShortPixel::fromFile("/path/to/your/local/unoptimized.png")->optimize(2)->toFiles("/path/to/save/to");

// Compress and resize - image is resized to have the either width equal to specified or height equal to specified 
//   but not LESS (with settings below, a 300x200 image will be resized to 150x100)
ShortPixel::fromUrls("https://your.site/img/unoptimized.png")->resize(100, 100)->toFiles("/path/to/save/to");
// Compress and resize - have the either width equal to specified or height equal to specified 
//   but not MORE (with settings below, a 300x200 image will be resized to 100x66)
ShortPixel::fromUrls("https://your.site/img/unoptimized.png")->resize(100, 100, true)->toFiles("/path/to/save/to");

// Keep the exif when compressing
ShortPixel::fromUrls("https://your.site/img/unoptimized.png")->keepExif()->toFiles("/path/to/save/to");

// Also generate and save a WebP version of the file - the WebP file will be saved next to the optimized file, with  same basename and .webp extension
ShortPixel::fromUrls("https://your.site/img/unoptimized.png")->generateWebP()->toFiles("/path/to/save/to");

//Compress from a folder - the status of the compressed images is saved in a text file named .shortpixel in each image folder
ShortPixel::ShortPixel::setOptions(["persist_type" => "text"]);
//Each call will optimize up to 10 images from the specified folder and mark in the .shortpixel file. 
//It automatically recurses a subfolder when finds it
//Save to the same folder, set wait time to 300 to allow enough time for the images to be processed
$ret = ShortPixel::fromFolder("/path/to/your/local/folder")->wait(300)->toFiles("/path/to/your/local/folder");
//Save to a different folder. CURRENT LIMITATION: When using the text persist type and saving to a different folder, you also need to specify the destination folder as the fourth parameter to fromFolder ( it indicates where the persistence files should be created)
$ret = ShortPixel::fromFolder("/path/to/your/local/folder", 0, array, "/different/path/to/save/to")->wait(300)->toFiles("/different/path/to/save/to");
//use a URL to map the folder to a WEB path in order for our servers to download themselves the images instead of receiving them via POST - faster and less exposed to connection timeouts
$ret = ShortPixel::fromWebFolder("/path/to/your/local/folder", "http://web.path/to/your/local/folder")->wait(300)->toFiles("/path/to/save/to");
//let ShortPixel back-up all your files, before overwriting them (third parameter of toFiles).
$ret = ShortPixel::fromFolder("/path/to/your/local/folder")->wait(300)->toFiles("/path/to/save/to", null, "/back-up/path");
//Recurse only <<N>> levels down into the subfolders of the folder ( N == 0 means do not recurse )
$ret = ShortPixel::fromFolder("/path/to/your/local/folder", 0,[], false, ShortPixel::CLIENT_MAX_BODY_SIZE, <<N>>)->wait(300)->toFiles("/path/to/save/to");

//A simple loop to optimize all images from a folder
$stop = false;
while(!$stop) {
    $ret = ShortPixel::fromFolder("/path/to/your/local/folder")->wait(300)->toFiles("/path/to/save/to");
    if(count($ret->->succeeded) + count($ret->failed) + count($ret->same) + count($ret->pending) == 0) {
        $stop = true;
    }
}

//Get account status and credits info:
$ret = ShortPixel::getClient()->apiStatus(config('shortpixel.key'));

```

## License

This software is licensed under the MIT License. [View the license](LICENSE).
