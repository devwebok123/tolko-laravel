<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\BlockPhoto;
use App\Http\Requests\Block\Photo\UploadRequest;
use App\Http\Requests\Block\Photo\BulkDestroyRequest;
use App\Http\Requests\Block\Photo\BulkUpdateRequest;
use App\Http\Requests\Block\Photo\SortableRequest;
use App\Services\Models\BlockPhotoService;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class BlockPhotoController extends Controller
{
    /**
     * @param Block $block
     * @param UploadRequest $request
     * @param BlockPhotoService $service
     *
     * @return JsonResponse
     */
    public function upload(Block $block, UploadRequest $request, BlockPhotoService $service): JsonResponse
    {
        $data = $request->validated();
        $photo = new BlockPhoto;
        $block->photos()->save($photo);

        $service->imageStore($photo, $data['image_file']);

        $photo->refresh();
        $block->load('photos');

        return response()->json($photo);
    }

    /**
     * @param Block $block
     * @param BulkUpdateRequest $request
     *
     * @return JsonResponse
     */
    public function update(Block $block, BulkUpdateRequest $request, BlockPhotoService $service): JsonResponse
    {
        $data = $request->validated()['items'];

        $photos = $block->photos()->whereIn('id', array_keys($data));
        $block->photos()->whereIn('id', array_keys($data))
            ->each(function (BlockPhoto $photo) use ($data, $service) {
                $photo->update($data[$photo->id]);
                if ($photo->getChanges('tag_id')) {
                    $service->changeResize($photo);
                }
            });

        return response()->json($photos->get());
    }

    /**
     * @param Block $block
     * @param SortableRequest $request
     *
     * @return JsonResponse
     */
    public function sort(Block $block, SortableRequest $request): JsonResponse
    {
        $data = $request->validated();

        foreach ($data['ids'] as $rank => $id) {
            BlockPhoto::query()->find($id)->update(['rank' => $rank]);
        }

        $block->load('photos');

        return response()->json([]);
    }

    /**
     * @param Block $block
     * @param BulkDestroyRequest $request
     * @param BlockPhotoService $photoService
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Block $block, BulkDestroyRequest $request, BlockPhotoService $photoService): JsonResponse
    {
        $ids = $request->validated()['ids'];

        $photos = $block->photos()->whereIn('id', $ids);
        if ($photos->each(function (BlockPhoto $photo) use ($photoService) {
            $photoService->deletePhotoFromStorage($photo);
        })) {
            $photos->delete();
        }

        return response()->json($photos->get());
    }
}
