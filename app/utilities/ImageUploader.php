<?php

namespace App\utilities;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImageUploader
{
    public static function upload($image, $path, $driverType = 'public_storage')
    {

        Storage::disk($driverType)->put($path, File::get($image));
    }

    public static function uploadMany(array $images, $path, $driverType = 'public_storage')
    {

        $imagesPath = [];
        foreach ($images as $key => $image) {

            $fullPath = $path . $key . $image->getClientOriginalName();

            self::upload($image, $fullPath, $driverType);

            $imagesPath += [$key => $fullPath];
        }
        return $imagesPath;
    }
}
