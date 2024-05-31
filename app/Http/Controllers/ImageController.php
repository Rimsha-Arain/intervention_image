<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageController extends Controller
{
    // Function to display the file upload form
    public function fileUpload() {
        return view('image-upload');
    }

    // Function to handle image upload and processing
    public function storeImage(Request $request){
        // Validate the uploaded image file
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:800', // Changed max size to 800KB
        ]);

        // Handle the uploaded image
        $image = $request->file('image');

        // Generate a unique name for the image using the current timestamp and the original file extension
        $imageName = time() . '.' . $image->getClientOriginalExtension();

        // Move the uploaded image to the 'uploads' directory
        $image->move('uploads', $imageName);

        // Create a new ImageManager instance with the desired driver
        $imgManager = new ImageManager(new Driver());

        // Read the uploaded image from the 'uploads' directory
        $thumbImage = $imgManager->read('uploads/' . $imageName);

        // Resize and crop the image to 1600x900 pixels
        $thumbImage->cover(1600, 900);

        // Initial quality
        $quality = 90;

        // Save the resized image to the 'uploads/thumbnails' directory
        $response = $thumbImage->save(public_path('uploads/thumbnails/' . $imageName), $quality);

        // Check if the image size is larger than 800KB and reduce quality if necessary
        $maxFileSize = 800 * 1024; // 800KB in bytes
        while (filesize(public_path('uploads/thumbnails/' . $imageName)) > $maxFileSize && $quality > 10) {
            $quality -= 10;
            $response = $thumbImage->save(public_path('uploads/thumbnails/' . $imageName), $quality);
        }

        // Check if the image was saved successfully
        if ($response) {
            return back()->with('success', 'Image uploaded and resized successfully.');
        }
        return back()->with('error', 'Unable to upload and resize image.');
    }
}
