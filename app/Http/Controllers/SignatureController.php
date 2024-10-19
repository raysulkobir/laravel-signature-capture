<?php

namespace App\Http\Controllers;

use App\Models\Signature;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSignatureRequest;
use App\Http\Requests\UpdateSignatureRequest;


class SignatureController extends Controller
{
    public function store(Request $request)
        {
        // Generate a unique file name for the signature
        $filename = date('mdYHis') . "-signature.png";

        // Save the file name in the database
        $signature = new Signature();
        $signature->file = $filename;
        $signature->save();

        // Decode the base64 encoded image from the request
        $data_uri = $request->signature; // This assumes that the signature comes as a base64 string in the request
        $encoded_image = explode(",", $data_uri)[1]; // Strip the base64 prefix
        $decoded_image = base64_decode($encoded_image); // Decode the base64 string

        // Define the path to store the file in the 'public/signatures' directory
        $file_path = public_path('signatures/' . $filename);

        // Save the image to the specified file path
        file_put_contents($file_path, $decoded_image);

        // Return a success response
        return response()->json(['success' => 'Data is successfully added']);
        }
}
