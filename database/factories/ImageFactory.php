<?php


$factory->define(WA\DataStore\Image\Image::class, function ($faker) {
    $originalName = ['IMAGE1', 'IMAGE2', 'IMAGE3', 'IMAGE4', 'IMAGE5', 'IMAGE6'];
    $filename = ['PHPASDFG', 'PHPERTRT', 'PHPSDSFG', 'PHPITURR', 'PHPSIRION', 'PHPDEVEL'];
    $size = [20000, 30000, 40000, 50000, 60000];

    $fileN = $filename[array_rand($filename)];

    return [
        'originalName' => $originalName[array_rand($originalName)],
        'filename' => $fileN,
        'mimeType' => 'mime/png',
        'extension' => 'png',
        'size' => $size[array_rand($size)],
        'url' => 'storage/files'.$fileN.'.png',
    ];
});
