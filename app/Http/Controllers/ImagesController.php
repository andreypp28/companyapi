<?php

namespace WA\Http\Controllers;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use WA\DataStore\Image\Image;
use WA\DataStore\Image\ImageTransformer;
use WA\Repositories\Image\ImageInterface;

/**
 * Image resource.
 *
 * @Resource("Image", uri="/image")
 */
class ImagesController extends FilteredApiController
{
    /**
     * @var ImageInterface
     */
    protected $image;

    /**
     * ImagesController constructor.
     *
     * @param ImageInterface $image
     * @param Request $request
     */
    public function __construct(ImageInterface $image, Request $request)
    {
        parent::__construct($image, $request);
        $this->image = $image;
    }

    /**
     * Show a single Image.
     *
     * Get a payload of a single Image
     *
     * @Get("/{id}")
     */
    public function show($id, Request $request)
    {
        $criteria = $this->getRequestCriteria();
        $this->image->setCriteria($criteria);
        $image = Image::find($id);

        if ($image == null) {
            $error['errors']['get'] = Lang::get('messages.NotExistClass', ['class' => 'Image']);
            return response()->json($error)->setStatusCode($this->status_codes['notexists']);
        }

        $path = $image->filename . '.' . $image->extension;

        $value = Storage::get($path);

        return response($value, 200)->header('Content-Type', $image->mimeType);
    }

    /**
     * Show a single Image Information.
     *
     * Get a payload of a single Image information
     *
     * @Get("/{id}")
     */
    public function info($id)
    {
        $image = Image::find($id);
        if ($image == null) {
            $error['errors']['get'] = Lang::get('messages.NotExistClass', ['class' => 'Image']);

            return response()->json($error)->setStatusCode($this->status_codes['notexists']);
        }

        return $this->response()->item($image, new ImageTransformer(),
            ['key' => 'images'])->setStatusCode($this->status_codes['created']);
    }

    /**
     * Create a new Image.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $file = $request->file('filename');

            $imageFile['originalName'] = $file->getClientOriginalName();
            $filename = $imageFile['filename'] = $file->getFilename();
            $imageFile['mimeType'] = $file->getClientMimeType();
            $extension = $imageFile['extension'] = $file->getClientOriginalExtension();
            $imageFile['size'] = $file->getClientSize();
            $imageFile['url'] = $filename . '.' . $extension;

            $filenameWithoutDot = explode(".", $filename)[0];

            $value = Storage::put($filenameWithoutDot . '.' . $extension, file_get_contents($file));

            if ($value) {
                $image = $this->image->create($imageFile);
            } else {
                Storage::delete($file);
            }
        } catch (\Exception $e) {
            $error['errors']['image'] = Lang::get('messages.NotOptionIncludeClass',
                ['class' => 'Image', 'option' => 'created', 'include' => '']);
            $error['errors']['Message'] = $e->getMessage();

            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        return $this->response()->item($image, new ImageTransformer(),
            ['key' => 'images'])->setStatusCode($this->status_codes['created']);
    }

    /**
     * Delete an Image.
     *
     * @param $id
     */
    public function delete($id)
    {
        $image = Image::find($id);
        if ($image != null) {
            $this->image->deleteById($id);
            Storage::delete($path = $image->filename . '.' . $image->extension);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotExistClass', ['class' => 'Image']);
            return response()->json($error)->setStatusCode($this->status_codes['notexists']);
        }

        $image = Image::find($id);
        if ($image == null) {
            return array("success" => true);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotDeletedClass', ['class' => 'Image']);
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
    }
}
