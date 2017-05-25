<?php

namespace WA\Http\Controllers;

use Illuminate\Http\Request;
use WA\Repositories\Asset\AssetInterface;

/**
 * Class AssetsController.
 */
class AssetsController extends FilteredApiController
{
    /**
     * @var AssetInterface
     */
    protected $asset;

    /**
     * AssetsController constructor.
     *
     * @param AssetInterface $asset
     * @param Request $request
     */
    public function __construct(AssetInterface $asset, Request $request)
    {
        parent::__construct($asset, $request);
        $this->asset = $asset;
    }

}
