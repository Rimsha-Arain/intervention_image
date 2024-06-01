<?php

namespace App\Http\Controllers;

use Intervention\Image\ImageManager;
use Intervention\Image\Facades\Image;
use Intervention\Image\Drivers\Gd\Driver;
//use Intervention\Image\Drivers\Imagick\Driver;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function fileUpload()
    {
        return view('image-upload');
    }
    public function storeImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move('uploads', $imageName);

        // Initialize the ImageManager with the GD driver
        $imgManager = new ImageManager(new Driver());

        // Save the original uploaded image
        $originalImage = $imgManager->read(public_path('uploads/' . $imageName));
        $originalImage->save(public_path('uploads/original/' . $imageName));

        // Resize while maintaining aspect ratio
        $height = 1200;
        $width = 1200;
        $thumbImage = $imgManager->read(public_path('uploads/' . $imageName));
        $thumbImage->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $thumbImage->save(public_path('uploads/thumbnails/' . $imageName));

        // Check image size by saving it and getting the file size
        $thumbImage->save(public_path('uploads/' . $imageName));
        $imageSize = filesize(public_path('uploads/' . $imageName));
        if ($imageSize > 800 * 1024) {
            $thumbImage->save(public_path('uploads/' . $imageName), 60);
        } else {
            $thumbImage->save(public_path('uploads/' . $imageName));
        }

        $this->generateThumbnails($imageName);

        // Clean up temporary file
        unlink(public_path('uploads/thumbnails/' . $imageName));

        return redirect()->back()->with('success', 'Image uploaded and processed successfully.');
    }

    private function generateThumbnails($imageName)
    {
        $thumbnailSizes = [
            200 => 'small',
            400 => 'medium',
            800 => 'large',
        ];

        $imgManager = new ImageManager(new Driver());

        // Get the original image dimensions
        $originalImage = $imgManager->read(public_path('uploads/' . $imageName));
        $originalWidth = $originalImage->width();
        $originalHeight = $originalImage->height();
        $aspectRatio = $originalWidth / $originalHeight;

        foreach ($thumbnailSizes as $width => $size) {
            $thumbnailImage = $imgManager->read(public_path('uploads/' . $imageName));

            // Calculate the new height based on the aspect ratio
            $height = $width / $aspectRatio;
            $thumbnailImage->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $thumbnailImage->save(public_path('uploads/thumbnails/' . $size . '_' . $imageName));
        }
    }
}