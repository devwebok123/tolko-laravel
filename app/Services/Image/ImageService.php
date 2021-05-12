<?php
declare( strict_types = 1 );

namespace App\Services\Image;

use Intervention\Image\Image;
use Intervention\Image\ImageManager;

/**
 * Class ImageService
 * @package App\Services\Image
 */
class ImageService implements ImageServiceInterface
{
    /**
     * @var ImageManager
     */
    private $imageManager;

    /**
     * ImageService constructor.
     * @param ImageManager        $imageManager
     */
    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager     = $imageManager;
    }
}
