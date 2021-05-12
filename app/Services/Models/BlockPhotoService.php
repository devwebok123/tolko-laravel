<?php


namespace App\Services\Models;

use App\Models\Block;
use App\Models\BlockPhoto;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class BlockPhotoService
{

    /**
     * Store image file
     *
     * @param BlockPhoto $photo
     * @param UploadedFile $file
     *
     * @return void
     */
    public function imageStore(BlockPhoto $photo, UploadedFile $file)
    {
        // Get image binary
        $content = file_get_contents($file->getRealPath());
        // Save image origin
        $scope = null;
        if ($photo->status === BlockPhoto::STATUS_DRAFT) {
            $scope = 'public';
        }
        Storage::disk('s3')->put($photo->getPhotoName(), $content, $scope);

        // Resize and Save public image
        if ($photo->status !== BlockPhoto::STATUS_DRAFT) {
            $this->resize(
                $content,
                $photo->getPhotoName(true),
                2000,
                1500,
                $photo->tag_id === BlockPhoto::TAG_ID_PLAN
            );
        }
    }

    /**
     * @param string $source
     * @param string $filename
     * @param int $width
     * @param int $height
     * @param bool $outer
     */
    public function resize(
        string $source,
        string $filename,
        int $width = 2000,
        int $height = 1500,
        bool $outer = false
    ): void {
        //
        $image = Image::make($source)->orientate();

        if ($outer) { // plan
            if ($image->width() / $image->height() > $width / $height) { // too landscape
                $image->widen($width);
            } else { // too portrait
                $image->heighten($height);
            }
            $image->resizeCanvas($width, $height);
        } else {
            $image->fit($width, $height);
        }

        // Insert Watermark Anyway
        $wmFile = resource_path("img/watermark.png");
        $image->insert($wmFile);

        Storage::disk('s3')->put($filename, $image->stream(), 'public');

        $image->destroy();
    }

    /**
     * @param BlockPhoto $photo
     * @throws FileNotFoundException
     */
    public function changeResize(BlockPhoto $photo): void
    {
        if ($content = Storage::disk('s3')->get($photo->getPhotoName())) {
            $this->resize($content, $photo->getPhotoName(true), 2000, 1500, $photo->tag_id == BlockPhoto::TAG_ID_PLAN);
        }
    }

    /**
     * @param Block $block
     */
    public function deleteDraftPhotos(Block $block): void
    {
        $photos = $block->photosDraft;

        foreach ($photos as $photo) {
            $this->deletePhotoFromStorage($photo);
            $photo->delete();
        }
    }

    /**
     * @param BlockPhoto $photo
     */
    public function deletePhotoFromStorage(BlockPhoto $photo): void
    {
        Storage::disk('s3')->delete($photo->getPhotoName(false));
        Storage::disk('s3')->delete($photo->getPhotoName(true));
    }
}
