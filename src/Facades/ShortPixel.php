<?php

namespace Zaherg\ShortPixel\Facades;

use Illuminate\Support\Facades\Facade;

class ShortPixel extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'shortpixel';
    }
}
