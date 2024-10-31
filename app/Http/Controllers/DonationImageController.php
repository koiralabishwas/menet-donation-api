<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Providers\GoogleDriveProvider;
use Google\Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DonationImageController extends Controller
{
    /**
     * @throws Exception
     */
    public function uploadDonationImage(Request $request): JsonResponse
    {
        $image = $request->file('image');

        $imageName = Helpers::CreateExternalIdfromDate();

        $imageId = GoogleDriveProvider::uploadFile($image, $imageName);

        return response()->json([
            'image_id' => $imageId,
            'image_name' => $imageName,
            'image_status' => 'uploaded',
            'image_url' => "https://drive.google.com/file/d/$imageId/view",
        ]);
    }

    /**
     * @throws Exception
     * @throws \Google\Service\Exception
     */
    public function deleteDonationImage($image_id): JsonResponse
    {
        $imageName = GoogleDriveProvider::delete_file($image_id);

        return response()->json([
            'image_id' => $image_id,
            'image_name' => $imageName,
            'image_status' => 'deleted',
            'image_url' => "https://drive.google.com/file/d/$image_id/view",
        ]);
    }
}
