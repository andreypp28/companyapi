<?php

/**
 * ImagesTableSeeder - Insert info into database.
 *
 * @author   Agustí Dosaiguas
 */
class ImagesTableSeeder extends BaseTableSeeder
{
    protected $table = 'images';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();

        $data = [

            [
                'originalName' => 'iphone6.png',
                'filename' => 'phpFmndT1',
                'mimeType' => 'image/png',
                'extension' => 'png',
                'size' => 235417,
                'url' => 'phpFmndT1.png',
            ],
            [
                'originalName' => 'iphone7.jpg',
                'filename' => 'phps1pu40',
                'mimeType' => 'image/jpeg',
                'extension' => 'jpg',
                'size' => 78965,
                'url' => 'phps1pu40.jpg',
            ],
            [
                'originalName' => 'samsunggalaxy.png',
                'filename' => 'phpQ3MhPB',
                'mimeType' => 'image/png',
                'extension' => 'png',
                'size' => 86709,
                'url' => 'phpQ3MhPB.png',
            ],
            [
                'originalName' => 'huaweip8lite.jpg',
                'filename' => 'phputBFCS',
                'mimeType' => 'image/jpeg',
                'extension' => 'jpg',
                'size' => 45663,
                'url' => 'phputBFCS.jpg',
            ],
            [
                'originalName' => 'verizon.jpg',
                'filename' => 'phpiicQ3z',
                'mimeType' => 'image/jpeg',
                'extension' => 'jpg',
                'size' => 5099,
                'url' => 'phpiicQ3z.jpg',
            ],
            [
                'originalName' => 'att.png',
                'filename' => 'phprwT8UU',
                'mimeType' => 'image/png',
                'extension' => 'png',
                'size' => 101996,
                'url' => 'phprwT8UU.png',
            ],
            [
                'originalName' => 'rogers.png',
                'filename' => 'phpjf9GTX',
                'mimeType' => 'image/png',
                'extension' => 'png',
                'size' => 88361,
                'url' => 'phpjf9GTX.png',
            ],
            [
                'originalName' => 'BellCanada.bmp',
                'filename' => 'phpjNUU8x',
                'mimeType' => 'image/bmp',
                'extension' => 'bmp',
                'size' => 160054,
                'url' => 'phpjNUU8x.bmp',
            ],
        ];

        if(!file_exists('./storage/app/public/')) {
            mkdir('./storage/app/public/');
        }

        copy('./database/seeds/imagesseeder/phpFmndT1.png', './storage/app/public/phpFmndT1.png');
        copy('./database/seeds/imagesseeder/phps1pu40.jpg', './storage/app/public/phps1pu40.jpg');
        copy('./database/seeds/imagesseeder/phpQ3MhPB.png', './storage/app/public/phpQ3MhPB.png');
        copy('./database/seeds/imagesseeder/phputBFCS.jpg', './storage/app/public/phputBFCS.jpg');
        copy('./database/seeds/imagesseeder/phpiicQ3z.jpg', './storage/app/public/phpiicQ3z.jpg');
        copy('./database/seeds/imagesseeder/phprwT8UU.png', './storage/app/public/phprwT8UU.png');
        copy('./database/seeds/imagesseeder/phpjf9GTX.png', './storage/app/public/phpjf9GTX.png');
        copy('./database/seeds/imagesseeder/phpjNUU8x.bmp', './storage/app/public/phpjNUU8x.bmp');

        $this->loadTable($data);
    }
}
